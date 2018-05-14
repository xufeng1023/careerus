<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded = ['tags'];
    
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

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function link()
    {
        return '/job/'.$this->title.'?i='.$this->identity;
    }

    public function jobType()
    {
        return $this->is_fulltime? 'Full-time' : 'Part-time';
    }

    public function findPost($title, $identity)
    {
        $postTitle = ucwords(implode(' ', explode('-', $title)));

        return $this->where('title', $postTitle)->where('identity', $identity)->firstOrFail();
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = ucwords(trim(preg_replace("/[^A-Za-z0-9]+/", ' ', $value)));
    }

    public function setLocationAttribute($value)
    {
        $this->attributes['location'] = preg_replace("/, /", ',', $value);
    }
}
