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
    /**
     * @param AbstractListing $listing
     * @param array $data
     * @return Home
     */
    public function process(AbstractListing $listing, array $data)
    {
        // TODO: Implement process() method.
    }
}