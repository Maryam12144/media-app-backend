<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\News\Http\Controllers\ChatRoomController;
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

Route::group(['middleware' => ['api', 'auth', 'admin'], 'prefix' => 'admin/chat-room'], function ($router) {

    /*
     ******************************************
     *           Show chat Room
     ******************************************
    */
    Route::get('', [ChatRoomController::class, 'index'])
        ->name('api.show.chat-room');
        
    /*
     ******************************************
     *           Store chat Room
     ******************************************
    */
    Route::post('', [ChatRoomController::class, 'store'])
        ->name('api.store.chat-room');
     /*
     ******************************************
     *           Show chat Room
     ******************************************
    */
    Route::get('{id}', [ChatRoomController::class, 'show'])
        ->name('api.show.chat-room');
    


        
// updateTotalNotification
    
});