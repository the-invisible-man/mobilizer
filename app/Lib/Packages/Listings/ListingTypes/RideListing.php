<?php

namespace App\Lib\Packages\Listings\ListingTypes;

use App\Lib\Packages\Listings\Contracts\BaseListing;
use App\Lib\Packages\Listings\Models\ListingMetadata;
use App\Lib\Packages\Listings\Models\ListingRoute;

/**
 * Class RideListing
 *
 * @package     App\Lib\Packages\Listings\Contracts
 * @copyright   Copyright (c) Polivet.org
 * @author      Carlos Granados <granados.carlos91@gmail.com>
 *
 * Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 */
class RideListing extends BaseListing
{
    const ListingType = 'R';

    /**
     * @var ListingRoute
     */
    private $listingRoute;

    /**
     * @var array
     */
    public static $required = ['time_of_day', 'selected_user_route'];

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return array_merge($this->prepareToArray(), ['route' => $this->route()->toArray()]);
    }

    /**
     * @return ListingRoute
     */
    public function getListingRoute()
    {
        return $this->route();
    }

    /**
     * @param ListingRoute $route
     * @return $this
     */
    public function setListingRoute(ListingRoute $route)
    {
        $this->listingRoute = $route;
        return $this;
    }

    /**
     * @return ListingRoute
     */
    public function route()
    {
        if ($this->listingRoute === null) {
            $this->listingRoute = $this->hasOne(ListingRoute::class);
        }

        return $this->listingRoute;
    }
}