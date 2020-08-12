<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\Items\ItemsDetailsResource;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemsFilters extends ApiHome
{
    public function __construct(Item $model)
    {
        parent::__construct($model);
    }//end of constructor

    public function myFilter(Request $request, $q)
    {
        if ($q === 'nosearch') {
            return $this->sendResponse('', 'Success');
        }
        return $this->sendResponse(ItemsDetailsResource::collection(Item::search($q)->get()), 'Success');
    }//end of myFilter


}//end of Class

