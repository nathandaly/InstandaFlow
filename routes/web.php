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

Route::get('/{hash}/unsubscribe', [
    'as' => 'unsubscribe',
    'uses' => 'SubscriberController@unsubscribe'
]);

Route::get('/key', function() {
    return str_random(32);
});