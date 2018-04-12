<?php

use Illuminate\Database\Seeder;

class CompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $companies = ['DreamGo', 'FreeH-1B'];

        foreach($companies as $company) {
            factory(App\Company::class)->create([
                'email' => $company.'@'.$company.'.com',
                'name' => $company,
                'hr' => 'Mr.'.$company
            ]);
        }
    }
}
