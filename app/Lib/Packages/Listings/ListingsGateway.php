<?php

namespace App\Lib\Packages\Listings;

use App\Lib\Packages\Listings\Contracts\AbstractListing;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\MySqlConnection;

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