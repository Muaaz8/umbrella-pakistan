<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LabOrderDeclinedMail extends Mailable
{
    use Queueable, SerializesModels;
    public $lab_order_approval;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($lab_order_approval)
    {
        $this->lab_order_approval=$lab_order_approval;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Umbrella Health Care Systems')
                    ->markdown('emails.lab_order_declined')
                    ->with('lab_order_approval' ,$this->lab_order_approval );

    }
}
