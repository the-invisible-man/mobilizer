<?php

namespace App\Lib\Packages\Listings\ListingTypes;

use App\Lib\Packages\Listings\Contracts\AbstractListing;

/**
 * Class RideListing
 * @package App\Lib\Packages\Listings\Contracts
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
class Ride extends AbstractListing
{
    const ListingType = 'R';

    /**
     * @var array
     */
    public static $required = ['time_of_day', 'selected_user_route'];

    // protected function

    /**
     * @return array
     */
    public function getData() : array
    {

    }
}