<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GreenCardSubTest extends TestCase
{
    use RefreshDatabase;

    public function test_url_is_required()
    {
        $this->expectException('Illuminate\Validation\ValidationException');

        $this->post('/green-card-subscriber', ['email' => 'ss@ff.com']);
    }

    public function test_url_must_be_valid()
    {
        $this->expectException('Illuminate\Validation\ValidationException');
        
        $this->post('/green-card-subscriber', ['email' => 'ss@ff.com', 'url' => 'a']);
    }

    public function test_email_is_required()
    {
        $this->expectException('Illuminate\Validation\ValidationException');
        
        $this->post('/green-card-subscriber', ['url' => 'http://localhost']);
    }

    public function test_email_must_be_valid()
    {
        $this->expectException('Illuminate\Validation\ValidationException');
        
        $this->post('/green-card-subscriber', ['email' => 'ss@ff', 'url' => 'http://localhost']);
    }

    public function test_email_must_be_unique()
    {
        $this->expectException('Illuminate\Database\QueryException');

        create('GreenCardSubscribe', ['email' => 'ss@ff.com']);
        create('GreenCardSubscribe', ['email' => 'ss@ff.com']);
    }
}
