<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded = [];
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function catagory()
    {
        return $this->belongsTo(Catagory::class);
    }

    public function findPost($title, $identity)
    {
        $postTitle = ucwords(implode(' ', explode('-', $title)));

        return $this->where('title', $postTitle)->where('identity', $identity)->firstOrFail();
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = ucwords(trim(preg_replace("/[^A-Za-z0-9 ]+/", ' ', $value)));
    }
}
