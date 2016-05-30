<?php

namespace App\Lib\Packages\Geo\Contracts;

use App\Lib\Packages\Geo\Responses\GeocodeResponse;

/**
 * Interface GeoServiceInterface
 * @package App\Lib\Packages\Geo\Contracts
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
interface GeoServiceInterface {

    /**
     * Expected to return duration in minutes
     * @param string $startingZip
     * @param string $destinationZip
     * @return string
     */
    public function estimateTripDurationByZip(string $startingZip, string $destinationZip) : string;

    /**
     * @param string $address
     * @return GeocodeResponse
     */
    public function geocode(string $address) : GeocodeResponse;
}