<?php

namespace App\Lib\Packages\Geo\Location;

use App\Lib\Packages\Geo\Contracts\GeoServiceInterface;
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
     * @var GeoServiceInterface
     */
    private $geoService;

    /**
     * LocationGateway constructor.
     * @param DatabaseManager $databaseManager
     * @param GeoServiceInterface $geoService
     */
    public function __construct(DatabaseManager $databaseManager, GeoServiceInterface $geoService)
    {
        $this->database     = $databaseManager->connection();
        $this->geoService   = $geoService;
    }

    /**
     * @param string $address
     * @return Location
     */
    public function create($address) : Location
    {
        $response = $this->geoService->geocode($address);
        $location = new Location();

        if (strlen($response->getStreetNumber()) || strlen($response->getStreetName())) {
            $location->setStreet("{$response->getStreetNumber()} {$response->getStreetName()}");
        }

        $location->setCity($response->getCity());
        $location->setState($response->getState());
        $location->setZip($response->getZip());
        $location->setCountry($response->getCountry());

        $location->save();

        return $location;
    }
}