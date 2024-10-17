<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\Admin\Members\AddMemberController;
use Modules\User\Http\Controllers\Admin\Members\UpdateMemberController;
use Modules\User\Http\Controllers\Admin\Members\DeleteMemberController;
use Modules\User\Http\Controllers\Admin\ListUsersController;
use Modules\User\Http\Controllers\Admin\ShowUserController;
use Modules\User\Http\Controllers\Admin\UpdateUserController;

/*
 **********************************************
 *                   Admins
 **********************************************
 */
Route::middleware(['api', 'auth', 'admin'])->prefix('admin/members')->group(function () {
    /*
     **********************************************
     *                  Add Member
     **********************************************
     */
    Route::post('/', [AddMemberController::class, 'add'])
        ->name('api.admin.members.add');

    /*
     **********************************************
     *                Single Member
     **********************************************
     */
    Route::prefix('{member}')->group(function () {

        /*
         **********************************************
         *                 Update User
         **********************************************
         */
        Route::post('/update', [UpdateMemberController::class, 'update'])
            ->name('api.admin.members.single.update');

        /*
         **********************************************
         *                Remove Member
         **********************************************
         */
        Route::delete('/', [DeleteMemberController::class, 'destroy'])
            ->name('api.admin.members.single.destroy');
    });
});
