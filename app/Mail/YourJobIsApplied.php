<?php

namespace App\Mail;

use App\Apply;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class YourJobIsApplied extends Mailable
{
    use Queueable, SerializesModels;

    public $post;
    public $user;

    public function __construct($user, $post)
    {
        $this->post = $post;
        $this->user = $user;
    }

    public function build()
    {
        return $this->markdown('email.applied')
                    ->subject(trans('admin.applied email subject'));
    }
}
