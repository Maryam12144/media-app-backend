<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\News\Http\Controllers\EvaluateTickerController;
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

Route::group(['middleware' => ['api', 'auth', 'admin'], 'prefix' => 'admin/evaluator-ticker'], function ($router) {

    /*
     ******************************************
     *           Show chat Box
     ******************************************
    */
    Route::get('', [EvaluateTickerController::class, 'index'])
        ->name('api.show.evaluator-ticker');
        
    /*
     ******************************************
     *           Store Box
     ******************************************
    */
    Route::post('{id}', [EvaluateTickerController::class, 'store'])
        ->name('api.store.evaluator-ticker');
     /*
     ******************************************
     *           Show chat Box
     ******************************************
    */
    Route::get('{id}', [EvaluateTickerController::class, 'show'])
        ->name('api.show.evaluator-ticker');
      /*
     ******************************************
     *           Update Slot
     ******************************************
    */
    // Route::post('{id}', [EvaluateTickerController::class, 'update'])
    //     ->name('api.update.evaluator-ticker');
});