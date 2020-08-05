<?php

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class Users extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        $faker = Faker::create();

//        $user1 = \App\Models\User::create([
//            'name' => 'SuperAdmin',
//            'email' => 'superadmin@app.com',
//            'password' => Hash::make('123456'),
//        'activation_token'=> Str::random(60),
//            'phone' => '01271553769',
//            'active' => true
//        ]);
//        $avatar = Avatar::create($user1->name)->getImageObject()->encode('png');
//        Storage::disk('public')->put('avatars/' . $user1->id . '/avatar.png', (string)$avatar);
//        $user2 = \App\Models\User::create([
//            'name' => 'Admin',
//            'email' => 'admin@app.com',
//            'password' => Hash::make('123456'),
//            'activation_token' => Str::random(60),
//            'phone' => '01271553766',
//            'active' => true
//        ]);
//        $avatar = Avatar::create($user2->name)->getImageObject()->encode('png');
//        Storage::disk('public')->put('avatars/' . $user2->id . '/avatar.png', (string)$avatar);
//
//        $user3 = \App\Models\User::create([
//            'name' => 'Poula',
//            'email' => 'poula@app.com',
//            'password' => Hash::make('123456'),
//            'activation_token' => Str::random(60),
//            'phone' => '01271553755',
//            'active' => true
//        ]);
//        $avatar = Avatar::create($user3->name)->getImageObject()->encode('png');
//        Storage::disk('public')->put('avatars/' . $user3->id . '/avatar.png', (string)$avatar);
//
//        $user4 = \App\Models\User::create([
//            'name' => 'Beshoy',
//            'email' => 'beshoy@app.com',
//            'password' => Hash::make('123456'),
//            'activation_token' => Str::random(60),
//            'phone' => '01271553722',
//            'active' => true
//        ]);
//        $avatar = Avatar::create($user4->name)->getImageObject()->encode('png');
//        Storage::disk('public')->put('avatars/' . $user4->id . '/avatar.png', (string)$avatar);
//
//        $user5 = \App\Models\User::create([
//            'name' => 'Bashar',
//            'email' => 'bashar@app.com',
//            'password' => Hash::make('123456'),
//            'activation_token' => Str::random(60),
//            'phone' => '01271553799',
//            'active' => true
//        ]);
//        $avatar = Avatar::create($user5->name)->getImageObject()->encode('png');
//        Storage::disk('public')->put('avatars/' . $user5->id . '/avatar.png', (string)$avatar);
//
//        $user6 = \App\Models\User::create([
//            'name' => 'hossam',
//            'email' => 'hossam@app.com',
//            'password' => Hash::make('123456'),
//            'activation_token' => Str::random(60),
//            'phone' => '01271553744',
//            'active' => true
//        ]);
//        $avatar = Avatar::create($user6->name)->getImageObject()->encode('png');
//        Storage::disk('public')->put('avatars/' . $user6->id . '/avatar.png', (string)$avatar);
//
//        $user7 = \App\Models\User::create([
//            'name' => 'Besho',
//            'email' => 'Besho@app.com',
//            'password' => Hash::make('123456'),
//            'activation_token' => Str::random(60),
//            'phone' => '01271553777',
//            'active' => true
//        ]);
//        $avatar = Avatar::create($user7->name)->getImageObject()->encode('png');
//        Storage::disk('public')->put('avatars/' . $user7->id . '/avatar.png', (string)$avatar);
//

    }
}
