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

/********** GenreController *************/
include_once($real_path . 'genres.php');

/********** GeographicalCriteriaController *************/
include_once($real_path . 'geographical-criterias.php');

/********** HumanInterestController *************/
include_once($real_path . 'human-interests.php');

/********** NewsController *************/
include_once($real_path . 'news.php');

/********** ProminenceController *************/
include_once($real_path . 'prominences.php');

/********** VideoTypeController *************/
include_once($real_path . 'video-types.php');

/********** NewsTypeController *************/
include_once($real_path . 'news-types.php');

/********** EvaluationController *************/
include_once($real_path . 'evaluation.php');

/********** ChatBoxController *************/
include_once($real_path . 'chat-box.php');

/********** ChatRoomController *************/
include_once($real_path . 'chat-room.php');
/********** ChannelController *************/
include_once($real_path . 'channel.php');
/********** CityController *************/
include_once($real_path . 'city.php');
/********** CityController *************/
include_once($real_path . 'archive.php');
/********** SlotController *************/
include_once($real_path . 'slot.php');
/********** SlotTypeController *************/
include_once($real_path . 'slot-type.php');
/********** TickerController *************/
include_once($real_path . 'ticker.php');
/********** TickerController *************/
include_once($real_path . 'evaluator-ticker.php');
