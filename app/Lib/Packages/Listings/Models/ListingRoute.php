<?php

namespace App\Lib\Packages\Listings\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ListingRoute
 * @package App\Lib\Packages\Listings\Models
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
class ListingRoute extends Model {

    protected $table = 'listing_routes';

    // Columns
    const   ID              = 'id',
            OVERVIEW_PATH   = 'overview_path',
            SYNCHRONIZED    = 'synchronized';

    /**
     * @return string
     */
    public function getId()
    {
        return (string)$this->getAttribute(self::ID);
    }

    /**
     * @return string
     */
    public function getOverviewPath()
    {
        return $this->getAttribute(self::OVERVIEW_PATH);
    }

    /**
     * @param $path
     * @return $this
     */
    public function setOverviewPath($path)
    {
        $this->setAttribute(self::OVERVIEW_PATH, $path);
        return $this;
    }

    /**
     * @return bool
     */
    public function getSynchronized()
    {
        return (bool)$this->getAttribute(self::SYNCHRONIZED);
    }

    /**
     * @param bool $synced
     * @return $this
     */
    public function setSynchronized(bool $synced)
    {
        $this->setAttribute(self::SYNCHRONIZED, (int)$synced);
        return $this;
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->getAttribute(self::CREATED_AT);
    }

    /**
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->getAttribute(self::UPDATED_AT);
    }
}