<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/phpinfo/credentials', function () {
    echo phpinfo(); exit;
});

// Subscriber routes
Route::get('/{hash}/unsubscribe', [
    'as' => 'unsubscribe',
    'uses' => 'SubscriberController@unsubscribe'
]);

Route::post('/{hash}/unsubscribe', [
    'as' => 'unsubscribe',
    'uses' => 'SubscriberController@unsubscribe'
]);

// Key generator route
Route::get('/key/{length}', function($length) {
    return str_random($length);
});

// Auth routes
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
