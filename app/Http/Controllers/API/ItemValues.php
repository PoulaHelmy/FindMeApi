<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\Items\ItemsValuesResource;
use App\Models\Item;
use App\Models\ItemOption;
use Illuminate\Http\Request;


class ItemValues extends ApiHome
{
    public function __construct(ItemOption $model)
    {
        parent::__construct($model);
    }//end of constructor

    public function index(Request $request)
    {
        return $this->model->all();
    }//end of index

    public function show($id)
    {
        $row = $this->model->find($id);
        if ($row) {
            return $this->sendResponse(new ItemsValuesResource($row),
                'Data Retrieved Successfully');
        }
        return $this->sendError('Not Found', 400);
    }//end of Show

    public function store(Request $request)
    {
        $item = $request->get('item_id');
        $requestArray = $request->all();
        unset($requestArray['item_id']);

        foreach ($requestArray as $key => $value) {
            $row = $this->model->create([
                'item_id' => $item,
                'name' => $key,
                'value' => $value
            ]);
        }
        return $this->sendResponse('',
            'Item Values Attached Successfully');
    }//end of Store

    public function update(Request $request, $id)
    {

        $item = $request->get('item_id');
        $requestArray = $request->all();
        unset($requestArray['item_id']);
        $itemRow = Item::find($item);
        foreach ($itemRow->dynamicValues as $option) {
            $row = $this->model->FindOrFail($option->id);
            $row->delete();
        }
        foreach ($requestArray as $key => $value) {
            $row = $this->model->create([
                'item_id' => $item,
                'name' => $key,
                'value' => $value
            ]);
        }

        return $this->sendResponse('',
            'Item Updated Successfully');
    }//end of Update

}//end of Class
