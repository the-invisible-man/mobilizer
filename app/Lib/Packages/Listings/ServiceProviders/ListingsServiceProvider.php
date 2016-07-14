<?php

namespace App\Lib\Packages\Listings\ServiceProviders;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use App\Lib\Packages\Listings\ListingDrivers\RideMetadataDriver;
use App\Lib\Packages\Listings\ListingDrivers\HomeMetadataDriver;

/**
 * Class ListingsServiceProvider
 *
 * @package     App\Lib\Packages\Listings\ServiceProviders
 * @copyright   Copyright (c) Polivet.org
 * @author      Carlos Granados <granados.carlos91@gmail.com>
 *
 * Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 */
class ListingsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(RideMetadataDriver::class, function (Application $app) {
            return new RideMetadataDriver();
        });

        $this->app->singleton(HomeMetadataDriver::class, function (Application $app) {
            return new HomeMetadataDriver();
        });
    }
}