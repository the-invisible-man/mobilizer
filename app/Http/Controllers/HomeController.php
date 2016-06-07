<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home', $this->userInfo());
    }

    public function listing(Request $request)
    {
        $type = $request->all();

        return $type['type'] == 'r' ? view('list_ride') : view('list_home');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ride()
    {
        return view('about_ride_listing', $this->userInfo());
    }

    public function housing()
    {
        return view('about_home_listing', $this->userInfo());
    }
}
