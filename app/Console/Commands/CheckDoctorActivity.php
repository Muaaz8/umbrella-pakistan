<?php

namespace App\Console\Commands;

use App\Events\loadOnlineDoctor;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Log;

class CheckDoctorActivity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:checkactivity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command use to check doctor,s last activity';

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
        $timestamp = date("Y-m-d H:i:s");
        $doctors = DB::table('user_auth_activity')->where('status', '1')->get();
        foreach ($doctors as $doc) {
            $url = $doc->url;
            Log::info($doc->expired_time . " " . $timestamp);
            if ($timestamp > $doc->expired_time) {
                if ($doc->url != env('APP_URL') . '/video') {
                    DB::table('users')->where('id', $doc->user_id)->update(['status' => 'offline']);
                    DB::table('user_auth_activity')->where('id', $doc->id)->update(['status' => '0']);
                    DB::table('sessions')->where('doctor_id')->where('status','invitation sent')
                    ->orwhere('status','doctor joined')->update(['status' => 'paid']);
                    event(new loadOnlineDoctor('run'));
                    try {
                        $data = DB::table('users')->where('id',$doc->id)->select('id','status')->first();
                        if($data->status == "online"){
                            $data->status = "offline";
                        }else{
                            $data->status = "online";
                        }
                        $data->id = (string)$doc->id;
                        $data->received = "false";
                        // \App\Helper::firebase($doc->id,'loadOnlineDoctor',$doc->id,$data);
                    } catch (\Throwable $th) {
                        throw $th;
                    }
                    Log::info('done');
                }
            }
        }
    }
}
