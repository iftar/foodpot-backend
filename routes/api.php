<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Auth
Route::post('login', 'AuthController@login');
Route::post('register', 'AuthController@register');

Route::group(['middleware' => ['auth:api']], function () {

    // Users API
    Route::group(['prefix' => 'user', 'name' => 'user.', 'namespace' => 'User'], function () {
        Route::get('/orders', 'OrderController');
    });

    // Charity Users API
    Route::group(['prefix' => 'charity', 'name' => 'charity.', 'namespace' => 'Charity'], function () {
        Route::get('/orders', 'OrderController');
    });

    // Collection Point Users API
    Route::group(['prefix' => 'collection-point', 'name' => 'collection-point.', 'namespace' => 'CollectionPoint'], function () {
        Route::get('/orders', 'OrderController');
    });

    Route::get('user', 'UserController@index');
    Route::get('logout', 'AuthController@logout');
});
