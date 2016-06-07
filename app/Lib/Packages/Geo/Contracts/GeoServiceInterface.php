<?php

namespace App\Lib\Packages\Geo\Contracts;

use App\Lib\Packages\Geo\Location\Geopoint;
use App\Lib\Packages\Geo\Responses\GeocodeResponse;
use App\Lib\Packages\Geo\Responses\TimeZoneResponse;
use App\Lib\Packages\Geo\Services\Google\API\Directions;

/**
 * Interface GeoServiceInterface
 * @package App\Lib\Packages\Geo\Contracts
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
interface GeoServiceInterface {

    /**
     * Expected to return duration in minutes - I know I shouldn't use the Directions class
     * from the Google API package, but I have little time now.
     *
     * Todo: Fix that shit
     * @param string $startingZip
     * @param string $destinationZip
     * @param string $format
     * @param string $travelMode
     * @return string
     */
    public function estimateTripDurationByZip(string $startingZip, string $destinationZip, string $format = Directions::AS_MINUTES, string $travelMode = Directions::DRIVING);

    /**
     * @param string $address
     * @return GeocodeResponse
     */
    public function geocode(string $address) : GeocodeResponse;

    /**
     * @param Geopoint $geopoint
     * @param int $timestamp
     * @return TimeZoneResponse
     */
    public function getTimeZone(Geopoint $geopoint, int $timestamp = null);
}