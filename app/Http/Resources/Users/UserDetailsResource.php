<?php

namespace App\Http\Resources\Users;

use App\Models\Photo;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserDetailsResource extends JsonResource
{

    public function toArray($request)
    {
        $image = 'data:image/png;base64,' . base64_encode(Storage::disk('public')->get('avatars/' . $this->id . '/' . $this->avatar));

        return [
            'id'=>$this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'photo' => $image,


        ];
    }
}
