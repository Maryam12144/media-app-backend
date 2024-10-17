<?php

use Modules\Core\Http\Controllers\Admin\Config\ListConfigsController;
use Modules\Core\Http\Controllers\Admin\Config\UpdateConfigController;
use Modules\Core\Http\Controllers\OptionsController;

#####################################
#               ADMIN
#####################################

Route::middleware(['api', 'auth', 'admin'])->prefix('admin/options')->group(function () {
    /*
     *****************************************
     *              List Configs
     *****************************************
     */
    Route::get('/', [ListConfigsController::class, 'index'])
        ->name('api.admin.configs.index');

    /*
     *******************************************
     *              Single Option
     *******************************************
     */
    Route::prefix('{config}')->group(function () {
        /*
         *******************************************
         *              Update Option
         *******************************************
         */
        Route::post('/update', [UpdateConfigController::class, 'update'])
            ->name('api.admin.options.single.update');
    });
});


#####################################
#                API
#####################################

Route::middleware(['api'])->group(function () {
    /*
     *******************************************
     *                Legal Stuff
     *******************************************
     */
    Route::prefix('legal')->group(function () {
        /*
         *******************************************
         *           Terms and Conditions
         *******************************************
         */
        Route::get('/terms-and-conditions', [OptionsController::class, 'termsAndConditions'])
            ->name('api.legal.terms-and-conditions');

        /*
         *******************************************
         *              Privacy Policy
         *******************************************
         */
        Route::get('/privacy-policy', [OptionsController::class, 'privacyPolicy'])
            ->name('api.legal.privacy-policy');
    });

    /*
     *******************************************
     *               Miscellaneous
     *******************************************
     */
    Route::prefix('misc')->group(function () {
        /*
         ************************************************
         *          Min Ratings for Trust Score
         ************************************************
         */
        Route::get('/min-ratings-for-trust-score', [OptionsController::class, 'minRatingsForTrustScore'])
            ->name('api.misc.min-ratings-for-trust-score');

        /*
         ************************************************
         *           iOS and Android App Links
         ************************************************
         */
        Route::get('/app-links', [OptionsController::class, 'appLinks'])
            ->name('api.misc.app-links');
    });
});