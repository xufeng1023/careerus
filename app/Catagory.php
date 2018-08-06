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

    public function orderByMostUsed()
    {
        $categories = static::all()->sortByDesc(function ($category, $key) {
            return count($category['posts']);
        })->pluck('name');

        return $categories->filter(function($val) {
            return $val != '其他';
        });
    }
}
