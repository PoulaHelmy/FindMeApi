<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matching extends Model
{
    protected $fillable = ['user_id','item_id','matched_id'];
    protected $table='matchings';
    protected $hidden=['updated_at'];







}//end Of Class
