<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemOption extends Model
{
    protected $fillable = ['name','value','item_id'];
    protected $hidden=['updated_at','created_at'];
    protected $table='itemvalues';





    public function item(){
        return $this->belongsTo('App\Models\Item');
    }






}//end of class
