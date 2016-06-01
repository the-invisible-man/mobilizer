<?php

namespace App\Lib\Packages\Bookings\Contracts;

use Illuminate\Database\Eloquent\Model;
use App\Lib\Packages\Tools\Traits\UuidModel;
use App\Lib\Packages\Bookings\Models\BookingMetadata;

/**
 * Class AbstractBooking
 * @package App\Lib\Packages\Bookings\Contracts
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
abstract class AbstractBooking extends Model implements \JsonSerializable {

    use UuidModel;

    /**
     * @var string
     */
    protected $table = 'bookings';

    /**
     * @var bool
     */
    public $incrementing = false;

    // Columns
    const   ID              = 'id',
            FK_USER_ID      = 'fk_user_id',
            FK_LISTING_ID   = 'fk_listing_id',
            TOTAL_PEOPLE    = 'total_people',
            STATUS          = 'status',
            ACTIVE          = 'active',
            METADATA        = 'metadata';

    /**
     * @return string
     */
    public function getId()
    {
        return $this->getAttribute(self::ID);
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
     * @param BookingMetadata $metadata
     * @return $this
     */
    public function setMetadata(BookingMetadata $metadata)
    {
        $this->setAttribute(self::METADATA, $metadata);
        return $this;
    }

    /**
     * @return BookingMetadata
     */
    public function getMetadata()
    {
        return $this->getAttribute(self::METADATA);
    }

    /**
     * @return mixed
     */
    public function getFKUserId()
    {
        return $this->getAttribute(self::FK_USER_ID);
    }

    /**
     * @param string $userId
     * @return $this
     */
    public function setFKUserId(string $userId)
    {
        $this->setAttribute(self::FK_USER_ID, $userId);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFkListingId()
    {
        return $this->getAttribute(self::FK_LISTING_ID);
    }

    /**
     * @param string $listingId
     * @return $thiss
     */
    public function setFKListingId(string $listingId)
    {
        $this->setAttribute(self::FK_LISTING_ID, $listingId);
        return $this;
    }

    /**
     * @return string
     */
    public function getTotalPeople()
    {
        return $this->getAttribute(self::TOTAL_PEOPLE);
    }

    /**
     * @param int $totalPeople
     * @return $this
     */
    public function setTotalPeople(int $totalPeople)
    {
        $this->setAttribute(self::TOTAL_PEOPLE, $totalPeople);
        return $this;
    }

    /**
     * @param $active
     * @return $this
     */
    public function setActive($active)
    {
        $this->setAttribute(self::ACTIVE, $active);
        return $this;
    }

    /**
     * @return bool;
     */
    public function getActive()
    {
        return (bool)$this->getAttribute(self::ACTIVE);
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->getActive();
    }
}