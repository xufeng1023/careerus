<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApplyTest extends TestCase
{
    use RefreshDatabase;

    // public function test_guests_cannot_apply()
    // {
    //     $this->expectException('Illuminate\Auth\AuthenticationException');

    //     $this->post('/apply');
    // }

    // public function test_student_apply_for_a_job()
    // {
    //     $this->login(
    //         $admin = create('User', ['role' => 'admin'])
    //     );

    //     $post = raw('Post');
    //     $this->post('/admin/post/add', $post);
    //     $post = \App\Post::first();

    //     $this->login(
    //         $student = create('User', ['role' => 'student'])
    //     );

    //     $this->post('/apply', $post->toArray());
        
    //     $this->assertDatabaseHas('applies', ['user_id' => $student->id, 'post_id' => $post->id]);
    // }
}
