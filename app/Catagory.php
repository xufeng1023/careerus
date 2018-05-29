<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Catagory extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function remove()
    {
        $this->posts->each->remove();
        $this->delete();
    }
}
