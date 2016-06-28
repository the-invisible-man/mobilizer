<?php

namespace App\Lib\Packages\Geo\Services\Google;

use App\Lib\Packages\Geo\Contracts\GeoServiceInterface;
use App\Lib\Packages\Geo\Exceptions\GeocodeException;
use App\Lib\Packages\Geo\Exceptions\ZeroResultsException;
use App\Lib\Packages\Geo\Location\Geopoint;
use App\Lib\Packages\Geo\Responses\TimeZoneResponse;
use App\Lib\Packages\Geo\Services\Google\API\Geocode;
use App\Lib\Packages\Geo\Services\Google\API\Directions;
use App\Lib\Packages\Geo\Responses\GeocodeResponse;
use App\Lib\Packages\Geo\Services\Google\API\Timezone;

/**
 * Class GoogleMaps
 *
 * This class merges the functionality of each Google Maps
 * API in a single object that implements the GeoServiceInterface contract
 *
 * @package App\Lib\Packages\Geo\GeoServices
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
class GoogleMaps implements GeoServiceInterface {

    /**
     * @var GoogleMapsAPI[]|Directions[]|Geocode[]|Timezone[]
     */
    private $services;

    const NO_RESULTS = 'ZERO_RESULTS';

    /**
     * GoogleMaps constructor.
     * @param Directions $directions
     * @param Geocode $geocode
     * @param Timezone $timezone
     */
    public function __construct(Directions $directions, Geocode $geocode, Timezone $timezone)
    {
        $this->services[Directions::class]  = $directions;
        $this->services[Geocode::class]     = $geocode;
        $this->services[Timezone::class]    = $timezone;
    }

    /**
     * @param Geopoint $geopoint
     * @param int $timestamp
     * @return string
     */
    public function getTimezone(Geopoint $geopoint, int $timestamp = null)
    {
        $timezone = $this->services[Timezone::class]->resolveTimezone($geopoint, $timestamp);

        return $this->formatTimeZoneResponse($timezone);
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
    public function estimateTripDurationByZip(string $startingZip, string $destinationZip, string $format = Directions::AS_MINUTES, string $travelMode = Directions::DRIVING)
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
     * @throws GeocodeException
     */
    public function geocode(string $address) : GeocodeResponse
    {
        $response = $this->services[Geocode::class]->geocode($address);

        if ($response['status'] != 'OK') {
            throw new GeocodeException($response['status']);
        }

        return $this->formatGeocodeResponse($response);
    }

    /**
     * @param array $response
     * @return TimeZoneResponse
     */
    private function formatTimeZoneResponse(array $response)
    {
        return (new TimeZoneResponse())->setDstOffset(array_get($response, 'dstOffset'))
                                       ->setRawOffset(array_get($response, 'rawOffset'))
                                       ->setTimeZoneId(array_get($response, 'timeZoneId'))
                                       ->setTimeZoneName(array_get($response, 'timeZoneName'));
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