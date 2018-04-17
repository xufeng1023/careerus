<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Apply extends Model
{
    public function apply(Post $post)
    {
        $this->user_id = auth()->id();
        $this->post_id = $post->id;
        $this->save();
    }
}
