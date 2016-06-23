<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Elasticsearch\ClientBuilder;

class ElasticsearchCreateListingsIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $client = ClientBuilder::create()->build();

        $params = [
            "index"     => "listings",
            "body"      => [
                "settings"  => [
                    "index"     => [
                        "number_of_shards"      => 5,
                        "number_of_replicas"    => 0
                    ],
                ],
                "mappings" => [
                    "route"     => [
                        "properties"    => [
                            "listing_id" => [
                                "type" => "string",
                                "fields" => [
                                    "raw" => [
                                        "type"  => "string",
                                        "index" => "not_analyzed"
                                    ]
                                ]
                            ],
                            "location"      => ["type" => "geo_point"],
                            "created_on"    => [
                                "type"          => "date",
                                "format"        => "date_time"
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $client->indices()->create($params);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
