<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\News\Http\Controllers\SlotTypeController;
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

Route::group(['middleware' => ['api', 'auth', 'admin'], 'prefix' => 'admin/slot-type'], function ($router) {

    /*
     ******************************************
     *           Show chat Box
     ******************************************
    */
    Route::get('', [SlotTypeController::class, 'index'])
        ->name('api.show.slot-type');
        
    /*
     ******************************************
     *           Store Box
     ******************************************
    */
    Route::post('', [SlotTypeController::class, 'store'])
        ->name('api.store.slot-type');
     /*
     ******************************************
     *           Show chat Box
     ******************************************
    */
    Route::get('{id}', [SlotTypeController::class, 'show'])
        ->name('api.show.slot-type');
    /*
     ******************************************
     *           Update Slot
     ******************************************
    */
    Route::post('{id}', [SlotTypeController::class, 'update'])
        ->name('api.update.slot-type');
});