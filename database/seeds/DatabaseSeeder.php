<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,
            CatagoryTableSeeder::class,
            CompanyTableSeeder::class,
            TagSeeder::class
        ]);
    }
}
