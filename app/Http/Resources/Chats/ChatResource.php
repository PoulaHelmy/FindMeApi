<?php

namespace App\Http\Resources\Chats;

use App\Models\User;
use App\Models\Chat;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Messages\MessageDetails;

class ChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user1=User::find($this->user_1);
        $user2=User::find($this->user_2);
        $image1 = 'data:image/png;base64,' . base64_encode(Storage::disk('public')->get('avatars/' .  $user1->id . '/' .  $user1->avatar));
        $image2 = 'data:image/png;base64,' . base64_encode(Storage::disk('public')->get('avatars/' .  $user2->id . '/' .  $user2->avatar));
        $allMsgs=[];
        $chatData=Chat::find($this->id);
        foreach ($chatData->messages as $msg){
            array_push( $allMsgs,new MessageDetails($msg));
        }
        return [
            'id'=>$this->id,
            'auth_id'=>auth()->user()->id,
            'user_1'=>$this->user_1,
            'user1_name'=>$user1->name,
            'user_2'=>$this->user_2,
            'user2_name'=>$user2->name,
            'request_id'=>$this->request_id,
            'created_at'=>$this->created_at,
            'AllMessages'=>$allMsgs,
            'user1_image'=>$image1,
            'user2_image'=>$image2,

        ];
    }
}
