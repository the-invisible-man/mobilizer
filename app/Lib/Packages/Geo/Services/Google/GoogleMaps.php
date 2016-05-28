<?php

namespace App\Lib\Packages\Geo\Services\Google;

use App\Lib\Packages\Core\Validators\ConfigValidatorTrait;
use App\Lib\Packages\Geo\Contracts\GeoServiceInterface;
use Guzzle;

/**
 * Class Google
 * @package App\Lib\Packages\Geo\GeoServices
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
class GoogleMaps implements GeoServiceInterface {

    use ConfigValidatorTrait;

    /**
     * @var array
     */
    private $config;

    /**
     * @var array
     */
    protected $requiredConfig   = ['key', 'url'];

    const   DRIVING = 'driving',
            WALKING = 'walking',
            TRANSIT = 'transit';

    /**
     * GoogleMaps constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $this->validateConfig($config);
    }

    /**
     * @param string $startingZip
     * @param string $destinationZip
     * @return string
     */
    public function estimateTripDurationByZip(string $startingZip, string $destinationZip) : string
    {

    }

    /**
     * @param string $origin
     * @param string $destination
     * @param string $mode
     * @return array
     */
    public function directions(string $origin, string $destination, string $mode=self::DRIVING) : array
    {

    }

    private function request()
    {

    }
}