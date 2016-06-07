<?php

namespace App\Http\Controllers;

use App\Lib\Packages\Bookings\BookingsGateway;
use App\Lib\Packages\Bookings\Exceptions\MismatchException;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
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

        if (\Auth::check()) {
            $this->bookingsGateway->setCurrentUser(\Auth::user());
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|JsonResponse|\Illuminate\View\View
     */
    public function all(Request $request)
    {
        try {
            $responseCode   = 200;
            $response       = $this->bookingsGateway->getUserBookings();
        } catch (\Exception $e) {
            $responseCode   = 400;
            $response       = ['message' => $e->getMessage()];
        }

        if ($request->ajax()) {
            return \Response::json($response, $responseCode);
        } else{
            // return a view
            return \Response::json($response, $responseCode);
        }
    }

    /**
     * @param Request $request
     * @param string $listingId
     * @return \Illuminate\Contracts\View\Factory|JsonResponse|\Illuminate\View\View
     */
    public function allByListing(Request $request, string $listingId)
    {
        try {
            $responseCode   = 200;
            $userId         = '';
            $response       = $this->bookingsGateway->getAllBookingsForListing($listingId, $userId);
        } catch (\Exception $e) {
            $responseCode   = 400;
            $response       = ['message' => $e->getMessage()];
        }

        if ($request->ajax()) {
            return \Response::json($response, $responseCode);
        } else{
            // return a view
            return view('');
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function new(Request $request)
    {
        $data                   = $request->all();
        $data['fk_user_id']     = \Auth::user()->getId();
        $validate               = Validator::make($data, $this->validatorAdd);
        $responseCode           = 200;

        if ($validate->fails()) {
            $response       = ['errors' => $validate->errors()];
            $responseCode   = 400;
        } else {
            $response    = $this->bookingsGateway->create($data);
        }

        if ($request->ajax()) {
            return \Response::json($response, $responseCode);
        } else {

        }
    }

    /**
     * @param string $bookingId
     * @return JsonResponse
     */
    public function get(string $bookingId)
    {
        $responseCode = 200;

        try {
            $response = $this->bookingsGateway->find($bookingId);
        } catch (ModelNotFoundException $e) {
            $responseCode   = 400;
            $response       = ['message' => "No booking with id of {$bookingId} found"];
        }

        return \Response::json($response, $responseCode);
    }

    /**
     * @param Request $request
     * @param string $bookingId
     * @return JsonResponse
     */
    public function edit(Request $request, string $bookingId)
    {
        $responseCode = 200;

        try {
            // Make sure user owns this booking
            $userid = '';
            if (!$this->bookingsGateway->ownsBooking($bookingId, $userid)) {
                throw new MismatchException("Booking to user exception: User {$userid} does not own booking {$bookingId}");
            }
            $response = $this->bookingsGateway->edit($bookingId, $request->all())->toArray();
        } catch (\Exception $e) {
            $responseCode   = 400;
            $response       = ['message' => "Service not available"];
        }

        if ($request->ajax()) {
            return \Response::json($response, $responseCode);
        }
    }

    /**
     * @param Request $request
     * @param string $bookingId
     * @return JsonResponse
     */
    public function cancel(Request $request, string $bookingId)
    {
        try {
            $userId = 'fa59822a-3f55-408c-98a6-e2b7e5905664';
            $this->bookingsGateway->cancel($bookingId, $userId);
        } catch (\Exception $e) {
            return \Response::json(['message' => 'Service not available'], 400);
        }

        if ($request->ajax()) {
            return \Response::json(['status' => 'ok'], 200);
        }
    }

    /**
     * @param Request $request
     * @param string $bookingId
     * @return JsonResponse
     */
    public function accept(Request $request, string $bookingId)
    {
        try {
            $userId = "fa59822a-3f55-408c-98a6-e2b7e5905664";
            $this->bookingsGateway->accept($bookingId, $userId);
        } catch (\Exception $e) {
            return \Response::json(['message' => 'Service not available'], 400);
        }

        if ($request->ajax()) {
            return \Response::json(['status' => 'ok'], 200);
        }
    }

    /**
     * @param Request $request
     * @param string $bookingId
     * @return JsonResponse
     */
    public function reject(Request $request, string $bookingId)
    {
        try {
            $userId = "fa59822a-3f55-408c-98a6-e2b7e5905664";
            $this->bookingsGateway->reject($bookingId, $userId);
        } catch (\Exception $e) {
            return \Response::json(['message' => 'Service not available'], 400);
        }

        if ($request->ajax()) {
            return \Response::json(['status' => 'ok'], 200);
        }
    }
}