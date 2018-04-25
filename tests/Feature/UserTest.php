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

    public function test_admin_can_add_a_post()
    {
        $this->login(
            $admin = create('User', ['role' => 'admin'])
        );

        $post = raw('Post', ['user_id' => $admin->id, 'title' => 'a--bb']);

        $this->post('/admin/post/add', $post);

        $post['title'] = ucwords('a bb');

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

    public function test_admin_can_email_students_after_applying()
    {
        $this->login(
            $admin = create('User', ['role' => 'admin'])
        );

        $apply = create('Apply');

        $this->assertDatabaseHas('applies', ['id' => $apply->id, 'is_applied' => 0]);

        $this->post('/admin/applied/notify/'.$apply->id);

        Mail::fake();
        event(new \App\Events\jobIsAppliedForStudent($apply));
        Mail::assertSent(\App\Mail\YourJobIsApplied::class);

        $this->assertDatabaseHas('applies', ['id' => $apply->id, 'is_applied' => 1]);
    }

    public function test_guests_can_not_access_settings_page()
    {
        $this->expectException(
            'Illuminate\Auth\AuthenticationException'
        );

        $this->get('/admin/settings');
    }

    public function test_users_can_not_access_settings_page()
    {
        $this->expectException(
            'Symfony\Component\HttpKernel\Exception\NotFoundHttpException'
        );

        $this->login(
            $student = create('User')
        );

        $this->get('/admin/settings');
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

    public function test_user_must_have_a_card_to_buy()
    {
        $this->login(
            $user = create('User')
        );

        $this->post('/dashboard/buy')->assertStatus(402)->assertSee(trans('front.no card'));
    }

    public function test_invalid_credit_card_can_not_be_added()
    {
        $this->login(
            $user = create('User')
        );

        $token = $this->createStripeToken(false, true);

        $this->post('/dashboard/card', ['token' => $token])->assertStatus(402)->assertSee(trans('front.cc invalid'));

        $this->assertNull($user->fresh()->card_brand); // but a stripe customer has been created, $user->stripe_id is not NULL
    }

    public function test_user_can_add_credit_card()
    {
        $this->login(
            $user = create('User')
        );

        $token = $this->createStripeToken();

        $this->post('/dashboard/card', ['token' => $token])->assertStatus(200);

        $this->assertNotNull($user->fresh()->card_brand);
    }

    public function test_a_plan_is_required_for_payment()
    {
        $this->login(
            $user = create('User')
        );

        $user->createAsStripeCustomer(
            $this->createStripeToken()
        );

        $this->post('/dashboard/buy')->assertStatus(402)->assertSee(trans('front.plan invalid'));
    }

    public function test_dont_give_points_if_payment_fails()
    {
        $this->login(
            $user = create('User')
        );

        $plan = create('Plan');

        $user->createAsStripeCustomer(
            $this->createStripeToken(true)
        );

        $this->post('/dashboard/buy', ['plan' => $plan->id])->assertStatus(402)->assertSee(trans('front.payment failed'));

        $this->assertEquals(0, $user->points);

        $this->assertCount(0, \App\Invoice::all());
    }

    public function test_user_can_buy_points()
    {
        $this->login(
            $user = create('User')
        );

        $plan = create('Plan');

        $user->createAsStripeCustomer(
            $this->createStripeToken()
        );

        $this->post('/dashboard/buy', ['plan' => $plan->id])->assertStatus(200);

        $this->assertEquals($plan->points, $user->points);

        $this->assertCount(1, \App\Invoice::all());
    }

    private function createStripeToken($paymentFail = false, $badCard = false)
    {
        Stripe::setApiKey('pk_test_SJpv8Y537mDjHvJY5ri7YRir');

        $number = "4242424242424242";

        if($paymentFail) $number = "4000000000000341";

        if($badCard) $number = "4000000000000069";

        return Token::create([
            "card" => [
                "number" => $number,
                "exp_month" => 4,
                "exp_year" => 2019,
                "cvc" => "314"
            ]
        ])->id;
    }
}
