<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\Admin\Profile\UpdateProfileController;

/*
 **********************************************
 *                  Profile
 **********************************************
 */
Route::middleware(['api', 'auth', 'admin'])->prefix('admin/profile')->group(function () {
    /*
     **********************************************
     *               Update Profile
     **********************************************
     */
    Route::post('/update', [UpdateProfileController::class, 'update'])
        ->name('api.admins.profile.update');
});