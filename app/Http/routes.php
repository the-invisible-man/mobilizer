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

Route::group(['middleware' => ['web']], function () {
//    Route::get('listings', 'ListingsController@all');
//    Route::get('listings/{listing_id}', 'ListingsController@get');
//    Route::put('listings/{listing_id}', 'ListingsController@edit');
//    Route::post('listings/{listing_id}', 'ListingsController@new');
//    Route::delete('listings/{listing_id}', 'ListingsController@delete');
//
//    Route::get('bookings', 'BookingsController@all');
//    Route::get('bookings/{booking_id}', 'BookingsController@get');
//    Route::put('bookings/{booking_id}', 'BookingsController@edit');
//    Route::post('bookings/{booking_id}', 'BookingsController@new');
//    Route::delete('bookings/{booking_id}', 'BookingsController@delete');
//
//    Route::put('accounts/{account_id}', 'AccountsController@edit');
//    Route::delete('accounts/{account_id}', 'AccountsController@delete');
});


Route::get('listings', 'ListingsController@all');
Route::get('listings/{listing_id}', 'ListingsController@get');
Route::put('listings/{listing_id}', 'ListingsController@edit');
Route::post('listings/{listing_id}', 'ListingsController@new');
Route::delete('listings/{listing_id}', 'ListingsController@delete');

Route::get('bookings', 'BookingsController@all');
Route::get('bookings/{booking_id}', 'BookingsController@get');
Route::put('bookings/{booking_id}', 'BookingsController@edit');
Route::post('bookings/{booking_id}', 'BookingsController@new');
Route::delete('bookings/{booking_id}', 'BookingsController@delete');

Route::put('accounts/{account_id}', 'AccountsController@edit');
Route::delete('accounts/{account_id}', 'AccountsController@delete');



Route::get('/add-listing', 'HomeController@listing');

Route::group(['middleware' => 'web'], function () {
    Route::auth();
    Route::get('/home', 'HomeController@index');
});

Route::group(['prefix' => 'api'], function () {
    Route::get('accounts/{account_id}', 'AccountsController@get');
    Route::post('accounts/{account_id}', 'AccountsController@new');


    Route::get('search', 'SearchController@index');
});
