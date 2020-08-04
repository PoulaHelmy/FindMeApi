<?php

namespace App\Http\Controllers\API;


use App\Http\Resources\Users\UserDetailsResource;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Notifications\SignupActivate;
use Illuminate\Support\Facades\Storage;

use Avatar;

class Passport extends ApiHome
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        $credentials = request(['email', 'password']);
        $credentials['active'] = 1;
        $credentials['deleted_at'] = null;
        if (!auth()->attempt($credentials))
            return $this->sendError('Unauthorized', 400);
        $user = $request->user();
        $success['token'] = $user->createToken('Personal Access Token')->accessToken;
        $success['name'] = $user->name;
        return $this->sendResponse($success, 'Success Login Operation');

    }//end of login

    public function signup(Request $request)
    {
        $v = validator($request->only('email', 'name', 'password', 'phone'), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'phone' => 'required|string|min:10|max:15|unique:users,phone',
        ]);
        if ($v->fails())
            return $this->sendError('Validation Error.!', $v->errors()->all(), 400);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'activation_token' => Str::random(60)
        ]);
        $avatar = Avatar::create($user->name)->getImageObject()->encode('png');
        Storage::disk('public')->put('avatars/' . $user->id . '/avatar.png', (string)$avatar);
        $user->notify(new SignupActivate($user));
        $success['token'] = $user->createToken('FindMe')->accessToken;
        $success['name'] = $user->name;
        return $this->sendResponse($success, 'Successfully created user!');
    }//end of register

    public function details()
    {
        return new UserDetailsResource(auth()->user());
    }//end of details

    public function update(Request $request)
    {
        $v = validator($request->all(), [
            'name' => 'string|max:255',
            'email' => 'string|email|max:255|unique:users',
            'password' => 'string|min:6',
            'phone' => 'string|min:10|max:15|unique:users,phone',
            'photo' => 'string'
        ]);
        if ($v->fails())
            return $this->sendError('Validation Error.!', $v->errors()->all(), 400);
        if (!auth()->user())
            return $this->sendError('Unauthorized', 400);
        $requestArray = $request->all();
        if (isset($requestArray['password']) && $requestArray['password'] != "") {
            $requestArray['password'] = Hash::make($requestArray['password']);
        } else {
            unset($requestArray['password']);
        }
        if ($request['photo']) {
            if ($requestArray['photo'] != '' && $requestArray['photo'] != null) {
                Storage::disk('public')->delete('avatars/' . auth()->user()->id . '/' . auth()->user()->avatar);
                $img = preg_replace('/^data:image\/\w+;base64,/', '', $requestArray['photo']);
                $type = explode(';', $requestArray['photo'])[0];
                $type = explode('/', $requestArray['photo'])[1]; // png or jpg etc
                $image_64 = $requestArray['photo']; //your base64 encoded data
                $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf
                $replace = substr($image_64, 0, strpos($image_64, ',') + 1);
                // find substring fro replace here eg: data:image/png;base64,
                $image = str_replace($replace, '', $image_64);
                $image = str_replace(' ', '+', $image);
                $imageName = 'avatar.' . $extension;
                auth()->user()->avatar = $imageName;
                auth()->user()->save();
                Storage::disk('public')->put('avatars/' . auth()->user()->id . '/' . $imageName, base64_decode($image));
            }
        }
        $user = auth()->user()->update($requestArray);
        return $this->sendResponse(new UserDetailsResource(auth()->user()), 'Successfully created user!');
    }//end of Update

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        $request->user()->token()->delete();
        return $this->sendResponse(null, 'Successfully Loged OUT user!');
    }//end of logout

    public function SignupActivate($token)
    {
        $user = User::where('activation_token', $token)->first();
        if (!$user)
            return $this->sendError('This activation token is invalid.!', 404);
        $user->active = true;
        $user->activation_token = '';
        $user->save();
        return $user;
    }//end of signup Activate

    public function SignupActivate2(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        if (!$user)
            return $this->sendError('This USer Not Found.!', 404);
        $user->active = true;
        $user->activation_token = '';
        $user->save();
        return $this->sendResponse(null, 'Successfully Activate This User!');
    }//end of signup Activate

    public function UserData($id)
    {
        return new UserDetailsResource(User::find($id));
    }//end of UserData

    public function updatePassword(Request $request)
    {
        $v = validator($request->all(), [
            'old_password' => 'string|min:6',
            'new_password' => 'string|min:6',
            'new_password_Confim' => 'string|min:6'
        ]);
        if ($v->fails())
            return $this->sendError('Validation Error.!', $v->errors()->all(), 400);
        if (!auth()->user())
            return $this->sendError('Unauthorized', 400);
        if (Hash::check($request->old_password, auth()->user()->getAuthPassword())) {
            auth()->user()->password = Hash::make($request->new_password);
            auth()->user()->save();
            return $this->sendResponse('', 'PassWord Updated Successfully');
        }
        return $this->sendError('Validation Error.!', 'Old PassWord Wrong', 400);
    }//end of Update PassWord
}//enf of controller
