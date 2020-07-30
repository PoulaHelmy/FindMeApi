<?php

namespace App\Http\Controllers\API\Admin;


use App\Http\Controllers\API\ApiHome;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
class ImagesController extends ApiHome
{
    public function __construct(Photo $model){
        parent::__construct($model);
    }//end of constructor

    public  function uploadImages(Request $request){
        $v = validator($request->only('item_id', 'images'), [
            'item_id' => ['required','integer'],
            'images.*.*' => ['required'],
        ]);
        if ($v->fails())
            return $this->sendError('Validation Error.!',$v->errors()->all(),400);
        $photos=$request->get('images');
        $id=$request->get('item_id');
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
        return $this->sendResponse(
            '','images Uploaded Successfully'
        );
    }//end of Upload Images





}//end of class
