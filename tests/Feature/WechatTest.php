<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WechatTest extends TestCase
{
    use RefreshDatabase;

    public function test_requests_with_wrong_referer_link_will_see_not_found()
    {
        $this->expectException('Symfony\Component\HttpKernel\Exception\NotFoundHttpException');

        $this->get('/api/search');
    }

    public function test_requests_with_wechat_referer_are_ok()
    {
        $this->get('/api/search', ['referer' => config('app.wechat_uri_prefix')])->assertStatus(200);
    }

    public function test_wechat_app_can_search_job_by_title()
    {
        $post1 = create('Post', ['title' => 'nice day']);
        $post2 = create('Post', ['title' => 'bad day']);

        $this->getJson('/api/search?search=nice&state=', ['referer' => config('app.wechat_uri_prefix')])
            ->assertSee($post1->title)
            ->assertDontSee($post2->title);

        $this->getJson('/api/search?search=bad&state=', ['referer' => config('app.wechat_uri_prefix')])
            ->assertSee($post2->title)
            ->assertDontSee($post1->title);
    }

    public function test_job_will_return_with_company_info()
    {
        $post = create('Post', ['title' => 'nice day']);

        $this->getJson('/api/search?search=nice&state=', ['referer' => config('app.wechat_uri_prefix')])
            ->assertSee($post->title)
            ->assertSee($post->company->name)
            ->assertDontSee($post->description);
    }

    public function test_wechat_app_can_search_job_by_location()
    {
        $ca = create('State', ['STATE_CODE' => 'CA', 'simplified_name' => '加州']);
        $ny = create('State', ['STATE_CODE' => 'NY', 'simplified_name' => '纽约州']);

        $post1 = create('Post', ['title' => 'nice day', 'location' => 'aa,ca']);
        $post2 = create('Post', ['title' => 'bad day', 'location' => 'bb,ny']);

        $this->getJson('/api/search?search=&state=加州', ['referer' => config('app.wechat_uri_prefix')])
            ->assertSee($post1->title)
            ->assertDontSee($post2->title);

        $this->getJson('/api/search?search=nice&state=加州', ['referer' => config('app.wechat_uri_prefix')])
            ->assertSee($post1->title)
            ->assertDontSee($post2->title);

        $this->getJson('/api/search?search=&state=纽约', ['referer' => config('app.wechat_uri_prefix')])
            ->assertSee($post2->title)
            ->assertDontSee($post1->title);

        $this->getJson('/api/search?search=&state=德州', ['referer' => config('app.wechat_uri_prefix')])
            ->assertDontSee($post2->title)
            ->assertDontSee($post1->title);

        $this->getJson('/api/search?search=any&state=纽约', ['referer' => config('app.wechat_uri_prefix')])
            ->assertDontSee($post2->title)
            ->assertDontSee($post1->title);
    }

    public function test_wechat_app_can_see_a_post()
    {
        $post = create('Post', ['description' => '<div>aa</div><div>bbb</div>']);

        $this->getJson('/api/job?i='.$post->identity, ['referer' => config('app.wechat_uri_prefix')])
            ->assertSee($post->title);
    }
}
