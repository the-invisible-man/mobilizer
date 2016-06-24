<?php

namespace App\Lib\Packages\EmailRelay;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Illuminate\Mail\Mailer;
use Illuminate\Database\DatabaseManager;
use App\Lib\Packages\EmailRelay\RelayGateway;

class RelayServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Postmaster::class, function (Application $app) {
            return new Postmaster($app['config']['mail.relay'], $app->make(Mailer::class), $app->make(DatabaseManager::class), $app->make(RelayGateway::class));
        });

        $this->app->singleton(RelayGateway::class, function (Application $app) {
            return new RelayGateway($app->make('db'));
        });
    }
}