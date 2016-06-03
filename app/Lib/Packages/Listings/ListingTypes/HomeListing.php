<?php

namespace App\Lib\Packages\Listings\ListingTypes;

use App\Lib\Packages\Listings\Contracts\BaseListing;

/**
 * Class HomeListing
 * @package App\Lib\Packages\Listings\ListingTypes
 * @author Carlos Granados <carlos@polivet.org>
 */
class HomeListing extends BaseListing
{
    const ListingType = 'H';

    /**
     * @var array
     */
    public static $required = [];


}