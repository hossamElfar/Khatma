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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::group(['prefix' => 'api/v1'], function () {
    /**
     * Users Authentication
     */
    Route::post('register', 'API\AuthAPIController@register');
    Route::get('register/verify/{token}', 'API\AuthAPIController@verify');
    Route::post('login', 'API\AuthAPIController@login');
    Route::post('logout', 'API\AuthAPIController@logout');
    Route::post('update', 'API\AuthAPIController@update');
    /**
     * Cases resource
     */
    Route::resource('cases', 'API\PersonsController');
    /**
     * View all khatmas
     */
    Route::get('khatma', 'API\KhatmaController@index');
    /**
     * View a specific khatma
     */
    Route::get('khatma/{id}', 'API\KhatmaController@show');
    /**
     * Subscribe to a part
     */
    Route::get('parts/subscribe/{part_id}', 'API\PartsController@subscribe');
    /**
     * View a specific part
     */
    Route::get('parts/{id}', 'API\PartsController@show');
    /**
     * Add pages to a specific part
     */
    Route::post('parts/{part_id}', 'API\PartsController@addPage');
    /**
     * View the authenticated user profile
     */
    Route::get('me', 'API\ProfileController@show');
    /**
     * View the profile of a specific user
     */
    Route::get('viewProfile/{id}', 'API\ProfileController@viewProfile');
});