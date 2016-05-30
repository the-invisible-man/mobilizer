<?php

namespace App\Lib\Packages\Listings\Contracts;

use Illuminate\Database\Eloquent\Model;
use App\Lib\Packages\Geo\Models\Address;
use App\Lib\Packages\Tools\Traits\UuidModel;
use App\User;

/**
 * Class AbstractListing
 * @package App\Lib\Packages\Listings\Contracts
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
abstract class AbstractListing extends Model implements \JsonSerializable {

    use UuidModel;

    // Columns
    const   ID              = 'id',
            FK_USER_ID      = 'fk_user_id',
            FK_LOCATION_ID  = 'fk_location_id',
            PARTY_NAME      = 'party_name',
            TYPE            = 'type',
            STARTING_DATE   = 'starting_date',
            ENDING_DATE     = 'ending_date',
            MAX_OCCUPANTS   = 'max_occupants',
            ACTIVE          = 'active';

    /**
     * @var array
     */
    public static $required = [];

    /**
     * @var string
     */
    protected $table = 'listings';

    /**
     * @return int
     */
    public function getId()
    {
        return (int)$this->getAttribute(self::ID);
    }

    /**
     * @param $id
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
    public function getFkUserId()
    {
        return (int)$this->getAttribute(self::FK_USER_ID);
    }

    /**
     * @param int $userId
     * @return $this
     */
    public function setFkUserId(int $userId)
    {
        $this->setAttribute(self::FK_USER_ID, $userId);
        return $this;
    }


    /**
     * @return int
     */
    public function getFkLocationId()
    {
        return (int)$this->getAttribute(self::FK_LOCATION_ID);
    }

    /**
     * @param int $locationId
     * @return $this
     */
    public function setFkLocationId(int $locationId)
    {
        $this->setAttribute(self::FK_LOCATION_ID, $locationId);
        return $this;
    }

    /**
     * @return string
     */
    public function getPartyName()
    {
        return $this->getAttribute(self::PARTY_NAME);
    }

    /**
     * @param string $partyName
     * @return $this
     */
    public function setPartyName(string $partyName)
    {
        $this->setAttribute(self::PARTY_NAME, $partyName);
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->getAttribute(self::TYPE);
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType(string $type)
    {
        $this->setAttribute(self::TYPE, $type);
        return $this;
    }

    /**
     * @param \DateTime $dateTime
     * @return $this
     */
    public function setStartingDate(\DateTime $dateTime)
    {
        $this->setAttribute(self::STARTING_DATE, $dateTime);
        return $this;
    }

    /**
     * @return string
     */
    public function getStartingDate()
    {
        return $this->getAttribute(self::STARTING_DATE);
    }

    /**
     * @param \DateTime $dateTime
     * @return $this
     */
    public function setEndingDate(\DateTime $dateTime)
    {
        $this->setAttribute(self::ENDING_DATE, $dateTime);
        return $this;
    }

    /**
     * @return string
     */
    public function getEndingDate()
    {
        return $this->getAttribute(self::ENDING_DATE);
    }

    /**
     * @return int
     */
    public function getMaxOccupants()
    {
        return $this->getAttribute(self::MAX_OCCUPANTS);
    }

    /**
     * @param int $maxOccupants
     * @return $this
     */
    public function setMaxOccupants(int $maxOccupants)
    {
        $this->setAttribute(self::MAX_OCCUPANTS, $maxOccupants);
        return $this;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return (bool)$this->getAttribute(self::ACTIVE);
    }

    /**
     * @param bool $active
     * @return $this
     */
    public function setActive(bool $active)
    {
        $this->setAttribute(self::ACTIVE, $active);
        return $this;
    }

    /**
     * @return User
     */
    public function user() : User
    {
        $this->belongsTo(User::class);
    }

    /**
     * @return Address
     */
    public function address() : Address
    {
        $this->hasOne(Address::class);
    }
}