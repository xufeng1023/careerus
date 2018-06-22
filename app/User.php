<?php

namespace App;

use Laravel\Cashier\Billable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, Billable;

    protected $fillable = [
        'name', 'email', 'phone', 'resume', 'password', 'role'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $append = ['apply_count'];

    public function applies()
    {
        return $this->hasMany(Apply::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function isApplied($post_id)
    {
        return $this->applies()->where('post_id', $post_id)->first();
    }

    public function cardLabel()
    {
        return $this->stripe_id? $this->card_brand.'-'.$this->card_last_four : trans('front.no card');
    }

    public function getApplyCountAttribute()
    {
        return $this->applies->filter(function($val) {
            return $val->created_at->format('Y-m-d') == now()->format('Y-m-d');
        })->count();
    }

    public function maskResumeName()
    {
        return substr($this->resume, 30, 25);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\ResetPasswordNotification($token));
    }

    public function isMaster()
    {
        return in_array($this->email, [
            'xfeng@dreamgo.com',
            'etsui@dreamgo.com'
        ]);
    }
}
