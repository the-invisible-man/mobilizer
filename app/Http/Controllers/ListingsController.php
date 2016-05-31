<?php

namespace App\Http\Controllers;

use App\Lib\Packages\Listings\Contracts\AbstractListing;
use App\Lib\Packages\Listings\ListingsGateway;
use App\Lib\Packages\Listings\ListingTypes\Ride;
use App\Lib\Packages\Listings\Models\ListingMetadata;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Log\Writer;
use Symfony\Component\HttpFoundation\Response;
use Validator;

/**
 * Class ListingsController
 * @package App\Http\Controllers
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
class ListingsController extends Controller {

    /**
     * @var ListingsGateway
     */
    private $listingsGateway;

    /**
     * @var Writer
     */
    private $log;

    public function __construct(ListingsGateway $listingsGateway, Writer $log)
    {
        //$this->middleware('auth');

        $this->listingsGateway  = $listingsGateway;
        $this->log              = $log;
    }

    /**
     * Get a validator for an incoming create request
     *
     * @param  array  $data
     * @return Validator
     */
    protected function validator(array $data) : Validator
    {
        return Validator::make($data, [
            'party_name'    => 'required|min:5|max:255',
            'starting_date' => 'required|date',
            'ending_date'   => 'required|date|after:start',
            'time_of_day'   => 'required|confirmed|min:6',
            'max_occupants' => 'required|numeric',
            'location'      => 'required|min:5',
            'type'          => 'required|min:1|max:1'
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function all() : JsonResponse
    {

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request)
    {
        try {
            $data       = $this->prepareData($request->all());
            $response   = $this->formatResponse($this->listingsGateway->create($data)->toArray());
        } catch (\Exception $e) {
            $this->log->error($e->getMessage());
            $responseCode   = 400;
            $response       = ["message" => "Service not available"];
            return \Response::json($response, $responseCode);
        }

        return $request['type'] == Ride::ListingType ? view('list_ride_success', $response) : view('list_house_success', $response);
    }

    /**
     * @param array $response
     * @return array
     */
    private function formatResponse(array $response)
    {
        $response = json_decode(json_encode($response), true);

        $response['starting_date']['text']  = (new \DateTime($response['starting_date']['date']))->format("M d, Y");
        $response['ending_date']['text']    = (new \DateTime($response['ending_date']['date']))->format("M d, Y");
        $response['user_email']             = 'granados.carlos91@gmail.com';
        $response['leaving']                = ListingMetadata::$timeOfDayTranslations[$response['metadata']['time_of_day']];

        return $response;
    }

    /**
     * @param array $data
     * @return array
     */
    public function prepareData(array $data) : array
    {
        $data['fk_user_id'] = "fa59822a-3f55-408c-98a6-e2b7e5905664";
        return $data;
    }

    /**
     * @param int $listingId
     * @return JsonResponse
     */
    public function get(int $listingId) : JsonResponse
    {
        $responseCode = 200;

        try {
            $response = $this->listingsGateway->find($listingId);
        } catch (ModelNotFoundException $e) {
            $responseCode   = 400;
            $response       = ['message' => "No listing with id of {$listingId} found"];
        }

        return \Response::json($response, $responseCode);
    }

    /**
     * @param int $listingId
     * @return JsonResponse
     */
    public function edit(int $listingId) : JsonResponse
    {

    }

    /**
     * @param int $listingId
     * @return JsonResponse
     */
    public function delete(int $listingId) : JsonResponse
    {

    }
}