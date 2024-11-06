<?php

namespace App\Http\Controllers\Api\Video;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use App\Events\patientJoinCall;
use App\Events\updateQuePatient;
use App\Events\patientEndCall;
use App\Events\LoadPrescribeItemList;
use App\Repositories\AllProductsRepository;
use App\Session;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use App\ActivityLog;
use App\Appointment;
use App\Cart;
use App\Events\redirectToCart;
use App\ImagingPrices;
use App\Mail\patientEvisitRecommendationMail;
use App\Notification;
use App\Events\RealTimeMessage;
use App\Mail\ReferDoctorToDoctorMail;
use App\Mail\ReferDoctorToPatientMail;
use Auth;
use DB;
use App\User;
use App\Prescription;
use App\QuestDataAOE;
use App\QuestDataTestCode;
use App\Referal;
use Mail;
use Illuminate\Support\Facades\Log;

class DoctorVideoScreenController extends BaseController
{
    private $allProductsRepository;
    public function __construct(AllProductsRepository $allProductsRepo)
    {
        $this->allProductsRepository = $allProductsRepo;
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
        $patientData['code'] = 200;
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
            $family_history = json_decode($medical_record->family_history);
            $record['code'] = 200;
            $record['prev_symp'] = explode(',', $medical_record->previous_symp);
            $record['patient_medical_file'] = $patient_medical_file;
            $record['family_history'] = $family_history;
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
    public function doctor_video($session_id){
        $session = Session::where('id', $session_id)->first();
        $doctor_info = DB::table('users')
                ->join('specializations', 'specializations.id', 'users.specialization')
                ->where('users.id', $session->doctor_id)
                ->select(DB::raw("CONCAT('Dr. ', users.name, ' ', users.last_name) as doctor_name"), 'users.id', 'specializations.name as sp_name')
                ->first();
        $patient = User::find($session->patient_id);
        $patient_name = $patient->name.' '.$patient->last_name;
        $birthDate = explode("-", $patient->date_of_birth);
        $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[2], $birthDate[0]))) > date("md") ? ((date("Y") - $birthDate[0]) - 1) : (date("Y") - $birthDate[0]));
        $date_of_birth = $age;

        $doctorScreenData['code'] = 200;
        $doctorScreenData['doctor_info'] = $doctor_info;
        $doctorScreenData['patient_name'] = $patient_name;
        $doctorScreenData['patient_age'] = $date_of_birth;
        return $this->sendResponse($doctorScreenData,"Doctor Side Video info");
    }
    public function diagnosis_doctor_side($session_id){
        $issable_data = DB::table('sessions')->where('sessions.id',$session_id)
        ->join('isabel_session_diagnosis','sessions.isabel_diagnosis_id','isabel_session_diagnosis.id' )
        ->select('isabel_session_diagnosis.*')
        ->first();

        if($issable_data){
            $diagnoses = unserialize($issable_data->diagnoses);
            $diagnoses_name = [];
            $diagnoses_redflag = [];
            foreach($diagnoses as $diagnose){
                $di_name =$diagnose->diagnosis_name;
                $red_flag =$diagnose->red_flag;
                if($red_flag == 'true'){
                    $redflag_diagnoses =$di_name;
                    array_push($diagnoses_redflag,$redflag_diagnoses);
                }
                array_push($diagnoses_name,$di_name);
            }
        }else{
            $diagnoses_name = 'null';
        }
        $diagnoseData['code'] = 200;
        $diagnoseData['diagnoses_name'] = $diagnoses_name;
        $diagnoseData['diagnoses_redflag'] = $diagnoses_redflag;
        return $this->sendResponse($diagnoseData,"Diagnoses List");
    }
    public function medician_categories(){
        $Medicines_categories = DB::table('products_sub_categories')->where('parent_id',38)->get();
        $Medicines_catData['code'] = 200;
        $Medicines_catData['Medicines_categories'] = $Medicines_categories;
        return $this->sendResponse($Medicines_catData,"Medicines Catrgories");
    }
    public function medician_categories_search(Request $request){
        $Medicines_categories = DB::table('products_sub_categories')->where('parent_id',38)->where('title','LIKE','%'.$request->search.'%')->first();
        $Medicines_catData['code'] = 200;
        $Medicines_catData['Medicines_categories'] = $Medicines_categories;
        return $this->sendResponse($Medicines_catData,"Medicines Catrgories");
    }
    public function medician_based_on_categories($id){
        $Medicines = DB::table('tbl_products')
            ->where('sub_category', $id)
            ->where('product_status', 1)
            ->where('is_approved', 1)
            ->get();
        // $Medicines = DB::table('tbl_products')->where('parent_category',38)->where('sub_category',$id)->get();
        $Medicines_catData['code'] = 200;
        $Medicines_catData['Medicines'] = $Medicines;
        return $this->sendResponse($Medicines_catData,"Medicines");
    }
    public function lab_test(){
        $lab_test = DB::table('quest_data_test_codes')->where([
                ['AOES_exist', null],
                ['DETAILS', '!=', ""],
                ['SALE_PRICE', '!=', ""],
            ])->get();
        $lab_test_Data['code'] = 200;
        $lab_test_Data['lab_test'] = $lab_test;
        return $this->sendResponse($lab_test_Data,"lab test");
    }
    public function lab_test_search(Request $request){
        $lab_test = DB::table('quest_data_test_codes')->where('TEST_NAME','LIKE','%'.$request->search.'%')->get();
        $lab_test_Data['code'] = 200;
        $lab_test_Data['lab_test'] = $lab_test;
        return $this->sendResponse($lab_test_Data,"lab test");
    }
    public function imaging_state_by_zipcode(Request $request){
        $zip = $request->zipcode;
        $getState = DB::table('tbl_zip_codes_cities')->where('zip_code', $zip)->first();
        $status = $getState->state;
        $state_name = $status;
        if ($state_name == '') {
            $stateError['code'] = 200;
            $stateError['state'] = 'not found';
            return $this->sendError($stateError,"Erorr");
        } else {
            $all_locations = DB::table('imaging_locations')->where('clinic_name', $state_name)->get()->toArray();
            if (count($all_locations) > 0) {
                $locationData['code'] = 200;
                $locationData['all_locations'] = $all_locations;
                return $this->sendResponse($all_locations,"Location found");
            } else {
                $locationDataError['code'] = 200;
                $locationDataError['all_locations'] = 'location not found';
                return $this->sendResponse($locationDataError,"Location Error");
            }
        }
    }
    public function imaging_category(){
        $data = DB::table('product_categories')->where('category_type','imaging')->get();
        if(!$data->isEmpty()){
            $status =200;
            $imaging_categories['code'] = $status;
            $imaging_categories['imaging_categories'] = $data;
            return $this->sendResponse($imaging_categories,"Imaging Categories");
        } else{
            $dataCode['code'] = 200;
            return $this->sendError($dataCode,"Error");
        }
    }
    public function imaging_product(){
        $data = DB::table('tbl_products')->where('mode','imaging')->where('product_status',1)->get();
        if(!$data->isEmpty()){
            $status =200;
            $imaging_products['code'] = $status;
            $imaging_products['imaging_products'] = $data;
            return $this->sendResponse($imaging_products,"Imaging Products");
        } else{
            $status =200;
            $imaging_products['code'] = $status;
            return $this->sendError($imaging_products,"No Image Found");
        }
    }
    public function imaging_category_product(Request $request){
        if ($request->name == '') {
            $products = DB::table('tbl_products')
                ->join('imaging_prices', 'imaging_prices.product_id', 'tbl_products.id')
                ->where('imaging_prices.location_id', $request->location_id)
                ->where('tbl_products.parent_category', $request->cat_id)
                ->where('imaging_prices.price','!=','0')
                ->select('tbl_products.id as pro_id', 'tbl_products.name as pro_name', 'imaging_prices.location_id')
                ->get();
        } else {
            $products = DB::table('tbl_products')
                ->join('imaging_prices', 'imaging_prices.product_id', 'tbl_products.id')
                ->where('tbl_products.name', 'LIKE', "%{$request->name}%")
                ->where('imaging_prices.location_id', $request->location_id)
                ->where('tbl_products.parent_category', $request->cat_id)
                ->select('tbl_products.id as pro_id', 'tbl_products.name as pro_name', 'imaging_prices.location_id')
                ->get();
        }
        if (count($products) > 0) {
            foreach ($products as $product) {
                $res = DB::table('prescriptions')->where('imaging_id', $product->pro_id)->where('session_id', $request->session_id)->first();
                if ($res != null) {
                    $product->added = 'yes';
                } else {
                    $product->added = 'no';
                }
            }
            $imagingData['code'] = 200;
            $imagingData['imaging_products'] = $products;
            return $this->sendResponse($products,"List of Imaging");
        } else {
            $imagingDataError['code'] = 200;
            return $this->sendResponse($imagingDataError,"No Imaging found");
        }
    }
    public function refer_doctor($id){
        $doctors = DB::table('users')
                ->where('user_type','doctor')
                ->where('specialization',$id)
                ->get(['id','name','last_name','user_image','nip_number','upin']);
        $referDoctor['code'] = 200;
        $referDoctor['refer_doctor_list'] = $doctors;
        return $this->sendResponse($referDoctor,"Refer Doctor list");
    }
    public function refer_doctor_search(Request $request, $id){
        $doctors = DB::table('users')
                ->where('user_type','doctor')
                ->where('specialization',$id)
                ->where('name','Like','%'.$request->search.'%')
                ->get(['id','name','last_name','user_image','nip_number','upin']);
        if($doctors->count() === 0){
            $referDoctor['code'] = 200;
            return $this->sendResponse($referDoctor,"doctor not found");
        } else{
            $referDoctor['code'] = 200;
            $referDoctor['refer_doctor_list'] = $doctors;
            return $this->sendResponse($referDoctor,"doctor found");
        }
    }
    public function refer_to_doctor(Request $request){
        $session_id = $request->session_id;
        $doctor_id = $request->doctor_id;
        $refered_doctor_id = $request->refered_to_doc;
        $patient_id = $request->patient_id;
        $comment = $request->comment;

        $res = Referal::create([
            'session_id' => $session_id,
            'doctor_id' => $doctor_id,
            'sp_doctor_id' => $refered_doctor_id,
            'patient_id' => $patient_id,
            'comment' => $comment,
            'status' => 'created'
        ]);
        $referDoc['code'] = 200;
        $referDoc['refer_info'] = $res;
        return $this->sendResponse($referDoc,'refered to doctor');
    }
    public function cancelReferal(Request $request){
        $referBtn = 0;
        // $session_id = $request->session_id;
        $refer_id =$request->refer_id;
        $ref_rec = Referal::find($refer_id);
        $session_id = $ref_rec->session_id;
        $session_data = Session::find($session_id);
        $res = Referal::where('id', $refer_id)->delete();
        $referData['code'] = 200;
        return $this->sendResponse($referData,'refer cancled');
    }
    public function doctor_end_session(Request $request){
        $end_time = Carbon::now()->addMinutes(15)->format('Y-m-d H:i:s');
        Session::where('id', $request->session_id)->update([
            'join_enable' => '0',
            'status' => 'ended',
            'end_time' => $end_time
        ]);
        $sessionData = Session::where('id', $request->session_id)->first();
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
            'identity' => $request->session_id,
            'party_involved' => $sessionData->patient_id,
        ]);
        ActivityLog::create([
            'activity' => 'ended session with Dr.' . $doctorDetail->name . " " . $doctorDetail->last_name,
            'type' => 'session end',
            'user_id' => $patientDetail->id,
            'user_type' => 'doctor',
            'identity' => $request->session_id,
            'party_involved' => $sessionData->doctor_id,
        ]);
        $referData = DB::table('referals')->where('session_id', $request->session_id)->first();
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
        try {
            $sessionData->received = false;
            // \App\Helper::firebase($sessionData->patient_id,'patientEndCall',$sessionData->id,$sessionData);
            // \App\Helper::firebase($sessionData->doctor_id,'updateQuePatient',$sessionData->id,$sessionData);
        } catch (\Throwable $th) {
            //throw $th;
        }
        event(new patientEndCall($request->session_id));
        event(new updateQuePatient('update patient que'));
        Log::info('run que update event');
        $callEnd['code'] = 200;
        return $this->sendResponse($callEnd,"Session completed");
    }
    public function patient_visit_history(Request $request){
        $all_sessions = DB::table('sessions')->where('patient_id', $request->patient_id)
                        ->where('status','!=', 'pending')
                        ->where('id', '!=', $request->session_id)
                        ->orderby('id','desc')
                        ->get();
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
                $session_record[] = ['date' => $date['date'], 'provider' => $doctor->name . ' ' . $doctor->last_name, 'symtems' => $final_symtems, 'note' => $session->provider_notes ?? 'none', 'diagnois' => $session->diagnosis ?? 'none', 'prescriptions' => $array];
            } else {
                $session_record[] = ['date' => $date['date'], 'provider' => $doctor->name . ' ' . $doctor->last_name, 'symtems' => $final_symtems, 'note' => $session->provider_notes ?? 'none', 'diagnois' => $session->diagnosis ?? 'none', 'prescriptions' => null];
            }
        }
        return $this->sendResponse($session_record,'Visit history');
    }
    public function add_medicine(Request $request){
        $medicine_id = $request->medicine_id;
        $session_id =$request->session_id;
        $user_id =  $request->user_id;
        $pres = DB::table('prescriptions')->where('session_id',$session_id)->where('medicine_id',$medicine_id)->get();
        if(count($pres)==0){
            Prescription::insert([
                'session_id' => $session_id,
                'medicine_id' => $medicine_id,
                'type' => 'medicine',
                'quantity' => 1,
                'created_at' => Carbon::now(),
            ]);
            event(new LoadPrescribeItemList($session_id, $user_id));
            try {
                // \App\Helper::firebase($session_id,'LoadPrescribeItemList',$session_id,$user_id);
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
        $pres['code'] = 200;
        return $this->sendResponse($pres,'medicine added');
    }
    public function check_aeos(Request $request){
        $getTestAOE = QuestDataAOE::select('id as question_id', "TEST_CD AS TestCode", "AOE_QUESTION_DESC AS QuestionLong")
            ->where('TEST_CD', $request->test_code)
            ->groupBy('AOE_QUESTION_DESC')
            ->get()
            ->toArray();
        if ($getTestAOE == null || $getTestAOE == "") {
            $checkAeos['code'] = 200;
            $checkAeos['no_response'] = 'not found';
            return $this->sendResponse($checkAeos,'aeos response');
        } else {
            $checkAeos['code'] = 200;
            $checkAeos['response'] = $getTestAOE;
            return $this->sendResponse($checkAeos,'aeos response');
        }
    }
    public function aoes_submit(Request $request){
        $aoes = serialize($request->data);
        $record = DB::table('patient_lab_recomend_aoe')
            ->where('testCode', $request->test_cd)
            ->where('session_id', $request->session_id)
            ->count();
        if ($record > 0) {
            DB::table('patient_lab_recomend_aoe')
                ->where('testCode', $request->test_cd)
                ->where('session_id', $request->session_id)
                ->update([
                    'aoes' => $aoes
                ]);
                $aeosData['code']= 200;
            return $this->sendResponse($aeosData,'aeos updated ');
        } else {
            DB::table('patient_lab_recomend_aoe')
                ->insert([
                    'aoes' => $aoes,
                    'testCode' => $request->test_cd,
                    'session_id' => $request->session_id,
                ]);
                $aeosData['code']= 200;
            return $this->sendResponse($aeosData,'aeos created');
        }
    }
    public function add_lab(Request $request){
        $lab_id = $request->lab_id;
        $session_id =$request->session_id;
        $user_id =  $request->user_id;
        $pres = DB::table('prescriptions')->where('session_id',$session_id)->where('test_id',$lab_id)->get();
        if(count($pres)==0){
            Prescription::insert([
                'session_id' => $session_id,
                'test_id' => $lab_id,
                'type' => 'lab-test',
                'quantity' => 1,
                'created_at' => Carbon::now(),
            ]);
            event(new LoadPrescribeItemList($session_id, $user_id));
            try {
                // \App\Helper::firebase($session_id,'LoadPrescribeItemList',$session_id,$user_id);
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
        $prescription['code'] = 200;
        $prescription['prescription'] = $pres;
        return $this->sendResponse($prescription,'Lab added');
    }
    public function fetch_state_zipcode(Request $request)
    {
        $zip = $request->zipcode;
        $getState = DB::table('tbl_zip_codes_cities')->where('zip_code', $zip)->first();
        $status = $getState->state;
        $state_name = $status;
        if ($state_name == '') {
            $fatchLocation['code'] = 200;
            return $this->sendResponse($fatchLocation,'No state found based on zipcode!');
        } else {
            $all_locations = DB::table('imaging_locations')->where('clinic_name', $state_name)->get()->toArray();
            if (count($all_locations) > 0) {
                $fatchLocation['code'] = 200;
                $fatchLocation['all_locations'] =$all_locations;
                return $this->sendResponse($fatchLocation,'Location list based on zipcode');
            } else {
                $fatchLocation['code'] = 200;
                return $this->sendResponse($fatchLocation,'No location found based on zipcode!');
            }
        }
    }
    public function add_imaging(Request $request){
        $imaging_id = $request->imaging_id;
        $session_id =$request->session_id;
        $user_id =  $request->user_id;
        $location_id =  $request->location_id;
        $dre = DB::table('imaging_selected_location')->insert([
            'session_id' => $session_id,
            'product_id' => $imaging_id,
            'imaging_location_id' => $location_id,
        ]);
        $pres = DB::table('prescriptions')->where('session_id',$session_id)->where('imaging_id',$imaging_id)->get();
        // dd(count($pres));
        if(count($pres)==0){
            Prescription::insert([
                'session_id' => $session_id,
                'imaging_id' => $imaging_id,
                'type' => 'imaging',
                'quantity' => 1,
                'created_at' => Carbon::now(),
            ]);
            event(new LoadPrescribeItemList($session_id, $user_id));
            try {
                // \App\Helper::firebase($session_id,'LoadPrescribeItemList',$session_id,$user_id);
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
        $presData['code'] = 200;
        $presData['imaging'] = $pres;
        return $this->sendResponse($presData,'Imaging added');
    }
    public function remove_prescription(Request $request){
        $session_id = $request->session_id;
        $pro_id = $request->product_id;
        $user_id =  $request->user_id;
        $type = $request->type;
        if ($type == "lab-test") {
           Prescription::where('session_id', $session_id)->where('test_id', $pro_id)->delete();
        } else if ($type == "imaging") {
           $img = Prescription::where('session_id', $session_id)->where('imaging_id', $pro_id)->delete();
        } else if ($type == "medicine") {
            Prescription::where('session_id', $session_id)->where('medicine_id', $pro_id)->delete();
        }
        event(new LoadPrescribeItemList($session_id, $user_id));
        try {
            // \App\Helper::firebase($session_id,'LoadPrescribeItemList',$session_id,$user_id);
        } catch (\Throwable $th) {
            //throw $th;
        }
        $removePres['code'] = 200;
        return $this->sendResponse($removePres,'Prescription removed!');
    }
    public function get_prescribe_item_list(Request $request){
        $items = [];
        $session_id = $request->session_id;
        $array_data = [];
        $cn =[];
        $pro_lists = Prescription::where('session_id', $session_id)->get();
        foreach ($pro_lists as $key => $pro_list) {
            if ($pro_list->type == "lab-test") {
                $labData = \App\QuestDataTestCode::where('TEST_CD', $pro_list->test_id)->first();
                $getTestAOE = QuestDataAOE::select("TEST_CD AS TestCode", "AOE_QUESTION AS QuestionShort", "AOE_QUESTION_DESC AS QuestionLong")
                    ->where('TEST_CD', $pro_list->test_id)
                    ->groupBy('AOE_QUESTION_DESC')
                    ->get();
                $count = count($getTestAOE);
                if ($count > 0) {
                    $labData->aoes = 1;
                } else {
                    $labData->aoes = 0;
                }
                $items[$key]['id'] = $pro_list->test_id;
                $items[$key]['name'] = $labData->TEST_NAME;
                $items[$key]['aoes'] = $labData->aoes;
                $items[$key]['type'] = $pro_list->type;
                $labname = $pro_list->name;
            } else if ($pro_list->type == "imaging") {
                $data = $this->allProductsRepository->find($pro_list->imaging_id);
                $res = DB::table('imaging_selected_location')->where('session_id', $session_id)->where('product_id', $data->id)->first();
                if ($res != null) {
                    $get = DB::table('imaging_locations')->where('imaging_locations.id', $res->imaging_location_id)->first();
                    $data->location = $get->clinic_name . ', ' . $get->city . ', ' . $get->zip_code;
                } else {
                    $data->location = 'nothing';
                }
                $items[$key]['id'] = $data->id;
                $items[$key]['name'] = $data->name;
                $items[$key]['aoes'] = 0;
                $items[$key]['type'] = $pro_list->type;
            } else if ($pro_list->type == "medicine") {
                if ($pro_list->usage != null) {
                    $getRes = $this->allProductsRepository->find($pro_list->medicine_id);
                    $getRes->usage = $pro_list->usage;
                    $items[] = $getRes;
                } else {
                    $getRes = $this->allProductsRepository->find($pro_list->medicine_id);
                    $getRes->usage = "";
                    $items[$key]['id'] = $getRes->id;
                    $items[$key]['name'] = $getRes->name;
                    $items[$key]['aoes'] = 0;
                    $items[$key]['type'] = $pro_list->type;
                }
            }
        }
        $product = $items;
        $prescription['code'] = 200;
        $prescription['product'] = $product;
        return $this->sendResponse($prescription,'Prescription');
    }
    public function provider_notes_update(Request $request){
        $session_id = $request['session_id'];
        $session = Session::where('id', $session_id)->first();
        if($session->validation_status == "valid")
        {
            Session::where('id', $session_id)->update(['provider_notes' => $request['note'], 'diagnosis' => $request['diagnosis']]);
            $providerNote['code'] = 200;
            $providerNote['session_id'] = $session_id;
            return $this->sendResponse($providerNote,'provider notes updated ');
        }
        else
        {
            $providerNote['code'] = 200;
            return $this->sendError('providerNote','Session expired!');
        }
    }
    public function recommendation_display(Request $request){
        $sessionData = Session::where('id', $request->session_id)->first();
        if($sessionData->validation_status == "valid")
        {
            $items = [];
            $pro_lists = Prescription::where('session_id', $request['session_id'])->get();
            foreach ($pro_lists as $pro_list) {
                if ($pro_list->type == "lab-test") {
                    $labData = \App\QuestDataTestCode::where('TEST_CD', $pro_list->test_id)->first();
                    $getTestAOE = QuestDataAOE::select("TEST_CD AS TestCode", "AOE_QUESTION AS QuestionShort", "AOE_QUESTION_DESC AS QuestionLong")
                        ->where('TEST_CD', $pro_list->test_id)
                        ->groupBy('AOE_QUESTION_DESC')
                        ->get();
                    $count = count($getTestAOE);
                    if ($count > 0) {
                        $labData->aoes = 1;
                    } else {
                        $labData->aoes = 0;
                    }
                    $labData->pres_id = $pro_list->id;
                    $items[] = $labData;
                } else if ($pro_list->type == "imaging") {
                    $data = $this->allProductsRepository->find($pro_list->imaging_id);
                    $res = DB::table('imaging_selected_location')->where('session_id', $request['session_id'])->where('product_id', $data->id)->first();
                    if ($res != null) {
                        $get = DB::table('imaging_locations')->where('imaging_locations.id', $res->imaging_location_id)->first();
                        $imaging_prices = DB::table('imaging_prices')->where('product_id',$data->id)->first();
                        // $items
                        $data->location = $get->address;
                        $data->imaging_price = $imaging_prices->price;
                    } else {
                        $data->location = 'nothing';
                    }
                    $data->pres_id = $pro_list->id;
                    $items[] = $data;

                } else if ($pro_list->type == "medicine") {
                    if ($pro_list->usage != null) {
                        $getRes = $this->allProductsRepository->find($pro_list->medicine_id);
                        $getRes->usage = $pro_list->usage;
                        $getRes->pres_id = $pro_list->id;
                        $items[] = $getRes;
                    } else {
                        $getRes = $this->allProductsRepository->find($pro_list->medicine_id);
                        $getRes->usage = "";
                        $getRes->pres_id = $pro_list->id;
                        $items[] = $getRes;
                    }
                }
            }
            $pres = collect($items);
            $getSession = Session::where('id', $request->session_id)->first();
            $recommendationData['code'] = 200;
            $recommendationData['pres'] = $pres;
            $recommendationData['getSession'] = $getSession;
            return $this->sendResponse($recommendationData,'Recommendation Data');
        }
        else
        {
            $recommendationDisplay['code'] = 200;
            return $this->sendResponse($recommendationDisplay,'Session Expired');
        }
    }
    public function add_dosage(Request $request){
        $res = Prescription::where('session_id', $request['session_id'])->where('medicine_id', $request['product_id'])->first();
        $medicine_price = DB::table('medicine_pricings')
            ->where('product_id', $request->product_id)
            ->where('unit_id', $request->unit_id)
            ->where('days_id', $request->day_id)
            // ->select('price', 'sale_price')
            ->first();
        $day =DB::table('medicine_days')->where('id',$request->day_id)->first();
        $unit =DB::table('medicine_units')->where('id',$request->unit_id)->first();
        $res->update([
            'med_days' => $day->days,
            'med_unit' => $unit->unit,
            'med_time' => $request['med_time'],
            'price' => $medicine_price->sale_price,
            'comment' => $request['instructions'],
            'usage' => 'Dosage: Every ' . $request['med_time'] . ' hours for ' .$day->days,
        ]);
        if ($res) {
            // $response Prescription::where('session_id', $request['session_id'])->where('medicine_id', $request['product_id'])
            $dosage['code'] = 200;
            return $this->sendResponse($dosage,'Dosage Added');
        } else{
            $dosage['code'] = 200;
            return $this->sendResponse($dosage,'Medicine not found!');
        }
    }
    public function get_med_detail(Request $request){
        $product_id = $request->product_id;
        $session_id = $request->session_id;
        $response['product'] = \DB::table('tbl_products')
            ->join('medicine_pricings', 'medicine_pricings.product_id', '=', 'tbl_products.id')
            ->where('tbl_products.id', $product_id)
            ->select('tbl_products.id', 'tbl_products.name', 'medicine_pricings.price', 'medicine_pricings.sale_price')
            ->first();
        $response['days'] = \DB::table('tbl_products')
            ->join('medicine_pricings', 'medicine_pricings.product_id', '=', 'tbl_products.id')
            ->join('medicine_days', 'medicine_days.id', '=', 'medicine_pricings.days_id')
            ->groupBy('medicine_days.id')
            ->where('tbl_products.id', $product_id)
            ->select('medicine_days.id', 'medicine_days.days', 'medicine_pricings.price', 'medicine_pricings.sale_price')
            ->get();
        $response['units'] = \DB::table('tbl_products')
            ->join('medicine_pricings', 'medicine_pricings.product_id', '=', 'tbl_products.id')
            ->join('medicine_units', 'medicine_units.id', '=', 'medicine_pricings.unit_id')
            ->groupBy('medicine_units.id')
            ->where('tbl_products.id', $product_id)
            ->select('medicine_units.id', 'medicine_units.unit', 'medicine_pricings.price', 'medicine_pricings.sale_price')
            ->get();
        $res = Prescription::where('session_id', $session_id)->where('medicine_id', $product_id)->first();
        $res->med_time;
        if ($res->med_days != null && $res->med_unit != null && $res->med_time != null) {
            $response['update'] = ['days' => $res->med_days, 'units' => $res->med_unit, 'time' => $res->med_time, 'comment' => $res->comment];
        }
        return $this->sendResponse($response,'Dosage Detail');
    }
    public function store_cart_for_user(Request $request){
        $session_id = $request['session_id'];
        $diagnosis = $request['diagnosis'];
        $notes = $request['note'];
        $session = Session::find($session_id);
        $patient_user = User::find($session->patient_id);

        if ($session['appointment_id'] != null) {
            Appointment::where('id', $session['appointment_id'])->update(['status' => 'complete']);
        }
        $pres_list = Prescription::where('session_id', $session_id)->get();
        $items = count($pres_list);
        $dataMarge = [];
        $prePharma = [];
        $preLab = [];
        $preImaging = [];
        Session::where('id', $session_id)->update(['validation_status' => 'expired']);
        if ($items > 0) {
            foreach ($pres_list as $pres) {
                $product = DB::table('tbl_products')->where('id', $pres->medicine_id)->first();
                if ($pres->type == "medicine") {
                    $med_unit = DB::table('medicine_units')->where('unit',$pres->med_unit)->first();
                    $med_day = DB::table('medicine_days')->where('days',$pres->med_days)->first();
                    $price = DB::table('medicine_pricings')
                    ->where('product_id', $pres->medicine_id)
                    ->where('unit_id',$med_unit->id)
                    ->where('days_id',$med_day->id)
                    ->first();
                    $pres->price = $price->sale_price;
                    $up = DB::table('prescriptions')->where('id',$pres->id)->update(['price' => $price->id]);
                    Cart::create([
                        'product_id' => $pres->medicine_id,
                        'name' => $product->name,
                        'quantity' => $pres->quantity,
                        'price' => $pres->price,
                        'update_price' => $pres->price * $pres->quantity,
                        'product_mode' => $pres->type,
                        'user_id' => $session->patient_id,
                        'doc_id' => $session->doctor_id,
                        'doc_session_id' => $session_id,
                        'pres_id' => $pres->id,
                        'item_type' => 'prescribed',
                        'status' => 'recommended',
                        'checkout_status' => 1,
                        'purchase_status' => 1,
                        'product_image' => $product->featured_image
                    ]);
                    $singleItemMedicine = [
                        'medicine_name' => $product->name,
                        'quantity' => $pres->quantity,
                        'usage' => $pres->usage,
                        'comment' => $pres->comment,
                    ];
                    array_push($prePharma, $singleItemMedicine);
                } else if ($pres->type == "lab-test") {
                    $lab_test_price = QuestDataTestCode::where('TEST_CD', $pres->test_id)->first();
                    Cart::create([
                        'product_id' => $pres->test_id,
                        'name' => $lab_test_price->DESCRIPTION,
                        'quantity' => $pres->quantity,
                        'price' => $lab_test_price->SALE_PRICE,
                        'update_price' => $lab_test_price->SALE_PRICE * $pres->quantity,
                        'product_mode' => $pres->type,
                        'user_id' => $session->patient_id,
                        'doc_id' => $session->doctor_id,
                        'doc_session_id' => $session_id,
                        'pres_id' => $pres->id,
                        'item_type' => 'prescribed',
                        'status' => 'recommended',
                        'checkout_status' => 1,
                        'purchase_status' => 1,
                        'product_image' => $lab_test_price->featured_image,
                    ]);
                    $singleItemTest = [
                        'test_name' => $lab_test_price->DESCRIPTION,
                        'quantity' => $pres->quantity,
                        'comment' => $pres->comment,
                    ];
                    array_push($preLab, $singleItemTest);
                } else if ($pres->type == "imaging") {
                    $product = DB::table('tbl_products')->where('id', $pres->imaging_id)->first();
                    $location = DB::table('imaging_selected_location')->where('session_id', $pres->session_id)->where('product_id', $pres->imaging_id)->first();
                    $imaging_price = ImagingPrices::where('location_id', $location->imaging_location_id)->where('product_id',$pres->imaging_id)->first();
                    Cart::create([
                        'product_id' => $pres->imaging_id,
                        'name' => $product->name,
                        'quantity' => $pres->quantity,
                        'price' => $imaging_price->price,
                        'update_price' => $imaging_price->price * $pres->quantity,
                        'product_mode' => $pres->type,
                        'user_id' => $session->patient_id,
                        'doc_id' => $session->doctor_id,
                        'doc_session_id' => $session_id,
                        'pres_id' => $pres->id,
                        'item_type' => 'prescribed',
                        'status' => 'recommended',
                        'checkout_status' => 1,
                        'purchase_status' => 1,
                        'product_image' => $product->featured_image,
                        'location_id' => $location->imaging_location_id
                    ]);
                    $singleItemImaging = [
                        'imaging_name' => $product->name,
                        'quantity' => $pres->quantity,
                        'comment' => $pres->comment,
                    ];
                    array_push($preImaging, $singleItemImaging);
                }
            }
            Session::where('id', $session_id)->update([
                'diagnosis' => $diagnosis,
                'provider_notes' => $notes,
                'queue' => 0,
                'status' => 'ended',
                'join_enable' => '0',
                'cart_flag' => '1'
            ]);
        } else {
            Session::where('id', $session_id)->update(['cart_flag' => '1']);
        }
        try {
            array_push($dataMarge, array('pat_name' => ucwords($patient_user->name)));
            array_push($dataMarge, array('rec_test' => $preLab));
            array_push($dataMarge, array('rec_pharma' => $prePharma));
            array_push($dataMarge, array('rec_imaging' => $preImaging));
            array_push($dataMarge, array('pat_email' => ucwords($patient_user->email)));
            if($dataMarge[1]['rec_test'] != [] || $dataMarge[2]['rec_pharma'] != [] || $dataMarge[3]['rec_imaging'] != []){
                Mail::to($patient_user->email)->send(new patientEvisitRecommendationMail($dataMarge));
            }
            $text = "Session Complete Please Check Recommendations";
            $notification_id = Notification::create([
                'user_id' => $patient_user->id,
                'type' => '/my/cart',
                'text' => $text,
                'session_id' => $session_id,
            ]);
            $data = [
                'user_id' => $patient_user->id,
                'type' => '/my/cart',
                'text' => $text,
                'session_id' => $session_id,
                'received' => 'false',
                'appoint_id' => 'null',
                'refill_id' => 'null',
            ];
            // \App\Helper::firebase($patient_user->id,'notification',$notification_id->id,$data);
            event(new RealTimeMessage($patient_user->id));
        } catch (\Exception $e) {
            Log::error($e);
        }
        if ($items > 0) {
            ActivityLog::create([
                'activity' => 'Create session recommendations for ' . $patient_user->name . " " . $patient_user->last_name,
                'type' => 'session recommendations',
                'user_id' => $session->doctor_id,
                'user_type' => 'doctor',
                'identity' => $pres_list[0]->id,
                'party_involved' => $session->patient_id,
            ]);
        }
        $referal = DB::table('referals')->where('session_id', $session_id)->first();
        if ($referal != null) {
            $admin_detail = DB::table('users')->where('users.user_type', 'admin')->first();
            $doc_detail = DB::table('users')->where('users.id', $referal->doctor_id)->first();
            $refer_doc_detail = DB::table('users')->where('users.id', $referal->sp_doctor_id)->first();
            $pat_detail = DB::table('users')->where('users.id', $referal->patient_id)->first();
            $data = [
                'pat_name' => $pat_detail->name,
                'ref_from_name' => $doc_detail->name,
                'ref_to_name' => $refer_doc_detail->name,
                'ref_to_email' => $refer_doc_detail->email,
            ];
            $data1 = [
                'pat_name' => $pat_detail->name,
                'ref_from_name' => $doc_detail->name,
                'ref_to_name' => $refer_doc_detail->name,
                'pat_email' => $pat_detail->email,
                'comment' => $referal->comment,
            ];
            $admin_data = [
                'pat_name' => $pat_detail->name,
                'ref_from_name' => $doc_detail->name,
                'ref_to_name' => $refer_doc_detail->name,
                'pat_email' => $pat_detail->email,
                'comment' => $referal->comment,
            ];
            try {
                Mail::to($pat_detail->email)->send(new ReferDoctorToPatientMail($data));
                Mail::to($refer_doc_detail->email)->send(new ReferDoctorToDoctorMail($data1));
                Mail::to($admin_detail->email)->send(new ReferDoctorToDoctorMail($admin_data));

                $notification_id = Notification::create([
                    'user_id' => $referal->sp_doctor_id,
                    'text' => 'New patient is refered to you from Dr.' . $doc_detail->name,
                    'type' => '/patient/detail/' . $referal->patient_id,
                    'status' => 'new',
                    'session_id' => $session_id,
                ]);
                $text = 'Dr.'.$refer_doc_detail->name.' '.$refer_doc_detail->last_name.' reffered (Click & book an appointment)';
                $notification_id2 = Notification::create([
                    'user_id' => $referal->patient_id,
                    'text' => $text,
                    'type' => '/view/doctor/'.\Crypt::encrypt($refer_doc_detail->id),
                    'status' => 'new',
                    'session_id' => $session_id,
                ]);
                $data = [
                    'user_id' => $referal->patient_id,
                    'text' => $text,
                    'type' => '/view/doctor/'.\Crypt::encrypt($refer_doc_detail->id),
                    'session_id' => $session_id,
                    'received' => 'false',
                    'appoint_id' => 'null',
                    'refill_id' => 'null',
                ];
                // \App\Helper::firebase($referal->patient_id,'notification',$notification_id2->id,$data);
                event(new RealTimeMessage($referal->patient_id));
                $data = [
                    'user_id' => $referal->sp_doctor_id,
                    'text' => 'New patient is refered to you from Dr.' . $doc_detail->name,
                    'type' => '/patient/detail/' . $referal->patient_id,
                    'session_id' => $session_id,
                    'received' => 'false',
                    'appoint_id' => 'null',
                    'refill_id' => 'null',
                ];
                // \App\Helper::firebase($referal->patient_id,'notification',$notification_id->id,$data);
                event(new RealTimeMessage($referal->sp_doctor_id));
            } catch (\Throwable $th) {
                event(new redirectToCart($session->id));
                $sessionComplete['code'] = 200;
                return $this->sendResponse($sessionComplete,'Session Done Doctor redirect to doctor_queue');
            }
        }
        event(new redirectToCart($session->id));
        $sessionComplete['code'] = 200;
        return $this->sendResponse($sessionComplete,'Session Done Doctor redirect to doctor_queue');
    }
}
