<?php

namespace App\Http\Resources\RequestsItems;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemRequest extends JsonResource
{
    public function toArray($request)
    {

        return [
            'id'            =>$this->id,
            'name'          =>$this->name,
            'description'   =>$this->des,
            'item_id'       =>$this->item->id,
            'item_name'    =>$this->item->name,
            'status'  =>$this->status,
            'created_at'    =>$this->created_at,
        ];
    }
}
