<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Socialite;
use App\SocialAccountService;
class SocialAccountController extends Controller
{

    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }


    public function handleProviderCallback(SocialAccountService $ProfileService,$provider)
    {
        // $user->token;
        try {

            //$user = Socialite::driver($provider)->user();
            $user = Socialite::driver($provider)->user();

        }catch (\Exception $e){
            return redirect()->to('login');
        }

        $user = $this->createUser($user,$provider);

        auth()->login($user->user,true);

        return redirect()->to('home');




    }



    function createUser($getInfo,$provider){

        $user = SocialAccount::where('provider_id', $getInfo->id)->first();

        if (!$user) {
            $user = User::create([
                'name'     => $getInfo->name,
                'email'    => $getInfo->email,
                'activation_token' => Str::random(60)
            ]);
            $acc=new SocialAccount();
            $acc->provider_id=$getInfo->id;
            $acc->provider_name=$provider;
            $user->accounts()->save($acc);


        }
        return $user;
    }










}//end of controller
