<?php

namespace App\Lib\Packages\Geo\Services\Google\API;

use App\Lib\Packages\Geo\Location\Geopoint;
use App\Lib\Packages\Geo\Services\Google\GoogleMapsAPI;

/**
 * Class Timezone
 *
 * @package     App\Lib\Packages\Geo\Services\Google\API
 * @copyright   Copyright (c) Polivet.org
 * @author      Carlos Granados <granados.carlos91@gmail.com>
 *
 * Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * */
class Timezone extends GoogleMapsAPI
{
    /**
     * Resolves timezone by lat long
     * @param Geopoint $geopoint
     * @param int $timestamp
     * @return string
     */
    public function resolveTimezone(Geopoint $geopoint, int $timestamp = null)
    {
        $data = [
            'location'  => $geopoint->getLat() . ", " . $geopoint->getLong(),
            'timestamp' => is_null($timestamp) ? time() : $timestamp
        ];

        return $this->do($data);
    }
}