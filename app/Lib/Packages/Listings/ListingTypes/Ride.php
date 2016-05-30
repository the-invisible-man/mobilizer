<?php

namespace App\Lib\Packages\Listings\ListingTypes;

use App\Lib\Packages\Listings\Contracts\AbstractListing;
use App\Lib\Packages\Listings\Models\ListingMetadata;
/**
 * Class RideListing
 * @package App\Lib\Packages\Listings\Contracts
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
class Ride extends AbstractListing
{
    const ListingType = 'R';

    /**
     * @var ListingMetadata
     */
    private $metadata;

    /**
     * @var array
     */
    public static $required = ['time_of_day', 'selected_user_route'];

    /**
     * @param ListingMetadata $listingMetadata
     * @return $this
     */
    public function setMetadata(ListingMetadata $listingMetadata)
    {
        $this->metadata = $listingMetadata;
        return $this;
    }

    /**
     * @return ListingMetadata
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

}