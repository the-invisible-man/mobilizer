<?php

namespace App\Http\Controllers;

use App\Lib\Packages\Bookings\BookingsGateway;
use App\Lib\Packages\Bookings\Contracts\BaseBooking;
use App\Lib\Packages\Bookings\Exceptions\MismatchException;
use App\Lib\Packages\Listings\ListingsGateway;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Mail\Message;
use Validator;
use App\User;
use Illuminate\Contracts\Mail\Mailer;

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
     * @var Mailer
     */
    private $mailer;

    /**
     * @var array
     */
    private $validatorAdd = [
        'fk_listing_id'     => 'required|min:36|min:36',
        'total_people'      => 'required|numeric',
        'additional_info'   => 'required',
    ];

    /**
     * @var ListingsGateway
     */
    private $listingsGateway;

    /**
     * BookingsController constructor.
     * @param BookingsGateway $bookingsGateway
     * @param DatabaseManager $databaseManager
     * @param ListingsGateway $listingsGateway
     * @param Mailer $mailer
     */
    public function __construct(BookingsGateway $bookingsGateway, DatabaseManager $databaseManager, ListingsGateway $listingsGateway, Mailer $mailer)
    {
        $this->bookingsGateway  = $bookingsGateway;
        $this->db               = $databaseManager->connection();
        $this->listingsGateway  = $listingsGateway;
        $this->mailer           = $mailer;

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
            $response       = $this->formatAll($response);
        } catch (\Exception $e) {
            $responseCode   = 400;
            $response       = ['message' => $e->getMessage()];
        }

        if ($request->ajax()) {
            return \Response::json($response, $responseCode);
        } else{
            // return a view
            return view('my_bookings', $response);
        }
    }

    public function formatAll($result)
    {
        return array_merge($this->userInfo(), ['bookings' => $result]);
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

        try {
            if ($validate->fails()) {
                $response = ['errors' => $validate->errors()];
                $responseCode = 400;
            } else {
                $response = $this->bookingsGateway->create($data);
                $response = $this->formatNewBooking($response);
                $this->sendNotificationEmails($response);
            }
        } catch (\Exception $e) {
            $response = ['errors' => $e->getMessage()];
            $responseCode = 400;
            return \Response::json($response, $responseCode);
        }

        if ($request->ajax()) {
            return \Response::json($response, $responseCode);
        }

        return view('booking_confirm', $response);
    }

    /**
     * @param BaseBooking $booking
     * @return array
     */
    private function formatNewBooking(BaseBooking $booking)
    {
        // We need to respond with a few pieces of information
        // There are partial bits of data of the listing, user, and booking.
        $listing                    = $this->listingsGateway->find($booking->getFkListingId()); $booking->getMetadata()->getLocation()->getCity();
        $pickUpTime                 = $this->listingsGateway->estimateArrivalTime($listing, (string)$booking->getMetadata()->getLocation());
        $booking                    = $booking->toArray();
        $booking['listing']         = $listing->toArray();
        $booking['starting_date']   = $pickUpTime->format('m d, Y @ H:i:s');
        $booking['ending_date']     = (new \DateTime($listing->getEndingDate()))->format('m d, Y');
        $booking['user_email']      = \Auth::user()->getEmail();
        $user                       = User::find($listing->getFkUserId());

        if ($user instanceof User) {
            $booking['listing']['host'] = $user->getFirstName();
            $booking['listing']['host_email'] = $user->getEmail();
        }

        return array_merge($booking, $this->userInfo());
    }

    /**
     * @param array $response
     */
    public function sendNotificationEmails(array $response)
    {
        $data['from_user_first_name']   = \Auth::user()->getFirstName();
        $data['from_user_last_name']    = \Auth::user()->getLastName();
        $data['from_city']              = $response['listing']['location']['city'];
        $data['from_state']             = $response['listing']['location']['state'];
        $data['additional_info']        = $response['additional_info'];
        $data['pickup_city']            = $response['metadata']['location']['city'];
        $data['pickup_state']           = $response['metadata']['location']['state'];
        $data['host_first_name']        = $response['listing']['host'];
        $data['to_email']               = $response['listing']['host_email'];
        $data['total_people']           = $response['total_people'];
        $data['confirm_to_email']       = \Auth::user()->getEmail();
        $data['user_first_name']        = \Auth::user()->getFirstName();


        // Push notification email to queue
        // UPDATE: Queue was sporadically sending these what the fuck thanks a lot.
        // Maybe move to terminable middleware
        $this->mailer->send('emails.booking_notification', $data, function (Message $email) use($data) {
            $email->to($data['to_email']);
            $email->from('no-reply@seeyouinphilly.com', '#SeeYouInPhilly Alerts');
            $email->subject('Your Have a New Request');
        });

        $this->mailer->send('emails.booking_confirmation', $data, function (Message $email) use($data) {
            $email->to($data['confirm_to_email']);
            $email->from('no-reply@seeyouinphilly.com', '#SeeYouInPhilly Alerts');
            $email->subject('Your Booking Request Has Been Sent');
        });
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
            $userid = \Auth::user()->getId();
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
            $userId = \Auth::user()->getId();
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
            $userId = \Auth::user()->getId();
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
            $userId = \Auth::user()->getId();
            $this->bookingsGateway->reject($bookingId, $userId);
        } catch (\Exception $e) {
            return \Response::json(['message' => 'Service not available'], 400);
        }

        if ($request->ajax()) {
            return \Response::json(['status' => 'ok'], 200);
        }
    }
}