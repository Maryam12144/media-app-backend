<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\News\Http\Controllers\GenreController;
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

Route::group(['middleware' => ['api', 'auth', 'admin'], 'prefix' => 'admin/genres'], function ($router) {

    /*
     ******************************************
     *           Show Genres
     ******************************************
    */
    Route::get('', [GenreController::class, 'index'])
        ->name('api.show.genres');
        
    /*
     ******************************************
     *           Store Genre
     ******************************************
    */
    Route::post('', [GenreController::class, 'store'])
        ->name('api.store.genre');
    
    /*
     ******************************************
     *           Show Genre
     ******************************************
    */
    Route::get('{genre}', [GenreController::class, 'show'])
        ->name('api.show.genre');
                
    /*
     ******************************************
     *           Update Genre
     ******************************************
    */
    Route::post('{genre}', [GenreController::class, 'update'])
        ->name('api.update.genre');
        
    /*
     ******************************************
     *           Delete Genre
     ******************************************
    */
    Route::delete('{genre}', [GenreController::class, 'destroy'])
        ->name('api.delete.genre');

});