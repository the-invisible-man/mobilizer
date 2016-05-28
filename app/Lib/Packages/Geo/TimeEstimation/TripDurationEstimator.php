<?php

namespace App\Lib\Packages\Geo\TimeEstimation;

use App\Lib\Packages\Geo\Contracts\GeoServiceInterface;
use Illuminate\Cache\Repository as CacheRepository;

/**
 * Class TripDurationEstimator
 * @package App\Lib\Packages\Geo
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
class TripDurationEstimator
{
    /**
     * @var GeoServiceInterface
     */
    private $geoService;

    /**
     * @var CacheRepository
     */
    private $cache;

    /**
     * @var array
     */
    private $config;

    /**
     * TripDurationEstimator constructor.
     * @param array $config
     * @param GeoServiceInterface $geoService
     * @param CacheRepository $cache
     */
    public function __construct(array $config, GeoServiceInterface $geoService, CacheRepository $cache)
    {
        $this->geoService   = $geoService;
        $this->cache        = $cache;
    }

    /**
     * @param string $startingZip
     * @param string $destinationZip
     * @return string
     */
    public function zip(string $startingZip, string $destinationZip) : string
    {
        $key = $this->cacheKeyForZip($startingZip, $destinationZip);

        if ($this->cache->has($key)) {
            return $this->cache->get($key);
        }

        $duration = $this->grabFreshFromZip($startingZip, $destinationZip);

        $this->cache->put($key, $duration, $this->config['cache_ttl']);

        return $duration;
    }

    /**
     * @param string $startingZip
     * @param string $destinationZip
     * @return string
     */
    private function grabFreshFromZip(string $startingZip, string $destinationZip) : string
    {

    }

    /**
     * @param string $startingZip
     * @param string $destinationZip
     * @return string
     */
    private function cacheKeyForZip(string $startingZip, string $destinationZip) : string
    {
        // Some zip codes in the US start with 0, the smallest zip code is 00501,
        // currently in use by the IRS. We will convert to int and multiply both
        // values, then generate a hash.
        $startingZip        = (int)$startingZip * 0x10;
        $destinationZip     = (int)$destinationZip * 0x10;

        if ($startingZip > $destinationZip) {
            $seed = $destinationZip . $startingZip;
        } else {
            $seed = $startingZip . $destinationZip;
        }

        // Now that we've created a seed based on the zip codes we can run it through
        // a hash algorithm and return. We will use sha256 to do the best we can to
        // avoid any collisions.
        return hash('sha256', $seed);
    }
}