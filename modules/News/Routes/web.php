<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\News\Http\Controllers\ChannelController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['web'], 'prefix' => '/news'], function ($router) {

    /*
     ******************************************
     *           Show All News
     ******************************************
    */

    Route::get('/kanact-media', [ChannelController::class, 'channelStreamOnVimeo'])
        ->name('web.show.channel.kanact');

    Route::get('', [ChannelController::class, 'channelStream'])
        ->name('web.show.channel.index');

    Route::get('/din-news', [ChannelController::class, 'demoChannel'])
        ->name('web.show.channel.din-news');

    Route::get('/channel/{id}', [ChannelController::class, 'getChannelByID'])
        ->name('web.view.channel');

    Route::get('/channel-list', [ChannelController::class, 'getChannelList'])
        ->name('web.show.channel.list');

    Route::get('/channel-ajax/{id}', [ChannelController::class, 'channelAjax'])
        ->name('web.show.channel-ajax');

    Route::get('/channel-ticker/{id}', [ChannelController::class, 'channelTicker'])
        ->name('web.show.channel-ticker');

    Route::get('/ajaxindex', [ChannelController::class, 'ajaxIndex'])
        ->name('web.show.ajax.channel');

});
