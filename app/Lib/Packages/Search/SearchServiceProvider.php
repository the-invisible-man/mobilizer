<?php

namespace App\Lib\Packages\Search;

use Illuminate\Foundation\Application;
use App\Lib\Packages\Geo\Contracts\GeoServiceInterface;
use Illuminate\Support\ServiceProvider;
use App\Lib\Packages\Search\Drivers\ElasticsearchDriver;
use Elasticsearch\ClientBuilder;
use App\Lib\Packages\Search\Drivers\SearchDriverInterface;

class SearchServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->app->singleton(SearchGateway::class, function (Application $app) {
            $db     = $app->make('db');
            $geo    = $app->make(GeoServiceInterface::class);
            $driver = $app[SearchDriverInterface::class];

            return new SearchGateway($db, $geo, $driver);
        });

        $this->app->singleton(SearchDriverInterface::class, function (Application $app) {
            $default = $app['config']['search.default-driver'];

            return $app->make($default);
        });

        $this->app->singleton(ElasticsearchDriver::class, function (Application $app) {
            $config = $app['config']['search.elasticsearch'];
            $client = ClientBuilder::create()->setHosts($config['hosts'])->build();

            return new ElasticsearchDriver($client, $config);
        });
    }
}