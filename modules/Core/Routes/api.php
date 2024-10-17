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

// Route::middleware('auth:api')->get('/core', function (Request $request) {
//     return $request->user();
// });

$real_path = realpath(__DIR__) . DIRECTORY_SEPARATOR;
include_once($real_path . 'auth.php');
include_once($real_path . 'roles.php');
