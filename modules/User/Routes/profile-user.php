<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\UserProfileController;

/*
 **********************************************
 *                  Profile
 **********************************************
 */
Route::middleware(['api', 'auth'])->prefix('profile')->group(function () {

    /*
     **********************************************
     *               Store User Profile
     **********************************************
     */
    Route::get('/info', [UserProfileController::class, 'info'])
        ->name('api.user.profile.info');

    /*
     **********************************************
     *               Store User Profile
     **********************************************
     */
    Route::post('/store', [UserProfileController::class, 'store'])
        ->name('api.user.profile.store');
    /*
     **********************************************
     *               Update User Profile
     **********************************************
     */
    Route::post('/update', [UserProfileController::class, 'update'])
        ->name('api.user.profile.update');
});