<?php

namespace App\Events;

use App\Models\PrintQueue;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PrintJobQueued
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $Print;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(PrintQueue $Print)
    {
        $this->Print = $Print;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('print-process');
    }
}
