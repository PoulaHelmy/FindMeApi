<?php


namespace App\Http\Controllers\API\Admin;


use App\Http\Controllers\API\ApiHome;
use App\Http\Requests\BackEnd\Categories\Store;
use App\Http\Requests\BackEnd\Categories\Update;
use App\Http\Resources\Categories\CategoryFullDetaillResouce;
use App\Http\Resources\Categories\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;


class CategoryApi extends ApiHome
{
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }//end of constructor

    public function index(Request $request)
    {
        return CategoryResource::collection(
            Category::all());
    }//end of index

    public function show($id)
    {
        $row = $this->model->findOrFail($id);
        if ($row) {
            return $this->sendResponse(new CategoryFullDetaillResouce($row),
                'Data Retrieved Successfully');
        }
        return $this->sendError('Not Found', 400);
    }//end of show

    public function indexWithFilter(Request $request)
    {
        if ($request->get('filter') == '' || $request->get('filter') == null) {
            return CategoryResource::collection(
                Category::orderBy($request->get('order'), $request->get('sort'))->
                paginate($request->get('pageSize')));
        } else {
            return
                CategoryResource::collection(Category::when($request->filter, function ($query) use ($request) {
                    return $query->where('name', 'like', '%' . $request->filter . '%');
                })
                    ->orderBy($request->get('order'), $request->get('sort'))
                    ->paginate($request->get('pageSize')));
        }
    }//endof index

    public function store(Store $request)
    {
        $row = Category::create($request->all());
        return $this->sendResponse(new CategoryResource($row), 'Created Successfully');
    }//end of store

    public function update(Update $request, $id)
    {
        $row = $this->model->find($id);
        if (!$row)
            return $this->sendError('This Category Not Found', 400);
        $row->update($request->json()->all());
        return $this->sendResponse(new CategoryResource($row), 'Category Updated Successfully');
    }//end of update

    public function all_subCatsData($id)
    {
        $AllSubCats = [];
        $row = $this->model->find($id);
        if (!$row)
            return $this->sendError('This Category Not Found', 400);
        foreach ($row->subcat as $subCat) {
            array_push($AllSubCats, $subCat);
        }
        return $this->sendResponse($AllSubCats, 'All Inputs Data Reteived Successfully');
    }//end of all_subCatsData

}//end of controller
