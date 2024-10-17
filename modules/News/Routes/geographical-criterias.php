<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\News\Http\Controllers\GeographicalCriteriaController;

/*
|--------------------------------------------------------------------------
| Geographical Criteria
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['api', 'auth', 'admin'], 'prefix' => 'admin/geographical-criterias'], function ($router) {

    /*
     ******************************************
     *           Show Geographical Criterias
     ******************************************
    */
    Route::get('', [GeographicalCriteriaController::class, 'index'])
        ->name('api.show.geographical.criterias');
        
    /*
     ******************************************
     *           Store Geographical Criteria
     ******************************************
    */
    Route::post('', [GeographicalCriteriaController::class, 'store'])
        ->name('api.store.geographical.criteria');
    
    /*
     ******************************************
     *           Show Geographical Criteria
     ******************************************
    */
    Route::get('{geographicalcriteria}', [GeographicalCriteriaController::class, 'show'])
        ->name('api.show.geographical.criteria');
                
    /*
     ******************************************
     *           Update Geographical Criteria
     ******************************************
    */
    Route::post('{geographicalcriteria}', [GeographicalCriteriaController::class, 'update'])
        ->name('api.update.geographical.criteria');
        
    /*
     ******************************************
     *           Delete Geographical Criteria
     ******************************************
    */
    Route::delete('{geographicalcriteria}', [GeographicalCriteriaController::class, 'destroy'])
        ->name('api.delete.geographical.criteria');

});