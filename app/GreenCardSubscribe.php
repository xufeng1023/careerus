<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GreenCardSubscribe extends Model
{
    protected $fillable = ['url', 'email'];

    protected $table = 'green_card_subscribe';
}
