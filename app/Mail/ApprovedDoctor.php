<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApprovedDoctor extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $doctorName;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($doctorName)
    {
        $this->doctorName=$doctorName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.ApprovedDoctor')->with('doctorName',$this->doctorName);
    }

}
