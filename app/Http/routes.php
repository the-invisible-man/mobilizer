<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
| Some of this stuff is not gonna be very restful", forgive me ahead of time.
|
*/

Route::group(['middleware' => 'web'], function () {

    /*
    |--------------------------------------------------------------------------
    | Root Level URLs
    |--------------------------------------------------------------------------
    |
    | We have the homepage, tos, policy, and other stuff.
    |
    */
    Route::auth();
    Route::get('/', 'HomeController@index');
    Route::get("search", "SearchController@search");
    Route::get("about_rides", "HomeController@ride");
    Route::get("about_housing", "HomeController@housing");
    Route::get('my-requests', ['middleware' => 'auth', 'uses' => 'BookingsController@myRequests']);
    Route::get('my-listings', ['middleware' => 'auth', 'uses' => 'ListingsController@myListings']);
    Route::get("facebook", "Auth\\AuthController@facebook");
    Route::get('add-listing', ['middleware' => 'auth', 'uses' => 'HomeController@listing']);
    Route::get('success-add-listing', 'ListingsController@testSuccess');
    Route::get('tos', 'HomeController@tos');
    Route::get('privacy', 'HomeController@privacy');
    Route::get('about', "HomeController@about");
    Route::post('notifications', 'HomeController@signUpNotifications');

    $this->get('account/confirm/{confirmation_code}', 'Auth\AuthController@confirmEmail');
    $this->get('account/send_confirm', 'Auth\AuthController@resendEmailConfirm');

    /*
    |--------------------------------------------------------------------------
    | Listings
    |--------------------------------------------------------------------------
    |
    | All listing related logic. A mix of rest endpoints and endpoints
    | that server laravel views.
    |
    */
    Route::group(['prefix' => 'listings'], function () {

        Route::get('contact/{listing_id}', ['middleware' => 'auth', 'uses' => "ListingsController@contactInfo"]);

        Route::get('/', ['middleware' => 'auth', 'uses' => 'ListingsController@all']);
        Route::post('/', ['middleware' => 'auth', 'uses' => 'ListingsController@new']);
        Route::get('{listing_id}', 'ListingsController@get');
        Route::put('{listing_id}', ['middleware' => 'auth', 'uses' => 'ListingsController@edit']);
        Route::delete('{listing_id}', ['middleware' => 'auth', 'uses' => 'ListingsController@delete']);
    });


    /*
     |--------------------------------------------------------------------------
     | Requests
     |--------------------------------------------------------------------------
     |
     | Logic for listing owner to be able to deny, accept, cancel
     | requests. Also talks to the bookings gateway at some point,
     | it's kinda weird.
     |
     */
    Route::group(['prefix' => 'requests'], function () {

        Route::get('/', ['middleware' => 'auth', 'uses' => 'BookingsController@getUserRequests']);

        // Booking Request Actions
        Route::get("{booking_id}/accept", ['middleware' => 'auth', 'uses' => "BookingsController@accept"]);
        Route::get("{booking_id}/reject", ['middleware' => 'auth', 'uses' => "BookingsController@reject"]);
        Route::get("{booking_id}/cancel", ['middleware' => 'auth', 'uses' => "BookingsController@reject"]);

        Route::delete('{booking_id}', ['middleware' => 'auth', 'uses' => 'BookingsController@cancel']);
    });


    /*
     |--------------------------------------------------------------------------
     | Bookings
     |--------------------------------------------------------------------------
     |
     | User bookings/ride/hosing requests.
     |
     */
    Route::group(['prefix' => 'bookings'], function () {

        Route::get('contact/{booking_id}', ['middleware' => 'auth', 'uses' => "BookingsController@contactInfo"]);


        Route::get('/', ['middleware' => 'auth', 'uses' => 'BookingsController@all']);
        Route::post('/', ['middleware' => 'auth', 'uses' => 'BookingsController@new']);
        Route::get('{booking_id}', ['middleware' => 'auth', 'uses' => 'BookingsController@get']);
        Route::put('{booking_id}', ['middleware' => 'auth', 'uses' => 'BookingsController@edit']);
        Route::delete('{booking_id}', ['middleware' => 'auth', 'uses' => 'BookingsController@cancel']);
    });
});

Route::post('/mail/relay', 'EmailRelayController@receive');