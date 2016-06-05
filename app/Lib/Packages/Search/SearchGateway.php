<?php

namespace App\Lib\Packages\Search;

use App\Lib\Packages\Geo\Contracts\GeoServiceInterface;
use App\Lib\Packages\Listings\Models\ListingMetadata;
use App\Lib\Packages\Listings\ListingTypes\RideListing;
use App\Lib\Packages\Search\Drivers\SearchDriverInterface;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\MySqlConnection;
use App\Lib\Packages\Bookings\Contracts\BaseBooking;

/**
 * Class Search
 *
 * Some serious refactoring will come soon
 *
 * @package App\Lib\Packages\Search
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
class SearchGateway {

    /**
     * @var MySqlConnection
     */
    private $database;

    /**
     * @var GeoServiceInterface
     */
    private $geoService;

    /**
     * @var SearchDriverInterface
     */
    private $searchDriver;

    /**
     * Search constructor.
     * @param DatabaseManager $databaseManager
     * @param GeoServiceInterface $geoService
     * @param SearchDriverInterface $searchDriver
     */
    public function __construct(DatabaseManager $databaseManager, GeoServiceInterface $geoService, SearchDriverInterface $searchDriver)
    {
        $this->database     = $databaseManager->connection();
        $this->geoService   = $geoService;
        $this->searchDriver = $searchDriver;
    }

    /**
     * @param string $location
     * @return array
     */
    public function searchRide(string $location)
    {
        $location   = $this->geoService->geocode($location);
        $ids        = $this->searchDriver->searchRide($location->getGeoLocation());

        return $this->pullRidesFromDB($ids);
    }

    /**
     * @param array $ids
     * @return array
     */
    private function pullRidesFromDB(array $ids)
    {
        $result = $this->database->table('listings as a')
            ->join('listings_metadata as b', 'a.id', '=', 'b.fk_listing_id')
            ->join('locations as c', 'c.id', '=', 'a.fk_location_id')
            ->join('listing_routes as d', 'd.id', '=', 'b.fk_listing_route_id', 'left')
            ->leftJoin('bookings as e', function ($join) {
                $join->on('e.fk_listing_id', '=', 'a.id');
                $join->on('e.status', '=', $this->database->raw("'" . BaseBooking::STATUS_ACCEPTED . "'"));
            })
            ->groupBy(['e.fk_listing_id', 'a.id'])
            ->whereIn('a.id', $ids)
            ->where('a.active', '=', 1)
            ->limit(50)
            ->select($this->getSelectColumns())->get();

        $out = [];

        foreach ($result as $row) {
            if ((int)$row['remaining_slots']) {
                $out[] = $this->formatOne($row);
            }
        }

        return $out;
    }

    /**
     * @param string $id
     * @return int
     */
    public function remainingSlots($id) : int
    {
        $max =  (int)$this->database->table('listings')
            ->where('id', '=', $id)
            ->value('max_occupants');

        $taken = (int)$this->database->table('bookings')
            ->where(BaseBooking::FK_LISTING_ID, '=', $id)
            ->where(BaseBooking::STATUS, '=', BaseBooking::STATUS_ACCEPTED)
            ->where(BaseBooking::ACTIVE, '=', 1)
            ->sum(BaseBooking::TOTAL_PEOPLE);

        return ($max - $taken);
    }

    /**
     * @param array $data
     * @return array
     */
    private function formatOne(array $data)
    {
        $out = [
            'id'                => $data['id'],
            'party_name'        => $data['party_name'],
            'starting_date'     => $data['starting_date'],
            'ending_date'       => $data['ending_date'],
            'type'              => $data['type'],
            'additional_info'   => $data['additional_info'],
            'max_occupants'     => $data['max_occupants'],
            'remaining_slots'   => (int)$data['remaining_slots'],
            'dog_friendly'      => (bool)$data['dog_friendly'],
            'cat_friendly'      => (bool)$data['cat_friendly'],
            'time_of_day'       => ListingMetadata::translateTimeOfDay($data['time_of_day']),
            'location' => [
                'id'        => $data['location_id'],
                'street'    => $data['street'],
                'city'      => $data['city'],
                'state'     => $data['state'],
                'zip'       => $data['zip'],
                'country'   => $data['country']
            ]
        ];

        if ($data['type'] == RideListing::ListingType) {
            $out['route'] = [
                'id' => $data['route_id'],
                'overview_path' => $data['overview_path']
            ];
        }

        return $out;
    }

    /**
     * @return array
     */
    public function getSelectColumns()
    {
        return [
            'a.id',
            'a.party_name',
            'a.starting_date',
            'a.ending_date',
            'a.additional_info',
            'a.max_occupants',
            'a.type',
            $this->database->raw('(if (e.fk_listing_id IS NULL, a.max_occupants, (a.max_occupants - SUM(e.total_people)))) as \'remaining_slots\''),

            'b.dog_friendly',
            'b.cat_friendly',
            'b.time_of_day',

            'c.id as location_id',
            'c.street',
            'c.city',
            'c.state',
            'c.zip',
            'c.country',

            'd.id as route_id',
            'd.overview_path'
        ];
    }
}