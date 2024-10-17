<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\PhoneNumber\UpdatePhoneNumberController;

Route::prefix('user/phone-number')->middleware(['api', 'auth'])->group(function () {
    /*
     ******************************************
     *           Update Phone Number
     ******************************************
     */
    Route::post('/update', [UpdatePhoneNumberController::class, 'update'])
        ->name('api.user.phone-number.update');
});