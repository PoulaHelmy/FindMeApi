<?php

namespace App\Http\Resources\Items;

use App\Models\Category;
use App\Models\Subcat;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemsResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id'            =>$this->id,
            'userId' => $this->user_id,
            'name'          =>$this->name,
            'category_id'   =>$this->category_id,
            'category'   =>Category::find($this->category_id)->name,
            'subcat_id'  =>$this->subcat_id,
            'subcat'  =>Subcat::find($this->subcat_id)->name,
            'location'   =>$this->location,
            'lat'       =>$this->lat,
            'lan' =>$this->lan,
            'description'  =>$this->des,
            'is_found'   =>$this->is_found,
            'date'  =>$this->date,
            'created_at'    =>$this->created_at
        ];
    }
}
