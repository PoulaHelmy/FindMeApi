<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\API\ApiHome;
use App\Http\Controllers\Controller;
use App\Http\Resources\Matching\MatchItemsResource;
use App\Models\Matching;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class MatchingItems extends ApiHome
{
    public function __construct(Matching $model){
        parent::__construct($model);
    }
    public function main(){
        $data=Matching::where('user_id','=',auth()->user()->id)->get();
        return $this->sendResponse(MatchItemsResource::collection($data),'Matching Items Retrived Successfully');
    }

}//end Of Class
