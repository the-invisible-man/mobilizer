<?php

namespace App\Lib\Packages\Search;

use App\Lib\Packages\Search\Drivers\SearchDriver;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\MySqlConnection;

/**
 * Class Search
 * @package App\Lib\Packages\Search
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
class Search {

    /**
     * @var SearchDriver
     */
    private $driver;

    /**
     * @var MySqlConnection
     */
    private $database;

    /**
     * Search constructor.
     * @param SearchDriver $searchDriver
     * @param DatabaseManager $databaseManager
     */
    public function __construct(SearchDriver $searchDriver, DatabaseManager $databaseManager)
    {
        $this->driver   = $searchDriver;
        $this->database = $databaseManager->connection();
    }

    // public function search
}