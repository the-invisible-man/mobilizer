<?php

namespace App\Lib\Packages\Geo\Services\Google\API;

use App\Lib\Packages\Geo\Services\Google\GoogleMapsAPI;

class Geocode extends GoogleMapsAPI
{
    /**
     * @param string $address
     * @return array
     */
    public function geocode(string $address) : array
    {
        $data = [
            'address' => $address
        ];

        return $this->do($data);
    }
}