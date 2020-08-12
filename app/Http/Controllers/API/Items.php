<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\BackEnd\Items\Store;
use App\Http\Requests\BackEnd\Items\Update;
use App\Http\Resources\Items\ItemsDetailsResource;
use App\Http\Resources\Items\ItemsResource;
use App\Models\Category;
use App\Models\Item;
use App\Models\Matching;
use App\Models\Photo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Items extends ApiHome
{
    public function __construct(Item $model)
    {
        parent::__construct($model);
    }//end of constructor

    public function index(Request $request)
    {
        return ItemsResource::collection(
            Item::where('user_id', auth()->user()->id)->get());
    }//end of index

    public function show($id)
    {
        $row = $this->model->findOrFail($id);
        if ($row) {
            return $this->sendResponse(new ItemsDetailsResource($row),
                'Data Retrieved Successfully');
        }
        return $this->sendError('Not Found', 400);
    }

    public function indexWithFilter(Request $request)
    {
        if ($request->get('filter') == '' || $request->get('filter') == null) {
            return ItemsResource::collection(
                Item::orderBy($request->get('order'), $request->get('sort'))->
                paginate($request->get('pageSize')));
        } else {
            return
                ItemsResource::collection(Item::when($request->filter, function ($query) use ($request) {
                    return $query->where('name', 'like', '%' . $request->filter . '%');
                })
                    ->orderBy($request->get('order'), $request->get('sort'))
                    ->paginate($request->get('pageSize')));
        }
    }//endof index

    public function store(Store $request)
    {
        $requestArray = ['user_id' => auth()->user()->id] + $request->all();
        $row = $this->model->create($requestArray);
        if ($request->has('images')) {
            $this->uploadImages($request, $row->id);
        }
        return $this->sendResponse($row,
            'Item Created Successfully');
    }// End Of Store

    public function update(Update $request, $id)
    {
        $row = $this->model->FindOrFail($id);
        $row->update($request->all());
        if ($request->has('images')) {
            foreach ($row->photos as $photo) {
                Storage::disk('public')->delete($photo->src);
                $photo = \App\Models\Photo::find($photo->id);
                $photo->delete();
            }
            $this->uploadImages($request, $row->id);
        }
        $row->save();
        return $this->sendResponse($row,
            'Item Updated Successfully');
    }// End Of Update

    public function getAllItemOptions($id)
    {
        $item = Item::find($id);
        $Alloptions = [];
        if (!$item)
            return $this->sendError('Item Not Found', 400);
        foreach ($item->dynamicValues as $option) {
            array_push($Alloptions, $option);
        }
        return $this->sendResponse($Alloptions,
            'Data Retrivred Successfully');
    }//end of getAllItemOptions

    public function uploadImages(Request $request, $id)
    {
        $photos = $request->get('images');
        for ($i = 0; $i < sizeof($photos); $i++) {
            $image_64 = $photos[$i]; //your base64 encoded data
            $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf
            $replace = substr($image_64, 0, strpos($image_64, ',') + 1);

            // find substring from replace here eg: data:image/png;base64,
            $image = str_replace($replace, '', $image_64);
            $image = str_replace(' ', '+', $image);
            $imageName = Str::random(15) . '.' . $extension;
            Storage::disk('public')->put('images/items/' . $id . '/' . $imageName, base64_decode($image));
            $photo = Photo::create([
                    'src' => 'images/items/' . $id . '/' . $imageName,
                    'photoable_type' => 'App\Models\Item',
                    'photoable_id' => $id
                ]
            );
        }
        return true;
    }//end of Upload Images

    public function destroy($id)
    {
        $row = $this->model->find($id);
        if (!$row)
            return $this->sendError('This ITEM Not Found', 400);
        foreach ($row->photos as $photo) {
            Storage::disk('public')->delete($photo->src);
            $photo = \App\Models\Photo::find($photo->id);
            $photo->delete();
        }
        $row->delete();
        return $this->sendResponse(null, 'ITEM Deleted Successfully');
    }//end of destroy

    public function markAsReturned($id)
    {
        $row = $this->model->find($id);
        if (!$row)
            return $this->sendError('This ITEM Not Found', 400);
        $row->is_found = -1;
        $row->save();
        return $this->sendResponse($row->is_found, 'ITEM Arcivied Successfully');
    }//end of markAsReturned

    public function uploadPersonFaces(Request $request)
    {
        if (sizeof($request->get('images')) > 0) {
            $fileName = $this->uploadImages($request, $request->item_id);
        }
        return $this->sendResponse('',
            'Person Faces Uploaded Successfully');
    }//end of uploadPersonFaces

    /* -------------- Matching Functions ----------------- */
    public function Get_All_Matching_Items()
    {
        $cats = Category::all();
        $Aitems = [];
        foreach ($cats as $cat) {
            foreach ($cat->subcat as $subcat) {
                $Lost_Items = Item::where('subcat_id', '=', $subcat->id)->
                where('is_found', '=', 0)->get();
                if (sizeof($Lost_Items) > 0) {
                    foreach ($Lost_Items as $Lost_Item) {
                        $query = Item::getByDistance2($Lost_Item->lat, $Lost_Item->lan, 4, $subcat->id, $Lost_Item->user_id, 1);
                        array_push($Aitems, ["Lost_Item" => $Lost_Item, "Matched_Items" => $query]);
                    }//End Of Lost Items Loop
                }//End Of If Check FOR LOst Items Size
            }//end of SUB Cats loop
        }//end of cats loop
        return $Aitems;
    }

    public function matching()
    {
        $items = $this->Get_All_Matching_Items();
        Matching::truncate();
        $ids = [];
        DB::table('notifications')->where('type', 'App\Notifications\MatchingItems')->delete();
        if ($items) {
            foreach ($items as $item) {
                if (sizeof($item['Matched_Items']) > 0) {
                    // add Id FOR USER WHO UPLOAD THIS LOST ITEM
                    array_push($ids, $item['Lost_Item']->user_id);
                    foreach ($item['Matched_Items'] as $matched) {
                        Matching::create([
                            'user_id' => $item['Lost_Item']->user_id,
                            'item_id' => $item['Lost_Item']->id,
                            'matched_id' => $matched->id
                        ]);
                        $user_ID = Item::where('id', '=', $matched->id)->first()->user_id;
                        Matching::create([
                            'user_id' => $user_ID,
                            'item_id' => $matched->id,
                            'matched_id' => $item['Lost_Item']->id
                        ]);
                        array_push($ids, $user_ID);
                    }//end of inner loop
                }//end of if
            }//end of foreach for items
            $ids = array_unique($ids);
            foreach ($ids as $id) {
                $user = User::find($id);
                $user->notify(new \App\Notifications\MatchingItems($user->id, $user->name));
            }
            $response = [
                'success' => true,
                'data' => [],
                'message' => 'Data Retrieved Successfully',
            ];
            return response()->json($response, 200);
        }//End OF Big IF
        return $this->sendError('Not Found', 400);
    }

}//end of Class


