<?php

namespace App\Console\Commands;

use App\Mail\ReminderCartMail;
use Carbon\Carbon;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use DateTime;
use DateTimeZone;

class SendCartReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cartreminder:emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command Description';

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
        $cartItems = DB::table('tbl_cart')->where('status','recommended')->get();
        foreach ($cartItems as $item) {
            $nowtime = Carbon::now();
            $time = Carbon::createFromFormat('Y-m-d H:s:i', $item->created_at);
            $nowtime = Carbon::createFromFormat('Y-m-d H:s:i', $nowtime);
            $diff_in_hours = $nowtime->diffInHours($time);
            if($diff_in_hours > 168){
                $user = DB::table('users')->where('id', $item->user_id)->first();
                Log::info($user->email);
                Mail::to($user->email)->send(new ReminderCartMail($user));
            }
        }
    }
}
