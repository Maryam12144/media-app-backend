<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\Admin\ListMembersController;

/*
 **********************************************
 *                   Members
 **********************************************
 */
Route::middleware(['api', 'auth', 'admin'])->prefix('admin/members')->group(function () {
    /*
     **********************************************
     *                 List Members
     **********************************************
     */
    Route::get('/', [ListMembersController::class, 'index'])
        ->name('api.admin.members.index');
	
	Route::get('/count', [ListMembersController::class, 'membersCount'])
        ->name('api.admin.members.count');

});




