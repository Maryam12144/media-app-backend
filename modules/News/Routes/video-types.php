<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\News\Http\Controllers\VideoTypeController;

/*
|--------------------------------------------------------------------------
| Video Type
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['api', 'auth', 'admin'], 'prefix' => 'admin/video-types'], function ($router) {

    /*
     ******************************************
     *           Show Video Types
     ******************************************
    */
    Route::get('', [VideoTypeController::class, 'index'])
        ->name('api.show.video.types');
        
    /*
     ******************************************
     *           Store Video Type
     ******************************************
    */
    Route::post('', [VideoTypeController::class, 'store'])
        ->name('api.store.video.type');
    
    /*
     ******************************************
     *           Show Video Type
     ******************************************
    */
    Route::get('{videotype}', [VideoTypeController::class, 'show'])
        ->name('api.show.video.type');
                
    /*
     ******************************************
     *           Update Video Type
     ******************************************
    */
    Route::post('{videotype}', [VideoTypeController::class, 'update'])
        ->name('api.update.video.type');
        
    /*
     ******************************************
     *           Delete Video Type
     ******************************************
    */
    Route::delete('{videotype}', [VideoTypeController::class, 'destroy'])
        ->name('api.delete.video.type');

});