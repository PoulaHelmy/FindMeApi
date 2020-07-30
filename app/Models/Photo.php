<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $fillable = ['src','photoable_type','photoable_id'];
    protected $hidden=['updated_at','created_at'];





    public function photoable()
    {
        return $this->morphTo();
    }










}//end of class
