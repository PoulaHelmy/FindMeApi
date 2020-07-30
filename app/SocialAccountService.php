<?php
namespace App;
use App\Models\SocialAccount;
use App\Models\User;
use App\Notifications\SignupActivate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as ProviderUser;

class SocialAccountService{


    public function findOrCreate(ProviderUser $provideruser,$provider){

        $account=SocialAccount::where('provider_id',$provideruser->getId())
            ->where('provider_name',$provider)->get();
        if($account)
            return $account->user;
        else{
            $user=User::where('email',$provideruser->getEmail())->first();
            if(!$user) {
                $user=User::create([
                    'email'=>$provideruser->getEmail(),
                    'name'=>$provideruser->getName(),
                    'activation_token' => Str::random(60)
                ]);
//                $avatar = Avatar::create($user->name)->getImageObject()->encode('png');
//                Storage::put('avatars/'.$user->id.'/avatar.png', (string) $avatar);
//                $user->notify(new SignupActivate($user));
//                $user->createToken('FindMe')->accessToken;
            }//end of if
            $user->accounts()->create([
                'provider_name'=>$provider,
                'provider_id'=>$provideruser->getId()
            ]);
            return $user;
          }//end of else



    }
}
