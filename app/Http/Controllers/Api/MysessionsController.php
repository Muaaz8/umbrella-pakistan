<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use App\State;
use App\Session;
use App\User;
use App\AgoraAynalatics;
use App\Prescription;
use App\Referal;
use App\QuestDataTestCode;
use App\Models\AllProducts;
use App\Cart;
use Auth;
use DB;

class MysessionsController extends BaseController
{
    public function mysession(){
        $user = auth()->user();
        $user_type = $user->user_type;
        $user_time_zone = $user->timeZone;
        if ($user_type == 'patient') {
            $user_state = Auth::user()->state_id;
            $state = State::find($user_state);
            if ($state->active == 1) {
                $user_id = $user->id;
                $sessions = Session::where('patient_id', $user_id)
                    ->where('status', 'ended')
                    ->where('remaining_time','!=','full')
                    ->orderByDesc('id')
                    ->paginate(20);
                foreach ($sessions as $session) {
                    if ($session->start_time != 'null' && $session->end_time != 'null') {
                        $session->date = User::convert_utc_to_user_timezone($user->id, $session->created_at)['date'];
                        $session->start_time = date('h:i A', strtotime('-15 minutes', strtotime($session->start_time)));
                        $session->start_time = User::convert_utc_to_user_timezone($user->id, $session->start_time)['time'];
                        $session->end_time = date('h:i A', strtotime('-15 minutes', strtotime($session->end_time)));
                        $session->end_time = User::convert_utc_to_user_timezone($user->id, $session->end_time)['time'];
                        $doc = User::where('id', $session['doctor_id'])->first();
                        $session->doc_name = $doc['name'] . " " . $doc['last_name'];
                        $links = AgoraAynalatics::where('channel', $session['channel'])->first();
                        if ($links != null) {
                            $recording = $links->video_link;
                            $session->recording = $recording;
                        } else {
                            $session->recording = 'No recording';
                        }
                        $pres = Prescription::where('session_id', $session['id'])->get();
                        $pres_arr = [];
                        $referred_doc = Referal::where('session_id', $session['id'])
                            ->where('patient_id', $user_id)
                            ->where('doctor_id', $doc->id)
                            ->leftjoin('users', 'referals.sp_doctor_id', 'users.id')
                            ->select('users.name', 'users.last_name')
                            ->get();
                        if (count($referred_doc)) {
                            $session->refered = "Dr." . $referred_doc[0]->name . " " . $referred_doc[0]->last_name;
                        } else {
                            $session->refered = null;
                        }
                        foreach ($pres as $prod) {
                            if ($prod['type'] == 'medicine') {
                                $product = AllProducts::where('id', $prod['medicine_id'])->first();
                            } else if ($prod['type'] == 'imaging') {
                                $product = AllProducts::where('id', $prod['imaging_id'])->first();
                                $usage = DB::table('imaging_selected_location')
                                ->join('imaging_locations', 'imaging_selected_location.imaging_location_id', 'imaging_locations.id')
                                ->where('imaging_selected_location.session_id', $prod['session_id'])
                                ->where('imaging_selected_location.product_id', $prod['imaging_id'])
                                ->select('imaging_locations.address as location')
                                ->first();
                                $prod->usage = $usage->location;
                            } else if ($prod['type'] == 'lab-test') {
                                $product = QuestDataTestCode::where('TEST_CD', $prod['test_id'])->first();
                            }
                            $cart = Cart::where('doc_session_id', $session['id'])->where('pres_id', $prod->id)->first();
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
                $sessionData['code'] =200;
                $sessionData['sessions'] =$sessions;
                return $this->sendResponse($sessionData,"sessions found");
            } else{
                $sessionDataError['code'] =200;
                return $this->sendError($sessionDataError,"Opps something went wrong!");
            }
        }
    }
    public function mysession_detail($session_id){
        $user = auth()->user();
        $user_type = $user->user_type;
        $date = [];
        $start_time = [];
        $end_time = []; 
        $pat_name = []; 
        $recording = []; 
        $refered_doc = []; 
        $user_time_zone = $user->timeZone;
        if ($user_type == 'patient') {
            $user_state = Auth::user()->state_id;
            $state = State::find($user_state);
            if ($state->active == 1) {
                $user_id = $user->id;
                $sessions = Session::where('patient_id', $user_id)
                    ->where('status', 'ended')
                    ->where('remaining_time','!=','full')
                    ->where('id',$session_id)
                    ->orderByDesc('id')
                    ->get();
                foreach ($sessions as $session) {
                    if ($session->start_time != 'null' && $session->end_time != 'null') {
                        $session->date = User::convert_utc_to_user_timezone($user->id, $session->created_at)['date'];
                        $start_time = date('h:i A', strtotime('-15 minutes', strtotime($session->start_time)));
                        $start_time = User::convert_utc_to_user_timezone($user->id, $session->start_time)['time'];
                        $end_time = date('h:i A', strtotime('-15 minutes', strtotime($session->end_time)));
                        $end_time = User::convert_utc_to_user_timezone($user->id, $session->end_time)['time'];
                        $doc = User::where('id', $session['doctor_id'])->first();
                        $session->doc_name = $doc['name'] . " " . $doc['last_name'];
                        $patient_name = User::where('id', $session['patient_id'])->first();
                        $pat_name = $patient_name['name'] . " " . $patient_name['last_name'];
                        $links = AgoraAynalatics::where('channel', $session['channel'])->first();
                        $session->symptom_detail = DB::table('symptoms')->where('id',$session->symptom_id)->get(['symptoms_text','description']);
                        if ($links != null) {
                            $recording = $links->video_link;
                            $session->recording = $recording;
                        } else {
                            $session->recording = 'No recording';
                        }
                        $referred_doc = Referal::where('session_id', $session['id'])
                        ->where('patient_id', $user_id)
                        ->where('doctor_id', $doc->id)
                        ->leftjoin('users', 'referals.sp_doctor_id', 'users.id')
                        ->select('users.name', 'users.last_name')
                        ->get();
                        if (count($referred_doc)) {
                            $session->refered = "Dr." . $referred_doc[0]->name . " " . $referred_doc[0]->last_name;
                        } else {
                            $session->refered = null;
                        }
                        $pres = Prescription::where('session_id', $session['id'])->get();
                        $pres_arr = [];
                        foreach ($pres as $prod) {
                            if ($prod['type'] == 'medicine') {
                                $medicine['medicine_name'] = AllProducts::where('id', $prod['medicine_id'])->get(['name']);
                                $product = $medicine;   
                            } else if ($prod['type'] == 'imaging') {
                                $imaging_name['imaging_name'] = AllProducts::where('id', $prod['imaging_id'])->get(['name']);
                                $product = $imaging_name;   
                                $usage = DB::table('imaging_selected_location')
                                ->join('imaging_locations', 'imaging_selected_location.imaging_location_id', 'imaging_locations.id')
                                ->where('imaging_selected_location.session_id', $prod['session_id'])
                                ->where('imaging_selected_location.product_id', $prod['imaging_id'])
                                ->select('imaging_locations.address as location')
                                ->first();
                                $prod->usage = $usage->location;
                            } else if ($prod['type'] == 'lab-test') {
                                $lab_test['lab_test_name'] = QuestDataTestCode::where('TEST_CD', $prod['test_id'])->get(['TEST_NAME']);
                                $product = $lab_test;   
                            }
                            $cart = Cart::where('doc_session_id', $session['id'])->where('pres_id', $prod->id)->first();
                            $prod['prescription_detail'] = array($product);
                            // $prod->prod_detail = $product;
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
                $sessionData['code'] =200;
                $sessionData['user_type'] =$user_type;
                $sessionData['sessions'] =$sessions;
                $sessionData['start_time'] = $start_time;
                $sessionData['end_time'] = $end_time;
                $sessionData['pat_name'] = $pat_name;
                $sessionData['recording'] = $recording;
                $sessionData['refered_doc'] = $refered_doc;
                return $this->sendResponse($sessionData,"sessions found");
            } else{
                $sessionDataError['code'] =200;
                return $this->sendError($sessionDataError,"Opps something went wrong!");
            }
        }
    }
    public function session_searchByID(Request $request){
        $user = auth()->user();
        $user_type = $user->user_type;
        $user_time_zone = $user->timeZone;
        $user_state = Auth::user()->state_id;
        $state = State::find($user_state);
        if($request->session_id){
            if ($state->active == 1) {
                $user_id = $user->id;
                $sessions = Session::where('patient_id', $user_id)
                    ->where('status', 'ended')
                    ->where('session_id', $request->session_id)
                    ->orderByDesc('id')
                    ->get();
                if(!$sessions->isEmpty()){
                    foreach ($sessions as $session) {
                        if ($session->status == 'ended' && $session->start_time != 'null' && $session->end_time != 'null') {
                            $session->date = User::convert_utc_to_user_timezone($user->id, $session->created_at)['date'];
                            $session->start_time = date('h:i A', strtotime('-15 minutes', strtotime($session->start_time)));
                            $session->start_time = User::convert_utc_to_user_timezone($user->id, $session->start_time)['time'];
                            $session->end_time = date('h:i A', strtotime('-15 minutes', strtotime($session->end_time)));
                            $session->end_time = User::convert_utc_to_user_timezone($user->id, $session->end_time)['time'];
                            $doc = User::where('id', $session['doctor_id'])->first();
                            $session->doc_name = $doc['name'] . " " . $doc['last_name'];
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
                                // dd($cart);
                                $prod->prod_detail = $product;
                                if (isset($cart->status))
                                    $prod->cart_status = $cart->status;
                                else
                                    $prod->cart_status = 'No record';
                                // dd($prod);
                                array_push($pres_arr, $prod);
                            }
                            $session->pres = $pres_arr;
                            // array_push($sessions,$session);
                        }
                    }
                    if($session != ''){
                        return $this->sendResponse($session,"session found with session id");
                    } else{
                        return $this->sendError("No Session found",false);
                    }
                } else{
                    $sessionData['code'] = 200;
                    return $this->sendError($sessionData,"No Sessions ");    
                }
            }
        } else{
            $sessionData['code'] = 200;
            return $this->sendError($sessionData,"Session ID Required");
        }
    }
    public function session_searchByDatefilter(Request $request){
        $user = auth()->user();
        $user_type = $user->user_type;
        $user_time_zone = $user->timeZone;
        $user_state = Auth::user()->state_id;
        $state = State::find($user_state);
        if($request->datefilter != null){
            $start_date = explode(' - ',$request->datefilter)[0];
            $end_date = explode(' - ',$request->datefilter)[1];
            $start_date = date('Y-m-d', strtotime($start_date));
            $end_date = date('Y-m-d', strtotime($end_date));
            if ($state->active == 1) {
                $user_id = $user->id;
                $sessions = Session::where('patient_id', $user_id)
                    ->where('status', 'ended')
                    ->where('date', '>=' ,$start_date)
                    ->where('date', '<=' ,$end_date)
                    ->get();
                if(!$sessions->isEmpty()){
                    foreach ($sessions as $session) {
                        if ($session->status == 'ended' && $session->start_time != 'null' && $session->end_time != 'null') {
                            $session->date = User::convert_utc_to_user_timezone($user->id, $session->created_at)['date'];
                            $session->start_time = date('h:i A', strtotime('-15 minutes', strtotime($session->start_time)));
                            $session->start_time = User::convert_utc_to_user_timezone($user->id, $session->start_time)['time'];
                            $session->end_time = date('h:i A', strtotime('-15 minutes', strtotime($session->end_time)));
                            $session->end_time = User::convert_utc_to_user_timezone($user->id, $session->end_time)['time'];
                            $doc = User::where('id', $session['doctor_id'])->first();
                            $session->doc_name = $doc['name'] . " " . $doc['last_name'];
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
                    if($session != ''){
                        $sessionData['code'] = 200;
                        $sessionData['session'] = $session;
                        return $this->sendResponse($sessionData,"Session found with datefilter");
                    } else{
                        $sessionData['code'] = 200;
                        return $this->sendError($sessionData,"No Session found");
                    }
                } else{
                    $sessionData['code'] = 200;
                    return $this->sendError($sessionData,"No Session found");
                }
            }
        } else{
                $sessionData['code'] = 200;
                return $this->sendError($sessionData,"Date Filter Required");
        }
    }
    public function session_searchById_and_Datefilter(Request $request){
        $user = auth()->user();
        $user_type = $user->user_type;
        $user_time_zone = $user->timeZone;
        $user_state = Auth::user()->state_id;
        $state = State::find($user_state);
        if($request->session_id != null && $request->datefilter != null){
            $start_date = explode(' - ',$request->datefilter)[0];
            $end_date = explode(' - ',$request->datefilter)[1];
            $start_date = date('Y-m-d', strtotime($start_date));
            $end_date = date('Y-m-d', strtotime($end_date));
            if ($state->active == 1) {
                $user_id = $user->id;
                $sessions = Session::where('patient_id', $user_id)
                    ->where('status', 'ended')
                    ->where('session_id', $request->session_id)
                    ->where('date', '>=' ,$start_date)
                    ->where('date', '<=' ,$end_date)
                    ->get();
                if(!$sessions->isEmpty()){
                    foreach ($sessions as $session) {
                        if ($session->status == 'ended' && $session->start_time != 'null' && $session->end_time != 'null') {
                            $session->date = User::convert_utc_to_user_timezone($user->id, $session->created_at)['date'];
                            $session->start_time = date('h:i A', strtotime('-15 minutes', strtotime($session->start_time)));
                            $session->start_time = User::convert_utc_to_user_timezone($user->id, $session->start_time)['time'];
                            $session->end_time = date('h:i A', strtotime('-15 minutes', strtotime($session->end_time)));
                            $session->end_time = User::convert_utc_to_user_timezone($user->id, $session->end_time)['time'];
                            $doc = User::where('id', $session['doctor_id'])->first();
                            $session->doc_name = $doc['name'] . " " . $doc['last_name'];
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
                    if($session != ''){
                        return $this->sendResponse($sessions,"Search Result found with session id and date filter");
                    } else{
                        return $this->sendError("No Session found",false);
                    }
                } else{
                    $sessionDate['code'] =200;
                    return $this->sendError($sessionDate,"Result not found!");
                } 
            }
            
        }else{
           $sessionData['code'] = 200;
           return $this->sendError($sessionData,"Session id and Data filter is required");
        } 
    }
}
