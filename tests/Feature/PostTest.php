<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_chinese_post_link_prior_to_english_link()
    {
        $post = create('Post', ['title' => 'English title', 'chinese_title' => '中文标题']);

        $this->assertEquals('/job/中文标题?i='.$post->identity, $post->link());
    }

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

    public function test_job_link_can_also_created_with_chinese_title()
    {
        $post_zh_en = create('Post', ['chinese_title' => '中文标题']);

        $this->get('/job/'.urlencode($post_zh_en->chinese_title).'?i='.$post_zh_en->identity)->assertStatus(200);
    }

    public function test_chinese_title_can_be_added()
    {
        $data = ['chinese_title' => '中文'];
        
        create('Post', $data);

        $this->assertDatabaseHas('posts', $data);
    }

    public function test_post_recommendation_can_be_toggled()
    {
        $post = create('Post');

        $this->assertDatabaseHas('posts', ['recommended' => 0]);

        $this->login(
            $admin = create('User', ['role' => 'admin'])
        );

        $this->post('/admin/job/recommend/1');

        $this->assertDatabaseHas('posts', ['recommended' => 1]);

        $this->post('/admin/job/recommend/1');

        $this->assertDatabaseHas('posts', ['recommended' => 0]);
    }
}
