<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LoadPrescribeItemList implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $session_id;
    public $user_id;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($session_id,$user_id)
    {
        $this->session_id = $session_id;
        $this->user_id = $user_id;


    }

    public function broadcastWith(){

        return [
            "session_id"=>$this->session_id,
            "user_id"=>$this->user_id
        ];
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('load-prescribe-item-list');
    }
}
