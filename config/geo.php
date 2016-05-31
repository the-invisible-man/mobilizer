<?php

use App\Lib\Packages\Geo\Services\Google\GoogleMaps;
use App\Lib\Packages\Geo\Services\Google\GoogleMapsAPI;

return [

    /*
    |--------------------------------------------------------------------------
    | Geosptial Drivers
    |--------------------------------------------------------------------------
    |
    | The app is able to use multiple geocoding third party services. These
    | can be registered in the GeoServiceProvider. By the default the GeoService
    | manager will return the default driver when called by the service container
    | when automatically resolving class dependencies for injection.
    |
    */
    'default'   => GoogleMaps::class,

    'drivers'   => [

        // Currently we have a max of 150,000 requests per day that we can use.
        'google' => [

            'key'   => env('GOOGLE_MAPS_API_KEY'),

            'directions-api' => [
                'url'           => 'https://maps.googleapis.com/maps/api/directions/json',
                'responseType'  => GoogleMapsAPI::JSON
            ],

            'geocode-api'   => [
                'url'           => 'https://maps.googleapis.com/maps/api/geocode/json',
                'responseType'  => GoogleMapsAPI::JSON
            ],

            'timezone-api' => [
                'url'           => 'https://maps.googleapis.com/maps/api/timezone/json',
                'responseType'  => GoogleMapsAPI::JSON
            ],
        ]
    ],

    'duration-estimator' => [
        'cache_ttl' => 2880
    ]
];