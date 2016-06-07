<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
use App\User;
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

// Some of this stuff is not gonna be very "RESTful", forgive me ahead of time.

Route::group(['middleware' => ['web']], function () {
    Route::put('accounts/{account_id}', 'AccountsController@edit');
    Route::delete('accounts/{account_id}', 'AccountsController@delete');
});

Route::group(['middleware' => 'web'], function () {
    Route::auth();
    Route::get('/', 'HomeController@index');
    Route::get("search", "SearchController@search");
    Route::get("about_rides", "HomeController@ride");
    Route::get("about_housing", "HomeController@housing");

    Route::group(['prefix' => 'listings'], function () {
        Route::get('/', 'ListingsController@all');
        Route::post('/', ['middleware' => 'auth', 'uses' => 'ListingsController@new']);
        Route::get('{listing_id}', 'ListingsController@get');
        Route::put('{listing_id}', ['middleware' => 'auth', 'uses' => 'ListingsController@edit']);
        Route::delete('{listing_id}', ['middleware' => 'auth', 'uses' => 'ListingsController@delete']);
    });

    Route::get('/add-listing', ['middleware' => 'auth', 'uses' => 'HomeController@listing']);
    Route::get('/success-add-listing', 'ListingsController@testSuccess');
});


Route::group(['prefix' => 'bookings'], function () {
    Route::get('/', 'BookingsController@all');
    Route::post('/', 'BookingsController@new');
    Route::get('{booking_id}', 'BookingsController@get');
    Route::put('{booking_id}', 'BookingsController@edit');


    // Booking Actions
    Route::post("{booking_id}/accept", "BookingsController@accept");
    Route::post("{booking_id}/reject", "BookingsController@reject");
    Route::delete('{booking_id}', 'BookingsController@cancel');
});




Route::put('accounts/{account_id}', 'AccountsController@edit');
Route::delete('accounts/{account_id}', 'AccountsController@delete');



Route::group(['prefix' => 'api'], function () {
    Route::get('accounts/{account_id}', 'AccountsController@get');
    Route::post('accounts/{account_id}', 'AccountsController@new');


    //Route::get('search', 'SearchController@index');
});

Route::get('test', function () {
    $location = \App\Lib\Packages\Geo\Location\Location::find('c80ea16d-2093-4965-b087-63710756ef68');
    echo $location->__toString();
});
