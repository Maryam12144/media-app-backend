<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\News\Http\Controllers\SlotController;
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

Route::group(['middleware' => ['api', 'auth', 'admin'], 'prefix' => 'admin/slot'], function ($router) {

    /*
     ******************************************
     *           Show chat Box
     ******************************************
    */
    Route::get('', [SlotController::class, 'index'])
        ->name('api.show.slot');
        
    /*
     ******************************************
     *           Store Box
     ******************************************
    */
    Route::post('', [SlotController::class, 'store'])
        ->name('api.store.slot');
     /*
     ******************************************
     *           Show chat Box
     ******************************************
    */
    Route::get('{id}', [SlotController::class, 'show'])
        ->name('api.show.slot');
      /*
     ******************************************
     *           Update Slot
     ******************************************
    */
    Route::post('{id}', [SlotController::class, 'update'])
        ->name('api.update.channel');
});