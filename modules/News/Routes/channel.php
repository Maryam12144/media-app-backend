<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\News\Http\Controllers\NewsController;
use Modules\News\Http\Controllers\ChatRoomController;

use Modules\News\Http\Controllers\ChannelController;

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

Route::group(['middleware' => ['api', 'auth', 'admin'], 'prefix' => 'admin/channel'], function ($router) {

          /*
     ******************************************
     *           All  Channel
     ******************************************
    */
    Route::get('', [ChannelController::class, 'index'])
    ->name('api.view.channel');
    /*
     ******************************************
     *           Store Channel
     ******************************************
    */
    Route::post('', [ChannelController::class, 'store'])
        ->name('api.store.channel');
    
 /*
     ******************************************
     *           Store Channel
     ******************************************
    */
    Route::post('{id}', [ChannelController::class, 'update'])
        ->name('api.update.channel');


});

