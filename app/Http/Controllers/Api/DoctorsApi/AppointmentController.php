<?php

namespace App\Http\Controllers\Api\DoctorsApi;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController;
use App\Mail\CancelAppointmentAccountantMail;
use App\Mail\CancelAppointmentDoctorMail;
use App\Mail\CancelAppointmentPatientMail;
use App\Events\RealTimeMessage;
use Illuminate\Http\Request;
use Auth;
use DB;
use App\State;
use App\User;
use App\Notification;
use App\Appointment;
use App\DoctorSchedule;
use Mail;
use Carbon\Carbon;

class AppointmentController extends BaseController
{
    public function doctor_appointments(){
        $user = Auth::user();
        $today = date('Y-m-d');
        $todayTime = date('h:i A');
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
                ->join('users','users.id','appointments.patient_id')
                ->where('appointments.doctor_id', $user->id)
                ->where('sessions.status','!=','pending')
                ->orderBy('appointments.created_at', 'DESC')
                ->select('appointments.*','users.user_image','sessions.id as session_id', 'sessions.que_message as msg', 'sessions.join_enable')
                ->paginate(8);

            $appointments_count =[];
            foreach ($appointments as $app) {
                $ddd = date('Y-m-d H:i:s', strtotime("$app->date $app->time"));
                // $ddd=date("Y-m-d H:i:s",strtotime($app->date $app->time));
                $app->date = User::convert_utc_to_user_timezone($user->id, $ddd)['date'];
                $app->time = User::convert_utc_to_user_timezone($user->id, $ddd)['time'];
            }
            $dataDoctorAppointment['code'] = 200;
            $dataDoctorAppointment['appointments'] = $appointments;
            $dataDoctorAppointment['user'] = $user;
            return $this->sendResponse($dataDoctorAppointment,"Doctor Appointment");
        } else {
            $dataDoctorAppointmentCode['code'] = 200;
            return $this->sendError($dataDoctorAppointmentCode,"Somthing Went Wrong!");
        }
    }
    public function view_appointment(){
        $user=Auth::user();
        if($user->user_type=='doctor'){
            $date = date('Y-m-d H:i:s');
            $date = User::convert_utc_to_user_timezone($user->id,$date)['datetime'];
            $time = date('H:i:s',strtotime($date));
            $date = date('Y-m-d',strtotime($date));
            $schedule = DoctorSchedule::where('doctorID', $user->id)->where('date','>=',date('Y-m-d'))->where('title','Availability')->orderBy('id','desc')->get();
            foreach($schedule as $sc){
                //$sc->appointments = Appointment::where('doctor_id',$user->id)->where('date',$sc->date)->where('time','>=',$sc->slotStartTime)->where('time','<',$sc->slotEndTime)->where('status','pending')->get();
                $sc->appointments = DB::table('appointments')
                ->join('sessions','appointments.id','sessions.appointment_id')
                ->where('appointments.doctor_id',$user->id)
                ->where('appointments.date',$sc->date)
                ->where('appointments.status','pending')
                ->where('sessions.status','paid')
                ->orderBy('appointments.time')
                ->select('appointments.*')->get();
                $sc->slotStartTime = date('H:i:s',strtotime(User::convert_utc_to_user_timezone($user->id,$sc->start)['time']));
                $sc->slotEndTime = date('H:i:s',strtotime(User::convert_utc_to_user_timezone($user->id,$sc->end)['time']));
                foreach($sc->appointments as $key=>$app){
                    $ddd = date('Y-m-d H:i:s', strtotime("$app->date $app->time"));
                    $datetime = User::convert_utc_to_user_timezone($user->id,$ddd);
                    if(date('H:i:s',strtotime($datetime['time']))<$sc->slotStartTime || date('H:i:s',strtotime($datetime['time']))>$sc->slotEndTime){
                        unset($sc->appointments[$key]);
                    }
                }
                $sc->date = User::convert_utc_to_user_timezone($user->id,$sc->start)['date'];
                $sc->start = User::convert_utc_to_user_timezone($user->id,$sc->start)['time'];
                $sc->end = User::convert_utc_to_user_timezone($user->id,$sc->end)['time'];
                $sc->time = $time;
                $sc->cdate = date('m-d-Y',strtotime($date));
            }
            $appointment_view['code'] = 200;
            $appointment_view['schedule'] = $schedule;
            $appointment_view['appointment_count'] = $sc->appointments->count();
            return $this->sendResponse($appointment_view,'appointment view');
        }
    }
    public function doc_appointment_cancel($id){
        $user_type = auth()->user()->user_type;
        $app = Appointment::find($id);
        if ($app) {
            $app->status = $user_type . ' has cancelled the appointment';
            $app->save();
        }
        try {
            $getAppointment = DB::table('appointments')->where('id', $id)->first();
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
            Mail::to($patient_data->email)->send(new CancelAppointmentPatientMail($p_markDownData));
            Mail::to($doctor_data->email)->send(new CancelAppointmentDoctorMail($d_markDownData));
            Mail::to($adminMail->email)->send(new CancelAppointmentAccountantMail($accountsMarkDownData));
            $text = "Appointment from Dr. " . $doctor_data->name . " " . $doctor_data->last_name . " has been Cancelled by " . $user_type;
            $noti = Notification::create([
                'user_id' =>  $getAppointment->patient_id,
                'type' => '/patient/appointments',
                'text' => $text,
                'appoint_id' => $id,
            ]);
            $data = [
                'user_id' =>  $getAppointment->patient_id,
                'type' => '/patient/appointments',
                'text' => $text,
                'appoint_id' => $id,
                'session_id' => "null",
                'received' => 'false',
                'refill_id' => 'null',
            ];
            // \App\Helper::firebase($getAppointment->patient_id,'notification',$noti->id,$data);
            event(new RealTimeMessage($getAppointment->patient_id));
        } catch (Exception $e) {
            Log::error($e);
        }
        $doc_appointment_cancel['code'] =200;
        $doc_appointment_cancel['Appointment_id'] =$getAppointment->id;
        return $this->sendResponse($doc_appointment_cancel,'Appointment Cancelled Successfully');
    }
    public function appointment_detail($id) {
        $user = Auth::user();
        $session = Session::find($id);
        $session->date = User::convert_utc_to_user_timezone($user->id, $session->created_at)['date'];
        if ($session->start_time == null) {
            $session->start_time = Helper::get_time_with_format($session->created_at);
        } else {
            $session->start_time = date('h:i A', strtotime('-15 minutes', strtotime($session->start_time)));
            $session->start_time = User::convert_utc_to_user_timezone($user->id, $session->start_time)['time'];
        }
        if ($session->end_time == null) {
            $session->end_time = Helper::get_time_with_format($session->updated_at);
        } else {
            $session->end_time = date('h:i A', strtotime('-15 minutes', strtotime($session->end_time)));
            $session->end_time = User::convert_utc_to_user_timezone($user->id, $session->end_time)['time'];
        }
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
        foreach ($pres as $prod) {
            if ($prod['medicine_id'] != 0) {
                $product = AllProducts::find($prod['medicine_id']);
            } elseif($prod['imaging_id'] != 0){
                $product = AllProducts::find($prod['imaging_id']);
            } else {
                $product = QuestDataTestCode::where('TEST_CD', $prod['test_id'])->first();
            }
            if ($product->mode == 'lab-test') {
                $product->id = $product->TEST_CD;
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
            array_push($pres_arr, $prod);
        }
        $session->pres = $pres_arr;
        $user_type = auth()->user()->user_type;
        $appointment_detial['code'] = 200;
        $appointment_detial['appointment_detial'] = $session;
        return $this->sendResponse($appointment_detial,'appointment details');
    }
}
