<?php

namespace App\Lib\Packages\Listings\ListingDrivers;

use App\Lib\Packages\Listings\Contracts\AbstractListing;
use App\Lib\Packages\Listings\ListingTypes\Home;
use App\Lib\Packages\Listings\ListingTypes\Ride;
use App\Lib\Packages\Listings\Models\ListingMetadata;
use App\Lib\Packages\Listings\Models\ListingRoute;
use Illuminate\Database\DatabaseManager;

/**
 * Class RideDriver
 *
 * This class processes data that is specific to a ride listing
 *
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
     * @var array
     */
    protected $allowedTimeOfDay = [
        self::EARLY_MORNING,
        self::NOON.
        self::AFTERNOON,
        self::EVENING
    ];

    // Columns
    const   EARLY_MORNING   = 0,
            NOON            = 1,
            AFTERNOON       = 2,
            EVENING         = 3;

    /**
     * RideDriver constructor.
     * @param DatabaseManager $databaseManager
     */
    public function __construct(DatabaseManager $databaseManager)
    {
        array_push($this->required, "selected_user_route", "time_of_day");
        $this->database = $databaseManager->connection();
    }

    /**
     * @param AbstractListing|Ride $listing
     * @param array $data
     * @return Ride
     */
    public function process(AbstractListing $listing, array $data) : Ride
    {
        $this->validateRequired($data, function (array $data) use($listing) {
            if (!$this->isCorrectType($listing)) {
                throw new \InvalidArgumentException("(!) [Listing -> Driver] Mismatch: RideDriver expected a Ride object to process, received a " . get_class($listing));
            }

            if (!in_array($data['time_of_day'], $this->allowedTimeOfDay)) {
                throw new \InvalidArgumentException("Invalid time_of_day \"{$data['time_of_day']}\". Allowed only: [" . implode(',', $this->allowedTimeOfDay) . "]");
            }
        });

        $route      = new ListingRoute();
        $route->setOverviewPath($data['selected_user_route']);

        $route->save();

        $metdadata = new ListingMetadata();
        $metdadata->setFkListingId($listing->getId());
        $metdadata->setFkListingRouteId($route->getId());

        $metdadata->save();

        return $listing;
    }
}