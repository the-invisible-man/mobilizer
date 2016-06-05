<?php

namespace App\Lib\Packages\Search\Drivers;

use App\Lib\Packages\Geo\Location\Geopoint;

interface SearchDriverInterface {

    /**
     * @param Geopoint $pickupLocation
     * @return array
     */
    public function searchRide(Geopoint $pickupLocation) : array;

    //public function searchHousing();
}