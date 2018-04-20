<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Apply extends Model
{
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function apply(Post $post)
    {
        $this->user_id = auth()->id();
        $this->post_id = $post->id;
        $this->save();
    }
}
