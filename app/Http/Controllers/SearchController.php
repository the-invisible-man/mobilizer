<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
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
            $response = $this->searchGateway->searchRide($request->get('location'));
        } catch (\Exception $e) {
            $response = ['message' => 'Service not available'];
            $resultCode = 400;
        }

        return \Response::json($response, $resultCode);
    }
}