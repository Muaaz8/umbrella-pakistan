<?php

namespace App\Listeners;

use App\Events\DoctorJoinedVideoSession;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class MovePatientToVideoSession
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  DoctorJoinedVideoSession  $event
     * @return void
     */
    public function handle(DoctorJoinedVideoSession $event)
    {
        // dd('event');
        if($event->message=="doctor joined session"){
            dd($event);
        }
        dd('event called1');

    }
}
