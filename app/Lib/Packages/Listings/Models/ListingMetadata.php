<?php

namespace App\Lib\Packages\Listings\Models;

use Illuminate\Database\Eloquent\Model;
use App\Lib\Packages\Tools\Traits\UuidModel;

/**
 * Class ListingMetadata
 * @package App\Lib\Packages\Listings\Models
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
class ListingMetadata extends Model
{
    use UuidModel;

    // Columns
    const   ID                  = 'id',
            FK_LISTING_ID       = 'fk_listing_id',
            FK_LISTING_ROUTE_ID = 'fk_listing_route_id',
            DOG_FRIENDLY        = 'dog_friendly',
            CAT_FRIENDLY        = 'cat_friendly',
            TIME_OF_DAY         = 'time_of_day';

    public static $timeOfDayTranslations = [
        0 => "Early Morning",
        1 => "Noon",
        2 => "Afternoon",
        3 => "Evening"
    ];

    /**
     * Time of day as actual times [hour, minute, second]
     * @var array
     */
    public static $timeOfDayNumerical = [
        0 => [5, 30, 0],
        1 => [12, 30, 0],
        2 => [17, 30, 0],
        3 => [20, 30, 0]
    ];

    /**
     * @var string
     */
    protected $table = 'listings_metadata';

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @return string
     */
    public function getId() : string
    {
        return (string)$this->getAttribute(self::ID);
    }

    /**
     * @param $key
     * @param bool $datetime
     * @return string|null|\DateTime
     */
    public static function translateTimeOfDay($key, bool $datetime = false)
    {
        if (!isset(self::$timeOfDayTranslations[$key])) return null;

        if ($datetime) {
            $date   = new \DateTime();
            $time   = self::$timeOfDayNumerical[$key];
            $date->setTime($time[0], $time[1], $time[2]);
            return $date;
        }

        return self::$timeOfDayTranslations[$key];
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
     * @param int $timeOfDayInterval
     * @return ListingMetadata
     */
    public function setTimeOfDay(int $timeOfDayInterval) : ListingMetadata
    {
        $this->setAttribute(self::TIME_OF_DAY, $timeOfDayInterval);
        return $this;
    }

    /**
     * @return ListingRoute
     */
    public function route()
    {
        return $this->hasOne(ListingRoute::class);
    }
}