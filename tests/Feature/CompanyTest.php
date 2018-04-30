<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompanyTest extends TestCase
{
    use RefreshDatabase;

    public function test_visa_job_records_can_be_added_to_companies()
    {
        $this->login(
            $admin = create('User', ['role' => 'admin'])
        );

        $company = create('Company');

        $data = raw('VisaJob', ['company_id'=> $company->id]);

        $this->post('/admin/company/'.$company->id.'/visajob', $data);

        $this->assertDatabaseHas('visa_jobs', $data);
    }
}
