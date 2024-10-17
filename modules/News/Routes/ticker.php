<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\News\Http\Controllers\TickerController;
/*
|--------------------------------------------------------------------------
| Genre
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['api', 'auth', 'admin'], 'prefix' => 'admin/ticker'], function ($router) {

    /*
     ******************************************
     *           Show chat Ticker
     ******************************************
    */
    Route::get('', [TickerController::class, 'index'])
        ->name('api.show.ticker');
        
    /*
     ******************************************
     *           Store Ticker
     ******************************************
    */
    Route::post('', [TickerController::class, 'store'])
        ->name('api.store.ticker');
     /*
     ******************************************
     *           Show chat Ticker
     ******************************************
    */
    Route::get('{id}', [TickerController::class, 'show'])
        ->name('api.show.ticker');
    /*
    /*
     ******************************************
     *           Update Ticker
     ******************************************
    */
    Route::post('{id}', [TickerController::class, 'update'])
        ->name('api.update.ticker');
});