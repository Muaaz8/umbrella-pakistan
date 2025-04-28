<?php

namespace App\Events;

use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DoctorJoinedVideoSession implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $doctor_id='';
    public $patient_id='';
    public $session_id='';
    public $message='';

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($doctor_id,$patient_id,$session_id)
    {
        $this->doctor_id = $doctor_id;
        $this->patient_id = $patient_id;
        $this->session_id = $session_id;
        $this->message = "doctor joined session";
    }
    public function broadcastWith(){
        $doc = User::find($this->doctor_id);
        $doc_name = $doc->name.' '.$doc->last_name;
        $doc_image = \App\Helper::check_bucket_files_url($doc->user_image);
        return ["doctor_id"=>$this->doctor_id,
                "patient_id"=>$this->patient_id,
                "session_id"=>\Crypt::encrypt($this->session_id),
                "doc_name"=>$doc_name,
                "doc_image"=>$doc_image,
                "message"=>$this->message,
                "session"=>$this->session_id,
            ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('session-channel');
    }
}
