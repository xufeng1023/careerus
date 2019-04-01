<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CallHistory extends Model
{
    protected $table = 'call_history';
    public $timestamps = false;
    protected $guarded = [];
}
