<?php

namespace App\Http\Controllers;

use App\Lib\Packages\Bookings\BookingsGateway;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Lib\Packages\Bookings\Models\Booking;
use App\Lib\Packages\Bookings\BookingBuilder;
use Validator;

/**
 * Class BookingsController
 * @package App\Http\Controllers
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
class BookingsController extends Controller {

    /**
     * @var BookingsGateway
     */
    private $bookingsGateway;

    /**
     * @var \Illuminate\Database\Connection
     */
    private $db;

    /**
     * @var array
     */
    private $validatorAdd = [
        'fk_listing_id'     => 'required|min:36|min:36',
        'total_people'      => 'required|numeric',
        'additional_info'   => 'required',
    ];

    /**
     * BookingsController constructor.
     * @param BookingsGateway $bookingsGateway
     * @param DatabaseManager $databaseManager
     */
    public function __construct(BookingsGateway $bookingsGateway, DatabaseManager $databaseManager)
    {
        $this->bookingsGateway  = $bookingsGateway;
        $this->db               = $databaseManager->connection();

        //$this->middleware('auth');
    }

    /**
     * @return JsonResponse
     */
    public function all() : JsonResponse
    {
        // We'll return all the bookings of the user that is
        // currently signed in
        $userId = 'fa59822a-3f55-408c-98a6-e2b7e5905664';

        try {
            $responseCode   = 200;
            $response       = $this->bookingsGateway->getUserBookings($userId);
        } catch (\Exception $e) {
            $responseCode   = 400;
            $response       = ['message' => 'Service not available'];
        }

        return \Response::json($response, $responseCode);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function new(Request $request) : JsonResponse
    {
        $data                   = $request->all();
        $data['fk_user_id']     = 'fa59822a-3f55-408c-98a6-e2b7e5905664';
        $validate       = Validator::make($data, $this->validatorAdd);
        $responseCode   = 200;

        if ($validate->fails()) {
            $response       = ['errors' => $validate->errors()];
            $responseCode   = 400;
        } else {
            $response    = $this->bookingsGateway->create($data);
        }

        return \Response::json($response, $responseCode);
    }

    /**
     * @param int $bookingId
     * @return JsonResponse
     */
    public function get(int $bookingId) : JsonResponse
    {
        $responseCode = 200;
        try {
            $response = Booking::findOrFail($bookingId);
        } catch (ModelNotFoundException $e) {
            $responseCode   = 400;
            $response       = ['message' => "No booking with id of {$bookingId} found"];
        }

        return \Response::json($response, $responseCode);
    }

    /**
     * @param int $bookingId
     * @return JsonResponse
     */
    public function edit(int $bookingId) : JsonResponse
    {

    }

    /**
     * @param int $bookingId
     * @return JsonResponse
     */
    public function cancel(int $bookingId) : JsonResponse
    {

    }

    public function reject(string $bookingId)
    {

    }
}