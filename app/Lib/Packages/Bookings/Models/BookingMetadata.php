<?php

namespace App\Lib\Packages\Bookings\Models;

use App\Lib\Packages\Geo\Location\Location;
use Illuminate\Database\Eloquent\Model;
use App\Lib\Packages\Tools\Traits\UuidModel;

/**
 * Class BookingMetadata
 * @package App\Lib\Packages\Bookings\Models
 * @author Carlos Granados <granados.carlos81@gmail.com>
 */
class BookingMetadata extends Model
{
    use UuidModel;

    /**
     * @var string
     */
    protected $table = 'bookings_metadata';

    /**
     * @var string
     */
    public $incrementing = false;

    // Columns
    const   ID              = 'id',
            FK_BOOKING_ID   = 'fk_booking_id',
            FK_LOCATION_ID  = 'fk_location_id',
            BRINGS_DOG      = 'brings_dog',
            BRINGS_CAT      = 'brings_cat';
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
    public function getId()
    {
        return $this->getAttribute(self::ID);
    }

    /**
     * @param string $bookingId
     * @return $this
     */
    public function setFkBookingId(string $bookingId)
    {
        $this->setAttribute(self::FK_BOOKING_ID, $bookingId);
        return $this;
    }

    /**
     * @return string
     */
    public function getFkBookingId()
    {
        return $this->getAttribute(self::FK_BOOKING_ID);
    }

    public function setLocation(Location $location)
    {
        $this->setAttribute('location', $location);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->getAttribute('location');
    }

    /**
     * @param string $locationId
     * @return $this
     */
    public function setFkLocationId(string $locationId)
    {
        $this->setAttribute(self::FK_LOCATION_ID, $locationId);
        return $this;
    }

    /**
     * @return string
     */
    public function getFkLocationId()
    {
        return $this->getAttribute(self::FK_LOCATION_ID);
    }

    /**
     * @param bool $bringsDog
     * @return $this
     */
    public function setBringsDog(bool $bringsDog)
    {
        $this->setAttribute(self::BRINGS_DOG, $bringsDog);
        return $this;
    }

    /**
     * @return string
     */
    public function getBringsDog()
    {
        return $this->getAttribute(self::BRINGS_DOG);
    }

    /**
     * @return string
     */
    public function isBringingDog()
    {
        return $this->getBringsDog();
    }

    /**
     * @param bool $bringsCat
     * @return $this
     */
    public function setBringsCat(bool $bringsCat)
    {
        $this->setAttribute(self::BRINGS_CAT, $bringsCat);
        return $this;
    }

    /**
     * @return string
     */
    public function getBringsCat()
    {
        return $this->getAttribute(self::BRINGS_CAT);
    }

    /**
     * @return string
     */
    public function isBringingCat()
    {
        return $this->getBringsCat();
    }

    /**
     * @param string $additionalInfo
     * @return $this
     */
    public function setAdditionalInfo(string $additionalInfo)
    {
        $this->setAttribute(self::ADDITIONAL_INFO, $additionalInfo);
        return $this;
    }

    /**
     * @return string
     */
    public function getAdditionalInfo()
    {
        return $this->getAttribute(self::ADDITIONAL_INFO);
    }
}