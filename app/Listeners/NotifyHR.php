<?php

namespace App\Listeners;

use App\Events\StudentAppliedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyHR
{
    public function __construct()
    {
        //
    }

    public function handle(StudentAppliedEvent $event)
    {
        \Mail::to($event->post->company->email)->send(new \App\Mail\NotifyHREmail($event->post));
    }
}
