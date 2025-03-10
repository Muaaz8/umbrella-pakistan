<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Session;
use App\User;

class SessionsController extends Controller
{
    public function createSession(Request $request){

                $symp = $request->validate([
                    'doc_sp_id' =>  ['required'],
                    'doc_id' =>  ['required', 'string', 'max:255'],
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
                        return response()->json(['navigate' => 'mazaan bank payment gateway', 'data' => $res->formUrl]);

                    }else{
                        return response()->json(['error'=> 'Sorry, we are currently facing server issues. Please try again later.']);
                    }
                }elseif($request->payment_method == "first-visit"){
                    $session = Session::find($session_id);
                    $session->status = "paid";
                    $session->save();
                    return response()->json(['success'=> 'Session created successfully']);
                }else{
                    Session::find($session_id)->delete();
                    return response()->json(['error'=> 'Payment method not found']);
                }
    }
}
