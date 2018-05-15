<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            CatagoryTableSeeder::class,
            UsersTableSeeder::class,
            PostSeeder::class
        ]);
    }
}
