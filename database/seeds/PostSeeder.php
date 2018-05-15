<?php

use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run()
    {
        factory(App\Post::class, 20)->create()->each(function($post) {
            $post->tags()->attach(factory(App\Tag::class)->create());
        });
    }
}
