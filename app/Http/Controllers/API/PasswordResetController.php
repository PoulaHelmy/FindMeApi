<?php

namespace App\Http\Controllers\API;

use App\Models\PasswordReset;
use App\Models\User;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordResetController extends ApiHome
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }//end of constructor

    public function create(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user)
            return $this->sendError('We can\'t find a user with that e-mail address', '', 404);
        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            ['email' => $user->email, 'token' => Str::random(60)]);
        if ($user && $passwordReset)
            $user->notify(new PasswordResetRequest($passwordReset->token));
        return $this->sendResponse($passwordReset, 'We have e-mailed your password reset link!');
    }//end of create

    public function find($token)
    {
        $passwordReset = PasswordReset::where('token', $token)->first();
        if (!$passwordReset)
            return $this->sendError('This password reset token is invalid.', 404);
        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();
            return $this->sendError('This password reset token is invalid.', 404);
        }
        return $this->sendResponse($passwordReset, 'Success');
    }//end of find

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|confirmed',
            'token' => 'required|string'
        ]);
        $passwordReset = PasswordReset::where([
            ['token', $request->token],
            ['email', $request->email]
        ])->first();
        if (!$passwordReset)
            return $this->sendError('This password reset token is invalid.', 404);
        $user = User::where('email', $passwordReset->email)->first();
        if (!$user)
            return $this->sendError('We can\'t find a user with that e-mail address.', 404);
        $user->password = Hash::make($request->password);
        $user->save();
        $passwordReset->delete();
        $user->notify(new PasswordResetSuccess($passwordReset));
        return $this->sendResponse($user, 'Success');
    }//end of reset

}//end of controller
