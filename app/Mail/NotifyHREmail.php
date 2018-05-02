<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyHREmail extends Mailable
{
    use Queueable, SerializesModels;

    public $post;

    public function __construct(\App\Post $post)
    {
        $this->post = $post;
    }

    public function build()
    {
        return $this->from(auth()->user()->email)
                    ->subject($this->post->title)
                    ->replyTo(auth()->user()->email)
                    ->attach(storage_path('app/'.auth()->user()->resume))
                    ->markdown('email.hr');
    }
}
