<?php

namespace App\Events;

use App\Apply;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class jobIsAppliedForStudent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $apply;

    public function __construct(Apply $apply)
    {
        $this->apply = $apply;
    }
}
