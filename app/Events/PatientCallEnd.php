<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PatientCallEnd implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $session_id = '';
    public $pat_id = '';
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($session_id,$pat_id)
    {
        $this->session_id = $session_id;
        $this->pat_id = $pat_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('end_patient_call');
    }

    public function broadcastWith()
    {
        return [
            'session_id' => $this->session_id,
            'pat_id' => $this->pat_id
        ];
    }
}
