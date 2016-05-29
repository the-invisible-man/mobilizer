<?php

namespace App\Lib\Packages\Search\Drivers;

use App\Lib\Packages\Geo\Address\Geopoint;

interface SearchDriver {

    /**
     * @param Geopoint $latLong
     * @return array
     */
    public function searchRoute(Geopoint $latLong) : array;

    public function searchHousing();
}