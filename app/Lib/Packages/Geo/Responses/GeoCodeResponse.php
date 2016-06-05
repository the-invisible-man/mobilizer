<?php

namespace App\Lib\Packages\Geo\Responses;

use App\Lib\Packages\Geo\Location\Geopoint;

/**
 * Class GeoCodeResponse
 * @package App\Lib\Packages\Geo\Contracts
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
class GeocodeResponse {

    /**
     * @var string
     */
    private $streetNumber;

    /**
     * @var string
     */
    private $streetName;

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $county;

    /**
     * @var string
     */
    private $state;

    /**
     * @var string
     */
    private $country;

    /**
     * @var string
     */
    private $zip;

    /**
     * @var Geopoint
     */
    private $geoLocation;

    /**
     * @param Geopoint $point
     * @return $this
     */
    public function setGeoLocation(Geopoint $point)
    {
        $this->geoLocation = $point;
        return $this;
    }

    /**
     * @return Geopoint
     */
    public function getGeoLocation()
    {
        return $this->geoLocation;
    }

    /**
     * @return string
     */
    public function getStreetNumber()
    {
        return $this->streetNumber;
    }

    /**
     * @return string
     */
    public function getStreetName()
    {
        return $this->streetName;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getCounty()
    {
        return $this->county;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * @param string $streetNumber
     * @return $this
     */
    public function setStreetNumber(string $streetNumber)
    {
        $this->streetNumber = $streetNumber;
        return $this;
    }

    /**
     * @param string $streetName
     * @return $this
     */
    public function setStreetName(string $streetName)
    {
        $this->streetName = $streetName;
        return $this;
    }

    /**
     * @param string $city
     * @return $this
     */
    public function setCity(string $city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @param string $county
     * @return $this
     */
    public function setCounty(string $county)
    {
        $this->county = $county;
        return $this;
    }

    /**
     * @param string $state
     * @return $this
     */
    public function setState(string $state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @param string $country
     * @return $this
     */
    public function setCountry(string $country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @param string $zip
     * @return $this
     */
    public function setZip(string $zip)
    {
        $this->zip = $zip;
        return $this;
    }
}