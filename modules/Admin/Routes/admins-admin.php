<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\Admin\Admins\AddAdminController;
use Modules\User\Http\Controllers\Admin\Admins\DeleteAdminController;
use Modules\User\Http\Controllers\Admin\ListUsersController;
use Modules\User\Http\Controllers\Admin\ShowUserController;
use Modules\User\Http\Controllers\Admin\UpdateUserController;

/*
 **********************************************
 *                   Admins
 **********************************************
 */
Route::middleware(['api'])->prefix('admin')->group(function () {
    /*
     **********************************************
     *                  Add Admin
     **********************************************
     */
    Route::get('/add', [AddAdminController::class, 'add'])
        ->name('api.admin.add');

    /*
     **********************************************
     *                Single Admin
     **********************************************
     */
    Route::prefix('{admin}')->group(function () {
        /*
         **********************************************
         *                Remove Admin
         **********************************************
         */
        Route::delete('/', [DeleteAdminController::class, 'destroy'])
            ->name('api.admin.admins.single.destroy');
    });
});


