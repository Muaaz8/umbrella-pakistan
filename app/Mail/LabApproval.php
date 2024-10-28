<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LabApproval extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    // public $data;
    public $orderDetail;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($orderDetail)
    {
        // $this->data=$data;
        $this->orderDetail=$orderDetail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.ApprovalMail')->with(['data'=>$this->orderDetail]);
    }
}
