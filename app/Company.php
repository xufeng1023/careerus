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

    public function getWebsiteAttribute($value)
    {
        return str_start($value, 'http://');
    }

    public function thisYearVisa()
    {
        $visa = $this->visaJobs()->where('year', date('Y'))->first();
        if($visa) return $visa->number_of_visa;
    }

    public function setJobsAttribute($value)
    {
        $this->attributes['jobs'] = str_replace(")", '),', $value);
    }
}
