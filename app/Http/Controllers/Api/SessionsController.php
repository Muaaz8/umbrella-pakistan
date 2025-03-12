<?php

namespace App\Http\Controllers\Api;
use App\ActivityLog;
use App\Events\DoctorJoinedVideoSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Events\RealTimeMessage;
use App\Events\updateDoctorWaitingRoom;
use App\Mail\patientEvisitInvitationMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Notification;
use App\Session;
use App\User;
use App\Helper;

class SessionsController extends BaseController
{
    public function createSession(Request $request){

                $symp = $request->validate([
                    'doc_sp_id' =>  ['required'],
                    'doc_id' =>  ['required', 'max:255'],
                    'problem' =>  ['required', 'string', 'max:255'],
                ]);
        
                $patient_id = Auth::user()->id;
                $doc_id = $symp['doc_id'];
        
                $symp_id = 0;
        
                $check_session_already_have = DB::table('sessions')
                    ->where('doctor_id', $symp['doc_id'])
                    ->where('patient_id', $patient_id)
                    ->where('specialization_id', $request->doc_sp_id)
                    ->count();
        
                $session_price = "";
                if ($check_session_already_have > 0) {
                    $session_price_get = User::find($request->doc_id);
                    if ($session_price_get->followup_fee != null) {
                        $session_price = $session_price_get->followup_fee;
                    } else {
                        $session_price = $session_price_get->consultation_fee;
                    }
                } else {
                    $session_price_get = User::find($request->doc_id);
                    $session_price = $session_price_get->consultation_fee;
                }
        
                $timestamp = time();
                $date = date('Y-m-d', $timestamp);
                $permitted_chars = 'abcdefghijklmnopqrstuvwxyz';
                $channelName = substr(str_shuffle($permitted_chars), 0, 8);
                $get_last_session = DB::table('sessions')->where('doctor_id', $doc_id)->where('status', 'invitation sent')->orderBy('id', 'desc')->first();
                $queue = 0;
                if ($get_last_session != null) {
                    $queue = $get_last_session->queue + 1;
                } else {
                    $queue = 1;
                }
        
                $new_session_id = 0;
                $randNumber=rand(11,99);
                $getLastSessionId = DB::table('sessions')->orderBy('id', 'desc')->first();
                if ($getLastSessionId != null) {
                    $new_session_id = $getLastSessionId->session_id + 1+$randNumber;
                } else {
                    $new_session_id = rand(311111,399999);
                }
        
                $session_id = Session::create([
                    'patient_id' =>  $patient_id,
                    'doctor_id' =>  $doc_id,
                    'date' =>  $date,
                    'status' => 'pending',
                    'queue' => $queue,
                    'symptom_id' => '',
                    'remaining_time' => 'full',
                    'channel' => $channelName,
                    'price' => $session_price,
                    'specialization_id' => $request->doc_sp_id,
                    'session_id' => $new_session_id,
                    'validation_status' => "valid",
                ])->id;
                if($request->payment_method == "credit-card"){
                    $session = Session::find($session_id);
                    $data = "Evisit-".$new_session_id."-1";
                    $pay = new \App\Http\Controllers\MeezanPaymentController();
                    $res = $pay->payment($data,($session->price*100));
                    if (isset($res) && $res->errorCode == 0) {
                        return $this->sendResponse(['method'=> 'credit-card', 'url'=> $res->formUrl], 'Payment link generated successfully');

                    }else{
                        return $this->sendError([], 'Payment link not generated');
                    }
                }elseif($request->payment_method == "first-visit"){
                    $session = Session::find($session_id);
                    $session->status = "paid";
                    $session->save();
                    $session_id = $session->id;
                    return $this->sendResponse(['method'=>'first-visit', 'session_id'=> $session_id], 'Session created successfully');
                    
                }else{
                    Session::find($session_id)->delete();
                    return $this->sendError([], 'Payment method not found');
                }
    }

    public function getSession($id){
        $session = Session::select('patient_id', 'doctor_id', 'status', 'queue', 'channel', 'que_message')->find($id);
        if(empty($session)){
            return $this->sendError([], 'Session not found');
        }
        $doctor = User::select('name', 'last_name', 'user_image')->find($session->doctor_id);
        $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);
        $session->doctor = $doctor;
    
        return $this->sendResponse(['session' => $session], 'Session found successfully');
    }

    public function sessionInvite($id){
                $res = Session::where('id', $id)->update(['status' => 'invitation sent', 'invite_time' => now()]);
                if ($res == 1) {
                    $sessionData = Session::where('id', $id)->first();
                    $doc_name = Helper::get_name($sessionData->doctor_id);
                    $pat_name = Helper::get_name($sessionData->patient_id);
                    $notification_id = Notification::create([
                        'user_id' => $sessionData->doctor_id,
                        'text' => 'E-visit Joining Request Send by ' . $pat_name,
                        'session_id' => $id,
                        'type' => 'doctor/patient/queue',
                    ]);
                    $data = [
                        'user_id' => $sessionData->doctor_id,
                        'text' => 'E-visit Joining Request Send by ' . $pat_name,
                        'session_id' => $id,
                        'type' => 'doctor/patient/queue',
                        'received' => 'false',
                        'appoint_id' => 'null',
                        'refill_id' => 'null',
                    ];
        
                    event(new RealTimeMessage($sessionData->doctor_id));
                    event(new updateDoctorWaitingRoom('new_patient_listed'));
                    try {
                        $doctor =  DB::table('users')->where('id', $sessionData->doctor_id)->first();
                        $markDown = [
                            'doc_email' => $doctor->email,
                            'doc_name' => ucwords($doc_name),
                            'pat_name' => ucwords($pat_name),
                        ];
                        Mail::to($doctor->email)->send(new patientEvisitInvitationMail($markDown));
                    } catch (\Exception $e) {
                        Log::error($e);
                    }
        
                    //get doctor all session order by ASC
                    $doc_all_session = Session::where('doctor_id', $sessionData->doctor_id)
                        ->where('status', 'invitation sent')
                        ->orWhere('status', 'doctor joined')
                        ->orderBy('id', 'ASC')
                        ->get();
                    //count doctor session
                    $session_count = count($doc_all_session);
                    if ($session_count > 1) {
                        $mints = 5;
                        foreach ($doc_all_session as $single_session) {
                            Session::where('id', $single_session['id'])->update(['que_message' => 'Your Doctor Will Be Available In Approximately ' . $mints . ' Mints']);
                            $mints += 10;
                        }
                    } else {
                        foreach ($doc_all_session as $single_session) {
                            Session::where('id', $single_session['id'])->update(['que_message' => 'Your Doctor Will Be Available In Approximately 5 Mints']);
                        }
                    }
                    $getData = Session::where('id', $id)->where('patient_id', auth()->user()->id)->first();
                    return $this->sendResponse($getData, 'Session invitation sent successfully');
                } else {
                    return $this->sendError([], 'Session invitation not sent');
                }
    }

    public function waitingPatientJoinCall(Request $request)
    {
        $id = $request->session_id;
        $getSession = Session::where('id', $id)->first();

        $userTypeCheck = User::where('id', $getSession->doctor_id)->first();
        $patUser = User::where('id', $getSession->patient_id)->first();
        if ($patUser->med_record_file != null) {
            $patUser->med_record_file = \App\Helper::get_files_url($patUser->med_record_file);
        }
        // $patAge=User::where('id',$getSession->patient_id)->first();

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
        return response()->json(['message' => 'waiting for patient to join']);
    }
    
}
