<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'phone', 'resume', 'password'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function applies()
    {
        return $this->hasMany(Apply::class);
    }
}
