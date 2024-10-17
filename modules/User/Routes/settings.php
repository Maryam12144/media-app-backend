<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\Settings\UpdateUserSettingsController;

Route::prefix('user/settings')->middleware(['api', 'auth', 'admin'])->group(function () {
    /*
     ******************************************
     *           Update User Setting
     ******************************************
     */
    Route::post('/update', [UpdateUserSettingsController::class, 'update'])
        ->name('api.user.settings.update');
});