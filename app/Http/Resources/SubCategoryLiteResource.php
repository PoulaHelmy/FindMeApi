<?php

namespace App\Http\Resources;

use App\Models\Category;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Subcat;

class SubCategoryLiteResource extends JsonResource
{

    public function toArray($request)
    {

       
    
        return [
            'id'                 =>$this->id,
            'name'               =>$this->name,


        ];
    }
}
