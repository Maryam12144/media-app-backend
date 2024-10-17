<?php

Route::group(['middleware' => ['auth', 'admin'], 'namespace' => 'Admin', 'prefix' => 'admin'], function ($router) {
    $router->get('rules', 'RuleController@index');
    $router->post('rules/{rule}', 'RuleController@update');
});

