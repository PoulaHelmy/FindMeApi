<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['name','item_id'];
    protected $hidden=['updated_at'];
    protected $table='questions';

    public function item(){
        return $this->belongsTo(Item::class,'item_id');
    }

}//end of class

