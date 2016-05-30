<?php

namespace App\Lib\Packages\Listings\ServiceProviders;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use App\Lib\Packages\Listings\ListingDrivers\RideDriver;

/**
 * Class ListingsServiceProvider
 * @package App\Lib\Packages\Listings\ServiceProviders
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
class ListingsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(RideDriver::class, function (Application $app) {
            $database = $app->make('db');
            return new RideDriver($database);
        });
    }
}