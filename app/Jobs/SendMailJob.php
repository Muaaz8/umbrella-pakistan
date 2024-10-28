<?php

namespace App\Jobs;

use DB;
use App\Mail\SendEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $state;
    public $spec;
    public $timeout = 7200; // 2 hours

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($state,$spec)
    {
        $this->state = $state;
        $this->spec = $spec;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $doctors = DB::table('users')
        ->join('doctor_licenses','users.id','doctor_licenses.doctor_id')
        ->where('users.specialization',$this->spec)
        ->where('users.active',1)
        ->where('doctor_licenses.state_id',$this->state)
        ->where('doctor_licenses.is_verified',1)
        ->select('users.*')
        ->get();
        $data = [
            'name' => 'Doctor',
            'subject' => 'To Get Online For Patient',
            'body' => 'A patient is requested for an E-Visit and there is an opportunity to grab that patient for which you have to get online at',
        ];
        foreach($doctors as $doctor)
        {
            try
            {
                $date['name'] = $doctor->name.' '.$doctor->last_name;
                Mail::to($doctor->email)->send(new SendEmail($data));
                Log::info('Alert Email Send');        
            }
            catch(Exception $error){
                Log::info('error'.$error);
            }
        }
    }
}
