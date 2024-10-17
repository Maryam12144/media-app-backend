<?php

// Admin API Routes
Route::group(['middleware' => ['auth', 'admin'], 'prefix' => 'admin/logs', 'namespace' => 'Admin'], function()
{
    Route::get('/', 'LogController@index');
    Route::get('/{log}', 'LogController@show');

});


