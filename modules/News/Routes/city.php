<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\News\Http\Controllers\CityController;

/*
|--------------------------------------------------------------------------
| News
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['api', 'auth', 'admin'], 'prefix' => 'admin/city'], function ($router) {
   /*
     ******************************************
     *           All City
     ******************************************
    */
    Route::get('', [CityController::class, 'index'])
        ->name('api.view.city');
        
    /*
     ******************************************
     *           Store City
     ******************************************
    */
    Route::post('', [CityController::class, 'store'])
        ->name('api.store.city');
    
 /*
     ******************************************
     *           update City
     ******************************************
    */
    Route::post('{id}', [CityController::class, 'update'])
        ->name('api.update.city');


});

