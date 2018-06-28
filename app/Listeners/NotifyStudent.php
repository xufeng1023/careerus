<?php

namespace App\Listeners;

use App\Events\StudentAppliedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyStudent
{

    public function __construct()
    {
        //
    }

    public function handle(StudentAppliedEvent $event)
    {
        \Mail::to(auth()->user())->send(new \App\Mail\YourJobIsApplied($event->post));
    }
}
