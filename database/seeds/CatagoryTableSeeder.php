<?php

use Illuminate\Database\Seeder;

class CatagoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = ['金融','会计','计算机','法律','工程师','设计师'];
        
        foreach($categories as $category) {
            factory(App\Catagory::class)->create([
                'name' => $category,
            ]);
        }
    }
}
