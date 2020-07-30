<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class UsersNotifications extends ApiHome
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function index(Request $request)
    {
        $AllNotifications=[];
//        foreach (auth()->user()->notifications as $notification) {
//            array_push($AllNotifications,$notification);
//        }

        foreach (auth()->user()->unreadNotifications as $notification) {

            array_push($AllNotifications,$notification);
        }

        return $this->sendResponse($AllNotifications,
            'All Notifications Retrieved Successfully');
    }
    public function markNotificationAsRedaed($id){

        foreach (auth()->user()->unreadNotifications as $notification) {
            if($notification->id==$id){
                $notification->markAsRead();
            }
        }
        return $this->sendResponse('',
            'Notification Marked As Read Successfully');
    }
}
