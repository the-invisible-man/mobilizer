<?php

namespace App\Lib\Packages\Listings\ListingDrivers;

use App\Lib\Packages\Listings\Contracts\AbstractListing;
use App\Lib\Packages\Listings\Models\ListingMetadata;

abstract class AbstractMetadataDriver
{
    /**
     * @var array
     */
    protected $required = [];

    /**
     * @var array
     */
    protected $optional = [ListingMetadata::DOG_FRIENDLY, ListingMetadata::CAT_FRIENDLY];

    /**
     * @var string
     */
    protected $type = AbstractListing::class;

    /**
     * @param AbstractListing $listing
     * @param array $data
     * @return AbstractListing
     */
    abstract public function process(AbstractListing $listing, array $data);

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
    public function validateRequired(array $data, callable $furtherValidation = null)
    {
        $missing = array_diff($this->required, array_keys($data));

        if (count($missing)) {
            throw new \InvalidArgumentException("Missing required data for listing metadata: " . implode(',', $missing) . " - Type: {$this->type}");
        }

        if (null !== $furtherValidation) return $furtherValidation($data);
    }
}