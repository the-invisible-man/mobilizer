<?php

namespace App\Http\Controllers;

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
 * @author Carlos Granados <carlos@polivet.org>
 */
class BookingsController extends Controller {

    /**
     * @var BookingBuilder
     */
    private $bookingsBuilder;

    /**
     * @var \Illuminate\Database\Connection
     */
    private $db;

    /**
     * @var array
     */
    private $validatorAdd = [
        'id'            => 'required|min:36|min:36',
        'total_people'  => 'required|numeric',
        'message'       => 'required',
    ];

    /**
     * BookingsController constructor.
     * @param BookingBuilder $bookingsBuilder
     * @param DatabaseManager $databaseManager
     */
    public function __construct(BookingBuilder $bookingsBuilder, DatabaseManager $databaseManager)
    {
        $this->bookingsBuilder  = $bookingsBuilder;
        $this->db               = $databaseManager->connection();

        $this->middleware('auth');
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
    public function add(Request $request) : JsonResponse
    {
        $validate       = Validator::make($request->all(), $this->validatorAdd);
        $responseCode   = 200;

        if ($validate->fails()) {
            $response       = ['errors' => $validate->errors()];
            $responseCode   = 400;
        } else {
            $booking    = $this->bookingsBuilder->build($request->all());
            $response   = ['id' => $booking->id];
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
    public function delete(int $bookingId) : JsonResponse
    {

    }
}