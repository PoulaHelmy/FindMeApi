<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\ApiHome;
use App\Http\Resources\Items\ItemsDetailsResource;
use App\Models\Item;

class AdminItems extends ApiHome
{
    public function __construct(Item $model)
    {
        parent::__construct($model);
    }//end of constructor

    public function allItems()
    {
        return $this->sendResponse(ItemsDetailsResource::collection(Item::all()),
            'all Items ');
    }//end of allItems

    public function getitem($id)
    {
        $row = $this->model->findOrFail($id);
        if ($row) {
            return $this->sendResponse(new ItemsDetailsResource($row),
                'Data Retrieved Successfully');
        }
        return $this->sendError('Not Found', 400);
    }//end of getitem

    public function lastItems()
    {
        return $this->sendResponse(ItemsDetailsResource::
        collection(Item::where('category_id', '!=', '11')->orderBy('id', 'desc')->take(10)->get()),
            'all Items ');
    }//end of lastItems

    public function lastPersons()
    {
        return $this->sendResponse(ItemsDetailsResource::
        collection(Item::where('category_id', '=', '11')->orderBy('id', 'desc')->take(10)->get()),
            'all Items ');
    }//end of lastPersons

}//end of Class
