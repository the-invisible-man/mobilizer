<?php

namespace App\Http\Controllers;

class BlogController extends Controller
{
    public function __construct()
    {

    }

    public function events()
    {
        return view('events', $this->userInfo());
    }
}