<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LocationTest extends TestCase
{
    use RefreshDatabase;

    public function test_location_can_be_searched()
    {
        create('City', ['name' => 'New York']);
        create('City', ['name' => 'Los Angel']);

        $response = $this->getJson('/search?s=new');

        $response->assertJsonFragment(['name' => 'New York'])
                ->assertJsonMissing(['name' => 'Los Angel']);
    }
}
