<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmationEmail;
use Illuminate\Support\Facades\Log;



class SendOrderEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendorderemail:emails';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $emails = DB::table('emails_to_be_sent')->where('status','pending')->get();
        if(count($emails)>0){
            foreach($emails as $email){
                $u = User::find($email->reciever_id);
                $userDetails = unserialize($email->markdowndata);
                $order_cart_items = unserialize($email->order_cart_item);
                $checkAtatus=DB::table('emails_to_be_sent')->where('id',$email->id)->first();
                if($checkAtatus->status=='pending')
                {
                    Mail::to($u->email)->send(new OrderConfirmationEmail($order_cart_items, $userDetails));
                    DB::table('emails_to_be_sent')->where('id',$email->id)->update(['status'=>'sent']);
                    Log::info("ok send order mail");
                }
            }
        }

    }
}
