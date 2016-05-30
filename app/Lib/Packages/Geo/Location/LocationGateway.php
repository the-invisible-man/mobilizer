<?php

namespace App\Lib\Packages\Geo\Location;

use Illuminate\Database\DatabaseManager;

/**
 * Class LocationGateway
 * @package App\Lib\Packages\Geo\Location
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
class LocationGateway
{
    /**
     * @var \Illuminate\Database\Connection
     */
    private $database;

    /**
     * LocationGateway constructor.
     * @param DatabaseManager $databaseManager
     */
    public function __construct(DatabaseManager $databaseManager)
    {
        $this->database = $databaseManager->connection();
    }

    /**
     * @param array $data
     * @return Location
     */
    public function create(array $data) : Location
    {

    }
}