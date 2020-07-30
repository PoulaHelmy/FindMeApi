<?php

namespace App\Http\Resources\Matching;

use App\Http\Resources\Items\ItemsDetailsResource;
use App\Models\Item;
use Illuminate\Http\Resources\Json\JsonResource;

class MatchItemsResource extends JsonResource
{

    public function toArray($request)
    {
        $itemData=Item::where('id','=',$this->matched_id)->first();
        $item_name=Item::where('id','=',$this->item_id)->first();
        return [
            'item_id'=>$this->item_id,
            'item_name'=>$item_name['name'],
            'item_status'=>$item_name['is_found'],
            'item_data'=>new ItemsDetailsResource($itemData),

        ];
    }
}
