<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Lib\Packages\Listings\Listing;
use Illuminate\Http\JsonResponse;
use Validator;

/**
 * Class ListingsController
 * @package App\Http\Controllers
 * @author Carlos Granados <carlos@polivet.org>
 */
class ListingsController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
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
            'first_name'    => 'required|max:255',
            'last_name'     => 'required|max:255',
            'email'         => 'required|email|max:255|unique:users',
            'password'      => 'required|confirmed|min:6',
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
    public function add() : JsonResponse
    {

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