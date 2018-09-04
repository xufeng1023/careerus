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
        $blog = create('Blog', ['title' => 'aaa']);

        $this->get('/blog/'.$blog->title)->assertStatus(200);
    }

    public function test_blog_title_strip_slashes()
    {
        $this->login(
            create('User', ['role' => 'admin'])
        );

        $blog = raw('Blog', ['title' => 'aaa/ff']);

        $this->post('/admin/blog/add', $blog);

        $this->get('/blog/aaaff')->assertStatus(200);
    }
}
