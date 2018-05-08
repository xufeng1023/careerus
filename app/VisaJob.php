<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VisaJob extends Model
{
    protected $fillable = ['company_id', 'year', 'number_of_visa'];

    protected $visible = ['number_of_visa', 'year'];
}
