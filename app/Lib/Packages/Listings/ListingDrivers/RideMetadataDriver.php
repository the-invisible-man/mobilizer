<?php

namespace App\Lib\Packages\Listings\ListingDrivers;

use App\Lib\Packages\Listings\Contracts\BaseListing;
use App\Lib\Packages\Listings\ListingTypes\RideListing;
use App\Lib\Packages\Listings\Models\ListingMetadata;
use App\Lib\Packages\Listings\Models\ListingRoute;

/**
 * Class RideMetadataDriver
 *
 * This class processes data that is specific to a ride listing
 *
 * @package App\Lib\Packages\Listings\ListingDrivers
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
class RideMetadataDriver extends AbstractMetadataDriver
{
    /**
     * @var string
     */
    protected $type = RideListing::class;

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
     * RideMetadataDriver constructor.
     */
    public function __construct()
    {
        array_push($this->required, ListingRoute::OVERVIEW_PATH, ListingMetadata::TIME_OF_DAY);
    }

    /**
     * @param BaseListing|RideListing $listing
     * @param array $data
     * @return RideListing
     */
    public function process(BaseListing $listing, array $data)
    {
        $this->validate($listing, $data);

        $route = $this->createRoute($data[ListingRoute::OVERVIEW_PATH]);

        $listing->setListingRoute($route);
        $listing->setMetadata($this->createMetadata($listing, $route, $data));

        return $listing;
    }

    /**
     * @param RideListing $listing
     * @param ListingRoute $route
     * @param array $data
     * @return ListingMetadata
     */
    private function createMetadata(RideListing $listing, ListingRoute $route, array $data)
    {
        // We now have all necessary data to add metadata for a ride
        $metadata = new ListingMetadata();
        $metadata->setFkListingId($listing->getId())
                 ->setFkListingRouteId($route->getId())
                 ->setTimeOfDay($data[ListingMetadata::TIME_OF_DAY]);

        // Set optional values
        $this->setOptional($metadata, $data);

        $metadata->save();

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
     * @param BaseListing $listing
     * @param array $data
     */
    private function validate(BaseListing $listing, array  $data)
    {
        $this->validateRequired($data, function (array $data) use($listing) {
            if (!$this->isCorrectType($listing)) {
                throw new \InvalidArgumentException("(!) [Listing -> Driver] Mismatch: RideMetadataDriver expected a RideListing object to process, received a " . get_class($listing));
            }

            if (!in_array($data['time_of_day'], $this->allowedTimeOfDay)) {
                throw new \InvalidArgumentException("Invalid time_of_day \"{$data['time_of_day']}\". Allowed only: [" . implode(',', $this->allowedTimeOfDay) . "]");
            }
        });
    }
}