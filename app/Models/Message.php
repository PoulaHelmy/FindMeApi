<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['body','chat_id','sender_id','receiver_id'];
    protected $hidden=['updated_at'];

    public function chat(){
        return $this->belongsTo(Chat::class);
    }
}//end of Class
