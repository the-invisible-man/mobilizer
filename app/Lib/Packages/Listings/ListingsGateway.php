<?php

namespace App\Lib\Packages\Listings;

use App\Lib\Packages\Geo\Location\LocationGateway;
use App\Lib\Packages\Listings\ListingTypes\Home;
use App\Lib\Packages\Listings\ListingTypes\Ride;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\MySqlConnection;
use App\Lib\Packages\Listings\Contracts\AbstractListing;
use App\Lib\Packages\Listings\ListingDrivers\AbstractDriver;
use Illuminate\Foundation\Application;

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
        Ride::ListingType => Ride::class,
        Home::ListingType => Home::class
    ];

    /**
     * @var string[]
     */
    private $listingDrivers = [
        Ride::ListingType => '',
        Home::ListingType => ''
    ];

    /**
     * @var AbstractDriver[]
     */
    private $drivers = [];

    /**
     * @var string
     */
    private $currentDriver;

    /**
     * @var array
     */
    private $required = [
        'party_name',
        'additional_info',
        'starting_date',
        'ending_date',
        'max_occupants',
        'starting_location',
        'fk_user_id',
        'type'
    ];

    /**
     * @var Application
     */
    private $app;

    /**
     * ListingsGateway constructor.
     * @param DatabaseManager $databaseManager
     * @param LocationGateway $locationGateway
     * @param Application $app
     * @param string $defaultDriver
     */
    public function __construct(DatabaseManager $databaseManager, LocationGateway $locationGateway, Application $app, string $defaultDriver = Ride::ListingType)
    {
        $this->db               = $databaseManager->getConnections();
        $this->locationsGateway = $locationGateway;
        $this->app              = $app;
    }

    /**
     * @param array $data
     * @return AbstractListing
     */
    public function create(array $data) : AbstractListing
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
     * @return AbstractListing
     */
    private function with(array $data) : AbstractListing
    {
        // We're gonna wrap these two writes in a transaction
        $this->db->beginTransaction();

        $location               = $this->locationsGateway->create($data['starting_location']);
        $data['fk_location_id'] = $location->getLocationId();
        $listing                = $this->insert($data);

        // Use driver to process listing type specific metadata
        $this->getDriver()->process($listing);

        // We're done
        $this->db->commit();

        return $listing;
    }

    /**
     * @param array $data
     * @return AbstractListing
     */
    private function insert(array $data) : AbstractListing
    {
        /**
         * @var AbstractListing $listing
         */
        $listing = new $this->listingTypes[$data]($data);

        $listing->save();

        return $listing;
    }

    /**
     * @param $type
     * @return AbstractDriver
     */
    private function getDriver(string $type = null)
    {
        $type = $type?: $this->currentDriver;

        if (!isset($this->drivers[$type])) {
            $this->drivers[$type] = $this->app[$this->listingDrivers[$type]];
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

        $listingClass   = $this->listingTypes[$data['listing_type']];
        $requiredFields = array_merge($this->required, $listingClass::$required);
        $missing        = array_diff($requiredFields, array_keys($data));

        if (!$missing) {
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
     * @param AbstractListing $listing
     * @return bool
     */
    public function edit(AbstractListing $listing) : bool
    {

    }

    /**
     * @param int $listingId
     * @return AbstractListing
     */
    public function find(int $listingId) : AbstractListing
    {

    }

    /**
     * @param AbstractListing $listing
     * @return bool
     */
    public function delete(AbstractListing $listing) : bool
    {

    }
}