<?php

namespace App\Lib\Packages\Listings\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ListingRoute
 * @package App\Lib\Packages\Listings\Models
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
class ListingRoute extends Model {

    /**
     * @return string
     */
    public function getId()
    {
        return $this->attributes['id'];
    }

    /**
     * @return string
     */
    public function getOverviewPath()
    {
        return $this->attributes['overview_path'];
    }

    /**
     * @return bool
     */
    public function getSynchronized()
    {
        return $this->attributes['synchronized'];
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->attributes['created_at'];
    }

    /**
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->attributes['updated_at'];
    }
}