<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\ApiHome;
use App\Http\Requests\BackEnd\Items\Store;
use App\Http\Requests\BackEnd\Items\Update;
use App\Http\Resources\Items\ItemsDetailsResource;
use App\Http\Resources\Items\ItemsResource;
use App\Models\Category;
use App\Models\Item;
use App\Models\ItemOption;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminItems extends ApiHome
{
    public function __construct(Item $model){
        parent::__construct($model);
    }//end of constructor
    public function store(Store $request)
    {
        $requestArray =  ['user_id' => $request->user_id] + $request->all();
        $row = $this->model->create($requestArray);
        $fileName = $this->uploadImages($request,$row->id);
        $row->save();
        return $this->sendResponse($row,
            'Item Created Successfully');
    }
    public  function uploadImages(Request $request,$id){
        $photos=$request->get('images');

        for ($i=0;$i<sizeof($photos);$i++) {
            $img = preg_replace('/^data:image\/\w+;base64,/', '', $photos[$i]);
            $type = explode(';', $photos[$i])[0];
            $type = explode('/', $type)[1]; // png or jpg etc


            $image_64 = $photos[$i]; //your base64 encoded data

            $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf

            $replace = substr($image_64, 0, strpos($image_64, ',')+1);

            // find substring fro replace here eg: data:image/png;base64,

            $image = str_replace($replace, '', $image_64);

            $image = str_replace(' ', '+', $image);

            $imageName = Str::random(15).'.'.$extension;
            Storage::disk('public')->put('images/items/'.$id.'/'.$imageName, base64_decode($image));
            // Storage::disk('public')->put('eejaz/'.$safeName, $image);
            $photo=Photo::create([
                    'src'=> 'images/items/'.$id.'/'.$imageName,
                    'photoable_type'=> 'App\Models\Item',
                    'photoable_id'=>$id
                ]
            );
        }
        return true;
    }//end of Upload Images
    public function allItems(){
        return $this->sendResponse(ItemsDetailsResource::collection(Item::all()),
            'all Items ');
    }
    public function lastItems(){
        return $this->sendResponse(ItemsDetailsResource::
        collection(Item::where('category_id','!=','11')->orderBy('id', 'desc')->take(10)->get()),
            'all Items ');
    }
    public function lastPersons(){
        return $this->sendResponse(ItemsDetailsResource::
        collection(Item::where('category_id','=','11')->orderBy('id', 'desc')->take(10)->get()),
            'all Items ');
    }













}//end of Class
