<?php

namespace App\Http\Controllers;

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

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function doctor_dashboard()
    {
        $user = Auth::user();
        $doctors = User::where('user_type', 'doctor')->where('active', '1')
            ->addSelect([
                'spec' => Specialization::select('name')
                    ->whereColumn('id', 'users.specialization')
            ])->orderByDesc('rating')->paginate(4);
        $email_status = DB::table('users_email_verification')->where('user_id', $user->id)->first();
        $term_condition_status = DB::table('user_term_and_condition_status')->where('status', '0')->where('flag', 'update')->where('user_id', $user->id)->first();

        $currentRole = strtolower(Auth::user()->user_type);
        if($currentRole!='admin')
        {
            if ($email_status != null && $email_status->status == 1) {
                if ($term_condition_status == null || $term_condition_status == '') {

                    if ($user->provider == '') {
                        auth()->user()->assignRole($currentRole);
                    } else {
                        auth()->user()->assignRole('temp_patient');
                        return redirect()->route('edit_patient_profile');
                    }
                    $id = auth()->user()->id;
                    $profileImage = DB::table('users')
                        ->where('id', $id)
                        ->first();
                    $notcount = Notification::where('user_id', $id)
                        ->where('status', 'new')
                        ->orderby('id', 'desc')->get();
                    $notifs = Notification::where('user_id', $id)
                        ->orderby('id', 'desc')
                        ->get();
                    $countNotification = count($notcount);

                    if ($currentRole == 'doctor') {

                        $totalPatient = Session::where('doctor_id', $user->id)
                            ->where('status','!=','pending')
                            ->groupBy('patient_id')->get()
                            ->count();

                        $totalPendingAppoint = DB::table('appointments')
                        ->join('sessions','sessions.appointment_id','appointments.id')
                        ->where('appointments.status', 'pending')
                        ->where('sessions.status', 'paid')
                        ->where('appointments.doctor_id', $user->id)
                        ->get()
                        ->count();

                        $currentMonth = date('m');
                        $monthTotalAppoint = Appointment::where('doctor_id', $user->id)
                            ->whereRaw('MONTH(created_at) >= ?', $currentMonth)
                            ->count();

                        // $getDoctorSessionTotal = DB::table("sessions")
                        //     ->where('doctor_id', $id)
                        //     ->where('status', 'ended')
                        //     ->get()
                        //     ->count();

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

                        // $todayTime = User::convert_utc_to_user_timezone($user->id,$todayTime)['time'];

                        // dd($today,$todayTime);

                        $make_reschudle = DB::table('appointments')
                            ->where('appointments.date', '<=', $today)
                            ->where('appointments.time', '<', $todayTime)
                            ->where('appointments.status', 'pending')
                            ->update(['appointments.status' => 'make-reschedule']);


                        $totalSessions = Session::where('doctor_id', $user->id)->where('status', 'ended')->get()->count();
                        // User::where('id',$id)->update(['status'=>'online']);
                        // Session::where('doctor_id', $id)->update(['status' => 'ended', 'queue' => 0]);
                        $appoints = DB::table('appointments')
                        ->join('sessions', 'appointments.id', 'sessions.appointment_id')
                        ->where('appointments.doctor_id', $user->id)
                        ->where('appointments.status', 'pending')
                        ->where('appointments.date', '>=', $today)
                        ->where('sessions.status','!=','pending')
                        ->orderBy('appointments.created_at', 'DESC')
                        ->select('appointments.*', 'sessions.id as sesssion_id', 'sessions.que_message as msg', 'sessions.join_enable')
                        ->paginate(7);
                        foreach ($appoints as $appoint) {
                            $datetime = $appoint->date.' '.$appoint->time;
                            $datetime = User::convert_utc_to_user_timezone($user->id,$datetime);
                            $appoint->date = $datetime['date'];
                            $appoint->time = $datetime['time'];

                        }

                        // dd($appoints);
                        return view('dashboard_doctor.doctor', compact(
                            'notifs',
                            'countNotification',
                            'currentRole',
                            'appoints',
                            'profileImage',
                            'totalPatient',
                            'totalPendingAppoint',
                            'monthTotalAppoint',
                            'totalEarning',
                            'totalSessions'
                        ));
                    }

                }
                else {
                    return view('terms.term_and_condition_view');
                }
            }
            else {
                    $user = DB::table('users')
                        ->join('users_email_verification','users.id','users_email_verification.user_id')
                        ->join('contracts','users.id','contracts.provider_id')
                        ->where('users.id',Auth()->user()->id)
                        ->select('users.*','users_email_verification.status as email_status','contracts.status as contract_status')
                        ->first();
                        if ($user->id_card_front == '' && $user->id_card_back == '') {
                            $user->card_status = 0;
                        }elseif($user->id_card_front == '' || $user->id_card_back == ''){
                            $user->card_status = 0;
                        }else{
                            $user->card_status = 1;
                        }
                    $user->contract_date = DB::table('contracts')->where('provider_id',Auth()->user()->id)->orderby('id','desc')->first();
                    if(isset($user->contract_date)){
                        $user->contract_date->date = date('m-d-Y', strtotime($user->contract_date->date));
                    }
                return view('dashboard_doctor.EmailVerify', compact('user'));
            }
        }
    }

    // public function index()
    // {
    //     // dd('ok');
    //     $user = Auth::user();
    //     $doctors = User::where('user_type', 'doctor')->where('active', '1')
    //         ->addSelect([
    //             'spec' => Specialization::select('name')
    //                 ->whereColumn('id', 'users.specialization')
    //         ])->orderByDesc('rating')->paginate(4);
    //     $email_status = DB::table('users_email_verification')->where('user_id', $user->id)->first();
    //     $term_condition_status = DB::table('user_term_and_condition_status')->where('status', '0')->where('flag', 'update')->where('user_id', $user->id)->first();

    //     $currentRole = strtolower(Auth::user()->user_type);

    //     if ($currentRole != 'admin')
    //     {
    //         if ($email_status != null && $email_status->status == 1) {
    //             if ($term_condition_status == null || $term_condition_status == '') {

    //                 if ($user->provider == '') {
    //                     auth()->user()->assignRole($currentRole);
    //                 } else {
    //                     auth()->user()->assignRole('temp_patient');
    //                     return redirect()->route('edit_patient_profile');
    //                 }
    //                 $id = auth()->user()->id;
    //                 $profileImage = DB::table('users')
    //                     ->where('id', $id)
    //                     ->first();
    //                 $notcount = Notification::where('user_id', $id)
    //                     ->where('status', 'new')
    //                     ->orderby('id', 'desc')->get();
    //                 $notifs = Notification::where('user_id', $id)
    //                     ->orderby('id', 'desc')
    //                     ->get();
    //                 $countNotification = count($notcount);

    //                 if ($currentRole == 'patient') {
    //                     $user_id = auth()->user()->id;
    //                     $user_timeZone = auth()->user()->timeZone;
    //                     $profile = MedicalProfile::where('user_id', $user_id)->get();
    //                     $countExist = count($profile);
    //                     if ($countExist > 0) {
    //                         $app_count = Appointment::where('patient_id', $id)->where('status', 'pending')->count();
    //                         // $app_count=count($appoint);
    //                         $docs = Appointment::where('patient_id', $id)->groupBy('doctor_id')->get()->count();
    //                         $last_session = Session::where('patient_id', $id)->where('status', 'ended')->orderByDesc('id')->first();
    //                         $pending_feedback = [];
    //                         if (!empty($last_session)) {
    //                             if ($last_session['feedback_flag'] != '1') {
    //                                 $doc_id = $last_session['doctor_id'];
    //                                 $doc = User::find($doc_id);
    //                                 $pending_feedback['status'] = '1';
    //                                 $last_session['doc_name'] = $doc['name'] . ' ' . $doc['last_name'];
    //                                 $pending_feedback['session'] = $last_session;
    //                             } else {
    //                                 $pending_feedback['status'] = '0';
    //                             }
    //                         } else {
    //                             $pending_feedback['status'] = '0';
    //                         }
    //                         $currentMonth = date('m');
    //                         $monthTotalAppoint = Appointment::where('patient_id', $id)
    //                             ->whereRaw('MONTH(created_at) >= ?', $currentMonth)
    //                             ->count();
    //                         $pendingPerchases = Cart::where('user_id', $id)
    //                             ->where('status', 'prescribed')
    //                             ->count();

    //                         $orderTotal = DB::table("tbl_orders")
    //                             ->where('customer_id', $id)
    //                             ->where('order_status', 'complete')
    //                             ->sum('total');

    //                         $labOrderTotal = DB::table("tbl_products")
    //                             ->join('lab_orders', 'tbl_products.id', '=', 'lab_orders.product_id')
    //                             //->where('lab_orders.customer_id',$id)
    //                             ->where('lab_orders.status', 'complete')
    //                             ->sum('tbl_products.sale_price');

    //                         $totalSession = DB::table("sessions")
    //                             ->where("patient_id", $id)
    //                             ->where("status", 'ended')
    //                             ->count();

    //                         $totalSessionPrice = $totalSession * 30;
    //                         $grandTotal = $totalSessionPrice + $labOrderTotal + $orderTotal;
    //                         $today = Carbon::now()->timezone($user_timeZone)->format('Y-m-d');
    //                         //    dd($today);
    //                         $appoint = DB::table('appointments')
    //                             ->join('sessions', 'appointments.id', 'sessions.appointment_id')
    //                             ->where('appointments.patient_id', $id)
    //                             ->where('appointments.date', '>=', $today)
    //                             ->where('appointments.status', 'pending')
    //                             ->select('appointments.*', 'sessions.id as sesssion_id', 'sessions.que_message as msg', 'sessions.join_enable')
    //                             ->get();

    //                         foreach ($appoint as $app) {
    //                             $app->time = User::convert_utc_to_user_timezone($user->id,$app->time)['time'];
    //                         }

    //                         $today = date('Y-m-d');
    //                         $todayTime = date('h:i A');

    //                     // $todayTime = User::convert_utc_to_user_timezone($user->id,$todayTime)['time'];

    //                     // dd($today,$todayTime);

    //                         $make_reschudle = DB::table('appointments')
    //                             ->where('appointments.date', '<=', $today)
    //                             ->where('appointments.time', '<', $todayTime)
    //                             ->where('appointments.status', 'pending')
    //                             ->update(['appointments.status' => 'make-reschedule']);

    //                         //check patient reschedule_appointment are exist or not
    //                         $reschedule_appointment = DB::table('appointments')
    //                             ->join('sessions', 'sessions.appointment_id', 'appointments.id')
    //                             ->where('appointments.patient_id', $id)
    //                             ->where('appointments.status', 'make-reschedule')
    //                             ->select('sessions.specialization_id as spec_id', 'appointments.*')
    //                             ->get();
    //                         foreach ($reschedule_appointment as $app) {
    //                             $app->time = User::convert_utc_to_user_timezone($user->id,$app->time)['time'];
    //                         }


    //                         foreach ($appoint as $app) {
    //                             $doc = DB::table('users')->where('users.id', $app->doctor_id)
    //                                 ->join('specializations', 'specializations.id', '=', 'users.specialization')
    //                                 ->select('specializations.name as spec')->first();
    //                             $app->doc_speciality = $doc->spec;
    //                         }
    //                         return view('home', compact(
    //                             'reschedule_appointment',
    //                             'notifs',
    //                             'countNotification',
    //                             'currentRole',
    //                             'appoint',
    //                             'docs',
    //                             'app_count',
    //                             'profileImage',
    //                             'pending_feedback',
    //                             'monthTotalAppoint',
    //                             'pendingPerchases',
    //                             'grandTotal',
    //                         ));
    //                     } else {
    //                         return redirect()->route('medical_profile');
    //                         //return redirect()->route('add_medical_profile')->with('profile',$profile);
    //                     }
    //                 } else if ($currentRole == 'doctor') {

    //                     $totalPatient = Session::where('doctor_id', $id)
    //                         ->groupBy('patient_id')->get()
    //                         ->count();

    //                     $totalPendingAppoint = Appointment::where('status', 'pending')
    //                         ->where('doctor_id', $id)->get()
    //                         ->count();

    //                     $currentMonth = date('m');
    //                     $monthTotalAppoint = Appointment::where('doctor_id', $id)
    //                         ->whereRaw('MONTH(created_at) >= ?', $currentMonth)
    //                         ->count();

    //                     // $getDoctorSessionTotal = DB::table("sessions")
    //                     //     ->where('doctor_id', $id)
    //                     //     ->where('status', 'ended')
    //                     //     ->get()
    //                     //     ->count();

    //                     $getDoctorSessionTotalPrice = DB::table("sessions")
    //                         ->where('doctor_id', $id)
    //                         ->where('status', 'ended')
    //                         ->sum('sessions.price');
    //                     $doc_percentage = DB::table('doctor_percentage')->where('doc_id', $id)->first();
    //                     if ($doc_percentage->percentage != null) {
    //                         $totalEarning = ($doc_percentage->percentage / 100) * $getDoctorSessionTotalPrice;
    //                     } else {
    //                         $totalEarning = (50 / 100) * $getDoctorSessionTotalPrice;
    //                     }


    //                     $today = date('Y-m-d');
    //                     $todayTime = date('h:i A');

    //                     // $todayTime = User::convert_utc_to_user_timezone($user->id,$todayTime)['time'];

    //                     // dd($today,$todayTime);

    //                     $make_reschudle = DB::table('appointments')
    //                         ->where('appointments.date', '<=', $today)
    //                         ->where('appointments.time', '<', $todayTime)
    //                         ->where('appointments.status', 'pending')
    //                         ->update(['appointments.status' => 'make-reschedule']);


    //                     $totalSessions = Session::where('doctor_id', $id)->where('status', 'ended')->get()->count();
    //                     // User::where('id',$id)->update(['status'=>'online']);
    //                     // Session::where('doctor_id', $id)->update(['status' => 'ended', 'queue' => 0]);
    //                     $appoints = Appointment::where('doctor_id', $id)->where('status', 'pending')->where('date', '>=', $today)->paginate(7);
    //                     foreach ($appoints as $appoint) {
    //                         $appoint->time = User::convert_utc_to_user_timezone($user->id,$appoint->time)['time'];

    //                     }

    //                     // dd($appoints);
    //                     return view('home', compact(
    //                         'notifs',
    //                         'countNotification',
    //                         'currentRole',
    //                         'appoints',
    //                         'profileImage',
    //                         'totalPatient',
    //                         'totalPendingAppoint',
    //                         'monthTotalAppoint',
    //                         'totalEarning',
    //                         'totalSessions'
    //                     ));
    //                 }

    //                 // Role::create(['name'=>'admin']);
    //                 //  Role::create(['name'=>'doctor']);
    //                 //  Role::create(['name'=>'patient']);
    //                 //  Role::create(['name'=>'temp_patient']);
    //                 //  Role::create(['name'=>'editor']);
    //                 //  Role::create(['name'=>'editor_pharmacy']);
    //                 //  Role::create(['name'=>'editor_lab']);
    //                 //  Role::create(['name'=>'admin_pharmacy']);
    //                 //  Role::create(['name'=>'admin_lab']);
    //                 //  Role::create(['name'=>'hr']);
    //                 //  Role::create(['name' => 'editor_imaging']);

    //                 return view('home', compact('notifs', 'countNotification', 'currentRole', 'profileImage','doctors'));
    //             } else {
    //                 return view('terms.term_and_condition_view');
    //             }
    //         } else {
    //             return view('email_verification_view');
    //         }
    //     } else {
    //         // if ($user->provider == '') {
    //         //     auth()->user()->assignRole($currentRole);
    //         // } else {
    //         //     auth()->user()->assignRole('temp_patient');
    //         //     return redirect()->route('edit_patient_profile');
    //         // }
    //         $id = auth()->user()->id;
    //         $profileImage = DB::table('users')
    //             ->where('id', $id)
    //             ->first();
    //         $notcount = Notification::where('user_id', $id)
    //             ->where('status', 'new')
    //             ->orderby('id', 'desc')->get();
    //         $notifs = Notification::where('user_id', $id)
    //             ->orderby('id', 'desc')
    //             ->get();
    //         $countNotification = count($notcount);
    //         $currentMonth = date('m');
    //         $totalDoc = User::where('user_type', 'doctor')->count();

    //         $totalPatient = User::where('user_type', 'patient')->count();
    //         $totalpendingdoc = User::where([
    //             ['user_type', 'doctor'],
    //             ['active', '0']
    //         ])->count();

    //         $totalPendingAppoint = Appointment::where('status', 'pending')
    //             ->whereRaw('MONTH(created_at) >= ?', $currentMonth)
    //             ->count();

    //         //start here total session earning
    //         // $getSessionTotal = DB::table("sessions")
    //         //     ->where('status', 'ended')
    //         //     ->get()
    //         //     ->count();


    //         $getSessionTotals = DB::table("sessions")->where('status', 'ended')->sum('price');
    //         // dd($getSessionTotals);
    //         // $totalAdminSessionIncom=0;
    //         // foreach($getSessionTotals as $getSessionTotal)
    //         // {
    //         //     $getpercentage=DB::table('doctor_percentage')->where('doc_id',$getSessionTotal->doctor_id)->first();
    //         //     $doc_price=($getpercentage->percentage / 100) * intval($getSessionTotal->price);

    //         //     $totalAdminSessionIncom+=intval($getSessionTotal->price)-$doc_price;
    //         // }
    //         $totalSessionPrice = $getSessionTotals;

    //         // $totalSessionPrice = $getSessionTotal * 30;
    //         //dd($totalSessionPrice);
    //         //end here total orders earning

    //         //start here total orders earning
    //         $getOrderTotal = DB::table("tbl_orders")
    //             ->where('order_status', 'complete')
    //             ->sum('total');
    //         //dd($getOrderTotal);
    //         //end here total orders earning

    //         //start here total lab orders earning
    //         $getLabOrderTotal = DB::table("tbl_products")
    //             ->join('lab_orders', 'tbl_products.id', '=', 'lab_orders.product_id')
    //             ->where('lab_orders.status', 'complete')
    //             ->sum('tbl_products.sale_price');
    //         //dd($getLabOrderTotal);
    //         //end here total lab orders earning
    //         $totalBalance = $getLabOrderTotal + $getOrderTotal + $totalSessionPrice;
    //         $doctors = \DB::table('users')
    //             ->join('states', 'states.id', '=', 'users.state_id')
    //             ->join('contracts', 'contracts.provider_id', '!=', 'users.id')
    //             ->where('user_type', 'doctor')
    //             ->where('users.active', '0')
    //             ->where('users.status', '!=', 'declined')
    //             ->select('users.*', 'states.name as state')
    //             ->groupBy('users.id')
    //             ->orderBy('users.created_at', 'ASC')
    //             ->get();
    //         foreach ($doctors as $doctor) {
    //             $doctor->time = User::convert_utc_to_user_timezone($user->id,$doctor->created_at)['datetime'];

    //         }
    //         // dd($doctors);
    //         return view('home', compact(
    //             'notifs',
    //             'countNotification',
    //             'currentRole',
    //             'profileImage',
    //             'totalDoc',
    //             'totalPatient',
    //             'totalpendingdoc',
    //             'totalPendingAppoint',
    //             'totalBalance',
    //             'doctors'
    //         ));
    //     }
    // }
    public function index()
    {
        // dd('ok');
        $user = Auth::user();
        $doctors = User::where('user_type', 'doctor')->where('active', '1')
            ->addSelect([
                'spec' => Specialization::select('name')
                    ->whereColumn('id', 'users.specialization')
            ])->orderByDesc('rating')->paginate(4);
        $email_status = DB::table('users_email_verification')->where('user_id', $user->id)->first();
        $term_condition_status = DB::table('user_term_and_condition_status')->where('status', '0')->where('flag', 'update')->where('user_id', $user->id)->first();

        $currentRole = strtolower(Auth::user()->user_type);

        if ($currentRole != 'admin')
        {
            if ($email_status != null && $email_status->status == 1) {
                if ($term_condition_status == null || $term_condition_status == '') {

                    if ($user->provider == '') {
                        auth()->user()->assignRole($currentRole);
                    } else {
                        auth()->user()->assignRole('temp_patient');
                        return redirect()->route('edit_patient_profile');
                    }
                    $id = auth()->user()->id;
                    $profileImage = DB::table('users')
                        ->where('id', $id)
                        ->first();
                    $notcount = Notification::where('user_id', $id)
                        ->where('status', 'new')
                        ->orderby('id', 'desc')->get();
                    $notifs = Notification::where('user_id', $id)
                        ->orderby('id', 'desc')
                        ->get();
                    $countNotification = count($notcount);

                    if ($currentRole == 'patient') {
                        return redirect()->route('New_Patient_Dashboard');
                    } else if ($currentRole == 'doctor') {
                        return redirect()->route('doctor_dashboard');
                    } else if ($currentRole == 'admin_finance') {
                        return redirect()->route('finance_admin_dash');
                    } else if ($currentRole == 'admin_pharm') {
                        return redirect()->route('pharmacy_admin_dash');
                    } else if ($currentRole == 'admin_lab') {
                        return redirect()->route('lab_admin_dash');
                    } else if ($currentRole == 'admin_imaging') {
                        return redirect()->route('imaging_admin_dash');
                    } else if ($currentRole == 'editor_pharmacy') {
                        return redirect()->route('pharmacy_editor_dash');
                    } else if ($currentRole == 'editor_lab') {
                        return redirect()->route('lab_editor_dash');
                    } else if ($currentRole == 'editor_imaging') {
                        return redirect()->route('img_editor_dash');
                    } else if ($currentRole == 'chat_support') {
                        return redirect()->route('chat_support');
                    } else if ($currentRole == 'admin_seo') {
                        return redirect()->route('seo_admin_dash');
                    }
                    return view('home', compact('notifs', 'countNotification', 'currentRole', 'profileImage','doctors'));
                } else {
                    return view('terms.term_and_condition_view');
                }
            } else {
                return view('website_pages.email_verify_new');
            }
        } else {
                return redirect(url('admin/dash'));
            // if ($user->provider == '') {
            //     auth()->user()->assignRole($currentRole);
            // } else {
            //     auth()->user()->assignRole('temp_patient');
            //     return redirect()->route('edit_patient_profile');
            // }
            // $id = auth()->user()->id;
            // $profileImage = DB::table('users')
            //     ->where('id', $id)
            //     ->first();
            // $notcount = Notification::where('user_id', $id)
            //     ->where('status', 'new')
            //     ->orderby('id', 'desc')->get();
            // $notifs = Notification::where('user_id', $id)
            //     ->orderby('id', 'desc')
            //     ->get();
            // $countNotification = count($notcount);
            // $currentMonth = date('m');
            // $totalDoc = User::where('user_type', 'doctor')->count();

            // $totalPatient = User::where('user_type', 'patient')->count();
            // $totalpendingdoc = User::where([
            //     ['user_type', 'doctor'],
            //     ['active', '0']
            // ])->count();

            // $totalPendingAppoint = Appointment::where('status', 'pending')
            //     ->whereRaw('MONTH(created_at) >= ?', $currentMonth)
            //     ->count();

            // //start here total session earning
            // // $getSessionTotal = DB::table("sessions")
            // //     ->where('status', 'ended')
            // //     ->get()
            // //     ->count();


            // $getSessionTotals = DB::table("sessions")->where('status', 'ended')->sum('price');
            // // dd($getSessionTotals);
            // // $totalAdminSessionIncom=0;
            // // foreach($getSessionTotals as $getSessionTotal)
            // // {
            // //     $getpercentage=DB::table('doctor_percentage')->where('doc_id',$getSessionTotal->doctor_id)->first();
            // //     $doc_price=($getpercentage->percentage / 100) * intval($getSessionTotal->price);

            // //     $totalAdminSessionIncom+=intval($getSessionTotal->price)-$doc_price;
            // // }
            // $totalSessionPrice = $getSessionTotals;

            // // $totalSessionPrice = $getSessionTotal * 30;
            // //dd($totalSessionPrice);
            // //end here total orders earning

            // //start here total orders earning
            // $getOrderTotal = DB::table("tbl_orders")
            //     ->where('order_status', 'complete')
            //     ->sum('total');
            // //dd($getOrderTotal);
            // //end here total orders earning

            // //start here total lab orders earning
            // $getLabOrderTotal = DB::table("tbl_products")
            //     ->join('lab_orders', 'tbl_products.id', '=', 'lab_orders.product_id')
            //     ->where('lab_orders.status', 'complete')
            //     ->sum('tbl_products.sale_price');
            // //dd($getLabOrderTotal);
            // //end here total lab orders earning
            // $totalBalance = $getLabOrderTotal + $getOrderTotal + $totalSessionPrice;
            // $doctors = \DB::table('users')
            //     ->join('states', 'states.id', '=', 'users.state_id')
            //     ->join('contracts', 'contracts.provider_id', '!=', 'users.id')
            //     ->where('user_type', 'doctor')
            //     ->where('users.active', '0')
            //     ->where('users.status', '!=', 'declined')
            //     ->select('users.*', 'states.name as state')
            //     ->groupBy('users.id')
            //     ->orderBy('users.created_at', 'ASC')
            //     ->get();
            // foreach ($doctors as $doctor) {
            //     $doctor->time = User::convert_utc_to_user_timezone($user->id,$doctor->created_at)['datetime'];

            // }
            // // dd($doctors);
            // return view('home', compact(
            //     'notifs',
            //     'countNotification',
            //     'currentRole',
            //     'profileImage',
            //     'totalDoc',
            //     'totalPatient',
            //     'totalpendingdoc',
            //     'totalPendingAppoint',
            //     'totalBalance',
            //     'doctors'
            // ));
        }
    }

    public function new_admin_index(){
        $user = Auth::user();
        $user->user_image = \App\Helper::check_bucket_files_url($user->user_image);
        $doctor_count = DB::table('users')->where('user_type','doctor')->where('active','1')->count();
        $patients_count = DB::table('users')->where('user_type','patient')->count();
        $pending_doctors_count = DB::table('users')->where('user_type','doctor')->where('users.active','=','0')->where('users.status','!=','declined')->count();
        // dd($doctors);
        $doctors = DB::table('users')
        ->select('users.*','states.name as state_name')
        ->join('states', 'users.state_id', '=', 'states.id')
        ->leftjoin('contracts','contracts.provider_id','users.id')
        ->where('user_type','=','doctor')
        ->where('users.active','=','0')
        ->where('users.status','!=','declined')
        ->where('contracts.status','=',null)
        ->groupBy('users.id')
        ->orderBy('users.created_at', 'DESC')
        ->paginate(3);

        foreach ($doctors as $doc) {
            $doc->created_at = User::convert_utc_to_user_timezone($user->id,$doc->created_at);
            $doc->created_at = $doc->created_at['date']." ".$doc->created_at['time'];
        }
        $user->maintain_status = '';
        $user->authorize_api_status = '';
        $maintain = DB::table('services')->where('name','maintainance_mode')->first();
        $authorize = DB::table('services')->where('name','authorize_api_mode')->first();
        $ticker = DB::table('services')->where('name','ticker')->first();
        if($maintain!=null)
        {
            $user->maintain_status = $maintain->status;
        }
        if($authorize!=null)
        {
            $user->authorize_api_status = $authorize->status;
        }
        if($ticker!=null)
        {
            $user->ticker_status = $ticker->status;
            $user->ticker_value = $ticker->value;
        }
        else
        {
            $user->ticker_status = "Off";
            $user->ticker_value = "";
        }

        return view('dashboard_admin.admin',compact('doctor_count','patients_count','pending_doctors_count','doctors','user'));
    }

    public function new_pharm_editor_index(){
        return view('dashboard_Pharm_editor.pharm_editor');
    }

    public function new_pharm_admin_index(){

        return view('dashboard_Pharm_admin.pharm_admin');
    }


    public function new_pat_index(){
        $user = Auth::user();
        $doctors = DB::table('sessions')
        ->where('patient_id',$user->id)
        ->where('status','!=','pending')
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
        ->where('is_read',null)
        ->count();

        $pending_appoints = DB::table('appointments')
        ->join('sessions','appointments.id','sessions.appointment_id')
        ->where('appointments.patient_id', $user->id)
        ->where('appointments.status','pending')
        ->where('sessions.status','!=','pending')
        ->count();

        $pending_sessions = DB::table('sessions')
        ->where('patient_id', $user->id)
        ->where('appointment_id', null)
        ->where('status','!=','ended')
        ->where('reminder',null)
        ->orderby('id','desc')
        ->first();

        $med_profile = DB::table('medical_profiles')
        ->where('user_id',$user->id)
        ->count();

        $chat = DB::table('chat')->where('token_status','unsolved')->where('user_id',$user->id)->get();

        if($pending_sessions!=null){
            $total_reminds = $unread_reports+$pending_appoints+1;
        }
        else{
            $total_reminds = $unread_reports+$pending_appoints;
        }
        $session = DB::table('sessions')->where('patient_id',Auth::user()->id)->where('status','ended')->where('remaining_time','!=','full')->orderby('id','desc')->first();
        if($session == null){
            $ses_id = 0;
            $ses_feed = 1;
        }else{
            $ses_id = $session->id;
            $ses_feed = $session->feedback_flag;
        }
        return view('dashboard_patient.patient', compact('user', 'doctors','chat','orders','reports','unread_reports','pending_appoints','pending_sessions','total_reminds','med_profile','ses_id','ses_feed'));

    }

    public function searchProductMedicine(Request $request)
    {

        $search = $request["search"];
        $data = DB::table('tbl_products')
            ->where('mode', '=', 'medicine')
            ->where('name', 'like', $search . '%')
            ->take(10)
            ->get();

        $userData["medison"] = $data;
        echo json_encode($userData);
    }
    public function searchProductImaging(Request $request)
    {
        $search = $request["search"];
        $data = DB::table('tbl_products')
            ->where('mode', '=', 'imaging')
            ->where('name', 'like', $search . '%')
            ->take(10)
            ->get();
        $userData["imaging"] = $data;
        echo json_encode($userData);
    }
    public function searchProductLab(Request $request)
    {
        $search = $request["search"];
        $data = DB::table('tbl_products')
            ->where('mode', '=', 'lab-test')
            ->where('name', 'like', $search . '%')
            ->take(10)
            ->get();

        $userData["lab"] = $data;
        echo json_encode($userData);
    }
    public function searchPatient(Request $request)
    {
        $user_type = auth()->user()->user_type;
        if ($user_type == "doctor")
        {
            $doc_id = auth()->user()->id;
            $search = $request["search"];

            $data = DB::table('sessions')
                ->join('users', 'users.id', '=', 'sessions.patient_id')
                ->where('sessions.doctor_id', '=', $doc_id)
                ->where('users.name', 'like', $search . '%')
                ->groupBy('users.id')
                ->take(10)
                ->get();
            foreach($data as $d)
            {
                $d->user_image=\App\Helper::check_bucket_files_url($d->user_image);
            }

            $userData["patient"] = $data;
            echo json_encode($userData);
        }
        else
        {
            $search = $request["search"];

            $data = DB::table('users')
                ->where('user_type', '=', 'doctor')
                ->where('status', '=', 'online')
                ->where('name', 'like', $search . '%')
                ->take(10)
                ->get();
            foreach($data as $d)
            {
                $d->user_image=\App\Helper::check_bucket_files_url($d->user_image);
            }

            $userData["patient"] = $data;
            echo json_encode($userData);
        }
    }
    public function video()
    {
        $user_type = "patient";
        return view('frontend._test_agora_video_page', compact('user_type'));
    }
    public function term_agree(Request $request)
    {

        $user = DB::table('users')->where('id', $request->user_id)->first();
        $data_email["email"] = $user->email;
        $data_email["title"] = "Terms And Conditions";
        $time = DB::table('documents')->where('name','term of use')->select('updated_at')->first();
        $data_email["revised"] = date('m-d-Y',strtotime($time->updated_at));
        $pdf = app()->make(PDF::class);
        $pdf = $pdf->loadView('terms.index', $data_email);
        \Storage::disk('s3')->put('term_and_conditions/' . $user->name . '_term_and_conditions.pdf', $pdf->output());
        DB::table('user_term_and_condition_status')
            ->where('user_id', $request->user_id)
            ->update([
                'status' => 1,
                'flag' => 'agree',
                'term_and_condition_file' => 'term_and_conditions/' . $user->name . '_term_and_conditions.pdf'
            ]);

        try {
            $adminUsers = DB::table('users')->where('user_type', 'admin')->get();
            foreach ($adminUsers as $adminUser) {
                $admin_data_email["email"] =  $adminUser->email;
                $admin_data_email["title"] = "Terms And Conditions";
                Mail::send('emails.termAndConditionDoctorEmail', $admin_data_email, function ($message1) use ($admin_data_email, $pdf) {
                    $message1->to($admin_data_email["email"])->subject($admin_data_email["title"])->attachData($pdf->output(), "TermsAndConditions.pdf");
                });
            }
            Mail::send('emails.termAndConditionDoctorEmail', $data_email, function ($message) use ($data_email, $pdf) {
                $message->to($data_email["email"])->subject($data_email["title"])->attachData($pdf->output(), "TermsAndConditions.pdf");
            });
        } catch (Exception $e) {
            FacadesLog::info($e);
        }
        return redirect()->route('home');
    }
}
