<?php

namespace App\Http\Resources\Items;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemsValuesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'            =>$this->id,
            'name'          =>$this->name,
            'value'          =>$this->value,
        ];
    }
}
