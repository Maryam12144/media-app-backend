<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\Reset\ProcessResetController;
use Modules\User\Http\Controllers\Reset\RequestResetController;

/*
 ******************************************
 *           Phone Number Reset
 ******************************************
 */
Route::prefix('reset')->middleware(['api', 'guest'])->group(function () {
    /*
     ******************************************
     *            Request for Reset
     ******************************************
     */
    Route::post('/request', [RequestResetController::class, 'request'])
        ->name('api.reset.request');

    /*
     ******************************************
     *           Single Phone Reset
     ******************************************
     */
    Route::prefix('/{reset}')->group(function () {
        /*
         ******************************************
         *              Process Reset
         ******************************************
         */
        Route::any('/', [ProcessResetController::class, 'process'])
            ->name('api.reset.single.process');
    });
});