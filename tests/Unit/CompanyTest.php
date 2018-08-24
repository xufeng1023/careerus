<?php

namespace Tests\Unit;

use Tests\TestCase;
//use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompanyTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_has_a_total_sponsor_attribute()
    {
        $company = create('CompanyData');

        $sponsor_data1 = create('CompanySponsorData', ['company_id' => $company->id, 'year' => 2017]);
        $sponsor_data2 = create('CompanySponsorData', ['company_id' => $company->id, 'year' => 2017]);

        $this->assertEquals(
            $sponsor_data1->sponsor_number + $sponsor_data2->sponsor_number,
            $company->totalSponsor
        );
    }
}
