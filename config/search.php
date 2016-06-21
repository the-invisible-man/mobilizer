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
        'hosts'     => ['192.168.10.10:9200'],
        'radius'    => '32.1869km'
    ]
];