<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    protected $fillable = ['name', 'meta_keywords', 'meta_des'];
    protected $hidden = ['updated_at'];

    public function subcat()
    {
        return $this->hasMany(Subcat::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
