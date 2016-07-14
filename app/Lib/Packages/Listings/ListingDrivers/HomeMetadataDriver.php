<?php

namespace App\Lib\Packages\Listings\ListingDrivers;

use App\Lib\Packages\Listings\Contracts\BaseListing;
use App\Lib\Packages\Listings\ListingTypes\HomeListing;
use App\Lib\Packages\Listings\Models\ListingMetadata;

/**
 * Class HomeMetadataDriver
 *
 * @package     App\Lib\Packages\Listings\ListingDrivers
 * @copyright   Copyright (c) Polivet.org
 * @author      Carlos Granados <granados.carlos91@gmail.com>
 *
 * Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 */
class HomeMetadataDriver extends AbstractMetadataDriver
{
    /**
     * @param BaseListing|HomeListing $listing
     * @param array $data
     * @return HomeListing
     */
    public function process(BaseListing $listing, array $data)
    {
        $this->validate($listing, $data);

        $listing->setMetadata($this->createMetadata($listing, $data));

        return $listing;
    }

    /**
     * @param HomeListing $listing
     * @param array $data
     * @return ListingMetadata
     */
    private function createMetadata(HomeListing $listing, array $data)
    {
        // We now have all necessary data to add metadata for a ride
        $metadata = new ListingMetadata();
        $metadata->setFkListingId($listing->getId());

        // Set optional values
        $this->setOptional($metadata, $data);

        $metadata->save();

        return $metadata;
    }

    /**
     * @param BaseListing $listing
     * @param array $data
     */
    private function validate(BaseListing $listing, array  $data)
    {
        $this->validateRequired($data, function (array $data) use($listing) {
            if (!$this->isCorrectType($listing)) {
                throw new \InvalidArgumentException("(!) [Listing -> Driver] Mismatch: RideMetadataDriver expected a RideListing object to process, received a " . get_class($listing));
            }
        });
    }
}