<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$real_path = realpath(__DIR__) . DIRECTORY_SEPARATOR;
include_once($real_path . 'admins-admin.php');
/********** SlotTypeController *************/
include_once($real_path . 'users-admin.php');
/********** SlotTypeController *************/
include_once($real_path . 'settings.php');