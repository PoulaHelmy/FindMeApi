<?php

namespace App\Http\Resources\Messages;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class MessageDetails extends JsonResource
{

    public function toArray($request)
    {

        $user=User::find($this->sender_id);

        return [
            'id'            =>$this->id,
            'body'          =>$this->body,
            'sender_id'   =>$this->sender_id,
            'receiver_id'  =>$this->receiver_id,
            'chat_id' =>$this->chat_id,
            'created_at'    =>$this->created_at,
            'sender_name' =>$user->name,

        ];
    }
}
