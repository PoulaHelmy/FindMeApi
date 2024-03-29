<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Laravel\Scout\Searchable;

class Item extends Model
{
    use Searchable;

    protected $fillable = ['name', 'user_id', 'category_id', 'subcat_id', 'location', 'lat', 'lan', 'des', 'is_found', 'date'];
    protected $hidden = ['updated_at'];
    protected $table = 'items';


    public function photos()
    {
        return $this->morphMany('App\Models\Photo', 'photoable');
    }

    public function dynamicValues()
    {
        return $this->hasMany('App\Models\ItemOption', 'item_id');
    }

    public function questions()
    {
        return $this->hasMany('App\Models\Question', 'item_id');
    }

    public function itemRequests()
    {
        return $this->hasMany('App\Models\RequestItems', 'item_id');
    }

    public function cat()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function subcat()
    {
        return $this->belongsTo(Subcat::class, 'subcat');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function searchableAs()
    {
        return 'items_index';
    }

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'is_found' => $this->is_found
        ];
    }

    public static function getByDistance2($lat, $lng, $distance, $subcat, $user_id, $status)
    {
        $results = DB::select(
            DB::raw('SELECT id, ( 3959 * acos( cos( radians(' . $lat . ') ) * cos( radians( lat ) ) * cos( radians( lan ) - radians(' . $lng . ') ) + sin( radians(' . $lat . ') ) * sin( radians(lat) ) ) ) AS distance FROM items where subcat_id = ' . $subcat . ' AND user_id <> ' . $user_id . ' AND is_found = ' . $status . ' HAVING distance < ' . $distance . ' ORDER BY distance'));
        return $results;
    }


}//end of class
