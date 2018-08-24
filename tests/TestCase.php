<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $companyModel = 'CompanyData';
    protected $companyTable = 'company_data';

    public function login(\App\User $user)
    {
        $this->be($user);
    }
}
