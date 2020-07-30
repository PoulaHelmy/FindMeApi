<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class SubCategoris extends Seeder
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
                'meta_keywords' => $faker->name,
                'meta_des' => $faker->name,
                'category_id'=>$ids[rand(0,19)]
            ];
            $subcat=\App\Models\Subcat::create($array);
            $subcat->inputs()->sync(array_rand($ids , 10));


           // $subcat->inputs()->sync(array_rand($ids , 5));
//            $cat=\App\Models\Category::find(array_rand($ids , 1));
//
//            $cat->subcat()->associate($subcat);
//            $cat->save();


            //$cat=\App\Models\Category::find(array_rand($ids , 1))




        }
    }
}
