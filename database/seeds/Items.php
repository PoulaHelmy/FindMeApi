<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
class Items extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $ids = [1,2,3,4,5,6,7,8,9];

        for($i = 0 ;$i< 10 ;$i++){
            $array = [
                'name' => $faker->word,
                'des' => $faker->paragraph,
                'location' => $faker->paragraph,
                'user_id' => 1
            ];
            $item = \App\Models\Item::create($array);
            $photo='images/I4Srgioo0c5zUU6t5rnjj1ZSxULV4JgQ4nTJU3SG.jpeg';


        }
    }
}
