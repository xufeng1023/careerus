<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded = ['tags', 'state', 'city'];

    protected $appends = ['posted_at'];

    protected $hidden = ['user_id'];
    
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

    public function applies()
    {
        return $this->hasMany(Apply::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function link()
    {
        return '/job/'.($this->chinese_title? $this->chinese_title : str_slug($this->title)).'?i='.$this->identity;
    }

    public function jobType()
    {
        return $this->is_fulltime? 'Full-time' : 'Part-time';
    }

    public function remove()
    {
        $this->applies->each->delete();
        $this->tags()->detach();
        $this->delete();
    }

    public function cleanedDescription($zh = '')
    {
        return strip_tags($zh? $this->chinese_description : $this->description, 
            "<div><span><pre><p><br><hr><hgroup><h1><h2><h3><h4><h5><h6>
            <ul><ol><li><dl><dt><dd><strong><em><b><i><u><img><abbr><address>
            <blockquote><label><caption><table><tbody><td><tfoot><th><thead><tr>"
        );
    }

    public function applyTimes()
    {
        return $this->applies->filter(function($val) {
            return $val->created_at->format('Y-m-d') == now()->format('Y-m-d');
        })->count();
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

    public function setChineseTitleAttribute($value)
    {
        $this->attributes['chinese_title'] = trim(str_replace('/','', stripslashes($value)));
    }

    public function setLocationAttribute($value)
    {
        $this->attributes['location'] = preg_replace("/, /", ',', $value);
    }

    public function getPostedAtAttribute()
    {
        return $this->created_at->diffforhumans();
    }

    public function getWechatDescriptionAttribute()
    {   
        $description = $this->chinese_description ?: $this->description;

        $description = str_replace('<br>', '<div></div>', $description);

        $ary = explode('</div>', $description);

        $ary = array_map(function($value) {
            return str_replace('&nbsp;', ' ', strip_tags($value));
        }, $ary);

        return array_filter($ary, function($value) {
            return !empty($value);
        });
    }
}
