<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class inputValidator extends Model
{
    protected $fillable = ['input_id','validatorName','validatorMessage','validatorOptions'];
    protected $hidden=['created_at','updated_at'];
    protected $table='inputs_validators';
    public function inputvalidators(){
        return $this->belongsTo(Input::class,'input_id');
    }








}
