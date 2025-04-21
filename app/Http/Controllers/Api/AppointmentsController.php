<?php

namespace App\Http\Controllers\Api;

use App\Appointment;
use App\DoctorSchedule;
use App\Events\RealTimeMessage;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MeezanPaymentController;
use App\Mail\CancelAppointmentAccountantMail;
use App\Mail\CancelAppointmentDoctorMail;
use App\Mail\CancelAppointmentPatientMail;
use Exception;
use Log;
use Mail;
use Notification;
use Validator;
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
                if ($date->mon) {
                    $availability['mon'] = 1;
                }
                if ($date->tues) {
                    $availability['tues'] = 1;
                }
                if ($date->weds) {
                    $availability['weds'] = 1;
                }
                if ($date->thurs) {
                    $availability['thurs'] = 1;
                }
                if ($date->fri) {
                    $availability['fri'] = 1;
                }
                if ($date->sat) {
                    $availability['sat'] = 1;
                }
                if ($date->sun) {
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
        $data['sessions'] = Session::where('patient_id', auth()->user()->id)->first();
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
        $sche = DB::table('doctor_schedules')->where('user_id', $request->id)->where('title', 'Availability')->where($day_nick, 1)->first();
        $sche->from_time = User::convert_utc_to_user_timezone($sche->doctorID, $request->sdate . " " . $sche->from_time)['datetime'];
        $sche->to_time = User::convert_utc_to_user_timezone($sche->doctorID, $request->sdate . " " . $sche->to_time)['datetime'];
        $check_availability = DB::table('appointments')->where('doctor_id', $request->id)
            ->where('date', $request->sdate)
            ->pluck('time')->toArray();
        if ($sche) {
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
                $timeSlots = array_filter($timeSlots, function ($time) use ($user, $check_availability) {
                    $current = User::convert_user_timezone_to_utc($user->id, $time)['time'];
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
        try {

            $validator = Validator::make($request->all(), [
                'fname' => 'required|string|max:255',
                'lname' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:255',
                'provider' => 'required|exists:users,id',
                'date' => 'required|date_format:Y-m-d',
                'time' => 'required|string',
                'spec_id' => 'required|exists:specializations,id',
                'payment_method' => 'required|in:credit-card,first-visit',
            ]);


            if ($validator->fails()) {
                return $this->sendError('Validation Error', $validator->errors(), 422);
            }

            $user = Auth::user();
            if (!$user) {
                return $this->sendError('Authentication Error', ['error' => 'User not authenticated'], 401);
            }

            $datetime = date('Y-m-d', strtotime($request->date)) . ' ' . $request->time;
            $datetime = User::convert_user_timezone_to_utc($user->id, $datetime);

            $startTime = $datetime['datetime'];
            $appDate = $datetime['date'];
            $timeOnly = date('H:i:s', strtotime($startTime));
            $dayName = (new Carbon($appDate))->format('l');
            $patientId = $user->id;
            $providerId = $request->provider;

            $provider = DB::table('users')
                ->select('name', 'last_name', 'email', 'phone_number', 'consultation_fee', 'followup_fee')
                ->where('id', $providerId)
                ->first();

            if (!$provider) {
                return $this->sendError('Provider Error', ['error' => 'Provider not found'], 404);
            }

            $providerName = $provider->name . " " . $provider->last_name;
            $patientName = $request->fname . " " . $request->lname;

            $firstReminder = date('Y-m-d H:i:s', strtotime('-1 day', strtotime($startTime)));
            $secondReminder = date('Y-m-d H:i:s', strtotime('-15 minutes', strtotime($startTime)));

            $appointmentId = rand(411111, 499999);
            $lastAppointment = DB::table('appointments')->orderBy('id', 'desc')->first();
            if ($lastAppointment) {
                $appointmentId = $lastAppointment->appointment_id + rand(11, 99);
            }

            DB::beginTransaction();
            try {
                $appointment = Appointment::create([
                    'doctor_id' => $providerId,
                    'patient_id' => $patientId,
                    'patient_name' => $patientName,
                    'doctor_name' => $providerName,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'date' => $appDate,
                    'day' => $dayName,
                    'status' => 'pending',
                    'time' => $timeOnly,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'reminder_one_status' => 'pending',
                    'reminder_two_status' => 'pending',
                    'reminder_one' => $firstReminder,
                    'reminder_two' => $secondReminder,
                    'appointment_id' => $appointmentId,
                ]);

                
                $sessionCount = DB::table('sessions')
                ->where('doctor_id', $providerId)
                ->where('patient_id', $patientId)
                ->where('specialization_id', $request->spec_id)
                ->count();
                
                $sessionPrice = $sessionCount > 0 && $provider->followup_fee
                ? $provider->followup_fee
                : $provider->consultation_fee;
                
                $sessionId = rand(311111, 399999);
                $lastSession = DB::table('sessions')->orderBy('id', 'desc')->first();
                if ($lastSession) {
                    $sessionId = $lastSession->session_id + rand(11, 99);
                }
                
                $channelName = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 8);
                $endTime = date('Y-m-d H:i:s', strtotime('+15 minutes', strtotime($startTime)));
                
                if (isset($request->ses_id)) {
                    Session::where('id', $request->ses_id)
                    ->update([
                        'patient_id' => $patientId,
                        'appointment_id' => $appointment->id,
                        'doctor_id' => $providerId,
                            'date' => date('Y-m-d', strtotime($startTime)),
                            'status' => 'paid',
                            'queue' => 0,
                            'remaining_time' => 'full',
                            'channel' => $channelName,
                            'created_at' => now(),
                            'updated_at' => now(),
                            'specialization_id' => $request->spec_id,
                            'price' => $sessionPrice,
                            'location_id' => $request->loc_id,
                            'validation_status' => 'valid',
                            'start_time' => $startTime,
                            'end_time' => $endTime,
                        ]);
                    DB::commit();
                    return $this->sendResponse(['session_id' => $request->ses_id], 'Appointment Created');
                } else {
                    $session = Session::create([
                        'patient_id' => $patientId,
                        'appointment_id' => $appointment->id,
                        'doctor_id' => $providerId,
                        'date' => date('Y-m-d', strtotime($startTime)),
                        'status' => 'pending',
                        'queue' => 0,
                        'remaining_time' => 'full',
                        'channel' => $channelName,
                        'join_enable' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                        'specialization_id' => $request->spec_id,
                        'price' => $sessionPrice,
                        'session_id' => $sessionId,
                        'location_id' => $request->loc_id,
                        'validation_status' => 'valid',
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                    ]);

                    DB::commit();
                    if ($request->payment_method == "credit-card") {
                        $data = "Appointment-" . $sessionId . "-1";
                        $pay = new MeezanPaymentController();
                        $res = $pay->payment_app($data, ($session->price * 100));
                        
                        if (isset($res) && $res->errorCode == 0) {
                            return $this->sendResponse(['method' => 'credit-card', 'url' => $res->formUrl, 'session_id' => $session->id], 'Payment link generated successfully');
                        } else {
                            DB::rollBack();
                            return $this->sendError('Payment Error', ['error' => 'Payment gateway error'], 400);
                        }
                    } elseif ($request->payment_method == "first-visit") {
                        $session->status = "paid";
                        $session->save();
                        return $this->sendResponse(['session_id' => $session->id], 'Appointment Created');
                    } else {
                        DB::rollBack();
                        return $this->sendError('Payment Error', ['error' => 'Invalid payment method'], 400);
                    }
                }
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->sendError('Database Error', ['error' => $e->getMessage()], 500);
            }
        } catch (\Exception $e) {
            return $this->sendError('Server Error', ['error' => $e->getMessage()], 500);
        }
    }

    public function patient_appointments()
    {
        try {
            $user = Auth::user();
    
            if (!$user) {
                return $this->sendError('Authentication Error', ['error' => 'User not authenticated'], 401);
            }
    
            $today = date('Y-m-d');
            $todayTime = date('h:i A');
    
            $appointments = DB::table('appointments')
                ->join('users', 'appointments.doctor_id', 'users.id')
                ->join('sessions', 'appointments.id', 'sessions.appointment_id')
                ->where('appointments.patient_id', $user->id)
                ->where('sessions.status', '!=', 'pending')
                ->orderBy('appointments.created_at', 'DESC')
                ->select(
                    'appointments.*',
                    'sessions.id as sesssion_id',
                    'sessions.que_message as msg',
                    'sessions.join_enable',
                    'users.specialization as spec_id'
                )
                ->paginate(10);
    
            foreach ($appointments as $app) {
                $datetime = date('Y-m-d H:i:s', strtotime("$app->date $app->time"));
                $converted = User::convert_utc_to_user_timezone($user->id, $datetime);
                $app->time = $converted['time'];
                $app->date = $converted['date'];
            }
    
            return $this->sendResponse(['appointments' => $appointments], 'Appointments List');
        } catch (\Exception $e) {
            return $this->sendError('Server Error', ['error' => $e->getMessage()], 500);
        }
    }

    public function pat_appointment_cancel($id)
    {
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

            $text = "Appointment with " . $patient_data->name . " " . $patient_data->last_name . " has been Cancelled by " . $user_type;
            $notification_id = \App\Notification::create([
                'user_id' =>  $getAppointment->doctor_id,
                'type' => '/doctor/appointments',
                'text' => $text,
                'appoint_id' => $id,
            ]);
            $data = [
                'user_id' =>  $getAppointment->doctor_id,
                'type' => '/doctor/appointments',
                'text' => $text,
                'appoint_id' => $id,
                'refill_id' => "null",
                'received' => 'false',
                'session_id' => 'null',
            ];
            event(new RealTimeMessage($getAppointment->patient_id));
        } catch (Exception $e) {
            Log::error($e);
        }
        return $this->sendResponse([], 'Appointment cancelled.');
    }
    
}
