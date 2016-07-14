<?php

namespace App\Lib\Packages\Search\Drivers;

use App\Lib\Packages\Core\Validators\ValidatesConfig;
use App\Lib\Packages\Geo\Location\Geopoint;
use Elasticsearch\Client;
use App\Lib\Packages\Search\Exceptions\ElasticsearchAggregationException;

/**
 * Class ElasticsearchDriver
 *
 * @package     App\Lib\Packages\Search\Drivers
 * @copyright   Copyright (c) Polivet.org
 * @author      Carlos Granados <granados.carlos91@gmail.com>
 *
 * Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 */
class ElasticsearchDriver implements SearchDriverInterface
{
    use ValidatesConfig;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var array
     */
    private $config;

    /**
     * ElasticsearchDriver constructor.
     * @param Client $client
     * @param array $config
     */
    public function __construct(Client $client, array $config)
    {
        $this->validateConfig($config, ['hosts', 'radius']);

        $this->client   = $client;
        $this->config   = $config;
    }

    /**
     * Todo: revisit top_hits aggregator to return geo spatial match
     * @param Geopoint $pickupLocation
     * @return array
     * @throws ElasticsearchAggregationException
     */
    public function searchRide(Geopoint $pickupLocation) : array
    {
        $aggregator = 'group_by_listings';

        $params     = [
            'index' => 'listings',
            'type'  => 'route',
            'body'  => [
                'query' => [
                    'filtered' => [
                        'filter' => [
                            'geo_distance' => [
                                'distance' => $this->config['radius'],
                                'location' => [
                                    "lat" => $pickupLocation->getLat(),
                                    "lon" => $pickupLocation->getLong()
                                ]
                            ]
                        ]
                    ]
                ],
                'aggs' => [
                    $aggregator => [
                        'terms' => [
                            'field' => 'listing_id.raw',
                            'size'  => 0
                        ],
                        'aggs' => [
                            'geo_location_hits' => [
                                'top_hits' => [
                                    '_source' => [
                                        'include' => [
                                            'location',
                                            'key'
                                        ]
                                    ],
                                    'size' => 1
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $result = $this->client->search($params);

        if (!array_key_exists("aggregations", $result)) {
            throw new ElasticsearchAggregationException("Could not find aggregate index in elasticsearch results. Cannot pull buckets: " . json_encode($result));
        }

        return array_column($result["aggregations"][$aggregator]["buckets"], 'key');
    }
}