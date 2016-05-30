<?php

namespace App\Lib\Packages\Listings\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ListingMetadata
 * @package App\Lib\Packages\Listings\Models
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
class ListingMetadata extends Model
{
    // Columns
    const   ID = 'id',
            FK_LISTING_ID       = 'fk_listing_id',
            FK_LISTING_ROUTE_ID = 'fk_listing_route_id',
            DOG_FRIENDLY        = 'dog_friendly',
            CAT_FRIENDLY        = 'cat_friendly',
            TIME_OF_DAY         = 'time_of_day';

    /**
     * @var string
     */
    protected $table = 'listings_metadata';

    /**
     * @return string
     */
    public function getId() : string
    {
        return (string)$this->getAttribute(self::ID);
    }

    /**
     * @param string $id
     * @return ListingMetadata
     */
    public function setId(string $id) : ListingMetadata
    {
        $this->setAttribute(self::ID, $id);
        return $this;
    }

    /**
     * @return string
     */
    public function getFkListingId() : string
    {
        return (string)$this->getAttribute(self::FK_LISTING_ID);
    }

    /**
     * @param string $listingId
     * @return ListingMetadata
     */
    public function setFkListingId(string $listingId) : ListingMetadata
    {
        $this->setAttribute(self::FK_LISTING_ID, $listingId);
        return $this;
    }

    /**
     * @return string
     */
    public function getFkListingRouteId() : string
    {
        return (string)$this->getAttribute(self::FK_LISTING_ROUTE_ID);
    }

    /**
     * @param string $routeId
     * @return ListingMetadata
     */
    public function setFkListingRouteId(string $routeId) : ListingMetadata
    {
        $this->setAttribute(self::FK_LISTING_ROUTE_ID, $routeId);
        return $this;
    }

    /**
     * @return bool
     */
    public function isDogFriendly() : bool
    {
        return (bool)$this->getAttribute(self::DOG_FRIENDLY);
    }

    /**
     * @param $dogFriendly
     * @return ListingMetadata
     */
    public function setDogFriendly($dogFriendly) : ListingMetadata
    {
        $this->setAttribute(self::DOG_FRIENDLY, $dogFriendly);
        return $this;
    }

    /**
     * @return bool
     */
    public function isCatFriendly() : bool
    {
        return (bool)$this->getAttribute(self::CAT_FRIENDLY);
    }

    /**
     * @param $catFriendly
     * @return ListingMetadata
     */
    public function setCatFriendly($catFriendly) : ListingMetadata
    {
        $this->setAttribute(self::CAT_FRIENDLY, $catFriendly);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTimeOfDay()
    {
        return $this->getAttribute(self::TIME_OF_DAY);
    }

    /**
     * @param \DateTime $dateTime
     * @return ListingMetadata
     */
    public function setTimeOfDay(\DateTime $dateTime) : ListingMetadata
    {
        $this->setAttribute(self::TIME_OF_DAY, $dateTime);
        return $this;
    }
}