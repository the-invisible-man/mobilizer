<?php

namespace App\Lib\Packages\Listings\ListingDrivers;

use App\Lib\Packages\Listings\Contracts\AbstractListing;
use App\Lib\Packages\Listings\ListingTypes\Ride;
use Illuminate\Database\DatabaseManager;

/**
 * Class RideDriver
 * @package App\Lib\Packages\Listings\ListingDrivers
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
class RideDriver extends AbstractDriver
{
    /**
     * @var \Illuminate\Database\Connection
     */
    private $database;

    /**
     * @var string
     */
    protected $type = Ride::class;

    /**
     * RideDriver constructor.
     * @param DatabaseManager $databaseManager
     */
    public function __construct(DatabaseManager $databaseManager)
    {
        array_push($this->required, "fk_listing_route_id");
        $this->database = $databaseManager->connection();
    }

    /**
     * @param AbstractListing $listing
     * @return Ride
     */
    public function process(AbstractListing $listing) : Ride
    {
        if (!$this->isCorrectType($listing)) {
            throw new \InvalidArgumentException("(!) Listing Driver Mismatch: RideDriver expected a Ride object to process, received a " . get_class($listing));
        }

        /**
         * $var Ride $listing
         */

    }
}