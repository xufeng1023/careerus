<?php

use Illuminate\Database\Seeder;

class ProfileTableSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Profile::class)->create([
            'user_id' => 1,
            'phone' => '(888)888-8888',
            'resume' => 'resume.pdf',
        ]);
    }
}
