<?php

namespace App\Http\Controllers\Api\DoctorsApi;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController;
use App\Appointment;
use App\Cart;
use App\MedicalProfile;
use App\Notification;
use App\Session;
use App\User;
use App\Specialization;
use AWS\CRT\Log;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use App\Models\Contract;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log as FacadesLog;
use Illuminate\Support\Facades\Mail;
use App\Helper;
use App\Repositories\TblOrdersRepository;
use App\ActivityLog;
use App\Events\loadOnlineDoctor;
use App\QuestResult;
use App\Http\Controllers\HL7Controller;
use DateTime;
use DateTimeZone;

class DoctorDashboardController extends BaseController
{
    private $tblOrdersRepository;

    public function doctor_dashboard(){
        $user = Auth::user();
        // $doctors = User::where('user_type', 'doctor')->where('active', '1')
        //     ->addSelect([
        //         'spec' => Specialization::select('name')
        //             ->whereColumn('id', 'users.specialization')
        //     ])->orderByDesc('rating')->paginate(4);
        // $email_status = DB::table('users_email_verification')->where('user_id', $user->id)->first();
        // $term_condition_status = DB::table('user_term_and_condition_status')->where('status', '0')->where('flag', 'update')->where('user_id', $user->id)->first();
        // $currentRole = strtolower(Auth::user()->user_type);
        // if($currentRole == 'doctor') {
            $totalPatient = Session::where('doctor_id', $user->id)
                ->where('status','!=','pending')
                ->groupBy('patient_id')->get()
                ->count();
            $totalPendingAppoint = Appointment::where('status', 'pending')
                ->where('doctor_id', $user->id)->get()
                ->count();
            $currentMonth = date('m');
            $getDoctorSessionTotal = DB::table("sessions")
                                    ->where('doctor_id', $user->id)
                                    ->where('status', 'ended')
                                    ->get()
                                    ->count();
                                    $getDoctorSessionTotalPrice = DB::table("sessions")
                                    ->where('doctor_id', $user->id)
                                    ->where('status', 'ended')
                                    ->sum('sessions.price');
           $doc_percentage = DB::table('doctor_percentage')->where('doc_id', $user->id)->first();
            if ($doc_percentage->percentage != null) {
                $totalEarning = ($doc_percentage->percentage / 100) * $getDoctorSessionTotalPrice;
            } else {
                $totalEarning = (50 / 100) * $getDoctorSessionTotalPrice;
            }
            $lab_approval_earning = DB::table('lab_orders')
                                    ->where('lab_orders.status', 'quest-forwarded')
                                    ->where('lab_orders.type', 'Counter')
                                    ->where('lab_orders.doc_id', $user->id)
                                    ->groupBy('lab_orders.order_id')
                                    ->get();
            $lab_approval_earning = count($lab_approval_earning)*3;
            $totalEarning = $totalEarning + $lab_approval_earning;
            $today = date('Y-m-d');
            $todayTime = date('h:i A');
            $appoints = DB::table('appointments')
                        ->join('sessions', 'appointments.id', 'sessions.appointment_id')
                        ->where('appointments.doctor_id', $user->id)
                        ->where('appointments.status', 'pending')
                        ->where('appointments.date', '>=', $today)
                        ->where('sessions.status','!=','pending')
                        ->orderBy('appointments.created_at', 'DESC')
                        ->select('appointments.*', 'sessions.id as sesssion_id', 'sessions.que_message as msg', 'sessions.join_enable')
                        ->paginate(7);

            $doctor_data["code"] = 200;
            $doctor_data["totalPatient"] =$totalPatient;
            $doctor_data["PendingAppoint"] =$totalPendingAppoint;
            $doctor_data["DoctorSessionTotal"] =$getDoctorSessionTotal;
            $doctor_data["totalEarning"] =$totalEarning;
            $doctor_data["appoints"] =$appoints;
            return $this->sendResponse($doctor_data,"Doctor Dashboard");
        // }
    }
    public function doctor_all_patients(){
        $user = auth()->user();
        $patients = DB::table('sessions')
            ->join('users', 'sessions.patient_id', '=', 'users.id')
            ->where('sessions.doctor_id', $user->id)
            ->where('sessions.status', '!=', 'pending')
            ->orderBy('sessions.date', 'DESC')
            ->groupBy('sessions.patient_id')
            ->select('sessions.*', 'users.user_image', DB::raw('MAX(sessions.id) as last_id'))
            ->get();
        foreach ($patients as $patient) {
            $user = User::where('id', $patient->doctor_id)->first();
            $patient->doc_name = $user['name'] . " " . $user['last_name'];
            $user_details = User::where('id', $patient->patient_id)->first();
            $patient->pat_name = $user_details['name'] . " " . $user_details['last_name'];
            $session = Session::find($patient->last_id);
            $patient->last_visit = Helper::get_date_with_format($session->date);
            $patient->last_diagnosis = $session->diagnosis;
            $patient->user_image = \App\Helper::check_bucket_files_url($patient->user_image);
        }
        $all_patients['code'] = 200;
        $all_patients['patients'] = $patients;
        return $this->sendResponse($all_patients,"All Patients list");
    }
    public function __construct(TblOrdersRepository $tblOrdersRepo){
        $this->tblOrdersRepository = $tblOrdersRepo;
    }
    public function view_patient_record($id){
        $user_type = auth()->user()->user_type;
        $pat = User::find($id);
        $user = $pat;
        $pat_name = Helper::get_name($id);
        $pat_info = User::patient_info($id);
        $sessions = User::get_full_session_details($id);
        // print_r($sessions); die();
        $user_obj = new User();
        $tblOrders = $this->tblOrdersRepository->getOrdersByUserID($id);
        foreach ($tblOrders as $order) {
            $order->created_at = User::convert_utc_to_user_timezone($user->id,$order->created_at);
        }
        foreach ($sessions as $session) {
            $patname = User::where('id',$session->patient_id)->first();
            $session['patient_name'] =$patname->name.' '.$patname->last_name;
            $session->date = User::convert_utc_to_user_timezone($user->id, $session->created_at)['date'];
            $session->start_time = date('h:i A',strtotime('-15 minutes',strtotime($session->start_time)));
            $session->start_time = User::convert_utc_to_user_timezone($user->id, $session->start_time)['time'];
            $session->end_time = date('h:i A',strtotime('-15 minutes',strtotime($session->end_time)));
            $session->end_time = User::convert_utc_to_user_timezone($user->id, $session->end_time)['time'];
        }
        $history['patient_meds'] = $user_obj->get_current_medicines($id); //$patient_meds[0]->prod->name
        $history['patient_labs'] = $user_obj->get_lab_reports($id);
        $history['patient_imaging'] = $user_obj->get_imaging_reports($id);
        // $history['patient_pending_labs'] = $this->fetch_pending_labs($id,new Request);
        // $history['patient_pending_imagings'] = $this->fetch_pending_imagings($id,new Request);
        $medical_profile = MedicalProfile::where('user_id', $id)->orderByDesc('id')->first();
        $medical_profile['immunization_history'] = json_decode($medical_profile->immunization_history);
        $medical_profile['family_history'] = json_decode($medical_profile->family_history);
        $medical_profile['medication'] = json_decode($medical_profile->medication);
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
            $patient_details['code'] =200;
            $patient_details['sessions'] =$sessions;
            $patient_details['patient'] =$pat;
            // $patient_details['history'] =$history;
            // $patient_details['user'] =$user;
            // $patient_details['pat_info'] =$pat_info;
            // $patient_details['pat_name'] =$pat_name;
            $patient_details['medical_profile'] =$medical_profile;
            $patient_details['last_updated'] =$last_updated;
            $patient_details['user_type'] =$user_type;
            $patient_details['history'] =$history;
            $patient_details['tblOrders'] =$tblOrders;

            return $this->sendResponse($patient_details,"Patient details");
        }
        // dd($sessions);
    }
    public function fetch_pending_imagings($user_id,Request $request)
    {
        $imagings = DB::table('sessions')
        ->join('tbl_cart','sessions.id','tbl_cart.doc_session_id')
        ->where('tbl_cart.user_id',$user_id)
        ->where('tbl_cart.product_mode','imaging')
        ->where('tbl_cart.item_type','prescribed')
        ->where('tbl_cart.status','purchased')
        ->select('tbl_cart.*','sessions.date as session_date')
        ->paginate(10);
        return $this->sendResponse($imagings,'Patient imaging history');
    }
    public function fetch_pending_labs($user_id,Request $request)
    {
        $labs = DB::table('sessions')
        ->join('tbl_cart','sessions.id','tbl_cart.doc_session_id')
        ->where('tbl_cart.user_id',$user_id)
        ->where('tbl_cart.product_mode','lab-test')
        ->where('tbl_cart.item_type','prescribed')
        ->where('tbl_cart.status','purchased')
        ->select('tbl_cart.*','sessions.date as session_date')
        ->paginate(10);
        return $this->sendResponse($labs,'Patient labs history');
    }
    public function doctor_status(Request $request){
        $status = $request->status;
        $doc = auth()->user();
        if ($status == 'online') {
            $inQueue = Session::where(['doctor_id' => $doc->id, 'status' => 'invitation sent'])->first();
            if (isset($inQueue->id)) {
                $docStatus['code'] = 200;
                $docStatus['status'] = 'online';
                return $this->sendResponse($docStatus,'You cannot go offline');
            } else {
                User::where('id', $doc['id'])->update(['status' => 'offline']);
                event(new loadOnlineDoctor('run'));
                try {
                    $data = DB::table('users')->where('id',$doc->id)->select('id','status')->first();
                    $data->id = (string)$doc->id;
                    $data->received = "false";
                    \App\Helper::firebaseOnlineDoctor('loadOnlineDoctor',$doc->id,$data);
                } catch (\Throwable $th) {

                }
                $docStatus['code'] = 200;
                $docStatus['status'] = 'offline';
                return $this->sendResponse($docStatus,'Doctor offline');
            }
        } else {
            User::where('id', $doc['id'])->update(['status' => 'online']);
            event(new loadOnlineDoctor('run'));
            try {
                $data = DB::table('users')->where('id',$doc->id)->select('id','status')->first();
                $data->id = (string)$doc->id;
                $data->received = "false";
                \App\Helper::firebaseOnlineDoctor('loadOnlineDoctor',$doc->id,$data);
            } catch (\Throwable $th) {

            }
            $docStatus['code'] = 200;
            $docStatus['status'] = 'online';
            return $this->sendResponse($docStatus,'Doctor online');
        }

        // if($status == 'online'){
        //    $status = DB::table('users')->where('id', $id)->update(array('status' => "online"));
        //    $docStatus['code'] = 200;
        //    $docStatus['status'] = 'online';
        //     return $this->sendResponse($docStatus,'Doctor online');
        // } else if($status == 'offline'){
        //     $status = DB::table('users')->where('id', $id)->update(array('status' => "offline"));
        //     $docStatus['code'] = 200;
        //     $docStatus['status'] = 'offline';
        //     return $this->sendResponse($docStatus,'Doctor offline');
        // } else{
        //     $docStatus['code'] = 200;
        //     return $this->sendResponse($docStatus,'something went wrong');
        // }
    }
    public function get_doctor_status(){
        $id= Auth::user()->id;
        $status = DB::table('users')->where('id', $id)->select('status')->first();
        $docStatus['code'] = 200;
        $docStatus['status'] = $status;
        return $this->sendResponse($docStatus,'doctor status');
    }
    public function upload_id_Card(Request $request){
        $doctor = Auth::user();
        dd($doctor);
        if(request()->hasFile('front_img'))
        {
            $file = request()->file('front_img');
            $frontImgfileName = \Storage::disk('s3')->put('medical_records', $file);
        }
        if(request()->hasFile('back_img'))
        {
            $file = request()->file('back_img');
            $backImgfileName = \Storage::disk('s3')->put('medical_records', $file);
        }
        $doctor->update([
            'id_card_front' => $frontImgfileName,
            'id_card_back' => $backImgfileName,
        ]);
        $doctorData['code'] = 200;
        return $this->sendResponse($doctorData,'id card uploaded');
    }
    public function doctor_earning_search(Request $request)
    {
        $user_time_zone = auth()->user()->timeZone;
        $user_type = Auth::user()->user_type;
        $user_id = Auth::user()->id;
        $getpercentage = DB::table('doctor_percentage')->where('doc_id', $user_id)->first();
        $getSessionTotals = DB::table("sessions")->where('doctor_id', $user_id)->where('status', 'ended')->get();
        $totalDoctorSessionIncom = 0;
        foreach ($getSessionTotals as $getSessionTotal) {
            $doc_price = ($getpercentage->percentage / 100) * $getSessionTotal->price;
            $totalDoctorSessionIncom += $doc_price;
        }
        $totalEarning = $totalDoctorSessionIncom;
        $currentYear = date('Y');
        $getDoctorSessionTotals = DB::table("sessions")
            ->where('doctor_id', $user_id)
            ->where('status', 'ended')
            ->whereRaw('YEAR(created_at) = ?', [$currentYear])
            ->get();
        $currentYeartotalsessionAmount = 0;
        foreach ($getDoctorSessionTotals as $getDoctorSessionTotal) {
            $doc_price = ($getpercentage->percentage / 100) * $getDoctorSessionTotal->price;
            $currentYeartotalsessionAmount += $doc_price;
        }
        $totalEarningCurrentYear = $currentYeartotalsessionAmount;
        $currentMonth = date('m');
        $currentMonthDoctorTotals = DB::table("sessions")
            ->where('doctor_id', $user_id)
            ->where('status', 'ended')
            ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
            ->get();
        $month_lab_approval_earning = DB::table('lab_orders')
            ->where('lab_orders.status', 'quest-forwarded')
            ->where('lab_orders.type', 'Counter')
            ->where('lab_orders.doc_id', $user_id)
            ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
            ->groupBy('lab_orders.order_id')
            ->get();
        $month_lab_approval_earning = count($month_lab_approval_earning)*3;
        $currentMonthDoctorTotalAmount = 0;
        foreach ($currentMonthDoctorTotals as $currentMonthDoctorTotal) {
            $doc_price = ($getpercentage->percentage / 100) * $currentMonthDoctorTotal->price;
            $currentMonthDoctorTotalAmount += $doc_price;
        }
        $totalEarningCurrentMonth = $currentMonthDoctorTotalAmount + $month_lab_approval_earning;
        $currentDay = date('d');
        $currentDayDoctorTotals = DB::table("sessions")
            ->where('doctor_id', $user_id)
            ->where('status', 'ended')
            ->whereRaw('DAY(created_at) = ?', [$currentDay])
            ->get();
        $day_lab_approval_earning = DB::table('lab_orders')
            ->where('lab_orders.status', 'quest-forwarded')
            ->where('lab_orders.type', 'Counter')
            ->where('lab_orders.doc_id', $user_id)
            ->whereRaw('DAY(created_at) = ?', [$currentDay])
            ->groupBy('lab_orders.order_id')
            ->get();
        $day_lab_approval_earning = count($day_lab_approval_earning)*3;
        $currentDayDoctorTotalAmount = 0;
        foreach ($currentDayDoctorTotals as $currentDayDoctorTotals) {
            $doc_price = ($getpercentage->percentage / 100) * $currentDayDoctorTotals->price;
            $currentDayDoctorTotalAmount += $doc_price;
        }
        $totalEarningCurrentDay = $currentDayDoctorTotalAmount + $day_lab_approval_earning;
        $lab_approval_earning = DB::table('lab_orders')
            ->where('lab_orders.status', 'quest-forwarded')
            ->where('lab_orders.type', 'Counter')
            ->where('lab_orders.doc_id', $user_id)
            ->groupBy('lab_orders.order_id')
            ->get();
        $lab_approval_earning = count($lab_approval_earning)*3;
        if ($request->from_date != null && $request->to_date != null) {
            $doctorHistory = DB::table("sessions")
                ->join("users", 'users.id', '=', 'sessions.patient_id')
                ->where('sessions.doctor_id', $user_id)
                ->where('sessions.status', 'ended')
                ->where('sessions.date', '>=', $request->from_date)
                ->where('sessions.date', '<=', $request->to_date)
                ->select('sessions.*', 'users.*')
                ->paginate(10);
            foreach ($doctorHistory as $doc_history) {
                $doc_history->price = ($getpercentage->percentage / 100) * $doc_history->price;
                $doc_history->date = User::convert_utc_to_user_timezone($user_id, $doc_history->start_time)['date'];
                $date = new DateTime($doc_history->start_time);
                $date = date('h:i A',strtotime('-15 minutes',strtotime($doc_history->start_time)));
                $date = User::convert_utc_to_user_timezone($user_id,$date)['time'];
                $doc_history->start_time = $date;
                $date1 = new DateTime($doc_history->end_time);
                $date1 = date('h:i A',strtotime('-15 minutes',strtotime($doc_history->end_time)));
                $date1 = User::convert_utc_to_user_timezone($user_id,$date1)['time'];
                $doc_history->end_time = $date1;

            }
            // dd($doctorHistory);
        }
        $doctorEarning['code'] = 200;
        // $doctorEarning['totalEarning'] = $totalEarning;
        // $doctorEarning['getpercentage'] = $getpercentage;
        // $doctorEarning['totalEarningCurrentMonth'] = $totalEarningCurrentMonth;
        // $doctorEarning['totalEarningCurrentYear'] = $totalEarningCurrentYear;
        // $doctorEarning['totalEarningCurrentDay'] = $totalEarningCurrentDay;
        // $doctorEarning['lab_approval_earning'] = $lab_approval_earning;
        $doctorEarning['doctor_earning'] = array($doctorHistory);
        return $this->sendResponse($doctorEarning,'Doctor Earning');
        // else {
        //     //start here current month doctor history
        //     $doctorHistory = DB::table("sessions")
        //         ->join("users", 'users.id', '=', 'sessions.patient_id')
        //         ->where('sessions.doctor_id', $user_id)
        //         ->where('sessions.status', 'ended')
        //         ->whereRaw('MONTH(sessions.created_at) = ?', [$currentMonth])
        //         ->select('sessions.*', 'users.name', 'users.last_name')
        //         ->orderBy('sessions.id', 'DESC')
        //         ->paginate(10);

        //     foreach ($doctorHistory as $doc_history) {
        //         $doc_history->price = ($getpercentage->percentage / 100) * $doc_history->price;
        //         $doc_history->date = User::convert_utc_to_user_timezone($user_id, $doc_history->start_time)['date'];
        //         $date = new DateTime($doc_history->start_time);
        //         $date = date('h:i A',strtotime('-15 minutes',strtotime($doc_history->start_time)));
        //         $date = User::convert_utc_to_user_timezone($user_id,$date)['time'];
        //         // $date->setTimezone(new DateTimeZone($user_time_zone));
        //         $doc_history->start_time = $date;

        //         $date1 = new DateTime($doc_history->end_time);
        //         $date1 = date('h:i A',strtotime('-15 minutes',strtotime($doc_history->end_time)));
        //         $date1 = User::convert_utc_to_user_timezone($user_id,$date1)['time'];
        //         // $date1->setTimezone(new DateTimeZone($user_time_zone));
        //         $doc_history->end_time = $date1;
        //     }

        //     //end here current month doctor history
        // }
        // return view('dashboard_doctor.wallet.index', compact('user_type', 'totalEarning', 'getpercentage', 'totalEarningCurrentMonth', 'totalEarningCurrentYear', 'totalEarningCurrentDay','lab_approval_earning', array('doctorHistory')));
    }
}
