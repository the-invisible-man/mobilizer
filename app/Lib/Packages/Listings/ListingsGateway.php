<?php

namespace App\Lib\Packages\Listings;

use App\Lib\Packages\EmailRelay\RelayGateway;
use App\User;
use App\Lib\Packages\Geo\Location\Location;
use App\Lib\Packages\Geo\Location\LocationGateway;
use App\Lib\Packages\Geo\TimeEstimation\TripDurationEstimator;
use App\Lib\Packages\Listings\Exceptions\ListingNotFoundException;
use App\Lib\Packages\Listings\ListingDrivers\RideMetadataDriver;
use App\Lib\Packages\Listings\ListingTypes\HomeListing;
use App\Lib\Packages\Listings\ListingTypes\RideListing;
use App\Lib\Packages\Listings\Models\ListingMetadata;
use App\Lib\Packages\Listings\Models\ListingRoute;
use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\MySqlConnection;
use App\Lib\Packages\Listings\Contracts\BaseListing;
use App\Lib\Packages\Listings\ListingDrivers\AbstractMetadataDriver;
use Illuminate\Foundation\Application;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\Factory as ValidatorFactory;
use App\Lib\Packages\Listings\ListingDrivers\HomeMetadataDriver;
use App\Lib\Packages\Bookings\Contracts\BaseBooking;

