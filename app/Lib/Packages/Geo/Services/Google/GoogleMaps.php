<?php

namespace App\Lib\Packages\Geo\Services\Google;

use App\Lib\Packages\Geo\Contracts\GeoServiceInterface;
use App\Lib\Packages\Geo\Location\Geopoint;
use App\Lib\Packages\Geo\Services\Google\API\Geocode;
use App\Lib\Packages\Geo\Services\Google\API\Directions;
use App\Lib\Packages\Geo\Responses\GeocodeResponse;

/**
 * Class GoogleMaps
 *
 * This class merges the functionality of each Google Maps
 * API in a single object that obeys the GeoServiceInterface contract
 *
 * @package App\Lib\Packages\Geo\GeoServices
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
class GoogleMaps implements GeoServiceInterface {

    /**
     * @var GoogleMapsAPI[]|Directions[]|Geocode[]
     */
    private $services;

    /**
     * GoogleMaps constructor.
     * @param Directions $directions
     * @param Geocode $geocode
     */
    public function __construct(Directions $directions, Geocode $geocode)
    {
        $this->services[Directions::class]   = $directions;
        $this->services[Geocode::class]      = $geocode;
    }

    /**
     * @param GoogleMapsAPI $mapsAPI
     * @return $this
     */
    public function pushAPIService(GoogleMapsAPI $mapsAPI)
    {
        $this->services[get_class($mapsAPI)] = $mapsAPI;
        return $this;
    }

    /**
     * Returns duration in minutes of the shortest route
     *
     * @param string $startingZip
     * @param string $destinationZip
     * @param string $format
     * @param string $travelMode
     * @return string
     */
    public function estimateTripDurationByZip(string $startingZip, string $destinationZip, $format = Directions::AS_MINUTES, $travelMode = Directions::DRIVING) : string
    {
        $response = $this->directions($startingZip, $destinationZip, $travelMode);

        switch($format) {
            case Directions::AS_MINUTES:
                return (int)$response["routes"][0]["legs"][0]["duration"][$format] / 60;
            case Directions::AS_STRING:
                return $response["routes"][0]["legs"][0]["duration"][$format];
            default:
                throw new \InvalidArgumentException("Invalid format option '{$format}'");
        }
    }

    /**
     * @param string $address
     * @return GeocodeResponse
     */
    public function geocode(string $address) : GeocodeResponse
    {
        return $this->formatGeocodeResponse($this->services[Geocode::class]->geocode($address));
    }

    /**
     * @param array $response
     * @return GeocodeResponse
     */
    private function formatGeocodeResponse(array $response) : GeocodeResponse
    {
        $return = new GeocodeResponse();
        $point  = new Geopoint();

        foreach ($response['results'][0]['address_components'] as $component) {
            switch($component['types'][0]) {
                case "street_number":
                    $return->setStreetNumber($component['long_name']);
                    break;
                case "route":
                    $return->setStreetName($component['long_name']);
                    break;
                case "locality":
                    $return->setCity($component['long_name']);
                    break;
                case "administrative_area_level_2":
                    $return->setCounty($component['long_name']);
                    break;
                case "administrative_area_level_1":
                    $return->setState($component['short_name']);
                    break;
                case "country":
                    $return->setCountry($component['long_name']);
                    break;
                case "postal_code":
                    $return->setZip($component['long_name']);
                    break;
            }
        }

        $point->setLat($response['results'][0]['geometry']['location']['lat']);
        $point->setLong($response['results'][0]['geometry']['location']['lng']);

        $return->setGeoLocation($point);

        return $return;
    }

    /**
     * @param string $origin
     * @param string $destination
     * @param string $travelMode
     * @return array
     */
    public function directions(string $origin, string $destination, string $travelMode=Directions::DRIVING) : array
    {
        return $this->services[Directions::class]->getDirections($origin, $destination, $travelMode);
    }
}