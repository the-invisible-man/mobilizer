<?php

namespace App\Lib\Packages\Search\Drivers;

use App\Lib\Packages\Geo\Address\Geopoint;

class ElasticsearchDriver implements SearchDriver
{
    public function searchRoute(Geopoint $latLong) : array
    {
        // TODO: Implement searchRoute() method.
    }

    public function searchHousing()
    {
        // TODO: Implement searchHousing() method.
    }
}