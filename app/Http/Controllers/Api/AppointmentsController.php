<?php

namespace App\Http\Controllers\Api;

use App\Appointment;
use App\DoctorSchedule;
use App\Http\Controllers\Controller;
use App\Session;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AppointmentsController extends BaseController
{
    public function book_appointment(Request $req, $id)
    {
        $user = Auth::user();
        $user->ses_id = '';
        if ($req->name != null) {
            $doctors = DB::table('users')
                ->join('specializations', 'specializations.id', 'users.specialization')
                ->leftJoin('doctor_schedules', function ($join) {
                    $join->on('doctor_schedules.doctorID', '=', 'users.id');
                    $join->where('doctor_schedules.title', '=', 'Availability');
                    $join->where('doctor_schedules.end', '>', date('Y-m-d H:i:s'));
                })
                ->where('users.specialization', $req->spec_id)
                ->where('users.active', '1')
                ->where(DB::raw('CONCAT(users.name, " ",users.last_name)'), 'LIKE', '%' . $req->name . '%')
                ->select('users.*', 'specializations.name as sp_name', 'doctor_schedules.title')
                ->groupBy('users.id')
                ->orderby('doctor_schedules.doctorID', 'DESC')
                ->paginate(12);
            $id = $req->spec_id;
        } else {
            $doctors = DB::table('users')
                ->join('specializations', 'specializations.id', 'users.specialization')
                ->leftJoin('doctor_schedules', function ($join) {
                    $join->on('doctor_schedules.doctorID', '=', 'users.id');
                    $join->where('doctor_schedules.title', '=', 'Availability');
                })
                ->where('users.specialization', $id)
                ->where('users.active', '1')
                ->select('users.*', 'specializations.name as sp_name', 'doctor_schedules.title')
                ->groupBy('users.id')
                ->orderby('doctor_schedules.doctorID', 'DESC')
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
        $session = Session::where('patient_id', $user->id)->where('specialization_id', $id)->first();
        return $this->sendResponse(['doctors' => $doctors, 'session' => $session, 'id' => $id, 'user' => $user], 'Doctors List');
    }

    public function get_doc_avail_dates($id)
    {
        $today = date("Y-m-d", strtotime(Carbon::today()));
        $dates = DoctorSchedule::where('doctorID', $id)->where('title', 'Availability')->orderBy('date', 'asc')->get();
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
        $doc = DB::table('users')->join('specializations', 'users.specialization', 'specializations.id')->where('users.id', $id)->select('users.*', 'specializations.name as sp_name')->first();
        $doc->user_image = \App\Helper::check_bucket_files_url($doc->user_image);
        $data['doc'] = $doc;
        $symptoms = DB::table('isabel_symptoms')->get();
        $data['symptoms'] = $symptoms;
        $data['sessions'] = Session::where('patient_id',auth()->user()->id)->first();
        return $this->sendResponse($data, 'Doctor Availability');
    }

    public function doctor_timing(Request $request)
    {
        $user = Auth::user();
        $day = Carbon::parse($request->sdate)->format('l');
        $day_nick = '';
        switch ($day) {
            case 'Monday':
                $day_nick = 'mon';
                break;
            case 'Tuesday':
                $day_nick = 'tues';
                break;
            case 'Wednesday':
                $day_nick = 'weds';
                break;
            case 'Thursday':
                $day_nick = 'thurs';
                break;
            case 'Friday':
                $day_nick = 'fri';
                break;
            case 'Saturday':
                $day_nick = 'sat';
                break;
            case 'Sunday':
                $day_nick = 'sun';
                break;
            default:
                break;
        }
        $sche = DB::table('doctor_schedules')->where('user_id',$request->id)->where('title','Availability')->where($day_nick,1)->first();
        $sche->from_time =  User::convert_utc_to_user_timezone($sche->doctorID,$request->sdate." ".$sche->from_time)['datetime'];
        $sche->to_time =  User::convert_utc_to_user_timezone($sche->doctorID,$request->sdate." ".$sche->to_time)['datetime'];
        $check_availability = DB::table('appointments')->where('doctor_id',$request->id)
            ->where('date',$request->sdate)
            ->pluck('time')->toArray();
        if($sche){
            $fromTime = $sche->from_time;
            $toTime = $sche->to_time;
            $start = Carbon::createFromTimeString($fromTime);
            $end = Carbon::createFromTimeString($toTime);
            $timeSlots = [];
            while ($start->lessThan($end)) {
                $timeSlots[] = $start->format('h:i a');
                $start->addMinutes(20);
            }
            if ($check_availability) {
                $timeSlots = array_filter($timeSlots, function ($time) use ($user,$check_availability) {
                    $current = User::convert_user_timezone_to_utc($user->id,$time)['time'];
                    $tt = Carbon::parse($current)->format('H:i:s');
                    return !(in_array($tt, $check_availability));
                });
                $timeSlots = array_values($timeSlots);
            }
        }

        return $this->sendResponse(['time_slots' => $timeSlots], 'Doctor Timing');

    }

    public function create_appointment(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'fname' =>  ['required', 'string', 'max:255'],
            'lname' =>  ['required', 'string', 'max:255'],
            'email' =>  ['required', 'string', 'max:255'],
            'phone' =>  ['required', 'string', 'max:255'],
            'provider' =>  ['required', 'string', 'max:255'],
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
        $patient_id = Auth::user()->id;
        $pro_id = $data['provider'];
        $pro_name_data = DB::table('users')->select('name', 'last_name', 'email', 'phone_number')->where('id', $pro_id)->get();
        $provider_name = $pro_name_data[0]->name . " " . $pro_name_data[0]->last_name;
        $appoint_data_time = $datetime['datetime'];

        $firstReminder = date('Y-m-d H:i:s', (strtotime('-1 day', strtotime($appoint_data_time))));
        $timestamp = strtotime($appoint_data_time);
        $time = $timestamp - (15 * 60);

        $secondReminder = date("Y-m-d H:i:s", $time);
        $new_app_id = "";
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
            if ($session_price_get->followup_fee != null) {
                $session_price = $session_price_get->followup_fee;
            } else {
                $session_price = $session_price_get->consultation_fee;
            }
        } else {
            $session_price_get = User::find($data['provider']);
            $session_price = $session_price_get->consultation_fee;
        }

        $new_session_id = "";
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
                'remaining_time' => 'full',
                'channel' => $channelName,
                'created_at' => $sessiondate,
                'updated_at' => $sessiondate,
                'specialization_id' => $request->spec_id,
                'price' => $session_price,
                'location_id' => $request->loc_id,
                'validation_status' => "valid",
                'start_time' => date('Y-m-d H:i:s', (strtotime($datetime['datetime']))),
                'end_time' => date('Y-m-d H:i:s', (strtotime('15 min', strtotime($datetime['datetime'])))),
            ]);
            return $this->sendResponse(['session_id' => $request->ses_id], 'Appointment Created');
        }else{
            $session_id = Session::create([
                'patient_id' =>  $patient_id,
                'appointment_id' =>  $app_id,
                'doctor_id' =>  $pro_id,
                'date' => date('Y-m-d', (strtotime($datetime['datetime']))),
                'status' => 'pending',
                'queue' => 0,
                'remaining_time' => 'full',
                'channel' => $channelName,
                'join_enable' => null,
                'created_at' => $sessiondate,
                'updated_at' => $sessiondate,
                'specialization_id' => $request->spec_id,
                'price' => $session_price,
                'session_id' => $new_session_id,
                'location_id' => $request->loc_id,
                'validation_status' => "valid",
                'start_time' => date('Y-m-d H:i:s', (strtotime($datetime['datetime']))),
                'end_time' => date('Y-m-d H:i:s', (strtotime('15 min', strtotime($datetime['datetime'])))),
            ])->id;
            if($request->payment_method == "credit-card"){
                $session = Session::find($session_id);
                $data = "Appointment-".$new_session_id."-1";
                $pay = new \App\Http\Controllers\MeezanPaymentController();
                $res = $pay->payment_app($data,($session->price*100));
                if (isset($res) && $res->errorCode == 0) {
                    return redirect($res->formUrl);
                }else{
                    return $this->sendError('Payment Error', ['error' => 'Payment Error']);
                }
            }elseif($request->payment_method == "first-visit"){
                $session = Session::find($session_id);
                $session->status = "paid";
                $session->save();
                return $this->sendResponse(['session_id' => $session_id], 'Appointment Created');
            }else{
                Session::find($session_id)->delete();
                return $this->sendError('Payment Error', ['error' => 'Payment Error']);
            }
        }
    }
}
