<?php

namespace App\Http\Controllers;

use App\Lib\Packages\Listings\ListingTypes\RideListing;
use App\Lib\Packages\Search\SearchGateway;
use Illuminate\Http\Request;

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
     * SearchController constructor.
     * @param SearchGateway $searchGateway
     */
    public function __construct(SearchGateway $searchGateway)
    {
        $this->searchGateway = $searchGateway;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $resultCode = 200;

        try {
            if ($request->get('type', RideListing::ListingType) == RideListing::ListingType) {
                $response = $this->searchGateway->searchRide($request->get('location'), $request->get('total_people', 1));
                $response = array_merge($response, $this->userInfo());
            } else {
                $response = $this->searchGateway->searchHousing($request->get('starting_date'), $request->get('ending_date'));
            }
        } catch (\Exception $e) {
            $response = ['message' => 'Service not available'];
            $resultCode = 400;
        }

        if ($request->ajax() || $resultCode != 200) {
            return \Response::json($response, $resultCode);
        }

        return view('search', $response);
    }
}