/**
 * Class ListingsGateway
 * @package App\Lib\Packages\Listings
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
class ListingsGateway {

    /**
     * @var MySqlConnection
     */
    private $db;

    /**
     * @var LocationGateway
     */
    private $locationsGateway;

    /**
     * @var string[]
     */
    private $listingTypes = [
        RideListing::ListingType => RideListing::class,
        HomeListing::ListingType => HomeListing::class
    ];

    /**
     * @var string[]
     */
    private $listingDrivers = [
        RideListing::ListingType => RideMetadataDriver::class,
        HomeListing::ListingType => HomeMetadataDriver::class
    ];

    /**
     * @var AbstractMetadataDriver[]
     */
    private $drivers = [];

    /**
     * @var string
     */
    private $currentDriver;

    /**
     * @var ValidatorFactory
     */
    private $validator;

    /**
     * @var TripDurationEstimator
     */
    private $tripDurationEstimator;

    /**
     * @var array
     */
    private $required = [
        'fk_user_id',
        'party_name',
        'type',
        'starting_date',
        'ending_date',
        'max_occupants',
        'additional_info',
        'location'
    ];

    /**
     * @var array
     */
    private $editsRule = [
        'party_name'        => 'min:5',
        'additional_info'   => 'min:10'
    ];

    /**
     * @var Application
     */
    protected $kernel;

    /**
     * @var User
     */
    private $user;

    /**
     * @var RelayGateway
     */
    private $relayGateway;

    /**
     * ListingsGateway constructor.
     * @param DatabaseManager $databaseManager
     * @param LocationGateway $locationGateway
     * @param Application $app
     * @param ValidatorFactory $validator
     * @param TripDurationEstimator $tripDurationEstimator
     * @param RelayGateway $relayGateway
     */
    public function __construct(DatabaseManager $databaseManager, LocationGateway $locationGateway, Application $app, ValidatorFactory $validator, TripDurationEstimator $tripDurationEstimator, RelayGateway $relayGateway)
    {
        $this->db                       = $databaseManager->connection();
        $this->locationsGateway         = $locationGateway;
        $this->kernel                   = $app;
        $this->validator                = $validator;
        $this->tripDurationEstimator    = $tripDurationEstimator;
        $this->relayGateway             = $relayGateway;
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
     * @param array $data
     * @return BaseListing
     */
    public function create(array $data) : BaseListing
    {
        $this->validateRequiredForNew($data);

        return $this->list($data['type'])->with($data);
    }

    /**
     * @param $type
     * @return $this
     */
    private function list($type)
    {
        $this->currentDriver = $type;
        return $this;
    }

    /**
     * @param array $data
     * @return BaseListing
     * @throws \Exception
     */
    private function with(array $data) : BaseListing
    {
        // We're gonna wrap these writes in a transaction
        $this->db->beginTransaction();

        try {
            $listing = $this->insert($data);
            // Use driver to process listing type specific metadata
            $this->getDriver()->process($listing, $data);
        } catch(\Exception $e) {
            // Whoa, no no
            $this->db->rollBack();
            throw $e;
        }

        // We're done
        $this->db->commit();

        return $listing;
    }

    /**
     * @param array $data
     * @return BaseListing
     */
    private function insert(array $data) : BaseListing
    {
        /**
         * @var BaseListing $listing
         */
        $listing    = new $this->listingTypes[$data['type']];
        $location   = $this->locationsGateway->create($data['location']);

        $listing->setLocation($location);
        $data[BaseListing::FK_LOCATION_ID] = $location->getId();

        $listing->setFkUserId($data[BaseListing::FK_USER_ID]);
        $listing->setFkLocationId($data[BaseListing::FK_LOCATION_ID]);
        $listing->setPartyName($data[BaseListing::PARTY_NAME]);
        $listing->setType($data[BaseListing::TYPE]);
        $listing->setMaxOccupants($data[BaseListing::MAX_OCCUPANTS]);
        $listing->setAdditionalInfo($data[BaseListing::ADDITIONAL_INFO]);

        $startingDate = new \DateTime($data[BaseListing::STARTING_DATE]);
        $endingDate   = new \DateTime($data[BaseListing::ENDING_DATE]);

        $listing->setStartingDate($startingDate);
        $listing->setEndingDate($endingDate);

        $listing->save();

        return $listing;
    }

    /**
     * @param string $listingId
     * @param string $userId
     * @return bool
     */
    public function ownsListing(string $listingId, string $userId)
    {
        return $this->db->table('listings')
            ->where(BaseListing::ID, '=', $listingId)
            ->where(BaseListing::FK_USER_ID, '=', $userId)
            ->exists();
    }

    /**
     * @param $type
     * @return AbstractMetadataDriver
     */
    private function getDriver(string $type = null)
    {
        $type = $type?: $this->currentDriver;

        if (!isset($this->drivers[$type])) {
            $this->drivers[$type] = $this->kernel->make($this->listingDrivers[$type]);
        }

        return $this->drivers[$type];
    }

    /**
     * @param array $data
     */
    private function validateRequiredForNew(array $data)
    {
        if (!isset($data['type'])) {
            throw new \InvalidArgumentException("No listing type specified. Expected 'type' subset to exist in data array.");
        }

        if (!$this->isValidListingType($data['type'])) {
            throw new \InvalidArgumentException("Invalid listing type ({$data['type']}) - Allowed: [" . implode(',', $this->listingTypes) . "]");
        }

        $missing        = array_diff($this->required, array_keys($data));

        if (count($missing)) {
            throw new \InvalidArgumentException("Cannot create new listing. Missing required information: [" . implode(',', $missing) . "]");
        }
    }

    /**
     * @param string $type
     * @return bool
     */
    private function isValidListingType(string $type) {
        return isset($this->listingTypes[$type]);
    }

    /**
     * @param string $listingId
     * @param array $data
     * @return BaseListing
     * @throws ListingNotFoundException
     */
    public function edit(string $listingId, array $data) : BaseListing
    {
        $this->db->beginTransaction();

        $edits      = array_intersect_key($data, array_flip(BaseListing::$editable));
        $validator  = $this->validator->make($data, $this->editsRule);

        if ($validator->fails()) {
            $bag = new MessageBag($validator->failed());
            throw new ValidationException($bag);
        }

        $listing = BaseListing::find($listingId);

        if (!$listing) {
            throw new ListingNotFoundException("Listing id {$listingId} not found.");
        }

        foreach ($edits as $field => $value) {
            $setter = 'set' . str_replace('_', '', $field);
            $listing->{$setter}($value);
        }

        $listing->save();

        $metadata = ListingMetadata::where(ListingMetadata::FK_LISTING_ID, $listing->getId())->first();

        $listing->setMetadata($metadata);
        $location = Location::find($listing->getFkLocationId());
        $listing->setLocation($location);

        if ($listing->getType() == RideListing::ListingType) {
            $route = ListingRoute::find($metadata->getFkListingRouteId());
            $listing->setRoute($route);
        }

        $this->db->commit();

        return $listing;
    }

    public function getSelectColumns()
    {
        return [
            'a.id',
            'a.party_name',
            'a.starting_date',
            'a.ending_date',
            'a.additional_info',
            'a.max_occupants',
            'a.type',

            'b.dog_friendly',
            'b.cat_friendly',
            'b.time_of_day',

            'c.id as location_id',
            'c.street',
            'c.city',
            'c.state',
            'c.zip',
            'c.country',

            'd.id as route_id',
            'd.overview_path'
        ];
    }

    /**
     * @param string $userId
     * @return array
     */
    public function allForUser(string $userId)
    {
        $data = $this->db->table('listings as a')
            ->join('listings_metadata as b', 'a.id', '=', 'b.fk_listing_id')
            ->join('locations as c', 'c.id', '=', 'a.fk_location_id')
            ->join('listing_routes as d', 'd.id', '=', 'b.fk_listing_route_id', 'left')
            ->where('a.fk_user_id', '=', $userId)
            ->where('a.active', '=', 1)
            ->select($this->getSelectColumns())->get();

        if (!is_array($data) || !count($data)) return [];

        $out = [];
        foreach($data as $row) {
            $out[] = $this->formatOne($row);
        }

        return $out;
    }

    /**
     * @param array $data
     * @return array
     */
    private function formatOne(array $data)
    {
        $out = [
            'id' => $data['id'],
            'party_name' => $data['party_name'],
            'starting_date' => $data['starting_date'],
            'ending_date' => $data['ending_date'],
            'type' => $data['type'],
            'additional_info' => $data['additional_info'],
            'max_occupants' => $data['max_occupants'],
            'dog_friendly' => (bool)$data['dog_friendly'],
            'cat_friendly' => (bool)$data['cat_friendly'],
            'time_of_day' => ListingMetadata::translateTimeOfDay($data['time_of_day']),
            'location' => [
                'id' => $data['location_id'],
                'street' => $data['street'],
                'city' => $data['city'],
                'state' => $data['state'],
                'zip' => $data['zip'],
                'country' => $data['country']
            ]
        ];

        if ($data['type'] == RideListing::ListingType) {
            $out['route'] = [
                'id' => $data['route_id'],
                'overview_path' => $data['overview_path']
            ];
        }

        return $out;
    }

    /**
     * @param BaseListing $listing
     * @param string $location
     * @return \DateTime
     */
    public function estimateArrivalTime(BaseListing $listing, string $location)
    {
        // We'll add approximate arrival time to 'location'
        // based on the driver's departure time
        $departureTime = ListingMetadata::translateTimeOfDay($listing->getMetadata()->getTimeOfDay(), true);
        $departureDate = new \DateTime($listing->getStartingDate());

        $departureTime->setDate($departureDate->format('Y'), $departureDate->format('m'), $departureDate->format('d'));

        return $this->tripDurationEstimator->estimateArrivalDateTime($listing->getLocation(), $location, $departureTime);
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
     * @param $listingId
     * @return array
     */
    public function contactInfo($listingId)
    {
        // We only give out contact emails when the user
        // has a booking related to the listing and that
        // listing has been accepted.

        $owner = $this->db->table('bookings as a')
                          ->join('listings as b', 'a.fk_listing_id', '=', 'b.id')
                          ->join('users as c', 'c.id', '=', 'b.fk_user_id')
                          ->where('a.fk_listing_id', '=', $listingId)
                          ->where('a.fk_user_id', '=', $this->getCurrentUser()->getId())
                          ->where('a.status', '=', 'accepted')
                          ->first(['b.fk_user_id', 'c.first_name']);

        if (!$owner) {
            throw new \InvalidArgumentException("No booking belonging to user {$this->getCurrentUser()->getId()} associated with this listing {$listingId}");
        }

        // Now let's get address for the owner of the listing
        $email = $this->relayGateway->getCreateRelayAddress($owner['fk_user_id']) . '@relay.seeyouinphilly.com';

        return ['email' => $email, 'first_name' => $owner['first_name']];
    }

    /**
     * @param string $listingId
     * @return BaseListing
     * @throws ListingNotFoundException
     */
    public function find(string $listingId)
    {
        // This is performance hit. Too many reads at once. This could
        // be a single query with multiple joins

        // Todo: make this into a single query with multiple joins
        /**
         * @var BaseListing $listing
         */
        $listing = BaseListing::find($listingId);

        if (!$listing) {
            throw new ListingNotFoundException("Listing id {$listingId}. No such listing exists.");
        }

        $metadata = ListingMetadata::where(ListingMetadata::FK_LISTING_ID, $listingId)->first();
        $location = Location::find($listing->getFkLocationId());

        if ($listing->getType() == RideListing::ListingType) {
            $route = ListingRoute::find($metadata->getFkListingRouteId());
            $listing->setRoute($route);
        }

        if ($this->userSet()) {
            $bookings = $this->db->table('bookings')->where('fk_user_id', '=', $this->user->getId())->pluck('fk_listing_id');

            if (is_array($bookings)) {
                $listing->setUserBookings($bookings);
            }
        }

        return $listing->setMetadata($metadata)->setLocation($location);
    }

    private function userSet()
    {
        return $this->user instanceof User;
    }

    /**
     * @param string $listingId
     * @return bool
     */
    public function delete(string $listingId)
    {
        $this->db->table('listings')
            ->where(BaseListing::ID, '=', $listingId)
            ->update([BaseListing::ACTIVE => 0]);
    }
}