<?php

namespace App\Lib\Packages\Geo\Location;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Location
 * @package App\Lib\Packages\Geo\Location
 * @author Carlos Granados <granados.carlos91@gamil.com>
 */
class Location extends Model
{
    /**
     * @var Geopoint
     */
    private $geopoint;

    // Columns
    const   ID          = 'id',
            FK_USER_ID  = 'fk_user_id',
            STREET      = 'street',
            CITY        = 'city',
            STATE       = 'state',
            ZIP         = 'zip',
            COUNTRY     = 'country';

    /**
     * @return string
     */
    public function __toString() : string
    {
        // Build address string:
        $street = strlen($this->getStreet()) ? $this->getStreet() . ',' : '';

        return "{$street}, {$this->getCity()}, {$this->getState()}, {$this->getZip()}, {$this->getCountry()}";
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id)
    {
        $this->setAttribute(self::ID, $id);
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return (int)$this->getAttribute(self::ID);
    }

    /**
     * @param string $street
     * @return $this
     */
    public function setStreet(string $street)
    {
        $this->setAttribute(self::STREET, $street);
        return $this;
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->getAttribute(self::STREET);
    }

    /**
     * @param string $city
     * @return $this
     */
    public function setCity(string $city)
    {
        $this->setAttribute(self::CITY, $city);
        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->getAttribute(self::CITY);
    }

    /**
     * @param string $state
     * @return $this
     */
    public function setState(string $state)
    {
        $this->setAttribute(self::STATE, $state);
        return $this;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->getAttribute(self::STATE);
    }

    /**
     * @param string $zip
     * @return $this
     */
    public function setZip(string $zip)
    {
        $this->setAttribute(self::ZIP, $zip);
        return $this;
    }

    /**
     * @return string
     */
    public function getZip()
    {
        return $this->getAttribute(self::ZIP);
    }

    /**
     * @param string $country
     * @return $this
     */
    public function setCountry(string $country)
    {
        $this->setAttribute(self::COUNTRY, $country);
        return $this;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->getAttribute(self::COUNTRY);
    }

    /**
     * @param Geopoint $geopoint
     * @return Location
     */
    public function setGeopoint(Geopoint $geopoint)
    {
        $this->geopoint = $geopoint;
        return $this;
    }
}