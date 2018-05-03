<?php

namespace App\Mail;

use App\{Post, CoverLetter};
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyHREmail extends Mailable
{
    use Queueable, SerializesModels;

    public $post, $coverLetter;

    public function __construct(Post $post)
    {
        $this->post = $post;

        $this->getCoverLetter();
    }

    public function build()
    {
        return $this->from(auth()->user()->email, 'Career Us')
                    ->subject($this->post->title)
                    ->replyTo(auth()->user()->email)
                    ->attach(
                        storage_path('app/'.auth()->user()->resume),
                        ['as' => 'resume.'.\File::extension(auth()->user()->resume)]
                    )
                    ->markdown('email.hr');
    }

    private function getCoverLetter()
    {
        $letters = CoverLetter::all();

        if($letters->count()) $this->coverLetter = $letters->random();
    }
}
