<?php

namespace App\Lib\Packages\Bookings;

use App\Lib\Packages\Bookings\Exceptions\InvalidNumberOfPeopleException;
use App\Lib\Packages\Bookings\Exceptions\BookingNotFoundException;
use App\Lib\Packages\Bookings\Exceptions\InactiveBookingException;
use App\Lib\Packages\Bookings\Exceptions\MismatchException;
use App\Lib\Packages\Bookings\Models\BookingMetadata;
use App\Lib\Packages\Bookings\Models\HomeBooking;
use App\Lib\Packages\Bookings\Models\RideBooking;
use App\Lib\Packages\Geo\Location\LocationGateway;
use App\Lib\Packages\Geo\TimeEstimation\TripDurationEstimator;
use App\Lib\Packages\Listings\Contracts\BaseListing;
use App\Lib\Packages\Listings\ListingsGateway;
use App\Lib\Packages\Listings\Models\ListingMetadata;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Database\DatabaseManager;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\Factory as ValidatorFactory;
use App\Lib\Packages\Bookings\Contracts\BaseBooking;
use App\User;
use Monolog;


/**
 * Class BookingsGateway
 * @package App\Lib\Packages\Bookings
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
class BookingsGateway {

    use DispatchesJobs;

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
     * @var ListingsGateway
     */
    private $listingsGateway;

    /**
     * @var ValidatorFactory
     */
    private $validatorFactory;

    /**
     * @var array
     */
    private $required = [
        BaseBooking::FK_USER_ID         => 'required',
        BaseBooking::FK_LISTING_ID      => 'required|listingExists|listingIsActive|alreadyBooked',
        BaseBooking::TOTAL_PEOPLE       => 'required|numeric|min:1|validateTotalPeople',
        BaseBooking::TYPE               => 'required|bookingType',
        BaseBooking::ADDITIONAL_INFO    => 'required|min:50',
        'location'                      => 'required-if:type,R'
    ];

    /**
     * @var User
     */
    private $user = null;

    /**
     * @var Log
     */
    private $app;

    /**
     * @var Application
     */
    private $log;

    /**
     * @var TripDurationEstimator
     */
    private $tripDurationEstimator;

    /**
     * BookingsGateway constructor.
     * @param DatabaseManager $databaseManager
     * @param LocationGateway $locationGateway
     * @param ValidatorFactory $validatorFactory
     * @param Application $app
     * @param Log $log
     * @param ListingsGateway $listingsGateway
     * @param TripDurationEstimator $tripDurationEstimator
     */
    public function __construct(DatabaseManager $databaseManager, LocationGateway $locationGateway, ValidatorFactory $validatorFactory, Application $app, Log $log, ListingsGateway $listingsGateway, TripDurationEstimator $tripDurationEstimator)
    {
        $this->db               = $databaseManager->connection();
        $this->locationGateway  = $locationGateway;
        $this->validatorFactory = $validatorFactory;
        $this->app              = $app;
        $this->log              = $log;
        $this->listingsGateway  = $listingsGateway;

        $this->tripDurationEstimator = $tripDurationEstimator;
    }

    /**
     * @param array $data
     * @param array $rules
     * @return \Illuminate\Validation\Validator
     */
    private function validator(array $data, array $rules)
    {
        $slotsRemaining = 0;
        $db             = $this->db;

        $this->validatorFactory->extend('bookingType', function ($attribute, $value)
        {
            return isset($this->bookingTypes[$value]);
        }, "Invalid booking type. Allowed only: [" . implode(',', array_keys($this->bookingTypes)) . "]");

        $this->validatorFactory->extend('listingExists', function ($attribute, $value) use($db)
        {
            return $db->table('listings')->where('id', '=', $value)->exists();
        }, "Listing id does not exist: " . array_get($data, 'fk_listing_id', 'none'));

        $this->validatorFactory->extend('validateTotalPeople', function ($attribute, $value) use($data, &$slotsRemaining)
        {
            $slotsRemaining = (int)$this->remainingSlots(array_get($data, 'fk_listing_id', '1'));
            return ((int)$value) <= $slotsRemaining;
        }, 'Invalid number of people. Tried to reserve ' . $data['total_people'] . ' people but there\'s only ' . $slotsRemaining);

        $this->validatorFactory->extend('listingIsActive', function ($attribute, $value) use($db, $data)
        {
            return (bool) $db->table('listings')->where(BaseListing::ID, '=', $value)->value('active');
        }, "Listing {$data['fk_listing_id']} is no longer active. Cannot book for this listing.");

        $this->validatorFactory->extend('alreadyBooked', function ($attribute, $value) use($db, $data)
        {
            return ! $db->table('bookings')->where('fk_user_id', '=', $data['fk_user_id'])->where('fk_listing_id', '=', $value)->exists();
        }, "A booking request has already been sent for this listing, cannot submit duplicate.");

        return $this->validatorFactory->make($data, $rules);
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setCurrentUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return User $user
     */
    public function getCurrentUser()
    {
        if (is_null($this->user)){
            throw new \InvalidArgumentException("Cannot provide a user object because a user object has not been set");
        }
        return $this->user;
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
                               ->where(BaseBooking::FK_LISTING_ID, '=', $id)
                               ->where(BaseBooking::STATUS, '=', BaseBooking::STATUS_ACCEPTED)
                               ->where(BaseBooking::ACTIVE, '=', 1)
                               ->sum(BaseBooking::TOTAL_PEOPLE);

        return ($max - $taken);
    }

    /**
     * @param BaseBooking $booking
     * @param int $totalPeople
     * @throws InvalidNumberOfPeopleException
     */
    private function validateTotalPeopleForEdit(BaseBooking $booking, int $totalPeople)
    {
        $remaining = $this->remainingSlots($booking->getFkListingId()) + $booking->getTotalPeople();

        if ($totalPeople > $remaining) {
            throw new InvalidNumberOfPeopleException("Cannot edit total number of people to {$totalPeople}. There's only {$remaining} slots available");
        }
    }


    /**
     * Oh this will surpass all the hacks
     *
     * @param string $bookingId
     * @param array $data
     * @return BaseBooking
     * @throws BookingNotFoundException
     */
    public function edit(string $bookingId, array $data)
    {
        $this->db->beginTransaction();

        $editable   = ['total_people', 'additional_info'];
        $edits      = array_intersect_key($data, array_flip($editable));
        /**
         * @var BaseBooking $booking
         */
        $booking    = BaseBooking::find($bookingId);

        if (!$booking) {
            throw new BookingNotFoundException("Booking id {$bookingId}, no such booking exists");
        }

        /**
         * @var ListingMetadata $listingMetadata
         */
        $listingMetadata = ListingMetadata::where(ListingMetadata::FK_LISTING_ID, $booking->getFkListingId())->first();

        foreach ($edits as $column => $val) {
            if ($column == 'total_people') {
                $this->validateTotalPeopleForEdit($booking, $val);
            }
            $setter = 'set' . str_replace('_', '', $column);
            $booking->{$setter}($val);
        }

        $booking->save();

        $editable   = ['brings_dog', 'brings_cat'];
        $edits      = array_intersect_key($data, array_flip($editable));
        $metadata   = BookingMetadata::where(BookingMetadata::FK_BOOKING_ID, $booking->getId())->first();

        foreach ($edits as $column => $val) {
            if ($column == 'brings_dog' && (bool)$val &&  ! $listingMetadata->isDogFriendly()) {
                throw new \InvalidArgumentException("Opted to bring dog but listing does not allow dogs");
            } elseif ($column == 'brings_cat' && (bool)$val && ! $listingMetadata->isCatFriendly()) {
                throw new \InvalidArgumentException("Opted to bring cat but listing does not allow cats");
            }

            $setter = 'set' . str_replace('_', '', $column);
            $metadata->{$setter}($data[$column]);
        }

        $metadata->save();

        $booking->setMetadata($metadata);

        $this->db->commit();

        return $booking;
    }

    /**
     * @param array $data
     * @return BaseBooking
     * @throws \Exception
     */
    public function create(array $data)
    {
        // Shit's about to get hacky af. I have 7 days or maybe 10.
        $val = $this->validator($data, $this->required);

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
     * @return BaseBooking
     */
    private function processNew(array $data)
    {
        /**
         * @var BaseBooking $booking
         */
        $booking  = new $this->bookingTypes[$data['type']]($data);

        $booking->setTotalPeople($data[BaseBooking::TOTAL_PEOPLE]);
        $booking->setFKListingId($data[BaseBooking::FK_LISTING_ID]);

        $booking->save();

        $metadata = new BookingMetadata();
        $metadata->setFkBookingId($booking->getId());
        $metadata->setBringsCat(array_get($data, BookingMetadata::BRINGS_CAT, false));
        $metadata->setBringsDog(array_get($data, BookingMetadata::BRINGS_DOG, false));

        if ($booking instanceof RideBooking) {
            $location = $this->locationGateway->create($data['location']);
            $metadata->setFkLocationId($location->getId());
            $metadata->setLocation($location);
        }

        $metadata->save();

        $booking->setMetadata($metadata);

        return $booking;
    }

    /**
     * @return array
     */
    private function getSelectColumns()
    {
        return [
            // bookings
            'a.id',
            'a.status',
            'a.type',
            'a.additional_info',
            'a.total_people',
            'a.created_at',

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
            'f.country as listing_location_country',

            // listing owner info
            'g.first_name as host_first_name',
            'g.last_name as host_last_name',
        ];
    }

    /**
     * @return array
     */
    private function bookingOwnerColumns()
    {
        return [
            'h.first_name as guest_first_name',
            'h.last_name as guest_last_name'
        ];
    }

    /**
     * @param string $bookingId
     * @return array|null
     */
    public function find(string $bookingId)
    {
        $data = (array)$this->db->table('bookings as a')
            ->join('bookings_metadata as b', 'b.fk_booking_id', '=', 'a.id')
            ->join('listings as c', 'c.id', '=', 'a.fk_listing_id')
            ->join('listings_metadata as d', 'd.fk_listing_id', '=', 'c.id')
            ->join('locations as e', 'e.id', '=', 'b.fk_location_id', 'left')
            ->join('locations as f', 'f.id', '=', 'c.fk_location_id')
            ->where('a.id', '=', $bookingId)
            ->first($this->getSelectColumns());

        if (!is_array($data) || ! count($data)) return null;

        return $this->formatBookingResult($data);
    }

    /**
     * @param string $userId
     * @param bool $activeOnly
     * @return array
     */
    public function getUserBookings(string $userId = null, $activeOnly = true)
    {
        $user = ! is_null($userId) ?: $this->getCurrentUser()->getId();

        $data = $this->db->table('bookings as a')
            ->join('bookings_metadata as b', 'b.fk_booking_id', '=', 'a.id')
            ->join('listings as c', 'c.id', '=', 'a.fk_listing_id')
            ->join('listings_metadata as d', 'd.fk_listing_id', '=', 'c.id')
            ->join('locations as e', 'e.id', '=', 'b.fk_location_id', 'left')
            ->join('locations as f', 'f.id', '=', 'c.fk_location_id')
            ->join('users as g', 'c.fk_user_id', '=', 'g.id')
            ->where('a.fk_user_id', '=', $user)
            ->where('c.active', '=', 1);

        if ($activeOnly) {
            $data->where('a.active', '=', 1);
        }

        $data = (array)$data->get($this->getSelectColumns());

        if (!is_array($data) || ! count($data)) return [];

        $ouput = [];
        // Format data
        foreach ($data as $booking) {
            // Build booking data
            $ouput[] = $this->formatBookingResult($booking);

        }

        return $ouput;
    }

    /**
     * @param array $booking
     * @return array
     */
    private function formatBookingResult(array $booking)
    {
        $data = [
            'id'                => $booking[BaseBooking::ID],
            'total_people'      => $booking[BaseBooking::TOTAL_PEOPLE],
            'status'            => $booking[BaseBooking::STATUS],
            'additional_info'   => $booking[BaseBooking::ADDITIONAL_INFO],
            'type'              => $booking[BaseBooking::TYPE],
            'brings_cat'        => (bool)$booking[BookingMetadata::BRINGS_DOG],
            'brings_dog'        => (bool)$booking[BookingMetadata::BRINGS_CAT],
            'date_submitted'    => (new \DateTime($booking[BaseBooking::CREATED_AT]))->format('M d, Y'),
            'listing'           => [
                'id'                => $booking['listing_id'],
                'host_first_name'   => $booking['host_first_name'],
                'host_last_name'    => $booking['host_last_name'],
                'party_name'        => $booking['party_name'],
                'starting_date'     => $booking['starting_date'],
                'ending_date'       => $booking['ending_date'],
                'additional_info'   => $booking['listing_additional_info'],
                'remainingSlots'    => $this->remainingSlots($booking['listing_id']),
                'time_of_day'       => ListingMetadata::translateTimeOfDay($booking['time_of_day']),
                'location'          => [
                    'id'        => $booking['listing_location_id'],
                    'street'    => $booking['listing_location_street'],
                    'city'      => $booking['listing_location_city'],
                    'state'     => $booking['listing_location_state'],
                    'zip'       => $booking['listing_location_zip'],
                    'country'   => $booking['listing_location_country']
                ]
            ]
        ];

        // Check if it's ride, if so the booking should have
        // a location associated to it
        if ($booking[BaseBooking::TYPE] == RideBooking::ListingType) {
            $data['user_location'] = [
                'id'        => $booking['booking_location_id'],
                'street'    => $booking['booking_location_street'],
                'city'      => $booking['booking_location_city'],
                'state'     => $booking['booking_location_state'],
                'zip'       => $booking['booking_location_zip'],
                'country'   => $booking['booking_location_country']
            ];

            // SO FUCKING HACKY
            $origin             = $booking['listing_location_city'] . ', ' . $booking['listing_location_state'];
            $dest               = $booking['booking_location_city'] . ', ' . $booking['booking_location_state'];

            // Let's add the trip estimate
            $departureTime      = ListingMetadata::translateTimeOfDay($booking['time_of_day'], true);
            $departureDate      = new \DateTime($booking['starting_date']);

            $departureTime->setDate($departureDate->format('Y'), $departureDate->format('m'), $departureDate->format('d'));

            $pickup                 = $this->tripDurationEstimator->estimateArrivalDateTime($origin, $dest, $departureTime);
            $data['pickup_date']    = $pickup->format('M d, Y');
            $data['pickup_time']    = $pickup->format('h:i A');
        }

        // Check if booking owner is set
        if (isset($booking['guest_first_name']) and isset($booking['guest_last_name'])) {
            $data['user'] = [
                'first_name' => $booking['guest_first_name'],
                'last_name' => $booking['guest_last_name']
            ];
        }

        return $data;
    }

    /**
     * @param string $bookingId
     * @param string $userId
     * @return bool
     */
    public function ownsBooking(string $bookingId, string $userId)
    {
        return $this->db->table('bookings')
            ->where(BaseBooking::ID, '=', $bookingId)
            ->where(BaseBooking::FK_USER_ID, '=', $userId)
            ->exists();
    }

    /**
     * User has to own listing associated with booking
     *
     * @param string $bookingId
     * @param string $currentUserId
     * @return BaseBooking
     * @throws BookingNotFoundException
     * @throws MismatchException
     */
    public function listingOwnerToBookingValidation(string $bookingId, string $currentUserId)
    {
        /**
         * @var BaseBooking $booking
         */
        $booking = BaseBooking::find($bookingId);

        // Make sure booking is valid
        if (!$booking) {
            throw new BookingNotFoundException("Booking id: '{$bookingId}' - No such booking exists'");
        }

        // User must own listing associated with booking
        $owns = $this->listingsGateway->ownsListing($booking->getFkListingId(), $currentUserId);

        if (!$owns) {
            throw new MismatchException("User to listing to booking mismatch. User {$currentUserId} does not own listing {$booking->getFkListingId()}");
        }

        return $booking;
    }

    /**
     * User has to own booking
     *
     * @param string $bookingId
     * @param string $currentUserId
     * @return BaseBooking
     * @throws BookingNotFoundException
     * @throws MismatchException
     */
    public function bookingOwnerActionValidator(string $bookingId, string $currentUserId)
    {
        /**
         * @var BaseBooking $booking
         */
        $booking = BaseBooking::find($bookingId);

        // Make sure booking is valid
        if (!$booking) {
            throw new BookingNotFoundException("Booking id: '{$bookingId}' - No such booking exists'");
        }

        if ($booking->getFKUserId() !== $currentUserId) {
            throw new MismatchException("Booking to user mismatch. User {$currentUserId} does not own booking {$booking->getFkListingId()}, therefore you cannot do whatever the fuck sketchy shit you were trying to do.");
        }

        return $booking;
    }

    /**
     * @param string $bookingId
     * @param string $currentUserId
     * @return bool
     * @throws BookingNotFoundException
     * @throws MismatchException
     */
    public function reject(string $bookingId, string $currentUserId)
    {
        // This validation should probably not be in here, but as I mentioned
        // previously: #OneManDevTeam $tbs->#WeGot7DaysToWorkWith
        $this->listingOwnerToBookingValidation($bookingId, $currentUserId);

        $this->db->table('bookings')
            ->where(BaseBooking::ID, '=', $bookingId)
            ->update([BaseBooking::STATUS => BaseBooking::STATUS_REJECTED]);
    }

    /**
     * @param string $bookingId
     * @param string $currentUserId
     * @throws BookingNotFoundException
     * @throws MismatchException
     * @throws InactiveBookingException
     */
    public function accept(string $bookingId, string $currentUserId = null)
    {
        $currentUserId  = ! is_null($currentUserId) ?: $this->getCurrentUser()->getId();
        $booking        = $this->listingOwnerToBookingValidation($bookingId, $currentUserId);

        if (!$booking->isActive()) {
            throw new InactiveBookingException("Cannot accept inactive booking");
        }

        $this->db->table('bookings')
            ->where(BaseBooking::ID, '=', $bookingId)
            ->update([BaseBooking::STATUS => BaseBooking::STATUS_ACCEPTED]);
    }

    /**
     * @param string $bookingId
     * @return BaseBooking
     * @throws BookingNotFoundException
     * @throws MismatchException
     */
    public function cancel(string $bookingId)
    {
        $booking = $this->bookingOwnerActionValidator($bookingId, $this->getCurrentUser()->getId());

        $this->db->table('bookings')
            ->where(BaseBooking::ID, '=', $bookingId)
            ->update([BaseBooking::ACTIVE => 0]);

        return $booking;
    }

    /**
     * @param string $userId
     * @return array
     */
    public function getPendingBookingRequests(string $userId)
    {
        $data = (array)$this->db->table('bookings as a')
            ->join('bookings_metadata as b', 'b.fk_booking_id', '=', 'a.id')
            ->join('listings as c', 'c.id', '=', 'a.fk_listing_id')
            ->join('listings_metadata as d', 'd.fk_listing_id', '=', 'c.id')
            ->join('locations as e', 'e.id', '=', 'b.fk_location_id', 'left')
            ->join('locations as f', 'f.id', '=', 'c.fk_location_id')
            ->join('users as g', 'g.id', '=', 'a.fk_user_id')
            ->join('users as h', 'h.id', '=', 'a.fk_user_id')
            ->where('a.fk_user_id', '=', $userId)
            ->where('a.status', '=', BaseBooking::STATUS_PENDING)
            ->where('a.active', '=', 1)
            ->orderBy('c.id')
            ->get(array_merge($this->getSelectColumns(), $this->bookingOwnerColumns()));

        if (!is_array($data) || ! count($data)) return [];

        $ouput = [];
        // Format data
        foreach ($data as $booking) {
            // Build booking data
            $ouput[] = $this->formatBookingResult($booking);
        }

        return $ouput;
    }

    /**
     * @param string $userId
     * @return array
     */
    public function getAcceptedBookingRequests(string $userId)
    {
        $data = (array)$this->db->table('bookings as a')
            ->join('bookings_metadata as b', 'b.fk_booking_id', '=', 'a.id')
            ->join('listings as c', 'c.id', '=', 'a.fk_listing_id')
            ->join('listings_metadata as d', 'd.fk_listing_id', '=', 'c.id')
            ->join('locations as e', 'e.id', '=', 'b.fk_location_id', 'left')
            ->join('locations as f', 'f.id', '=', 'c.fk_location_id')
            ->join('users as g', 'g.id', '=', 'a.fk_user_id')
            ->join('users as h', 'h.id', '=', 'a.fk_user_id')
            ->where('a.fk_user_id', '=', $userId)
            ->where('a.status', '=', BaseBooking::STATUS_ACCEPTED)
            ->where('a.active', '=', 1)
            ->orderBy('c.id')
            ->get(array_merge($this->getSelectColumns(), $this->bookingOwnerColumns()));

        if (!is_array($data) || ! count($data)) return [];

        $ouput = [];
        // Format data
        foreach ($data as $booking) {
            // Build booking data
            $ouput[] = $this->formatBookingResult($booking);
        }

        return $ouput;
    }


    /**
     * @param string $userId
     * @return array
     */
    public function getAllBookingRequests(string $userId)
    {
        $data = (array)$this->db->table('bookings as a')
            ->join('bookings_metadata as b', 'b.fk_booking_id', '=', 'a.id')
            ->join('listings as c', 'c.id', '=', 'a.fk_listing_id')
            ->join('listings_metadata as d', 'd.fk_listing_id', '=', 'c.id')
            ->join('locations as e', 'e.id', '=', 'b.fk_location_id', 'left')
            ->join('locations as f', 'f.id', '=', 'c.fk_location_id')
            ->join('users as g', 'g.id', '=', 'a.fk_user_id')
            ->join('users as h', 'h.id', '=', 'a.fk_user_id')
            ->where('a.fk_user_id', '=', $userId)
            ->where('a.active', '=', 1)
            ->get(array_merge($this->getSelectColumns(), $this->bookingOwnerColumns()));

        if (!is_array($data) || ! count($data)) return [];

        $ouput = [];
        // Format data
        foreach ($data as $booking) {
            // Build booking data
            $ouput[] = $this->formatBookingResult($booking);
        }

        return $ouput;
    }
}