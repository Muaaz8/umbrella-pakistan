<?php

namespace App\Http\Controllers;

use App\Appointment;
use App\Cart;
use App\City;
use App\Events\RealTimeMessage;
use App\Events\updateQuePatient;
use App\Models\TblTransaction;
use App\Helper;
use App\Mail\CancelAppointmentAccountantMail;
use App\Mail\CancelAppointmentDoctorMail;
use App\Mail\CancelAppointmentPatientMail;
use App\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Symptom;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewAppointmentDoctorMail;
use App\Mail\NewAppointmentPatientMail;
use App\Mail\RescheduleAppointmentDocotrMail;
use App\Mail\RescheduleAppointmentPatientMail;
use App\Mail\EvisitBookMail;
use App\Models\AllProducts;
use App\Prescription;
use App\User;
use App\DoctorSchedule;
use App\Session;
use App\Referal;
use App\Specialization;
use App\State;
use App\VideoLinks;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Log;

class AppointmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = date('Y-m-d');
        $todayTime = date('h:i A');
        // dd($today,$todayTime);
        if ($user->user_type == 'admin') {
            $appointments = Appointment::orderBy('created_at', 'DESC')->paginate(12);
            return view('appointments.all_appointments', compact('appointments', 'user'));
        } else if ($user->user_type == 'doctor') {
            $user_state = $user->state_id;
            $state = State::find($user_state);
            if ($state->active == 1) {
                // $make_reschudle = DB::table('appointments')
                // ->where('appointments.date', '<=', $today)
                // ->where('appointments.time', '<', $todayTime)
                // ->where('appointments.status', 'pending')
                // ->update(['appointments.status' => 'make-reschedule']);
                // dd($make_reschudle);


                $appointments = DB::table('appointments')
                    ->join('sessions', 'appointments.id', 'sessions.appointment_id')
                    ->where('appointments.doctor_id', $user->id)
                    ->orderBy('appointments.created_at', 'DESC')
                    ->select('appointments.*', 'sessions.id as sesssion_id', 'sessions.que_message as msg', 'sessions.join_enable')
                    ->paginate(12);
                foreach ($appointments as $app) {
                    $app->time = User::convert_utc_to_user_timezone($user->id, $app->time)['time'];
                }
                return view('appointments.all_appointments', compact('appointments', 'user'));
            } else {
                return redirect()->route('errors', '101');
            }
        } else if ($user->user_type == 'patient') {
            $user_state = $user->state_id;
            $state = State::find($user_state);
            if ($state->active == 1) {
                $make_reschudle = DB::table('appointments')
                    ->where('appointments.date', '<=', $today)
                    ->where('appointments.time', '<', $todayTime)
                    ->where('appointments.status', 'pending')
                    ->update(['appointments.status' => 'make-reschedule']);

                $appointments = DB::table('appointments')
                    ->join('sessions', 'appointments.id', 'sessions.appointment_id')
                    ->where('appointments.patient_id', $user->id)
                    ->orderBy('appointments.created_at', 'DESC')
                    ->select('appointments.*', 'sessions.id as sesssion_id', 'sessions.que_message as msg', 'sessions.join_enable')
                    ->paginate(12);

                foreach ($appointments as $app) {
                    $app->time = User::convert_utc_to_user_timezone($user->id, $app->time)['time'];
                }

                $reschedule_appointment = DB::table('appointments')
                    ->join('sessions', 'sessions.appointment_id', 'appointments.id')
                    ->where('appointments.patient_id', $user->id)
                    ->where('appointments.status', 'make-reschedule')
                    ->select('sessions.specialization_id as spec_id', 'appointments.*')
                    ->get();

                foreach ($reschedule_appointment as $app) {
                    $app->time = User::convert_utc_to_user_timezone($user->id, $app->time)['time'];
                }
                return view('appointments.all_appointments', compact('appointments', 'user', 'reschedule_appointment'));
            } else {
                return redirect()->route('errors', '101');
            }
        } else
            return view('welcome');
    }

    public function dash_admin_appointments(Request $request)
    {
        if(isset($request->name)){
            $appointments = Appointment::where('appointment_id',$request->name)->orderBy('created_at', 'DESC')->paginate(12);
        }else{
            $appointments = Appointment::orderBy('created_at', 'DESC')->paginate(12);
        }
        $user = Auth::user();
        foreach ($appointments as $app) {
            $app->date = User::convert_utc_to_user_timezone($user->id, $app->date)['date'];
            $app->time = User::convert_utc_to_user_timezone($user->id, $app->time)['time'];
        }
        return view('dashboard_admin.All_appointmnet.index', compact('appointments'));
    }

    public function Patient_Appointments()
    {
        $user = Auth::user();
        $today = date('Y-m-d');
        $todayTime = date('h:i A');
        // $user_state = $user->state_id;
        // $state = State::find($user_state);
        // if ($state->active == 1) {
            // $make_reschudle = DB::table('appointments')
            // ->where('appointments.date', '<=', $today)
            // ->where('appointments.time', '<', $todayTime)
            // ->where('appointments.status', 'pending')
            // ->update(['appointments.status' => 'make-reschedule']);

            $appointments = DB::table('appointments')
                ->join('users', 'appointments.doctor_id', 'users.id')
                ->join('sessions', 'appointments.id', 'sessions.appointment_id')
                ->where('appointments.patient_id', $user->id)
                ->where('sessions.status','!=','pending')
                ->orderBy('appointments.created_at', 'DESC')
                ->select('appointments.*', 'sessions.id as sesssion_id', 'sessions.que_message as msg', 'sessions.join_enable', 'users.specialization as spec_id')
                ->paginate(10);

            foreach ($appointments as $app) {
                $ddd = date('Y-m-d H:i:s', strtotime("$app->date $app->time"));
                $app->time = User::convert_utc_to_user_timezone($user->id, $ddd)['time'];
                $app->date = User::convert_utc_to_user_timezone($user->id, $ddd)['date'];
            }
            return view('dashboard_patient.Appointments.index', compact('appointments', 'user'));
        // } else {
        //     return redirect()->route('errors', '101');
        // }
    }

    public function non_refund_cancel_appointment($id)
    {
        $user_type = auth()->user()->user_type;
        $session = DB::table('sessions')->where('id',$id)->first();
        $app = Appointment::find($session->appointment_id);
        if ($app) {
            $app->status = 'cancelled';
            $app->save();
            $session = DB::table('sessions')->where('id',$id)->update(['status'=>'cancelled']);
            return redirect()->route("New_Patient_Dashboard");
        }
        return redirect()->back();
    }

    public function pat_appointment_cancel(Request $request)
    {
        //dd($request);
        $user_type = auth()->user()->user_type;
        $app = Appointment::find($request->id);
        if ($app) {
            $app->status = $user_type . ' has cancelled the appointment';
            $app->save();
        }

        // notifications comment
        try {

            $getAppointment = DB::table('appointments')->where('id', $request->id)->first();
            $doctor_data = DB::table('users')->where('id', $getAppointment->doctor_id)->first();
            $patient_data = DB::table('users')->where('id', $getAppointment->patient_id)->first();
            $adminMail = DB::table('users')->where('user_type', 'admin')->first();
            $p_datetime = User::convert_utc_to_user_timezone($patient_data->id, $getAppointment->date . ' ' . $getAppointment->time);
            $d_datetime = User::convert_utc_to_user_timezone($doctor_data->id, $getAppointment->date . ' ' . $getAppointment->time);
            $a_datetime = User::convert_utc_to_user_timezone($adminMail->id, $getAppointment->date . ' ' . $getAppointment->time);

            $p_markDownData = [
                'doc_name' => ucwords($doctor_data->name),
                'pat_name' => ucwords($patient_data->name),
                'time' => $p_datetime['time'],
                'date' => $p_datetime['date'],
                'pat_mail' => $patient_data->email,
                'doc_mail' => $doctor_data->email,
            ];

            $d_markDownData = [
                'doc_name' => ucwords($doctor_data->name),
                'pat_name' => ucwords($patient_data->name),
                'time' => $d_datetime['time'],
                'date' => $d_datetime['date'],
                'pat_mail' => $patient_data->email,
                'doc_mail' => $doctor_data->email,
            ];

            $accountsMarkDownData = [
                'doc_name' => ucwords($doctor_data->name),
                'pat_name' => ucwords($patient_data->name),
                'time' => $a_datetime['time'],
                'date' => $a_datetime['date'],
                'acounts_name' => ucwords($adminMail->name),
                'acounts_mail' => $adminMail->email,
            ];

            //mailgun cancell appointment Mail to doctor and patient
            // Mail::to('baqir.redecom@gmail.com')->send(new CancelAppointmentPatientMail($markDownData));
            // Mail::to('baqir.redecom@gmail.com')->send(new CancelAppointmentDocotrMail($markDownData));
            // Mail::to('baqir.redecom@gmail.com')->send(new CancelAppointmentAccountantMail($accountsMarkDownData));

            Mail::to($patient_data->email)->send(new CancelAppointmentPatientMail($p_markDownData));
            Mail::to($doctor_data->email)->send(new CancelAppointmentDoctorMail($d_markDownData));
            Mail::to($adminMail->email)->send(new CancelAppointmentAccountantMail($accountsMarkDownData));

            $text = "Appointment with " . $patient_data->name . " " . $patient_data->last_name . " has been Cancelled by " . $user_type;
            $notification_id = Notification::create([
                'user_id' =>  $getAppointment->doctor_id,
                'type' => '/doctor/appointments',
                'text' => $text,
                'appoint_id' => $request->id,
            ]);
            $data = [
                'user_id' =>  $getAppointment->doctor_id,
                'type' => '/doctor/appointments',
                'text' => $text,
                'appoint_id' => $request->id,
                'refill_id' => "null",
                'received' => 'false',
                'session_id' => 'null',
            ];
            // \App\Helper::firebase($getAppointment->doctor_id,'notification',$notification_id->id,$data);
            event(new RealTimeMessage($getAppointment->patient_id));
        } catch (Exception $e) {
            Log::error($e);
        }

        return redirect()->route('pat_appointments')->with('success', 'Appointment cancelled.');
    }

    public function select_appointment_location()
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
            // ->where('doctor_licenses.is_verified','1')
            // ->groupBy('doctor_licenses.state_id')
            // ->select('states.*')->get();
            return view('dashboard_patient.Appointments.locations', compact('locations','Reg_state'));
        }
        else
        {
            return redirect()->route('errors', '101');
        }
    }

    public function select_specialization()
    {
        //$state = State::find($loc_id);
        if (auth()->user()->user_type=='patient') {
            // $Reg_state = auth()->user()->state_id;
            // $state = State::find($Reg_state);

            $spe = DB::table('specializations')
            // ->join('specalization_price', 'specalization_price.spec_id', 'specializations.id')
            ->join('users', 'users.specialization', 'specializations.id')
            // ->join('doctor_licenses', 'doctor_licenses.doctor_id', 'users.id')
            // ->where('specalization_price.state_id', $Reg_state)
            // ->where('doctor_licenses.is_verified', '1')
            ->groupBy('specializations.id')
            ->select('specializations.*')
            ->get();

            // $locations = DB::table('states')->where('active','1')->orderBy('name','ASC')->get();
            // foreach($locations as $loc)
            // {
            //     $docs = DB::table('doctor_licenses')
            //     ->where('state_id',$loc->id)
            //     ->where('is_verified','1')
            //     ->count();

            //     if($docs>0)
            //     {
            //         $loc->docs = 1;
            //     }
            //     else
            //     {
            //         $loc->docs = 0;
            //     }
            // }

            return view('dashboard_patient.Appointments.specialization', compact('spe'));
        } else {
            return redirect()->route('errors', '101');
        }
    }

    public function get_state_specializations(Request $request)
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

        return view('dashboard_patient.Appointments.load_specs', compact('spe'));
    }

    public function request_session($id)
    {

        $user = Auth::user();
        $state = DB::table('sessions')->where('doctor_id',$id)->where('patient_id',$user->id)->first();
        $loc_id = $state->location_id;
        $state = DB::table('states')->where('id',$loc_id)->first();
        $doctors = DB::table('users')->where('user_type', 'doctor')->where('id', $id)
            ->join('doctor_licenses', 'doctor_licenses.doctor_id', 'users.id')
            ->join('specializations', 'specializations.id', 'users.specialization')
            ->join('states', 'states.id', 'users.state_id')
            ->where('users.status', 'online')
            ->where('users.active', '1')
            ->where('doctor_licenses.is_verified', '1')
            ->groupBy('doctor_licenses.doctor_id')
            ->select('users.*', 'specializations.name as sp_name')
            ->first();
        //dd($doctors);
        foreach ($doctors as $doctor) {
            $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);
            $doctor->flag = '';

            $refered_doctors = DB::table('referals')
                ->where('patient_id', $user->id)
                ->where('sp_doctor_id', $doctor->id)
                ->where('status', 'accepted')
                ->first();
            if ($refered_doctors != null) {
                $doctor->flag = 'Recommended';
            }

            $already_session_did = DB::table('sessions')
                ->where('patient_id', $user->id)
                ->where('specialization_id', $doctors->specialization)
                ->where('doctor_id', $doctor->id)
                ->where('status', 'ended')
                ->first();

            if ($already_session_did != null) {
                $doctor->flag = 'Visited';
            }
        }
        $session = null;
        $price = DB::table('specalization_price')->where('spec_id', $id)->where('state_id',$loc_id)->first();
        if ($price != null) {
        if ($price->follow_up_price != null) {
            $session = DB::table('sessions')->where('patient_id', $user->id)->where('status', 'ended')
                ->join('specializations', 'sessions.specialization_id', 'specializations.id')
                ->join('specalization_price', 'sessions.specialization_id', 'specalization_price.spec_id')
                ->select('specializations.name as sp_name', 'specalization_price.follow_up_price as price')
                ->where('specialization_id', $doctors->specialization)->first();
        }}else{
            return view('errors.101');
        }
        //dd($doctors);
        return view('dashboard_patient.Appointments.book_appointment', compact('doctors', 'session', 'id', 'user','loc_id','state'));

        //return view('appointments.choose_doctor_for_appointment',compact('doctors','refered_doctors','already_session_did'));
    }

    public function book_appointment(Request $req,$id)
    {
        $user = Auth::user();
        $user->ses_id = '';
        if($id != '21'){
            if($req->name!=null){
                $doctors = DB::table('users')
                ->join('specializations', 'specializations.id', 'users.specialization')
                ->leftJoin('doctor_schedules',function ($join) {
                    $join->on('doctor_schedules.doctorID', '=' , 'users.id');
                    $join->where('doctor_schedules.title','=','Availability');
                    $join->where('doctor_schedules.end','>',date('Y-m-d H:i:s'));
                })
                ->where('users.specialization', $req->spec_id)
                ->where('users.active', '1')
                ->where(DB::raw('CONCAT(users.name, " ",users.last_name)'),'LIKE','%'. $req->name . '%')
                ->select('users.*', 'specializations.name as sp_name','doctor_schedules.title')
                ->groupBy('users.id')
                ->orderby('doctor_schedules.doctorID','DESC')
                ->paginate(12);
                $id = $req->spec_id;
            }else{
                $doctors = DB::table('users')
                    ->join('specializations', 'specializations.id', 'users.specialization')
                    ->leftJoin('doctor_schedules',function ($join) {
                        $join->on('doctor_schedules.doctorID', '=' , 'users.id');
                        $join->where('doctor_schedules.title','=','Availability');
                        // $join->where('doctor_schedules.end','>',date('Y-m-d H:i:s'));
                    })
                    ->where('users.specialization', $id)
                    ->where('users.active', '1')
                    ->select('users.*', 'specializations.name as sp_name','doctor_schedules.title')
                    ->groupBy('users.id')
                    ->orderby('doctor_schedules.doctorID','DESC')
                    ->paginate(12);
            }
            foreach ($doctors as $doctor) {
                $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);
                $doctor->flag = '';

                $refered_doctors = DB::table('referals')
                    ->where('patient_id', $user->id)
                    ->where('sp_doctor_id', $doctor->id)
                    ->where('status', 'accepted')
                    ->first();
                if ($refered_doctors != null) {
                    $doctor->flag = 'Recommended';
                }

                $already_session_did = DB::table('sessions')
                    ->where('patient_id', $user->id)
                    ->where('specialization_id', $id)
                    ->where('doctor_id', $doctor->id)
                    ->where('status', 'ended')
                    ->first();

                if ($already_session_did != null) {
                    $doctor->flag = 'Visited';
                }
            }
            // $session = null;
            // $price = DB::table('specalization_price')->where('spec_id', $id)->first();
            // if($price!=null)
            // {
            //     if ($price->follow_up_price != null) {
            //         $session = DB::table('sessions')
            //         ->leftjoin('specializations', 'sessions.specialization_id', 'specializations.id')
            //         ->leftjoin('specalization_price', 'sessions.specialization_id', 'specalization_price.spec_id')
            //         ->where('sessions.patient_id', $user->id)
            //         ->where('sessions.status', 'ended')
            //         ->select('specializations.name as sp_name', 'specalization_price.follow_up_price as price')
            //         ->where('sessions.specialization_id', $id)
            //         ->first();
            //     }
            // }
            // else
            // {
            //     $price = DB::table('specalization_price')->first();
            //     $price->follow_up_price = "0";
            //     $price->initial_price = "0";
            // }
        }else{
            $flag = 'appointment';
            return view('dashboard_patient.Evisit.patient_health',compact('user','flag'));
        }
        return view('dashboard_patient.Appointments.book_appointment', compact('doctors', 'id', 'user'));
        //return view('appointments.choose_doctor_for_appointment',compact('doctors','refered_doctors','already_session_did'));
    }

    public function requested_session($id)
    {
        $user = Auth::user();
        $ses = DB::table('sessions')->where('id',$id)->first();
        $user->ses_id = '';
        $loc_id = $ses->location_id;
        $state = DB::table('states')->where('id', $loc_id)->first();
        $doctors = DB::table('doctor_licenses')
        ->join('users', 'doctor_licenses.doctor_id', 'users.id')
        ->join('specializations', 'specializations.id', 'users.specialization')
        ->leftJoin('doctor_schedules',function ($join) {
            $join->on('doctor_schedules.doctorID', '=' , 'users.id');
            $join->where('doctor_schedules.title','=','Availability');
            $join->where('doctor_schedules.end','>',date('Y-m-d H:i:s'));
        })
        ->where('doctor_licenses.state_id', $loc_id)
        ->where('users.id', $ses->doctor_id)
        ->where('users.active', '1')
        ->where('doctor_licenses.is_verified', '1')
        ->select('users.*', 'specializations.name as sp_name','doctor_schedules.title')
        ->groupBy('users.id')
        ->orderby('doctor_schedules.doctorID','DESC')
        ->paginate(12);
        foreach ($doctors as $doctor) {
            $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);
            $doctor->flag = '';

            $refered_doctors = DB::table('referals')
                ->where('patient_id', $user->id)
                ->where('sp_doctor_id', $doctor->id)
                ->where('status', 'accepted')
                ->first();
            if ($refered_doctors != null) {
                $doctor->flag = 'Recommended';
            }

            $already_session_did = DB::table('sessions')
                ->where('patient_id', $user->id)
                ->where('specialization_id', $ses->specialization_id)
                ->where('doctor_id', $doctor->id)
                ->where('status', 'ended')
                ->first();

            if ($already_session_did != null) {
                $doctor->flag = 'Visited';
            }
        }
        $session = null;
        $price = DB::table('specalization_price')->where('spec_id', $ses->specialization_id)->where('state_id',$loc_id)->first();
        if($price!=null)
        {
            if ($price->follow_up_price != null) {
                $session = DB::table('sessions')
                ->leftjoin('specializations', 'sessions.specialization_id', 'specializations.id')
                ->leftjoin('specalization_price', 'sessions.specialization_id', 'specalization_price.spec_id')
                ->where('sessions.patient_id', $user->id)
                ->where('sessions.status', 'ended')
                ->select('specializations.name as sp_name', 'specalization_price.follow_up_price as price')
                ->where('sessions.specialization_id', $ses->specialization_id)
                ->first();
            }
        }
        else
        {
            $price = DB::table('specalization_price')->first();
            $price->follow_up_price = "0";
            $price->initial_price = "0";
        }
        return view('dashboard_patient.Appointments.requested_appointment', compact('doctors','price','session', 'id', 'user','loc_id','state'));
        //return view('appointments.choose_doctor_for_appointment',compact('doctors','refered_doctors','already_session_did'));
    }

    public function book_appointment_ajax(Request $request)
    {
        // if($req->name!=null){
        //     $doctors = DB::table('doctor_licenses')
        //     ->join('users', 'doctor_licenses.doctor_id', 'users.id')
        //     ->join('specializations', 'specializations.id', 'users.specialization')
        //     ->leftJoin('doctor_schedules',function ($join) {
        //         $join->on('doctor_schedules.doctorID', '=' , 'users.id');
        //         $join->where('doctor_schedules.title','=','Availability');
        //         $join->where('doctor_schedules.end','>',date('Y-m-d H:i:s'));
        //     })
        //     ->where('doctor_licenses.state_id', $loc_id)
        //     ->where('users.specialization', $id)
        //     ->where('users.active', '1')
        //     ->where('doctor_licenses.is_verified', '1')
        //     ->where(DB::raw('CONCAT(users.name, " ",users.last_name)'),'LIKE','%'. $req->name . '%')
        //     ->select('users.*', 'specializations.name as sp_name','doctor_schedules.title')
        //     ->groupBy('users.id')
        //     ->orderby('doctor_schedules.doctorID','DESC')
        //     ->paginate(12);
        // }else{

        // }
        $user = auth()->user();
        $doctors = DB::table('doctor_licenses')
        ->join('users', 'doctor_licenses.doctor_id', 'users.id')
        ->join('specializations', 'specializations.id', 'users.specialization')
        ->leftJoin('doctor_schedules',function ($join) {
            $join->on('doctor_schedules.doctorID', '=' , 'users.id');
            $join->where('doctor_schedules.title','=','Availability');
            $join->where('doctor_schedules.end','>',date('Y-m-d H:i:s'));
        })
        ->where('doctor_licenses.state_id', $request->loc_id)
        ->where('users.specialization', $request->spec_id)
        ->where('users.active', '1')
        ->where('doctor_licenses.is_verified', '1')
        ->select('users.*', 'specializations.name as sp_name','doctor_schedules.title')
        ->groupBy('users.id')
        ->orderby('doctor_schedules.doctorID','DESC')
        ->get();

        foreach ($doctors as $doctor) {
            $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);
            $doctor->flag = '';

            $refered_doctors = DB::table('referals')
            ->where('patient_id', $user->id)
            ->where('sp_doctor_id', $doctor->id)
            ->where('status', 'accepted')
            ->first();
            if ($refered_doctors != null) {
                $doctor->flag = 'Recommended';
            }

            $already_session_did = DB::table('sessions')
            ->where('patient_id', $user->id)
            ->where('specialization_id', $request->spec_id)
            ->where('doctor_id', $doctor->id)
            ->where('status', 'ended')
            ->first();

            if ($already_session_did != null) {
                $doctor->flag = 'Visited';
            }
        }
        $session = null;
        $price = DB::table('specalization_price')->where('spec_id', $request->spec_id)->where('state_id',$request->loc_id)->first();
        if($price==null)
        {
            return "1";
        }
        if($price->follow_up_price != null) {
            $session = DB::table('sessions')
            ->leftjoin('specializations', 'sessions.specialization_id', 'specializations.id')
            ->leftjoin('specalization_price', 'sessions.specialization_id', 'specalization_price.spec_id')
            ->where('sessions.patient_id', $user->id)
            ->where('sessions.status', 'ended')
            ->select('specializations.name as sp_name', 'specalization_price.follow_up_price as price')
            ->where('sessions.specialization_id', $request->spec_id)
            ->first();
        }
        return view('dashboard_patient.Appointments.load_docs', compact('doctors','price','session', 'user'));
    }

    public function psych_book_appointment(Request $req,$id)
    {

        $user = Auth::user();
        $user->ses_id = '';
        $input = $req->all();
            if($input!=null){
                $doctors = DB::table('users')
                ->join('specializations', 'specializations.id', 'users.specialization')
                ->leftJoin('doctor_schedules',function ($join) {
                    $join->on('doctor_schedules.doctorID', '=' , 'users.id');
                    $join->where('doctor_schedules.title','=','Availability');
                    $join->where('doctor_schedules.end','>',date('Y-m-d H:i:s'));
                })
                ->where('users.specialization', $req->spec_id)
                ->where('users.active', '1')
                ->where('users.name','LIKE','%'. $req->name . '%')
                ->select('users.*', 'specializations.name as sp_name','doctor_schedules.title')
                ->groupBy('users.id')
                ->orderby('doctor_schedules.doctorID','DESC')
                ->paginate(12);
                $loc_id = $req->loc_id;
                $state = DB::table('states')->where('id', $loc_id)->first();
                $id = $req->spec_id;
            }else{
                $doctors = DB::table('users')
                    ->join('specializations', 'specializations.id', 'users.specialization')
                    ->leftJoin('doctor_schedules',function ($join) {
                        $join->on('doctor_schedules.doctorID', '=' , 'users.id');
                        $join->where('doctor_schedules.title','=','Availability');
                        $join->where('doctor_schedules.end','>',date('Y-m-d H:i:s'));
                    })
                    ->where('users.specialization', $id)
                    ->where('users.active', '1')
                    ->select('users.*', 'specializations.name as sp_name','doctor_schedules.title')
                    ->groupBy('users.id')
                    ->orderby('doctor_schedules.doctorID','DESC')
                    ->paginate(12);
            }
            foreach ($doctors as $doctor) {
                $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);
                $doctor->flag = '';

                $refered_doctors = DB::table('referals')
                    ->where('patient_id', $user->id)
                    ->where('sp_doctor_id', $doctor->id)
                    ->where('status', 'accepted')
                    ->first();
                if ($refered_doctors != null) {
                    $doctor->flag = 'Recommended';
                }

                $already_session_did = DB::table('sessions')
                    ->where('patient_id', $user->id)
                    ->where('specialization_id', $id)
                    ->where('doctor_id', $doctor->id)
                    ->where('status', 'ended')
                    ->first();

                if ($already_session_did != null) {
                    $doctor->flag = 'Visited';
                }
            }
            $session = null;
            $price = DB::table('specalization_price')->where('spec_id', $id)->first();
            if ($price != null) {
            if ($price->follow_up_price != null) {
                $session = DB::table('sessions')
                    ->leftjoin('specializations', 'sessions.specialization_id', 'specializations.id')
                    ->leftjoin('specalization_price', 'sessions.specialization_id', 'specalization_price.spec_id')
                    ->where('sessions.patient_id', $user->id)
                    ->where('sessions.status', 'ended')
                    ->select('specializations.name as sp_name', 'specalization_price.follow_up_price as price')
                    ->where('sessions.specialization_id', $id)
                    ->first();
            }}else{
                return view('errors.101');
            }
        return view('dashboard_patient.Appointments.book_appointment', compact('doctors','price','session', 'id', 'user'));

        //return view('appointments.choose_doctor_for_appointment',compact('doctors','refered_doctors','already_session_did'));

    }

    public function paid_book_appointment(Request $req,$id,$ses_id)
    {

        $user = Auth::user();
        $user->ses_id = $ses_id;
        $ses = DB::table('sessions')->where('id', $ses_id)->first();
        $loc_id = $ses->location_id;
        $state = DB::table('states')->where('id', $loc_id)->first();
        if(isset($req)){
            $doctors = DB::table('doctor_licenses')
            ->join('users', 'doctor_licenses.doctor_id', 'users.id')
            ->join('specializations', 'specializations.id', 'users.specialization')
            ->leftJoin('doctor_schedules',function ($join) {
                $join->on('doctor_schedules.doctorID', '=' , 'users.id');
                $join->where('doctor_schedules.title','=','Availability');
                $join->where('doctor_schedules.end','>',date('Y-m-d H:i:s'));
            })
            ->where('doctor_licenses.state_id', $loc_id)
            ->where('users.specialization', $id)
            ->where('users.active', '1')
            ->where('doctor_licenses.is_verified', '1')
            ->where('users.name','LIKE','%'. $req->name . '%')
            ->select('users.*', 'specializations.name as sp_name','doctor_schedules.title')
            ->groupBy('users.id')
            ->orderby('doctor_schedules.doctorID','DESC')
            ->paginate(12);
        }else{
            $doctors = DB::table('doctor_licenses')
                ->join('users', 'doctor_licenses.doctor_id', 'users.id')
                ->join('specializations', 'specializations.id', 'users.specialization')
                ->leftJoin('doctor_schedules',function ($join) {
                    $join->on('doctor_schedules.doctorID', '=' , 'users.id');
                    $join->where('doctor_schedules.title','=','Availability');
                    $join->where('doctor_schedules.end','>',date('Y-m-d H:i:s'));
                })
                ->where('doctor_licenses.state_id', $loc_id)
                ->where('users.specialization', $id)
                ->where('users.active', '1')
                ->where('doctor_licenses.is_verified', '1')
                ->select('users.*', 'specializations.name as sp_name','doctor_schedules.title')
                ->groupBy('users.id')
                ->orderby('doctor_schedules.doctorID','DESC')
                ->paginate(12);
        }
        foreach ($doctors as $doctor) {
            $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);
            $doctor->flag = '';

            $refered_doctors = DB::table('referals')
                ->where('patient_id', $user->id)
                ->where('sp_doctor_id', $doctor->id)
                ->where('status', 'accepted')
                ->first();
            if ($refered_doctors != null) {
                $doctor->flag = 'Recommended';
            }

            $already_session_did = DB::table('sessions')
                ->where('patient_id', $user->id)
                ->where('specialization_id', $id)
                ->where('doctor_id', $doctor->id)
                ->where('status', 'ended')
                ->first();

            if ($already_session_did != null) {
                $doctor->flag = 'Visited';
            }
        }
        $session = null;
        $price = DB::table('specalization_price')->where('spec_id', $id)->where('state_id',$loc_id)->first();
        if ($price != null) {
        if ($price->follow_up_price != null) {
            $session = DB::table('sessions')
                ->leftjoin('specializations', 'sessions.specialization_id', 'specializations.id')
                ->leftjoin('specalization_price', 'sessions.specialization_id', 'specalization_price.spec_id')
                ->where('sessions.patient_id', $user->id)
                ->where('sessions.status', 'ended')
                ->select('specializations.name as sp_name', 'specalization_price.follow_up_price as price')
                ->where('sessions.specialization_id', $id)
                ->first();
        }}else{
            $price = DB::table('specalization_price')->first();
            $price->initial_price = "Not Available";
            $price->follow_up_price = "Not Available";
        }
        //dd($doctors);
        return view('dashboard_patient.AppointmentsPaid.book_appointment', compact('doctors','price','session', 'id', 'user','ses_id','loc_id','state'));

        //return view('appointments.choose_doctor_for_appointment',compact('doctors','refered_doctors','already_session_did'));

    }

    public function get_doc_avail_dates(Request $request)
    {
        $today = date("Y-m-d", strtotime(Carbon::today()));
        $dates = DoctorSchedule::where('doctorID', $request['id'])->where('title', 'Availability')->orderBy('date', 'asc')->get();
        $availability =
        [
            'mon' => 0,
            'tues' => 0,
            'weds' => 0,
            'thurs' => 0,
            'fri' => 0,
            'sat' => 0,
            'sun' => 0,
        ];
        if ($dates != null) {
            foreach ($dates as $date) {
                if($date->mon){
                    $availability['mon'] = 1;
                }
                if($date->tues){
                    $availability['tues'] = 1;
                }
                if($date->weds){
                    $availability['weds'] = 1;
                }
                if($date->thurs){
                    $availability['thurs'] = 1;
                }
                if($date->fri){
                    $availability['fri'] = 1;
                }
                if($date->sat){
                    $availability['sat'] = 1;
                }
                if($date->sun){
                    $availability['sun'] = 1;
                }
                // $date->date = User::convert_utc_to_user_timezone(auth()->user()->id, $date->start)['date'];
                // $date->start = User::convert_utc_to_user_timezone(auth()->user()->id, $date->start)['datetime'];
                // $date->end = User::convert_utc_to_user_timezone(auth()->user()->id, $date->end)['datetime'];
            }
            $startDate = Carbon::today();
            $datesToShow = [];
            $dayMap = [
                'Monday' => 'mon',
                'Tuesday' => 'tues',
                'Wednesday' => 'weds',
                'Thursday' => 'thurs',
                'Friday' => 'fri',
                'Saturday' => 'sat',
                'Sunday' => 'sun',
            ];
            for ($i = 0; $i <= 7; $i++) {
                $currentDate = $startDate->copy()->addDays($i);
                $dayName = $currentDate->format('l');
                if ($availability[$dayMap[$dayName]] === 1) {
                    $datesToShow[] = $currentDate->toDateString();
                }
            }
            $data['dates'] = $datesToShow;
            $data['user'] = auth()->user();
        } else {
            $data['dates'] = '';
        }
        //$data['doc'] = User::where('id',$request['id'])->first();
        $doc = DB::table('users')->join('specializations', 'users.specialization', 'specializations.id')->where('users.id', $request['id'])->select('users.*', 'specializations.name as sp_name')->first();
        $doc->user_image = \App\Helper::check_bucket_files_url($doc->user_image);
        $data['doc'] = $doc;
        $symptoms = DB::table('isabel_symptoms')->get();
        $data['symptoms'] = $symptoms;
        return $data;
    }

    public function Doctor_Appointments()
    {
        $user = Auth::user();
        $today = date('Y-m-d');
        $todayTime = date('h:i A');
        // $user_state = $user->state_id;
        // $state = State::find($user_state);
        // if ($state->active == 1) {
            $make_reschudle = DB::table('appointments')
                ->where('appointments.date', '<=', $today)
                ->where('appointments.time', '<', $todayTime)
                ->where('appointments.status', 'pending')
                ->update(['appointments.status' => 'make-reschedule']);

            $appointments = DB::table('appointments')
                ->join('sessions', 'appointments.id', 'sessions.appointment_id')
                ->where('appointments.doctor_id', $user->id)
                ->where('sessions.status','!=','pending')
                ->orderBy('appointments.created_at', 'DESC')
                ->select('appointments.*', 'sessions.id as session_id', 'sessions.que_message as msg', 'sessions.join_enable')
                ->paginate(8);
            foreach ($appointments as $app) {
                $ddd = date('Y-m-d H:i:s', strtotime("$app->date $app->time"));
                // $ddd=date("Y-m-d H:i:s",strtotime($app->date $app->time));
                $app->date = User::convert_utc_to_user_timezone($user->id, $ddd)['date'];
                $app->time = User::convert_utc_to_user_timezone($user->id, $ddd)['time'];
            }
            return view('dashboard_doctor.Appointments.index', compact('appointments', 'user'));
        // } else {
            // return redirect()->route('errors', '101');
        // }
    }

    public function create_appointment(Request $request)
    {
        // dd($request->all());
        $user = Auth::user();
        $data = $request->validate([
            'fname' =>  ['required', 'string', 'max:255'],
            'lname' =>  ['required', 'string', 'max:255'],
            'email' =>  ['required', 'string', 'max:255'],
            'phone' =>  ['required', 'string', 'max:255'],
            'provider' =>  ['required', 'string', 'max:255'],
            // 'problem' => ['required'],
            'date' =>  ['required', 'max:255'],
            'time' =>  ['required', 'max:255']
        ]);
        $datetime = date('Y-m-d', strtotime($request->date)) . ' ' . $data['time'];
        $datetime = User::convert_user_timezone_to_utc($user->id, $datetime);

        $p_s_Time = date('H:i:s', (strtotime($datetime['datetime'])));
        $full_date = $datetime['date'];
        $date = $datetime['date'];
        $date1 = new Carbon($date);
        $app_date = $datetime['date'];

        // dd($p_s_Time,$full_date,$date,$date1,$app_date);


        $patient_id = Auth::user()->id;
        $pro_id = $data['provider'];
        $pro_name_data = DB::table('users')->select('name', 'last_name', 'email', 'phone_number')->where('id', $pro_id)->get();
        $provider_name = $pro_name_data[0]->name . " " . $pro_name_data[0]->last_name;
        $appoint_data_time = $datetime['datetime'];

        $firstReminder = date('Y-m-d H:i:s', (strtotime('-1 day', strtotime($appoint_data_time))));
        $timestamp = strtotime($appoint_data_time);
        $time = $timestamp - (15 * 60);

        $secondReminder = date("Y-m-d H:i:s", $time);
        $new_app_id;
        $randNumber=rand(11,99);
        $getLastAppId = DB::table('appointments')->orderBy('id', 'desc')->first();
        if ($getLastAppId != null) {
            $new_app_id = $getLastAppId->appointment_id + 1+$randNumber;
        } else {
            $new_app_id = rand(411111,499999);
        }

        $app_id = Appointment::create([
            'doctor_id' =>  $data['provider'],
            'patient_id' =>  $patient_id,
            'patient_name' =>  $data['fname'] . " " . $data['lname'],
            'doctor_name' => $provider_name,
            'email' => $data['email'],
            'phone' => $data['phone'],
            // 'problem' => $problems,
            'date' => $app_date,
            'day' => $date1->format('l'),
            'status' => 'pending',
            'time' => $p_s_Time,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'reminder_one_status' => "pending",
            'reminder_two_status' => "pending",
            'reminder_one' => $firstReminder,
            'reminder_two' => $secondReminder,
            'appointment_id' => $new_app_id,
        ])->id;

        $check_session_already_have = DB::table('sessions')
            ->where('doctor_id', $data['provider'])
            ->where('patient_id', $patient_id)
            ->where('specialization_id', $request->spec_id)
            ->count();

        $session_price = "";
        if ($check_session_already_have > 0) {
            $session_price_get = User::find($data['provider']);
            // consultation_fee
            // followup_fee
            if ($session_price_get->followup_fee != null) {
                $session_price = $session_price_get->followup_fee;
            } else {
                $session_price = $session_price_get->consultation_fee;
            }
        } else {
            $session_price_get = User::find($data['provider']);
            $session_price = $session_price_get->consultation_fee;
        }

        $new_session_id;
        $randNumber=rand(11,99);
        $getLastSessionId = DB::table('sessions')->orderBy('id', 'desc')->first();
        if ($getLastSessionId != null) {
            $new_session_id = $getLastSessionId->session_id + 1+$randNumber;
        } else {
            $new_session_id = rand(311111,399999);
        }

        $permitted_chars = 'abcdefghijklmnopqrstuvwxyz';
        $channelName = substr(str_shuffle($permitted_chars), 0, 8);
        $sessiondate = Carbon::now();
        if($request->ses_id != '' && $request->ses_id != "undefined"){
            $session_id = Session::where('id',$request->ses_id)->update([
                'patient_id' =>  $patient_id,
                'appointment_id' =>  $app_id,
                'doctor_id' =>  $pro_id,
                'date' => date('Y-m-d', (strtotime($datetime['datetime']))),
                'status' => 'paid',
                'queue' => 0,
                // 'symptom_id' => $symp->id,
                'remaining_time' => 'full',
                'channel' => $channelName,
                'created_at' => $sessiondate,
                'updated_at' => $sessiondate,
                'specialization_id' => $request->spec_id,
                'price' => $session_price,
                'location_id' => $request->loc_id,
                // 'session_id' => $new_session_id,
                // 'isabel_diagnosis_id' => $isabel_symp_id,
                'validation_status' => "valid",
                'start_time' => date('Y-m-d H:i:s', (strtotime($datetime['datetime']))),
                'end_time' => date('Y-m-d H:i:s', (strtotime('15 min', strtotime($datetime['datetime'])))),
            ]);
            return redirect()->route('pat_appointments');
        }else{
            $session_id = Session::create([
                'patient_id' =>  $patient_id,
                'appointment_id' =>  $app_id,
                'doctor_id' =>  $pro_id,
                'date' => date('Y-m-d', (strtotime($datetime['datetime']))),
                'status' => 'pending',
                'queue' => 0,
                // 'symptom_id' => $symp->id,
                'remaining_time' => 'full',
                'channel' => $channelName,
                'join_enable' => null,
                'created_at' => $sessiondate,
                'updated_at' => $sessiondate,
                'specialization_id' => $request->spec_id,
                'price' => $session_price,
                'session_id' => $new_session_id,
                // 'isabel_diagnosis_id' => $isabel_symp_id,
                'location_id' => $request->loc_id,
                'validation_status' => "valid",
                'start_time' => date('Y-m-d H:i:s', (strtotime($datetime['datetime']))),
                'end_time' => date('Y-m-d H:i:s', (strtotime('15 min', strtotime($datetime['datetime'])))),
            ])->id;
            $session = Session::find($session_id);
            $data = "Appointment-".$new_session_id."-1";
            $pay = new \App\Http\Controllers\MeezanPaymentController();
            $res = $pay->payment($data,($session->price*100));
            if (isset($res) && $res->errorCode == 0) {
                return redirect($res->formUrl);
            }else{
                return redirect()->back()->with('error','Sorry, we are currently facing server issues. Please try again later.');
            }
            // return redirect()->route('appoint_payment', ['id' => $session_id]);
        }
    }

    public function appoint_payment($session_id)
    {
        $years = [];
        $id = auth()->user()->id;
        $current_year = Carbon::now()->format('Y');
        array_push($years, $current_year);
        $j = 15;
        for ($i = 0; $i < $j; $i++) {
            $get_year = $current_year += 1;
            array_push($years, $get_year);
        }
        $states = State::where('country_code', 'US')->get();
        $session_data = DB::table('sessions')->where('id', $session_id)->first();
        if($session_data->status == "pending")
        {
            $price = DB::table('sessions')->where('id', $session_id)->first();
            $price = $price->price;
            // dd($price);
            $cards = DB::table('card_details')->where('user_id', $id)->get();

            return view('dashboard_patient.Appointments.payment', compact('session_id', 'states', 'years', 'session_data', 'cards', 'price'));
        }
        else
        {
            return redirect()->route('New_Patient_Dashboard');
        }
    }

    public function payment_appointment(Request $request)
    {
        $this->validate($request, [
            'card_holder_name' => 'required',
            'card_num' => 'required|integer|min:16',
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
        //dd($request);
        $getSession = DB::table('sessions')->where('id', $request->session_id)->first();
        $name = $request->card_holder_name . $request->card_holder_name_middle;
        $city = City::find($request->city)->name;
        $state = State::find($request->state)->name;

        $input = [
            'info' => [
                'subject' => $request->subject,
                'user_id' => $getSession->patient_id,
                'description' => $request->session_id,
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
        $request_data = new Request($input);
        $pay = new PaymentController();
        $responce = json_decode($pay->proceedToPay($request_data));

        if ($responce->success == 'true') {
            Session::where('id', $request->session_id)->update(['status' => 'paid']);
            $firebase_ses = DB::table('sessions')->where('id', $request->session_id)->first();
            $firebase_ses->received = false;
            try {
                $doctor_data = DB::table('users')->where('id', $getSession->doctor_id)->first();
                $patient_data = DB::table('users')->where('id', $getSession->patient_id)->first();
                $getAppointment = DB::table('appointments')->where('id', $getSession->appointment_id)->first();
                $time = date('h:i a', strtotime($getAppointment->time));
                $date = date('M-d-Y', strtotime($getAppointment->date));
                $markDownData = [
                    'doc_name' => ucwords($doctor_data->name),
                    'pat_name' => ucwords($patient_data->name),
                    'time' => $time,
                    'date' => $date,
                    'pat_mail' => $patient_data->email,
                    'doc_mail' => $doctor_data->email,
                ];
                // mailgun send new appoointment mail to doctor and patient
                // Mail::to('baqir.redecom@gmail.com')->send(new NewAppointmentPatientMail($markDownData));
                // Mail::to('baqir.redecom@gmail.com')->send(new NewAppointmentDoctorMail($markDownData));
                Mail::to($patient_data->email)->send(new NewAppointmentPatientMail($markDownData));
                Mail::to($doctor_data->email)->send(new NewAppointmentDoctorMail($markDownData));

                $text = "New Appointment Created by " . $patient_data->name . " " . $patient_data->last_name;
                $notification_id = Notification::create([
                    'user_id' =>  $getSession->doctor_id,
                    'type' => '/doctor/appointments',
                    'text' => $text,
                    'appoint_id' => $getSession->appointment_id,
                ]);
                $data = [
                    'user_id' =>  $getSession->doctor_id,
                    'type' => '/doctor/appointments',
                    'text' => $text,
                    'appoint_id' => $getSession->appointment_id,
                    'refill_id' => "null",
                    'received' => 'false',
                    'session_id' => 'null',
                ];
                // \App\Helper::firebase($getSession->doctor_id,'notification',$notification_id->id,$notification_id);
                event(new RealTimeMessage($getSession->doctor_id));

                // \App\Helper::firebase($firebase_ses->doctor_id,'updateQuePatient',$firebase_ses->id,$firebase_ses);
                event(new updateQuePatient('update patient que'));
            } catch (Exception $e) {
                Log::error($e);
            }
            return redirect()->route('pat_appointments')->with("message", "Appointment Create Successfully");
        } else {
            foreach ($responce->errors as $error) {
                $erroras = $error;
            }

            return redirect()->route('appoint_payment', ['id' => $request->session_id])->with('error_message', $erroras);
        }
    }

    public function api_payment_appointment(Request $request)
    {
        // dd($request);
        $id = auth()->user()->id;
        $getSession = DB::table('sessions')->where('id', $request->session_id)->first();
        if ((isset($request->old_card))) {
            $query = DB::table('card_details')
                ->where('id', $request->card_no)
                ->get();
            $pay = new PaymentController();
            $profile = $query[0]->customerProfileId;
            $payment = $query[0]->customerPaymentProfileId;
            $amount = $request->amount_charge;
            // dd($profile,$payment,$amount);
            $response = ($pay->new_createPaymentwithCustomerProfile($amount, $profile, $payment));
            $flag = false;
        } else {
            $this->validate($request, [
                'card_holder_name' => 'required',
                'card_holder_last_name' => 'required',
                'card_num' => 'required|min:16',
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
            $name = $request->card_holder_name . $request->card_holder_name_middle;
            $city = City::find($request->city)->name;
            $state = State::find($request->state)->name;
            $request->card_num = str_replace('-', '', $request->card_num);
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
            // dd($input);
            // $request_data=new Request($input);
            // dd($request_data);
            $pay = new PaymentController();
            $response = ($pay->new_createCustomerProfile($input));
            $flag = true;
        }


        if($response['messages']['message'][0]['text'] == 'Successful.') {
            if ($flag) {
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
                    'phoneNumber' => $request->phoneNumber,
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
            Session::where('id', $request->session_id)->update(['status' => 'paid']);
            $firebase_ses = DB::table('sessions')->where('id', $request->session_id)->first();
            $firebase_ses->received = false;
            // dd($getSession);
            try {
                $doctor_data = DB::table('users')->where('id', $getSession->doctor_id)->first();
                $patient_data = DB::table('users')->where('id', $getSession->patient_id)->first();
                $getAppointment = DB::table('appointments')->where('id', $getSession->appointment_id)->first();
                $d_date = User::convert_utc_to_user_timezone($doctor_data->id, $getAppointment->date . ' ' . $getAppointment->time);
                $p_date = User::convert_utc_to_user_timezone($patient_data->id, $getAppointment->date . ' ' . $getAppointment->time);
                // $time=date('h:i a',strtotime($getAppointment->time));

                $markDownData1 = [
                    'doc_name' => ucwords($doctor_data->name),
                    'pat_name' => ucwords($patient_data->name),
                    'time' => $p_date['time'],
                    'date' => $p_date['date'],
                    'pat_mail' => $patient_data->email,
                    'doc_mail' => $doctor_data->email,
                ];
                // dd($getAppointment->time,$dtime,$ptime);
                $markDownData2 = [
                    'doc_name' => ucwords($doctor_data->name),
                    'pat_name' => ucwords($patient_data->name),
                    'time' => $d_date['time'],
                    'date' => $d_date['date'],
                    'pat_mail' => $patient_data->email,
                    'doc_mail' => $doctor_data->email,
                ];
                // dd($markDownData);
                // mailgun send new appoointment mail to doctor and patient
                // Mail::to('baqir.redecom@gmail.com')->send(new NewAppointmentPatientMail($markDownData));
                // Mail::to('baqir.redecom@gmail.com')->send(new NewAppointmentDoctorMail($markDownData));
                Mail::to($patient_data->email)->send(new NewAppointmentPatientMail($markDownData1));
                Mail::to($doctor_data->email)->send(new NewAppointmentDoctorMail($markDownData2));

                $text = "New Appointment Created by " . $patient_data->name . " " . $patient_data->last_name;
                $notification_id = Notification::create([
                    'user_id' =>  $getSession->doctor_id,
                    'type' => '/doctor/appointments',
                    'text' => $text,
                    'appoint_id' => $getSession->appointment_id,
                ]);
                $data = [
                    'user_id' =>  $getSession->doctor_id,
                    'type' => '/doctor/appointments',
                    'text' => $text,
                    'appoint_id' => $getSession->appointment_id,
                    'refill_id' => "null",
                    'received' => 'false',
                    'session_id' => 'null',
                ];
                // \App\Helper::firebase($getSession->doctor_id,'notification',$notification_id->id,$data);
                event(new RealTimeMessage($getSession->doctor_id));

                // \App\Helper::firebase($firebase_ses->doctor_id,'updateQuePatient',$firebase_ses->id,$firebase_ses);
                event(new updateQuePatient('update patient que'));
            } catch (Exception $e) {
                Log::error($e);
            }
            return redirect()->route('pat_appointments')->with("message", "Appointment Create Successfully");
        } else {

            // dd($response['messages']['message'][0]['text']);
            $code = $response['messages']['message'][0]['code'];
            $message = TblTransaction::errorCode($code);
            DB::table('error_log')->insert([
                'user_id' => $id,
                'Error_code' => $code,
                'Error_text' => $response['messages']['message'][0]['text'],
            ]);
            return redirect()->route('appoint_payment', ['id' => $request->session_id])->with('error_message', $message);
        }
    }
    public function api_payment_session(Request $request)
    {
        $request->session_id = \Crypt::decrypt($request->session_id);
        // dd($request);
        // dd(substr($request->card_num, 0,1));
        $id = auth()->user()->id;
        // if ((isset($request->old_card))) {
        //     $query = DB::table('card_details')
        //         ->where('id', $request->card_no)
        //         ->get();
        //     // dd($query);
        //     $pay = new PaymentController();
        //     $profile = $query[0]->customerProfileId;
        //     $payment = $query[0]->customerPaymentProfileId;
        //     $amount = $request->amount_charge;
        //     // dd($profile,$payment,$amount);
        //     $response = ($pay->new_createPaymentwithCustomerProfile($amount, $profile, $payment));
        //     $flag = false;
        // } else {
        //     $this->validate($request, [
        //         'card_holder_name' => 'required',
        //         'card_holder_last_name' => 'required',
        //         'card_num' => 'required|min:16',
        //         'email' => 'required',
        //         'phoneNumber' => 'required',
        //         'month' => 'required|',
        //         'year' => 'required|',
        //         'cvc' => 'required|integer',
        //         'state' => 'required',
        //         'city' => 'required',
        //         'zipcode' => 'required',
        //         'address' => 'required',
        //         'session_id' => 'required',
        //         'amount_charge' => 'required',
        //         'subject' => 'required',
        //     ]);
        //     $getSession = DB::table('sessions')->where('id', $request->session_id)->first();
        //     $name = $request->card_holder_name . $request->card_holder_name_middle;
        //     $city = City::find($request->city)->name;
        //     $state = State::find($request->state)->name;
        //     $request->card_num = str_replace('-', '', $request->card_num);
        //     $input = [
        //         'user' => [
        //             'description' => $request->card_holder_name . " " . $request->card_holder_last_name,
        //             'email' => $request->email,
        //             'firstname' => $request->card_holder_name,
        //             'lastname' => $request->card_holder_last_name,
        //             'phoneNumber' => $request->phoneNumber,
        //         ],
        //         'info' => [
        //             'subject' => $request->subject,
        //             'user_id' => $getSession->patient_id,
        //             'description' => $request->session_id,
        //             'amount' => $request->amount_charge,
        //         ],
        //         'billing_info' => [
        //             'amount' => $request->amount_charge,
        //             'credit_card' => [
        //                 'number' => $request->card_num,
        //                 'expiration_month' => $request->month,
        //                 'expiration_year' => $request->year,
        //             ],
        //             'integrator_id' => $request->subject . '-' . $request->session_id,
        //             'csc' => $request->cvc,
        //             'billing_address' => [
        //                 'name' => $name,
        //                 'street_address' => $request->address,
        //                 'city' => $city,
        //                 'state' => $state,
        //                 'zip' => $request->zipcode,
        //             ]
        //         ]
        //     ];
        //     // dd($input);
        //     // $request_data=new Request($input);
        //     // dd($request_data);
        //     $pay = new PaymentController();
        //     $response = ($pay->new_createCustomerProfile($input));
        //     $flag = true;
        // }
        $response['messages']['message'][0]['text'] = "Successful.";
        $flag = false;

        if ($response['messages']['message'][0]['text'] == 'Successful.') {
            if ($flag) {
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
                    'phoneNumber' => $request->phoneNumber,
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
            $firebase_ses = DB::table('sessions')->where('id', $request->session_id)->first();
            $firebase_ses->received = false;
            try{
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
            $notification_id = Notification::create([
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
                'refill_id' => "null",
                'received' => 'false',
                'session_id' => 'null',
            ];
            // \App\Helper::firebase($getSession->doctor_id,'notification',$notification_id->id,$data);
            event(new RealTimeMessage($getSession->doctor_id));

            // \App\Helper::firebase($firebase_ses->doctor_id,'updateQuePatient',$firebase_ses->id,$firebase_ses);
            event(new updateQuePatient('update patient que'));

            }
            catch(Exception $e){
                Log::error($e);
            }
            return redirect()->route('waiting_room_pat', ['id' => \Crypt::encrypt($request->session_id)]);
        } else {
            // dd($response['messages']['message'][0]['text']);
            $code = $response['messages']['message'][0]['code'];
            $message = TblTransaction::errorCode($code);
            DB::table('error_log')->insert([
                'user_id' => $id,
                'Error_code' => $code,
                'Error_text' => $response['messages']['message'][0]['text'],
            ]);
            return redirect()->route('patient_session_payment_page', ['id' => \Crypt::encrypt($request->session_id)])->with('error_message', $message);
        }
    }

    public function doc_appointment_cancel(Request $request)
    {
        //dd($request);
        $user_type = auth()->user()->user_type;
        $app = Appointment::find($request->id);
        if ($app) {
            $app->status = $user_type . ' has cancelled the appointment';
            $app->save();
            if($user_type=='doctor')
            {
                DB::table('sessions')->where('appointment_id',$app->id)->update(['status'=>'paid']);
            }
            else
            {
                DB::table('sessions')->where('appointment_id',$app->id)->update(['status'=>'cancel']);
            }
        }

        // notifications comment
        try {

            $getAppointment = DB::table('appointments')->where('id', $request->id)->first();
            $doctor_data = DB::table('users')->where('id', $getAppointment->doctor_id)->first();
            $patient_data = DB::table('users')->where('id', $getAppointment->patient_id)->first();
            $adminMail = DB::table('users')->where('user_type', 'admin')->first();
            $p_datetime = User::convert_utc_to_user_timezone($patient_data->id, $getAppointment->date . ' ' . $getAppointment->time);
            $d_datetime = User::convert_utc_to_user_timezone($doctor_data->id, $getAppointment->date . ' ' . $getAppointment->time);
            $a_datetime = User::convert_utc_to_user_timezone($adminMail->id, $getAppointment->date . ' ' . $getAppointment->time);

            $p_markDownData = [
                'doc_name' => ucwords($doctor_data->name),
                'pat_name' => ucwords($patient_data->name),
                'time' => $p_datetime['time'],
                'date' => $p_datetime['date'],
                'pat_mail' => $patient_data->email,
                'doc_mail' => $doctor_data->email,
            ];





            $d_markDownData = [
                'doc_name' => ucwords($doctor_data->name),
                'pat_name' => ucwords($patient_data->name),
                'time' => $d_datetime['time'],
                'date' => $d_datetime['date'],
                'pat_mail' => $patient_data->email,
                'doc_mail' => $doctor_data->email,
            ];

            $accountsMarkDownData = [
                'doc_name' => ucwords($doctor_data->name),
                'pat_name' => ucwords($patient_data->name),
                'time' => $a_datetime['time'],
                'date' => $a_datetime['date'],
                'acounts_name' => ucwords($adminMail->name),
                'acounts_mail' => $adminMail->email,
            ];

            //mailgun cancell appointment Mail to doctor and patient
            // Mail::to('baqir.redecom@gmail.com')->send(new CancelAppointmentPatientMail($markDownData));
            // Mail::to('baqir.redecom@gmail.com')->send(new CancelAppointmentDocotrMail($markDownData));
            // Mail::to('baqir.redecom@gmail.com')->send(new CancelAppointmentAccountantMail($accountsMarkDownData));

            Mail::to($patient_data->email)->send(new CancelAppointmentPatientMail($p_markDownData));
            Mail::to($doctor_data->email)->send(new CancelAppointmentDoctorMail($d_markDownData));
            Mail::to($adminMail->email)->send(new CancelAppointmentAccountantMail($accountsMarkDownData));

            $text = "Appointment from Dr. " . $doctor_data->name . " " . $doctor_data->last_name . " has been Cancelled by " . $user_type;
            $notification_id = Notification::create([
                'user_id' =>  $getAppointment->patient_id,
                'type' => '/patient/appointments',
                'text' => $text,
                'appoint_id' => $request->id,
            ]);
            $data = [
                'user_id' =>  $getAppointment->patient_id,
                'type' => '/patient/appointments',
                'text' => $text,
                'appoint_id' => $request->id,
                'refill_id' => "null",
                'received' => 'false',
                'session_id' => 'null',
            ];
            // \App\Helper::firebase($getAppointment->patient_id,'notification',$notification_id->id,$data);
            event(new RealTimeMessage($getAppointment->patient_id));
        } catch (Exception $e) {
            Log::error($e);
        }

        return redirect()->route('doc_appointments')->with('success', 'Appointment cancelled.');
    }

    public function view($id)
    {
        $user = Auth::user();
        $today = date('Y-m-d');
        if ($user->user_type == 'admin') {
            $appointments = Appointment::orderBy('created_at', 'DESC')->get();
            return view('appointments.view_appointment', compact('appointments', 'user'));
        } else if ($user->user_type == 'doctor') {
            $user_type = $user->user_type;
            $user_id = auth()->user()->id;
            $sessions = Session::where('appointment_id', $id)->first();
            $sessions->date = Helper::get_date_with_format($sessions->date);
            if ($sessions->start_time == null) {
                $sessions->start_time = Helper::get_time_with_format($sessions->created_at);
            } else {
                $sessions->start_time = Helper::get_time_with_format($sessions->start_time);
            }
            if ($sessions->end_time == null) {
                $sessions->end_time = Helper::get_time_with_format($sessions->updated_at);
            } else {
                $sessions->end_time = Helper::get_time_with_format($sessions->end_time);
            }
            $pat = User::where('id', $sessions->patient_id)->first();
            $sessions->pat_name = $pat['name'] . " " . $pat['last_name'];
            $links = VideoLinks::where('session_id', $sessions->id)->orderByDesc('id')->first();
            $pres = Prescription::where('session_id', $sessions->id)->get();
            $pres_arr = [];
            foreach ($pres as $prod) {
                $product = AllProducts::where('id', $prod['medicine_id'])->first();
                $cart = Cart::where('doc_session_id', $sessions['id'])->where('pres_id', $prod->id)->first();
                $prod->prod_detail = $product;
                if (isset($cart->status)) {
                    $prod->cart_status = $cart->status;
                } else {
                    $prod->cart_status = 'No record';
                }
                array_push($pres_arr, $prod);
            }
            $sessions->pres = $pres_arr;
            // dd($sessions);
            return view('appointments.view_appointment', compact('user_type', 'sessions'));
        } else if ($user->user_type == 'patient') {

            $user_type = $user->user_type;
            $user_id = auth()->user()->id;
            $sessions = Session::where('appointment_id', $id)->first();
            $sessions->date = Helper::get_date_with_format($sessions->date);
            if ($sessions->start_time == null) {
                $sessions->start_time = Helper::get_time_with_format($sessions->created_at);
            } else {
                $sessions->start_time = Helper::get_time_with_format($sessions->start_time);
            }
            if ($sessions->end_time == null) {
                $sessions->end_time = Helper::get_time_with_format($sessions->updated_at);
            } else {
                $sessions->end_time = Helper::get_time_with_format($sessions->end_time);
            }
            $doc = User::where('id', $sessions->doctor_id)->first();
            $sessions->doc_name = $doc['name'] . " " . $doc['last_name'];
            $links = VideoLinks::where('session_id', $sessions->id)->orderByDesc('id')->first();
            $pres = Prescription::where('session_id', $sessions->id)->get();
            $pres_arr = [];
            foreach ($pres as $prod) {
                $product = AllProducts::where('id', $prod['medicine_id'])->first();
                $cart = Cart::where('doc_session_id', $sessions['id'])->where('pres_id', $prod->id)->first();
                $prod->prod_detail = $product;
                if (isset($cart->status)) {
                    $prod->cart_status = $cart->status;
                } else {
                    $prod->cart_status = 'No record';
                }
                array_push($pres_arr, $prod);
            }
            $sessions->pres = $pres_arr;
            return view('appointments.view_appointment', compact('user_type', 'sessions'));
        } else {
            return view('welcome');
        }
    }

    public function loadAllAppointment()
    {
        $user = Auth::user();
        $appointments = DB::table('appointments')
            ->join('sessions', 'appointments.id', 'sessions.appointment_id')
            ->where('appointments.patient_id', $user->id)
            ->orderBy('appointments.created_at', 'DESC')
            ->select('appointments.*', 'sessions.id as sesssion_id', 'sessions.que_message as msg', 'sessions.join_enable')
            ->paginate(12);

        $reschedule_appointment = DB::table('appointments')
            ->join('sessions', 'sessions.appointment_id', 'appointments.id')
            ->where('appointments.patient_id', $user->id)
            ->where('appointments.status', 'make-reschedule')
            ->select('sessions.specialization_id as spec_id', 'appointments.*')
            ->get();




        return view('appointments.loadAllAppointment', compact('appointments', 'user', 'reschedule_appointment'));
    }
    public function load_all_appointments_home_page()
    {
        $user = Auth::user();
        $appointments = DB::table('appointments')
            ->join('sessions', 'appointments.id', 'sessions.appointment_id')
            ->where('appointments.patient_id', $user->id)
            ->orderBy('appointments.created_at', 'DESC')
            ->select('appointments.*', 'sessions.id as sesssion_id', 'sessions.que_message as msg', 'sessions.join_enable')
            ->paginate(12);

        $reschedule_appointment = DB::table('appointments')
            ->join('sessions', 'sessions.appointment_id', 'appointments.id')
            ->where('appointments.patient_id', $user->id)
            ->where('appointments.status', 'make-reschedule')
            ->select('sessions.specialization_id as spec_id', 'appointments.*')
            ->get();
        return view('appointments.homePageAppointmentsList', compact('appointments', 'reschedule_appointment'));
    }



    public function create($spec, $doc_id)
    {
        $user = Auth::user();
        $doctors = DB::table('users')
            ->join('specializations', 'specializations.id', 'users.specialization')
            ->where('users.id', $doc_id)
            ->select('specializations.name as spec', 'users.*')
            ->get();
        $today = date("Y-m-d", strtotime(Carbon::today()));
        // $today[4]='-';$today[7]='-';
        // dd($today);
        $Availabale_dates = DoctorSchedule::where('doctorID', $doc_id)->where('title', 'Availability')->where('date', '>=', $today)->orderBy('date', 'asc')->groupBy('date')->get();
        return view('appointments.book_appointment', compact('Availabale_dates', 'doctors', 'user', 'spec'));
    }

    public function reschedule_appoint($app_id, $spec_id, $doc_id)
    {
        $user = Auth::user();
        $appointment = DB::table('appointments')->where('id', $app_id)->first();

        $specialization = DB::table('specializations')->where('id', $spec_id)->first();
        $doctors = DB::table('users')->where('id', $doc_id)->get();
        return view('appointments.reschedule_appointment', compact('doctors', 'user', 'specialization', 'appointment'));
    }

    public function doctorForAppointment($id)
    {

        $date = (Carbon::now()->format('Y-m-d'));
        // dd($date);
        $user = Auth::user();
        $doctors = DB::table('users')
            ->join('doctor_schedules', 'doctor_schedules.doctorID', 'users.id')
            ->join('specializations', 'specializations.id', 'users.specialization')
            ->join('states', 'states.id', 'users.state_id')
            ->where('doctor_schedules.date', '>=', $date)
            ->where('users.specialization', $id)
            ->where('users.user_type', 'doctor')
            ->where('users.active', '1')
            ->select('users.*', 'specializations.name as sp_name')
            ->groupBy('users.id')
            ->get();

        $already_session_did = DB::table('sessions')
            ->where('patient_id', $user->id)
            ->where('specialization_id', $id)
            ->groupBy('doctor_id')
            ->get();


        $refered_doctors = DB::table('referals')
            ->where('patient_id', $user->id)
            ->where('sp_doctor_id', $id)
            ->where('status', 'accepted')
            ->groupBy('sp_doctor_id')
            ->get();
        foreach ($already_session_did as $a_s_d) {

            $doc = DB::table('users')
                ->join('specializations', 'specializations.id', 'users.specialization')
                ->where('users.id', $a_s_d->doctor_id)
                ->select('users.*', 'specializations.name as sp_name')
                ->first();
            $a_s_d->user_image = \App\Helper::check_bucket_files_url($doc->user_image);

            $a_s_d->name = $doc->name;
            $a_s_d->last_name = $doc->last_name;
            $a_s_d->sp_name = $doc->sp_name;
            $a_s_d->specialization = $doc->specialization;
            $a_s_d->id = $doc->id;
        }
        foreach ($refered_doctors as $r_d) {

            $doc = DB::table('users')
                ->join('specializations', 'specializations.id', 'users.specialization')
                ->where('users.id', $r_d->doctor_id)
                ->select('users.*', 'specializations.name as sp_name')
                ->first();


            $r_d->user_image = \App\Helper::check_bucket_files_url($doc->user_image);
            $r_d->name = $doc->name;
            $r_d->last_name = $doc->last_name;
            $r_d->sp_name = $doc->sp_name;
            $r_d->specialization = $doc->specialization;
            $r_d->id = $doc->id;
        }
        foreach ($doctors as $doctor) {
            $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);
        }

        return view('appointments.choose_doctor_for_appointment', compact('doctors', 'refered_doctors', 'already_session_did'));
    }
    public function appointment_specialization()
    {
        $user_state = Auth::user()->state_id;
        $state = State::find($user_state);
        if ($state->active == 1) {
            $spe = DB::table('specializations')
                ->join('specalization_price', 'specalization_price.spec_id', 'specializations.id')
                ->where('specalization_price.state_id', $user_state)
                ->where('specializations.status', '1')
                ->select('specializations.*', 'specalization_price.follow_up_price as follow_up_price', 'specalization_price.initial_price as initial_price')
                ->get();
            return view('patient.appointment_specialization', compact('spe'));
        } else {
            return redirect()->route('errors', '101');
        }
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'fname' =>  ['required', 'string', 'max:255'],
            'lname' =>  ['required', 'string', 'max:255'],
            'email' =>  ['required', 'string', 'max:255'],
            'phone' =>  ['required', 'string', 'max:255'],
            'provider' =>  ['required', 'string', 'max:255'],
            'problem' => ['required', 'string', 'max:255'],
            'date' =>  ['required', 'max:255'],
            'time' =>  ['required', 'max:255']
        ]);
        $p_s_Time = $data['time'];
        $p_s_Time = date("H:i:s", strtotime($p_s_Time));
        $full_date = $request->date;
        $full_date = date("Y-m-d", strtotime($full_date));
        $date = $request->date;
        $date = date("Y-m-d", strtotime($date));
        $date1 = new Carbon($date);
        $app_date = $request->date;
        $app_date = date("Y-m-d", strtotime($app_date));


        $p_s_Time = User::convert_user_timezone_to_utc($user->id, $p_s_Time)['time'];
        // dd($p_s_Time,$full_date,$date,$date1,$app_date);


        $patient_id = Auth::user()->id;
        $pro_id = $data['provider'];
        $pro_name_data = DB::table('users')->select('name', 'last_name', 'email', 'phone_number')->where('id', $pro_id)->get();
        $provider_name = $pro_name_data[0]->name . " " . $pro_name_data[0]->last_name;
        $appoint_data_time = $app_date . ' ' . $p_s_Time;


        // dd($appoint_data_time);

        $firstReminder = date('Y-m-d H:i:s', (strtotime('-1 day', strtotime($appoint_data_time))));
        $timestamp = strtotime($appoint_data_time);
        $time = $timestamp - (15 * 60);

        $secondReminder = date("Y-m-d H:i:s", $time);
        // $appoint = Appointment::where('doctor_id',$data['provider'])->where('date',$date)->where('time',$p_s_Time)->get();
        // if($appoint!=[[]])
        // {
        //     return response()->json([$appoint]);
        // }
        $app_id = Appointment::create([
            'doctor_id' =>  $data['provider'],
            'patient_id' =>  $patient_id,
            'patient_name' =>  $data['fname'] . " " . $data['lname'],
            'doctor_name' => $provider_name,
            'email' => $data['email'],
            'phone' => $data['phone'],
            'problem' => $data['problem'],
            'date' => $app_date,
            'day' => $date1->format('l'),
            'status' => 'pending',
            'time' => $p_s_Time,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'reminder_one_status' => "pending",
            'reminder_two_status' => "pending",
            'reminder_one' => $firstReminder,
            'reminder_two' => $secondReminder,
        ])->id;
        $semtem = explode(" ", $data['problem']);
        $headache = 0;
        $flu = 0;
        $fever = 0;
        $nausea = 0;
        $others = 0;
        if (in_array("Headache", $semtem)) {
            $headache = 1;
        }
        if (in_array("Flu", $semtem)) {
            $flu = 1;
        }
        if (in_array("Fever", $semtem)) {
            $fever = 1;
        }
        if (in_array("Nausea", $semtem)) {
            $nausea = 1;
        }
        if (in_array("Others", $semtem)) {
            $others = 1;
        }
        $symp = Symptom::create([
            'patient_id' => $patient_id,
            'doctor_id' => $pro_id,
            'headache' => $headache,
            'flu' => $flu,
            'fever' => $fever,
            'nausea' => $nausea,
            'others' => $others,
            'description' => 'nothing',
            'status' => 'pending'
        ]);
        $symp->save();


        $check_session_already_have = DB::table('sessions')
            ->where('doctor_id', $pro_id)
            ->where('patient_id', $patient_id)
            ->where('specialization_id', $request->spec_id)
            ->count();
        $session_price = "";
        if ($check_session_already_have > 0) {
            $session_price_get = DB::table('specalization_price')->where('spec_id', $request->spec_id)->first();
            if ($session_price_get->follow_up_price != null) {
                $session_price = $session_price_get->follow_up_price;
            } else {
                $session_price = $session_price_get->initial_price;
            }
        } else {
            $session_price_get = DB::table('specalization_price')->where('spec_id', $request->spec_id)->first();
            $session_price = $session_price_get->initial_price;
        }



        $permitted_chars = 'abcdefghijklmnopqrstuvwxyz';
        $channelName = substr(str_shuffle($permitted_chars), 0, 8);
        $sessiondate = Carbon::now();
        $session_id = Session::create([
            'patient_id' =>  $patient_id,
            'appointment_id' =>  $app_id,
            'doctor_id' =>  $pro_id,
            'date' => $sessiondate,
            'status' => 'pending',
            'queue' => 0,
            'symptom_id' => $symp->id,
            'remaining_time' => 'full',
            'channel' => $channelName,
            'created_at' => $sessiondate,
            'updated_at' => $sessiondate,
            'specialization_id' => $request->spec_id,
            'price' => $session_price,
        ])->id;
        return redirect()->route('payment.appointment', ['id' => $session_id]);
    }

    public function appointment_payment_page($session_id)
    {
        $years = [];
        $current_year = Carbon::now()->format('Y');
        array_push($years, $current_year);
        $j = 15;
        for ($i = 0; $i < $j; $i++) {
            $get_year = $current_year += 1;
            array_push($years, $get_year);
        }
        $states = State::where('country_code', 'US')->get();
        $session_data = DB::table('sessions')->where('id', $session_id)->first();

        return view('appointments.appointment-payment', compact('session_id', 'states', 'years', 'session_data'));
    }

    public function appointment_payment(Request $request)
    {
        $this->validate($request, [
            'card_holder_name' => 'required',
            'card_num' => 'required|integer|min:16',
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
            'info' => [
                'subject' => $request->subject,
                'user_id' => $getSession->patient_id,
                'description' => $request->session_id,
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
        $request_data = new Request($input);
        $pay = new PaymentController();
        $responce = json_decode($pay->proceedToPay($request_data));


        if ($responce->success == 'true') {
            Session::where('id', $request->session_id)->update(['status' => 'paid']);
            $firebase_ses = DB::table('sessions')->where('id', $request->session_id)->first();
            $firebase_ses->received = false;

            try {
                $doctor_data = DB::table('users')->where('id', $getSession->doctor_id)->first();
                $patient_data = DB::table('users')->where('id', $getSession->patient_id)->first();
                $getAppointment = DB::table('appointments')->where('id', $getSession->appointment_id)->first();
                $time = date('h:i a', strtotime($getAppointment->time));
                $date = date('M-d-Y', strtotime($getAppointment->date));
                $markDownData = [
                    'doc_name' => ucwords($doctor_data->name),
                    'pat_name' => ucwords($patient_data->name),
                    'time' => $time,
                    'date' => $date,
                    'pat_mail' => $patient_data->email,
                    'doc_mail' => $doctor_data->email,
                ];
                // mailgun send new appoointment mail to doctor and patient
                // Mail::to('baqir.redecom@gmail.com')->send(new NewAppointmentPatientMail($markDownData));
                // Mail::to('baqir.redecom@gmail.com')->send(new NewAppointmentDoctorMail($markDownData));
                Mail::to($patient_data->email)->send(new NewAppointmentPatientMail($markDownData));
                Mail::to($doctor_data->email)->send(new NewAppointmentDoctorMail($markDownData));

                $text = "New Appointment Created by " . $patient_data->name . " " . $patient_data->last_name;
                $notification_id = Notification::create([
                    'user_id' =>  $getSession->doctor_id,
                    'type' => '/doctor/appointments',
                    'text' => $text,
                    'appoint_id' => $getSession->appointment_id,
                ]);
                $data = [
                    'user_id' =>  $getSession->doctor_id,
                    'type' => '/doctor/appointments',
                    'text' => $text,
                    'appoint_id' => $getSession->appointment_id,
                    'refill_id' => "null",
                    'received' => 'false',
                    'session_id' => 'null',
                ];
                // \App\Helper::firebase($getSession->doctor_id,'notification',$notification_id->id,$data);
                event(new RealTimeMessage($getSession->doctor_id));

                // \App\Helper::firebase($firebase_ses->doctor_id,'updateQuePatient',$firebase_ses->id,$firebase_ses);
                event(new updateQuePatient('update patient que'));
            } catch (Exception $e) {
                Log::error($e);
            }
            return redirect()->route('appointment.index')->with("message", "Appointment Create Successfully");
        } else {
            foreach ($responce->errors as $error) {
                $erroras = $error;
            }
            return redirect()->route('payment.appointment', ['id' => $request->session_id])->with('error_message', $erroras);
        }
    }

    public function show(Appointment $appointment)
    {
        //
    }
    public function edit(Appointment $appointment)
    {
        //
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'fname' =>  ['required', 'string', 'max:255'],
            'lname' =>  ['required', 'string', 'max:255'],
            'email' =>  ['required', 'string', 'max:255'],
            'phone' =>  ['required', 'string', 'max:255'],
            'provider' =>  ['required', 'string', 'max:255'],
            'problem' => ['required', 'string', 'max:255'],
            'date' =>  ['required', 'string', 'max:255'],
            'time' =>  ['required', 'string', 'max:255'],
        ]);

        $p_s_Time = $data['time'];
        $p_s_Time = date("H:i:s", strtotime($p_s_Time));
        $full_date = $request['date'];
        $date_split = explode(" ", $full_date);
        $day = $date_split[0];
        $date = $date_split[1];
        $month1 = $date_split[2];
        $year = $date_split[3];
        $month = $this->getmonth($month1);
        $app_date = $year . '-' . $month . '-' . $date;
        $patient_id = Auth::user()->id;
        $pro_id = $data['provider'];
        $pro_name_data = DB::table('users')->select('name', 'last_name', 'email', 'phone_number')->where('id', $pro_id)->get();

        $provider_name = $pro_name_data[0]->name . " " . $pro_name_data[0]->last_name;
        $appoint_data_time = $app_date . ' ' . $p_s_Time;
        $firstReminder = date('Y-m-d H:i:s', (strtotime('-1 day', strtotime($appoint_data_time))));
        $timestamp = strtotime($appoint_data_time);
        $time = $timestamp - (15 * 60);
        $secondReminder = date("Y-m-d H:i:s", $time);

        $appoint = DB::table('appointments')->where('id', $request->app_id)->update([
            'doctor_id' => $data['provider'],
            'patient_id' => $patient_id,
            'patient_name' => $data['fname'] . " " . $data['lname'],
            'doctor_name' => $provider_name,
            'email' => $data['email'],
            'phone' => $data['phone'],
            'problem' => $data['problem'],
            'date' => $app_date,
            'day' => $day,
            'time' => $p_s_Time,
            'status' => 'pending',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'reminder_one' => $firstReminder,
            'reminder_two' => $secondReminder,
            'reminder_two_status' => 'pending',
            'reminder_one_status' => 'pending'
        ]);

        $semtem = explode(",", $data['problem']);
        $headache = 0;
        $flu = 0;
        $fever = 0;
        $nausea = 0;
        $others = 0;
        if (in_array("Headache", $semtem)) {
            $headache = 1;
        }
        if (in_array("Flu", $semtem)) {
            $flu = 1;
        }
        if (in_array("Fever", $semtem)) {
            $fever = 1;
        }
        if (in_array("Nausea", $semtem)) {
            $nausea = 1;
        }
        if (in_array("Others", $semtem)) {
            $others = 1;
        }
        $sym = DB::table('symptoms')->where('patient_id', $patient_id)->where('doctor_id', $pro_id)->update(
                [
                    'patient_id' => $patient_id,
                    'doctor_id' => $pro_id,
                    'headache' => $headache,
                    'flu' => $flu,
                    'fever' => $fever,
                    'others' => $others,
                    'description' => 'description',
                    'status' => 'pending',
                ]
            );
        $sessiondate = Carbon::now();
        $ses = DB::table('sessions')->where('appointment_id', $request->app_id)->update(
            [
                'patient_id' => $patient_id,
                'appointment_id' => $request->app_id,
                'doctor_id' => $pro_id,
                'date' => $sessiondate,
                'created_at' => $sessiondate,
                'updated_at' => $sessiondate,
                'status' => 'paid',
                'join_enable' => '0',
                'specialization_id' => $request->spec_id,
            ]
        );

        try {
            $doctor_data = DB::table('users')->where('id', $pro_id)->first();
            $patient_data = DB::table('users')->where('id', $patient_id)->first();

            $getAppointment = DB::table('appointments')->where('id', $request->app_id)->first();
            $time = date('h:i a', strtotime($getAppointment->time));
            $date = date('M-d-Y', strtotime($getAppointment->date));
            $markDownData = [
                'doc_name' => ucwords($doctor_data->name),
                'pat_name' => ucwords($patient_data->name),
                'time' => $time,
                'date' => $date,
                'pat_mail' => $patient_data->email,
                'doc_mail' => $doctor_data->email,
            ];
            //mailgun send Reschedule appoointment mail to doctor and patient
            // Mail::to('baqir.redecom@gmail.com')->send(new RescheduleAppointmentPatientMail($markDownData));
            // Mail::to('baqir.redecom@gmail.com')->send(new RescheduleAppointmentDocotrMail($markDownData));
            Mail::to($patient_data->email)->send(new RescheduleAppointmentPatientMail($markDownData));
            Mail::to($doctor_data->email)->send(new RescheduleAppointmentDocotrMail($markDownData));

            $text = "Appointment reschedule by " . $patient_data->name . " " . $patient_data->last_name;
            $notification_id = Notification::create([
                'user_id' =>  $pro_id,
                'type' => '/doctor/appointments',
                'text' => $text,
                'appoint_id' => $request->app_id,
            ]);
            $data = [
                'user_id' =>  $pro_id,
                'type' => '/doctor/appointments',
                'text' => $text,
                'appoint_id' => $request->app_id,
                'refill_id' => "null",
                'received' => 'false',
                'session_id' => 'null',
            ];
            // \App\Helper::firebase($pro_id,'notification',$notification_id->id,$data);
            event(new RealTimeMessage($pro_id));

            $firebase_ses = DB::table('sessions')->where('appointment_id', $request->app_id)->first();
            $firebase_ses->received = false;
            // \App\Helper::firebase($firebase_ses->doctor_id,'updateQuePatient',$firebase_ses->id,$firebase_ses);
            event(new updateQuePatient('update patient que'));
        } catch (Exception $e) {
            Log::error($e);
        }




        return redirect()->route('home')->with("message", "Your Appointment Reschedule Successfully");
    }
    public function destroy(Appointment $appointment)
    {
        //
    }
    public function cancel(Request $request)
    {
        //dd($request);
        $user_type = auth()->user()->user_type;
        $app = Appointment::find($request->id);
        if ($app) {
            $app->status = $user_type . ' has cancelled the appointment';
            $app->save();
        }

        // notifications comment
        try {

            $getAppointment = DB::table('appointments')->where('id', $request->id)->first();
            $doctor_data = DB::table('users')->where('id', $getAppointment->doctor_id)->first();
            $patient_data = DB::table('users')->where('id', $getAppointment->patient_id)->first();
            $adminMail = DB::table('users')->where('user_type', 'admin')->first();

            $time = date('h:i a', strtotime($getAppointment->time));
            $date = date('M-d-Y', strtotime($getAppointment->date));
            $markDownData = [
                'doc_name' => ucwords($doctor_data->name),
                'pat_name' => ucwords($patient_data->name),
                'time' => $time,
                'date' => $date,
                'pat_mail' => $patient_data->email,
                'doc_mail' => $doctor_data->email,
            ];
            $accountsMarkDownData = [
                'doc_name' => ucwords($doctor_data->name),
                'pat_name' => ucwords($patient_data->name),
                'time' => $time,
                'date' => $date,
                'acounts_name' => ucwords($adminMail->name),
                'acounts_mail' => $adminMail->email,
            ];

            //mailgun cancell appointment Mail to doctor and patient
            // Mail::to('baqir.redecom@gmail.com')->send(new CancelAppointmentPatientMail($markDownData));
            // Mail::to('baqir.redecom@gmail.com')->send(new CancelAppointmentDocotrMail($markDownData));
            // Mail::to('baqir.redecom@gmail.com')->send(new CancelAppointmentAccountantMail($accountsMarkDownData));

            Mail::to($patient_data->email)->send(new CancelAppointmentPatientMail($markDownData));
            Mail::to($doctor_data->email)->send(new CancelAppointmentDoctorMail($markDownData));
            Mail::to($adminMail->email)->send(new CancelAppointmentAccountantMail($accountsMarkDownData));

            $text = "Appointment with " . $patient_data->name . " " . $patient_data->last_name . " has been Cancelled by " . $user_type;
            Notification::create([
                'user_id' =>  $getAppointment->doctor_id,
                'type' => '/doctor/appointments',
                'text' => $text,
                'appoint_id' => $request->id,
            ]);
            $text = "Appointment from Dr. " . $doctor_data->name . " " . $doctor_data->last_name . " has been Cancelled by " . $user_type;
            $notification_id = Notification::create([
                'user_id' =>  $getAppointment->patient_id,
                'type' => '/patient/appointments',
                'text' => $text,
                'appoint_id' => $request->id,
            ]);
            $data = [
                'user_id' =>  $getAppointment->patient_id,
                'type' => '/patient/appointments',
                'text' => $text,
                'appoint_id' => $request->id,
                'refill_id' => "null",
                'received' => 'false',
                'session_id' => 'null',
            ];
            // \App\Helper::firebase($getAppointment->patient_id,'notification',$notification_id->id,$data);
            event(new RealTimeMessage($getAppointment->patient_id));
        } catch (Exception $e) {
            Log::error($e);
        }

        return redirect()->route('appointment.index')->with('success', 'Appointment cancelled.');
    }
    public function booked(Request $request)
    {
        $doc = $request['id'];
        $full = $request['full_date'];
        $app = DB::table('appointments')->where('doctor_id', $doc)->where('date', $full)->where('status', 'pending')->get();
        return $app;
    }
    public function bookedappoint(Request $request)
    {
        $doc = $request['id'];
        $app = DB::table('appointments')->where('doctor_id', $doc)->where('status', 'pending')->get();
        return $app;
    }
    public function getappointments(Request $request)
    {
        $date = $request['date'];
        $id = Auth::user()->id;
        $app = DB::table('appointments')->where('doctor_id', $id)->where('date', $date)->get();

        return $app;
    }
    public function getmonth($m)
    {
        $month = "";
        if ($m == 'Jan' || $m == 'January')
            $month = '01';
        else if ($m == 'Feb' || $m == 'February')
            $month = '02';
        else if ($m == 'Mar' || $m == 'March')
            $month = '03';
        else if ($m == 'Apr' || $m == 'April')
            $month = '04';
        else if ($m == 'May' || $m == 'May')
            $month = '05';
        else if ($m == 'Jun' || $m == 'June')
            $month = '06';
        else if ($m == 'Jul' || $m == 'July')
            $month = '07';
        else if ($m == 'Aug' || $m == 'August')
            $month = '08';
        else if ($m == 'Sep' || $m == 'September')
            $month = '09';
        else if ($m == 'Oct' || $m == 'October')
            $month = '10';
        else if ($m == 'Nov' || $m == 'November')
            $month = '11';
        else if ($m == 'Dec' || $m == 'December')
            $month = '12';

        return $month;
    }
    public function getanotherdoctor(Request $request)
    {
        $app_id = $request['app_id'];
        $app = Appointment::where('id', $app_id)->first();
        $app_time = $app['time'];
        $doc_id = '3';
        $all_app = Appointment::where('doctor_id', $doc_id)->where('time', '!=', $app_time)->get();
        if (empty($all_doc)) {
            return 'wth';
        } else {
            return $all_doc;
        }
    }

    public function appointment_time_check(Request $request)
    {
        $appointment = DB::table('appointments')
            ->where('id', $request->appointment_id)
            ->first();
        // dd($appointment);

        $timestamp = date("Y-m-d H:i:s");

        $current_date_and_time = Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, auth()->user()->timeZone)->setTimezone('UTC');

        $app_date_time = $appointment->date . " " . $appointment->time;


        $appoint_date_and_time = Carbon::createFromFormat('Y-m-d H:i:s', $app_date_time, auth()->user()->timeZone)->setTimezone('UTC');


        $startTime = Carbon::parse($current_date_and_time);
        $endTime = Carbon::parse($appoint_date_and_time);
        $totalDuration = $endTime->diffForHumans($startTime);
        $result_split = explode(" ", $totalDuration);
        return response()->json(['timeNow' => $result_split]);
    }
}
