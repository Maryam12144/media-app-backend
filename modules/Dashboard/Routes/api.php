<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Dashboard API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['api', 'auth', 'admin'], 'prefix' => 'dashboard'], function ($router) {
    $router->get('stats', 'DashboardController@index');
});