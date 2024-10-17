<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });
// Broadcast::channel('video_{sender_id}', function ($user, $sender_id) {

//     return true;
// });

// Broadcast::channel('video_{receiverr_id}', function ($user, $sender_id) {

//     return true;
// });

Broadcast::channel('modify-video', function ($user) {

    return true;
});

Broadcast::channel('chat', function ($user) {

    return true;
});
Broadcast::channel('chat_{sender_id}', function ($user, $sender_id) {

    return true;
});