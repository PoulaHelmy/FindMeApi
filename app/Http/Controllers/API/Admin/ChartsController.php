<?php

namespace App\Http\Controllers\API\Admin;
use App\Http\Controllers\API\ApiHome;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Chat;
use App\Models\Input;
use App\Models\Item;
use App\Models\Message;
use App\Models\RequestItems;
use App\Models\Subcat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Users\UserDetailsResource;

class ChartsController extends ApiHome
{
    public function __construct(RequestItems $model){
        parent::__construct($model);
    }//end of constructor

    public function allResults(){
        $data=[
            'allUsers'=>User::all()->Count(),
            'allItems'=>Item::where('category_id','!=','11')->get()->count(),
            'allPersons'=>Item::where('category_id','=','11')->get()->count(),
            'allRequests'=>RequestItems::all()->Count(),
            'allCategories'=>Category::all()->Count(),
            'allSubCategories'=>Subcat::all()->Count(),
            'allInputs'=>Input::all()->Count(),
            'approvedRequests'=>RequestItems::where('status','=','1')->get()->count(),
            'rejectedRequests'=>RequestItems::where('status','=','-1')->get()->count(),
            'pendingRequests'=>RequestItems::where('status','=','0')->get()->count(),
            'allChats'=>Chat::all()->count(),
            'allMessages'=>Message::all()->count()

        ];
        return $this->sendResponse($data,'ALL Statistics For The APP');
    }
    public function summeryData(){
        $data=[
            'lastUsers'=>DB::table('users')->orderBy('id', 'desc')->take(10)->get(),
            'lastItems'=>Item::where('category_id','!=','11')->orderBy('id', 'desc')->take(10)->get(),
            'lastPersons'=>Item::where('category_id','=','11')->orderBy('id', 'desc')->take(10)->get(),
        ];
        return $this->sendResponse($data,'ALL Statistics For The APP');
    }
    public function allResultsSummery(){
        $data=[
            'allUsers'=>User::all()->Count(),
            'allItems'=>Item::where('category_id','!=','11')->get()->count(),
            'allPersons'=>Item::where('category_id','=','11')->get()->count(),
            'lastUsers'=>UserDetailsResource::collection(DB::table('users')->orderBy('id', 'desc')->take(10)->get()),
            'lastItems'=>Item::where('category_id','!=','11')->orderBy('id', 'desc')->take(10)->get(),
            'lastPersons'=>Item::where('category_id','=','11')->orderBy('id', 'desc')->take(10)->get(),
            'allRequests'=>RequestItems::all()->Count(),
            'allCategories'=>Category::all()->Count(),
            'allSubCategories'=>Subcat::all()->Count(),
            'allInputs'=>Input::all()->Count(),
            'approvedRequests'=>RequestItems::where('status','=','1')->get()->count(),
            'rejectedRequests'=>RequestItems::where('status','=','-1')->get()->count(),
            'pendingRequests'=>RequestItems::where('status','=','0')->get()->count(),
            'allChats'=>Chat::all()->count(),
            'allMessages'=>Message::all()->count()

        ];
        return $this->sendResponse($data,'ALL Statistics For The APP');
    }

}
