<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = ['request_id','user_1','user_2'];
    protected $hidden=['updated_at'];


    public function messages(){
        return $this->hasMany(Message::class,'chat_id');
    }
    public function requestChat(){
        return $this->belongsTo(RequestItems::class);
    }

}//end of Class
