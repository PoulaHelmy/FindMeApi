<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class Inputs extends Seeder
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
                'label'=>$faker->word,
                'type'=>$faker->word,
                'placeholder'=>$faker->word,
            ];
            $input=\App\Models\Input::create($array);
            //$input->subcat()->sync(array_rand($ids , 1));



        }
    }
}
