<?php

use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run()
    {
        for($i = 0; $i <= 20; $i++) {
            factory(App\Post::class)->create(
                [
                    'company_id' => App\CompanyData::all()->random(),
                    'catagory_id' => App\Catagory::all()->random()
                ]
            )->tags()->attach(factory(App\Tag::class)->create());
        }
    }
}
