<?php

namespace App\Http\Controllers\API\Admin;
use App\Http\Controllers\API\ApiHome;

use App\Http\Resources\RequestsItems\ItemRequestDetails;
use App\Models\RequestItems;
use Illuminate\Http\Request;

class AdminRequests extends ApiHome
{
    public function __construct(RequestItems $model){
        parent::__construct($model);
    }//end of constructor


    public function allrequests(){
        return $this->sendResponse(ItemRequestDetails::collection(RequestItems::all()),
            'all Requests ');
    }

}
