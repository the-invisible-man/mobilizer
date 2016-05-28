<?php

namespace App\Lib\Packages\Geo\Services;

use App\Lib\Packages\Core\Contracts\ServiceFactory;
use App\Lib\Packages\Core\Contracts\ServiceManager;

/**
 * Class GeocodeManager
 * @package App\Lib\Packages\Geo\API
 * @author Carlos Granados <carlos@polivet.org>
 */
class GeocodeManager extends ServiceManager implements ServiceFactory
{
    /**
     * Get the cache connection configuration.
     *
     * @param  string  $name
     * @return string
     */
    protected function getConfig(string $name)
    {
        return $this->app['config']["cache.stores.{$name}"];
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function service(string $name)
    {
        $name = $name ?: $this->getDefaultService();

        return $this->app[$name];
    }

    /**
     * Get the default cache driver name.
     *
     * @return string
     */
    public function getDefaultService()
    {
        return $this->app['config']['geo.default'];
    }
}