<?php

namespace App\Http\Controllers\Api;

use App\ActivityLog;
use App\Helper;
use App\Http\Controllers\Controller;
use App\Models\InClinics;
use App\Repositories\TblOrdersRepository;
use App\MedicalProfile;
use App\User;
use Auth;
use DB;
use Illuminate\Http\Request;

class PatientsController extends BaseController
{

    private $tblOrdersRepository;

    public function __construct(TblOrdersRepository $tblOrdersRepo)
    {

        $this->tblOrdersRepository = $tblOrdersRepo;
    }

    public function fetch_pending_imagings($user_id,Request $request)
    {
        if($request->ajax())
        {
            $imagings = DB::table('sessions')
            ->join('tbl_cart','sessions.id','tbl_cart.doc_session_id')
            ->where('tbl_cart.user_id',$user_id)
            ->where('tbl_cart.product_mode','imaging')
            ->where('tbl_cart.item_type','prescribed')
            ->where('tbl_cart.status','purchased')
            ->select('tbl_cart.*','sessions.date as session_date')
            ->latest()
            ->paginate(4,['*'],'pimagings');
            return view('dashboard_doctor.All_patient.pimagings_page',compact('imagings'));
        }
        else{
            $imagings = DB::table('sessions')
            ->join('tbl_cart','sessions.id','tbl_cart.doc_session_id')
            ->where('tbl_cart.user_id',$user_id)
            ->where('tbl_cart.product_mode','imaging')
            ->where('tbl_cart.item_type','prescribed')
            ->where('tbl_cart.status','purchased')
            ->latest()
            ->select('tbl_cart.*','sessions.date as session_date')
            ->paginate(4,['*'],'pimagings');
            return $imagings;
        }
    }

    public function fetch_pending_labs($user_id,Request $request)
    {
        if($request->ajax())
        {
            $labs = DB::table('sessions')
            ->join('tbl_cart','sessions.id','tbl_cart.doc_session_id')
            ->where('tbl_cart.user_id',$user_id)
            ->where('tbl_cart.product_mode','lab-test')
            ->where('tbl_cart.item_type','prescribed')
            ->where('tbl_cart.status','purchased')
            ->select('tbl_cart.*','sessions.date as session_date')
            ->latest()
            ->paginate(4,['*'],'plabs');
            return view('dashboard_doctor.All_patient.plabs_page',compact('labs'));
        }
        else
        {
            $labs = DB::table('sessions')
            ->join('tbl_cart','sessions.id','tbl_cart.doc_session_id')
            ->where('tbl_cart.user_id',$user_id)
            ->where('tbl_cart.product_mode','lab-test')
            ->where('tbl_cart.item_type','prescribed')
            ->where('tbl_cart.status','purchased')
            ->select('tbl_cart.*','sessions.date as session_date')
            ->latest()
            ->paginate(4,['*'],'plabs');
            return $labs;
        }
    }


