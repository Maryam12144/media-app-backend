<?php

Route::group(['middleware' => ['auth', 'admin'], 'prefix' => 'admin/dashboard', 'namespace' => 'Admin'], function ($router) {

    $router->get('', 'DashboardController@index');

});