<?php

namespace App\Lib\Packages\Listings\ListingDrivers;

use App\Lib\Packages\Listings\Contracts\AbstractListing;
use App\Lib\Packages\Listings\ListingTypes\HomeListing;
use App\Lib\Packages\Listings\Models\ListingMetadata;

/**
 * Class HomeMetadataDriver
 * @package App\Lib\Packages\Listings\ListingDrivers
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
class HomeMetadataDriver extends AbstractMetadataDriver
{
    /**
     * @param AbstractListing|HomeListing $listing
     * @param array $data
     * @return HomeListing
     */
    public function process(AbstractListing $listing, array $data)
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
     * @param AbstractListing $listing
     * @param array $data
     */
    private function validate(AbstractListing $listing, array  $data)
    {
        $this->validateRequired($data, function (array $data) use($listing) {
            if (!$this->isCorrectType($listing)) {
                throw new \InvalidArgumentException("(!) [Listing -> Driver] Mismatch: RideMetadataDriver expected a RideListing object to process, received a " . get_class($listing));
            }
        });
    }
}