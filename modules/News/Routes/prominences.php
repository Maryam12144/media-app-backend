<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\News\Http\Controllers\ProminenceController;

/*
|--------------------------------------------------------------------------
| Prominence
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['api', 'auth', 'admin'], 'prefix' => 'admin/prominences'], function ($router) {

    /*
     ******************************************
     *           Show Prominences
     ******************************************
    */
    Route::get('', [ProminenceController::class, 'index'])
        ->name('api.show.prominences');
        
    /*
     ******************************************
     *           Store Prominence
     ******************************************
    */
    Route::post('', [ProminenceController::class, 'store'])
        ->name('api.store.prominence');
    
    /*
     ******************************************
     *           Show Prominence
     ******************************************
    */
    Route::get('{prominence}', [ProminenceController::class, 'show'])
        ->name('api.show.prominence');
                
    /*
     ******************************************
     *           Update Prominence
     ******************************************
    */
    Route::post('{prominence}', [ProminenceController::class, 'update'])
        ->name('api.update.prominence');
        
    /*
     ******************************************
     *           Delete Prominence
     ******************************************
    */
    Route::delete('{prominence}', [ProminenceController::class, 'destroy'])
        ->name('api.delete.prominence');

});