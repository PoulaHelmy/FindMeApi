<?php

namespace App\Http\Resources\SubCategories;

use Illuminate\Http\Resources\Json\JsonResource;

class SubCategoryLiteResource extends JsonResource
{

    public function toArray($request)
    {


        return [
            'id' => $this->id,
            'name' => $this->name,


        ];
    }
}
