<?php

namespace App\Lib\Packages\Geo\Contracts;

interface GeoServiceInterface {

    public function estimateTripDurationByZip(string $startingZip, string $destinationZip) : string;
}