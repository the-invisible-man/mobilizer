<?php

namespace App\Lib\Packages\Listings\ListingDrivers;

use App\Lib\Packages\Listings\Contracts\AbstractListing;

abstract class AbstractDriver
{
    /**
     * @var array
     */
    protected $required = [
        'fk_listing_id',
        'additional_info'
    ];

    /**
     * @var string
     */
    protected $type = AbstractListing::class;

    /**
     * @param AbstractListing $existingListing
     * @return array
     */
    abstract public function process(AbstractListing $existingListing) : array;

    /**
     * @param AbstractListing $listing
     * @return bool
     */
    protected function isCorrectType(AbstractListing $listing)
    {
        return is_a($listing, $this->type);
    }
}