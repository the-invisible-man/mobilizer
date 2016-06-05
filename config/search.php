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

    'elasticsearch'     => [
        'hosts'     => ['192.168.10.10:9200'],
        'radius'    => '24km'
    ]
];