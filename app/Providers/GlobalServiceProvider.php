<?php

namespace App\Providers;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use App\Lib\Packages\Bookings\BookingBuilder;

class GlobalServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->app->singleton(BookingBuilder::class, function (Application $app, $params) {
            return new BookingBuilder($app->make('db'));
        });
    }
}