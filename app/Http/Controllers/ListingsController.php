<?php

namespace App\Http\Controllers;

use App\Lib\Packages\Listings\ListingsGateway;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Lib\Packages\Listings\Models\Listing;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Validator;

/**
 * Class ListingsController
 * @package App\Http\Controllers
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
class ListingsController extends Controller {

    private $listingsGateway;

    public function __construct(ListingsGateway $listingsGateway)
    {
        $this->middleware('auth');

        $this->listingsGateway = $listingsGateway;
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
            'party_name'            => 'required|min:5|max:255',
            'start'                 => 'required|date',
            'end'                   => 'required|date|after:start',
            'time_of_day'           => 'required|confirmed|min:6',
            'max_passengers'        => 'required|numeric',
            'starting_location'     => 'required|min:5',
            'selected_user_route'   => 'required'
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function all() : JsonResponse
    {

    }

    /**
     * @return JsonResponse
     */
    public function add(Request $request) : JsonResponse
    {
        $reponseCode = 200;

        try {
            $response = $this->listingsGateway->create($request->all());
        } catch (\Exception $e) {

        }

        return $response;
    }

    /**
     * @param int $listingId
     * @return JsonResponse
     */
    public function get(int $listingId) : JsonResponse
    {
        $responseCode = 200;

        try {
            $response = Listing::findOrFail($listingId);
        } catch (ModelNotFoundException $e) {
            $responseCode   = 400;
            $response       = ['message' => "No booking with id of {$listingId} found"];
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