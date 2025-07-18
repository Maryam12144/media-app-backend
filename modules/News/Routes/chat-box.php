<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\News\Http\Controllers\ChatBoxController;

/*
|--------------------------------------------------------------------------
| Chat Box Routes
|--------------------------------------------------------------------------
|
| These routes are for the ChatBox module. They are loaded with the "api"
| middleware and "admin/chat-box" prefix.
|
*/

Route::group(['middleware' => ['api', 'auth', 'admin'], 'prefix' => 'admin/chat-box'], function () {
    
    // Show chat box list
    Route::get('', [ChatBoxController::class, 'index'])
        ->name('api.show.chat-box.list');
    
    // Store new chat box
    Route::post('', [ChatBoxController::class, 'store'])
        ->name('api.store.chat-box');

    // Show single chat box
    Route::get('{id}', [ChatBoxController::class, 'show'])
        ->name('api.show.chat-box.single');

});
