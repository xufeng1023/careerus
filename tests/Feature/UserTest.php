<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    
    use RefreshDatabase;

    public function test_guests_cannot_add_posts()
    {
        $this->expectException(
            'Illuminate\Auth\AuthenticationException'
        );

        $this->post('/admin/post/add');                
    }

    public function test_students_cannot_add_posts()
    {
        $this->expectException(
            'Symfony\Component\HttpKernel\Exception\NotFoundHttpException'
        );

        $this->login(
            $student = create('User', ['role' => 'student'])
        );

        $this->post('/admin/post/add');
    }

    public function test_guests_cannot_access_admin_pages()
    {
        $this->expectException(
            'Illuminate\Auth\AuthenticationException'
        );

        $this->get('/admin');
    }

    public function test_students_cannot_access_admin_pages()
    {
        $this->expectException(
            'Symfony\Component\HttpKernel\Exception\NotFoundHttpException'
        );

        $this->login(
            $student = create('User', ['role' => 'student'])
        );

        $this->get('/admin');
    }

    public function test_guests_and_students_can_see_all_the_posts()
    {
        $post = create('Post', ['identity' => 'aaa']);
        $this->get('/jobs')->assertSee($post->title);
    }

    public function test_admin_can_add_a_post()
    {
        $this->login(
            $admin = create('User', ['role' => 'admin'])
        );

        $post = raw('Post', ['user_id' => $admin->id, 'title' => 'a--bb']);

        $this->post('/admin/post/add', $post);

        $post['title'] =  'a bb';

        $this->assertDatabaseHas('posts', $post);
    }

    public function test_admin_can_add_a_catagory()
    {
        $this->login(
            $admin = create('User', ['role' => 'admin'])
        );

        $this->post('/admin/catagory/add', $catagory = raw('Catagory'));

        $this->assertDatabaseHas('catagories', $catagory);
    }

    public function test_admin_can_see_all_catagories()
    {
        $this->login(
            $admin = create('User', ['role' => 'admin'])
        );

        $catagory = create('Catagory');

        $this->get('/admin/catagory')->assertSee($catagory->name);
    }

    public function test_admin_can_see_all_companies()
    {
        $this->login(
            $admin = create('User', ['role' => 'admin'])
        );

        $company = create('Company');

        $this->get('/admin/company')->assertSee($company->name);
    }

    public function test_admin_can_add_a_company()
    {
        $this->login(
            $admin = create('User', ['role' => 'admin'])
        );

        $this->post('/admin/company/add', $company = raw('Company'));

        $this->assertDatabaseHas('companies', $company);
    }

    public function test_admin_can_update_a_company()
    {
        $this->login(
            $admin = create('User', ['role' => 'admin'])
        );

        $company = create('Company', ['name' => 'Google']);

        $this->post('/admin/company/update/'.$company->id, ['name' => 'Apple']);

        $this->assertDatabaseMissing('companies', ['name' => 'Google']);
        $this->assertDatabaseHas('companies', ['name' => 'Apple']);
    }

    public function test_admin_can_see_all_users()
    {
        $this->login(
            $admin = create('User', ['role' => 'admin'])
        );

        $this->get('/admin/user')->assertSee($admin->name);
    }

    public function test_admin_can_add_an_user()
    {
        $this->login(
            $admin = create('User', ['role' => 'admin'])
        );

        $this->post('/admin/user/add', $user = raw('User'));

        $this->assertDatabaseHas('users', ['email' => $user['email'], 'id' => 2]);
    }

    public function test_admin_can_update_an_user()
    {
        $this->login(
            $admin = create('User', ['role' => 'admin', 'name' => 'admin'])
        );

        $this->post('/admin/user/update/'.$admin->id, ['name' => 'not admin']);

        $this->assertDatabaseMissing('users', ['name' => 'admin']);
        $this->assertDatabaseHas('users', ['name' => 'not admin']);
    }

    // public function test_user_can_search_jobs_by_job_title_andOr_location()
    // {
    //     $post1 = create('Post', ['title' => 'job1']);
    //     $post2 = create('Post', ['title' => 'job2']);

    //     $this->get('/jobs')->assertSee($post1->title)->assertDontSee($post2->title);
    // }
}
