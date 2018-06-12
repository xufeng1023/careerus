<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GreenCardInventory extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    protected $dates = ['updated_at'];
}
