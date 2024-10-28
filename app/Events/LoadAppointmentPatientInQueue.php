<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use DB;

class LoadAppointmentPatientInQueue implements ShouldBroadcast
{

    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $doctor_id;
    public $appointment_id;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($doctor_id,$appointment_id)
    {
        $this->doctor_id = $doctor_id;
        $this->appointment_id = $appointment_id;

    }
    public function broadcastWith(){
        $ses = DB::table('sessions')->where('appointment_id',$this->appointment_id)->first();
        return [
            "doctor_id"=>$this->doctor_id,
            "appointment_id"=>$this->appointment_id,
            "patient_id"=>$ses->patient_id,
            "session_id"=>\Crypt::encrypt($ses->id),
        ];
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('load_appointment_patient_in_queue');
    }
}
