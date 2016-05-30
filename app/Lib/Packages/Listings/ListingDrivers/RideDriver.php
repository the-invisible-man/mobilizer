<?php

namespace App\Lib\Packages\Listings\ListingDrivers;

use App\Lib\Packages\Listings\Contracts\AbstractListing;
use App\Lib\Packages\Listings\ListingTypes\Ride;
use App\Lib\Packages\Listings\Models\ListingMetadata;
use App\Lib\Packages\Listings\Models\ListingRoute;

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
     * @var string
     */
    protected $type = Ride::class;

    /**
     * @var array
     */
    protected $allowedTimeOfDay = [
        self::EARLY_MORNING,
        self::NOON,
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
     */
    public function __construct()
    {
        array_push($this->required, "selected_user_route", "time_of_day");
    }

    /**
     * @param AbstractListing|Ride $listing
     * @param array $data
     * @return Ride
     */
    public function process(AbstractListing $listing, array $data)
    {
        $this->validate($listing, $data);

        $route = $this->createRoute($data['selected_user_route']);

        $listing->setMetadata($this->createMetadata($listing, $route));

        return $listing;
    }

    /**
     * @param Ride $listing
     * @param ListingRoute $route
     * @return ListingMetadata
     */
    private function createMetadata(Ride $listing, ListingRoute $route)
    {
        // We now have all necessary data to add metadata for a ride
        $metadata = new ListingMetadata();
        $metadata->setFkListingId($listing->getId())
                 ->setFkListingRouteId($route->getId())
                 ->save();

        return $metadata;
    }

    /**
     * @param $path
     * @return ListingRoute
     */
    private function createRoute($path)
    {
        // Save encoded lat/long path in database for further
        // processing at a later time.
        $route      = new ListingRoute();
        $route->setOverviewPath($path)->save();

        return $route;
    }

    /**
     * @param AbstractListing $listing
     * @param array $data
     */
    private function validate(AbstractListing $listing, array  $data)
    {
        $this->validateRequired($data, function (array $data) use($listing) {
            if (!$this->isCorrectType($listing)) {
                throw new \InvalidArgumentException("(!) [Listing -> Driver] Mismatch: RideDriver expected a Ride object to process, received a " . get_class($listing));
            }

            if (!in_array($data['time_of_day'], $this->allowedTimeOfDay)) {
                throw new \InvalidArgumentException("Invalid time_of_day \"{$data['time_of_day']}\". Allowed only: [" . implode(',', $this->allowedTimeOfDay) . "]");
            }
        });
    }
}