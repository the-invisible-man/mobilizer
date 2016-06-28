<?php

namespace App\Lib\Packages\Geo\Location;

use App\Lib\Packages\Geo\Responses\GeocodeResponse;
use Illuminate\Database\Eloquent\Model;
use App\Lib\Packages\Tools\Traits\UuidModel;

/**
 * Class Location
 * @package App\Lib\Packages\Geo\Location
 * @author Carlos Granados <granados.carlos91@gamil.com>
 */
class Location extends Model
{
    use UuidModel;

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
            COUNTRY     = 'country',
            LAT         = 'lat',
            LONG        = 'lng';

    protected $table = 'locations';

    /**
     * @var array
     */
    protected $standardComponents = [
        self::STREET, self::CITY, self::STATE, self::ZIP, self::COUNTRY
    ];

    /**
     * @var array
     */
    protected $fillable = [
        self::STREET, self::CITY, self::STATE, self::ZIP, self::COUNTRY, self::LAT, self::LONG
    ];

    /**
     * We are using uuid's and not auto-incrementing integers
     * @var bool
     */
    public $incrementing = false;

    /**
     * @return string
     */
    public function __toString() : string
    {
        // Build address string:
        $components = [];

        foreach ($this->standardComponents as $component) {
            $str = $this->getAttribute($component);

            if (strlen($str)) $components[] = $str;
        }

        return implode(', ', $components);
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setId(string $id)
    {
        $this->setAttribute(self::ID, $id);
        return $this;
    }

    /**
     * @return string
     */
    public function getId() : string
    {
        return (string)$this->getAttribute(self::ID);
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
     * Maintain as string to avoid precision errors!
     * @return string
     */
    public function getLat()
    {
        return $this->getAttribute(self::LAT);
    }

    /**
     * Maintain as string to avoid precision errors!
     * @return string
     */
    public function getLong()
    {
        return $this->getAttribute(self::LONG);
    }

    /**
     * @param string $lat
     * @return $this
     */
    public function setLat(string $lat)
    {
        $this->setAttribute(self::LAT, $lat);
        return $this;
    }

    /**
     * @param string $lng
     * @return $this
     */
    public function setLong(string $lng)
    {
        $this->setAttribute(self::LONG, $lng);
        return $this;
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

    /**
     * @param GeocodeResponse $response
     * @return static
     */
    public static function build(GeocodeResponse $response)
    {
        return new static([
            self::STREET    => $response->getStreetNumber() . ' ' . $response->getStreetName(),
            self::CITY      => $response->getCity(),
            self::STATE     => $response->getState(),
            self::ZIP       => $response->getZip(),
            self::COUNTRY   => $response->getCountry(),
            self::LAT       => $response->getGeoLocation()->getLat(),
            self::LONG      => $response->getGeoLocation()->getLong()
        ]);
    }
}