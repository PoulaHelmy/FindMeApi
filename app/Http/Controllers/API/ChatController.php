<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\Chats\ChatResource;
use App\Http\Resources\Messages\MessageDetails;
use App\Models\Chat;
use App\Models\Message;
use App\Models\RequestItems;
use App\Models\User;
use App\Notifications\SendMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatController extends ApiHome
{
    public function __construct(Chat $model)
    {
        parent::__construct($model);
    }//end of __construct

    public function getChat($id)
    {
        $chatData = Chat::where('request_id', $id)->get();
        return $this->sendResponse(new ChatResource($chatData[0]), 'Chat DAta');
    }//end of getChat

    public function storeMsg(Request $request)
    {
        $v = validator($request->all(), [
            'body' => 'required|string|max:255',
            'chat_id' => 'required|integer',
            'sender_id' => 'required|integer',
            'receiver_id' => 'required|integer',
        ]);
        if ($v->fails())
            return $this->sendError('Validation Error.!', $v->errors()->all(), 400);
        $msg = Message::create($request->all());
        $user = User::find($request->receiver_id);
        $sender = DB::table('users')->where('id', '=', $request->sender_id)->first();
        $user->notify(new SendMessage($user->name, $user->id, $user->email, $sender->name, $request->chat_id));
        return $this->sendResponse($msg,
            'Item Created Successfully');
    }//end of storeMsg

    public function getAllMsgs(Request $request)
    {
        $v = validator($request->all(), [
            'request_id' => 'required|integer',
        ]);
        if ($v->fails())
            return $this->sendError('Validation Error.!', $v->errors()->all(), 400);
        $requestData = RequestItems::find($request->request_id);
        if (!$requestData)
            return $this->sendError('This Request Not Found', '', 400);
        $allMsgs = [];
        $chatData = $requestData->chat;
        foreach ($chatData->messages as $msg) {
            array_push($allMsgs, new MessageDetails($msg));
        }
        return $this->sendResponse($allMsgs, 'All Msgs Reterieved Successfully');
    }//end of getAllMsgs

}//end of Class
