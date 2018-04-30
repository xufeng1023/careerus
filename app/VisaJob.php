<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VisaJob extends Model
{
    protected $fillable = ['company_id', 'year', 'number_of_visa', 'jobs'];

    protected $visible = ['jobs', 'number_of_visa', 'year'];
}
