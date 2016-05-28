<?php

namespace App\Lib\Packages\Geo\Contracts;

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
}