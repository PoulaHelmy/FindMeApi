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

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
Broadcast::channel('chat.{chat_id}', function ($user, $chat_id) {
    $chatData=\App\Models\Chat::find($chat_id);
    if((int) $user->id === (int) $chatData->user_1||(int) $user->id === (int) $chatData->user_2){
        return true;
    }
    return false;
});
Broadcast::channel('test-channel', function () {

    return true;
});
