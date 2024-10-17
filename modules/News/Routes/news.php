<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\News\Http\Controllers\NewsController;
use Modules\News\Http\Controllers\ChatRoomController;
use Modules\News\Http\Controllers\TickerController;

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

Route::group(['middleware' => ['api', 'auth', 'admin'], 'prefix' => 'admin/news'], function ($router) {

    /*
     ******************************************
     *           Show All News
     ******************************************
    */
    Route::get('', [NewsController::class, 'index'])
        ->name('api.show.news');
        
    /*
     ******************************************
     *           Store News
     ******************************************
    */
    Route::post('', [NewsController::class, 'store'])
        ->name('api.store.news');
    
    /*
     ******************************************
     *           Show Single News
     ******************************************
    */
    Route::get('{news}', [NewsController::class, 'show'])
        ->name('api.show.news');
                
    /*
     ******************************************
     *           Update News
     ******************************************
    */
    Route::post('{news}', [NewsController::class, 'update'])
        ->name('api.update.news');
        
    /*
     ******************************************
     *           Delete News
     ******************************************
    */
    Route::delete('{news}', [NewsController::class, 'destroy'])
        ->name('api.delete.news');

         /*
     ******************************************
     *           Upload News Video
     ******************************************
    */
    Route::post('/upload/video', [NewsController::class, 'uploadVideo'])
        ->name('api.store.vimeo.video');  


});

Route::group(['middleware' => ['api'], 'prefix' => '/news'], function ($router) {

    /*
     ******************************************
     *           Show All News
     ******************************************
    */
    Route::get('', [ChannelController::class, 'index'])
        ->name('web.show.channel');
    Route::get('/ajax', [ChannelController::class, 'ajaxIndex'])
        ->name('web.show.ajax.channel');  
    
                   /*
     ******************************************
     *           Total Notification
     ******************************************
    */
    
    Route::get('/notification', [ChatRoomController::class, 'getTotalNotification'])
    ->name('api.chat-room.notification');
        
                   /*
     ******************************************
     *           Update Total Notification
     ******************************************
    */
    
    Route::get('/notification/{id}', [ChatRoomController::class, 'updateTotalNotification'])
    ->name('api.chat-room.notification');
      /*
     ******************************************
     *           Get Modify  Videos
     ******************************************
    */
    Route::get('/modify-videos', [NewsController::class, 'getPosterModifyVideos'])
    ->name('api.show.modify.videos');  
       /*
     ******************************************
     *           Get Modify Video Detail
     ******************************************
    */
    Route::get('/modify-video/{id}', [NewsController::class, 'getReporterModifyVideo'])
        ->name('api.show.pending.videos'); 
     /*
     ******************************************
     *           Get Pending Videos
     ******************************************
    */
    
    Route::get('/pending-videos', [NewsController::class, 'getReporterPendingVideos'])
        ->name('api.show.pending.videos');   
      /*
     ******************************************
     *           Get Pending Video
     ******************************************
    */
    Route::get('/pending-video/{id}', [NewsController::class, 'getReporterSinglePendingVideo'])
        ->name('api.show.pending.videos');  
      /*
     ******************************************
     *           Get Pending Video
     ******************************************
    */
    Route::get('/count-views/{id}', [NewsController::class, 'updateTotalViews'])
        ->name('api.update.total.views');    

});

Route::group(['middleware' => ['api', 'auth', 'admin'], 'prefix' => 'admin/ticker'], function ($router) {

    Route::get('/pending-list', [TickerController::class, 'pendingTicker'])
        ->name('api.ticker.pending-list');
    /*
    ******************************************
    *           Evaluate Ticker
    ******************************************
    */
    Route::get('/evaluate-list', [TickerController::class, 'evaluateTicker'])
        ->name('api.ticker.evaluate-list');
 
});