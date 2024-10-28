<?php

namespace App\Console\Commands;
use App\User;
use Illuminate\Console\Command;
class DoctorOnline extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:doctor';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command use to check docotr online or not';

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
        $timestamp = date("d-m-Y h:i:s a");
        $date = \Carbon\Carbon::createFromFormat('d-m-Y h:i:s a', $timestamp, 'UTC')->subMinutes(20)->setTimezone('UTC');
        $doctors=User::where('user_type','doctor')->where('status','online')->where('last_activity','<',$date)->where('active','1')->get();
        if($doctors!=null)
        {
            foreach($doctors as $doctor)
            {
                User::where('id',$doctor->id)->update(['status'=>'offline']);
                // event(new CheckDoctorStatus($doctor->id));
            }
        }
        // $doctors=User::where('user_type','doctor')
        //             ->where('last_activity','<',$date)
        //             ->orwhere('last_activity',null)
        //             ->where('active','1')
        //             ->update(['status'=>'offline'])->id;
        // Log::info($doctors);
        // event(new CheckDoctorStatus('run'));
    }
}
