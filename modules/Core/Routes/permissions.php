<?php

Route::group(['middleware' => ['auth', 'admin'], 'namespace' => 'Admin', 'prefix' => 'admin'], function ($router) {
    
    $router->get('permissions', 'PermissionController@index');

    $router->get('permissions/{role}', 'PermissionController@show');

    $router->post('permissions', 'PermissionController@store');

    $router->put('permissions/{role}', 'PermissionController@update');

    $router->delete('permissions/{role}', 'PermissionController@destroy');

});

