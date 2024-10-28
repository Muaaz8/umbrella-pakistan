<?php

namespace App\Console\Commands;

use App\Events\LoadAppointmentPatientInQueue;
use App\Events\RealTimeMessage;
use App\Events\updateDoctorWaitingRoom;
use App\Mail\ReminderAppointmentDoctorMail;
use App\Mail\ReminderAppointmentPatientMail;
use App\Mail\ReminderTwoEmail;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Nexmo\Laravel\Facade\Nexmo;
use DateTime;
use DateTimeZone;

class SendReminderTwoEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remindertwo:emails';

    /**
     * The console command description.
     *
     *
     * @var string
     */
    protected $description = 'Send Appointment Reminder Email Two';

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

        $current_dateTime = date("Y-m-d H:i:s");
        $appointments=DB::table('appointments')
        ->join('sessions','appointments.id','sessions.appointment_id')
        ->where('sessions.status','paid')
        ->where('appointments.status','pending')
        ->select('appointments.*')->get();
        foreach ($appointments as $appointment) {
            $reminder_dateTime = $appointment->reminder_two;

            $app_date_and_time = $appointment->date . ' ' . $appointment->time;
            $app_date_time = date('Y-m-d H:i:s', strtotime('+5 minutes', strtotime($app_date_and_time)));
            //$appoint_dateTime=Carbon::createFromFormat('Y-m-d H:i:s',$app_date_time,$patient_timeZone->timeZone)->addMinutes(5)->setTimezone('UTC');
            if ($current_dateTime >= $app_date_time) {
                Log::info('current' . $current_dateTime . " = " . 'app time' . $app_date_time);
                Log::info('make-reschedule');
                DB::table('appointments')->where('id', $appointment->id)->update(['appointments.status' => 'make-reschedule']);
            } else {
                if ($reminder_dateTime <= $current_dateTime) {
                    if ($appointment->reminder_two_status != "send") {

                        $doctor_data = DB::table('users')->where('id', $appointment->doctor_id)->first();
                        $patient_data = DB::table('users')->where('id', $appointment->patient_id)->first();
                        $date = new DateTime($app_date_and_time);
                        $date->setTimezone(new DateTimeZone($patient_data->timeZone));
                        $format = array('date' => $date->format('m-d-Y'), 'time' => $date->format('h:i A'), 'datetime' => $date->format('Y-m-d h:i A'));
                        $time = $format['time'];
                        $date = $format['date'];
                        $data = [
                            'doc_name' => ucwords($doctor_data->name),
                            'pat_name' => ucwords($patient_data->name),
                            'time' => $time,
                            'date' => $date,
                            'pat_mail' => $patient_data->email,
                            'doc_mail' => $doctor_data->email,
                        ];
                        //mailgun reminder mail send to patient and doctor
                        // Mail::to('baqir.redecom@gmail.com')->send(new ReminderAppointmentPatientMail($data));
                        // Mail::to('baqir.redecom@gmail.com')->send(new ReminderAppointmentDoctorMail($data));
                        try
                        {
                            Mail::to($patient_data->email)->send(new ReminderAppointmentPatientMail($data));
                            Mail::to($doctor_data->email)->send(new ReminderAppointmentDoctorMail($data));
                            Log::info('send reminder email');
                        }
                        catch(Exception $error){
                            Log::info($error);
                        }
                        
                        DB::table('sessions')->where('appointment_id', $appointment->id)->update(['status' => 'invitation sent', 'que_message' => 'Your doctor will join you in next few mints ', 'join_enable' => '1']);
                        DB::table('appointments')->where('id', $appointment->id)->update(['reminder_two_status' => 'send']);
                        event(new LoadAppointmentPatientInQueue($doctor_data->id, $appointment->id));
                        // event(new updateDoctorWaitingRoom('new_patient_listed'));
                    }
                } else {
                    Log::info('reminder no available');
                }
            }
        }
        Log::info('2 reminder running');
    }
}
