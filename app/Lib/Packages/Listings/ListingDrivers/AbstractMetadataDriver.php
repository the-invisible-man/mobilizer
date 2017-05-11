<?php

namespace App\Lib\Packages\Listings\ListingDrivers;

use App\Lib\Packages\Listings\Contracts\BaseListing;
use App\Lib\Packages\Listings\Models\ListingMetadata;

/**
 * Class AbstractMetadataDriver
 *
 * @package     App\Lib\Packages\Listings\ListingDrivers
 * @copyright   Copyright (c) Polivet.org
 * @author      Carlos Granados <granados.carlos91@gmail.com>
 *
 * Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * This notice applies retroactively to all commits.
 */
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
    protected $type = BaseListing::class;

    /**
     * @param BaseListing $listing
     * @param array $data
     * @return BaseListing
     */
    abstract public function process(BaseListing $listing, array $data);

    /**
     * @param BaseListing $listing
     * @return bool
     */
    protected function isCorrectType(BaseListing $listing)
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