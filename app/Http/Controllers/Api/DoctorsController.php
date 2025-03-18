<?php

namespace App\Http\Controllers\Api;
use App\Events\loadOnlineDoctor;
use App\Http\Controllers\Controller;
use App\Events\updateQuePatient;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Session;
use App\User;
use DB;

class DoctorsController extends BaseController
{
    public function index()
    {
        $doctors = DB::table('users')
            ->select(
                'users.name',
                'users.last_name',
                'users.id',
                'users.rating',
                'users.gender',
                'users.zip_code',
                'users.consultation_fee',
                'users.followup_fee',
                'users.user_image',
                'users.status',
                'users.specialization'
            )
            ->where('user_type', 'doctor')
            ->where('active', '1')
            ->where('status', '!=', 'ban')
            ->orderBy('id', 'desc')
            ->paginate(8);

        $doctors->getCollection()->transform(function ($doctor) {

            $doctorDetails = DB::table('doctor_details')->where('doctor_id', $doctor->id)->first();
            $specialization = DB::table('specializations')->where('id', $doctor->specialization)->first();
            $doctor->details = $doctorDetails;
            $doctor->specializations = $specialization;
            $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);

            return $doctor;
        });

        return response()->json($doctors);
    }

    public function singleDoctor($id)
    {
        $doctor = DB::table('users')
            ->select(
                'users.name',
                'users.last_name',
                'users.id',
                'users.rating',
                'users.gender',
                'users.zip_code',
                'users.consultation_fee',
                'users.followup_fee',
                'users.user_image',
                'users.status',
                'users.specialization'
            )
            ->where('user_type', 'doctor')
            ->where('active', '1')
            ->where('status', '!=', 'ban')
            ->where('id', $id)
            ->first();

        if ($doctor) {
            $doctorDetails = DB::table('doctor_details')->where('doctor_id', $doctor->id)->first();
            $specialization = DB::table('specializations')->where('id', $doctor->specialization)->first();
            $doctor->details = $doctorDetails;
            $doctor->specializations = $specialization;
            $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);
        }

        return response()->json($doctor);
    }

    public function getSpeciallization()
    {
        $specialization = DB::table('specializations')
            ->join('users', 'users.specialization', 'specializations.id')
            ->groupBy('specializations.id')
            ->select('specializations.*', )
            ->get();

        return response()->json($specialization);
    }

    public function getDoctorsBySpeciallization($id)
    {
        $doctors = DB::table('users')
            ->join('specializations', 'specializations.id', 'users.specialization')
            ->where('users.specialization', $id)
            ->where('users.active', '1')
            ->select(
                'users.name',
                'users.last_name',
                'users.id',
                'users.rating',
                'users.zip_code',
                'users.status',
                'users.consultation_fee',
                'users.followup_fee',
                'users.user_image',
                'specializations.name as sp_name',
                'specializations.id as sp_id'
            )
            ->paginate(10);
        foreach ($doctors as $doctor) {
            $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);
        }
        return response()->json($doctors);
    }
    public function getOnlineDoctorsBySpeciallization($id)
    {
        $doctors = DB::table('users')
            ->join('specializations', 'specializations.id', 'users.specialization')
            ->where('users.specialization', $id)
            ->where('users.status', 'online')
            ->where('users.active', '1')
            ->select(
                'users.name',
                'users.last_name',
                'users.id',
                'users.rating',
                'users.zip_code',
                'users.status',
                'users.consultation_fee',
                'users.followup_fee',
                'users.user_image',
                'specializations.name as sp_name',
                'specializations.id as sp_id'
            )
            ->paginate(10);
        foreach ($doctors as $doctor) {
            $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);
        }
        return response()->json($doctors);
    }

    public function getOnlineDoctors()
    {
        $doctors = DB::table('users')
            ->join('specializations', 'specializations.id', 'users.specialization')
            ->where('users.status', 'online')
            ->where('users.active', '1')
            ->select(
                'users.name',
                'users.last_name',
                'users.id',
                'users.rating',
                'users.status',
                'users.zip_code',
                'users.consultation_fee',
                'users.followup_fee',
                'users.user_image',
                'specializations.name as sp_name',
                'specializations.id as sp_id'
            )
            ->paginate(10);

        foreach ($doctors as $doctor) {
            $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);
        }

        return response()->json($doctors);
    }

    public function patients_in_queue()
    {
        $user = auth()->user();
        $session_check = Session::where('doctor_id', $user->id)->where('status', 'started')->first();
        if ($session_check != null) {
            return $this->sendResponse(['session_id' => $session_check->id, 'action' => 'videoScreen'], 'Session already started');
        } else {
            $doc_id = $user->id;
            $appointments = DB::table('appointments')
                ->join('sessions', 'appointments.id', 'sessions.appointment_id')
                ->where('appointments.date', '=', date('Y-m-d'))
                ->where('appointments.status', '=', 'pending')
                ->where('sessions.status', '=', 'paid')
                ->where('sessions.doctor_id', '=', $doc_id)
                ->select('appointments.time', 'appointments.date', 'appointments.id as appointment_id')
                ->first();
            if ($appointments == null) {
                $data_sessions = Session::where('doctor_id', $doc_id)
                    ->where('status', 'invitation sent')
                    ->groupBy('patient_id')
                    ->orderBy('queue', 'ASC')
                    ->select('id as session_id', 'patient_id', 'appointment_id', 'status')
                    ->get();

                $sessions = [];
                $mints = 5;
                $doc_joined_pat = Session::where('doctor_id', $doc_id)
                    ->where('status', 'doctor joined')
                    ->orderBy('queue', 'ASC')
                    ->select('id as session_id', 'patient_id', 'appointment_id', 'status')
                    ->first();
                if ($doc_joined_pat == null) {
                    $mints = 5;
                } else {
                    $pat = DB::table('users')->where('id', $doc_joined_pat['patient_id'])->select('name', 'last_name', 'user_image')->first();
                    $pat->user_image = \App\Helper::check_bucket_files_url($pat->user_image);
                    $patient_full_name = $pat->name . " " . $pat->last_name;
                    $doc_joined_pat['patient_full_name'] = $patient_full_name;
                    $doc_joined_pat['user_image'] = $pat->user_image;
                    array_push($sessions, $doc_joined_pat);
                    event(new updateQuePatient('update patient que'));
                    $mints = 15;
                }
                foreach ($data_sessions as $single_session) {
                    Session::where('id', $single_session['session_id'])->update(['que_message' => 'Your Doctor Will Be Available In Approximately ' . $mints . ' Mints', 'join_enable' => '1']);
                    $mints += 10;
                    $pat = DB::table('users')->where('id', $single_session['patient_id'])->select('name', 'last_name', 'user_image')->first();

                    $pat->user_image = \App\Helper::check_bucket_files_url($pat->user_image);

                    $patient_full_name = $pat->name . " " . $pat->last_name;
                    $single_session['patient_full_name'] = $patient_full_name;
                    $single_session['user_image'] = $pat->user_image;
                    array_push($sessions, $single_session);
                    event(new updateQuePatient('update patient que'));
                }
                return $this->sendResponse(['sessions' => $sessions, 'action' => 'OnlineWaitingScreen'], 'get patient in queue');
            } else {
                $timestamp = date('Y-m-d H:i:s', strtotime(User::convert_utc_to_user_timezone($user->id, date("Y-m-d H:i:s"))['datetime']));
                $current_date_time = date('Y-m-d H:i:s', strtotime(User::convert_utc_to_user_timezone($user->id, Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, 'UTC')->setTimezone('UTC'))['datetime']));
                $app_date_time = $appointments->date . ' ' . $appointments->time;
                $appoint_dateTime = date('Y-m-d H:i:s', strtotime(User::convert_utc_to_user_timezone($user->id, $app_date_time)['datetime']));
                $date = strtotime($appoint_dateTime);
                $date_time_plus = date('Y-m-d H:i:s', strtotime("+5 minute", $date));
                $date_time_subtract = date('Y-m-d H:i:s', strtotime("-15 minute", $date));


                if ($current_date_time >= $date_time_subtract && $current_date_time <= $date_time_plus) {
                    $a_sessions = Session::where('doctor_id', $doc_id)
                        ->where('appointment_id', $appointments->appointment_id)
                        ->groupBy('patient_id')
                        ->select('id as session_id', 'patient_id', 'appointment_id', 'status')
                        ->get()
                        ->toArray();

                    $e_sessions = Session::where('doctor_id', $doc_id)
                        ->where('status', 'invitation sent')
                        ->where('appointment_id', '=', null)
                        ->groupBy('patient_id')
                        ->orderBy('queue', 'ASC')
                        ->select('id as session_id', 'patient_id', 'appointment_id', 'status')
                        ->get()
                        ->toArray();

                    $data_sessions = array_merge($a_sessions, $e_sessions);

                    $sessions = [];
                    $mints = 5;
                    $doc_joined_pat = Session::where('doctor_id', $doc_id)
                        ->where('status', 'doctor joined')
                        ->orderBy('queue', 'ASC')
                        ->select('id as session_id', 'patient_id', 'appointment_id', 'status')
                        ->first();
                    if ($doc_joined_pat == null) {
                        $mints = 5;
                    } else {
                        $pat = DB::table('users')->where('id', $doc_joined_pat['patient_id'])->select('name', 'last_name', 'user_image')->first();
                        $pat->user_image = \App\Helper::check_bucket_files_url($pat->user_image);
                        $patient_full_name = $pat->name . " " . $pat->last_name;
                        $doc_joined_pat['patient_full_name'] = $patient_full_name;
                        $doc_joined_pat['user_image'] = $pat->user_image;
                        array_push($sessions, $doc_joined_pat);
                        event(new updateQuePatient('update patient que'));
                        $mints = 15;
                    }
                    foreach ($data_sessions as $single_session) {

                        Session::where('id', $single_session['session_id'])->update(['que_message' => 'Your Doctor Will Be Available In Approximately ' . $mints . ' Mints', 'join_enable' => '1']);
                        $mints += 10;
                        $pat = DB::table('users')->where('id', $single_session['patient_id'])->select('name', 'last_name', 'user_image')->first();
                        $pat->user_image = \App\Helper::check_bucket_files_url($pat->user_image);

                        $patient_full_name = $pat->name . " " . $pat->last_name;
                        $single_session['patient_full_name'] = $patient_full_name;
                        $single_session['user_image'] = $pat->user_image;
                        array_push($sessions, $single_session);
                        event(new updateQuePatient('update patient que'));
                    }
                    return $this->sendResponse(['sessions' => $sessions, 'action' => 'OnlineWaitingScreen'], 'No patient in queue');
                } else {
                    $data_sessions = Session::where('doctor_id', $doc_id)
                        ->where('status', 'invitation sent')
                        ->groupBy('patient_id')
                        ->orderBy('queue', 'ASC')
                        ->select('id as session_id', 'patient_id', 'appointment_id', 'status')
                        ->get();

                    $sessions = [];
                    $mints = 5;
                    $doc_joined_pat = Session::where('doctor_id', $doc_id)
                        ->where('status', 'doctor joined')
                        ->orderBy('queue', 'ASC')
                        ->select('id as session_id', 'patient_id', 'appointment_id', 'status')
                        ->first();
                    if ($doc_joined_pat == null) {
                        $mints = 5;
                    } else {
                        $pat = DB::table('users')->where('id', $doc_joined_pat['patient_id'])->select('name', 'last_name', 'user_image')->first();
                        $pat->user_image = \App\Helper::check_bucket_files_url($pat->user_image);
                        $patient_full_name = $pat->name . " " . $pat->last_name;
                        $doc_joined_pat['patient_full_name'] = $patient_full_name;
                        $doc_joined_pat['user_image'] = $pat->user_image;
                        array_push($sessions, $doc_joined_pat);
                        event(new updateQuePatient('update patient que'));
                        $mints = 15;
                    }
                    foreach ($data_sessions as $single_session) {
                        Session::where('id', $single_session['session_id'])->update(['que_message' => 'Your Doctor Will Be Available In Approximately ' . $mints . ' Mints', 'join_enable' => '1']);
                        $mints += 10;
                        $pat = DB::table('users')->where('id', $single_session['patient_id'])->select('name', 'last_name', 'user_image')->first();

                        $pat->user_image = \App\Helper::check_bucket_files_url($pat->user_image);

                        $patient_full_name = $pat->name . " " . $pat['last_name'];
                        $single_session['patient_full_name'] = $patient_full_name;
                        $single_session['user_image'] = $pat->user_image;
                        array_push($sessions, $single_session);
                        event(new updateQuePatient('update patient que'));
                    }
                    return $this->sendResponse(['sessions' => $sessions, 'action' => 'OnlineWaitingScreen'], 'No patient in queue');
                }
            }
        }
    }

    public function change_online_status()
    {
        event(new loadOnlineDoctor('run'));
        $doc = auth()->user();
        if ($doc->status == 'online') {
            $inQueue = Session::where(['doctor_id' => $doc->id, 'status' => 'invitation sent'])->first();
            if (isset($inQueue->id)) {
                return $this->sendResponse('online', 'doctor is online');
            } else {
                User::where('id', $doc['id'])->update(['status' => 'offline']);
                try {
                    $data = DB::table('users')->where('id',$doc->id)->select('id','status')->first();
                    $data->id = (string)$doc->id;
                    $data->received = "false";
                } catch (\Throwable $th) {
                    throw $th;
                }
                return $this->sendResponse("offline", "doctor is offline");
            }
        } else {
            User::where('id', $doc['id'])->update(['status' => 'online']);
            try {
                $data = DB::table('users')->where('id',$doc->id)->select('id','status')->first();
                $data->id = (string)$doc->id;
                $data->received = "false";
            } catch (\Throwable $th) {
                throw $th;
            }
            return $this->sendResponse("online", "doctor is online");
        }
    }

}
