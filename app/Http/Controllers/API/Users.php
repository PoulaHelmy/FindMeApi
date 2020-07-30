<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Users\UserDetailsResource;
class Users extends ApiHome
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function getAllUsers(){
        return $this->sendResponse(DB::table('users')->get(),'Success Retrive ALL Users');
    }
    public function getLastUsers(){
        return $this->sendResponse(UserDetailsResource::collection(
            DB::table('users')->orderBy('id', 'desc')->take(10)->get()),'Success Retrive ALL Users');
    }
    public function getUserData(Request $request){
        $data=User::where('id',$request->id)->get();
        return $this->sendResponse($data,'Success Retrive ALL Users');
    }
    public function indexWithFilter(Request $request){
        if($request->get('filter')==''||$request->get('filter')==null){
            return 
                DB::table('users')->orderBy($request->get('order'), $request->get('sort'))->
                paginate($request->get('pageSize'));
        }
        else{
            return
              DB::table('users')->when($request->filter,function ($query)use($request){
                    return $query->where('name','like','%'.$request->filter.'%');})
                    ->orderBy($request->get('order'), $request->get('sort'))
                    ->paginate($request->get('pageSize'));
        }
    }//endof index

}
