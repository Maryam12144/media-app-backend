<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use Modules\News\Http\Controllers\ArchiveController;

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

Route::group(['middleware' => ['api', 'auth', 'admin'], 'prefix' => 'admin/archive'], function ($router) {

          /*
     ******************************************
     *           All  Channel
     ******************************************
    */
    Route::get('', [ArchiveController::class, 'index'])
    ->name('api.view.channel');
    /*
     ******************************************
     *           Store Channel
     ******************************************
    */
    Route::post('', [ArchiveController::class, 'store'])
        ->name('api.store.archive');
    
 /*
     ******************************************
     *           Store Channel
     ******************************************
    */
    Route::post('{id}', [ArchiveController::class, 'update'])
        ->name('api.update.archive');


});

