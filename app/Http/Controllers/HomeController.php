<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Lib\Packages\Core\EmailListForNotifications;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function signUpNotifications(Request $request)
    {
        $email = $request->get('email');
        $query = $request->get('query');

        $data  = EmailListForNotifications::create([
            'email' => $email,
            'query' => $query
        ]);

        return view('confirm_notify', $data->toArray());
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home', $this->userInfo());
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function listing(Request $request)
    {
        $type = $request->all();

        return $type['type'] == 'r' ? view('list_ride', $this->userInfo()) : view('list_home', $this->userInfo());
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function privacy()
    {
        return view('privacy_policy', array_merge($this->userInfo(), ['page_meta_title' => 'Privacy Policy']));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tos()
    {
        return view('tos', array_merge($this->userInfo(), ['page_meta_title' => 'Terms of Service']));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ride()
    {
        return view('about_ride_listing', array_merge($this->userInfo(), ['page_meta_title' => 'List Your Ride']));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function housing()
    {
        return view('about_home_listing', $this->userInfo());
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function about()
    {
        return view('about', array_merge($this->userInfo(), ['page_meta_title' => 'About']));
    }
}
