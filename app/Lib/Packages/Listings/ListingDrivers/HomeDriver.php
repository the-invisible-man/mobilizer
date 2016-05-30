<?php

namespace App\Lib\Packages\Listings\ListingDrivers;

use App\Lib\Packages\Listings\Contracts\AbstractListing;
use App\Lib\Packages\Listings\ListingTypes\Home;

/**
 * Class HomeDriver
 * @package App\Lib\Packages\Listings\ListingDrivers
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
class HomeDriver extends AbstractDriver
{
    public function process(AbstractListing $listing, array $data) : Home
    {
        // TODO: Implement process() method.
    }
}