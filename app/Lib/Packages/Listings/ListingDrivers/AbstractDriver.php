<?php

namespace App\Lib\Packages\Listings\ListingDrivers;

use App\Lib\Packages\Listings\Contracts\AbstractListing;

abstract class AbstractDriver
{
    /**
     * @var array
     */
    protected $required = [
        'fk_listing_id'
    ];

    /**
     * @var string
     */
    protected $type = AbstractListing::class;

    /**
     * @param AbstractListing $listing
     * @param array $data
     * @return array
     */
    abstract public function process(AbstractListing $listing, array $data) : array;

    /**
     * @param AbstractListing $listing
     * @return bool
     */
    protected function isCorrectType(AbstractListing $listing)
    {
        return is_a($listing, $this->type);
    }

    /**
     * @param array $data
     * @param callable $furtherValidation
     */
    public function validateRequired(array $data, callable $furtherValidation)
    {
        $missing = array_diff($this->required, $data);

        if (count($missing)) {
            throw new \InvalidArgumentException("Missing required data for listing metadata: " . implode(',', $missing) . " - Type: {$this->type}");
        }

        return $furtherValidation($data);
    }
}