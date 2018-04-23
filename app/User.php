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

    protected $append = ['apply_count'];

    public function applies()
    {
        return $this->hasMany(Apply::class);
    }

    public function getApplyCountAttribute()
    {
        return $this->applies()->count();
    }

    public function maskResumeName()
    {
        return substr($this->resume, 30, 25);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\ResetPasswordNotification($token));
    }
}
