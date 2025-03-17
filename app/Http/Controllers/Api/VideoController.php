<?php

namespace App\Http\Controllers\Api;

use App\ActivityLog;
use App\Appointment;
use App\Events\DoctorJoinedVideoSession;
use App\Events\patientEndCall;
use App\Events\updateQuePatient;
use App\Http\Controllers\Controller;
use App\Mail\ReferDoctorToDoctorMail;
use App\Mail\ReferDoctorToPatientMail;
use App\User;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;


class VideoController extends BaseController
{
    public function waitingPatientJoinCall($id)
    {
        $getSession = Session::where('id', $id)->first();

        $userTypeCheck = User::where('id', $getSession->doctor_id)->first();
        $patUser = User::where('id', $getSession->patient_id)->first();
        if ($patUser->med_record_file != null) {
            $patUser->med_record_file = \App\Helper::get_files_url($patUser->med_record_file);
        }
        if ($userTypeCheck->user_type == 'doctor') {
            Session::where('id', $id)->update(['status' => 'doctor joined']);
            $queue = Session::where('id', $id)->first();
            if ($queue->queue < 2) {
                Session::where('id', $id)->update(['response_time' => now()]);
            }

            event(new DoctorJoinedVideoSession($getSession->doctor_id, $getSession->patient_id, $id));
            ActivityLog::create([
                'activity' => 'joined session with ' . $patUser->name . " " . $patUser->last_name,
                'type' => 'session start',
                'user_id' => $getSession->doctor_id,
                'user_type' => 'doctor',
                'identity' => $id,
                'party_involved' => $getSession->patient_id,
            ]);
        }
        return $this->sendResponse(['session_id'=>$getSession->id , 'action'=> 'VideoScreen'], 'Session joined successfully');
    }

    public function doctor_end_session($id)
    {

        $end_time = Carbon::now()->addMinutes(15)->format('Y-m-d H:i:s');
        Session::where('id', $id)->update([
            'join_enable' => '0',
            'status' => 'ended',
            'end_time' => $end_time
        ]);

        $sessionData = Session::where('id', $id)->first();

        $patientDetail = User::where('id', $sessionData->patient_id)->first();
        $doctorDetail = User::where('id', $sessionData->doctor_id)->first();
        if ($sessionData->appointment_id != null) {
            Appointment::where('id', $sessionData->appointment_id)->update([
                'status' => 'complete'
            ]);
        }

        $allSession = Session::where('doctor_id', $sessionData->doctor_id)->where('status', 'invitation sent')->get();
        $session_count = count($allSession);
        if ($session_count > 1) {
            $mints = 5;
            foreach ($allSession as $single_session) {
                Session::where('id', $single_session['id'])->update(['que_message' => 'Your Doctor Will Be Available In Approximately ' . $mints . ' Mints']);
                $mints += 10;
            }
        } else {
            foreach ($allSession as $single_session) {
                Session::where('id', $single_session['id'])->update(['que_message' => 'Your Doctor Will Be Available In Approximately 5 Mints']);
            }
        }

        ActivityLog::create([
            'activity' => 'ended session with ' . $patientDetail->name . " " . $patientDetail->last_name,
            'type' => 'session end',
            'user_id' => $sessionData->doctor_id,
            'user_type' => 'doctor',
            'identity' => $id,
            'party_involved' => $sessionData->patient_id,
        ]);

        ActivityLog::create([
            'activity' => 'ended session with Dr.' . $doctorDetail->name . " " . $doctorDetail->last_name,
            'type' => 'session end',
            'user_id' => $patientDetail->id,
            'user_type' => 'doctor',
            'identity' => $id,
            'party_involved' => $sessionData->doctor_id,
        ]);


        $referData = DB::table('referals')->where('session_id', $id)->first();
        if ($referData != null) {
            $ref_from_user = DB::table('users')->where('id', $referData->doctor_id)->first();
            $ref_to_user = DB::table('users')->where('id', $referData->sp_doctor_id)->first();
            $patient_user = DB::table('users')->where('id', $referData->patient_id)->first();
            try {
                $data = [
                    'pat_name' => $patient_user->name,
                    'ref_from_name' => $ref_from_user->name,
                    'ref_to_name' => $ref_to_user->name,
                    'ref_to_email' => $ref_to_user->email,
                ];
                $data1 = [
                    'pat_name' => $patient_user->name,
                    'ref_from_name' => $ref_from_user->name,
                    'ref_to_name' => $ref_to_user->name,
                    'pat_email' => $patient_user->email,
                ];
                Mail::to($patient_user->email)->send(new ReferDoctorToPatientMail($data1));
                Mail::to($ref_to_user->email)->send(new ReferDoctorToDoctorMail($data));
            } catch (\Exception $e) {
                Log::error($e);
            }
        }

        event(new patientEndCall($id));

        $firebase_ses = Session::where('id', $id)->first();
        event(new updateQuePatient('update patient que'));
        Log::info('run que update event');
        return "done";
    }
}
