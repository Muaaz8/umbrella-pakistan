<?php

namespace App\Http\Controllers\Api\Video;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use App\Events\patientJoinCall;
use App\Session;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Auth;
use DB;
use App\User;

class VideoScreenController extends BaseController
{
    public function patientVideo($session_id){
        // $session_id = \Crypt::decrypt($session_id);
        Session::where('id', $session_id)->update(['status' => 'started']);
        $session = Session::where('id', $session_id)->first();
        $doctor = DB::table('users')
            ->join('specializations', 'specializations.id', 'users.specialization')
            ->where('users.id', $session->doctor_id)
            ->select('users.name', 'users.last_name', 'users.id', 'specializations.name as sp_name')
            ->first();

        event(new patientJoinCall($session->doctor_id, $session->patient_id, $session_id));
        try {
                $session->received = false;
                // \App\Helper::firebase($session->doctor_id,'patientJoinCall',$session_id,$session);

        } catch (\Throwable $th) {
            //throw $th;
        }
        if ($session->remaining_time == 'full') {
            $start_time = Carbon::now()->addMinutes(15)->format('Y-m-d H:i:s');
            Session::where('id', $session_id)->update(['start_time' => $start_time, 'remaining_time' => '15 minute : 00 seconds']);
            $session->remaining_time = '15 minute : 00 seconds';
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
        $videoPatientData['code'] = 200;
        $videoPatientData['session'] = $session;
        $videoPatientData['doctor'] = $doctor;
        return $this->sendResponse($videoPatientData,"patient side video information");
    }
    public function patient_symptoms(Request $request){
        $getSymtems = array();
        $symptoms = DB::table('sessions')->join('symptoms', 'symptoms.id', 'sessions.symptom_id')->where('sessions.id', $request->session_id)->select('symptoms.*')->first();
        if ($symptoms->headache == 1) {
            array_push($getSymtems, 'headache');
        }
        if ($symptoms->flu == 1) {
            array_push($getSymtems, 'flu');
        }
        if ($symptoms->fever == 1) {
            array_push($getSymtems, 'fever');
        }
        if ($symptoms->nausea == 1) {
            array_push($getSymtems, 'nausea');
        }
        if ($symptoms->others == 1) {
            array_push($getSymtems, 'others');
        }
        $symptoms->symptoms_text = explode(",", $symptoms->symptoms_text);
        array_pop($symptoms->symptoms_text);
        $patient_data['code'] = 200;
        // $patient_data['symptoms'] = $getSymtems;
        $patient_data['symptoms'] = $symptoms->symptoms_text;
        $patient_data['description'] = $symptoms->description;
        return $this->sendResponse($patient_data,"patient symptoms");
    }
    public function patient_medical_history(Request $request){
        if(Auth::user()->user_type =='doctor'){
            $file = DB::table('users')->where('id',$request->patient_id)->first();
            $patient_medical_file = $file->med_record_file;
            $medical_record = DB::table('medical_profiles')->where('user_id', $request->patient_id)->first();
            $record['code'] = 200;
            $record['prev_symp'] = explode(',', $medical_record->previous_symp);
            $record['patient_medical_file'] = $patient_medical_file;
            $record['comment'] = $medical_record->comment;
            array_pop($record['prev_symp']);
            return $this->sendResponse($record,"Patient medical history");
        } else{
            $medical_record = DB::table('medical_profiles')->where('user_id', $request->patient_id)->first();
            $record['code'] = 200;
            $record['prev_symp'] = explode(',', $medical_record->previous_symp);
            $record['comment'] = $medical_record->comment;
            array_pop($record['prev_symp']);
            return $this->sendResponse($record,"Patient medical history");
        }


    }
    public function patient_current_medication(Request $request){
        $user_obj = new User();
        $current_medication['code'] = 200;
        $current_medication['current_medication'] = $user_obj->get_current_med($request->patient_id);
        return $this->sendResponse($current_medication,"Patient Current Medications");
    }
    public function patient_family_history(Request $request){
        $medical_record = DB::table('medical_profiles')->where('user_id', $request->patient_id)->first();
        $record = json_decode($medical_record->family_history);
        $patient_family_history['code'] = 200;
        $patient_family_history['patient_family_history'] = $record;
        return $this->sendResponse($patient_family_history,"Patient Family History");
    }
    public function patient_imaging_report(){
        $code['code'] =  200;
        return $this->sendResponse($code,"No Imaging reports available");
    }
    public function patient_lab_report(){
        $code['code'] =  200;
        return $this->sendResponse($code,"No Lab reports available");
    }
    public function patient_visit_history(Request $request){
        $all_sessions = DB::table('sessions')->where('patient_id', $request->patient_id)->where('status','!=', 'pending')->where('id', '!=', $request->session_id)->orderby('id','desc')->limit(10)->get();
        $session_record = [];
        foreach ($all_sessions as $session) {
            $user_obj = new User();
            $date = User::convert_utc_to_user_timezone(Auth::user()->id, $session->created_at);
            $symtems = DB::table('symptoms')->where('id', $session->symptom_id)->first();
            $symtems->description = '';
            $symtemsText = '';
            if ($symtems->headache == 1) {
                $symtemsText .= 'headache';
            }
            if ($symtems->fever == 1) {
                $symtemsText .= ',fever';
            }
            if ($symtems->flu == 1) {
                $symtemsText .= ',flu';
            }
            if ($symtems->nausea == 1) {
                $symtemsText .= ',nausea';
            }
            if ($symtems->others == 1) {
                $symtemsText .= ',others';
            }
            $final_symtems = $symtemsText . ', ' . $symtems->description;
            $all_pres = DB::table('prescriptions')->where('session_id', $session->id)->get();
            $doctor = User::find($session->doctor_id);
            $array = [];
            if (count($all_pres) > 0) {

                foreach ($all_pres as $pres) {
                    if ($pres->type == 'lab-test') {
                        $item = DB::table('quest_data_test_codes')->where('TEST_CD', $pres->test_id)->first();
                        $buyItem = DB::table('lab_orders')->where('pres_id', $pres->id)->first();
                        if ($buyItem != null) {
                            array_push($array, ['pro_name' => $item->DESCRIPTION, 'status' => 'Purchased']);
                        } else {
                            array_push($array, ['pro_name' => $item->DESCRIPTION, 'status' => 'Recommend']);
                        }
                    } else if ($pres->type == 'medicine') {
                        $item = DB::table('tbl_products')->where('id', $pres->medicine_id)->first();
                        $buyItem = DB::table('medicine_order')->where('order_product_id', $pres->medicine_id)->where('session_id', $session->id)->first();
                        if ($buyItem != null) {
                            array_push($array, ['pro_name' => $item->name, 'status' => 'Purchased']);
                        } else {
                            array_push($array, ['pro_name' => $item->name, 'status' => 'Recommend']);
                        }
                    } else if ($pres->type == 'imaging') {
                        $item = DB::table('tbl_products')->where('id', $pres->imaging_id)->first();
                        $buyItem = DB::table('imaging_orders')->where('pres_id', $pres->id)->first();
                        if ($buyItem != null) {
                            array_push($array, ['pro_name' => $item->name, 'status' => 'Purchased']);
                        } else {
                            array_push($array, ['pro_name' => $item->name, 'status' => 'Recommend']);
                        }
                    }
                }
                $session_record[] = ['date' => $date['date'], 'provider' => $doctor->name . ' ' . $doctor->last_name, 'symtems' => $final_symtems, 'note' => $session->provider_notes ?? 'none', 'diagnois' => $session->diagnosis ?? 'none', 'prescriptions' => $array,'session_id' => $session->id];
            } else {
                $session_record[] = ['date' => $date['date'], 'provider' => $doctor->name . ' ' . $doctor->last_name, 'symtems' => $final_symtems, 'note' => $session->provider_notes ?? 'none', 'diagnois' => $session->diagnosis ?? 'none', 'prescriptions' => null, 'session_id' => $session->id];
            }
        }
        return $this->sendResponse($session_record,'Visit history');
    }
}
