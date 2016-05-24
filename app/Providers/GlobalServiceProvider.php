<?php

namespace App\Providers;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use App\Lib\Packages\Bookings\BookingBuilder;
use App\Lib\Packages\Listings\ListingsGateway;

/**
 * Class GlobalServiceProvider
 * @package App\Providers
 */
class GlobalServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->app->singleton(BookingBuilder::class, function (Application $app, array $params) : BookingBuilder {
            return new BookingBuilder($app->make('db'));
        });

        $this->app->singleton(ListingsGateway::class, function (Application $app, array $params) : ListingsGateway {
            return new ListingsGateway($app->make('db'));
        });
    }
}