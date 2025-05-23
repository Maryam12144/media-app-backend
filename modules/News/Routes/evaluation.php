<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\News\Http\Controllers\EvaluationController;
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

Route::group(['middleware' => ['api', 'auth', 'admin'], 'prefix' => 'admin/evaluation'], function ($router) {

    /*
     ******************************************
     *           Show Evaluation
     ******************************************
    */
    Route::get('', [EvaluationController::class, 'index'])
        ->name('api.show.evaluation');
        
    /*
     ******************************************
     *           Store Evaluation
     ******************************************
    */
    Route::post('', [EvaluationController::class, 'store'])
        ->name('api.store.evaluation');
    
});