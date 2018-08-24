<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $guarded = [];

    public function visaJobs()
    {
        return $this->hasMany(VisaJob::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function getWebsiteAttribute($value)
    {
        return str_start($value, 'http://');
    }

    public function getNameAttribute($value)
    {
        return html_entity_decode($value);
    }

    public function setJobsAttribute($value)
    {
        $this->attributes['jobs'] = preg_replace('/\),*/', '),', $value);
    }
}
