<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Input extends Model
{
    protected $fillable = ['name','label','type','inputOptionsInput','placeholder'];
    protected $hidden=['updated_at'];

    public function subcats(){
        return $this->belongsToMany(Subcat::class,'inputs_subcats');
    }
    public function optionsInputs(){
        return $this->hasMany(inputOption::class);
    }
    public function optionsValidators(){
        return $this->hasMany(inputValidator::class);
    }
}
