<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\ApiHome;
use App\Http\Resources\RequestsItems\ItemRequestDetails;
use App\Models\RequestItems;

class AdminRequests extends ApiHome
{
    public function __construct(RequestItems $model)
    {
        parent::__construct($model);
    }//end of constructor

    public function allrequests()
    {
        return $this->sendResponse(ItemRequestDetails::collection(RequestItems::all()),
            'all Requests ');
    }//end of allrequests

    public function getrequest($id)
    {
        $row = $this->model->findOrFail($id);
        if ($row) {
            return $this->sendResponse(new ItemRequestDetails($row),
                'Data Retrieved Successfully');
        }
        return $this->sendError('Not Found', 400);
    }//end of getrequest

}//End Of Class
