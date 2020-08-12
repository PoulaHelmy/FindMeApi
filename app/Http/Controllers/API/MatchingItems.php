<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Matching\MatchItemsResource;
use App\Models\Matching;

class MatchingItems extends ApiHome
{
    public function __construct(Matching $model)
    {
        parent::__construct($model);
    }//end Of constructor

    public function main()
    {
        $data = Matching::where('user_id', '=', auth()->user()->id)->get();
        return $this->sendResponse(MatchItemsResource::collection($data), 'Matching Items Retrived Successfully');
    }//end Of main

}//end Of Class
