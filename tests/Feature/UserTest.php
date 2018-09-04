<?php

namespace Tests\Feature;

use Tests\TestCase;
use Stripe\{Stripe, Token};
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

class UserTest extends TestCase
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

        $this->get('/admin/applies');
    }

    public function test_a_confirm_email_will_send_when_sign_up()
    {
        Storage::fake();
        Mail::fake();

        $data = raw('User');
        $data['password_confirmation'] = $data['password'];
        $data['resume'] = $file = UploadedFile::fake()->image('resume.pdf');

        $this->post('/register', $data);

        $user = \App\User::first();

        $this->assertDatabaseHas('users', ['confirmed' => 0]);
        $this->assertNotNull($user->confirm_token);

        Mail::assertSent(\App\Mail\ConfirmYourEmail::class);
    }

    public function test_a_user_can_click_the_button_in_the_email_to_finish_sign_up()
    {
        $user = create('User');

        $this->assertDatabaseHas('users', ['id' => 1, 'confirmed' => 0, 'confirm_token' => $user->confirm_token]);

        $this->get('/register/verification?token='.$user->confirm_token);

        $this->assertDatabaseHas('users', ['id' => 1, 'confirmed' => 1, 'confirm_token' => null]);
    }

    public function test_gustes_can_not_add_favorite_job()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $this->post('/job/favorite/toggle/1');
    }

    public function test_a_user_can_toggle_their_favorite_job_list()
    {
        $post = create('Post');

        $this->login(
          $user = create('User')  
        );

        $this->post('/job/favorite/toggle/'.$post->id);
        $this->assertDatabaseHas('favorites', ['user_id' => $user->id, 'post_id' => $post->id]);

        $this->post('/job/favorite/toggle/'.$post->id);
        $this->assertDatabaseMissing('favorites', ['user_id' => $user->id, 'post_id' => $post->id]);
    }

    public function test_user_can_their_favorite_job_lists()
    {
        $this->login( $user = create('User') );
        $post = create('Post');
        create('Favorite', ['user_id' => $user->id, 'post_id' => $post->id]);

        $this->get('/dashboard/favorites')->assertSee($post->title);
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

    public function test_post_title_will_strip_off_extra_spaces()
    {
        $this->login(
            $admin = create('User', ['role' => 'admin'])
        );

        $post = raw('Post', ['user_id' => $admin->id, 'title' => 'A  B', 'chinese_title' => 'A  B标题']);

        $this->post('/admin/post/add', $post);

        $this->assertDatabaseHas('posts', ['title' => 'A B', 'chinese_title' => 'A B标题']);
    }

    public function test_when_adding_a_post_company_email_will_be_updated()
    {
        $this->login(
            $admin = create('User', ['role' => 'admin'])
        );

        $post = raw('Post');

        $post['email'] = 'aaa@email.com';

        $this->post('/admin/post/add', $post);

        $this->assertDatabaseHas($this->companyTable, ['email' => 'aaa@email.com']);
    }

    public function test_admin_can_add_a_post_without_tags()
    {
        $this->login(
            $admin = create('User', ['role' => 'admin'])
        );

        $post = raw('Post');

        $this->post('/admin/post/add', $post);

        $this->assertDatabaseHas('posts', ['description' => $post['description']]);
    }

    public function test_admin_can_add_a_post_with_tags()
    {
        $this->login(
            $admin = create('User', ['role' => 'admin'])
        );

        $tag = create('Tag');

        $post = raw('Post', ['tags' => [$tag->id]]);

        $this->post('/admin/post/add', $post);

        $this->assertDatabaseHas('posts', ['description' => $post['description']]);
        $this->assertDatabaseHas('post_tag', ['post_id' => 1, 'tag_id' => $tag->id]);
    }

    public function test_post_location_will_be_formatted_when_saving_in_the_db()
    {
        $this->login(
            $admin = create('User', ['role' => 'admin'])
        );

        $post = raw('Post', ['user_id' => $admin->id]);
        $post['city'] = 'New York'.time();
        $post['state'] = 'NY';

        $this->post('/admin/post/add', $post);

        $this->assertDatabaseHas('posts', ['location' => $post['city'].','.$post['state']]);
    }

    public function test_admin_can_delete_a_post_along_with_applies_and_pivot_tags()
    {
        $this->login(
            $admin = create('User', ['role' => 'admin'])
        );

        $tag1 = create('Tag');
        $tag2 = create('Tag');

        $post = raw('Post', ['user_id' => $admin->id, 'tags' => [$tag1->id, $tag2->id]]);

        $this->post('/admin/post/add', $post);

        $apply = create('Apply', ['user_id' => 1, 'post_id' => 1]);

        $this->delete('/admin/job/delete/1');

        $this->assertDatabaseMissing('posts', ['id' => 1]);
        $this->assertDatabaseMissing('post_tag', ['post_id' => 1]);
        $this->assertDatabaseMissing('applies', ['post_id' => 1]);
    }

    public function test_admin_can_suspend_a_user_with_a_trash_resume()
    {
        $this->login(
            $admin = create('User', ['role' => 'admin'])
        );

        $user = create('User');

        $this->assertDatabaseHas('users', ['id' => 2, 'role' => 'student', 'suspended' => 0]);

        $this->post('/admin/user/suspend/2');

        $this->assertDatabaseHas('users', ['id' => 2, 'role' => 'student', 'suspended' => 1]);
    }

    public function test_admin_can_release_a_user_after_he_updates_his_resume()
    {
        $this->login(
            $admin = create('User', ['role' => 'admin'])
        );

        $user = create('User', ['suspended' => 1]);

        $this->assertDatabaseHas('users', ['id' => 2, 'suspended' => 1]);

        $this->post('/admin/user/suspend/2');

        $this->assertDatabaseHas('users', ['id' => 2, 'suspended' => 0]);
    }

    public function test_a_suspended_user_can_not_apply()
    {
        $this->login(
            $user = create('User', ['suspended' => 1, 'confirmed' => 1])
        );

        $this->post('/apply')->assertStatus(403)->assertJson(['errors' => ['account' => trans('front.bad resume msg')]]);
    }

    public function test_admin_can_add_a_catagory()
    {
        $this->login(
            $admin = create('User', ['role' => 'admin'])
        );

        $this->post('/admin/catagory/add', $catagory = raw('Catagory'));

        $this->assertDatabaseHas('catagories', $catagory);
    }

    public function test_admin_can_delete_a_category_along_with_posts()
    {
        $this->login(
            $admin = create('User', ['role' => 'admin'])
        );

        $category = create('Catagory');

        $post = create('Post', ['catagory_id' => $category->id]);

        $this->delete('/admin/category/delete/'.$category->id);

        $this->assertDatabaseMissing('catagories', ['id' => $category->id]);
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_admin_can_add_tags()
    {
        $this->login(
            $admin = create('User', ['role' => 'admin'])
        );

        $this->post('/admin/tag/add', $tag = raw('Tag'));

        $this->assertDatabaseHas('tags', $tag);
    }

    public function test_admin_can_delete_a_tag_with_pivots()
    {

        $this->login(
            $admin = create('User', ['role' => 'admin'])
        );

        $tag = create('Tag');
        $post = create('Post');

        $post->tags()->attach($tag->id);

        $this->assertDatabaseHas('post_tag', ['tag_id' => $tag->id, 'post_id' => $post->id]);

        $this->delete('/admin/tag/delete/'.$tag->id);

        $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
        $this->assertDatabaseMissing('post_tag', ['tag_id' => $tag->id, 'post_id' => $post->id]);
    }

    public function test_admin_can_update_tags()
    {
        $this->login(
            $admin = create('User', ['role' => 'admin'])
        );

        $tag = create('Tag', ['name' => 'tag1']);

        $this->post('/admin/tag/update/'.$tag->id, ['name' => 'tag2']);

        $this->assertDatabaseHas('tags', ['name' => 'tag2']);
        $this->assertDatabaseMissing('tags', ['name' => 'tag1']);
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

        $company = create($this->companyModel);

        $this->get('/admin/company')->assertSee($company->name);
    }

    public function test_admin_can_update_a_company()
    {
        $this->login(
            $admin = create('User', ['role' => 'admin'])
        );

        $company = create($this->companyModel, ['name' => 'Google']);

        $this->post('/admin/company/update/'.$company->id, ['name' => 'Apple']);

        $this->assertDatabaseMissing($this->companyTable, ['name' => 'Google']);
        $this->assertDatabaseHas($this->companyTable, ['name' => 'Apple']);
    }

    public function test_normal_admin_cannot_see_all_users()
    {
        $this->login(
            $admin = create('User', ['role' => 'admin'])
        );

        $this->get('/admin/user')->assertRedirect('/admin/jobs');
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

    public function test_superadmin_can_send_applications_to_hr_and_also_email_notify_user()
    {
        Mail::fake();

        $this->login(
            $admin = create('User', ['role' => 'admin', 'email' => 'xfeng@dreamgo.com'])
        );

        create('Apply');

        $this->assertDatabaseHas('applies', ['is_applied' => 0, 'id' => 1]);

        $this->post('/admin/send/applies');

        $this->assertDatabaseHas('applies', ['is_applied' => 1, 'id' => 1]);

        Mail::assertSent(\App\Mail\NotifyHREmail::class);
        Mail::assertSent(\App\Mail\YourJobIsApplied::class);
    }

    public function test_guests_can_not_see_dashboard()
    {
        $this->expectException(
            'Illuminate\Auth\AuthenticationException'
        );

        $this->get('/dashboard/applies');
    }

    public function test_students_can_update_their_account_info()
    {
        $data1 = ['name' => 'name A', 'email'=> 'email1@email.com', 'phone' => '8888888888'];
        $data2 = ['name' => 'name B', 'email'=> 'email2@email.com', 'phone' => '7777777777'];

        $this->login(
            $student = create('User', $data1)
        );

        $this->post('/dashboard/account', $data2);

        $this->assertDatabaseHas('users', $data2)
            ->assertDatabaseMissing('users', $data1);
    }

    public function test_students_can_update_their_password()
    {
        $pass1 = '123123';
        $pass2 = '12341234';
        $data = ['oldPass' => $pass1, 'password' => $pass2, 'password_confirmation' => $pass2];

        $this->login(
            $student = create('User', ['password' => $pass1 = bcrypt($pass1)])
        );

        $this->post('/dashboard/password', $data);

        $this->assertDatabaseMissing('users', ['password' => $pass1]);
    }

    public function test_third_party_login_user_dont_need_an_old_pass_when_update_password()
    {
        $this->login(
            $user = create('User', ['password' => null, 'login_provider' => 'google'])
        );

        $this->assertDatabaseHas('users', ['password' => null, 'name' => $user->name]);
        
        $data = ['password' => '123123', 'password_confirmation' => '123123'];

        $this->post('/dashboard/password', $data);

        $this->assertDatabaseMissing('users', ['password' => null, 'name' => $user->name]);
    }

    public function test_students_can_update_their_resume()
    {
        Storage::fake();

        $this->login(
            $student = create('User', ['resume' => 'resume.pdf'])
        );

        $file = UploadedFile::fake()->image('new-resume.pdf');

        $this->post('/dashboard/resume', ['resume' => $file]);

        $this->assertDatabaseMissing('users', ['resume' => 'resume.pdf']);
        $this->assertDatabaseHas('users', ['resume' => 'resumes/'.$file->hashName()]);
        Storage::disk('local')->assertExists('resumes/' . $file->hashName());
    }

    public function test_admin_can_add_and_update_plans()
    {
        $this->login(
            $admin = create('User', ['role' => 'admin'])
        );

        $plan = raw('Plan', ['name' => 'plan1']);

        $this->post('/admin/plan/add', $plan);

        $this->assertDatabaseHas('plans', $plan);

        $plan = \App\Plan::latest()->first();

        $this->post('/admin/plan/update/'.$plan->id, ['name' => 'plan2']);

        $this->assertDatabaseMissing('plans', ['name' => 'plan1']);
        $this->assertDatabaseHas('plans', ['name' => 'plan2']);
    }

    public function test_admin_can_add_cover_letter()
    {
        $this->login(
            $admin = create('User', ['role' => 'admin'])
        );

        $data = raw('CoverLetter');

        $this->post('/admin/cover-letter/add', $data);

        $this->assertDatabaseHas('cover_letters', $data);
    }

    public function test_admin_can_update_cover_letter()
    {
        $this->login(
            $admin = create('User', ['role' => 'admin'])
        );

        $data = create('CoverLetter');

        $this->post('/admin/cover-letter/update/'.$data->id, ['content' => $data->content.'1231231']);

        $this->assertDatabaseHas('cover_letters', ['content'=> $data->content.'1231231']);
    }

    public function test_admin_can_view_each_blog_in_the_backend()
    {
        $this->login(
          $admin = create("User", ['role' => 'admin'])  
        );

        $blog1 = create('Blog');
        $blog2 = create('Blog');

        $this->get('/admin/blog?id='.$blog1->title)
            ->assertSee($blog1->title)
            ->assertDontSee($blog2->title);
    }

    public function test_admin_can_add_blog()
    {
        $this->login(
            $admin = create('User', ['role' => 'admin'])
        );

        $data = raw('Blog', ['user_id' => $admin->id]);
        
        $this->post('/admin/blog/add', $data);

        $this->assertDatabaseHas('blogs', $data);
    }

    public function test_admin_can_update_blog()
    {
        $this->login(
            $admin = create('User', ['role' => 'admin'])
        );

        $data = create('Blog', ['user_id' => $admin->id]);
        
        $this->post('/admin/blog/update/'.$data->title, ['content' => $data->content.'22']);

        $this->assertDatabaseHas('blogs', ['content' => $data->content.'22']);
    }

    // public function test_admin_can_email_students_after_applying()
    // {
    //     $this->login(
    //         $admin = create('User', ['role' => 'admin'])
    //     );

    //     $apply = create('Apply');

    //     $this->assertDatabaseHas('applies', ['id' => $apply->id, 'is_applied' => 0]);

    //     $this->post('/admin/applied/notify/'.$apply->id);

    //     Mail::fake();
    //     event(new \App\Events\jobIsAppliedForStudent($apply));
    //     Mail::assertSent(\App\Mail\YourJobIsApplied::class);

    //     $this->assertDatabaseHas('applies', ['id' => $apply->id, 'is_applied' => 1]);
    // }

    // public function test_guests_can_not_access_settings_page()
    // {
    //     $this->expectException(
    //         'Illuminate\Auth\AuthenticationException'
    //     );

    //     $this->get('/admin/settings');
    // }

    // public function test_users_can_not_access_settings_page()
    // {
    //     $this->expectException(
    //         'Symfony\Component\HttpKernel\Exception\NotFoundHttpException'
    //     );

    //     $this->login(
    //         $student = create('User')
    //     );

    //     $this->get('/admin/settings');
    // }

    // public function test_user_must_have_a_card_to_buy()
    // {
    //     $this->login(
    //         $user = create('User')
    //     );

    //     $this->post('/dashboard/buy')->assertStatus(402)->assertSee(trans('front.no card'));
    // }

    // public function test_invalid_credit_card_can_not_be_added()
    // {
    //     $this->login(
    //         $user = create('User')
    //     );

    //     $token = $this->createStripeToken(false, true);

    //     $this->post('/dashboard/card', ['token' => $token])->assertStatus(402)->assertSee(trans('front.cc invalid'));

    //     $this->assertNull($user->fresh()->card_brand); // but a stripe customer has been created, $user->stripe_id is not NULL
    // }

    // public function test_user_can_add_credit_card()
    // {
    //     $this->login(
    //         $user = create('User')
    //     );

    //     $token = $this->createStripeToken();

    //     $this->post('/dashboard/card', ['token' => $token])->assertStatus(200);

    //     $this->assertNotNull($user->fresh()->card_brand);
    // }

    // public function test_a_plan_is_required_for_payment()
    // {
    //     $this->login(
    //         $user = create('User')
    //     );

    //     $user->createAsStripeCustomer(
    //         $this->createStripeToken()
    //     );

    //     $this->post('/dashboard/buy')->assertStatus(402)->assertSee(trans('front.plan invalid'));
    // }

    // public function test_dont_give_points_if_payment_fails()
    // {
    //     $this->login(
    //         $user = create('User')
    //     );

    //     $plan = create('Plan');

    //     $user->createAsStripeCustomer(
    //         $this->createStripeToken(true)
    //     );

    //     $this->post('/dashboard/buy', ['plan' => $plan->id])->assertStatus(402)->assertSee(trans('front.payment failed'));

    //     $this->assertEquals(0, $user->points);

    //     $this->assertCount(0, \App\Invoice::all());
    // }

    // public function test_user_can_buy_points()
    // {
    //     $this->login(
    //         $user = create('User')
    //     );

    //     $plan = create('Plan');

    //     $user->createAsStripeCustomer(
    //         $this->createStripeToken()
    //     );

    //     $this->post('/dashboard/buy', ['plan' => $plan->id])->assertStatus(200);

    //     $this->assertEquals($plan->points, $user->points);

    //     $this->assertCount(1, \App\Invoice::all());
    // }

    // private function createStripeToken($paymentFail = false, $badCard = false)
    // {
    //     Stripe::setApiKey('pk_test_SJpv8Y537mDjHvJY5ri7YRir');

    //     $number = "4242424242424242";

    //     if($paymentFail) $number = "4000000000000341";

    //     if($badCard) $number = "4000000000000069";

    //     return Token::create([
    //         "card" => [
    //             "number" => $number,
    //             "exp_month" => 4,
    //             "exp_year" => 2019,
    //             "cvc" => "314"
    //         ]
    //     ])->id;
    // }
}
