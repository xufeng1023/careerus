<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded = ['tags', 'state', 'city', 'email'];

    protected $appends = ['posted_at', 'availibility', 'showTitle', 'excerpt', 'posted_in_hours', 'is_favorited', 'is_applied', 'chinese_job_type'];

    protected $hidden = ['user_id'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d'
    ];

    private $jobTypes = [
        'Full-time' => '全职',
        'Part-time' => '半职',
        'Internship' => '实习'
    ];
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function company()
    {
        return $this->belongsTo(CompanyData::class);
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

    public function getChineseJobTypeAttribute()
    {
        return $this->jobTypes[$this->job_type];
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = ucwords(trim(preg_replace("/[^A-Za-z0-9]+/", ' ', $value)));
    }

    public function setChineseTitleAttribute($value)
    {
        $this->attributes['chinese_title'] = trim(str_replace('/','', stripslashes(preg_replace("/ +/", ' ', $value))));
    }

    public function setLocationAttribute($value)
    {
        $this->attributes['location'] = preg_replace("/, /", ',', $value);
    }

    public function getExcerptAttribute()
    {
        return str_limit(html_entity_decode(strip_tags($this->chinese_description? $this->chinese_description : $this->description)), 110);
    }

    public function getShowTitleAttribute()
    {
        $title = $this->chinese_title ? $this->chinese_title : $this->title;
        return str_limit($title, 20);
    }

    public function getPathAttribute()
    {
        return $this->link();
    }

    public function getPostedInHoursAttribute()
    {
        return $this->created_at->diffInHours(Carbon::now());
    }

    public function favorites()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    public function getIsAppliedAttribute()
    {
        if(!auth()->check()) return false;

        return $this->applies()->where('user_id', auth()->id())->exists();
    }

    public function getIsFavoritedAttribute()
    {
        return $this->favorites()->where('user_id', auth()->id())->exists();
    }

    public function getChineseDateAttribute()
    {
        return $this->created_at->format('Y年m月d日');
    }

    public function getPostedAtAttribute()
    {
        return $this->created_at? $this->created_at->diffforhumans() : '';
    }

    public function getApplyTimesLeftAttribute()
    {
        return cache('job_applies_limit', 10) - $this->applies->count();
    }

    public function getAvailibilityAttribute()
    {
        if($this->applyTimesLeft) return '还有'.$this->applyTimesLeft.'次申请机会';

        return '申请已结束';

        // if(!$this->end_at || $this->end_at < date('Y-m-d')) return '申请已结束';

        // if($this->end_at == date('Y-m-d')) return '申请将于今日截止';

        // return '申请截止于'.Carbon::createFromFormat('Y-m-d', $this->end_at)->diffInDays(Carbon::now()).'天后';
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