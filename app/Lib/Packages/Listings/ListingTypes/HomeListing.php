<?php

namespace App\Lib\Packages\Listings\ListingTypes;

use App\Lib\Packages\Listings\Contracts\AbstractListing;

/**
 * Class HomeListing
 * @package App\Lib\Packages\Listings\ListingTypes
 * @author Carlos Granados <carlos@polivet.org>
 */
class HomeListing extends AbstractListing
{
    const ListingType = 'H';

    /**
     * @var array
     */
    public static $required = [];


}