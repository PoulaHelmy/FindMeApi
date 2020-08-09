<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, SoftDeletes;


    protected $dates = ['deleted_at'];
    protected $fillable = [
        'name', 'email', 'password', 'phone', 'active', 'activation_token', 'avatar'
    ];
    protected $hidden = [
        'password', 'remember_token', 'activation_token'
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    protected $appends = ['avatar_url'];

    public function getAvatarUrlAttribute()
    {
        return Storage::disk('public')->get('avatars/' . $this->id . '/' . $this->avatar);
    }


    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function itemRequests()
    {
        return $this->hasMany(RequestItems::class, 'user_id');
    }
//    public function messages(){
//        return $this->hasMany(Message::class,'');
//    }


}
