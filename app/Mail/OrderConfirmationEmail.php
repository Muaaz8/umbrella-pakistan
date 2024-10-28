<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderConfirmationEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $data;
    public $userDetails;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data,$userDetails)
    {
        $this->data=$data;
        $this->userDetails=$userDetails;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.OrderConfirmationEmail')->with(['data'=>$this->data,'userDetails'=>$this->userDetails]);
    }
}
