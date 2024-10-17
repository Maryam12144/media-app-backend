<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\Admin\DeleteUserController;
use Modules\User\Http\Controllers\Admin\ListUsersController;
use Modules\User\Http\Controllers\Admin\ShowUserController;
use Modules\User\Http\Controllers\Admin\SuspendUserController;
use Modules\User\Http\Controllers\Admin\UnsuspendUserController;
use Modules\User\Http\Controllers\Admin\UpdateUserController;

/*
 **********************************************
 *                   Users
 **********************************************
 */
Route::middleware(['api', 'auth', 'admin'])->prefix('admin/users')->group(function () {
    /*
     **********************************************
     *                 List Users
     **********************************************
     */
    Route::get('/count', [ListUsersController::class, 'UsersCount'])
        ->name('api.admin.users.count');

    Route::get('/', [ListUsersController::class, 'index'])
        ->name('api.admin.users.index');

    /*
     **********************************************
     *                Single User
     **********************************************
     */
    Route::prefix('{user}')->group(function () {
        /*
         **********************************************
         *                  Show User
         **********************************************
         */
        Route::get('/', [ShowUserController::class, 'show'])
            ->name('api.admin.users.single.show');

        /*
         **********************************************
         *                 Update User
         **********************************************
         */
        Route::post('/update', [UpdateUserController::class, 'update'])
            ->name('api.admin.users.single.update');

        /*
         **********************************************
         *                 Delete User
         **********************************************
         */
        // Route::delete('/', [DeleteUserController::class, 'destroy'])
        //     ->name('api.admin.users.single.destroy');

        /*
         **********************************************
         *                Suspend User
         **********************************************
         */
        Route::post('/suspend', [SuspendUserController::class, 'suspend'])
            ->name('api.admin.users.single.suspend');

        /*
         **********************************************
         *                Unsuspend User
         **********************************************
         */
        Route::post('/unsuspend', [UnsuspendUserController::class, 'unsuspend'])
            ->name('api.admin.users.single.unsuspend');
    });
});


