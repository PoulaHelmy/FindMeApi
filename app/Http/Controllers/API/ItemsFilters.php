<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use App\Models\Subcat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Resources\Items\ItemsDetailsResource;
use App\Http\Resources\Items\ItemsResource;
class ItemsFilters extends ApiHome
{
    public function __construct(Item $model)
    {
        parent::__construct($model);
    }

    public function myFilter(Request $request,$q){
        if($q==='nosearch'){
            return $this->sendResponse('','Success');
        }
        return $this->sendResponse( ItemsDetailsResource::collection(Item::search($q)->get()),'Success');



    }//end of myFilter


}//end of Class


//
////        $lostItemCount=Item::where('is_found','0')->count();
////        $foundItemCount=Item::where('is_found','1')->count();
////        dd($request->itemSearch,$lostItemCount,$foundItemCount);
//$cats=Category::all();
//for($i=0;$i<sizeof($cats);$i++){
//    echo '--------------------------------';
//    foreach ($cats[$i]->subcat as $subcat){
//        echo '<br>'.$subcat->name.'<br>';
////                    foreach ($subcat->items as $item){
////                        echo '/n'.$item.'/n';
////
////
////
////                    }
//
//
//
//
//
//
//
//
//
//
//
//
//    }
//
//
//
//
//
//
