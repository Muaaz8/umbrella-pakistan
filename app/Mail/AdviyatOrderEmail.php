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
        $data = DB::table('tbl_cart')
            ->join('tbl_products', 'tbl_products.id','tbl_cart.product_id')
            ->where('tbl_cart.user_id', auth()->user()->id)
            ->where('show_product', '1')
            ->where('status', 'recommended')
            ->select('tbl_cart.*','tbl_products.generic','tbl_products.is_single')
            ->get();
        return $this->view('emails.adviyatEmail',compact('data'));
    }
}
