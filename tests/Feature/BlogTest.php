<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BlogTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_see_blog()
    {
        $blog = create('Blog');

        $this->get('/'.urlencode('求职攻略'))->assertSee($blog->title);
    }
}
