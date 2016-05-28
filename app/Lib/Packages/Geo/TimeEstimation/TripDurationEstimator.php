<?php

namespace App\Lib\Packages\Geo\TimeEstimation;

use App\Lib\Packages\Core\Validators\ConfigValidatorTrait;
use App\Lib\Packages\Geo\Contracts\GeoServiceInterface;
use Illuminate\Cache\Repository as CacheRepository;

/**
 * Class TripDurationEstimator
 * @package App\Lib\Packages\Geo
 * @author Carlos Granados <carlos@polivet.org>
 */
class TripDurationEstimator
{
    use ConfigValidatorTrait;

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
     * @var array
     */
    protected $requiredConfig = ['cache-ttl'];

    /**
     * TripDurationEstimator constructor.
     * @param array $config
     * @param GeoServiceInterface $geoService
     * @param CacheRepository $cache
     */
    public function __construct(array $config, GeoServiceInterface $geoService, CacheRepository $cache)
    {
        $this->geoService   = $geoService;
        $this->config       = $this->validateConfig($config);
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
        return $this->geoService->estimateTripDurationByZip($startingZip, $destinationZip);
    }

    /**
     * @param string $startingZip
     * @param string $destinationZip
     * @return string
     */
    private function cacheKeyForZip(string $startingZip, string $destinationZip) : string
    {
        // Some zip codes in the US start with 0, the smallest zip code is 00501,
        // currently  in use by the IRS. We will convert to int and multiply both
        // values, then generate a hash.
        $startingZip        = (int)$startingZip * 0x10;
        $destinationZip     = (int)$destinationZip * 0x10;

        // We'll enforce the smaller zip code to always come before the larger zip
        // code. This will allows us to always generate a seed that will work
        // regardless of the order that the zip code arguments are passed
        if ($startingZip > $destinationZip) {
            $seed = $destinationZip . $startingZip;
        } else {
            $seed = $startingZip . $destinationZip;
        }

        // Now that we've created a seed based on the zip codes we can run it through
        // the  sha256 algorithm and  return the generated hash to be uses as a cache
        // key that works two ways.
        return hash('sha256', $seed);
    }
}