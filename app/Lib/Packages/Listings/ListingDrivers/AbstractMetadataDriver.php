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
     * Here add any optional boolean options. The key should be
     * the column name and the value will be the default if the
     * data array doesn't contain the option.
     * @var array
     */
    protected $options = [
        ListingMetadata::DOG_FRIENDLY   => false,
        ListingMetadata::CAT_FRIENDLY   => false
    ];

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
     * @param ListingMetadata $metadata
     * @param array $data
     */
    public function setOptional(ListingMetadata $metadata, array $data)
    {
        foreach ($this->options as $column => $default) {
            $val = array_get($data, $column, $default);
            $metadata->setAttribute($column, $val);
        }
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