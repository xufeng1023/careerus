<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LocationTest extends TestCase
{
    use RefreshDatabase;

    public function test_location_can_be_searched()
    {
       // $this->json('GET', '/search', ['s'=> 'new york'])->assertJson(['name' => 'New York']);
    }
}
