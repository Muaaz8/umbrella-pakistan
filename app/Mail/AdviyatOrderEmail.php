<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class AdviyatOrderEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $id;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = DB::table('tbl_orders')
            ->join('medicine_order','medicine_order.order_main_id','tbl_orders.order_id')
            ->where('tbl_orders.id',$this->id)
            ->get();
        dd($data);
        return $this->view('emails.adviyatEmail');
    }
}
