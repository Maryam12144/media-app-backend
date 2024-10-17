<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\News\Http\Controllers\HumanInterestController;

/*
|--------------------------------------------------------------------------
| Human Interest
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['api', 'auth', 'admin'], 'prefix' => 'admin/human-interests'], function ($router) {

    /*
     ******************************************
     *           Show Human Interests
     ******************************************
    */
    Route::get('', [HumanInterestController::class, 'index'])
        ->name('api.show.humaninterests');
        
    /*
     ******************************************
     *           Store Human Interest
     ******************************************
    */
    Route::post('', [HumanInterestController::class, 'store'])
        ->name('api.store.human.interest');
    
    /*
     ******************************************
     *           Show Human Interest
     ******************************************
    */
    Route::get('{humaninterest}', [HumanInterestController::class, 'show'])
        ->name('api.show.human.interest');
                
    /*
     ******************************************
     *           Update Human Interest
     ******************************************
    */
    Route::post('{humaninterest}', [HumanInterestController::class, 'update'])
        ->name('api.update.human.interest');
        
    /*
     ******************************************
     *           Delete Human Interest
     ******************************************
    */
    Route::delete('{humaninterest}', [HumanInterestController::class, 'destroy'])
        ->name('api.delete.human.interest');

});