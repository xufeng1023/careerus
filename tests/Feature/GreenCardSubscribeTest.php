<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GreenCardSubscribeTest extends TestCase
{
    use RefreshDatabase;

    public function test_anyone_can_subscribe_green_card_update_by_email()
    {
        $sub = raw('GreenCardSubscribe');

        $this->post('/green-card-subscriber', $sub);

        $this->assertDatabaseHas('green_card_subscribe', $sub);
    }
}
