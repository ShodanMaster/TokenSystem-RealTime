<?php

namespace App\Events\Counter;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TokenReceived implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $lastFiveData;

    public function __construct($lastFiveData)
    {
        $this->lastFiveData = $lastFiveData;
    }


    public function broadcastOn()
    {
        return [
            new PrivateChannel('admin.notifications'),
            new Channel('index.notifications'),
        ];
    }

    // Custom event name
    public function broadcastAs()
    {
        return 'token.received';
    }

    // Structure the data
    public function broadcastWith()
    {
        return [
            'lastFiveData' => $this->lastFiveData,
        ];
    }
}
