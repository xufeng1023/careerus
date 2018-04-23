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

    public $apply;

    public function __construct(Apply $apply)
    {
        $this->apply = $apply;
    }

    public function build()
    {
        return $this->markdown('email.applied')
                    ->subject(trans('admin.applied email subject'));
    }
}
