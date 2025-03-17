<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use App\ActivityLog;

class patientJoinCall implements ShouldBroadcast
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
        $this->message = "patient joined session";

        $doctor = DB::table('users')->where('id',$doctor_id)->first();
        // dd($doctor);

        ActivityLog::create([
            'activity' => 'joined session with Dr.' . $doctor->name . " " . $doctor->last_name,
            'type' => 'session start',
            'user_id' => $patient_id,
            'user_type' => 'patient',
            'party_involved' => $doctor_id
        ]);
    }
    public function broadcastWith(){
        return ["doctor_id"=>$this->doctor_id,
                "patient_id"=>$this->patient_id,
                "session_id"=>\Crypt::encrypt($this->session_id),
                "message"=>$this->message,
                "id"=>$this->session_id
            ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('patient_join_call');
    }
}
