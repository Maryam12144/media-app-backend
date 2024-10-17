<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\News\Http\Controllers\NewsTypeController;

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

Route::group(['middleware' => ['api', 'auth', 'admin'], 'prefix' => 'admin/news-types'], function ($router) {

    /*
     ******************************************
     *           Show All News
     ******************************************
    */
    Route::get('', [NewsTypeController::class, 'index'])
        ->name('api.show.news.types');
        
    /*
     ******************************************
     *           Store News
     ******************************************
    */
    Route::post('', [NewsTypeController::class, 'store'])
        ->name('api.store.news.types');
    
    /*
     ******************************************
     *           Show Single News
     ******************************************
    */
    Route::get('{news}', [NewsTypeController::class, 'show'])
        ->name('api.show.news.types');
                
    /*
     ******************************************
     *           Update News
     ******************************************
    */
    Route::post('{news}', [NewsTypeController::class, 'update'])
        ->name('api.update.news.types');
        
    /*
     ******************************************
     *           Delete News
     ******************************************
    */
    Route::delete('{news}', [NewsTypeController::class, 'destroy'])
        ->name('api.delete.news.types');

});