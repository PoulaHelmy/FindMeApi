<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
class Tags extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $ids = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20];

        for($i = 0 ;$i< 30 ;$i++){
            $array = [
                'name' => $faker->word,
            ];
            \App\Models\Tag::create($array);
        }
    }
}
