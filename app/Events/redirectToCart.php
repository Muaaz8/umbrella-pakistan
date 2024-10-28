<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class redirectToCart implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $session_id = '';

    public function __construct($session_id)
    {
        $this->session_id = $session_id;
    }
    public function broadcastWith()
    {
        return [
            "session_id" => $this->session_id,
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('patient_redirect_to_cart');
    }
}
