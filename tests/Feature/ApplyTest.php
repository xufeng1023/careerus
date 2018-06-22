<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApplyTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_can_apply_and_register_all_together()
    {
        Storage::fake();
        
        $post = create('Post');

        $data = raw('User');
        $data['password_confirmation'] = $data['password'];
        $data['identity'] = $post->identity;
        $data['job'] = $post->title;
        $data['resume'] = $file = UploadedFile::fake()->image('resume.pdf');

        $this->post('/applyRegister', $data)->assertSee('/dashboard/applies');
        $this->assert_hr_gets_email_when_student_apply($post);

        $user = \App\User::latest()->first();

        $this->assertDatabaseHas('applies', ['user_id' => $user->id, 'post_id' => $post->id]);
        $this->assertDatabaseHas('users', ['id' => $user->id, 'resume' => 'resumes/'.$file->hashName()]);
        
        Storage::disk('local')->assertExists('resumes/' . $file->hashName());
    }

    public function test_students_can_apply()
    {
        Storage::fake();

        $file = UploadedFile::fake()->image('resume.pdf');

        $this->login(
            $student = create('User', ['resume' => 'resumes/' . $file->hashName()])
        );
        
        $post = create('Post');

        $data['identity'] = $post->identity;
        $data['job'] = $post->title;

        $this->post('/apply', $data)->assertSee('/dashboard/applies');

        $this->assert_hr_gets_email_when_student_apply($post);

        $this->assertDatabaseHas('applies', ['user_id' => $student->id, 'post_id' => $post->id]);
    }

    public function test_apply_for_everyone_is_limited_in_a_day()
    {
        Storage::fake();

        $limit = cache('apply_times_a_day', 5);

        $file = UploadedFile::fake()->image('resume.pdf');

        $this->login(
            $user = create('User', ['resume' => 'resumes/' . $file->hashName()])
        );

        for($i = 1; $i <= $limit; $i++) {
            create('Apply', ['user_id' => $user->id]);
        }

        $this->assertEquals($limit, $user->apply_count);

        $post = create('Post');

        $data['identity'] = $post->identity;
        $data['job'] = $post->title;

        $this->post('/apply', $data)->assertStatus(422);
        $this->assertEquals($limit, $user->fresh()->apply_count);
    }

    public function test_each_job_has_limited_apply_times()
    {
        Storage::fake();

        $job = create('Post');

        $limit = cache('job_applies_a_day', 5);

        $file = UploadedFile::fake()->image('resume.pdf');

        $this->login(
            $user = create('User', ['resume' => 'resumes/' . $file->hashName()])
        );

        for($i = 1; $i <= $limit; $i++) {
            create('Apply', ['user_id' => create('User'), 'post_id' => $job->id]);
        }

        $this->assertEquals($limit, $job->applyTimes());

        $data['identity'] = $job->identity;
        $data['job'] = $job->title;

        $this->post('/apply', $data)->assertStatus(422);
        $this->assertEquals($limit, $job->fresh()->applyTimes());
    }

    // public function test_after_5_times_coupon_students_can_not_apply_if_not_enough_points()
    // {
    //     $this->login(
    //         $student = create('User')
    //     );
        
    //     $post = create('Post');

    //     $data['identity'] = $post->identity;
    //     $data['job'] = $post->title;

    //     for($i = 1; $i < 6; $i++) {
    //         $this->post('/apply', $data);
    //     }

    //     $this->post('/apply', $data)->assertStatus(422);

    //     $this->assertEquals(5, \App\Apply::count());
    // }

    // public function test_after_5_times_coupon_a_successful_apply_will_cost_points()
    // {
    //     $this->login(
    //         $student = create('User', ['points' => 100])
    //     );
        
    //     $post = create('Post');

    //     $data['identity'] = $post->identity;
    //     $data['job'] = $post->title;

    //     for($i = 1; $i < 6; $i++) {
    //         $this->post('/apply', $data);
    //     }

    //     $this->post('/apply', $data);

    //     $this->assertEquals(6, \App\Apply::count());
    //     $this->assertEquals(80, auth()->user()->points);
    // }

    public function test_students_can_see_their_applies_on_dashboard()
    {
        $this->login(
            $student = create('User', ['points' => 100])
        );
        
        $post = create('Post');

        $data['identity'] = $post->identity;
        $data['job'] = $post->title;

        $this->post('/apply', $data);
        $this->get('/dashboard/applies')->assertSee($post->title);
    }

    private function assert_hr_gets_email_when_student_apply($post)
    {
        Mail::fake();
        event(new \App\Events\StudentAppliedEvent($post));
        Mail::assertSent(\App\Mail\NotifyHREmail::class);
    }
}
