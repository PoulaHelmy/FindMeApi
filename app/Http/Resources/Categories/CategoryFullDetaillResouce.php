<?php

namespace App\Http\Resources\Categories;

use App\Http\Resources\SubCategories\SubCategoryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryFullDetaillResouce extends JsonResource
{
    public function toArray($request)
    {

        $AllCats = [];
        foreach ($this->subcat as $subcat) {
            array_push($AllCats, new SubCategoryResource($subcat));
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'meta_description' => $this->meta_des,
            'meta_keywords' => $this->meta_keywords,
            'created_at' => $this->created_at,
            'number_sub_categories' => sizeof($AllCats),
            'sub_categories' => $AllCats
        ];

    }
}
