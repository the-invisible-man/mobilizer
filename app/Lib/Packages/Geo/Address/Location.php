<?php

namespace App\Lib\Packages\Geo\Address;

use App\Lib\Packages\Geo\Models\Address as AddressDatabaseModel;
use App\Lib\Packages\Geo\Services\Google\API\Geocode;

/**
 * Class Address
 * @package App\Lib\Packages\Geo\Address
 * @author Carlos Granados <granados.carlos91@gamil.com>
 */
class Location
{
    /**
     * @var AddressDatabaseModel;
     */
    private $address;

    /**
     * @var Geopoint
     */
    private $geopoint;

    /**
     * Address constructor.
     * @param AddressDatabaseModel $address
     */
    public function __construct(AddressDatabaseModel $address = null)
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        // TODO: Implement __toString() method.
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

    public function hydrate(AddressDatabaseModel $address)
    {
        
    }
}