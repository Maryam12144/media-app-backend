<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\Admin\Info\AdminGetUserInfoController;
use Modules\User\Http\Controllers\GetUserInfoController;
use Modules\User\Http\Controllers\Settings\UpdateUserSettingsController;
use Modules\User\Http\Controllers\UserSummaryController;

#
##############################################
#                   ADMIN
##############################################
#
Route::middleware(['api', 'auth', 'admin'])->prefix('admin/user')->group(function () {
    /*
     ******************************************
     *              Get User Info
     ******************************************
     */
    Route::get('/info', [AdminGetUserInfoController::class, 'info'])
        ->name('api.admin.user.info');
});

#
##############################################
#                     API
##############################################
#
Route::prefix('user')->middleware(['api', 'auth'])->group(function () {
    /*
     ******************************************
     *              Get User Info
     ******************************************
     */
    Route::get('/info', [GetUserInfoController::class, 'info'])
        ->name('api.user.info');

    /*
     ******************************************
     *             Get User Summary
     ******************************************
     */
    Route::get('/summary', [UserSummaryController::class, 'summary'])
        ->name('api.user.summary');

    /*
     ******************************************
     *           Update User Setting
     ******************************************
     */
    Route::post('/update', [UpdateUserSettingsController::class, 'update'])
        ->name('api.user.settings.update');
});