<?php

namespace App\Http\Controllers;

use App\Lib\Packages\Bookings\Exceptions\MismatchException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Lib\Packages\Listings\ListingTypes\RideListing;
use App\Lib\Packages\Listings\Models\ListingMetadata;
use App\Lib\Packages\Listings\ListingsGateway;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Log\Writer;
use Validator;
use App\User;

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

    /**
     * ListingsController constructor.
     * @param ListingsGateway $listingsGateway
     * @param Writer $log
     */
    public function __construct(ListingsGateway $listingsGateway, Writer $log)
    {
        $this->listingsGateway  = $listingsGateway;
        $this->log              = $log;

        if (\Auth::check()) {
            $this->listingsGateway->setCurrentUser(\Auth::user());
        }
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function myListings()
    {
        $userinfo = $this->userInfo();
        return view('my_listings', $userinfo);
    }

    /**
     * @return JsonResponse
     */
    public function all()
    {
        $responseCode = 200;

        try {
            $user       = \Auth::user()->getId();
            $response   = $this->listingsGateway->all($user);
        } catch (\Exception $e) {
            $response       = ['message' => $e->getMessage()];
            $responseCode   = 400;
        }


        return \Response::json($response, $responseCode);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function new(Request $request)
    {
        try {
            $data       = $this->prepareData($request->all());
            $response   = $this->listingsGateway->create($data)->toArray();
            $response   = $this->formatResponse($response);
        } catch (\Exception $e) {
            $this->log->error($e->getMessage());
            $responseCode   = 400;
            $response       = ["message" => "Service not available"];
            return \Response::json($response, $responseCode);
        }

        if ($request->ajax()) {
            return $response;
        } else {
            return $request['type'] == RideListing::ListingType ? view('list_ride_success', $response) : view('list_house_success', $response);
        }
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
        $response['user_email']             = \Auth::user()->getEmail();
        $response['leaving']                = ListingMetadata::translateTimeOfDay($response['metadata']['time_of_day']);

        return $response;
    }

    /**
     * @param array $data
     * @return array
     */
    public function prepareData(array $data) : array
    {
        $data['fk_user_id'] = \Auth::user()->getId();
        return $data;
    }

    /**
     * hacky af
     * @param Request $request,
     * @param string $listingId
     * @return JsonResponse
     */
    public function get(Request $request, string $listingId)
    {
        $responseCode = 200;

        try {
            $listing    = $this->listingsGateway->find($listingId);
            $response   = array_merge($listing->toArray(), $this->userInfo());
            $user       = User::find($response['fk_user_id']);

            if ($user instanceof User) {
                $response['host'] = $user->getFirstName();
            }

            $response['starting_date_raw'] = (new \DateTime($response['starting_date']))->format('M d, Y');
            $response['remaining_slots'] = $this->listingsGateway->remainingSlots($listing->getId());

            if ($listing->getType() == RideListing::ListingType && strlen($request->get('location'))) {
                $pickuptime = $this->listingsGateway->estimateArrivalTime($listing, $request->get('location'));

                // Pick up time is local time of 'location'
                if ($pickuptime instanceof \DateTime) {
                    $response['pickup_time']['raw']     = $pickuptime->format('Y-m-d H:i:s');
                    $response['pickup_time']['date']    = $pickuptime->format('M d, Y');
                    $response['pickup_time']['time']    = $pickuptime->format('h:i A');
                } else {
                    $response['pickup_time'] = null;
                }
            }
        } catch (ModelNotFoundException $e) {
            $responseCode   = 400;
            $response       = ['message' => "No listing with id of {$listingId} found"];
        }

        return \Response::json($response, $responseCode);
    }

    /**
     * @param Request $request
     * @param string $listingId
     * @return JsonResponse
     */
    public function edit(Request $request, string $listingId)
    {
        $responseCode = 200;

        try {
            $user = \Auth::user()->id;
            if (!$this->listingsGateway->ownsListing($listingId, $user)) {
                throw new MismatchException("Cannot delete listing. Listing id {$listingId} does not belong to user {$user}");
            }
            $response = $this->listingsGateway->edit($listingId, $request->all())->toArray();
        } catch (\Exception $e) {
            $responseCode   = 400;
            $response       = ['messages' => $e->getMessage()];
        }

        return \Response::json($response, $responseCode);
    }

    /**
     * @param string $listingId
     * @return JsonResponse
     */
    public function contactInfo(string $listingId)
    {
        $responseCode = 200;

        try {
            $response = $this->listingsGateway->contactInfo($listingId);
        } catch (\Exception $e) {
            $responseCode = 400;
            $response = ['error' => $e->getMessage()];
        }

        return \Response::json($response, $responseCode);
    }

    /**
     * @param Request $request
     * @param string $listingId
     * @throws MismatchException
     * @return JsonResponse
     */
    public function delete(Request $request, string $listingId)
    {
        try {
            $user = \Auth::user()->id;
            if ($this->listingsGateway->ownsListing($listingId, $user)) {
                throw new MismatchException("Cannot delete listing. Listing id {$listingId} does not belong to user {$user}");
            }
            $this->listingsGateway->delete($listingId);
            $response       = ['status' => 'ok'];
            $responseCode   = 200;
        } catch (\Exception $e) {
            $response       = ['message', $e->getMessage()];
            $responseCode   = 400;
        }

        if ($request->ajax()) {
            return \Response::json($response, $responseCode);
        } else {
            return view('');
        }
    }
}