<?php

namespace App\Lib\Packages\Listings;

use Illuminate\Database\DatabaseManager;
use Illuminate\Database\MySqlConnection;
use App\Lib\Packages\Listings\Contracts\AbstractListing;

/**
 * Class ListingsGateway
 * @package App\Lib\Packages\Listings
 * @author Carlos Granados <carlos@polivet.org>
 */
class ListingsGateway {

    /**
     * @var MySqlConnection
     */
    private $db;

    /**
     * ListingsGateway constructor.
     * @param DatabaseManager $databaseManager
     */
    public function __construct(DatabaseManager $databaseManager)
    {
        $this->db = $databaseManager->getConnections();
    }

    /**
     * @param array $data
     * @return AbstractListing
     */
    public function create(array $data) : AbstractListing
    {
        $required = [
            'party_name',
            'additional_info',
            'start',
            'end',
            'max_passengers',
            'starting_locations',
            'time_of_day'
        ];

        $missing = array_diff($required, array_keys($data));

        if (!$missing) {
            throw new \InvalidArgumentException("Cannot create new listing. Missing required information: [" . implode(',', $missing) . "]");
        }


    }

    /**
     * @param AbstractListing $listing
     * @return bool
     */
    public function save(AbstractListing $listing) : bool
    {

    }

    /**
     * @param AbstractListing $listing
     * @return bool
     */
    public function delete(AbstractListing $listing) : bool
    {

    }
}