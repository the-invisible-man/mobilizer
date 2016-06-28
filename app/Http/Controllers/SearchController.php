<?php

namespace App\Http\Controllers;

use App\Lib\Packages\Geo\Exceptions\GeocodeException;
use App\Lib\Packages\Listings\ListingTypes\RideListing;
use App\Lib\Packages\Search\SearchGateway;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Http\Request;
use App\Lib\Packages\Search\Exceptions\IncompleteQueryException;

/**
 * Class SearchController
 * @package App\Http\Controllers
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
class SearchController extends Controller {

    /**
     * @var SearchGateway
     */
    private $searchGateway;

    /**
     * @var Log
     */
    private $log;

    /**
     * SearchController constructor.
     * @param SearchGateway $searchGateway
     * @param Log $log
     */
    public function __construct(SearchGateway $searchGateway, Log $log)
    {
        $this->searchGateway    = $searchGateway;
        $this->log              = $log;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $resultCode = 200;
        $view = 'search';

        try {
            if ($request->get('type', RideListing::ListingType) == RideListing::ListingType) {
                $response = $this->searchGateway->searchRide($request->get('location'),
                    $request->get('total_people', 1));
                $response = array_merge($response, $this->userInfo());
            } else {
                $response = $this->searchGateway->searchHousing($request->get('starting_date'),
                    $request->get('ending_date'));
            }
        } catch (IncompleteQueryException $e) {
            $response   = ['status' => 'error', 'message' => 'There was an error'];
            $resultCode = 400;
            $view       = 'search_error';
            $this->log->error($e->getMessage());
        } catch (GeocodeException $e) {
            $response = ['status' => 'error', 'message' => 'We weren\'t able to understand that address.'];
            $this->log->error($e->getMessage());
        } catch (\Exception $e) {
            $response = ['status' => 'error', 'message' => 'There was an error'];
            $resultCode = 400;
            $this->log->error($e->getMessage());
        }

        if ($request->ajax()) {
            return \Response::json($response, $resultCode);
        }

        return view($view, $response);
    }

    public function logRideSearch($query)
    {

    }
}