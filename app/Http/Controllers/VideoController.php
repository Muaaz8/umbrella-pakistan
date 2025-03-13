<?php

namespace App\Http\Controllers;

use App\ActivityLog;
use App\Events\DoctorJoinedVideoSession;
use App\Events\patientJoinCall;
use App\Events\RealTimeMessage;
use App\Events\updateDoctorWaitingRoom;
use App\Events\updateQuePatient;
use App\Http\Controllers\Controller;
use App\Mail\EvisitStart;
use App\MedicalProfile;
use App\Models\AllProducts;
use App\Models\ProductCategory;
use App\Models\ProductsSubCategory;
use App\Notification;
use App\QuestDataAOE;
use App\QuestDataTestCode;
use App\Referal;
use App\Session;
use App\Specialization;
use App\Symptom;
use App\User;
use App\VideoLinks;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class VideoController extends Controller
{
    public function get_specializations_doctors(Request $request)
    {
        if($request->ajax()){
            $spec_docs = DB::table('users')
                ->where('user_type', 'doctor')
                ->where('id', '!=', $request->doctor_id)
                ->where('specialization', $request->spec)
                ->where('status', '!=', 'ban')
                ->where('active', 1)
                ->paginate(4);

            $referBtn = 0;
            foreach ($spec_docs as $doc) {
                $refered = Referal::where('session_id', $request->session_id)->where('sp_doctor_id', $doc->id)->first();
                if ($refered != null) {
                    $doc->refered = true;
                    $referBtn = 1;
                    $doc->refer_id = $refered->id;
                } else {
                    $doc->refered = false;
                }
                $doc->user_image = \App\Helper::check_bucket_files_url($doc->user_image);
            }
            return view('dashboard_doctor.Video.refer_paginate', compact('spec_docs','referBtn'));
        }
    }

    public function doctorVideo($session_id)
    {
        $session_id = \Crypt::decrypt($session_id);
        $session = Session::where('id', $session_id)->first();
        if($session->status == "doctor joined" || $session->status == "started")
        {
            $specializations = DB::table('specializations')->where('status', '1')->get();
            $session = Session::where('id', $session_id)->first();
            $patient = User::find($session->patient_id);
            $birthDate = explode("-", $patient->date_of_birth);
            $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[2], $birthDate[0]))) > date("md") ? ((date("Y") - $birthDate[0]) - 1) : (date("Y") - $birthDate[0]));
            $patient->date_of_birth = $age;


            $med = DB::table('products_sub_categories')->where('parent_id', '38')
            ->join('tbl_products','products_sub_categories.id','tbl_products.sub_category')
            ->select('products_sub_categories.*')
            ->groupBy('tbl_products.sub_category')
            ->get();
            // dd($med);
            $img = DB::table('product_categories')->where('category_type', 'imaging')->get();
            $files = DB::table('medical_records')->where('user_id',$patient->id)->get();
            foreach($files as $file)
            {
                $file->record_file = \App\Helper::get_files_url($file->record_file);
            }
            // if(count($file)>0){
            //     for
            //     $file = \App\Helper::get_files_url($patient->med_record_file);
            // }else{
            //     $file = "";
            // }
            $nowTime = strtotime(Carbon::now()->format('Y-m-d H:i:s'));
            $end_time = strtotime($session->start_time);
            if ($end_time > $nowTime) {
                $diff = $end_time - $nowTime;
                $getTime = CarbonInterval::seconds($diff)->cascade()->forHumans();

                $breakTime = explode(' ', $getTime);
                if (in_array('seconds', $breakTime) && in_array('minute', $breakTime) || in_array('minutes', $breakTime) && in_array('seconds', $breakTime)) {
                    $existTime = $breakTime[0] . ' ' . $breakTime[1] . ' : ' . $breakTime[2] . ' ' . $breakTime[3];
                    Session::where('id', $session_id)->update(['remaining_time' => $existTime]);
                    $session->remaining_time = $existTime;
                } else if (!in_array('seconds', $breakTime) && in_array('minute', $breakTime) || in_array('minutes', $breakTime)) {
                    $existTime = $breakTime[0] . ' ' . $breakTime[1] . ' : 00 seconds';
                    Session::where('id', $session_id)->update(['remaining_time' => $existTime]);
                    $session->remaining_time = $existTime;
                } else if (in_array('seconds', $breakTime) && !in_array('minute', $breakTime) || in_array('minutes', $breakTime)) {
                    $existTime = '00 minute : ' . $breakTime[0] . ' ' . $breakTime[1];
                    Session::where('id', $session_id)->update(['remaining_time' => $existTime]);
                    $session->remaining_time = $existTime;
                }
            } else {
                // dd('time end');
            }
            return view('dashboard_doctor.Video.index', compact('session', 'patient', 'med', 'img', 'specializations','files'));
        }
        elseif($session->status == "ended" && $session->validation_status == "valid")
        {
            return redirect()->route('recommendations.display', ['session_id' => $session->id]);
        }
        else
        {
            return redirect()->route('doctor_dashboard');
        }
    }

    public function patientVideo($session_id)
    {
        $session_id = \Crypt::decrypt($session_id);
        $session = Session::where('id', $session_id)->first();
        if($session->status == "doctor joined" || $session->status == "started")
        {
            Session::where('id', $session_id)->update(['status' => 'started']);
            $session = Session::where('id', $session_id)->first();
            $doctor = DB::table('users')
            ->join('specializations', 'specializations.id', 'users.specialization')
            ->where('users.id', $session->doctor_id)
            ->select('users.name', 'users.last_name', 'users.id', 'specializations.name as sp_name','specializations.id as sp_id')
            ->first();

            event(new patientJoinCall($session->doctor_id, $session->patient_id, $session_id));

            if ($session->remaining_time == 'full') {
                $time_in_minutes = Specialization::where('id',$doctor->sp_id)->first();
                $time_in_minutes = $time_in_minutes->consultation_time;
                $start_time = Carbon::now()->addMinutes($time_in_minutes)->format('Y-m-d H:i:s');
                Session::where('id', $session_id)->update(['start_time' => $start_time, 'remaining_time' => $time_in_minutes.' minute : 00 seconds']);
                $session->remaining_time = $time_in_minutes.' minute : 00 seconds';
            } else {
                $nowTime = strtotime(Carbon::now()->format('Y-m-d H:i:s'));
                $end_time = strtotime($session->start_time);
                if ($end_time > $nowTime) {
                    $diff = $end_time - $nowTime;
                    $getTime = CarbonInterval::seconds($diff)->cascade()->forHumans();
                    $breakTime = explode(' ', $getTime);
                    if (in_array('seconds', $breakTime) && in_array('minute', $breakTime) || in_array('minutes', $breakTime)) {
                        $existTime = $breakTime[0] . ' ' . $breakTime[1] . ' : ' . $breakTime[2] . ' ' . $breakTime[3];
                        Session::where('id', $session_id)->update(['remaining_time' => $existTime]);
                        $session->remaining_time = $existTime;
                    } else if (!in_array('seconds', $breakTime) && in_array('minute', $breakTime) || in_array('minutes', $breakTime)) {
                        $existTime = $breakTime[0] . ' ' . $breakTime[1] . ' : 00 seconds';
                        Session::where('id', $session_id)->update(['remaining_time' => $existTime]);
                        $session->remaining_time = $existTime;
                    } else if (in_array('seconds', $breakTime) && !in_array('minute', $breakTime) || in_array('minutes', $breakTime)) {
                        $existTime = '00 minute : ' . $breakTime[0] . ' ' . $breakTime[1];
                        Session::where('id', $session_id)->update(['remaining_time' => $existTime]);
                        $session->remaining_time = $existTime;
                    }
                } else {
                    // dd('time end');
                }
            }


            return view('dashboard_patient.Video.index', compact('session', 'doctor'));
        }
        else
        {
            return redirect()->route('New_Patient_Dashboard');
        }
    }
    public function load_imaging_report_video_page()
    {
        return "ok";
    }
    public function load_lab_report_video_page()
    {
        return "ok";
    }

    public function load_diagnosis_video_page(Request $request)
    {
        $I_S = DB::table('sessions')->where('sessions.id',$request->id)
        ->join('symptom_checker','sessions.patient_id','symptom_checker.user_id' )
        ->select('symptom_checker.*')
        ->orderBy('symptom_checker.id','DESC')
        ->first();
        if($I_S){
            // $diagnoses = unserialize($I_S->diagnoses);
            return $I_S;
        }else{
            return 'null';
        }
    }

    public function load_psych_question(Request $request)
    {
        $session = DB::table('sessions')->where('sessions.id',$request->id)
        ->join('psychiatry_form', 'psychiatry_form.user_id','sessions.patient_id')
        ->select('psychiatry_form.*')
        ->orderby('psychiatry_form.id','DESC')
        ->first();
        $session->patient_health = unserialize($session->patient_health);
        $session->mood_disorder = unserialize($session->mood_disorder);
        $session->anxiety_scale = unserialize($session->anxiety_scale);
        return $session;
    }

    public function new_add_labtest_aoes_into_db(Request $request)
    {
        $aoes = serialize($request->inputValue);
        $record = DB::table('patient_lab_recomend_aoe')
            ->where('testCode', $request->getTestCode)
            ->where('session_id', $request->session_id)
            ->count();
        if ($record > 0) {
            DB::table('patient_lab_recomend_aoe')
                ->where('testCode', $request->getTestCode)
                ->where('session_id', $request->session_id)
                ->update([
                    'aoes' => $aoes
                ]);
            return "ok";
        } else {
            DB::table('patient_lab_recomend_aoe')
                ->insert([
                    'aoes' => $aoes,
                    'testCode' => $request->getTestCode,
                    'session_id' => $request->session_id,
                ]);
            return "ok";
        }
    }
    public function check_lab_aoes(Request $request)
    {
        $getTestAOE = QuestDataAOE::select('id as question_id', "TEST_CD AS TestCode", "AOE_QUESTION AS QuestionShort", "AOE_QUESTION_DESC AS QuestionLong")
            ->where('TEST_CD', $request->test_code)
            ->groupBy('AOE_QUESTION_DESC')
            ->get()
            ->toArray();

        if ($getTestAOE == null || $getTestAOE == "") {
            return 'not found';
        } else {
            return $getTestAOE;
        }
    }
    public function fetch_user_state_by_zipcode(Request $request)
    {
        $zip = $request->zipcode;
        $getState = DB::table('tbl_zip_codes_cities')->where('zip_code', $zip)->first();

        $status = $getState->state;

        $state_name = $status;
        if ($state_name == '') {
            return 'no found';
        } else {
            $all_locations = DB::table('imaging_locations')->where('clinic_name', $state_name)->get()->toArray();
            if (count($all_locations) > 0) {
                return $all_locations;
            } else {
                return 'no found';
            }
        }
    }
    public function load_session_record_video_page(Request $request)
    {
        $all_sessions = DB::table('sessions')->where('patient_id', $request->id)->where('status','!=', 'pending')->where('id', '!=', $request->session_id)->orderby('id','desc')->get();
        $session_record = [];
        foreach ($all_sessions as $session) {
            $user_obj = new User();
            $date = User::convert_utc_to_user_timezone(Auth::user()->id, $session->created_at);
            $all_pres = DB::table('prescriptions')->where('session_id', $session->id)->get();
            $doctor = User::find($session->doctor_id);
            $array = [];
            if (count($all_pres) > 0) {
                foreach ($all_pres as $pres) {
                    if ($pres->type == 'lab-test' || $pres->type == 'imaging') {
                        $item = DB::table('quest_data_test_codes')->where('TEST_CD', $pres->test_id)->first();
                        $buyItem = DB::table('lab_orders')->where('pres_id', $pres->id)->first();
                        if ($buyItem != null) {
                            array_push($array, ['pro_name' => $item->TEST_NAME, 'status' => 'Purchased']);
                        } else {
                            array_push($array, ['pro_name' => $item->TEST_NAME, 'status' => 'Recommend']);
                        }
                    } else if ($pres->type == 'medicine') {
                        $item = DB::table('tbl_products')->where('id', $pres->medicine_id)->first();
                        $buyItem = DB::table('medicine_order')->where('order_product_id', $pres->medicine_id)->where('session_id', $session->id)->first();
                        if ($buyItem != null) {
                            array_push($array, ['pro_name' => $item->name, 'status' => 'Purchased']);
                        } else {
                            array_push($array, ['pro_name' => $item->name, 'status' => 'Recommend']);
                        }
                    }
                    // else if ($pres->type == 'imaging') {
                    //     $item = DB::table('tbl_products')->where('id', $pres->imaging_id)->first();
                    //     $buyItem = DB::table('imaging_orders')->where('pres_id', $pres->id)->first();
                    //     if ($buyItem != null) {
                    //         array_push($array, ['pro_name' => $item->name, 'status' => 'Purchased']);
                    //     } else {
                    //         array_push($array, ['pro_name' => $item->name, 'status' => 'Recommend']);
                    //     }
                    // }
                }
                $session_record[] = ['date' => $date['date'], 'provider' => $doctor->name . ' ' . $doctor->last_name, 'note' => $session->provider_notes ?? 'none', 'diagnois' => $session->diagnosis ?? 'none', 'prescriptions' => $array];
            } else {
                $session_record[] = ['date' => $date['date'], 'provider' => $doctor->name . ' ' . $doctor->last_name, 'note' => $session->provider_notes ?? 'none', 'diagnois' => $session->diagnosis ?? 'none', 'prescriptions' => null];
            }
        }

        return $session_record;
    }
    public function load_current_medication_video_page(Request $request)
    {
        $user_obj = new User();
        return $user_obj->get_current_med($request->id);
    }
    public function load_medical_history_video_page(Request $request)
    {
        $medical_record = DB::table('medical_profiles')->where('user_id', $request->id)->first();
        $record['prev_symp'] = explode(',', $medical_record->previous_symp);
        $record['comment'] = $medical_record->comment;
        array_pop($record['prev_symp']);
        return $record;
    }
    public function load_family_history_video_page(Request $request)
    {
        $medical_record = DB::table('medical_profiles')->where('user_id', $request->id)->first();
        $record = json_decode($medical_record->family_history);



        return $record;
    }

    public function load_symtems_video_page(Request $request)
    {
        $getSymtems = array();
        $symptoms = DB::table('sessions')
                    ->join('symptom_checker', 'symptom_checker.user_id', 'sessions.patient_id')
                    ->orderBy('symptom_checker.id','DESC')
                    ->first();
        // if ($symptoms->headache == 1) {
        //     array_push($getSymtems, 'headache');
        // }
        // if ($symptoms->flu == 1) {
        //     array_push($getSymtems, 'flu');
        // }
        // if ($symptoms->fever == 1) {
        //     array_push($getSymtems, 'fever');
        // }
        // if ($symptoms->nausea == 1) {
        //     array_push($getSymtems, 'nausea');
        // }
        // if ($symptoms->others == 1) {
        //     array_push($getSymtems, 'others');
        // }
        $symptoms->symptoms_text = explode(",", $symptoms->symptoms_text);
        array_pop($symptoms->symptoms_text);
        // dd($symptoms->symptoms_text);
        return array('symtem' => $getSymtems, 'description' => $symptoms->description, 'symptoms_text'=> $symptoms->symptoms_text);
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
    public function videoJoin($id, Request $request)
    {

        $user_id = auth()->user()->id;
        $getSession = Session::where('id', $id)->first();
        $userTypeCheck = User::where('id', $user_id)->first();
        $patUser = User::where('id', $getSession->patient_id)->first();
        $docUser = User::where('id', $getSession->doctor_id)->first();
        if ($patUser->med_record_file != null) {
            $patUser->med_record_file = \App\Helper::get_files_url($patUser->med_record_file);
        }

        if ($userTypeCheck->user_type == 'doctor') {


            $medicines['products'] = AllProducts::where(['mode' => 'medicine', 'medicine_type' => 'prescribed'])->get();
            $medicines['category'] = ProductsSubCategory::where(['parent_id' => 38])->get();
            // dd($med_cats);
            $labs = QuestDataTestCode::whereRaw("TEST_CD NOT LIKE '#%%' ESCAPE '#'")
                ->whereIn('id', [
                    '3327', '4029', '1535', '3787', '47', '1412',
                    '1484', '1794', '3194', '3352', '3566', '3769',
                    '4446', '18811', '11363', '899', '16846', '3542',
                    '229', '747', '6399', '7573', '16814',
                ])
                ->where('TEST_CD', '!=', '92613')
                ->where('TEST_CD', '!=', '11196')
                ->where('LEGAL_ENTITY', 'DAL')
                ->orWhere('PRICE', '!=', null)
                ->get();
            foreach ($labs as $lab) {
                // dd($lab);
                $res = DB::table('prescriptions')->where('test_id', $lab->TEST_CD)->where('session_id', $id)->first();
                if ($res != null) {

                    $lab->added = 'yes';
                } else {
                    $lab->added = 'no';
                }
                $getTestAOE = QuestDataAOE::select("TEST_CD AS TestCode", "AOE_QUESTION AS QuestionShort", "AOE_QUESTION_DESC AS QuestionLong")
                    ->where('TEST_CD', $lab->TEST_CD)
                    ->groupBy('AOE_QUESTION_DESC')
                    ->get()
                    ->toArray();

                $count = count($getTestAOE);
                if ($count > 0) {
                    $lab->aoes = 1;
                    $lab->aoeQuestions = $getTestAOE;
                } else {
                    $lab->aoes = 0;
                    $lab->aoeQuestions = '';
                }
            }


            $imaging = AllProducts::where('mode', 'imaging')->get();

            $img_categories = ProductCategory::where('category_type', 'imaging')->get();
            $symp = Symptom::find($getSession->symptom_id);
            $symptoms = $this->getSymptoms($symp);
            $symp_desc = ($symp != null) ? $symp['description'] : '';

            $user_obj = new User();
            $patient_meds = $user_obj->get_current_medicines($getSession->patient_id);
            $patient_labs = $user_obj->get_lab_reports($getSession->patient_id);
            // dd($patient_labs);
            $img_reports = $user_obj->get_imaging_reports($getSession->patient_id);
            $prev_sessions = $user_obj->get_sessions($getSession->patient_id);
            $pat_age = $user_obj->get_age($getSession->patient_id);
            $medical_profile = MedicalProfile::where('user_id', $getSession->patient_id)->orderByDesc('id')->first();
            $specs = Specialization::where('status', '1')->get();



            $user_type = "doctor";
            return view('video.video_page', compact(
                'user_type',
                'medicines',
                'pat_age',
                'labs',
                'imaging',
                'symptoms',
                'symp_desc',
                'patUser',
                'getSession',
                'medical_profile',
                'prev_sessions',
                'patient_meds',
                'specs',
                'patient_labs',
                'img_categories',
                'img_reports'
            ));
        } else if ($userTypeCheck->user_type == 'patient') {
            // dd('patient');
            $user_obj = new User();
            $history['medicines'] = $user_obj->get_current_medicines($getSession->patient_id);
            $history['sessions'] = $user_obj->get_sessions($getSession->patient_id);
            $history['labs'] = $user_obj->get_lab_reports($getSession->patient_id);
            $history['imaging'] = $user_obj->get_imaging_reports($getSession->patient_id);
            // dd($history['imaging']);
            $pat_age = $user_obj->get_age($getSession->patient_id);
            $spec = new Specialization();
            $spname = $spec->speciality($getSession->doctor_id);
            $docUser->specialization = $spname;
            if ($getSession->status != 'doctor joined') {
                $msg = "Session Ended";
                $currentRole = $patUser->user_type;
                return redirect()->route('home', compact('msg', 'currentRole'));
            }
            $symp = Symptom::find($getSession->symptom_id);
            $symptoms = $this->getSymptoms($symp);
            $symp_desc = ($symp != null) ? $symp['description'] : '';
            $user_type = "patient";
            $medical_profile = MedicalProfile::where('user_id', $getSession->patient_id)->orderByDesc('id')->first();
            ActivityLog::create([
                'activity' => 'joined session with ' . $docUser->name . " " . $docUser->last_name,
                'type' => 'session start',
                'user_id' => $getSession->patient_id,
                'user_type' => 'doctor',
                'party_involved' => $getSession->doctor_id,
            ]);

            //event accure
            try {
                $getSession->received = false;
                // \App\Helper::firebase($getSession->doctor_id,'patientJoinCall',$getSession->id,$getSession);
            } catch (\Throwable $th) {
                //throw $th;
            }
            event(new patientJoinCall($getSession->doctor_id, $getSession->patient_id, $id));

            return view('video.video_page', compact(
                'user_type',
                'docUser',
                'pat_age',
                'getSession',
                'medical_profile',
                'symptoms',
                'symp_desc',
                'history',
                'patUser'
            ));
        }
    }

    public function patientNotJoiningCall($id)
    {
        $current_session = DB::table('sessions')->where('id', $id)->first();

        $doctor_all_sessions = DB::table('sessions')->where('doctor_id', $current_session->doctor_id)->where('status', 'invitation sent')->orderBy('queue', 'asc')
            ->get()->toArray();
        $count = count($doctor_all_sessions);
        $length = $count;


        $mints = 5;
        for ($i = 0; $i <= $length; $i++) {
            if ($length == $i) {
                DB::table('sessions')
                    ->where('id', $id)
                    ->update(['queue' => $i, 'status' => 'invitation sent', 'que_message' => 'Your Doctor Will Be Available In Approximately ' . $mints . ' Mints', 'join_enable' => '1']);
            } else {
                DB::table('sessions')
                    ->where('id', $doctor_all_sessions[$i]->id)
                    ->update(['queue' => $i, 'status' => 'invitation sent', 'que_message' => 'Your Doctor Will Be Available In Approximately ' . $mints . ' Mints', 'join_enable' => '1']);
                $mints += 10;
            }
        }

        $length = count($doctor_all_sessions);
        $length++;
        $counter = 1;
        $mints = 5;
        foreach ($doctor_all_sessions as $session) {
            DB::table('sessions')
                ->where('id', $session->id)
                ->update(['queue' => $counter, 'status' => 'invitation sent', 'que_message' => 'Your Doctor Will Be Available In Approximately ' . $mints . ' Mints', 'join_enable' => '1']);
            $mints += 10;
            $counter += 1;
        }
        DB::table('sessions')
            ->where('id', $id)
            ->update(['queue' => $counter + 1, 'status' => 'invitation sent', 'que_message' => 'Your Doctor Will Be Available In Approximately ' . $mints . ' Mints', 'join_enable' => '1']);

        try {
            $firebase_ses = Session::where('id', $session->id)->first();
            $firebase_ses->received = false;
            // \App\Helper::firebase($firebase_ses->doctor_id,'updateQuePatient',$firebase_ses->id,$firebase_ses);

        } catch (\Throwable $th) {
            //throw $th;
        }
        event(new updateQuePatient('update patient que'));
        event(new updateDoctorWaitingRoom('new_patient_listed'));

        return redirect()->route('waiting_room', ['id' => $id]);
    }

    public function patient_NotJoiningCall($id)
    {
        $id = \Crypt::decrypt($id);
        $current_session = DB::table('sessions')->where('id', $id)->first();
        $redirect = 0;

        if ($current_session->queue_count == 2) {
            $redirect = 1;
            DB::table('sessions')->where('id', $current_session->id)->update(['status' => 'cancel', 'join_enable' => '0']);
            $doc_user = User::find($current_session->doctor_id);
            ActivityLog::create([
                'activity' => 'Patient declined call of session with Dr.' . $doc_user['name'] . " " . $doc_user['last_name'] . ' more than 2 times',
                'type' => 'session ended',
                'user_id' => $current_session->patient_id,
                'user_type' => 'patient',
                'party_involved' => $current_session->doctor_id,
            ]);
        } else {
            $getLastSession = DB::table('sessions')->where('doctor_id', $current_session->doctor_id)->orderBy('id', 'DESC')->first();

            $current_patient_queue = $getLastSession->queue + 1;

            $queueCounter = 0;
            if ($current_session->queue_count == null) {
                $queueCounter = 1;
            } else {
                $queueCounter = $current_session->queue_count + 1;
            }

            DB::table('sessions')->where('id', $id)->update(['queue' => $current_patient_queue, 'status' => 'invitation sent', 'queue_count' => $queueCounter]);

            $doc_user = User::find($current_session->doctor_id);
            ActivityLog::create([
                'activity' => 'Patient declined call of session with Dr.' . $doc_user['name'] . " " . $doc_user['last_name'] . ' first time',
                'type' => 'session delayed',
                'user_id' => $current_session->patient_id,
                'user_type' => 'patient',
                'party_involved' => $current_session->doctor_id,
            ]);
        }

        $doctor_all_sessions = DB::table('sessions')->where('doctor_id', $current_session->doctor_id)->where('status', 'invitation sent')
            ->orderBy('queue', 'asc')->get();

        $mints = 5;

        foreach ($doctor_all_sessions as $doctor) {
            DB::table('sessions')->where('id', $doctor->id)->update(['que_message' => 'Your Doctor Will Be Available In Approximately ' . $mints . ' Mints']);
            $mints += 10;
            try {
                $firebase_ses = Session::where('id', $doctor->id)->first();
                $firebase_ses->received = false;
                // \App\Helper::firebase($firebase_ses->doctor_id,'updateQuePatient',$firebase_ses->id,$firebase_ses);
            } catch (\Throwable $th) {
                //throw $th;
            }
        }

        event(new updateQuePatient('update patient que'));
        event(new updateDoctorWaitingRoom('new_patient_listed'));
        if ($redirect != 0) {
            return redirect()->route('New_Patient_Dashboard');
        } else {
            return redirect()->route('waiting_room_pat', ['id' => \Crypt::encrypt($id)]);
        }
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $user_type = $user->user_type;

        if ($user_type == 'doctor') {
            //change session status doctor join
            $session_id = $request['session'];
            Session::where('id', $session_id)->update(['status' => 'doctor joined']);
            $session_patient = Session::find($session_id);
            $patient_user = User::find($session_patient['patient_id']);
            ActivityLog::create([
                'activity' => 'joined session with ' . $patient_user['name'] . " " . $patient_user['last_name'],
                'type' => 'session start',
                'user_id' => $session_patient['doctor_id'],
                'user_type' => 'doctor',
                'party_involved' => $session_patient['patient_id'],
            ]);
            try {
                $firebase_session = DB::table('sessions')->where('id',$session_id)->first();
                $firebase_session->received = false;
                // \App\Helper::firebase($firebase_session->patient_id,'DoctorJoinedVideoSession',$firebase_session->id,$firebase_session);

            } catch (\Throwable $th) {
                //throw $th;
            }
            event(new DoctorJoinedVideoSession($user->id, $patient_user->id, $session_id));
            // dd($event);
            // $request['session']=2;
            $medicines = AllProducts::where('mode', 'medicine')->get();
            // $labs = AllProducts::where('mode', 'lab-test')->get();
            $labs = QuestDataTestCode::whereRaw("TEST_CD NOT LIKE '#%%' ESCAPE '#'")
                // ->whereIn('id',['3327','4029','1535','3787','47','1412',
                //                 '1484','1794','3194','3352','3566','3769',
                //                 '4446','18811','11363','899','16846','3542',
                //                 '229','747','6399','7573','16814','1129','297','4029','791','4004'
                //                 ])//test cases
                ->where('TEST_CD', '!=', '92613') //contains result filters for AOEs
                ->where('TEST_CD', '!=', '11196') //contains result filters for AOEs
                ->where('LEGAL_ENTITY', 'DAL')
                ->orWhere('PRICE', '!=', null)
                ->get();
            //  dd($labs);


            $imaging = AllProducts::where('mode', 'imaging')->get();
            $img_reports = DB::table('imaging_orders')
                ->join('tbl_products', 'tbl_products.id', '=', 'imaging_orders.product_id')
                ->where('imaging_orders.user_id', $session_patient['patient_id'])
                ->where('imaging_orders.status', 'reported')
                ->select('imaging_orders.id', 'imaging_orders.created_at', 'tbl_products.name')
                ->get();
            $img_categories = ProductCategory::where('category_type', 'imaging')->get();
            // dd($request['session']);
            // $symp=Symptom::where('patient_id')->
            $session = Session::where('id', $request['session'])->first();
            $symp = Symptom::find($session['symptom_id']);
            // dd($symp);
            $symptoms = $this->getSymptoms($symp);
            $symp_desc = ($symp != null) ? $symp['description'] : '';

            $user_obj = new User();
            $patient_meds = $user_obj->get_current_medicines($session['patient_id']);
            $patient_labs = $user_obj->get_lab_reports($session['patient_id']);
            // dd($patient_labs);
            $prev_sessions = $user_obj->get_sessions($session['patient_id']);
            $patient = User::find($session['patient_id']);
            $medical_profile = MedicalProfile::where('user_id', $patient['id'])->orderByDesc('id')->first();
            $links = VideoLinks::where('session_id', $request['session'])->orderByDesc('id')->first();
            $specs = Specialization::where('status', '1')->get();

            // notifications comment
            try {
                // event(new RealTimeMessage('Hello World'));
                $doctor_data = User::where('id', $session_patient['doctor_id'])->first();
                $patient_data = User::where('id', $session['patient_id'])->first();
                $getSessions = Session::where('id', $request['session'])->get();
                //////////////send doctor and Patient email////////////////////////
                ////////////create notification for patient and doctor////////////////
                $text = $doctor_data->name . " " . $doctor_data->last_name . " Join Session On Your E-visit Request";
                $notification_id = Notification::create([
                    'user_id' => $patient_data->id,
                    'type' => 'patient/video/' . $session_id,
                    'text' => $text,
                    'session_id' => $session_id,
                ]);
                $data = [
                    'user_id' => $patient_data->id,
                    'type' => 'patient/video/' . $session_id,
                    'text' => $text,
                    'session_id' => $session_id,
                    'received' => 'false',
                    'appoint_id' => 'null',
                    'refill_id' => 'null',
                ];

                // \App\Helper::firebase($patient_data->id,'notification',$notification_id->id,$data);

                event(new RealTimeMessage($patient_data->id));
                //////////////////////////////////////////////////////////////////End
                // Mail::to($patient_data->email)->send(new EvisitStart($getSessions));
                /////////////////////////////////////////////////////////////////////End

                ////////////send message notification to patient and doctor///////////
                // Nexmo::message()->send([                                         //
                //     'to'   => $patient_data->phone_number,                       //
                //     'from' => '923461652351',                                    //
                //     'text' => 'That Is Testing Message By Umbrella-MD'           //
                // ]);                                                              //
                ///////////////////////////////////////////////////////////////////End

            } catch (\Exception $e) {
                Log::error($e);
            }
            return view('video.video_page', compact(
                'user_type',
                'medicines',
                'labs',
                'imaging',
                'user',
                'symptoms',
                'symp_desc',
                'patient',
                'links',
                'session',
                'medical_profile',
                'prev_sessions',
                'patient_meds',
                'specs',
                'patient_labs',
                'img_categories',
                'img_reports'
            ));
        } else if ($user_type == 'patient') {
            $user_obj = new User();
            $meds = $user_obj->get_current_medicines($user->id);
            $prev_sessions = $user_obj->get_sessions($user->id);
            $prev_labs = $user_obj->get_lab_reports($user->id);
            // dd($prev_labs);
            $doc = User::find($request['doc_id']);
            $spec = new Specialization();
            $spname = $spec->speciality($request['doc_id']);
            $doc->specialization = $spname;
            $session = Session::where('patient_id', $user->id)->where('doctor_id', $request['doc_id'])->orderByDesc('id')->first();
            // dd($request['doc_id']);
            if ($session->status != 'doctor joined') {
                $msg = "Session Ended";
                $currentRole = $user_type;
                return redirect()->route('home', compact('msg', 'currentRole'));
            }
            $links = VideoLinks::where('session_id', $session['id'])->orderByDesc('id')->first();
            $symp = Symptom::find($session['symptom_id']);
            // dd($symp);

            $symptoms = $this->getSymptoms($symp);
            $symp_desc = ($symp != null) ? $symp['description'] : '';

            $medical_profile = MedicalProfile::where('user_id', auth()->user()->id)->orderByDesc('id')->first();
            // dd($medical_profile);
            ActivityLog::create([
                'activity' => 'joined session with ' . $doc['name'] . " " . $doc['last_name'],
                'type' => 'session start',
                'user_id' => $user->id,
                'user_type' => 'patient',
                'party_involved' => $request['doc_id'],
            ]);
            return view('video.video_page', compact(
                'user_type',
                'doc',
                'session',
                'links',
                'medical_profile',
                'symptoms',
                'symp_desc',
                'meds',
                'prev_sessions',
                'prev_labs'
            ));
        }
    }
    public function add_video_links(Request $request)
    {
        $pat = auth()->user();
        $pat_id = $pat->id;
        $pat_name = $pat->name . " " . $pat->last_name;
        // return $pat_name;
        $session_id = $request['session_id'];
        $doc_id = $request['doc_id'];
        $symp_id = Symptom::where('patient_id', $pat_id)->where('doctor_id', $doc_id)->orderByDesc('id')->first();

        $pat_url = $request['patURL'];
        $doc_url = $request['docURL'];
        $date = Carbon::today();
        // $session_id=Session::create([
        //     'patient_id'=>  $pat_id,
        //     'doctor_id'=>  $doc_id,
        //     'date'=>  $date,
        //     'status'=>'patient waiting',
        //     'symptom_id'=>$symp_id['id'],
        // ])->id;
        // return $request['session_id'];
        // return $request['room_id'];
        VideoLinks::create([
            'session_id' => $session_id,
            'doctor_link' => $doc_url,
            'patient_link' => $pat_url,
            'room_id' => $request['room_id'],
        ]);
        // DB::connection('suunnoo_db')->insert('insert into lsv_rooms (agent,visitor,agenturl,visitorurl) values (?,?,?,?)',[$doc_id,$pat_id,$doc_url,$pat_url]);
        // $lsa_rooms=DB::connection('suunnoo_db')->select('select * from lsa_rooms');
        // LsvRoom::create([
        //     'agent'=>$pat_id,
        //     'visitor'=>$doc_id,
        //     'agenturl'=>$pat_url,
        //     'visitorurl'=>$doc_url,
        //     'password'=>'d41d8cd98f00b204e9800998ecf8427e',
        //     'roomId'=>$request['room_id'],
        //     'datetime'=>$date,
        //     'duration'=>'6',
        //     'shortagenturl'=>$request['shortAgentUrl'],
        //     'shortvisitorurl'=>$request['shortVisitorUrl'],
        //     'is_active'=>'1',
        //     'session_id'=>$session_id
        // ]);

        $last_invite = Session::where('doctor_id', $doc_id)->max('sequence');

        $sequence = $last_invite + 1;
        $session = Session::find($session_id)->update(['status' => 'invitation sent', 'queue' => 1, 'sequence' => $sequence]);
        // $all_waiting=Session::where('doctor_id',$doc_id)
        //                     ->where('patient_id','!=',$pat_id)
        //                     ->where('id','<',$session_id)
        //                     ->where('status','invitation sent')
        //                     ->groupBy('patient_id')
        //                     ->get();
        // $joined=Session::where('doctor_id',$doc_id)
        //                 ->where('patient_id','!=',$pat_id)
        //                 ->where('id','<',$session_id)
        //                 ->where('status','doctor joined')
        //                 ->groupBy('patient_id')
        //                 ->get();
        $all_waiting = Session::where('doctor_id', $doc_id)
            ->where('patient_id', '!=', $pat_id)
            // ->where('id','<',$session_id)
            ->where('queue', 1)
            ->groupBy('patient_id')
            ->get();

        // $waiting_count=count($all_waiting);
        // $joined_count=count($joined);
        // notifications comment
        // event(new RealTimeMessage('Hello World'));
        $notification_id = Notification::create([
            'user_id' => $doc_id,
            'text' => 'E-visit Joining Request Send by ' . $pat_name,
            'session_id' => $session_id,
            'type' => '/waiting_room',
        ]);
        $data = [
            'user_id' => $doc_id,
            'text' => 'E-visit Joining Request Send by ' . $pat_name,
            'session_id' => $session_id,
            'type' => '/waiting_room',
            'received' => 'false',
            'appoint_id' => 'null',
            'refill_id' => 'null',
        ];
        try {

            // \App\Helper::firebase($doc_id,'notification',$notification_id->id,$data);
        } catch (\Throwable $th) {
            //throw $th;
        }

        event(new RealTimeMessage($doc_id));
        return $all_waiting;
    }
    public function join_video_session(Request $request)
    { //user_id,doc_id
        $user = $request['user_id'];
        $user_type = $user->user_type;
        if ($user_type == 'patient') {
            $user_obj = new User();
            $meds = $user_obj->get_current_medicines($user->id);
            $prev_sessions = $user_obj->get_sessions($user->id);
            $prev_labs = $user_obj->get_lab_reports($user->id);
            $doc = User::find($request['doc_id']);
            $spec = new Specialization();
            $spname = $spec->speciality($request['doc_id']);
            $doc->specialization = $spname;
            $session = Session::where('patient_id', $user->id)->where('doctor_id', $request['doc_id'])
                ->orderByDesc('id')->first();
            if ($session->status != 'doctor joined') {
                return view('/error');
            }
            // $links = VideoLinks::where('session_id', $session['id'])->orderByDesc('id')->first();
            $symp = Symptom::find($session['symptom_id']);
            if ($symp != null) {
                $symptoms = '';
                if ($symp['flu'] == '1') {
                    $symptoms .= 'flu, ';
                }

                if ($symp['headache'] == '1') {
                    $symptoms .= 'headache, ';
                }

                if ($symp['nausea'] == '1') {
                    $symptoms .= 'nausea, ';
                }

                if ($symp['fever'] == '1') {
                    $symptoms .= 'fever, ';
                }

                if ($symp['others'] == '1') {
                    $symptoms .= 'others';
                }

                $symp_desc = $symp['description'];
            } else {
                $symptoms = 'Not specified';
                $symp_desc = '';
            }
            $medical_profile = MedicalProfile::where('user_id', $user->id)->orderByDesc('id')->first();
            return view('video.video_page', compact(
                'user_type',
                'doc',
                'session',
                'medical_profile',
                'symptoms',
                'symp_desc',
                'meds',
                'prev_sessions',
                'prev_labs'
            ));
        }
    }
    public function getSymptoms($symp)
    {
        if ($symp != null) {
            $symptoms = '';
            if ($symp['flu'] == '1') {
                $symptoms .= 'flu, ';
            }
            if ($symp['headache'] == '1') {
                $symptoms .= 'headache, ';
            }

            if ($symp['nausea'] == '1') {
                $symptoms .= 'nausea, ';
            }

            if ($symp['fever'] == '1') {
                $symptoms .= 'fever, ';
            }

            if ($symp['others'] == '1') {
                $symptoms .= 'others';
            }

            $symp_desc = $symp['description'];
        } else {
            $symptoms = 'Not specified';
            $symp_desc = '';
        }
        return $symptoms;
    }

    public function patient_absent($id)
    {
        if($id!=null)
        {
            $current_session = DB::table('sessions')->where('id', $id)->where('status','doctor joined')->first();
            if($current_session!=null)
            {
                $redirect = 0;
                if ($current_session->queue_count == 2) {
                    $redirect = 1;
                    DB::table('sessions')->where('id', $current_session->id)->update(['status' => 'cancel', 'join_enable' => '0']);
                    $doc_user = User::find($current_session->doctor_id);
                    ActivityLog::create([
                        'activity' => 'Patient declined call of session with Dr.' . $doc_user['name'] . " " . $doc_user['last_name'] . ' more than 2 times',
                        'type' => 'session ended',
                        'user_id' => $current_session->patient_id,
                        'user_type' => 'patient',
                        'party_involved' => $current_session->doctor_id,
                    ]);
                } else {
                    $getLastSession = DB::table('sessions')->where('doctor_id', $current_session->doctor_id)->orderBy('id', 'DESC')->first();

                    $current_patient_queue = $getLastSession->queue + 1;

                    $queueCounter = 0;
                    if ($current_session->queue_count == null) {
                        $queueCounter = 1;
                    } else {
                        $queueCounter = $current_session->queue_count + 1;
                    }

                    DB::table('sessions')->where('id', $id)->update(['queue' => $current_patient_queue, 'status' => 'invitation sent', 'queue_count' => $queueCounter]);

                    $doc_user = User::find($current_session->doctor_id);
                    ActivityLog::create([
                        'activity' => 'Patient declined call of session with Dr.' . $doc_user['name'] . " " . $doc_user['last_name'] . ' first time',
                        'type' => 'session delayed',
                        'user_id' => $current_session->patient_id,
                        'user_type' => 'patient',
                        'party_involved' => $current_session->doctor_id,
                    ]);
                }

                $doctor_all_sessions = DB::table('sessions')->where('doctor_id', $current_session->doctor_id)->where('status', 'invitation sent')
                    ->orderBy('queue', 'asc')->get();

                $mints = 5;

                foreach ($doctor_all_sessions as $doctor) {
                    DB::table('sessions')->where('id', $doctor->id)->update(['que_message' => 'Your Doctor Will Be Available In Approximately ' . $mints . ' Mints']);
                    $mints += 10;
                    try {
                        $firebase_ses = Session::where('id', $doctor->id)->first();
                        $firebase_ses->received = false;
                        // \App\Helper::firebase($firebase_ses->doctor_id,'updateQuePatient',$firebase_ses->id,$firebase_ses);

                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                }
                event(new updateQuePatient('update patient que'));
                event(new updateDoctorWaitingRoom('new_patient_listed'));
            }
        }
        return redirect()->back();
    }
}
