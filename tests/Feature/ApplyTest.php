<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
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

        $this->post('/applyRegister', $data);

        $user = \App\User::latest()->first();

        $this->assertDatabaseHas('applies', ['user_id' => $user->id, 'post_id' => $post->id]);
        $this->assertDatabaseHas('users', ['id' => $user->id, 'resume' => 'resumes/'.$file->hashName()]);
        Storage::disk('local')->assertExists('resumes/' . $file->hashName());
    }

    public function test_before_finish_5_times_coupon_students_can_apply_free()
    {
        $this->login(
            $student = create('User')
        );
        
        $post = create('Post');

        $data['identity'] = $post->identity;
        $data['job'] = $post->title;

        $this->post('/apply', $data);

        $this->assertDatabaseHas('applies', ['user_id' => $student->id, 'post_id' => $post->id]);
    }

    public function test_after_5_times_coupon_students_can_not_apply_if_not_enough_points()
    {
        $this->login(
            $student = create('User')
        );
        
        $post = create('Post');

        $data['identity'] = $post->identity;
        $data['job'] = $post->title;

        for($i = 1; $i < 6; $i++) {
            $this->post('/apply', $data);
        }

        $this->post('/apply', $data)->assertSee(trans('front.no points'));

        $this->assertEquals(5, \App\Apply::count());
    }

    public function test_after_5_times_coupon_a_successful_apply_will_cost_points()
    {
        $this->login(
            $student = create('User', ['points' => 100])
        );
        
        $post = create('Post');

        $data['identity'] = $post->identity;
        $data['job'] = $post->title;

        for($i = 1; $i < 6; $i++) {
            $this->post('/apply', $data);
        }

        $this->post('/apply', $data);

        $this->assertEquals(6, \App\Apply::count());
        $this->assertEquals(80, auth()->user()->points);
    }
}
