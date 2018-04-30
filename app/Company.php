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
}
