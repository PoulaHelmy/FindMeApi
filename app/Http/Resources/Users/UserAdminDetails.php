<?php

namespace App\Http\Resources\Users;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

/** @see \App\Models\User */
class UserAdminDetails extends JsonResource
{

    public function toArray($request)
    {
        $user = User::find($this->id);
        $items = [];
        $requests = [];
        foreach ($user->items as $item) {
            array_push($items, $item);
        }
        foreach ($user->itemRequests as $request) {
            array_push($requests, $request);
        }
        return [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'items' => sizeof($items) > 0 ? $items : ['Items Not Founds'],
            'requests' => sizeof($requests) > 0 ? $requests : ['Requests Not Founds'],
        ];
    }
}
