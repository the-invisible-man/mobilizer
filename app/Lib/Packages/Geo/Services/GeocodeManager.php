<?php

namespace App\Lib\Packages\Geo\Services;

use App\Lib\Packages\Core\Contracts\ServiceFactory;
use App\Lib\Packages\Core\Contracts\ServiceManager;

/**
 * Class GeocodeManager
 *
 * @package     App\Lib\Packages\Geo\API
 * @copyright   Copyright (c) Polivet.org
 * @author      Carlos Granados <granados.carlos91@gmail.com>
 *
 * Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * This notice applies retroactively.
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
    public function service(string $name = null)
    {
        $name = $name ?: $this->getDefaultService();

        return $this->app[$name];
    }

    /**
     * Get the default cache driver name.
     *
     * @return string
     */
    public function getDefaultService() : string
    {
        return $this->app['config']['geo.default'];
    }
}