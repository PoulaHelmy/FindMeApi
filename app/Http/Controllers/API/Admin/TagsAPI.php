<?php

namespace App\Http\Controllers\API\Admin;


use App\Http\Controllers\API\ApiHome;
use App\Http\Requests\BackEnd\Tags\Store;
use App\Http\Resources\TagsResource\Tags;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagsAPI extends ApiHome
{
    public function __construct(Tag $model)
    {
        parent::__construct($model);
    }//end of constructor

    public function index(Request $request)
    {
        return TagsResource::collection(Tag::all());
    }//end of index

    public function indexWithFilter(Request $request)
    {
        if ($request->get('filter') == '' || $request->get('filter') == null) {
            return TagsResource::collection(
                Tag::orderBy($request->get('order'), $request->get('sort'))->
                paginate($request->get('pageSize')));
        } else {
            return
                TagsResource::collection(Tag::when($request->filter, function ($query) use ($request) {
                    return $query->where('name', 'like', '%' . $request->filter . '%');
                })
                    ->orderBy($request->get('order'), $request->get('sort'))
                    ->paginate($request->get('pageSize')));
        }
    }//end of filter

    public function store(Store $request)
    {
        $row = Tag::create($request->all());
        return $this->sendResponse(new TagsResource($row), 'Created Successfully');
    }//end of store


    public function update(Store $request, $id)
    {
        $row = $this->model->find($id);
        if (!$row)
            return $this->sendError('This Tag Not Found', 400);
        $row->update($request->all());
        return $this->sendResponse(new TagsResource($row), 'Tag Updated Successfully');
    }//end of update
}
