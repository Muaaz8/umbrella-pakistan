<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Session;
use App\User;
use App\Helper;
use App\ActivityLog;
use App\AgoraAynalatics;
use App\Appointment;
use App\Symptom;
use App\Prescription;
use App\Pharmacy;
use App\Cart;
use App\Events\LoadPrescribeItemList;
use App\Events\patientEndCall;
use App\State;
use DateTime;
use DateTimeZone;
use App\Events\RealTimeMessage;
use App\Events\updateDoctorWaitingRoom;
use App\Events\updateQuePatient;
use App\Models\AllProducts;
use App\Models\MapMarkers;
use App\Models\PrescriptionsFile;
use App\ImagingLocations;
use App\ImagingPrices;
use App\Mail\patientEvisitInvitationMail;
use App\Mail\ReferDoctorToDoctorMail;
use App\Mail\ReferDoctorToPatientMail;
use App\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\QuestDataTestCode;
use App\Referal;
use App\Specialization;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SessionController extends Controller
{
    public function sessionCtatusCheck(Request $request)
    {
        $sessionStatus = Session::where('id', $request->session_id)->first();
        return response()->json(['data' => $sessionStatus]);
    }
    public function sendInvite(Request $request)
    {
        // update session status
        $res = Session::where('id', $request->session_id)->update(['status' => 'invitation sent']);
        if ($res == 1) {
            //get patient session record
            $sessionData = Session::where('id', $request->session_id)->first();
            $doc_name = Helper::get_name($sessionData->doctor_id);
            $pat_name = Helper::get_name($sessionData->patient_id);
            $notification_id = Notification::create([
                'user_id' => $sessionData->doctor_id,
                'text' => 'E-visit Joining Request Send by ' . $pat_name,
                'session_id' => $request->session_id,
                'type' => 'doctor/patient/queue',
            ]);
            $data = [
                'user_id' => $sessionData->doctor_id,
                'text' => 'E-visit Joining Request Send by ' . $pat_name,
                'session_id' => $request->session_id,
                'type' => 'doctor/patient/queue',
                'received' => 'false',
                'appoint_id' => 'null',
                'refill_id' => 'null',
            ];
            try {
                // \App\Helper::firebase($sessionData->doctor_id,'notification',$notification_id->id,$data);

            } catch (\Throwable $th) {
                //throw $th;
            }

            event(new RealTimeMessage($sessionData->doctor_id));
            event(new updateDoctorWaitingRoom('new_patient_listed'));
            try {
                // \App\Helper::firebase($sessionData->doctor_id,'updateDoctorWaitingRoom',$request->session_id,$sessionData);
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
            $getData = Session::where('id', $request->session_id)->where('patient_id', auth()->user()->id)->first();
            return response()->json(['data' => $getData]);
        } else {
            return false;
        }
    }
    public function all()
    {
        $user_type = auth()->user()->user_type;
        if ($user_type == 'patient') {
            $user_id = auth()->user()->id;
            $sessions = Session::where('patient_id', $user_id)->orderByDesc('id')->get();
            return view('sessions', compact('user_type', 'sessions'));
        }
    }
    public function all_sessions_record(Request $request)
    {
        // dd($request->all());
        $user_type = auth()->user()->user_type;
        if ($user_type == 'admin') {
            $user = Auth::user();
            $user_id = $user->id;
            if(isset($request->name)){
                $sessions = Session::where('status', 'ended')
                ->where('session_id',$request->name)
                ->where('remaining_time','!=','full')
                ->orderByDesc('id')->paginate(15);
            }else{
                $sessions = Session::where('status', 'ended')->where('remaining_time','!=','full')->orderByDesc('id')->paginate(15);
            }
            foreach ($sessions as $session) {
                $getPersentage = DB::table('doctor_percentage')->where('doc_id', $session['doctor_id'])->first();
                $doc_price = ($getPersentage->percentage / 100) * $session->price;
                $session->price = $session->price - $doc_price;

                if ($session->status == 'ended' && $session->start_time != 'null' && $session->end_time != 'null') {
                    $session->date = User::convert_utc_to_user_timezone($user->id, $session->start_time)['date'];
                    $session->date = $session->date;

                    $session->start_time = date('h:i A', strtotime('-15 minutes', strtotime($session->start_time)));
                    $session->start_time = User::convert_utc_to_user_timezone($user->id, $session->start_time)['time'];

                    $session->end_time = date('h:i A', strtotime('-15 minutes', strtotime($session->end_time)));
                    $session->end_time = User::convert_utc_to_user_timezone($user->id, $session->end_time)['time'];


                    $doc = User::where('id', $session['doctor_id'])->first();

                    $session->doc_name = !empty($doc) ?  $doc['name'] . " " . $doc['last_name'] : 'N/A';
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
                            ->where('doctor_id', $session['doctor_id'])
                            ->leftjoin('users', 'referals.sp_doctor_id', 'users.id')
                            ->select('users.name', 'users.last_name')
                            ->get();
                        if (count($referred_doc)) {
                            $session->refered = "Dr. ".$session->doc_name." Referred the Patient to Dr." . $referred_doc[0]->name . " " . $referred_doc[0]->last_name;
                        } else {
                            $session->refered = null;
                        }
                    $session->sympptoms = DB::table('symptoms')->where('id',$session['symptom_id'])->first();

                    $pres = Prescription::where('session_id', $session['id'])->get();
                    $pres_arr = [];
                    //dd($pres);
                    foreach ($pres as $prod) {
                        if ($prod['imaging_id'] != '0') {

                            $product = AllProducts::where('id', $prod['imaging_id'])->first()->toArray();
                            $usage = DB::table('imaging_selected_location')
                                ->join('imaging_locations', 'imaging_selected_location.imaging_location_id', 'imaging_locations.id')
                                ->where('imaging_selected_location.session_id', $prod['session_id'])
                                ->where('imaging_selected_location.product_id', $prod['imaging_id'])
                                ->select('imaging_locations.city as location')
                                ->first();
                            $prod->usage = $usage->location;
                        } else if ($prod['medicine_id'] != '0') {

                            $product = AllProducts::where('id', $prod['medicine_id'])->first()->toArray();
                        } else {
                            $product = QuestDataTestCode::where('TEST_CD', $prod['test_id'])
                                ->first()->toArray();
                        }
                        $cart = Cart::where('doc_session_id', $session['id'])
                            ->where('pres_id', $prod->id)->first();
                        // dd($cart);
                        $prod->prod_detail = $product;
                        if (isset($cart->status))
                            $prod->cart_status = $cart->status;
                        else
                            $prod->cart_status = 'Invalid';
                        // dd($prod);
                        array_push($pres_arr, $prod);
                    }
                    $session->pres = $pres_arr;
                    $session->pres_files = PrescriptionsFile::where(['session_id' => $session->id, 'status' => '1'])->get();
                    // array_push($sessions,$session);
                }
            }
            $specializations = DB::table('specializations')->where('status','1')->get();
            // $sessions=(object)$sessions;
            // dd($sessions);
            return view('dashboard_admin.all_sessions.index', compact('user_type', 'sessions','specializations'));
        }
    }

    public function all_sessions_record_with_spec(Request $request)
    {
        $user_type = auth()->user()->user_type;
        if ($user_type == 'admin') {
            $user = Auth::user();
            $user_id = $user->id;
            if($request->spec == "0"){
                $sessions = Session::where('status', 'ended')
                ->where('remaining_time','!=','full')
                ->orderByDesc('id')->paginate(15);
            }else{
                //$sessions = Session::where('status', 'ended')->where('remaining_time','!=','full')->orderByDesc('id')->paginate(15);
                $sessions = DB::table('sessions')
                ->join('users','users.id','sessions.doctor_id')
                ->where('users.specialization', $request->spec)
                ->where('sessions.status', 'ended')
                ->where('sessions.remaining_time','!=','full')
                ->orderByDesc('sessions.id')
                ->select('sessions.*')->paginate(15);
            }
            foreach ($sessions as $session) {
                $getPersentage = DB::table('doctor_percentage')->where('doc_id', $session->doctor_id)->first();
                $doc_price = ($getPersentage->percentage / 100) * $session->price;
                $session->price = $session->price - $doc_price;

                if ($session->status == 'ended' && $session->start_time != 'null' && $session->end_time != 'null') {
                    $session->date = User::convert_utc_to_user_timezone($user->id, $session->start_time)['date'];
                    $session->date = $session->date;

                    $session->start_time = date('h:i A', strtotime('-15 minutes', strtotime($session->start_time)));
                    $session->start_time = User::convert_utc_to_user_timezone($user->id, $session->start_time)['time'];

                    $session->end_time = date('h:i A', strtotime('-15 minutes', strtotime($session->end_time)));
                    $session->end_time = User::convert_utc_to_user_timezone($user->id, $session->end_time)['time'];


                    $doc = User::where('id', $session->doctor_id)->first();

                    $session->doc_name = !empty($doc) ?  $doc['name'] . " " . $doc['last_name'] : 'N/A';
                    $pat = User::where('id', $session->patient_id)->first();
                    $session->pat_name = $pat['name'] . " " . $pat['last_name'];
                    $links = AgoraAynalatics::where('channel', $session->channel)->first();
                    if ($links != null) {
                        $recording = $links->video_link;
                        $session->recording = $recording;
                    } else {
                        $session->recording = 'No recording';
                    }

                    $referred_doc = Referal::where('session_id', $session->id)
                            ->where('patient_id', $session->patient_id)
                            ->where('doctor_id', $session->doctor_id)
                            ->leftjoin('users', 'referals.sp_doctor_id', 'users.id')
                            ->select('users.name', 'users.last_name')
                            ->get();
                        if (count($referred_doc)) {
                            $session->refered = "Dr. ".$session->doc_name." Referred the Patient to Dr." . $referred_doc[0]->name . " " . $referred_doc[0]->last_name;
                        } else {
                            $session->refered = null;
                        }
                    $session->sympptoms = DB::table('symptoms')->where('id',$session->symptom_id)->first();

                    $pres = Prescription::where('session_id', $session->id)->get();
                    $pres_arr = [];
                    //dd($pres);
                    foreach ($pres as $prod) {
                        if ($prod['imaging_id'] != '0') {

                            $product = AllProducts::where('id', $prod['imaging_id'])->first()->toArray();
                            $usage = DB::table('imaging_selected_location')
                                ->join('imaging_locations', 'imaging_selected_location.imaging_location_id', 'imaging_locations.id')
                                ->where('imaging_selected_location.session_id', $prod['session_id'])
                                ->where('imaging_selected_location.product_id', $prod['imaging_id'])
                                ->select('imaging_locations.city as location')
                                ->first();
                            $prod->usage = $usage->location;
                        } else if ($prod['medicine_id'] != '0') {

                            $product = AllProducts::where('id', $prod['medicine_id'])->first()->toArray();
                        } else {
                            $product = QuestDataTestCode::where('TEST_CD', $prod['test_id'])
                                ->first()->toArray();
                        }
                        $cart = Cart::where('doc_session_id', $session->id)
                            ->where('pres_id', $prod->id)->first();
                        // dd($cart);
                        $prod->prod_detail = $product;
                        if (isset($cart->status))
                            $prod->cart_status = $cart->status;
                        else
                            $prod->cart_status = 'Invalid';
                        // dd($prod);
                        array_push($pres_arr, $prod);
                    }
                    $session->pres = $pres_arr;
                    $session->pres_files = PrescriptionsFile::where(['session_id' => $session->id, 'status' => '1'])->get();
                    // array_push($sessions,$session);
                }
            }
            // $sessions=(object)$sessions;
            // dd($sessions);
            return view('dashboard_admin.all_sessions.with_spec', compact('user_type', 'sessions'));
        }
    }
    public function evisit_specialization()
    {
        $user_state = Auth::user()->state_id;
        $state = State::find($user_state);
        if ($state->active == 1) {
            $spe = DB::table('specializations')
                ->join('specalization_price', 'specalization_price.spec_id', 'specializations.id')
                ->where('specializations.status', '1')
                ->where('specalization_price.state_id', $user_state)
                ->select('specializations.*', 'specalization_price.follow_up_price as follow_up_price', 'specalization_price.initial_price as initial_price')
                ->get();
            return view('patient.evisit_specialization', compact('spe'));
        } else {
            return redirect()->route('errors', '101');
        }
    }

    public function dash_evisit_locations()
    {
        if(auth()->user()->user_type=='patient')
        {
            $Reg_state = auth()->user()->state_id;
            $Reg_state = State::find($Reg_state);
            $locations = DB::table('states')->where('active','1')->orderBy('name','ASC')->get();
            foreach($locations as $loc)
            {
                $docs = DB::table('doctor_licenses')
                ->where('state_id',$loc->id)
                ->where('is_verified','1')
                ->count();

                if($docs>0)
                {
                    $loc->docs = 1;
                }
                else
                {
                    $loc->docs = 0;
                }
            }
            $locations = $locations->groupBy(function($item,$key) {
                return $item->name[0];     //treats the name string as an array
            });

            // $locations = DB::table('doctor_licenses')
            // ->join('states','doctor_licenses.state_id','states.id')
            // ->join('users', 'doctor_licenses.doctor_id', 'users.id')
            // ->where('doctor_licenses.is_verified','1')
            // ->where('users.active','1')
            // ->groupBy('doctor_licenses.state_id')
            // ->select('states.*')
            // ->orderBy('states.name','ASC')->get();

            return view('dashboard_patient.Evisit.locations', compact('locations','Reg_state'));
        }
        else
        {
            return redirect()->route('errors', '101');
        }

    }

    public function dash_evisit_specialization()
    {
        //$state = State::find($loc_id);
        if (auth()->user()->user_type=='patient') {
            // $Reg_state = auth()->user()->state_id;
            // $state = State::find($Reg_state);

            $spe = DB::table('specializations')
            ->join('specalization_price', 'specalization_price.spec_id', 'specializations.id')
            ->join('users', 'users.specialization', 'specializations.id')
            ->join('doctor_licenses', 'doctor_licenses.doctor_id', 'users.id')
            // ->where('specalization_price.state_id', $Reg_state)
            ->where('doctor_licenses.is_verified', '1')
            ->groupBy('specializations.id')
            ->select('specializations.*', 'specalization_price.follow_up_price as follow_up_price', 'specalization_price.initial_price as initial_price')
            ->get();

            $locations = DB::table('states')->where('active','1')->orderBy('name','ASC')->get();
            foreach($locations as $loc)
            {
                $docs = DB::table('doctor_licenses')
                ->where('state_id',$loc->id)
                ->where('is_verified','1')
                ->count();

                if($docs>0)
                {
                    $loc->docs = 1;
                }
                else
                {
                    $loc->docs = 0;
                }
            }
            return view('dashboard_patient.Evisit.index', compact('spe','locations'));
        } else {
            return redirect()->route('errors', '101');
        }
    }

    public function states_specialization(Request $request)
    {
        $Reg_state = $request->loc_id;
        $state = State::find($Reg_state);

        $spe = DB::table('specializations')
        ->join('specalization_price', 'specalization_price.spec_id', 'specializations.id')
        ->join('users', 'users.specialization', 'specializations.id')
        ->join('doctor_licenses', 'doctor_licenses.doctor_id', 'users.id')
        ->where('specalization_price.state_id', $Reg_state)
        ->where('doctor_licenses.is_verified', '1')
        ->groupBy('specializations.id')
        ->select('specializations.*', 'specalization_price.follow_up_price as follow_up_price', 'specalization_price.initial_price as initial_price')
        ->get();

        return view('dashboard_patient.Evisit.load_specs', compact('spe'));
    }

    public function show(Request $request)
    {
        // dd($request['id']);
        $id = $request['id'];
        $session = Session::find($id);
        $doctor = User::where('id', $session['doctor_id'])->first();
        $symptom = Symptom::find($session['symptom_id']);
        $pres = Prescription::where('session_id', $id)->get();
        $pres_arr = [];
        foreach ($pres as $prod) {
            $product = AllProducts::where('id', $prod['medicine_id'])->first();
            // dd($product);
            $prod->prod_detail = $product;
            // dd($prod);
            array_push($pres_arr, $prod);
        }
        $session->date = Helper::get_date_with_format($session->date);
        $session->start_time = ($session->start_time != null) ? Helper::get_time_with_format($session->start_time) : $session->start_time;
        $session->end_time = ($session->end_time != null) ? Helper::get_time_with_format($session->end_time) : $session->end_time;
        $session->doc_name = $doctor->name . " " . $doctor->last_name;
        $session->prescription = $pres_arr;
        $session->symptom = $symptom;
        // dd($session);
        if (isset($request['video']))
            return $session;
        return view('session_view')->with('session', $session);
    }
    public function get_patient_id(Request $request)
    {
        $id = $request['id'];
        $session = Session::find($id);
        return $session;
    }

    public function feedback(Request $request)
    {
        $session = $request['session'];
        return view('feedback')->with('session', $session);
    }
    public function feedback_submit(Request $request)
    {
        // dd($request);
        $session = $request['session_id'];
        Session::where('id', $session)->update([
            'patient_feedback' => $request['feedback'],
            'patient_rating' => $request['rating'],
            'patient_suggestions' => $request['suggestions'],
            'feedback_flag' => '1',
        ]);
        $currentRole = auth()->user()->user_type;
        $session = Session::find($session);
        // dd($session);
        $all_sessions = Session::where('doctor_id', $session['doctor_id'])->where('patient_rating', '!=', 'null')->get()->toArray();
        // dd($all_sessions);
        $ratings = array_column($all_sessions, 'patient_rating');
        if (count($ratings) == 0) {
            $average = 5;
        } else {
            $average1 = array_sum($ratings) / count($ratings);
            $average = number_format((float)$average1, 2, '.', '');
        }
        // dd($session['doctor_id'].'  '.$average);
        User::where('id', $session['doctor_id'])->update(['rating' => $average]);
        // dd($average);
        return redirect()->route('home')->with('currentRole', $currentRole);
    }

    public function new_feedback_submit(Request $request)
    {
        // dd($request);
        $session = $request['session_id'];
        Session::where('id', $session)->update([
            // 'patient_feedback' => $request['feedback'],
            'patient_rating' => $request['rate'],
            'patient_suggestions' => $request['suggestion'],
            'feedback_flag' => '1',
        ]);
        $currentRole = auth()->user()->user_type;
        $session = Session::find($session);
        // dd($session);
        $all_sessions = Session::where('doctor_id', $session['doctor_id'])->where('patient_rating', '!=', 'null')->get()->toArray();
        // dd($all_sessions);
        $ratings = array_column($all_sessions, 'patient_rating');
        if (count($ratings) == 0) {
            $average = 5;
        } else {
            $average1 = array_sum($ratings) / count($ratings);
            $average = number_format((float)$average1, 2, '.', '');
        }
        $percentage = ($average * 100)/5;
        // dd($session['doctor_id'].'  '.$average);
        User::where('id', $session['doctor_id'])->update(['rating' => $percentage]);
        // dd($average);
        return redirect()->route('home')->with('currentRole', $currentRole);
    }

    public function end_session_patient(Request $request)
    {
        $session_id = $request['session'];
        $session = Session::where('id', $session_id)->first();
        if($session->validation_status == "valid")
        {
            $doctor = User::find($session['doctor_id']);
            $session->doctor_name = $doctor['name'] . ' ' . $doctor['last_name'];

            // dd($session);
            return view('dashboard_patient.Waiting_room.waiting')->with('session', $session);
        }
        else
        {
            return redirect()->route('New_Patient_Dashboard');
        }
    }
    public function check_cart_status(Request $request)
    {
        // return $request['session_id'];
        $session_id = $request['session_id'];
        // return $session_id;
        $session = Session::where('id', $session_id)->first();
        // return $check;

        if ($session['cart_flag'] == '0' || $session['cart_flag'] == '1') {
            return 'added';
        } else {
            return 'wait';
        }
    }
    public function change_session_status(Request $request)
    {
        $id = $request['id'];
        Session::where('id', $id)->update(['status' => 'doctor joined']);
        $session = Session::find($id);
        $patient = User::find($session['patient_id']);
        ActivityLog::create([
            'activity' => 'joined session with ' . $patient['name'] . " " . $patient['last_name'],
            'type' => 'session start',
            'user_id' => $session['doctor_id'],
            'user_type' => 'doctor',
            'party_involved' => $session['patient_id']
        ]);
        return 'joined';
    }
    public function check_status(Request $request)
    {

        $session_id = $request['session_id'];
        $session = Session::where('id', $session_id)->first();

        // return $check;

        if ($session['status'] == 'doctor joined') {
            return 'join';
        } elseif ($session['status'] == 'ended') {
            return 'ended';
        } else {
            return 'waiting';
        }
    }
    public function check_status_video(Request $request)
    {
        $session_id = $request['session_id'];
        // return $request;
        $session = Session::where('id', $session_id)->first();
        Session::where('id', $request['session_id'])->update(['remaining_time' => $request['time']]);

        // return $check;

        if ($session['status'] == 'doctor joined') {
            return 'join';
        } elseif ($session['status'] == 'ended') {
            return 'ended';
        } else {
            return 'waiting';
        }
    }
    public function videoRecording($id)
    {

        $session = Session::where('id', $id)->first();
        $link = AgoraAynalatics::where('channel', $session->channel)->first();
        if ($link == null) {
            $videoLink = "Not Found Recording";
        } else {
            $videoLink = $link->video_link;
        }
        return view('video.videoPlay', compact('videoLink'));
    }



    public function VideoRecordSession($id)
    {

        $session = Session::where('id', $id)->first();
        $link = AgoraAynalatics::where('channel', $session->channel)->first();
        if ($link == null) {
            $videoLink = "Not Found Recording";
        } else {
            $videoLink = $link->video_link;
        }
        return view('video.videoPlay', compact('videoLink'));
    }
    public function set_session_start_time(Request $request)
    {
        $start_time = Carbon::now()->format('Y-m-d H:i:s');
        Session::where('id', $request['id'])->update([
            'start_time' => $start_time
        ]);
        return 'done';
    }
    public function doctor_end_session(Request $request)
    {

        $end_time = Carbon::now()->addMinutes(15)->format('Y-m-d H:i:s');
        Session::where('id', $request['id'])->update([
            'join_enable' => '0',
            'status' => 'ended',
            'end_time' => $end_time
        ]);

        $sessionData = Session::where('id', $request['id'])->first();

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
            'identity' => $request['id'],
            'party_involved' => $sessionData->patient_id,
        ]);

        ActivityLog::create([
            'activity' => 'ended session with Dr.' . $doctorDetail->name . " " . $doctorDetail->last_name,
            'type' => 'session end',
            'user_id' => $patientDetail->id,
            'user_type' => 'doctor',
            'identity' => $request['id'],
            'party_involved' => $sessionData->doctor_id,
        ]);


        //mailgun refer email send to doctor//////
        $referData = DB::table('referals')->where('session_id', $request['id'])->first();
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
            // notification Comment
            // Notification::create([
            //     'user_id' => $referData->sp_doctor_id,
            //     'text' => 'New patient is refered to you from Dr.' . $ref_from_user->name,
            //     'type' => '/patient_record/' . $referData->patient_id,
            //     'status' => 'new',
            //     'session_id' => $request['id'],
            // ]);

            // Notification::create([
            //     'user_id' => $referData->patient_id,
            //     'text' => 'New doctor is refered to you.',
            //     'type' => '/book/appointment/' . $ref_to_user->specialization,
            //     'status' => 'new',
            //     'session_id' => $request['id'],
            // ]);
            // event(new RealTimeMessage($referData->patient_id));
            // event(new RealTimeMessage($referData->sp_doctor_id));
        }
        try {

            $sessionData->received = false;
            // \App\Helper::firebase($sessionData->patient_id,'patientEndCall',$sessionData->id,$sessionData);
        } catch (\Throwable $th) {
            //throw $th;
        }

        event(new patientEndCall($request['id']));

        $firebase_ses = Session::where('id', $request['id'])->first();
        // \App\Helper::firebase($firebase_ses->doctor_id,'updateQuePatient',$firebase_ses->id,$firebase_ses);
        event(new updateQuePatient('update patient que'));
        Log::info('run que update event');
        return "done";
    }
    public function record(Request $request)
    {
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
                    ->paginate(15);
                // $sessions=[];
                foreach ($sessions as $session) {
                    if ($session->start_time != 'null' && $session->end_time != 'null') {
                        $session->date = Helper::get_date_with_format($session->date);

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
                            } else if ($prod['type'] == 'lab-test') {
                                $product = QuestDataTestCode::where('TEST_CD', $prod['test_id'])->first();
                            }

                            $cart = Cart::where('doc_session_id', $session['id'])->where('pres_id', $prod->id)->first();
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
                //  dd($sessions);
                return view('session.sessions_full', compact('user_type', 'sessions'));
            } else {
                return redirect()->route('errors', '101');
            }
        } else if ($user_type == 'doctor') {
            $user_state = Auth::user()->state_id;
            $state = State::find($user_state);
            if ($state->active == 1) {
                $user_id = $user->id;
                $sessions = Session::where('doctor_id', $user_id)
                    ->where('status', 'ended')
                    ->where('remaining_time','!=','full')
                    ->orderByDesc('id')
                    ->paginate(15);
                // $sessions=[];
                foreach ($sessions as $session) {

                    $getPersentage = DB::table('doctor_percentage')->where('doc_id', $user_id)->first();
                    $session->price = ($getPersentage->percentage / 100) * $session->price;

                    if ($session->status == 'ended' && $session->start_time != 'null' && $session->end_time != 'null') {
                        $session->date = Helper::get_date_with_format($session->date);

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
                // dd($sessions);

                // return view('session.sessions_full',['sessions'=>$sessions,'user_type'=>$user_type]);

                return view('session.sessions_full', compact('user_type', 'sessions'));
            } else {
                return redirect()->route('errors', '101');
            }
        } else if ($user_type == 'admin') {
            $user_id = $user->id;
            $sessions = Session::where('status', 'ended')->where('remaining_time','!=','full')->orderByDesc('id')->paginate(15);
            foreach ($sessions as $session) {
                $getPersentage = DB::table('doctor_percentage')->where('doc_id', $session['doctor_id'])->first();
                $doc_price = ($getPersentage->percentage / 100) * $session->price;
                $session->price = $session->price - $doc_price;

                if ($session->status == 'ended' && $session->start_time != 'null' && $session->end_time != 'null') {
                    $session->date = User::convert_utc_to_user_timezone($user->id, $session->start_time)['datetime'];

                    $session->start_time = date('h:i A', strtotime('-15 minutes', strtotime($session->start_time)));
                    $session->start_time = User::convert_utc_to_user_timezone($user->id, $session->start_time)['time'];

                    $session->end_time = date('h:i A', strtotime('-15 minutes', strtotime($session->end_time)));
                    $session->end_time = User::convert_utc_to_user_timezone($user->id, $session->end_time)['time'];


                    $doc = User::where('id', $session['doctor_id'])->first();

                    $session->doc_name = !empty($doc) ?  $doc['name'] . " " . $doc['last_name'] : 'N/A';
                    $pat = User::where('id', $session['patient_id'])->first();
                    $session->pat_name = $pat['name'] . " " . $pat['last_name'];
                    $links = AgoraAynalatics::where('channel', $session['channel'])->first();
                    if ($links != null) {
                        $recording = $links->video_link;
                        $session->recording = $recording;
                    } else {
                        $session->recording = 'No recording';
                    }

                    $pres = Prescription::where('session_id', $session['id'])->get();
                    $pres_arr = [];
                    //dd($pres);
                    foreach ($pres as $prod) {
                        if ($prod['imaging_id'] != '0') {

                            $product = AllProducts::where('id', $prod['imaging_id'])->first()->toArray();
                            $usage = DB::table('imaging_selected_location')
                                ->join('imaging_locations', 'imaging_selected_location.imaging_location_id', 'imaging_locations.id')
                                ->where('imaging_selected_location.session_id', $prod['session_id'])
                                ->where('imaging_selected_location.product_id', $prod['imaging_id'])
                                ->select('imaging_locations.city as location')
                                ->first();
                            $prod->usage = $usage->location;
                        } else if ($prod['medicine_id'] != '0') {

                            $product = AllProducts::where('id', $prod['medicine_id'])->first()->toArray();
                        } else {
                            $product = QuestDataTestCode::where('TEST_CD', $prod['test_id'])
                                ->first()->toArray();
                        }
                        $cart = Cart::where('doc_session_id', $session['id'])
                            ->where('pres_id', $prod->id)->first();
                        // dd($cart);
                        $prod->prod_detail = $product;
                        if (isset($cart->status))
                            $prod->cart_status = $cart->status;
                        else
                            $prod->cart_status = 'Invalid';
                        // dd($prod);
                        array_push($pres_arr, $prod);
                    }
                    $session->pres = $pres_arr;
                    $session->pres_files = PrescriptionsFile::where(['session_id' => $session->id, 'status' => '1'])->get();
                    // array_push($sessions,$session);
                }
            }
            // $sessions=(object)$sessions;
            // dd($sessions);
            return view('session.sessions_full', compact('user_type', 'sessions'));
        }
    }
    public function dash_pat_record(Request $request)
    {
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
                    ->paginate(7);
                // $sessions=[];
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
                        $session->sympptoms = DB::table('symptoms')->where('id',$session['symptom_id'])->first();

                        foreach ($pres as $prod) {


                            if ($prod['type'] == 'medicine') {
                                $product = AllProducts::where('id', $prod['medicine_id'])->first();
                            } else if ($prod['type'] == 'imaging') {
                                $product = AllProducts::where('id', $prod['imaging_id'])->first();
                                $usage = DB::table('imaging_selected_location')
                                ->join('imaging_locations', 'imaging_selected_location.imaging_location_id', 'imaging_locations.id')
                                ->where('imaging_selected_location.session_id', $prod['session_id'])
                                ->where('imaging_selected_location.product_id', $prod['imaging_id'])
                                ->select('imaging_locations.city as location')
                                ->first();
                                $prod->usage = $usage->location;
                            } else if ($prod['type'] == 'lab-test') {
                                $product = QuestDataTestCode::where('TEST_CD', $prod['test_id'])->first();
                            }

                            $cart = Cart::where('doc_session_id', $session['id'])->where('pres_id', $prod->id)->first();
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
                //  dd($sessions);
                return view('dashboard_patient.Session.index', compact('user_type', 'sessions'));
            } else {
                return redirect()->route('errors', '101');
            }
        }
    }
    public function dash_record(Request $request)
    {
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
                    ->paginate(7);
                // $sessions=[];
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
                                $usage = DB::table('imaging_selected_location')
                                ->join('imaging_locations', 'imaging_selected_location.imaging_location_id', 'imaging_locations.id')
                                ->where('imaging_selected_location.session_id', $prod['session_id'])
                                ->where('imaging_selected_location.product_id', $prod['imaging_id'])
                                ->select('imaging_locations.city as location')
                                ->first();
                                $prod->usage = $usage->location;
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
                // dd($sessions);

                // return view('session.sessions_full',['sessions'=>$sessions,'user_type'=>$user_type]);

                return view('dashboard_doctor.All_Session.index', compact('user_type', 'sessions'));
            } else {
                return redirect()->route('errors', '101');
            }
        } else if ($user_type == 'admin') {
            $user_id = $user->id;
            $sessions = Session::where('status', 'ended')->orderByDesc('id')->paginate(7);
            foreach ($sessions as $session) {
                $getPersentage = DB::table('doctor_percentage')->where('doc_id', $session['doctor_id'])->first();
                $doc_price = ($getPersentage->percentage / 100) * $session->price;
                $session->price = $session->price - $doc_price;

                if ($session->status == 'ended' && $session->start_time != 'null' && $session->end_time != 'null') {
                    $session->date = User::convert_utc_to_user_timezone($user->id, $session->created_at)['date'];
                    if ($session->start_time == null) {
                        $session->start_time = Helper::get_time_with_format($session->created_at);
                    } else {

                        $session->start_time = User::convert_utc_to_user_timezone($user->id, $session->start_time)['time'];

                        // $date = new DateTime($session->start_time);
                        // $date->setTimezone(new DateTimeZone($user_time_zone));
                        // $session->start_time = $date->format('h:i:s A');
                    }
                    if ($session->end_time == null) {
                        $session->end_time = Helper::get_time_with_format($session->updated_at);
                    } else {

                        $session->end_time = User::convert_utc_to_user_timezone($user->id, $session->end_time)['time'];

                        // $date = new DateTime($session->end_time);
                        // $date->setTimezone(new DateTimeZone($user_time_zone));
                        // $session->end_time = $date->format('h:i:s A');
                    }

                    // dd($session->end_time);
                    $doc = User::where('id', $session['doctor_id'])->first();

                    $session->doc_name = !empty($doc) ?  $doc['name'] . " " . $doc['last_name'] : 'N/A';
                    $pat = User::where('id', $session['patient_id'])->first();
                    $session->pat_name = $pat['name'] . " " . $pat['last_name'];
                    $links = AgoraAynalatics::where('channel', $session['channel'])->first();
                    if ($links != null) {
                        $recording = $links->video_link;
                        $session->recording = $recording;
                    } else {
                        $session->recording = 'No recording';
                    }
                    $session->sympptoms = DB::table('symptoms')->where('id',$session['symptom_id'])->first();

                    $pres = Prescription::where('session_id', $session['id'])->get();
                    $pres_arr = [];
                    // dd($pres);
                    foreach ($pres as $prod) {
                        if ($prod['imaging_id'] != '0') {

                            $product = AllProducts::where('id', $prod['imaging_id'])->first()->toArray();
                        } else if ($prod['medicine_id'] != '0') {

                            $product = AllProducts::where('id', $prod['medicine_id'])->first()->toArray();
                        } else {
                            $product = QuestDataTestCode::where('TEST_CD', $prod['test_id'])
                                ->first()->toArray();
                        }
                        $cart = Cart::where('doc_session_id', $session['id'])
                            ->where('pres_id', $prod->id)->first();
                        // dd($cart);
                        $prod->prod_detail = $product;
                        if (isset($cart->status))
                            $prod->cart_status = $cart->status;
                        else
                            $prod->cart_status = 'Invalid';
                        // dd($prod);
                        array_push($pres_arr, $prod);
                    }
                    $session->pres = $pres_arr;
                    $session->pres_files = PrescriptionsFile::where(['session_id' => $session->id, 'status' => '1'])->get();
                    // array_push($sessions,$session);
                }
            }
            // $sessions=(object)$sessions;
            // dd($sessions);
            return view('dashboard_doctor.All_Session.index', compact('user_type', 'sessions'));
        }
    }
    public function get_med_detail(Request $request)
    {
        $response['product'] = \DB::table('tbl_products')
            ->join('medicine_pricings', 'medicine_pricings.product_id', '=', 'tbl_products.id')
            ->where('tbl_products.id', $request['id'])
            ->select('tbl_products.id', 'tbl_products.name', 'medicine_pricings.price', 'medicine_pricings.sale_price')
            ->first();
        $response['days'] = \DB::table('tbl_products')
            ->join('medicine_pricings', 'medicine_pricings.product_id', '=', 'tbl_products.id')
            ->join('medicine_days', 'medicine_days.id', '=', 'medicine_pricings.days_id')
            ->groupBy('medicine_days.id')
            ->where('tbl_products.id', $request['id'])
            ->select('medicine_days.id', 'medicine_days.days', 'medicine_pricings.price', 'medicine_pricings.sale_price')
            ->get();
        $response['units'] = \DB::table('tbl_products')
            ->join('medicine_pricings', 'medicine_pricings.product_id', '=', 'tbl_products.id')
            ->join('medicine_units', 'medicine_units.id', '=', 'medicine_pricings.unit_id')
            ->groupBy('medicine_units.id')
            ->where('tbl_products.id', $request['id'])
            ->select('medicine_units.id', 'medicine_units.unit', 'medicine_pricings.price', 'medicine_pricings.sale_price')
            ->get();
        $res = Prescription::where('session_id', $request['session_id'])->where('medicine_id', $request['id'])->first();

        if ($res->med_days != null && $res->med_unit != null && $res->med_time != null) {
            $response['update'] = ['days' => $res->med_days, 'units' => $res->med_unit, 'time' => $res->med_time, 'comment' => $res->comment];
        }
        return $response;
    }
    public function add_dosage(Request $request)
    {
        $res = Prescription::where('session_id', $request['session_id'])->where('medicine_id', $request['pro_id'])->update([
            'med_days' => $request['days'],
            'med_unit' => $request['units'],
            'med_time' => $request['med_time'],
            'price' => $request['price'],
            'comment' => $request['instructions'],
            'usage' => 'Dosage: Every ' . $request['med_time'] . ' hours for ' . $request['days'],
        ]);
        if ($res) {
            return redirect()->back();
            return "ok";
        }
    }
    public function get_medicine_price(Request $request)
    {
        return \DB::table('medicine_pricings')
            ->where('product_id', $request['product_id'])
            ->where('unit_id', $request['unit_id'])
            ->where('days_id', $request['day_id'])
            ->select('price', 'sale_price')
            ->first();
    }
    public function update_rem_time(Request $request)
    {
        // return $request['time'];
        Session::where('id', $request['session_id'])->update(['remaining_time' => $request['time']]);
    }
    public function get_locations(Request $request)
    {
        $phar = new Pharmacy();
        $data['data'] = $phar->get_lat_long_of_zipcode($request['zip']);
        $data1 = $phar->get_nearby_places($data['data'][0]->lat, $data['data'][0]->long);
        return $data1;
    }
    public function get_locations_imaging(Request $request)
    {
        $phar = new Pharmacy();
        $data['data'] = $phar->get_lat_long_of_zipcode_imaging($request['zip']);
        if ($data['data']->count() != 0) {
            $counter = 0;
            $locations = $phar->get_nearby_places_imaging($data['data'][0]->lat, $data['data'][0]->long);
            foreach ($locations as  $location) {
                $res = ImagingPrices::where('location_id', $location->id)->where('product_id', $request['pro_id'])->first();
                if ($res == null) {
                    unset($locations[$counter]);
                }
            }
            return ['locations' => $locations, 'user_id' => $request['user_id']];
        } else {
            return 'no location found';
        }
    }
    public function get_marker_by_id(Request $request)
    {
        $marker = MapMarkers::where('id', $request['id'])->first();
        return $marker;
    }
    public function get_marker_by_id_imaging(Request $request)
    {
        // services which are not available for
        $res = DB::table('imaging_selected_location')->where('session_id', $request['session_id'])->where('product_id', $request['pro_id'])->first();
        if ($res != null) {
            DB::table('imaging_selected_location')->where('id', $res->id)->update([
                'session_id' => $request['session_id'],
                'product_id' => $request['pro_id'],
                'imaging_location_id' => $request['location_id']
            ]);
            event(new LoadPrescribeItemList($request['session_id'], $request['user_id']));
        } else {
            DB::table('imaging_selected_location')->insert([
                'session_id' => $request['session_id'],
                'product_id' => $request['pro_id'],
                'imaging_location_id' => $request['location_id']
            ]);
            event(new LoadPrescribeItemList($request['session_id'], $request['user_id']));
        }
        //$marker = ImagingLocations::find($request['id']);
        //$response['locations'] = $marker;
        return 'ok';
    }
    public function update_feedback_status(Request $request)
    {
        Session::where('id', $request['id'])->update(['feedback_flag' => '1']);
        return 'done';
    }
    public function minute_rem_timer(Request $request)
    {
        Session::where('id', $request['session'])->update(['less_min_flag' => '1']);
    }
    public function session_detail_current(Request $request)
    {
        $session = Session::find($request['id']);
        $session->date = Helper::get_date_with_format($session->date);
        if ($session->start_time == null) {
            $session->start_time = Helper::get_time_with_format($session->created_at);
        } else {
            $session->start_time = Helper::get_time_with_format($session->start_time);
        }
        if ($session->end_time == null) {
            $session->end_time = Helper::get_time_with_format($session->updated_at);
        } else {
            $session->end_time = Helper::get_time_with_format($session->end_time);
        }
        // dd($session->end_time);
        $doc = User::where('id', $session['doctor_id'])->first();
        $session->doc_name = !empty($doc) ? $doc['name'] . ' ' . $doc['last_name']  : 'N/A';
        $pres = Prescription::where('session_id', $session['id'])->get();
        $pres_arr = [];
        // dd($pres);
        foreach ($pres as $prod) {
            if ($prod['medicine_id'] != 0) {
                $product = AllProducts::find($prod['medicine_id']);
            } else {
                $product = QuestDataTestCode::where('TEST_CD', $prod['test_id'])->first();
            }
            // dd($prod);
            if ($product->mode == 'lab-test') {
                $product->id = $product->TEST_CD;
                // dd($product);
            }
            $cart = Cart::where('pres_id', $prod['id'])
                ->where('product_id', $product->id)
                ->where('refill_flag', '0')
                ->first();

            if (isset($cart['status']))
                $prod->cart_status = $cart['status'];
            else
                $prod->cart_status = '';
            $prod->prod_detail = $product;
            // dd($prod);
            array_push($pres_arr, $prod);
        }
        // die;
        $session->pres = $pres_arr;
        $user_type = auth()->user()->user_type;
        // dd($session);
        return view('patient.pharmacy.session_details', compact('session', 'user_type'));
    }

    public function dash_session_detail_current(Request $request)
    {
        $user = Auth::user();
        $session = Session::find($request['id']);
        // $session->date = Helper::get_date_with_format($session->date);
        $session->date = User::convert_utc_to_user_timezone($user->id, $session->created_at)['date'];
        if ($session->start_time == null) {
            $session->start_time = Helper::get_time_with_format($session->created_at);
        } else {
            // $session->start_time = Helper::get_time_with_format($session->start_time);
            $session->start_time = date('h:i A', strtotime('-15 minutes', strtotime($session->start_time)));
            $session->start_time = User::convert_utc_to_user_timezone($user->id, $session->start_time)['time'];
        }
        if ($session->end_time == null) {
            $session->end_time = Helper::get_time_with_format($session->updated_at);
        } else {
            // $session->end_time = Helper::get_time_with_format($session->end_time);
            $session->end_time = date('h:i A', strtotime('-15 minutes', strtotime($session->end_time)));
            $session->end_time = User::convert_utc_to_user_timezone($user->id, $session->end_time)['time'];

        }
        // dd($session->end_time);
        $user_id = $session->patient_id;
        $doctor_id = $session->doctor_id;
        $referred_doc = Referal::where('session_id', $session['id'])
            ->where('patient_id', $user_id)
            ->where('doctor_id', $doctor_id)
            ->leftjoin('users', 'referals.sp_doctor_id', 'users.id')
            ->select('users.name', 'users.last_name')
            ->get();
        if (count($referred_doc)) {
            $session->refered = "Dr." . $referred_doc[0]->name . " " . $referred_doc[0]->last_name;
        } else {
            $session->refered = null;
        }

        $doc = User::where('id', $session['doctor_id'])->first();
        $session->doc_name = !empty($doc) ? $doc['name'] . ' ' . $doc['last_name']  : 'N/A';
        $pres = Prescription::where('session_id', $session['id'])->get();
        $pres_arr = [];
        // dd($pres);
        foreach ($pres as $prod) {
            if ($prod['medicine_id'] != 0) {
                $product = AllProducts::find($prod['medicine_id']);
            } elseif($prod['imaging_id'] != 0){
                $product = AllProducts::find($prod['imaging_id']);
            } else {
                $product = QuestDataTestCode::where('TEST_CD', $prod['test_id'])->first();
            }
            // dd($prod);
            if ($product->mode == 'lab-test') {
                $product->id = $product->TEST_CD;
                // dd($product);
            }
            $cart = Cart::where('pres_id', $prod['id'])
                ->where('product_id', $product->id)
                ->where('refill_flag', '0')
                ->first();

            if (isset($cart['status']))
                $prod->cart_status = $cart['status'];
            else
                $prod->cart_status = '';
            $prod->prod_detail = $product;
            // dd($prod);
            array_push($pres_arr, $prod);
        }
        // die;
        $session->pres = $pres_arr;
        $user_type = auth()->user()->user_type;
        // dd($session);
        if($user_type == "doctor")
        {
            $percentage = DB::table('doctor_percentage')->where('doc_id',$doc->id)->first();
            $session->price = ($session->price * $percentage->percentage)/100;
            return view('dashboard_doctor.Refill.Session_Details', compact('session', 'user_type'));
        }
        else
        {
            return view('dashboard_doctor.Refill.sessionDetail', compact('session', 'user_type'));
        }
    }
    public function filter_session_record($from_date, $to_date)
    {
        $user_type = auth()->user()->user_type;
        $user_time_zone = auth()->user()->timeZone;
        $user_id = auth()->user()->id;
        if ($user_type == 'patient') {
            $sessions = Session::where('patient_id', $user_id)->where('status', 'ended')->whereBetween(DB::raw('DATE(created_at)'), array($from_date, $to_date))->get();
            foreach ($sessions as $session) {
                if ($session->start_time != 'null' && $session->end_time != 'null') {
                    $session->date = Helper::get_date_with_format($session->date);
                    if ($session->start_time == null) {
                        $session->start_time = Helper::get_time_with_format($session->created_at);
                    } else {
                        $date = new DateTime($session->start_time);
                        $date->setTimezone(new DateTimeZone($user_time_zone));
                        $session->start_time = $date->format('h:i:s A');
                    }
                    if ($session->end_time == null) {
                        $session->end_time = Helper::get_time_with_format($session->updated_at);
                    } else {
                        $date = new DateTime($session->end_time);
                        $date->setTimezone(new DateTimeZone($user_time_zone));
                        $session->end_time = $date->format('h:i:s A');
                    }

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
                    foreach ($pres as $prod) {
                        if ($prod['test_id'] == '0') {
                            $product = AllProducts::where('id', $prod['medicine_id'])->first()->toArray();
                        } else {
                            $product = QuestDataTestCode::where('TEST_CD', $prod['test_id'])->first()->toArray();
                        }
                        $cart = Cart::where('doc_session_id', $session['id'])->where('pres_id', $prod->id)->first();
                        $prod->prod_detail = $product;
                        if (isset($cart->status)) {
                            $prod->cart_status = $cart->status;
                        } else {
                            $prod->cart_status = 'No record';
                        }
                        array_push($pres_arr, $prod);
                    }
                    $session->pres = $pres_arr;
                }
            }
        } else if ($user_type == 'doctor') {
            $sessions = Session::where('doctor_id', $user_id)->where('status', 'ended')->whereBetween(DB::raw('DATE(created_at)'), array($from_date, $to_date))->orderByDesc('id')->paginate(15);
            foreach ($sessions as $session) {
                if ($session->status == 'ended' && $session->start_time != 'null' && $session->end_time != 'null') {
                    $session->date = Helper::get_date_with_format($session->date);
                    if ($session->start_time == null) {
                        $session->start_time = Helper::get_time_with_format($session->created_at);
                    } else {
                        $date = new DateTime($session->start_time);
                        $date->setTimezone(new DateTimeZone($user_time_zone));
                        $session->start_time = $date->format('h:i:s A');
                    }
                    if ($session->end_time == null) {
                        $session->end_time = Helper::get_time_with_format($session->updated_at);
                    } else {
                        $date = new DateTime($session->end_time);
                        $date->setTimezone(new DateTimeZone($user_time_zone));
                        $session->end_time = $date->format('h:i:s A');
                    }

                    $pat = User::where('id', $session['patient_id'])->first();
                    $session->pat_name = $pat['name'] . " " . $pat['last_name'];
                    $links = AgoraAynalatics::where('channel', $session['channel'])->first();

                    if ($links != null) {
                        $recording = $links->video_link;
                        $session->recording = $recording;
                    } else {
                        $session->recording = 'No recording';
                    }

                    $pres = Prescription::where('session_id', $session['id'])->get();
                    $pres_arr = [];
                    foreach ($pres as $prod) {
                        if ($prod['test_id'] == '0') {
                            $product = AllProducts::where('id', $prod['medicine_id'])->first()->toArray();
                        } else {
                            $product = QuestDataTestCode::where('TEST_CD', $prod['test_id'])->first()->toArray();
                        }
                        $cart = Cart::where('doc_session_id', $session['id'])->where('pres_id', $prod->id)->first();
                        $prod->prod_detail = $product;
                        if (isset($cart->status)) {
                            $prod->cart_status = $cart->status;
                        } else {
                            $prod->cart_status = 'No record';
                        }
                        array_push($pres_arr, $prod);
                    }
                    $session->pres = $pres_arr;
                }
            }
        } else if ($user_type == 'admin') {
            $sessions = Session::where('status', 'ended')->whereBetween(DB::raw('DATE(created_at)'), array($from_date, $to_date))->get();;
            foreach ($sessions as $session) {
                if ($session->status == 'ended' && $session->start_time != 'null' && $session->end_time != 'null') {
                    $session->date = Helper::get_date_with_format($session->date);
                    if ($session->start_time == null) {
                        $session->start_time = Helper::get_time_with_format($session->created_at);
                    } else {
                        $date = new DateTime($session->start_time);
                        $date->setTimezone(new DateTimeZone($user_time_zone));
                        $session->start_time = $date->format('h:i:s A');
                    }
                    if ($session->end_time == null) {
                        $session->end_time = Helper::get_time_with_format($session->updated_at);
                    } else {
                        $date = new DateTime($session->end_time);
                        $date->setTimezone(new DateTimeZone($user_time_zone));
                        $session->end_time = $date->format('h:i:s A');
                    }
                    $doc = User::where('id', $session['doctor_id'])->first();
                    $session->doc_name = $doc['name'] . " " . $doc['last_name'];
                    $pat = User::where('id', $session['patient_id'])->first();
                    $session->pat_name = $pat['name'] . " " . $pat['last_name'];
                    $links = AgoraAynalatics::where('channel', $session['channel'])->first();
                    if ($links != null) {
                        $recording = $links->video_link;
                        $session->recording = $recording;
                    } else {
                        $session->recording = 'No recording';
                    }
                    $pres = Prescription::where('session_id', $session['id'])->get();
                    $pres_arr = [];
                    foreach ($pres as $prod) {
                        $product = AllProducts::where('id', $prod['medicine_id'])->first();
                        $cart = Cart::where('doc_session_id', $session['id'])->where('pres_id', $prod->id)->first();
                        $prod->prod_detail = $product;
                        if (isset($cart->status)) {
                            $prod->cart_status = $cart->status;
                        } else {
                            $prod->cart_status = 'No record';
                        }
                        array_push($pres_arr, $prod);
                    }
                    $session->pres = $pres_arr;
                }
            }
        }
        return view('session_filter', compact('user_type', 'sessions'));
    }
}
