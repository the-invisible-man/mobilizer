<?php

namespace App\Lib\Packages\Listings\ListingTypes;

use App\Lib\Packages\Listings\Contracts\AbstractListing;

/**
 * Class Home
 * @package App\Lib\Packages\Listings\ListingTypes
 * @author Carlos Granados <carlos@polivet.org>
 */
class Home extends AbstractListing {

    const ListingType = 'Home';

    public function getData() : array
    {
        // TODO: Implement getData() method.
    }
}