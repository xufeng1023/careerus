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

    public $post, $coverLetter, $user;

    public function __construct($user, $jobTitle)
    {
        $this->user = $user;

        $this->post = $jobTitle;

       // $this->getCoverLetter();
    }

    public function build()
    {
        $this->subject($this->post);

        //$this->replyTo(auth()->user()->email);
        
        foreach($this->user as $u) {
            $this->attach(
                storage_path('app/'.$u->resume),
                ['as' => $u->name.'.'.\File::extension($u->resume)]
            );
        }

        return $this->markdown('email.hr');
    }

    private function getCoverLetter()
    {
        $letters = CoverLetter::all();

        if($letters->count()) $this->coverLetter = $letters->random();
    }
}
