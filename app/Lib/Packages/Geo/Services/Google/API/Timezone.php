<?php

namespace App\Lib\Packages\Geo\Services\Google\API;

use App\Lib\Packages\Geo\Location\Geopoint;
use App\Lib\Packages\Geo\Services\Google\GoogleMapsAPI;

/**
 * Class Timezone
 * @package App\Lib\Packages\Geo\Services\Google\API
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
class Timezone extends GoogleMapsAPI
{
    /**
     * Resolves timezone by lat long
     * @param Geopoint $geopoint
     * @param string $timestamp
     * @return string
     */
    public function resolveTimezone(Geopoint $geopoint, string $timestamp = null) : string
    {
        $data = [
            'location' => $geopoint->getLat() . ", " . $geopoint->getLong()
        ];

        if ($timestamp !== null) {
            $data['timestamp'] = $timestamp;
        }

        return $this->do($data);
    }
}