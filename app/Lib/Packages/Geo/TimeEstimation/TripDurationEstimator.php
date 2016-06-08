<?php

namespace App\Lib\Packages\Geo\TimeEstimation;

use App\Lib\Packages\Core\Validators\ConfigValidatorTrait;
use App\Lib\Packages\Geo\Contracts\GeoServiceInterface;
use Illuminate\Cache\Repository as CacheRepository;

/**
 * Class TripDurationEstimator
 * @package App\Lib\Packages\Geo
 * @author Carlos Granados <granados.carlos91@gmail.com>
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
     * TripDurationEstimator constructor.
     * @param array $config
     * @param GeoServiceInterface $geoService
     * @param CacheRepository $cache
     */
    public function __construct(array $config, GeoServiceInterface $geoService, CacheRepository $cache)
    {
        $this->requiredConfig   = ['cache_ttl'];
        $this->geoService       = $geoService;
        $this->config           = $this->validateConfig($config);
        $this->cache            = $cache;
    }

    /**
     * @param string $origin
     * @param string $destination
     * @return int|string
     */
    public function estimate(string $origin, string $destination)
    {
        //$key = $this->cacheKeyForZip($origin, $destination);

//        if ($this->cache->has($key)) {
//            return $this->cache->get($key);
//        }

        $duration = $this->grabFreshFromZip($origin, $destination);

        //$this->cache->put($key, $duration, $this->config['cache_ttl']);

        return $duration;
    }

    /**
     * @param string $driverOrigin
     * @param string $destination
     * @param \DateTime $departureDateTime
     * @return \DateTime
     */
    public function estimateArrivalDateTime(string $driverOrigin, string $destination, \DateTime $departureDateTime)
    {
        // We need the lat and long of the driver's starting location and pickup location
        $origin = $this->geoService->geocode($driverOrigin);
        $pickup = $this->geoService->geocode($destination);

        // Now let's figure out the timezone of the driver's starting location.
        $originTimeZone         = $this->geoService->getTimeZone($origin->getGeoLocation(), strtotime($departureDateTime->format('d M Y')));
        $destinationTimeZone    = $this->geoService->getTimeZone($pickup->getGeoLocation(), strtotime($departureDateTime->format('d M Y')));

        // Now create a new datetime object from the original datetime
        // but initialize with timezone of the driver's origin
        $final = new \DateTime($departureDateTime->format('Y-n-d H:i:s'), new \DateTimeZone($originTimeZone->getTimeZoneId()));

        // Now calculate how many minutes to drive from origin to pick up
        // location and create a DateInterval from the minutes.
        $tripDuration   = (int)$this->estimate($driverOrigin, $destination);
        $duration       = new \DateInterval("PT" . $tripDuration . "M");

        // If the duration is less than a minute then we can't go any further
        // so we'll just return the final datetime as is.
        if ($tripDuration < 1) {
            return $final;
        }

        // Increase the departure time by the number of minutes
        // that the trip from the origin to the pick up location
        // will last.
        $final->add($duration);

        // Now we can convert to the timezone of the pick up location
        $final->setTimezone(new \DateTimeZone($destinationTimeZone->getTimeZoneId()));

        // And voila!
        return $final;
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