<?php

namespace App\Lib\Packages\Listings\Models;

use Illuminate\Database\Eloquent\Model;
use App\Lib\Packages\Tools\Traits\UuidModel;

/**
 * Class ListingRoute
 *
 * @package     App\Lib\Packages\Listings\Models
 * @copyright   Copyright (c) Polivet.org
 * @author      Carlos Granados <granados.carlos91@gmail.com>
 *
 * Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * */
class ListingRoute extends Model {

    use UuidModel;

    /**
     * @var string
     */
    protected $table = 'listing_routes';

    /**
     * @var bool
     */
    public $incrementing = false;

    // Columns
    const   ID              = 'id',
            NAME            = 'name',
            OVERVIEW_PATH   = 'overview_path',
            SYNCHRONIZED    = 'synchronized';

    /**
     * @return string
     */
    public function getName()
    {
        return (string)$this->getAttribute(self::NAME);
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name)
    {
        $this->setAttribute(self::NAME, $name);
        return $this;
    }

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