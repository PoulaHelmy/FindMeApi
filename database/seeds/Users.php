<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
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

        \App\Models\User::create([
            'name'=> 'SuperAdmin',
            'email' => 'superadmin@app.com',
            'password' => Hash::make('123456'),
            'api_token'=> Str::random(60),
        ]);
        \App\Models\User::create([
            'name'=> 'Admin',
            'email' => 'admin@app.com',
            'password' => Hash::make('123456'),
            'api_token'=> Str::random(60),
        ]);
        \App\Models\User::create([
            'name'=> 'Poula',
            'email' => 'poula@app.com',
            'password' => Hash::make('123456'),
            'api_token'=> Str::random(60),
        ]);
        \App\Models\User::create([
            'name'=> 'Beshoy',
            'email' => 'beshoy@app.com',
            'password' => Hash::make('123456'),
            'api_token'=> Str::random(60),
        ]);
        \App\Models\User::create([
            'name'=> 'Bashar',
            'email' => 'bashar@app.com',
            'password' => Hash::make('123456'),
            'api_token'=> Str::random(60),
        ]);
        \App\Models\User::create([
            'name'=> 'hossam',
            'email' => 'hossam@app.com',
            'password' => Hash::make('123456'),
            'api_token'=> Str::random(60),
        ]);
        \App\Models\User::create([
            'name'=> 'Besho',
            'email' => 'Besho@app.com',
            'password' => Hash::make('123456'),
            'api_token'=> Str::random(60),
        ]);
    }
}
