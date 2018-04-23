<?php

namespace App\Listeners;

use App\Mail\YourJobIsApplied;
use App\Events\jobIsAppliedForStudent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyStudent
{

    public function __construct()
    {
        //
    }

    public function handle(jobIsAppliedForStudent $event)
    {
        \Mail::to($event->apply->user)->send(new YourJobIsApplied($event->apply));
    }
}
