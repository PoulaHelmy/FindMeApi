<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\BackEnd\Items\Store;
use App\Http\Requests\BackEnd\Items\Update;
use App\Http\Resources\Items\ItemsDetailsResource;
use App\Http\Resources\Items\ItemsResource;
use App\Models\Item;
use App\Models\Matching;
use App\Models\Photo;
use App\Models\ItemOption;
use App\Models\User;
use App\Notifications\MatchingItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
class Items extends ApiHome
{
    public function __construct(Item $model){
        parent::__construct($model);
    }//end of constructor
    public function index(Request $request){
        return ItemsResource::collection(
            Item::where('user_id',auth()->user()->id)->get());
    }//endof index
    public function show($id){
        $row=$this->model->findOrFail($id);
        if($row) {
            return $this->sendResponse(new ItemsDetailsResource($row),
                'Data Retrieved Successfully');
        }
        return $this->sendError('Not Found',400);
    }
    public function indexWithFilter(Request $request){
        if($request->get('filter')==''||$request->get('filter')==null){
            return ItemsResource::collection(
                Item::orderBy($request->get('order'), $request->get('sort'))->
                paginate($request->get('pageSize')));
        }
        else{
            return
                ItemsResource::collection(Item::when($request->filter,function ($query)use($request){
                    return $query->where('name','like','%'.$request->filter.'%');})
                    ->orderBy($request->get('order'), $request->get('sort'))
                    ->paginate($request->get('pageSize')));
        }
    }//endof index
    public function store(Store $request)
    {
        $requestArray =  ['user_id' => auth()->user()->id] + $request->all();
        $row = $this->model->create($requestArray);
       if($request->has('images')){
        $fileName = $this->uploadImages($request,$row->id);
       }
        $row->save();
        return $this->sendResponse($row,
            'Item Created Successfully');
    }
    public function update(Update $request,$id)
    {
        $requestArray = $request->all();
        $row = $this->model->FindOrFail($id);
        $row->update($requestArray);
        if($request->has('images'))
        {
            foreach($row->photos as $photo) {

                Storage::disk('public')->delete($photo->src);
                $photo=\App\Models\Photo::find($photo->id);
                $photo->delete();
            }
            $fileName = $this->uploadImages($request,$row->id);
        }
        $row->save();
        return $this->sendResponse($row,
            'Item Updated Successfully');
    }
    public function getAllItemOptions($id){
        $item=Item::find($id);
        $Alloptions=[];
        if(!$item)
            return $this->sendError('Item Not Found',400);
        foreach($item->dynamicValues as $option)
        {
            array_push($Alloptions,$option);
        }
        return  $this->sendResponse($Alloptions,
        'Data Retrivred Successfully');
    }//end of getAllItemOptions
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
    public function destroy($id){
        $row=$this->model->find($id);
        if(!$row)
            return $this->sendError('This ITEM Not Found',400);
        foreach($row->dynamicValues as $option){
                $optionData = ItemOption::find($option->id);
                $optionData->delete();
            }
        foreach ($row->photos as $photo) {

            Storage::disk('public')->delete($photo->src);
            $photo=\App\Models\Photo::find($photo->id);
            $photo->delete();
        }
        $row->delete();
        return$this->sendResponse(null,'ITEM Deleted Successfully');
    }//end of destroy
    public function uploadPersonFaces(Request $request ){

        if(sizeof($request->get('images'))>0)
        {
            $fileName = $this->uploadImages($request,$request->item_id);
        }

        return $this->sendResponse('',
            'Person Faces Uploaded Successfully');

    }
    public function getItemByName(Request $request){
        $row=$this->model->where('name','=',$request->itemName)->first();
        if($row){
            return $this->sendResponse(new ItemsDetailsResource($this->model->find($row['id'])),'ITEM Retreived Successfully');
        }
        return $this->sendError('This ITEM Not Found',400);
    }
    public function markAsReturned($id){
        $row=$this->model->find($id);
        if(!$row)
            return $this->sendError('This ITEM Not Found',400);
        $row->is_found=-1;
        $row->save();
        return $this->sendResponse($row->is_found,'ITEM Arcivied Successfully');

    }
    /* -------------- Matching Functions ----------------- */
    public function matching(){
        Matching::truncate();
        DB::table('notifications')->where('type','App\Notifications\MatchingItems')->delete();
        $ids=[];
        $items = DB::table('items')
            ->where('is_found','=',0)
            ->get();
        $items = $this->addItemValuestoItems($items);
        $items = $this->getMatchingItems($items);
        $items = $this->checkIdenticalItems($items);
        if($items) {
            foreach ($items as $itemId => $item){
                array_push($ids,$items[$itemId]->user_id);
                foreach ($items[$itemId]->otherItems as $otherItemId => $otherItem){
                    array_push($ids,$otherItem->user_id);
                    Matching::create([
                        'user_id'=>$items[$itemId]->user_id,
                        'item_id'=>$items[$itemId]->id,
                        'matched_id'=>$otherItem->id
                    ]);
                    Matching::create([
                        'user_id'=>$otherItem->user_id,
                        'item_id'=>$otherItem->id,
                        'matched_id'=>$items[$itemId]->user_id
                    ]);
                }
            }
            $ids=array_unique($ids);
            foreach ($ids as $id){
                $user=User::find($id);
                $user->notify(new MatchingItems($user->id,$user->name));
            }
            $response = [
                'success' => true,
                'data'    => '',
                'message' => 'Data Retrieved Successfully',
            ];
            return response()->json($response, 200);

        }
        return $this->sendError('Not Found',400);
    }
    private function addItemValuestoItems($items) {
        foreach ($items as $item)
        {
            $itemValues = DB::table('itemvalues')
                ->where('itemvalues.item_id','=',$item->id)
                ->get();
            $item->itemValues =$itemValues;
        }
        return $items;

    }
    private function getMatchingItems ($items) {
        foreach ($items as $item)
        {
            $query = Item::getByDistance($item->lat,$item->lan,4);
            if(empty($query)) {
                $item->otherItems=[];
            }
            else {
                $ids = [];
                //Extract the id's
                foreach($query as $q) {
                    array_push($ids, $q->id);
                }
                // Get the listings that match the returned ids
                $results = DB::table('items')->whereIn( 'id', $ids)
                    ->where('is_found','=',1)
                    ->where('items.id','!=',$item->id)
                    ->where('items.category_id','=',$item->category_id)
                    ->where('items.subcat_id','=' , $item->subcat_id)
                    ->get();
                $results = $this->addItemValuestoItems($results);
                $item->otherItems = $results;
            }
        }
        return $items;
    }
    private function checkIdenticalItems($items) {
        foreach ($items as $itemID => $item)
        {
            if(sizeof($item->otherItems)<1) {
                unset($items[$itemID]);
            }
            else if (sizeof($item->itemValues) <1) {
                unset($items[$itemID]);
            }
            else {
                $otherItems = $item->otherItems;
                $itemValues = $item->itemValues;
                foreach ($item->otherItems as $otherItemID => $otherItem)
                {
                    $count = 0;
                    foreach ($item->itemValues as $itemValueID => $itemValue)
                    {
            //                        return response()->json(['ques' =>$item->itemValues[2]->value,'ans'=> $otherItem->itemValues[2]->value ,'comp'=>($itemValue->value == $otherItem->itemValues[$itemValueID]->value )],200);
                        if(isset($otherItem->itemValues[$itemValueID])) {
                            if($itemValue->value == $otherItem->itemValues[$itemValueID]->value ) {
                            }
                            else {
                                $count++;
                            }
                        }
                        else {
                            $count ++;
                        }
                    }
                    if($count >0) {
                        unset($item->otherItems[$otherItemID]);
                    }
                }
                if(sizeof($item->otherItems)<1) {
                    unset($items[$itemID]);
                }
            }
        }
        return $items;
    }


}//end of Class











