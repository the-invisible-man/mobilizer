<?php

namespace App\Lib\Packages\Bookings;

use App\Lib\Packages\Bookings\Contracts\AbstractBooking;
use App\Lib\Packages\Bookings\Models\BookingMetadata;
use App\Lib\Packages\Bookings\Models\HomeBooking;
use App\Lib\Packages\Bookings\Models\RideBooking;
use App\Lib\Packages\Geo\Location\LocationGateway;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Database\DatabaseManager;
use Illuminate\Foundation\Application;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\Factory as ValidatorFactory;
use Monolog;

/**
 * Class BookingsGateway
 * @package App\Lib\Packages\Bookings
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
class BookingsGateway {

    /**
     * @var \Illuminate\Database\Connection
     */
    private $db;

    /**
     * @var array
     */
    private $bookingTypes = [
        RideBooking::ListingType => RideBooking::class,
        HomeBooking::ListingType => HomeBooking::class
    ];

    /**
     * @var LocationGateway
     */
    private $locationGateway;

    /**
     * @var ValidatorFactory
     */
    private $validatorFactory;

    /**
     * @var array
     */
    private $required = [
        AbstractBooking::FK_USER_ID     => 'required',
        AbstractBooking::FK_LISTING_ID  => 'required|bookingExists',
        AbstractBooking::TOTAL_PEOPLE   => 'required|numeric|min:1|validateTotalPeople',
        AbstractBooking::TYPE           => 'required|bookingType',
        'location'                      => 'required-if:type,R'
    ];

    /**
     * BookingsGateway constructor.
     * @param DatabaseManager $databaseManager
     * @param LocationGateway $locationGateway
     * @param ValidatorFactory $validatorFactory
     * @param Application $app
     * @param Log $log
     */
    public function __construct(DatabaseManager $databaseManager, LocationGateway $locationGateway, ValidatorFactory $validatorFactory, Application $app, Log $log)
    {
        $this->db               = $databaseManager->connection();
        $this->locationGateway  = $locationGateway;
        $this->validatorFactory = $validatorFactory;
        $this->app              = $app;
        $this->log              = $log;
    }

    /**
     * @param $data
     * @return \Illuminate\Validation\Validator
     */
    private function validator($data)
    {
        $slotsRemaining = 0;
        $db = $this->db;

        $this->validatorFactory->extend('bookingType', function ($attribute, $value)
        {
            return isset($this->bookingTypes[$value]);
        }, "Invalid booking type. Allowed only: [" . implode(',', array_keys($this->bookingTypes)) . "]");

        $this->validatorFactory->extend('bookingExists', function ($attribute, $value) use($db)
        {
            return $db->table('listings')->where('id', '=', $value)->exists();
        }, "Listing id does not exist: " . array_get($data, 'fk_listing_id', 'none'));

        $this->validatorFactory->extend('validateTotalPeople', function ($attribute, $value) use($data, &$slotsRemaining)
        {
            $slotsRemaining = (int)$this->remainingSlots(array_get($data, 'fk_listing_id', '1'));
            return ((int)$value) <= $slotsRemaining;
        }, 'Invalid number of people. Tried to reserve ' . $data['total_people'] . ' people but there\'s only ' . $slotsRemaining);

        return $this->validatorFactory->make($data, $this->required);
    }

    /**
     * @param string $id
     * @return int
     */
    public function remainingSlots($id) : int
    {
        $max =  (int)$this->db->table('listings')
                                ->where('id', '=', $id)
                                ->value('max_occupants');

        $taken = (int)$this->db->table('bookings')
                                ->where(AbstractBooking::FK_LISTING_ID, '=', $id)
                                ->where(AbstractBooking::STATUS, '=', AbstractBooking::STATUS_ACCEPTED)
                                ->sum(AbstractBooking::TOTAL_PEOPLE);

        return ($max - $taken);
    }

    /**
     * @param array $data
     * @return AbstractBooking
     * @throws \Exception
     */
    public function create(array $data)
    {
        // Shit's about to get hacky af. I have 7 days or maybe 10.
        $val = $this->validator($data);

        if ($val->fails()) {
            $bag = new MessageBag($val->failed());
            throw new ValidationException($bag);
        }

        $this->db->beginTransaction();

        try {
            $booking = $this->processNew($data);
        } catch(\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }

        $this->db->commit();

        return $booking;
    }

    /**
     * @param array $data
     * @return AbstractBooking
     */
    private function processNew(array $data)
    {
        /**
         * @var AbstractBooking $booking
         */
        $booking  = new $this->bookingTypes[$data['type']]($data);

        $booking->setTotalPeople($data[AbstractBooking::TOTAL_PEOPLE]);
        $booking->setFKListingId($data[AbstractBooking::FK_LISTING_ID]);

        $booking->save();

        $metadata = new BookingMetadata();
        $metadata->setFkBookingId($booking->getId());
        $metadata->setBringsCat(array_get($data, BookingMetadata::BRINGS_CAT, false));
        $metadata->setBringsDog(array_get($data, BookingMetadata::BRINGS_DOG, false));

        if ($booking instanceof RideBooking) {
            $location = $this->locationGateway->create($data['location']);
            $metadata->setFkLocationId($location->getId());
        }

        $metadata->save();

        $booking->setMetadata($metadata);

        return $booking;
    }

    /**
     * @param string $user
     * @return array
     */
    public function getUserBookings(string $user)
    {
        $data = (array)$this->db->table('bookings as a')
            ->join('bookings_metadata as b', 'b.fk_booking_id', '=', 'a.id')
            ->join('listings as c', 'c.id', '=', 'a.fk_listing_id')
            ->join('listings_metadata as d', 'd.fk_listing_id', '=', 'c.id')
            ->join('locations as e', 'e.id', '=', 'b.fk_location_id', 'left')
            ->join('locations as f', 'f.id', '=', 'c.fk_location_id')
            ->where('a.fk_user_id', '=', $user)
            ->where('c.active', '=', 1)
            ->get([
                // bookings
                'a.id',
                'a.status',
                'a.type',
                'a.additional_info',
                'a.total_people',

                // bookings_metadata
                'b.brings_dog',
                'b.brings_cat',

                // listing
                'c.id as listing_id',
                'c.starting_date',
                'c.ending_date',
                'c.party_name',
                'c.additional_info as listing_additional_info',

                // listing_metadata
                'd.time_of_day',

                // booking location
                'e.id as booking_location_id',
                'e.street as booking_location_street',
                'e.city as booking_location_city',
                'e.state as booking_location_state',
                'e.zip as booking_location_zip',
                'e.country as booking_location_country',

                // listing location
                'f.id as listing_location_id',
                'f.street as listing_location_street',
                'f.city as listing_location_city',
                'f.state as listing_location_state',
                'f.zip as listing_location_zip',
                'f.country as listing_location_country'
            ]);

        if (!is_array($data) || ! count($data)) return [];

        $ouput = [];
        // Format data
        for ($i = 0; $i < count($data); $i++) {
            // Build booking data
            $ouput[$i] = [
                'id'                => $data[$i][AbstractBooking::ID],
                'total_people'      => $data[$i][AbstractBooking::TOTAL_PEOPLE],
                'status'            => $data[$i][AbstractBooking::STATUS],
                'additional_info'   => $data[$i][AbstractBooking::ADDITIONAL_INFO],
                'type'              => $data[$i][AbstractBooking::TYPE],
                'brings_cat'        => (bool)$data[$i][BookingMetadata::BRINGS_DOG],
                'brings_dog'        => (bool)$data[$i][BookingMetadata::BRINGS_CAT],
                'listing'           => [
                    'id'                => $data[$i]['listing_id'],
                    'party_name'        => $data[$i]['party_name'],
                    'starting_date'     => $data[$i]['starting_date'],
                    'ending_date'       => $data[$i]['ending_date'],
                    'additional_info'   => $data[$i]['listing_additional_info'],
                    'remainingSlots'    => $this->remainingSlots($data[$i]['listing_id']),
                    'time_of_day'       => $data[$i]['time_of_day'],
                    'location'          => [
                        'id'        => $data[$i]['listing_location_id'],
                        'street'    => $data[$i]['listing_location_street'],
                        'city'      => $data[$i]['listing_location_city'],
                        'state'     => $data[$i]['listing_location_state'],
                        'zip'       => $data[$i]['listing_location_zip'],
                        'country'   => $data[$i]['listing_location_country']
                    ]
                ]
            ];

            // Check if it's ride, if so the booking should have
            // a location associated to it
            if ($data[$i][AbstractBooking::TYPE] == RideBooking::ListingType) {
                $ouput[$i]['user_location'] = [
                    'id'        => $data[$i]['booking_location_id'],
                    'street'    => $data[$i]['booking_location_street'],
                    'city'      => $data[$i]['booking_location_city'],
                    'state'     => $data[$i]['booking_location_state'],
                    'zip'       => $data[$i]['booking_location_zip'],
                    'country'   => $data[$i]['booking_location_country']
                ];
            }

        }

        return $ouput;
    }

    /**
     * @param string $bookingId
     * @param string $userId
     * @return bool
     */
    public function ownsBooking(string $bookingId, string $userId)
    {
        return $this->db->table('bookings')
            ->where(AbstractBooking::ID, '=', $bookingId)
            ->where(AbstractBooking::FK_USER_ID, '=', $userId)
            ->exists();
    }

    /**
     * @param string $bookingId
     */
    public function reject(string $bookingId)
    {
        $this->db->table('bookings')
            ->where(AbstractBooking::ID, '=', $bookingId)
            ->update([AbstractBooking::STATUS => AbstractBooking::STATUS_DENIED]);
    }

    /**
     * @param string $bookingId
     */
    public function accept(string $bookingId)
    {
        $this->db->table('bookings')
            ->where(AbstractBooking::ID, '=', $bookingId)
            ->update([AbstractBooking::STATUS => AbstractBooking::STATUS_ACCEPTED]);
    }

    /**
     * @param string $bookingId
     */
    private function cancel(string $bookingId)
    {
        // User has to own booking
    }
}