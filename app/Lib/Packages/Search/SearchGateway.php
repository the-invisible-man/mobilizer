<?php

namespace App\Lib\Packages\Search;

use App\Lib\Packages\Core\Validators\ValidatesConfig;
use App\Lib\Packages\Geo\Contracts\GeoServiceInterface;
use App\Lib\Packages\Geo\Exceptions\ZeroResultsException;
use App\Lib\Packages\Geo\Location\Location;
use App\Lib\Packages\Geo\Responses\GeocodeResponse;
use App\Lib\Packages\Listings\ListingTypes\RideListing;
use App\Lib\Packages\Listings\Models\ListingMetadata;
use App\Lib\Packages\Search\Drivers\SearchDriverInterface;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\MySqlConnection;
use App\Lib\Packages\Bookings\Contracts\BaseBooking;
use Illuminate\Database\Query\JoinClause;
use App\Lib\Packages\Search\Exceptions\IncompleteQueryException;

/**
 * Class Search
 *
 * Some serious refactoring will come soon
 *
 * @package     App\Lib\Packages\Search
 * @copyright   Copyright (c) Polivet.org
 * @author      Carlos Granados <granados.carlos91@gmail.com>
 *
 * Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 */
class SearchGateway {

    use ValidatesConfig;

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
     * @var array
     */
    private $config;

    /**
     * Search constructor.
     * @param DatabaseManager $databaseManager
     * @param GeoServiceInterface $geoService
     * @param SearchDriverInterface $searchDriver
     * @param array $config
     */
    public function __construct(DatabaseManager $databaseManager, GeoServiceInterface $geoService, SearchDriverInterface $searchDriver, array $config)
    {
        $this->validateConfig($config, ['max_results']);

        $this->database     = $databaseManager->connection();
        $this->geoService   = $geoService;
        $this->searchDriver = $searchDriver;
    }

    /**
     * @param string $user_location
     * @param int $minRemainingSlots
     * @return array
     * @throws IncompleteQueryException
     */
    public function searchRide(string $user_location, int $minRemainingSlots = 1)
    {
        $time       = microtime();
        $location   = $this->geoService->geocode($user_location);

        if (!strlen($location->getZip()) && ! strlen($location->getCity())) {
            throw new IncompleteQueryException("You need to search using at least a U.S./Canada ZIP Code or city");
        }

        $ids        = $this->searchDriver->searchRide($location->getGeopoint());
        $results    = $this->fetchListings($ids, $minRemainingSlots);
        $benchmark  = microtime() - $time;
        $filters    = ['total_people' => $minRemainingSlots];
        $queryInfo  = $this->formatQueryInfo($user_location, $location, $benchmark, $filters, RideListing::ListingType);

        return $this->formatResults($results, $queryInfo);
    }

    /**
     * @param $raw
     * @param GeocodeResponse $geocoded
     * @param string $benchmark
     * @param array $filters
     * @param string $type
     * @return array
     */
    public function formatQueryInfo($raw, GeocodeResponse $geocoded, string $benchmark, array $filters, string $type)
    {
        return [
                'process_time'  => $benchmark,
                'type'          => $type,
                'search_term'   => [
                    'raw'           => $raw,
                    'url_encoded'   => urlencode($raw),
                    'geocoded'      => $geocoded->toArray(),
                    'composed'      => (string)Location::build($geocoded),
                    'filters'       => $filters
                ]
        ];
    }

    /**
     * @param array $resultSet
     * @param array $queryInfo
     * @return array
     */
    private function formatResults(array $resultSet, array $queryInfo)
    {
        return [
            'status'            => 'ok',
            'query_info'        => $queryInfo,
            'number_of_hits'    => count($resultSet),
            'results'           => $resultSet
        ];
    }

    /**
     * @param string $startingDate
     * @param string $endingDate
     * @return array
     */
    public function searchHousing(string $startingDate, string $endingDate)
    {
        return [];
    }

    /**
     * @param array $ids
     * @param int $minRemainingSlots
     * @return array
     */
    private function fetchListings(array $ids, int $minRemainingSlots = 1)
    {
        $result = $this->database->table('listings as a')
                    ->join('listings_metadata as b', 'a.id', '=', 'b.fk_listing_id')
                    ->join('locations as c', 'c.id', '=', 'a.fk_location_id')
                    ->join('users as d', 'd.id', '=', 'a.fk_user_id')
                    ->leftJoin('bookings as e', function (JoinClause $join) {
                        $join->on('e.fk_listing_id', '=', 'a.id');
                        $join->on('e.status', '=', $this->database->raw("'" . BaseBooking::STATUS_ACCEPTED . "'"));
                    })
                    ->groupBy(['e.fk_listing_id', 'a.id'])
                    ->whereIn('a.id', $ids)
                    ->where('a.active', '=', 1)
                    ->having('remaining_slots', '>=', $minRemainingSlots)
                    ->limit($this->config['max_results'])
                    ->select($this->getSelectColumns())->get();

        $out = [];

        foreach ($result as $row) {
            $out[] = $this->formatOne($row);
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
            'id'                    => $data['id'],
            'party_name'            => $data['party_name'],
            'starting_date'         => (new \DateTime($data['starting_date']))->format('M d, Y'),
            'ending_date'           => (new \DateTime($data['ending_date']))->format('M d, Y'),
            'host'                  => $data['host'],
            'type'                  => $data['type'],
            'additional_info'       => $data['additional_info'],
            'additional_info_trim'  => strlen($data['additional_info']) > 200 ? substr($data['additional_info'], 0, 200) . '...' : $data['additional_info'],
            'max_occupants'         => $data['max_occupants'],
            'remaining_slots'       => (int)$data['remaining_slots'],
            'dog_friendly'          => (bool)$data['dog_friendly'],
            'cat_friendly'          => (bool)$data['cat_friendly'],
            'time_of_day'           => ListingMetadata::translateTimeOfDay($data['time_of_day']),
            'location' => [
                'id'        => $data['location_id'],
                'city'      => $data['city'],
                'state'     => $data['state'],
                'zip'       => $data['zip'],
                'country'   => $data['country']
            ],
            'booking'   => [
                'status'    => $data['status']
            ]
        ];

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
            'c.city',
            'c.state',
            'c.zip',
            'c.country',

            'd.first_name as host',

            'e.status'
        ];
    }
}