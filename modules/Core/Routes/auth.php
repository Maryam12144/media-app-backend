<?php

//use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;
use Modules\Core\Http\Controllers\Auth\Admin\AdminForgotPasswordController;
use Modules\Core\Http\Controllers\Auth\ForgotPasswordController;
use Modules\Core\Http\Controllers\Auth\Admin\AdminLoginController;
use Modules\Core\Http\Controllers\Auth\Admin\AdminResetPasswordController;
use Modules\Core\Http\Controllers\Auth\ResetPasswordController;
use Modules\Core\Http\Controllers\Auth\LoginController;
use Modules\Core\Http\Controllers\Auth\RegisterController;
use Modules\Core\Http\Controllers\Auth\RequestLoginPinController;
use Modules\Core\Http\Controllers\Auth\VerifyEmailController;
use Modules\Core\Http\Controllers\Auth\VerifyLoginPinController;

#
##########################################
#                 ADMIN
##########################################
#
Route::group(['middleware' => ['api'], 'prefix' => 'admin', 'namespace' => 'Auth\Admin'], function () {
    // these need user not to be logged in
    Route::middleware('guest')->group(function () {
        /*
         ******************************************
         *               Admin Login
         ******************************************
         */
        Route::post('login', [AdminLoginController::class, 'login'])
            ->name('api.admin.login');

        /*
         ****************************************************
         *              Admin Forgot Password
         ****************************************************
         */
        Route::prefix('forgot-password')->group(function () {
            /*
             ************************************************
             *               Request for Email
             ************************************************
             */
            Route::post('/', [AdminForgotPasswordController::class, 'request'])
                ->middleware(['throttle:10,1'])
                ->name('api.admin.forgot-password.request');

            /*
             ******************************************
             *               Verify Pin
             ******************************************
             */
            Route::post('/verify', [AdminResetPasswordController::class, 'verify'])
                ->middleware(['throttle:10,1'])
                ->name('api.admin.forgot-password.verify');

            /*
             ********************************************
             *               Reset Password
             ********************************************
             */
            Route::post('/reset', [AdminResetPasswordController::class, 'reset'])
                ->middleware(['throttle:15,1'])
                ->name('api.admin.forgot-password.reset');
        });
    });

    // user should be logged in for these endpoints
    Route::middleware(['auth', 'admin'])->group(function () {
        /*
         ******************************************
         *           Register and Login
         ******************************************
         */
        Route::get('logout', [AdminLoginController::class, 'logout'])
            ->name('api.admin.logout');
    });
});

#
##########################################
#                  API
##########################################
#
Route::group(['middleware' => ['api'], 'namespace' => 'Auth'], function () {
    Broadcast::routes(['middleware' => 'auth']);

//    /*
//     ******************************************
//     *        CSRF Cookie for Sanctum
//     ******************************************
//     */
//    Route::group(['prefix' => config('sanctum.prefix', 'sanctum')], function () {
//        Route::get('/csrf-cookie', [HomeController::class, 'empty'])
//            ->middleware('web');
//    });

    /*
     ******************************************
     *               Login Pin
     ******************************************
     */
    Route::prefix('login-pin')->group(function () {
        /*
         ******************************************
         *          Request for Login Pin
         ******************************************
         */
        Route::post('/request', [RequestLoginPinController::class, 'request'])
            ->name('api.login-pin.request');

        /*
         ******************************************
         *             Verify Login Pin
         ******************************************
         */
        Route::post('/verify', [VerifyLoginPinController::class, 'verify'])
            ->name('api.login-pin.verify');
    });

    // these need user not to be logged in
    Route::middleware('guest')->group(function () {
        /*
         ******************************************
         *            Register with Pin
         ******************************************
         */
        Route::post('register', [RegisterController::class, 'register'])
            ->name('api.register');

        /*
         ******************************************
         *             Login with Pin
         ******************************************
         */
        Route::post('login', [LoginController::class, 'login'])
            ->name('api.login');

        /*
         ****************************************************
         *                  Forgot Password
         ****************************************************
         */
        Route::prefix('forgot-password')->group(function () {
            /*
             ************************************************
             *               Request for Email
             ************************************************
             */
            Route::post('/', [ForgotPasswordController::class, 'request'])
                ->middleware(['throttle:10,1'])
                ->name('api.forgot-password.request');

            /*
             ******************************************
             *               Verify Pin
             ******************************************
             */
            Route::post('/verify', [ResetPasswordController::class, 'verify'])
                ->middleware(['throttle:10,1'])
                ->name('api.forgot-password.verify');

            /*
             ********************************************
             *               Reset Password
             ********************************************
             */
            Route::post('/reset', [ResetPasswordController::class, 'reset'])
                ->middleware(['throttle:15,1'])
                ->name('api.forgot-password.reset');
        });
    });

    // these need user to be authenticated
    Route::middleware('auth')->group(function () {
        /*
         ***********************************************
         *          Resend Verification Email
         ***********************************************
         */
        Route::post('verify-email/resend', [VerifyEmailController::class, 'resend'])
            ->middleware(['throttle:10,1', 'email-not-verified'])
            ->name('api.verify-email.resend');

        /*
         ******************************************
         *               User Logout
         ******************************************
         */
        Route::get('logout', [LoginController::class, 'logout'])
            ->name('api.logout');
    });

    /*
     ***********************************************
     *              Email Verification
     ***********************************************
     */
    Route::post('/verify-email', [VerifyEmailController::class, 'verify'])
        ->middleware(['throttle:500,1', 'email-not-verified'])
        ->name('api.verify-email.verify');
});