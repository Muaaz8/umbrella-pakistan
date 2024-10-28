<?php

namespace App\Http\Controllers\Api\DoctorsApi;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use App\Events\DoctorJoinedVideoSession;
use Auth;
use DB;
use App\Session;
use App\User;
use App\ActivityLog;
use App\AgoraAynalatics;
use App\Referal;
use App\Prescription;
use App\Pharmacy;
use App\Cart;
use App\QuestDataTestCode;
use App\Models\AllProducts;
use App\State;

class SessionController extends BaseController
{
    public function all_session(){
        $user = auth()->user();
        $user_type = $user->user_type;
        $user_time_zone = $user->timeZone;
        if ($user_type == 'doctor') {
            $user_state = Auth::user()->state_id;
            $state = State::find($user_state);
            if ($state->active == 1) {
                $user_id = $user->id;
                $sessions = Session::where('doctor_id', $user_id)
                    ->where('status', 'ended')
                    ->where('remaining_time','!=','full')
                    ->orderByDesc('id')
                    ->paginate(10);
                foreach ($sessions as $session) {
                    $getPersentage = DB::table('doctor_percentage')->where('doc_id', $user_id)->first();
                    $session->price = ($getPersentage->percentage / 100) * $session->price;
                    if ($session->status == 'ended' && $session->start_time != 'null' && $session->end_time != 'null') {
                        $session->date = User::convert_utc_to_user_timezone($user->id, $session->created_at)['date'];
                        $session->start_time = date('h:i A', strtotime('-15 minutes', strtotime($session->start_time)));
                        $session->start_time = User::convert_utc_to_user_timezone($user->id, $session->start_time)['time'];
                        $session->end_time = date('h:i A', strtotime('-15 minutes', strtotime($session->end_time)));
                        $session->end_time = User::convert_utc_to_user_timezone($user->id, $session->end_time)['time'];
                        // dd($session->end_time);
                        $pat = User::where('id', $session['patient_id'])->first();
                        $session->pat_name = $pat['name'] . " " . $pat['last_name'];
                    }
                }
                $doc_session['code'] = 200;
                $doc_session['sessions'] =$sessions;
                $doc_session['user_type'] =$user_type;
                return $this->sendResponse($doc_session,"list of a Doctor Sessions with Patient");
            } else {
                $code['code'] = 200;
                return $this->sendError($code,"Somthing Went Wrong");
            }
        }
    }
    //doctor click on join button
    public function waitingPatientJoinCall(Request $request)
    {
        $id = $request->session_id;
        $getSession = Session::where('id', $id)->first();
        $channel_id = $getSession->channel;
        $userTypeCheck = User::where('id', $getSession->doctor_id)->first();
        $patUser = User::where('id', $getSession->patient_id)->first();
        if ($patUser->med_record_file != null) {
            $patUser->med_record_file = \App\Helper::get_files_url($patUser->med_record_file);
        }
        if ($userTypeCheck->user_type == 'doctor') {
            Session::where('id', $id)->update(['status' => 'doctor joined']);
            event(new DoctorJoinedVideoSession($getSession->doctor_id, $getSession->patient_id, $id));
            try {
                $firebase_session = DB::table('sessions')->where('id',$id)->first();
                $firebase_session->received = false;
                \App\Helper::firebase($firebase_session->patient_id,'DoctorJoinedVideoSession',$firebase_session->id,$firebase_session);
            } catch (\Throwable $th) {
                //throw $th;
            }
            ActivityLog::create([
                'activity' => 'joined session with ' . $patUser->name . " " . $patUser->last_name,
                'type' => 'session start',
                'user_id' => $getSession->doctor_id,
                'user_type' => 'doctor',
                'identity' => $id,
                'party_involved' => $getSession->patient_id,
            ]);
        }
        $doctSendJoinRequest['code'] = 200;
        $doctSendJoinRequest['channel_name'] = $channel_id;
        $doctSendJoinRequest['patient_id'] = $getSession->patient_id;
        return $this->sendResponse($doctSendJoinRequest,'Waiting for patient to join');
    }
    public function session_details($session_id){
        $user = auth()->user();
        $user_type = $user->user_type;
        $doctor_name = $user->name.' '.$user->last_name;
        $date = [];
        $earning = [];
        $start_time = [];
        $end_time = [];
        $pat_name = [];
        $recording = [];
        $refered_doc = [];
        $user_time_zone = $user->timeZone;
        if ($user_type == 'doctor') {
            $user_state = Auth::user()->state_id;
            $state = State::find($user_state);
            if ($state->active == 1) {
                $user_id = $user->id;
                $sessions = Session::where('doctor_id', $user_id)
                    ->where('status', 'ended')
                    ->where('id', $session_id)
                    ->where('remaining_time','!=','full')
                    ->orderByDesc('id')
                    ->get();
                foreach ($sessions as $session) {
                    $getPersentage = DB::table('doctor_percentage')->where('doc_id', $user_id)->first();
                    $earning= ($getPersentage->percentage / 100) * $session->price;
                    if ($session->status == 'ended' && $session->start_time != 'null' && $session->end_time != 'null') {
                        $date = User::convert_utc_to_user_timezone($user->id, $session->created_at)['date'];
                        $start_time = date('h:i A', strtotime('-15 minutes', strtotime($session->start_time)));
                        $start_time = User::convert_utc_to_user_timezone($user->id, $session->start_time)['time'];
                        $end_time = date('h:i A', strtotime('-15 minutes', strtotime($session->end_time)));
                        $end_time = User::convert_utc_to_user_timezone($user->id, $session->end_time)['time'];
                        $pat = User::where('id', $session['patient_id'])->first();
                        $pat_name = $pat['name'] . " " . $pat['last_name'];
                        $session->patient_name = $pat['name'] . " " . $pat['last_name'];
                        $links = AgoraAynalatics::where('channel', $session['channel'])->first();
                        $session->symptom_detail = DB::table('symptoms')->where('id',$session->symptom_id)->get(['symptoms_text','description']);
                        if ($links != null) {
                            $recording = $links->video_link;
                        } else {
                            $recording = 'No recording';
                        }
                        $referred_doc = Referal::where('session_id', $session['id'])
                            ->where('patient_id', $session['patient_id'])
                            ->where('doctor_id', $user_id)
                            ->leftjoin('users', 'referals.sp_doctor_id', 'users.id')
                            ->select('users.name', 'users.last_name')
                            ->get();
                        if (count($referred_doc)) {
                            $refered_doc = "You Referred the Patient to Dr." . $referred_doc[0]->name . " " . $referred_doc[0]->last_name;
                        } else {
                            $refered_doc = null;
                        }
                        $pres = Prescription::where('session_id', $session['id'])->get();
                        $pres_arr = [];
                        foreach ($pres as $prod) {
                            if ($prod['type'] == 'medicine') {
                                $medicine['medicine_name'] = AllProducts::where('id', $prod['medicine_id'])->get(['name']);
                                $medicine['imaging_name'] =[];
                                $medicine['lab_test_name'] =[];
                                $product = $medicine;
                            } else if ($prod['type'] == 'imaging') {
                                $imaging['imaging_name'] = AllProducts::where('id', $prod['imaging_id'])->get(['name']);
                                $imaging['medicine_name'] =[];
                                $imaging['lab_test_name'] =[];
                                $product = $imaging;
                                $usage = DB::table('imaging_selected_location')
                                ->join('imaging_locations', 'imaging_selected_location.imaging_location_id', 'imaging_locations.id')
                                ->where('imaging_selected_location.session_id', $prod['session_id'])
                                ->where('imaging_selected_location.product_id', $prod['imaging_id'])
                                ->select('imaging_locations.address as location')
                                ->first();
                                $prod->usage = $usage->location;
                            } else if ($prod['type'] == 'lab-test') {
                                $lab_test['lab_test_name'] = QuestDataTestCode::where('TEST_CD', $prod['test_id'])
                                    ->get(['TEST_NAME']);
                                $lab_test['medicine_name'] =[];
                                $lab_test['imaging_name'] =[];
                                $product = $lab_test;
                            }
                            $cart = Cart::where('doc_session_id', $session['id'])
                                ->where('pres_id', $prod->id)->first();
                            $prod['prescription_detail'] = array($product);
                            if (isset($cart->status)){
                                $prod->cart_status = $cart->status;
                            } else{
                                $prod->cart_status = 'No record';
                            }
                            array_push($pres_arr, $prod);
                        }
                        $session->pres = $pres_arr;
                    }
                }
                $doc_session['code'] = 200;
                $doc_session['sessions'] =$sessions;
                $doc_session['user_type'] =$user_type;
                $doc_session['doctor_name'] =$doctor_name;
                $doc_session['earning'] =$earning;
                $doc_session['date'] = $date;
                $doc_session['start_time'] = $start_time;
                $doc_session['end_time'] = $end_time;
                $doc_session['pat_name'] = $pat_name;
                $doc_session['recording'] = $recording;
                $doc_session['refered_doc'] = $refered_doc;
                return $this->sendResponse($doc_session,"list of a Doctor Sessions with Patient");
            } else {
                $code['code'] = 200;
                return $this->sendError($code,"Somthing Went Wrong");
            }
        }
    }
    public function session_search(Request $request){
        $user = auth()->user();
        $user_type = $user->user_type;
        $user_time_zone = $user->timeZone;
        $user_state = Auth::user()->state_id;
        $state = State::find($user_state);
        if($request->session_id != null && $request->datefilter == null){
            if ($state->active == 1) {
                $user_id = $user->id;
                $sessions = Session::where('doctor_id', $user_id)
                    ->where('status', 'ended')
                    ->where('session_id', $request->session_id)
                    ->orderByDesc('id')
                    ->paginate(7);
                foreach ($sessions as $session) {
                    $getPersentage = DB::table('doctor_percentage')->where('doc_id', $user_id)->first();
                    $session->price = ($getPersentage->percentage / 100) * $session->price;
                    if ($session->status == 'ended' && $session->start_time != 'null' && $session->end_time != 'null') {
                        $session->date = User::convert_utc_to_user_timezone($user->id, $session->created_at)['date'];
                        $session->start_time = date('h:i A', strtotime('-15 minutes', strtotime($session->start_time)));
                        $session->start_time = User::convert_utc_to_user_timezone($user->id, $session->start_time)['time'];
                        $session->end_time = date('h:i A', strtotime('-15 minutes', strtotime($session->end_time)));
                        $session->end_time = User::convert_utc_to_user_timezone($user->id, $session->end_time)['time'];
                        $pat = User::where('id', $session['patient_id'])->first();
                        $session->pat_name = $pat['name'] . " " . $pat['last_name'];
                        $links = AgoraAynalatics::where('channel', $session['channel'])->first();
                        if ($links != null) {
                            $recording = $links->video_link;
                            $session->recording = $recording;
                        } else {
                            $session->recording = 'No recording';
                        }
                        $referred_doc = Referal::where('session_id', $session['id'])
                            ->where('patient_id', $session['patient_id'])
                            ->where('doctor_id', $user_id)
                            ->leftjoin('users', 'referals.sp_doctor_id', 'users.id')
                            ->select('users.name', 'users.last_name')
                            ->get();
                        if (count($referred_doc)) {
                            $session->refered = "You Referred the Patient to Dr." . $referred_doc[0]->name . " " . $referred_doc[0]->last_name;
                        } else {
                            $session->refered = null;
                        }
                        $session->sympptoms = DB::table('symptoms')->where('id',$session['symptom_id'])->first();
                        $pres = Prescription::where('session_id', $session['id'])->get();
                        $pres_arr = [];
                        foreach ($pres as $prod) {
                            if ($prod['type'] == 'medicine') {
                                $product = AllProducts::where('id', $prod['medicine_id'])->first();
                            } else if ($prod['type'] == 'imaging') {
                                $product = AllProducts::where('id', $prod['imaging_id'])->first();
                            } else if ($prod['type'] == 'lab-test') {
                                $product = QuestDataTestCode::where('TEST_CD', $prod['test_id'])
                                    ->first();
                            }
                            $cart = Cart::where('doc_session_id', $session['id'])
                                ->where('pres_id', $prod->id)->first();
                            $prod->prod_detail = $product;
                            if (isset($cart->status))
                                $prod->cart_status = $cart->status;
                            else
                                $prod->cart_status = 'No record';
                            array_push($pres_arr, $prod);
                        }
                        $session->pres = $pres_arr;
                    }
                }
            }
        }else if($request->session_id == null && $request->datefilter != null){
            $start_date = explode(' - ',$request->datefilter)[0];
            $end_date = explode(' - ',$request->datefilter)[1];
            $start_date = date('Y-m-d', strtotime($start_date));
            $end_date = date('Y-m-d', strtotime($end_date));
            if ($state->active == 1) {
                $user_id = $user->id;
                $sessions = Session::where('doctor_id', $user_id)
                    ->where('status', 'ended')
                    ->where('date', '>=' ,$start_date)
                    ->where('date', '<=' ,$end_date)
                    ->paginate(7);
                foreach ($sessions as $session) {
                    $getPersentage = DB::table('doctor_percentage')->where('doc_id', $user_id)->first();
                    $session->price = ($getPersentage->percentage / 100) * $session->price;
                    if ($session->status == 'ended' && $session->start_time != 'null' && $session->end_time != 'null') {
                        $session->date = User::convert_utc_to_user_timezone($user->id, $session->created_at)['date'];
                        $session->start_time = date('h:i A', strtotime('-15 minutes', strtotime($session->start_time)));
                        $session->start_time = User::convert_utc_to_user_timezone($user->id, $session->start_time)['time'];
                        $session->end_time = date('h:i A', strtotime('-15 minutes', strtotime($session->end_time)));
                        $session->end_time = User::convert_utc_to_user_timezone($user->id, $session->end_time)['time'];
                        $pat = User::where('id', $session['patient_id'])->first();
                        $session->pat_name = $pat['name'] . " " . $pat['last_name'];
                        $links = AgoraAynalatics::where('channel', $session['channel'])->first();
                        if ($links != null) {
                            $recording = $links->video_link;
                            $session->recording = $recording;
                        } else {
                            $session->recording = 'No recording';
                        }
                        $referred_doc = Referal::where('session_id', $session['id'])
                            ->where('patient_id', $session['patient_id'])
                            ->where('doctor_id', $user_id)
                            ->leftjoin('users', 'referals.sp_doctor_id', 'users.id')
                            ->select('users.name', 'users.last_name')
                            ->get();
                        if (count($referred_doc)) {
                            $session->refered = "You Referred the Patient to Dr." . $referred_doc[0]->name . " " . $referred_doc[0]->last_name;
                        } else {
                            $session->refered = null;
                        }
                        $session->sympptoms = DB::table('symptoms')->where('id',$session['symptom_id'])->first();
                        $pres = Prescription::where('session_id', $session['id'])->get();
                        $pres_arr = [];
                        foreach ($pres as $prod) {
                            if ($prod['type'] == 'medicine') {
                                $product = AllProducts::where('id', $prod['medicine_id'])->first();
                            } else if ($prod['type'] == 'imaging') {
                                $product = AllProducts::where('id', $prod['imaging_id'])->first();
                            } else if ($prod['type'] == 'lab-test') {
                                $product = QuestDataTestCode::where('TEST_CD', $prod['test_id'])
                                    ->first();
                            }
                            $cart = Cart::where('doc_session_id', $session['id'])
                                ->where('pres_id', $prod->id)->first();
                            $prod->prod_detail = $product;
                            if (isset($cart->status))
                                $prod->cart_status = $cart->status;
                            else
                                $prod->cart_status = 'No record';
                            array_push($pres_arr, $prod);
                        }
                        $session->pres = $pres_arr;
                    }
                }
            }
        }else if($request->session_id != null && $request->datefilter != null){
            $start_date = explode(' - ',$request->datefilter)[0];
            $end_date = explode(' - ',$request->datefilter)[1];
            $start_date = date('Y-m-d', strtotime($start_date));
            $end_date = date('Y-m-d', strtotime($end_date));
            if ($state->active == 1) {
                $user_id = $user->id;
                $sessions = Session::where('doctor_id', $user_id)
                    ->where('status', 'ended')
                    ->where('session_id', $request->session_id)
                    ->where('date', '>=' ,$start_date)
                    ->where('date', '<=' ,$end_date)
                    ->paginate(7);
                foreach ($sessions as $session) {
                    $getPersentage = DB::table('doctor_percentage')->where('doc_id', $user_id)->first();
                    $session->price = ($getPersentage->percentage / 100) * $session->price;
                    if ($session->status == 'ended' && $session->start_time != 'null' && $session->end_time != 'null') {
                        $session->date = User::convert_utc_to_user_timezone($user->id, $session->created_at)['date'];
                        $session->start_time = date('h:i A', strtotime('-15 minutes', strtotime($session->start_time)));
                        $session->start_time = User::convert_utc_to_user_timezone($user->id, $session->start_time)['time'];
                        $session->end_time = date('h:i A', strtotime('-15 minutes', strtotime($session->end_time)));
                        $session->end_time = User::convert_utc_to_user_timezone($user->id, $session->end_time)['time'];
                        $pat = User::where('id', $session['patient_id'])->first();
                        $session->pat_name = $pat['name'] . " " . $pat['last_name'];
                        $links = AgoraAynalatics::where('channel', $session['channel'])->first();
                        if ($links != null) {
                            $recording = $links->video_link;
                            $session->recording = $recording;
                        } else {
                            $session->recording = 'No recording';
                        }
                        $referred_doc = Referal::where('session_id', $session['id'])
                            ->where('patient_id', $session['patient_id'])
                            ->where('doctor_id', $user_id)
                            ->leftjoin('users', 'referals.sp_doctor_id', 'users.id')
                            ->select('users.name', 'users.last_name')
                            ->get();
                        if (count($referred_doc)) {
                            $session->refered = "You Referred the Patient to Dr." . $referred_doc[0]->name . " " . $referred_doc[0]->last_name;
                        } else {
                            $session->refered = null;
                        }
                        $session->sympptoms = DB::table('symptoms')->where('id',$session['symptom_id'])->first();

                        $pres = Prescription::where('session_id', $session['id'])->get();
                        $pres_arr = [];
                        foreach ($pres as $prod) {
                            if ($prod['type'] == 'medicine') {
                                $product = AllProducts::where('id', $prod['medicine_id'])->first();
                            } else if ($prod['type'] == 'imaging') {
                                $product = AllProducts::where('id', $prod['imaging_id'])->first();
                            } else if ($prod['type'] == 'lab-test') {
                                $product = QuestDataTestCode::where('TEST_CD', $prod['test_id'])
                                    ->first();
                            }
                            $cart = Cart::where('doc_session_id', $session['id'])
                                ->where('pres_id', $prod->id)->first();
                            $prod->prod_detail = $product;
                            if (isset($cart->status))
                                $prod->cart_status = $cart->status;
                            else
                                $prod->cart_status = 'No record';
                            array_push($pres_arr, $prod);
                        }
                        $session->pres = $pres_arr;
                    }
                }
            }
        }else{
            if ($state->active == 1) {
                $user_id = $user->id;
                $sessions = Session::where('doctor_id', $user_id)
                    ->where('status', 'ended')
                    ->orderByDesc('id')
                    ->paginate(15);
                foreach ($sessions as $session) {

                    $getPersentage = DB::table('doctor_percentage')->where('doc_id', $user_id)->first();
                    $session->price = ($getPersentage->percentage / 100) * $session->price;

                    if ($session->status == 'ended' && $session->start_time != 'null' && $session->end_time != 'null') {
                        $session->date = User::convert_utc_to_user_timezone($user->id, $session->created_at)['date'];
                        $session->start_time = date('h:i A', strtotime('-15 minutes', strtotime($session->start_time)));
                        $session->start_time = User::convert_utc_to_user_timezone($user->id, $session->start_time)['time'];

                        $session->end_time = date('h:i A', strtotime('-15 minutes', strtotime($session->end_time)));
                        $session->end_time = User::convert_utc_to_user_timezone($user->id, $session->end_time)['time'];
                        $pat = User::where('id', $session['patient_id'])->first();
                        $session->pat_name = $pat['name'] . " " . $pat['last_name'];

                        $links = AgoraAynalatics::where('channel', $session['channel'])->first();
                        if ($links != null) {
                            $recording = $links->video_link;
                            $session->recording = $recording;
                        } else {
                            $session->recording = 'No recording';
                        }
                        $referred_doc = Referal::where('session_id', $session['id'])
                            ->where('patient_id', $session['patient_id'])
                            ->where('doctor_id', $user_id)
                            ->leftjoin('users', 'referals.sp_doctor_id', 'users.id')
                            ->select('users.name', 'users.last_name')
                            ->get();
                        if (count($referred_doc)) {
                            $session->refered = "You Referred the Patient to Dr." . $referred_doc[0]->name . " " . $referred_doc[0]->last_name;
                        } else {
                            $session->refered = null;
                        }
                        $session->sympptoms = DB::table('symptoms')->where('id',$session['symptom_id'])->first();
                        $pres = Prescription::where('session_id', $session['id'])->get();
                        $pres_arr = [];
                        foreach ($pres as $prod) {
                            if ($prod['type'] == 'medicine') {
                                $product = AllProducts::where('id', $prod['medicine_id'])->first();
                            } else if ($prod['type'] == 'imaging') {
                                $product = AllProducts::where('id', $prod['imaging_id'])->first();
                            } else if ($prod['type'] == 'lab-test') {
                                $product = QuestDataTestCode::where('TEST_CD', $prod['test_id'])
                                    ->first();
                            }
                            $cart = Cart::where('doc_session_id', $session['id'])
                                ->where('pres_id', $prod->id)->first();
                            $prod->prod_detail = $product;
                            if (isset($cart->status))
                                $prod->cart_status = $cart->status;
                            else
                                $prod->cart_status = 'No record';
                            array_push($pres_arr, $prod);
                        }
                        $session->pres = $pres_arr;
                    }
                }
            }
        }
        $sessionData['code'] = 200;
        $sessionData['sessions'] = $sessions;
        return $this->sendResponse($sessionData,'session info');
    }
}
