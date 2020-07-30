<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class inputOption extends Model
{
    protected $fillable = ['input_id','optionName'];
    protected $hidden=['created_at','updated_at'];
    protected $table='inputs_options';
    public function inputOptions(){
        return $this->belongsTo(Input::class,'input_id');
    }
}
