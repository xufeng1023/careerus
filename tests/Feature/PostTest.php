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

    public function test_posts_can_be_searched_by_title_and_location()
    {
        $post1 = create('Post', ['title' => 'Web Developer', 'location' => 'New York, NY']);
        $post2 = create('Post', ['title' => 'Graphic Designer', 'location' => 'New York, NY']);
        $post3 = create('Post', ['title' => 'Web Developer', 'location' => 'New Jersey, NJ']);

        $this->get('/jobs?s=web&l=new+york')
            ->assertSee($post1->title)
            ->assertDontSee($post2->title);

        $this->get('/jobs?s=web&l=')
            ->assertSee($post1->title)
            ->assertSee($post3->title)
            ->assertDontSee($post2->title);

        $this->get('/jobs?s=&l=new+york')
            ->assertSee($post1->location)
            ->assertSee($post2->location)
            ->assertDontSee($post3->location);

        $this->get('/jobs?s=&l=')
            ->assertDontSee($post1->title)
            ->assertDontSee($post2->title)
            ->assertDontSee($post3->title);

        $this->get('/jobs?s=cook&l=')
            ->assertDontSee($post1->title)
            ->assertDontSee($post2->title)
            ->assertDontSee($post3->title);

        $this->get('/jobs?s=&l=los+angel')
            ->assertDontSee($post1->title)
            ->assertDontSee($post2->title)
            ->assertDontSee($post3->title);

        $this->get('/jobs?s=web&l=los+angel')
            ->assertDontSee($post1->title)
            ->assertDontSee($post2->title)
            ->assertDontSee($post3->title);

        $this->get('/jobs?s=&l=york')
            ->assertSee($post1->location)
            ->assertSee($post2->location)
            ->assertDontSee($post3->location);
    }
}
