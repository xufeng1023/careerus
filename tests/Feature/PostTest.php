<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_identities_are_unique()
    {
        $this->login(
            $admin = create('User', ['role' => 'admin'])
        );

        $post1 = raw('Post', ['title' => 'post']);
        $this->post('/admin/post/add', $post1);

        $post2 = raw('Post', ['title' => 'post']);
        $this->post('/admin/post/add', $post2);

        $post1 = \App\Post::first();
        $post2 = \App\Post::find(2);

        $this->assertNotEquals($post1->identity, $post2->identity);
    }

    public function test_posts_with_same_title_in_the_uri_should_show_by_identity()
    {
        $this->login(
            $admin = create('User', ['role' => 'admin'])
        );

        $post1 = raw('Post', ['title' => 'post']);
        $this->post('/admin/post/add', $post1);

        $post2 = raw('Post', ['title' => 'post']);
        $this->post('/admin/post/add', $post2);

        $post1 = \App\Post::first();

        $this->get("/job/post?i={$post1->identity}")
            ->assertSee($post1['description'])
            ->assertDontSee($post2['description']);
    }
}
