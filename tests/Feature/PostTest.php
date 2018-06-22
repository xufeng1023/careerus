<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_posts_with_same_title_in_the_uri_should_show_by_identity()
    {
        $this->login(
            $admin = create('User', ['role' => 'admin'])
        );

        $title = 'Development Coordinator';

        $post1 = raw('Post', ['title' => $title]);
        $this->post('/admin/post/add', $post1);

        $post2 = raw('Post', ['title' => $title]);
        $this->post('/admin/post/add', $post2);

        $post1 = \App\Post::first();


        $this->get("/job/development-coordinator?i={$post1->identity}")
        ->assertSee($post1['description'])
        ->assertDontSee($post2['description']);
    }

    public function test_chinese_title_can_be_added()
    {
        $data = ['chinese_title' => '中文'];
        
        create('Post', $data);

        $this->assertDatabaseHas('posts', $data);
    }
}
