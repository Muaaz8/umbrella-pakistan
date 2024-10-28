<?php

namespace App\Console\Commands;

use App\Mail\ReminderAppointmentPatientMail;
use Carbon\Carbon;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use DateTime;
use DateTimeZone;

class SendReminderOneEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminderone:emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Appointment Reminder Email One';

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
        $current_dateTime=date("Y-m-d H:i:s");
        $appointments=DB::table('appointments')
        ->join('sessions','appointments.id','sessions.appointment_id')
        ->where('sessions.status','paid')
        ->where('appointments.status','pending')
        ->select('appointments.*')->get();
        foreach($appointments as $appointment)
        {
            if($appointment->reminder_one<=$current_dateTime)
            {
                if($appointment->reminder_one_status!="send")
                {
                    $doctor_data=DB::table('users')->where('id', $appointment->doctor_id)->first();
                    $patient_data=DB::table('users')->where('id', $appointment->patient_id)->first();
                    $app_date_time = $appointment->date.' '.$appointment->time;
                    $date = new DateTime($app_date_time);
                    $date->setTimezone(new DateTimeZone($patient_data->timeZone));
                    $format = array('date' => $date->format('m-d-Y'), 'time' => $date->format('h:i A'), 'datetime' => $date->format('Y-m-d h:i A'));
                    $time = $format['time'];
                    $date = $format['date'];
                    $markDownData=[
                        'doc_name'=>ucwords($doctor_data->name),
                        'pat_name'=>ucwords($patient_data->name),
                        'time'=>$time,
                        'date'=>$date,
                        'pat_mail'=>$patient_data->email,
                    ];
                    //mailgun reminder mail send to patient and doctor
                    // Mail::to('baqir.redecom@gmail.com')->send(new ReminderAppointmentPatientMail($markDownData));
                    Mail::to($patient_data->email)->send(new ReminderAppointmentPatientMail($markDownData));
                    Log::info('send reminder email');
                    DB::table('appointments')->where('id', $appointment->id)->update(['reminder_one_status' => 'send']);
                }
            }
        }
        Log::info('1 reminder running');
    }
}