//public function fullTextSeacrch(Request $request){
//    if($request->get('itemSearch')){
//        $items=Item::search($request->itemSearch)->get();
//        return $this->sendResponse($items,
//            'Items Successfully');
//    }
//    else{
//        return Item::all();
//    }
//}

// public  function uploadImages(Request $request,$id){
//     $photos=$request->get('images');

//     for ($i=0;$i<sizeof($photos);$i++) {
//         $photo=Photo::create([
//             'src'=> $photos[$i],
//             'photoable_type'=> 'App\Models\Item',
//             'photoable_id'=>$id
//         ]
//     );

//     }
//    return true;
// }
//end of Upload Images

//public  function getImages(Request $request,$id){
//    $photos=$request->get('images');
//
//    for ($i=0;$i<sizeof($photos);$i++) {
//        $img = preg_replace('/^data:image\/\w+;base64,/', '', $photos[$i]);
//        $type = explode(';', $photos[$i])[0];
//        $type = explode('/', $type)[1]; // png or jpg etc
//
//
//        $image_64 = $photos[$i]; //your base64 encoded data
//
//        $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf
//
//        $replace = substr($image_64, 0, strpos($image_64, ',')+1);
//
//        // find substring fro replace here eg: data:image/png;base64,
//
//        $image = str_replace($replace, '', $image_64);
//
//        $image = str_replace(' ', '+', $image);
//
//        $imageName = Str::random(15).'.'.$extension;
//        Storage::disk('public')->put('images/items/'.$id.'/'.$imageName, base64_decode($image));
//        // Storage::disk('public')->put('eejaz/'.$safeName, $image);
//        $photo=Photo::create([
//                'src'=> 'images/items/'.$id.'/'.$imageName,
//                'photoable_type'=> 'App\Models\Item',
//                'photoable_id'=>$id
//            ]
//        );
//    }
//    return true;
//}//end of Upload Images


 // protected function uploadImage($request,$id)
    // {

    //         $photo=Photo::create([
    //             'src'=> $request->image->store('images','public'),
    //             'photoable_type'=> 'App\Models\Item',
    //             'photoable_id'=> $id
    //         ]
    //         );


    //     return $photo;
    // }



//    public function getAllItemOptions($id){
//        resul
//        $item=Item::findOrFail($id);
//        foreach($item->dynamicValues as $option)
//        {
//            $row = $this->model->FindOrFail($option->id);
//        }
//    }

