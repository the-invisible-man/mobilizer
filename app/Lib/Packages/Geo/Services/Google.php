<?php

namespace App\Lib\Packages\Geo\Services;

use App\Lib\Packages\Geo\Contracts\GeoServiceInterface;
use Guzzle;

/**
 * Class Google
 * @package App\Lib\Packages\Geo\GeoServices
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
class Google implements GeoServiceInterface {

    /**
     * @var array
     */
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function estimateTripDurationByZip(string $startingZip, string $destinationZip) : string
    {
        // TODO: Implement estimateTripDurationByZip() method.
    }

    private function request()
    {

    }
}