<?php

namespace App\Http\Controllers\API\Admin;


use App\Http\Controllers\API\ApiHome;
use App\Http\Requests\BackEnd\SubCategories\Store;
use App\Http\Requests\BackEnd\SubCategories\Update;
use App\Http\Resources\Inputs\InputsFullDetailsResource;
use App\Http\Resources\SubCategories\SubCategoryResource;
use App\Models\Input;
use App\Models\Item;
use App\Models\Subcat;
use Illuminate\Http\Request;

class SubCategoryAPI extends ApiHome
{
    public function __construct(Subcat $model)
    {
        parent::__construct($model);
    }//end of constructor

    public function index(Request $request)
    {
        return SubCategoryResource::collection(Subcat::all());
    }//end of index

    public function show($id)
    {

        $row = $this->model->findOrFail($id);
        if ($row) {
            //            dd($row);
            return $this->sendResponse(new SubCategoryResource($row),
                'Data Retrieved Successfully');
        }
        return $this->sendError('Not Found', 400);
    }

    public function indexWithFilter(Request $request)
    {
        if ($request->get('filter') == '' || $request->get('filter') == null) {
            return SubCategoryResource::collection(
                Subcat::orderBy($request->get('order'), $request->get('sort'))->
                paginate($request->get('pageSize')));
        } else {
            return
                SubCategoryResource::collection(Subcat::when($request->filter, function ($query) use ($request) {
                    return $query->where('name', 'like', '%' . $request->filter . '%');
                })
                    ->orderBy($request->get('order'), $request->get('sort'))
                    ->paginate($request->get('pageSize')));
        }
    }//endof index

    public function store(Store $request)
    {
        $row = Subcat::create($request->all());
        return $this->sendResponse(new SubCategoryResource($row), 'Created Successfully');
    }//end of store

    public function update(Update $request, $id)
    {
        $row = $this->model->find($id);
        if (!$row)
            return $this->sendError('This SubCategory Not Found', 400);
        $row->update($request->all());
        return $this->sendResponse(new SubCategoryResource($row), 'SubCategory Updated Successfully');
    }//end of update

    public function subcats_inputs(Request $request)
    {
        $v = validator($request->only('subcat', 'inputs'), [
            'subcat' => 'required|integer',
            'inputs.*.*.id' => 'required|integer',
        ]);

        if ($v->fails())
            return $this->sendError('Validation Error.!', $v->errors()->all(), 400);

        $row = $this->model->find($request->subcat);
        if (!$row)
            return $this->sendError('This SubCategory Not Found', 400);
        $row->inputs()->sync($request->inputs);
        return $this->sendResponse(new SubCategoryResource($row), 'Attachment the inputs to this subcategory is Successfully');

    }//end of update

    public function all_subcatsids($id)
    {
        $AllInputs = [];
        $row = $this->model->find($id);
        if (!$row)
            return $this->sendError('This SubCategory Not Found', 400);
        foreach ($row->inputs as $input) {
            $inputData = Input::find($input->id);
            array_push($AllInputs, [new InputsFullDetailsResource($input)]);
        }
        return $this->sendResponse($AllInputs, 'All Inputs Data Reteived Successfully');
    }

    public function all_items_subcats_data(Request $request)
    {
        $v = validator($request->only('subcat_id', 'item_id'), [
            'subcat_id' => 'required|integer',
            'item_id' => 'required|integer',
        ]);
        if ($v->fails())
            return $this->sendError('Validation Error.!', $v->errors()->all(), 400);
        $item = Item::find($request->item_id);
        $row = Subcat::find($request->subcat_id);
        $Alloptions = [];
        $AllInputs = [];
        if (!$row)
            return $this->sendError('This SubCategory Not Found', 400);
        if (!$item)
            return $this->sendError('Item Not Found', 400);
        foreach ($row->inputs as $input) {
            $inputData = Input::find($input->id);
            array_push($AllInputs, new InputsFullDetailsResource($input));
        }
        foreach ($item->dynamicValues as $option) {
            array_push($Alloptions, $option);
        }
        $data = [$Alloptions, $AllInputs];
        return $this->sendResponse($data,
            'Data Retrivred Successfully');
    }

}//end of class

