<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LocationTest extends TestCase
{
    use RefreshDatabase;

    public function test_location_can_be_searched()
    {
        create('Post', ['location' => 'New York']);
        create('Post', ['location' => 'Los Angel']);

        $response = $this->getJson('/searchLocation?s=new');

        $response->assertJsonFragment(['New York'])
                ->assertJsonMissing(['Los Angel']);
    }
}
