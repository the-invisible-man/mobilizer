<?php

namespace App\Lib\Packages\Geo\ServiceProvider;

use Guzzle\Http\Client;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use App\Lib\Packages\Geo\TimeEstimation\TripDurationEstimator;
use App\Lib\Packages\Geo\Contracts\GeoServiceInterface;
use App\Lib\Packages\Geo\Services\GeocodeManager;
use App\Lib\Packages\Geo\Services\Google\API\Directions;
use App\Lib\Packages\Geo\Services\Google\API\Geocode;

class GeoServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('geocoding', function (Application $app) {
            return new GeocodeManager($app);
        });

        $this->app->singleton(GeoServiceInterface::class, function (Application $app) {
            return $app['geocoding']->service();
        });

        $this->app->singleton(TripDurationEstimator::class, function (Application $app) {
            $config         = $app['config']['geo.duration-estimator'];
            $geoService     = $app[GeoServiceInterface::class];
            $cache          = $app['cache.store'];

            return new TripDurationEstimator($config, $geoService, $cache);
        });

        $this->app->singleton(Directions::class, function (Application $app) {
            $config             = $app['config']['geo.drivers.google.directions-api'];
            $config['apiKey']   = $app['config']['geo.drivers.google.key'];
            $httpClient         = new Client();

            return new Directions($config, $httpClient);
        });

        $this->app->singleton(Geocode::class, function (Application $app) {
            $config             = $app['config']['geo.drivers.google.geocode-api'];
            $config['apiKey']   = $app['config']['geo.drivers.google.key'];
            $httpClient         = new Client();

            return new Geocode($config, $httpClient);
        });
    }
}