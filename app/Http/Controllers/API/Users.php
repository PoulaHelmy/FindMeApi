<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\Users\UserAdminDetails;
use App\Http\Resources\Users\UserDetailsResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Users extends ApiHome
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function getAllUsers()
    {
        return $this->sendResponse(DB::table('users')->get(), 'Success Retrive ALL Users');
    }

    public function getLastUsers()
    {
        return $this->sendResponse(UserDetailsResource::collection(
            DB::table('users')->orderBy('id', 'desc')->take(10)->get()), 'Success Retrive ALL Users');
    }

    public function getUserData(Request $request)
    {
        $data = User::where('id', $request->id)->first();

        return $this->sendResponse($data, 'Success Retrive ALL Users');
    }

    public function adminGetUserData($id)
    {
        $data = DB::table('users')->where('id', $id)->first();
        return $this->sendResponse(new UserAdminDetails($data), 'Success Get This User');
    }

}//End Of Class
