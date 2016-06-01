<?php

namespace App\Lib\Packages\Bookings;

use App\Lib\Packages\Bookings\Contracts\AbstractBooking;
use App\Lib\Packages\Bookings\Models\BookingMetadata;
use App\Lib\Packages\Bookings\Models\HomeBooking;
use App\Lib\Packages\Bookings\Models\RideBooking;
use App\Lib\Packages\Geo\Location\LocationGateway;
use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\MessageBag;

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
     * @var array
     */
    private $required = [
        'fk_user_id'    => 'required',
        'fk_listing_id' => 'required',
        'total_people'  => 'required|numeric|min:1',
        'type'          => 'required|bookingType',
        'location'      => 'required-if:type,R'
    ];

    /**
     * BookingsGateway constructor.
     * @param DatabaseManager $databaseManager
     * @param LocationGateway $locationGateway
     */
    public function __construct(DatabaseManager $databaseManager, LocationGateway $locationGateway)
    {
        $this->db = $databaseManager->connection();
        $this->locationGateway = $locationGateway;
    }

    /**
     * @param $data
     * @return \Illuminate\Validation\Validator
     */
    private function validator($data)
    {
        \Validator::extend('bookingType', function ($value) {
            return isset($this->bookingTypes[$value]);
        }, "Invalid booking type. Allowed only: [" . implode(',', array_keys($this->bookingTypes)) . "]");

        return \Validator::make($data, $this->required);
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

        $booking->save();


        $metadata = new BookingMetadata($data);
        $metadata->setFkBookingId($booking->getId());

        if (isset($data['location'])) {
            $location = $this->locationGateway->create($data['location']);
            $metadata->setFkLocationId($location->getId());
        }

        $metadata->save();

        $booking->setMetadata($metadata);

        return $booking;
    }
}