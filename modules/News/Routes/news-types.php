<?php

use Illuminate\Support\Facades\Route;
use Modules\News\Http\Controllers\NewsTypeController;

Route::group(['middleware' => ['api', 'auth', 'admin'], 'prefix' => 'admin/news-types'], function ($router) {
    /*
     ******************************************
     *           Show All News Types
     ******************************************
     */
    Route::get('', [NewsTypeController::class, 'index'])
        ->name('api.admin.news-types.index');

    /*
     ******************************************
     *           Show Single News Type
     ******************************************
     */
    Route::get('{news}', [NewsTypeController::class, 'show'])
        ->name('api.admin.news-types.show');

    /*
     ******************************************
     *           Store News Type
     ******************************************
     */
    Route::post('', [NewsTypeController::class, 'store'])
        ->name('api.admin.news-types.store');

    /*
     ******************************************
     *           Update News Type
     ******************************************
     */
    Route::post('{news}', [NewsTypeController::class, 'update'])
        ->name('api.admin.news-types.update');

    /*
     ******************************************
     *           Delete News Type
     ******************************************
     */
    Route::delete('{news}', [NewsTypeController::class, 'destroy'])
        ->name('api.admin.news-types.destroy');
});
