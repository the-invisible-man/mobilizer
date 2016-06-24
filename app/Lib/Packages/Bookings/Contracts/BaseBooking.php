<?php

namespace App\Lib\Packages\Bookings\Contracts;

use Illuminate\Database\Eloquent\Model;
use App\Lib\Packages\Tools\Traits\UuidModel;
use App\Lib\Packages\Bookings\Models\BookingMetadata;

/**
 * Class BaseBooking
 * @package App\Lib\Packages\Bookings\Contracts
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
class BaseBooking extends Model implements \JsonSerializable {

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
            TYPE            = 'type',
            ADDITIONAL_INFO = 'additional_info',
            ACTIVE          = 'active',
            METADATA        = 'metadata',

            // Booking request statuses
            STATUS_ACCEPTED     = 'accepted',
            STATUS_REJECTED     = 'rejected',
            STATUS_CANCELLED    = 'cancelled',
            STATUS_PENDING      = 'pending';


    /**
     * @var array
     */
    protected $fillable = [
        self::FK_USER_ID,
        self::FK_LISTING_ID.
        self::TOTAL_PEOPLE,
        self::TYPE,
        self::ADDITIONAL_INFO,
        self::METADATA
    ];

    public function toArray()
    {
        $attributes = $this->attributesToArray();

        if ($this->getMetadata() instanceof BookingMetadata) {
            $attributes['metadata'] = $this->getMetadata()->toArray();
        }

        return $attributes;
    }

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
     * @return int
     */
    public function getTotalPeople()
    {
        return (int)$this->getAttribute(self::TOTAL_PEOPLE);
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

    /**
     * @return string
     */
    public function getAdditionalInfo()
    {
        return $this->getAttribute(self::ADDITIONAL_INFO);
    }

    /**
     * @param string $info
     * @return $this
     */
    public function setAdditionalInfo(string $info)
    {
        $this->setAttribute(self::ADDITIONAL_INFO, $info);
        return $this;
    }

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus(string $status)
    {
        $this->setAttribute(self::STATUS, $status);
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->getAttribute(self::STATUS);
    }
}