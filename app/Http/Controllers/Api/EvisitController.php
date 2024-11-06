<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use App\User;
use App\Symptom;
use App\Specialization;
use App\AgoraAynalatics;
use App\Appointment;
use App\Models\TblTransaction;
use App\Helper;
use App\Session;
use App\State;
use App\City;
use App\MedicalProfile;
use App\Prescription;
use App\Models\AllProducts;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\RefillRequest;
use App\Cart;
use App\Events\CheckDoctorStatus;
use App\Events\updateDoctorWaitingRoom;
use App\Events\loadOnlineDoctor;
use App\Events\RealTimeMessage;
use App\Events\updateQuePatient;
use App\Referal;
use App\Notification;
use Illuminate\Support\Facades\Mail;
use App\Mail\EvisitBooking;
use App\Mail\PasswordReset;
use App\Mail\EvisitBookMail;
use App\Mail\ReferDoctorToDoctorMail;
use App\Mail\RequestSessionPatientMail;
use App\Mail\patientEvisitInvitationMail;
use App\Mail\ReferDoctorToPatientMail;
use App\Mail\RefillCompleteMailToPatient;
use App\Mail\RefillRequestToDoctorMail;
use App\QuestDataAOE;
use App\QuestDataTestCode;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class EvisitController extends BaseController
{
    public function onlineDoctors(Request $request, $id){
        $user = Auth::user();
        $user_state = Auth::user()->state_id;
        $state = DB::table('users')->where('users.id', $user->id)->select('users.state_id')->first();
        $state = $state->state_id;
        $active_states = State::where('active',1)->get();
        $price = DB::table('specalization_price')->where('spec_id', $id)->where('state_id',$user_state)->first();
        $location = State::where('id',$user_state)->first();

        if ($user->user_type == 'patient') {
            $active_states = State::where('active',1)->get();
            if($request->location_id !=''){
                $priceLoc = DB::table('specalization_price')->where('spec_id', $id)->where('state_id',$request->location_id)->first();
                if($priceLoc !=''){
                    $doctors = DB::table('doctor_licenses')
                    ->join('users', 'doctor_licenses.doctor_id', 'users.id')
                    ->join('specializations', 'specializations.id', 'users.specialization')
                    ->where('doctor_licenses.state_id', $request->location_id)
                    ->where('users.specialization', $id)
                    ->where('users.status', 'online')
                    ->where('users.active', '1')
                    ->where('doctor_licenses.is_verified', '1')
                    ->select('users.*', 'specializations.name as sp_name')
                    ->get();
                    foreach ($doctors as $doctor) {
                        $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);
                    }
                    $session = null;
                    $price = DB::table('specalization_price')->where('spec_id', $id)->where('state_id',$request->location_id)->first();
                    if ($price->follow_up_price != null) {
                        $session = DB::table('sessions')->where('patient_id', $user->id)
                            ->join('specializations', 'sessions.specialization_id', 'specializations.id')
                            ->join('specalization_price', 'sessions.specialization_id', 'specalization_price.spec_id')
                            ->select('specializations.name as sp_name', 'specalization_price.follow_up_price as price')
                            ->where('specialization_id', $id)->first();
                    }
                } else{
                    $doctors = "No online Doctor Available in this state";
                    $session = null;
                }
            } else{
                if($price !=''){
                    $doctors = DB::table('doctor_licenses')
                    ->join('users', 'doctor_licenses.doctor_id', 'users.id')
                    ->join('specializations', 'specializations.id', 'users.specialization')
                    ->where('doctor_licenses.state_id', $state)
                    ->where('users.specialization', $id)
                    ->where('users.status', 'online')
                    ->where('users.active', '1')
                    ->where('doctor_licenses.is_verified', '1')
                    ->select('users.*', 'specializations.name as sp_name')
                    ->get();
                    foreach ($doctors as $doctor) {
                        $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);
                    }
                    $session = null;
                    $price = DB::table('specalization_price')->where('spec_id', $id)->where('state_id',$state)->first();
                    if($price->follow_up_price != null) {
                        $session = DB::table('sessions')->where('patient_id', $user->id)
                            ->join('specializations', 'sessions.specialization_id', 'specializations.id')
                            ->join('specalization_price', 'sessions.specialization_id', 'specalization_price.spec_id')
                            ->select('specializations.name as sp_name', 'specalization_price.follow_up_price as price')
                            ->where('specialization_id', $id)->first();
                    }
                } else{
                    $doctors = null;
                    $session = null;
                }
            }
                $data["code"] = 200;
                $data["doctors"] = $doctors;
                $data["session"] = $session;
                $data["active_states"] = $active_states;
                $data["location"] = $location;
                $data["id"] = $id;
                if($data["doctors"] != '[]'){
                    return $this->sendResponse($data,"Online Doctors");
                } else{
                    $code["code"] = 200;
                    return $this->sendError($code,"No Online Doctors");
                }
                return $this->sendResponse($data,"Evisit doctors info");

        } else {
            $onlinDoc['code'] = 200;
            return $this->sendError($onlinDoc,"Please inform to your admin, you seems to be not a patient");
        }
    }
    public function problem_inquiry(Request $request){
        //  dd($request->all());
        $sym = \App\Http\Controllers\IsabelController::proquery($request->symptoms);
        if($sym != "Invalid Authentication."){
            $pro_sym = \App\Http\Controllers\IsabelController::getsymptoms($sym,$request->Pregnancy);
            $isabel_symp_id = DB::table('isabel_session_diagnosis')->insertGetId([
                'triage_api_url' => $pro_sym->triage_api_url,
                'diagnoses' => serialize($pro_sym->diagnoses),
            ]);
        }else{
            $isabel_symp_id = 0;
        }
        $patient_id = Auth::user()->id;
        $location_id = $request->location_id;
        $doc_id = $request->doc_id;
        $temp = '';
        foreach ($request->symptoms as $prob) {
            $temp = $temp . $prob . ",";
        }
        $symp_id = Symptom::create([
            'patient_id' =>  $patient_id,
            'doctor_id' => $request->doc_id,
            'headache' => '0',
            'flu' => '0',
            'fever' => '0',
            'nausea' => '0',
            'others' => '0',
            'description' => $request->problem,
            'symptoms_text' => $temp,
            'status' => 'pending',
        ])->id;
        $check_session_already_have = DB::table('sessions')
            ->where('doctor_id', $request->doc_id)
            ->where('patient_id', $patient_id)
            ->where('specialization_id', $request->doc_sp_id)
            ->count();
        $session_price = "";
        if ($check_session_already_have > 0) {
            $session_price_get = DB::table('specalization_price')->where('spec_id', $request->doc_sp_id)->where('state_id', auth()->user()->state_id)->first();
            if ($session_price_get->follow_up_price != null) {
                $session_price = $session_price_get->follow_up_price;
            } else {
                $session_price = $session_price_get->initial_price;
            }
        } else {
            $session_price_get = DB::table('specalization_price')->where('spec_id', $request->doc_sp_id)->where('state_id', auth()->user()->state_id)->first();
            $session_price = $session_price_get->initial_price;
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
        $new_session_id;
        if(isset($request->ses_id)){
            $session_id = $request->ses_id;
            $sesion = DB::table('sessions')->where('id',$session_id)->select('session_id')->first();
            $new_session_id = $sesion->session_id;
            Session::where('id',$session_id)->update([
                'patient_id' =>  $patient_id,
                'doctor_id' =>  $doc_id,
                'date' =>  $date,
                'status' => 'paid',
                'queue' => $queue,
                'symptom_id' => $symp_id,
                'remaining_time' => 'full',
                'channel' => $channelName,
                'price' => $session_price,
                'specialization_id' => $request->doc_sp_id,
                'session_id' => $new_session_id,
                'location_id' => $location_id,
                'isabel_diagnosis_id' => $isabel_symp_id,
                'validation_status' => "valid",
            ]);
            $data['code'] = 200;
            $data['session_id'] = $session_id;
            return $this->sendResponse($data,"session created redirect to patient waiting room");
        }else{
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
                'symptom_id' => $symp_id,
                'remaining_time' => 'full',
                'channel' => $channelName,
                'price' => $session_price,
                'specialization_id' => $request->doc_sp_id,
                'session_id' => $new_session_id,
                'location_id' => $location_id,
                'isabel_diagnosis_id' => $isabel_symp_id,
                'validation_status' => "valid",
            ])->id;
            if($session_id != ''){
                $data['code'] =200;
                $data['session_id'] =$session_id;
                return $this->sendResponse($data,"patient session payment");
            } else{
                $error['code'] =200;
                return $this->sendResponse( $error,"Opps! Something Went Wrong");
            }
        }
    }
    public function evisit_oldCard(Request $request){
        $session_id= $request->session_id;
        $id = auth()->user()->id;
        $card_no= $request->card_no; //old card id
        $amount_charge =$request->amount_charge;
        $query = DB::table('card_details')
        ->where('id',$card_no)
        ->get();
        $pay = new \App\Http\Controllers\PaymentController();
        $profile = $query[0]->customerProfileId;
        $payment = $query[0]->customerPaymentProfileId;
        $amount = $amount_charge;
        $response = ($pay->new_createPaymentwithCustomerProfile($amount, $profile, $payment));
        // $flag = false;
        if($response['messages']['message'][0]['text'] == 'Successful.') {
            $getSession = DB::table('sessions')->where('id', $session_id)->first();
            Session::where('id', $session_id)->update(['status' => 'paid']);
            $fireSession = DB::table('sessions')->where('id', $session_id)->first();
            $doctor_data = DB::table('users')->where('id', $getSession->doctor_id)->first();
            $patient_data = DB::table('users')->where('id', $getSession->patient_id)->first();
            $markDownData = [
                'doc_name' => ucwords($doctor_data->name),
                'pat_name' => ucwords($patient_data->name),
                'pat_email' => $patient_data->email,
                'doc_mail' => $doctor_data->email,
                'amount' => $amount_charge,
            ];
            Mail::to($patient_data->email)->send(new EvisitBookMail($markDownData));
            $text = "New Session Created by " . $patient_data->name . " " . $patient_data->last_name;
            $evisitnoti =Notification::create([
                'user_id' =>  $getSession->doctor_id,
                'type' => '/doctor/patient/queue',
                'text' => $text,
                'appoint_id' => $session_id,
            ]);
            $data = [
                'user_id' =>  $getSession->doctor_id,
                'type' => '/doctor/patient/queue',
                'text' => $text,
                'appoint_id' => $session_id,
                'session_id' => "null",
                'received' => 'false',
                'refill_id' => 'null',
            ];
            event(new RealTimeMessage($getSession->doctor_id));
            event(new updateQuePatient('update patient que'));
            try {
                $getSession->received = false;
                // \App\Helper::firebase($getSession->doctor_id,'notification',$evisitnoti->id,$data);
                // \App\Helper::firebase($getSession->doctor_id,'updateQuePatient',$session_id,$fireSession);
            } catch (\Throwable $th) {
                //throw $th;
            }

            $oldpayment['code'] = 200;
            $oldpayment['evist_session_id'] = $session_id;
            return $this->sendResponse($oldpayment,'payment successfull.');
        } else {
            $code = $response['messages']['message'][0]['code'];
            $message = TblTransaction::errorCode($code);
            DB::table('error_log')->insert([
                'user_id' => $id,
                'Error_code' => $code,
                'Error_text' => $response['messages']['message'][0]['text'],
            ]);
            $oldcardError['code'] = 200;
            $oldcardError['evisit_session_id'] = $session_id;
            $oldcardError['error_message'] = $message;
            return $this->sendError($oldcardError,'error in old card payment');
        }
    }
    public function evisit_newcard(Request $request){
        $id = auth()->user()->id;
        $this->validate($request, [
            'card_holder_name' => 'required',
            'card_holder_last_name' => 'required',
            'card_num' => 'required|integer|min:16',
            'email' => 'required',
            'phoneNumber' => 'required',
            'month' => 'required|',
            'year' => 'required|',
            'cvc' => 'required|integer',
            'state' => 'required',
            'city' => 'required',
            'zipcode' => 'required',
            'address' => 'required',
            'session_id' => 'required',
            'amount_charge' => 'required',
            'subject' => 'required',
        ]);
        $getSession = DB::table('sessions')->where('id', $request->session_id)->first();
        $name = $request->card_holder_name . $request->card_holder_name_middle;
        $city = City::find($request->city)->name;
        $state = State::find($request->state)->name;
        $input = [
            'user' => [
                'description' => $request->card_holder_name . " " . $request->card_holder_last_name,
                'email' => $request->email,
                'firstname' => $request->card_holder_name,
                'lastname' => $request->card_holder_last_name,
                'phoneNumber' => $request->phoneNumber,
            ],
            'info' => [
                'subject' => $request->subject,
                'user_id' => $getSession->patient_id,
                'description' => $request->session_id,
                'amount' => $request->amount_charge,
            ],
            'billing_info' => [
                'amount' => $request->amount_charge,
                'credit_card' => [
                    'number' => $request->card_num,
                    'expiration_month' => $request->month,
                    'expiration_year' => $request->year,
                ],
                'integrator_id' => $request->subject . '-' . $request->session_id,
                'csc' => $request->cvc,
                'billing_address' => [
                    'name' => $name,
                    'street_address' => $request->address,
                    'city' => $city,
                    'state' => $state,
                    'zip' => $request->zipcode,
                ]
            ]
        ];
        $pay = new \App\Http\Controllers\PaymentController();
        $response = ($pay->new_createCustomerProfile($input));
        $flag = true;
        if($response['messages']['message'][0]['text'] == 'Successful.') {
            if($flag) {
                $profileId = $response['transactionResponse']['profile']['customerProfileId'];
                $paymentId = $response['transactionResponse']['profile']['customerPaymentProfileId'];
                $billing = array(
                    'number' => 'xxxx-xxxx-xxxx-' . substr($request->card_num, -4),
                    'expiration_month' => $request->month,
                    'expiration_year' => $request->year,
                    "csc" => $request->cvc,
                    "name" => $name,
                    "last_name" => $request->card_holder_last_name,
                    "email" => $request->email,
                    "street_address" => $request->address,
                    "city" => $city,
                    "state" => $state,
                    "zip" => $request->zipcode,
                    "phoneNumber" => $request->phoneNumber
                );
                DB::table('card_details')->insert([
                    'user_id' => Auth::user()->id,
                    'customerProfileId' => $profileId,
                    'customerPaymentProfileId' => $paymentId,
                    'card_number' => substr($request->card_num,-4),
                    'billing' => serialize($billing),
                    'shipping' => '',
                    'card_type' =>substr($request->card_num, 0,1),
                ]);
            }
            $getSession = DB::table('sessions')->where('id', $request->session_id)->first();
            Session::where('id', $request->session_id)->update(['status' => 'paid']);
            $firebaseSession = DB::table('sessions')->where('id', $request->session_id)->first();
            $doctor_data = DB::table('users')->where('id', $getSession->doctor_id)->first();
            $patient_data = DB::table('users')->where('id', $getSession->patient_id)->first();
            $markDownData = [
                'doc_name' => ucwords($doctor_data->name),
                'pat_name' => ucwords($patient_data->name),
                'pat_email' => $patient_data->email,
                'doc_mail' => $doctor_data->email,
                'amount' => $request->amount_charge,
            ];
            Mail::to($patient_data->email)->send(new EvisitBookMail($markDownData));
            $text = "New Session Created by " . $patient_data->name . " " . $patient_data->last_name;
            $evisitnewCard =Notification::create([
                'user_id' =>  $getSession->doctor_id,
                'type' => '/doctor/patient/queue',
                'text' => $text,
                'appoint_id' => $request->session_id,
            ]);
            $data = [
                'user_id' =>  $getSession->doctor_id,
                'type' => '/doctor/patient/queue',
                'text' => $text,
                'appoint_id' => $request->session_id,
                'session_id' => "null",
                'received' => 'false',
                'refill_id' => 'null',
            ];
            event(new RealTimeMessage($getSession->doctor_id));
            event(new updateQuePatient('update patient que'));
            try {
                // \App\Helper::firebase($getSession->doctor_id,'notification',$evisitnewCard->id,$data);
                // \App\Helper::firebase($getSession->doctor_id,'updateQuePatient',$request->session_id,$firebaseSession);
            } catch (\Throwable $th) {
                //throw $th;
            }
            $newpayment['code'] = 200;
            $newpayment['evist_session_id'] = $request->session_id;
            return $this->sendResponse($newpayment,'payment successfull.');
        } else {
            $code = $response['messages']['message'][0]['code'];
            $message = TblTransaction::errorCode($code);
            DB::table('error_log')->insert([
                'user_id' => $id,
                'Error_code' => $code,
                'Error_text' => $response['messages']['message'][0]['text'],
            ]);
            $newcardError['code'] = 200;
            $newcardError['evisit_session_id'] = $request->session_id;
            $newcardError['error_message'] = $message;
            return $this->sendError($newcardError,'Error in New card payment');
        }
    }
    public function sendinvite(Request $request){
        // update session status
        $session_id = $request->session_id;
        $res = Session::where('id', $session_id)->update(['status' => 'invitation sent']);
        if ($res == 1) {
            //get patient session record
            $sessionData = Session::where('id', $session_id)->first();
            $doc_name = Helper::get_name($sessionData->doctor_id);
            $pat_name = Helper::get_name($sessionData->patient_id);
            $sessionNoti = Notification::create([
                'user_id' => $sessionData->doctor_id,
                'text' => 'E-visit Joining Request Send by ' . $pat_name,
                'session_id' => $session_id,
                'type' => 'doctor/patient/queue',
            ]);
            $data = [
                'user_id' => $sessionData->doctor_id,
                'text' => 'E-visit Joining Request Send by ' . $pat_name,
                'session_id' => $session_id,
                'type' => 'doctor/patient/queue',
                'appoint_id' => "null",
                'received' => 'false',
                'refill_id' => 'null',
            ];
            event(new RealTimeMessage($sessionData->doctor_id));
            event(new updateDoctorWaitingRoom('new_patient_listed'));
            try {
                // \App\Helper::firebase($sessionData->doctor_id,'notification',$sessionNoti->id,$data);
                $sessionData->received = false;
                // \App\Helper::firebase($sessionData->doctor_id,'updateDoctorWaitingRoom',$session_id,$sessionData);
            } catch (\Throwable $th) {
                //throw $th;
            }
            try {
                // mailgun send mail to doctor to join evisit session
                $doctor =  DB::table('users')->where('id', $sessionData->doctor_id)->first();
                $markDown = [
                    'doc_email' => $doctor->email,
                    'doc_name' => ucwords($doc_name),
                    'pat_name' => ucwords($pat_name),
                ];
                //Mail::to('baqir.redecom@gmail.com')->send(new patientEvisitInvitationMail($markDown));
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
            $getData = Session::where('id', $session_id)->where('patient_id', auth()->user()->id)->first();
            $inviteData['code'] = 200;
            $inviteData['session_data'] = $getData;
            return $this->sendResponse($inviteData,"Invitation send to doctor");
        } else {
            return false;
        }
    }
    public function waiting_room_pat(Request $request){
        $session_id = $request->session_id;
        $session = Session::find($session_id);
        $pat_id = auth()->user()->id;
        $all_waiting = Session::where('doctor_id', $session['doctor_id'])
            ->where('patient_id', '!=', $pat_id)
            ->where('id', '<', $session_id)
            ->where('status', 'invitation sent')
            ->groupBy('patient_id')
            ->get();

        $waiting_count = count($all_waiting);

        if ($session['status'] == 'invitation sent')
            $status = 'true';
        else
            $status = 'false';
        $doctor = User::where('id', $session['doctor_id'])->first();
        $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);

        $wating_roomData['code'] = 200;
        $wating_roomData['status'] = $status;
        $wating_roomData['doctor'] = $doctor;
        $wating_roomData['session_id'] = $session_id;
        $wating_roomData['waiting_count'] = $waiting_count;
        return $this->sendResponse($wating_roomData,"patient waiting in room");
    }
    public function patient_join(Request $request){
        $id = $request->session_id;
        $getSession = Session::where('id', $id)->first();
        $channel_id = $getSession->channel;
        $userTypeCheck = User::where('id', $getSession->doctor_id)->first();
        $patUser = User::where('id', $getSession->patient_id)->first();
        if ($patUser->med_record_file != null) {
            $patUser->med_record_file = \App\Helper::get_files_url($patUser->med_record_file);
        }
        if ($userTypeCheck->user_type == 'patient') {
            event(new patientJoinCall($getSession->doctor_id, $getSession->patient_id, $id));
            try {
                $getSession->received = false;
                // \App\Helper::firebase($getSession->doctor_id,'patientJoinCall',$getSession->patient_id,$id);
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
        $doctSendJoinRequest['code'] = 200;
        $doctSendJoinRequest['channel_name'] = $channel_id;
        $doctSendJoinRequest['patient_id'] = $getSession->patient_id;
        $doctSendJoinRequest['doctor_id'] = $getSession->doctor_id;
        return $this->sendResponse($doctSendJoinRequest,'patient join');
    }
    public function latest_session(){
        $user =Auth::user()->id;
        $getSession = Session::where('patient_id',$user)->where('status','doctor joined')->latest()->first();
        if($getSession != null){
            $data['code'] = 200;
            $data['session'] = $getSession;
            return $this->sendResponse($data,'Doctor has Join session');
        } else{
            $data['code'] = 200;
            $data['session'] = null;
            return $this->sendError($data,'No session info');

        }

    }
}
