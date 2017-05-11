<?php

use App\Lib\Packages\Search\Drivers\ElasticsearchDriver;

return [

    /*
    |--------------------------------------------------------------------------
    | Search Settings/Drivers
    |--------------------------------------------------------------------------
    |
    | Currently only using Elasticsearch for geospatial search
    |
    */
    'default-driver'    => ElasticsearchDriver::class,

    'gateway' => [
        'max_results'   => 50
    ],

    'elasticsearch'     => [
        'hosts'     => [env('ELASTICSEARCH_HOST', '192.168.10.10') . ':' . env('ELASTICSEARCH_PORT', '9200')],

        // Max search radius
        'radius'    => '32.1869km'
    ]
];