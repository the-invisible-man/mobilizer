<?php

namespace App\Lib\Packages\Geo\Services\Google;

use App\Lib\Packages\Geo\Contracts\GeoServiceInterface;
use App\Lib\Packages\Geo\Services\Google\API\Geocode;
use App\Lib\Packages\Geo\Services\Google\API\Directions;

/**
 * Class GoogleMaps
 *
 * This clase merges the functionality of each Google Maps
 * API in a single object that obeys the GeoServiceInterface contract
 *
 * @package App\Lib\Packages\Geo\GeoServices
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
class GoogleMaps implements GeoServiceInterface {

    /**
     * @var GoogleMapsAPI[]|Directions[]
     */
    private $services;

    /**
     * GoogleMaps constructor.
     * @param Directions $directions
     * @param Geocode $geocode
     */
    public function __construct(Directions $directions, Geocode $geocode)
    {
        $this->services['directions']   = $directions;
        $this->services['geocode']      = $geocode;
    }

    /**
     * Returns duration in minutes
     *
     * @param string $startingZip
     * @param string $destinationZip
     * @param string $travelMode
     * @param string $format
     * @return string
     */
    public function estimateTripDurationByZip(string $startingZip, string $destinationZip, $travelMode=Directions::DRIVING, $format=Directions::AS_MINUTES) : string
    {
        $response = $this->directions($startingZip, $destinationZip, $travelMode);

        return (int)$response["routes"][0]["legs"][0]["duration"][$format] / 60;
    }

    /**
     * @param string $origin
     * @param string $destination
     * @param string $travelMode
     * @return array
     */
    public function directions(string $origin, string $destination, string $travelMode=Directions::DRIVING) : array
    {
        return $this->services['directions']->getDirections($origin, $destination, $travelMode);
    }
}