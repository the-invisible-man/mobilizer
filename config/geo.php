<?php

use App\Lib\Packages\Geo\Services\Google;

return [

    /*
    |--------------------------------------------------------------------------
    | Geosptial Driver
    |--------------------------------------------------------------------------
    |
    | The app is able to use multiple geocoding third party services. These
    | can be registered in the GeoServiceProvider. By the default the GeoService
    | manager will return the default driver when called by the service container
    | when automatically resolving class dependencies for injection.
    |
    */
    'default'   => Google::class,

    'drivers'   => [

        // Currently we have a max of 150,000
        // requests per day that we can use.
        'google' => [
            'key'   => 'AIzaSyB7-veSR-bmwjhQNXSPPIKAJRTRG5CzZZ8'
        ]
    ],

    'duration-estimator' => [
        'cache_ttl' => 2880
    ]
];