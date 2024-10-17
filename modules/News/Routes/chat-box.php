<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\News\Http\Controllers\ChatBoxController;
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

Route::group(['middleware' => ['api', 'auth', 'admin'], 'prefix' => 'admin/chat-box'], function ($router) {

    /*
     ******************************************
     *           Show chat Box
     ******************************************
    */
    Route::get('', [ChatBoxController::class, 'index'])
        ->name('api.show.chat-box');
        
    /*
     ******************************************
     *           Store Box
     ******************************************
    */
    Route::post('', [ChatBoxController::class, 'store'])
        ->name('api.store.chat-box');
     /*
     ******************************************
     *           Show chat Box
     ******************************************
    */
    Route::get('{id}', [ChatBoxController::class, 'show'])
        ->name('api.show.chat-box');
});