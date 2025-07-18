<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\News\Http\Controllers\ChatRoomController;

Route::group(['middleware' => ['api', 'auth', 'admin'], 'prefix' => 'admin/chat-room'], function ($router) {

    /*
     ******************************************
     *           List chat Rooms
     ******************************************
    */
    Route::get('', [ChatRoomController::class, 'index'])
        ->name('api.index.chat-room'); // Changed from api.show.chat-room to api.index.chat-room
        
    /*
     ******************************************
     *           Store chat Room
     ******************************************
    */
    Route::post('', [ChatRoomController::class, 'store'])
        ->name('api.store.chat-room');

    /*
     ******************************************
     *           Show single chat Room
     ******************************************
    */
    Route::get('{id}', [ChatRoomController::class, 'show'])
        ->name('api.show.chat-room'); // This is the correct usage of api.show.chat-room for a single item
});
