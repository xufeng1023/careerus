<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CoverLetter extends Model
{
    public $timestamps = false;

    protected $table = 'cover_letters';

    protected $fillable = ['content'];
}
