<?php

namespace App\Lib\Packages\Geo\Address;

use App\Lib\Packages\Geo\Models\Address as AddressDatabaseModel;
use App\Lib\Packages\Geo\Services\Google\API\Geocode;

/**
 * Class Address
 * @package App\Lib\Packages\Geo\Address
 * @author Carlos Granados <granados.carlos91@gamil.com>
 */
class Address
{
    /**
     * @var AddressDatabaseModel;
     */
    private $address;

    /**
     * @var Geocode
     */
    private $geocode;

    /**
     * Address constructor.
     * @param AddressDatabaseModel $address
     * @param Geocode $geocode
     */
    public function __construct(AddressDatabaseModel $address, Geocode $geocode)
    {
        $this->address = $address;
        $this->geocode = $geocode;
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        // TODO: Implement __toString() method.
    }
}