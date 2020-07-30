<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestItems extends Model
{
    protected $fillable = ['name','user_id','item_id','des','status'];
    protected $hidden=['updated_at'];
    protected $table='requests';


    public function item(){
        return $this->belongsTo(Item::class,'item_id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function questionResponses(){
        return $this->hasMany('App\Models\QuestionResponse','request_id');
    }
    public function chat(){
        return $this->hasOne(Chat::class,'request_id');
    }




}//end of Class