    public function get_patient_dasboard_info()
    {
        $user = Auth::user();
        $doctors = DB::table('sessions')
            ->where('patient_id', $user->id)
            ->where('status', '!=', 'pending')
            ->groupBy('doctor_id')
            ->select('*')
            ->get();

        $orders = DB::table('tbl_orders')
            ->where('customer_id', $user->id)
            ->get();

        $reports = DB::table('quest_results')
            ->where('pat_id', $user->id)
            ->get();

        $unread_reports = DB::table('quest_results')
            ->where('pat_id', $user->id)
            ->where('is_read', null)
            ->count();

        $pending_appoints = DB::table('appointments')
            ->join('sessions', 'appointments.id', 'sessions.appointment_id')
            ->where('appointments.patient_id', $user->id)
            ->where('appointments.status', 'pending')
            ->where('sessions.status', '!=', 'pending')
            ->count();
        
            $upComingAppointment = DB::table('appointments')
            ->where('patient_id', $user->id)
            ->whereNotIn('status', [
                'doctor has cancelled the appointment',
                'patient has cancelled the appointment',
                'completed'
            ])
            ->whereRaw("STR_TO_DATE(CONCAT(date, ' ', time), '%Y-%m-%d %H:%i:%s') > ?", [now()])
            ->orderByRaw("STR_TO_DATE(CONCAT(date, ' ', time), '%Y-%m-%d %H:%i:%s') ASC")
            ->first();

        $pending_sessions = DB::table('sessions')
            ->where('patient_id', $user->id)
            ->where('appointment_id', null)
            ->where('status', '!=', 'ended')
            ->where('reminder', null)
            ->orderby('id', 'desc')
            ->first();

        $med_profile = DB::table('medical_profiles')
            ->where('user_id', $user->id)
            ->count();

        $chat = DB::table('chat')->where('token_status', 'unsolved')->where('user_id', $user->id)->get();

        if ($pending_sessions != null) {
            $total_reminds = $unread_reports + $pending_appoints + 1;
        } else {
            $total_reminds = $unread_reports + $pending_appoints;
        }
        $session = DB::table('sessions')->where('patient_id', Auth::user()->id)->where('status', 'ended')->where('remaining_time', '!=', 'full')->orderby('id', 'desc')->first();
        if ($session == null) {
            $ses_id = 0;
            $ses_feed = 1;
        } else {
            $ses_id = $session->id;
            $ses_feed = $session->feedback_flag;
        }

        return $this->sendResponse([
            'doctors' => $doctors,
            'chat' => $chat,
            'orders' => $orders,
            'reports' => $reports,
            'unread_reports' => $unread_reports,
            'pending_appoints' => $pending_appoints,
            'pending_sessions' => $pending_sessions,
            'total_reminds' => $total_reminds,
            'med_profile' => $med_profile,
            'ses_id' => $ses_id,
            'ses_feed' => $ses_feed,
            'upComingAppointment' => $upComingAppointment,
        ], 'Patient Dashboard Info');
    }

public function patient_medical_prfile_save(Request $request)
{
    try {

        $user = auth()->user()->id;
        
        $symptomsArray = [];
        if ($request->has('symp')) {
            $symptomsArray = json_decode($request->symp, true);

            $symptoms = implode(',', $symptomsArray);
        } else {
            $symptoms = "";
        }
        $immunization_history = [];
        if ($request->has('immun_name') && $request->has('immun_when')) {
            $immun_names = json_decode($request->immun_name, true);
            $immun_whens = json_decode($request->immun_when, true);
            
            if (is_array($immun_names) && is_array($immun_whens)) {
                for ($i = 0; $i < count($immun_names); $i++) {
                    if (isset($immun_names[$i]) && isset($immun_whens[$i])) {
                        $temp['name'] = $immun_names[$i];
                        $temp['when'] = $immun_whens[$i];
                        $temp['flag'] = 'yes';
                        $immunization_history[] = $temp;
                    }
                }
            }
        }
        $immunization_history = json_encode($immunization_history);

        $family_history = [];
        if ($request->has('family') && $request->has('disease') && $request->has('age')) {
            $families = json_decode($request->family, true);
            $diseases = json_decode($request->disease, true);
            $ages = json_decode($request->age, true);
            
            if (is_array($families) && is_array($diseases) && is_array($ages)) {
                for ($i = 0; $i < count($families); $i++) {
                    if (isset($families[$i]) && isset($diseases[$i]) && isset($ages[$i])) {
                        $fam_arr['family'] = $families[$i];
                        $fam_arr['disease'] = $diseases[$i];
                        $fam_arr['age'] = $ages[$i];
                        $family_history[] = $fam_arr;
                    }
                }
            }
        }
        $family_history = json_encode($family_history);

        $medication_history = [];
        if ($request->has('med_name') && $request->has('med_dosage')) {
            $med_names = json_decode($request->med_name, true);
            $med_dosages = json_decode($request->med_dosage, true);
            
            if (is_array($med_names) && is_array($med_dosages)) {
                for ($i = 0; $i < count($med_names); $i++) {
                    if (isset($med_names[$i]) && isset($med_dosages[$i])) {
                        $med_arr['med_name'] = $med_names[$i];
                        $med_arr['med_dosage'] = $med_dosages[$i];
                        $medication_history[] = $med_arr;
                    }
                }
            }
        }
        $medication_history = json_encode($medication_history);

        $med = MedicalProfile::where('user_id', $user)->count();
        
        if ($med > 0) {
            $profile = MedicalProfile::where('user_id', $user)
                ->orderByDesc('id')
                ->first();
                
            $profile->update([
                'allergies' => $request->input('allergies', ''),
                'previous_symp' => $symptoms,
                'immunization_history' => $immunization_history,
                'family_history' => $family_history,
                'surgeries' => $request->input('surgeries', ''),
                'comment' => $request->input('comm', ''),
                'medication' => $medication_history,
            ]);
            
            $update = true;
        } else {
            $profile = MedicalProfile::create([
                'user_id' => $user,
                'allergies' => $request->input('allergies', ''),
                'previous_symp' => $symptoms,
                'immunization_history' => $immunization_history,
                'family_history' => $family_history,
                'surgeries' => $request->input('surgeries', ''),
                'comment' => $request->input('comm', ''),
                'medication' => $medication_history,
            ]);
            
            $update = false;
        }

        if ($request->hasFile('certificate')) {
            $images = $request->file('certificate');
            foreach ($images as $image) {
                $filename = \Storage::disk('s3')->put('medical_records', $image);
                DB::table('medical_records')->insert([
                    'user_id' => $user,
                    'record_file' => $filename,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $responseMessage = $update ? 
            'Successfully Updated Medical History Record' : 
            'Successfully Created Medical History Record';
            
        return $this->sendResponse($profile, $responseMessage);
        
    } catch (\Exception $e) {
        \Log::error('Medical profile update error: ' . $e->getMessage());
        return $this->sendError('Error updating medical profile', $e->getMessage(), 500);
    }
}
    public function pat_medical_profile()
    {
        $user_id = auth()->user()->id;
        $med_prof_exists = MedicalProfile::where('user_id', $user_id)->get()->count();
        $update = false;
        $med_files = DB::table('medical_records')->where('user_id',$user_id)->get();

        if ($med_files->isEmpty()) {
            $med_files = null;
        } else {
            foreach ($med_files as $file) {
                $file->record_file = \App\Helper::check_bucket_files_url($file->record_file);
            }
        }

        if ($med_prof_exists >= 1) {
            $update = true;
            $profile = DB::table('medical_profiles')->where('user_id', $user_id)->orderByDesc('id')->first();
            $profile->family_history = json_decode($profile->family_history);
            $profile->medication = json_decode($profile->medication);
            $profile->immunization_history = json_decode($profile->immunization_history);
            $profile->updated_at = User::convert_utc_to_user_timezone($user_id, $profile->updated_at)['datetime'];
            $profile->previous_symp = explode(",",$profile->previous_symp);
            array_pop($profile->previous_symp);
            $diseases = array("cancer", "hypertension", "heart-disease", "diabetes", "stroke", "mental", "drugs", "glaucoma", "bleeding", "others");

            return $this->sendResponse(['profile' => $profile, 'update' => $update, 'diseases' => $diseases, 'med_files' => $med_files], 'Medical Profile');
        }

        return $this->sendResponse(['profile' => null, 'update' => $update, 'diseases' => [], 'med_files' => $med_files], 'Medical Profile');
    }

    public function view_patient_record(Request $request)
    {
        $user_type = auth()->user()->user_type;
        $id = $request['id'];
        $pat = User::find($id);
        $user = $pat;
        $pat_name = Helper::get_name($id);
        $pat_info = User::patient_info($id);
        $sessions = User::get_full_session_details($id);
        $inclinic = InClinics::with(['user','prescriptions','doctor'])->where('user_id',$id)->orderBy('id','desc')->paginate(10);
        $user_obj = new User();
        $tblOrders = $this->tblOrdersRepository->getOrdersByUserID($id);
        foreach ($tblOrders as $order) {
            $order->created_at = User::convert_utc_to_user_timezone($user->id,$order->created_at);
        }
        foreach ($sessions as $session) {
            $session->date = User::convert_utc_to_user_timezone($user->id, $session->created_at)['date'];

            $session->start_time = date('h:i A',strtotime('-15 minutes',strtotime($session->start_time)));
            $session->start_time = User::convert_utc_to_user_timezone($user->id, $session->start_time)['time'];

            $session->end_time = date('h:i A',strtotime('-15 minutes',strtotime($session->end_time)));
            $session->end_time = User::convert_utc_to_user_timezone($user->id, $session->end_time)['time'];
        }
        $history['patient_meds'] = $user_obj->get_current_medicines($id); //$patient_meds[0]->prod->name
        $history['patient_labs'] = $user_obj->get_lab_reports($id);
        $history['patient_imaging'] = $user_obj->get_imaging_reports($id);
        $history['patient_pending_labs'] = $this->fetch_pending_labs($id,new Request);
        $history['patient_pending_imagings'] = $this->fetch_pending_imagings($id,new Request);
        $medical_profile = MedicalProfile::where('user_id', $id)->orderByDesc('id')->first();
        $last_updated = "";
        $box['med_price'] = DB::table('medicine_order')->where('user_id', $id)->sum('update_price');
        $orderLabs = DB::table('lab_orders')
            ->join('quest_data_test_codes', 'quest_data_test_codes.TEST_CD', 'lab_orders.product_id')
            ->join('prescriptions', 'prescriptions.test_id', 'lab_orders.product_id')
            ->where('lab_orders.status','!=', 'pending')
            ->where('lab_orders.user_id', $id)
            ->groupBy('lab_orders.id')
            ->select('lab_orders.*', 'quest_data_test_codes.DESCRIPTION', 'quest_data_test_codes.SALE_PRICE', 'prescriptions.quantity',)
            ->get();
        $box['lab_price'] = 0;
        foreach($orderLabs as $order){
            $box['lab_price'] += $order->SALE_PRICE;
        }
        $box['imaging_price'] = DB::table('imaging_orders')->where('user_id', $id)->sum('price');
        $box['sessions'] = DB::table('sessions')->where('patient_id',$id)->where('status','!=','pending')->count();
        if ($medical_profile != null) {
            $last_updated = Helper::get_date_with_format($medical_profile['updated_at']);
        }

        if (auth()->user()->user_type == 'doctor') {
            ActivityLog::create([
                'activity' => 'viewed record of ' . $pat_name,
                'type' => 'record',
                'user_id' => auth()->user()->id,
                'user_type' => 'doctor',
                'party_involved' => $id,
            ]);
            return $this->sendResponse([
                'sessionss' => $sessions,
                'pat_info' => $pat_info,
                'pat_name' => $pat_name,
                'medical_profile' => $medical_profile,
                'last_updated' => $last_updated,
                'user_type' => $user_type,
                'history' => $history,
                'tblOrders' => $tblOrders,
                'inclinic' => $inclinic,
                'box' => $box
            ], 'Patient Record');

        }elseif(auth()->user()->user_type == 'admin'){
            return $this->sendResponse([
                'sessionss' => $sessions,
                'pat_info' => $pat_info,
                'pat_name' => $pat_name,
                'medical_profile' => $medical_profile,
                'last_updated' => $last_updated,
                'user_type' => $user_type,
                'history' => $history,
                'tblOrders' => $tblOrders,
                'inclinic' => $inclinic,
                'box' => $box
            ], 'Patient Record');
        }
    }

}
