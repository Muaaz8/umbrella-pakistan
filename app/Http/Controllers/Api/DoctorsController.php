<?php

namespace App\Http\Controllers\Api;
use App\Appointment;
use App\DoctorSchedule;
use App\Events\loadOnlineDoctor;
use App\Helper;
use App\Http\Controllers\Controller;
use App\Events\updateQuePatient;
use Auth;
use App\Notification;
use App\Events\RealTimeMessage;
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
                    $data = DB::table('users')->where('id', $doc->id)->select('id', 'status')->first();
                    $data->id = (string) $doc->id;
                    $data->received = "false";
                } catch (\Throwable $th) {
                    throw $th;
                }
                return $this->sendResponse("offline", "doctor is offline");
            }
        } else {
            User::where('id', $doc['id'])->update(['status' => 'online']);
            try {
                $data = DB::table('users')->where('id', $doc->id)->select('id', 'status')->first();
                $data->id = (string) $doc->id;
                $data->received = "false";
            } catch (\Throwable $th) {
                throw $th;
            }
            return $this->sendResponse("online", "doctor is online");
        }
    }

    public function doctors_filter($type)
    {

        if ($type == "pakistani") {
            $doctors = DB::table('users')
                ->where('user_type', 'doctor')
                ->where('active', '1')
                ->where('status', '!=', 'ban')
                ->whereNull('zip_code')
                ->orderBy('id', 'desc')
                ->get();
            foreach ($doctors as $doctor) {
                $doctor->details = DB::table('doctor_details')->where('doctor_id', $doctor->id)->first();
                $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);
                $doctor->specializations = DB::table('specializations')->where('id', $doctor->specialization)->first();
            }
            return $this->sendResponse($doctors, 'Pakistani doctors');
        } else if ($type == "american") {
            $doctors = DB::table('users')
                ->where('user_type', 'doctor')
                ->where('active', '1')
                ->where('status', '!=', 'ban')
                ->whereNotNull('zip_code')
                ->orderBy('id', 'desc')
                ->get();
            foreach ($doctors as $doctor) {
                $doctor->details = DB::table('doctor_details')->where('doctor_id', $doctor->id)->first();
                $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);
                $doctor->specializations = DB::table('specializations')->where('id', $doctor->specialization)->first();
            }
            return $this->sendResponse($doctors, 'American doctors');
        } else if ($type == "all") {
            $doctors = DB::table('users')
                ->where('user_type', 'doctor')
                ->where('active', '1')
                ->where('status', '!=', 'ban')
                ->orderBy('id', 'desc')
                ->get();
            foreach ($doctors as $doctor) {
                $doctor->details = DB::table('doctor_details')->where('doctor_id', $doctor->id)->first();
                $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);
                $doctor->specializations = DB::table('specializations')->where('id', $doctor->specialization)->first();
            }
            return $this->sendResponse($doctors, 'All doctors');
        } else if ($type == "online") {
            $doctors = DB::table('users')
                ->where('user_type', 'doctor')
                ->where('status', 'online')
                ->where('active', '1')
                ->orderBy('id', 'desc')
                ->get();
            foreach ($doctors as $doctor) {
                $doctor->details = DB::table('doctor_details')->where('doctor_id', $doctor->id)->first();
                $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);
                $doctor->specializations = DB::table('specializations')->where('id', $doctor->specialization)->first();
            }
            return $this->sendResponse($doctors, 'Online doctors');
        } else {
            $doctors = DB::table('users')
                ->where('user_type', 'doctor')
                ->where('active', '1')
                ->where('status', '!=', 'ban')
                ->where('name', 'LIKE', '%' . $type . '%')
                ->orWhere('last_name', 'LIKE', '%' . $type . '%')
                ->orderBy('id', 'desc')
                ->get();
            foreach ($doctors as $doctor) {
                $doctor->details = DB::table('doctor_details')->where('doctor_id', $doctor->id)->first();
                $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);
                $doctor->specializations = DB::table('specializations')->where('id', $doctor->specialization)->first();
            }
            return $this->sendResponse($doctors, 'Doctors by name');
        }
    }

    public function get_doctor_schedule()
    {
        $user=Auth::user();
        if($user->user_type=='doctor'){
            $date = date('Y-m-d H:i:s');
            $date = User::convert_utc_to_user_timezone($user->id,$date)['datetime'];
            $time = date('H:i:s',strtotime($date));
            $date = date('Y-m-d',strtotime($date));
            $schedule = DoctorSchedule::where('doctorID', $user->id)->where('title','Availability')->orderBy('id','desc')->get();
            foreach($schedule as $sc){
                $sc->from_time = User::convert_utc_to_user_timezone($user->id,$sc->from_time)['time'];
                $sc->to_time = User::convert_utc_to_user_timezone($user->id,$sc->to_time)['time'];
            }
            return $this->sendResponse(['schedule'=>$schedule], 'Doctor Schedule');
        }
        else{
            return $this->sendError('Unauthorized', 'You are not authorized to access this resource.');
        }
    }

    public function add_doc_schedule(Request $request)
    {
        $doctorID = isset($request->doc_id) ? $request->doc_id : Auth::user()->id;

        $AvailabilityStartUser = $request->from_time;
        $AvailabilityStart = User::convert_user_timezone_to_utc($doctorID,$AvailabilityStartUser)['time'];
        $AvailabilityEndUser = $request->to_time;
        $AvailabilityEnd = User::convert_user_timezone_to_utc($doctorID,$AvailabilityEndUser)['time'];
        if($request->AvailabilityTitle == "Availability"){
            $query=DB::table('doctor_schedules')->insert([
                'user_id'=>$doctorID,
                'mon'=> in_array("Mon",$request->week),
                'tues'=> in_array("Tues",$request->week),
                'weds'=> in_array("Wed",$request->week),
                'thurs'=> in_array("Thurs",$request->week),
                'fri'=> in_array("Fri",$request->week),
                'sat'=> in_array("Sat",$request->week),
                'sun'=> in_array("Sun",$request->week),
                'from_time'=> $AvailabilityStart,
                'to_time'=> $AvailabilityEnd,
                'created_at'=> now(),
                'updated_at'=> now(),
                'title' => $request->AvailabilityTitle,
                'doctorID'=>$doctorID,
            ]);
        }else{
            $query=DB::table('doctor_schedules')->insert([
                'user_id'=>$doctorID,
                'mon'=> 0,
                'tues'=> 0,
                'weds'=> 0,
                'thurs'=> 0,
                'fri'=> 0,
                'sat'=> 0,
                'sun'=> 0,
                'from_time'=> $request->from_time,
                'to_time'=> $request->to_time,
                'created_at'=> now(),
                'updated_at'=> now(),
                'title' => $request->AvailabilityTitle,
                'doctorID'=>$doctorID,
            ]);
        }
        return $this->sendResponse([], 'schedule added successfully');
    }

    public function add_doctor_details(Request $request){
        $doctor = DB::table('doctor_details')->where('doctor_id',auth()->user()->id)->first();
        if($doctor){
            $certi = json_encode($request->certificates);
            $condi = json_encode($request->conditions);
            $proce = json_encode($request->procedures);
            DB::table('doctor_details')->where('doctor_id',auth()->user()->id)->update([
                'doctor_id' => auth()->user()->id,
                'certificates' => $certi,
                'conditions' => $condi,
                'procedures' => $proce,
                'location' => $request->location,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'about' => $request->about,
                'experience' => $request->experience,
                'education' => $request->education,
                'helping' => $request->helping,
                'issue' => $request->issue,
                'specialties' => $request->specialties,
                'updated_at' => now(),
            ]);
        }else{
            $certi = json_encode($request->certificates);
            $condi = json_encode($request->conditions);
            $proce = json_encode($request->procedures);
            DB::table('doctor_details')->insert([
                'doctor_id' => auth()->user()->id,
                'certificates' => $certi,
                'conditions' => $condi,
                'procedures' => $proce,
                'location' => $request->location,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'about' => $request->about,
                'education' => $request->education,
                'experience' => $request->experience,
                'helping' => $request->helping,
                'issue' => $request->issue,
                'specialties' => $request->specialties,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        return $this->sendResponse($doctor, 'update doctor details successfully');
    }

    public function get_doctor_details_by_id(){
        $id = auth()->user()->id;
        $doctor = DB::table('doctor_details')->where('doctor_id',$id)->first();
        if($doctor){
            $doctor->certificates = json_decode($doctor->certificates);
            $doctor->conditions = json_decode($doctor->conditions);
            $doctor->procedures = json_decode($doctor->procedures);
            return $this->sendResponse($doctor, 'get doctor details successfully');
        }
    }

    public function doc_patients(Request $request)
    {
        $user = auth()->user();
        $session_patients = DB::table('sessions')
            ->join('users', 'sessions.patient_id', '=', 'users.id')
            ->where('sessions.doctor_id', $user->id)
            ->where('sessions.status', '!=', 'pending')
            ->orderBy('sessions.date', 'DESC')
            ->groupBy('sessions.patient_id')
            ->select('sessions.*', 'users.user_image', DB::raw('MAX(sessions.id) as last_id'))
            ->get();

        $inclinic_patients = DB::table('in_clinics')
            ->join('users', 'in_clinics.user_id', 'users.id')
            ->where('in_clinics.doctor_id', $user->id)
            ->where('in_clinics.status', 'ended')
            ->orderBy('in_clinics.created_at', 'DESC')
            ->groupBy('in_clinics.user_id')
            ->select('in_clinics.*','in_clinics.user_id as patient_id', 'users.user_image', DB::raw('MAX(in_clinics.id) as last_id'))
            ->get();

        $patients = collect($session_patients->toArray())->merge($inclinic_patients->toArray());

        foreach ($patients as $patient) {
            $user = User::where('id', $user->id)->first();
            $patient->doc_name = $user['name'] . " " . $user['last_name'];
            $user_details = User::where('id', $patient->patient_id)->first();
            $patient->pat_name = $user_details['name'] . " " . $user_details['last_name'];

            $session = Session::find($patient->last_id);
            $inclinic = \App\Models\InClinics::where('user_id',$patient->patient_id)->orderBy('id','desc')->first();
            if($session != null && $inclinic != null){
                if($session->date > $inclinic->created_at){
                    $patient->last_visit = Helper::get_date_with_format($session->date);
                }else{
                    $patient->last_visit = Helper::get_date_with_format($inclinic->created_at);
                }
            }else if($session != null){
                $patient->last_visit = Helper::get_date_with_format($session->date);
            }else if($inclinic != null){
                $patient->last_visit = Helper::get_date_with_format($inclinic->created_at);
            }
            if(isset($patient->reason)){
                $patient->inclinic = true;
                $patient->last_diagnosis = $inclinic->reason;
            }else{
                $patient->inclinic = false;
                $patient->last_diagnosis = $session->diagnosis;
            }
            $patient->user_image = \App\Helper::check_bucket_files_url($patient->user_image);
        }
        $all_patients = collect($patients);
        return $this->sendResponse($all_patients, 'get patients successfully');
    }

    public function doctor_dashboard()
{
    $user = Auth::user();
    $userId = $user->id;
    $currentRole = strtolower($user->user_type);

    $email_status = DB::table('users_email_verification')
        ->where('user_id', $userId)
        ->value('status');

    $term_condition_status = DB::table('user_term_and_condition_status')
        ->where([
            ['status', '=', '0'],
            ['flag', '=', 'update'],
            ['user_id', '=', $userId],
        ])->exists();

    if ($currentRole !== 'admin') {

        if ($email_status === 1) {
            if (!$term_condition_status) {
                if (empty($user->provider)) {
                    auth()->user()->assignRole($currentRole);
                } else {
                    auth()->user()->assignRole('temp_patient');
                    return $this->sendError('Unauthorized', 'You are not authorized to access this resource.');
                }

                $profileImage = DB::table('users')->find($userId);
                $notifications = Notification::where('user_id', $userId)
                    ->orderByDesc('id')->get();

                $countNotification = $notifications->where('status', 'new')->count();

                if ($currentRole === 'doctor') {

                        $totalPatient = Session::where('doctor_id', $user->id)
                            ->where('status','!=','pending')
                            ->groupBy('patient_id')->get()
                            ->count();

                    $totalPendingAppoint = DB::table('appointments')
                        ->join('sessions', 'sessions.appointment_id', '=', 'appointments.id')
                        ->where([
                            ['appointments.status', '=', 'pending'],
                            ['sessions.status', '=', 'paid'],
                            ['appointments.doctor_id', '=', $userId]
                        ])->count();

                    $currentMonth = now()->month;
                    $monthTotalAppoint = Appointment::where('doctor_id', $userId)
                        ->whereMonth('created_at', $currentMonth)
                        ->count();

                    $sessionTotalPrice = DB::table("sessions")
                        ->where([
                            ['doctor_id', '=', $userId],
                            ['status', '=', 'ended'],
                        ])->sum('price');

                    $percentage = DB::table('doctor_percentage')
                        ->where('doc_id', $userId)
                        ->value('percentage') ?? 50;

                    $totalEarning = ($percentage / 100) * $sessionTotalPrice;

                    $labApprovalCount = DB::table('lab_orders')
                        ->where([
                            ['status', '=', 'essa-forwarded'],
                            ['type', '=', 'Counter'],
                            ['doc_id', '=', $userId]
                        ])
                        ->select('order_id')
                        ->distinct()
                        ->count();

                    $totalEarning += ($labApprovalCount * 3);

                    // Reschedule past appointments
                    $today = now()->format('Y-m-d');
                    $nowTime = now()->format('H:i:s');
                    DB::table('appointments')
                        ->whereDate('date', '<=', $today)
                        ->where('time', '<', $nowTime)
                        ->where('status', 'pending')
                        ->update(['status' => 'make-reschedule']);

                    $totalSessions = DB::table('sessions')
                        ->where([
                            ['doctor_id', '=', $userId],
                            ['status', '=', 'ended'],
                        ])->count();

                    $appoints = DB::table('appointments')
                        ->join('sessions', 'appointments.id', '=', 'sessions.appointment_id')
                        ->where([
                            ['appointments.doctor_id', '=', $userId],
                            ['appointments.status', '=', 'pending'],
                            ['appointments.date', '>=', $today],
                        ])
                        ->where('sessions.status', '!=', 'pending')
                        ->orderByDesc('appointments.created_at')
                        ->select('appointments.*', 'sessions.id as sesssion_id', 'sessions.que_message as msg', 'sessions.join_enable')
                        ->get();

                    foreach ($appoints as $appoint) {
                        $datetime = $appoint->date . ' ' . $appoint->time;
                        $datetime = User::convert_utc_to_user_timezone($userId, $datetime);
                        $appoint->date = $datetime['date'];
                        $appoint->time = $datetime['time'];
                    }
                    return $this->sendResponse([
                        'notifications' => $notifications,
                        'countNotification' => $countNotification,
                        'currentRole' => $currentRole,
                        'appoints' => $appoints,
                        'profile' => $profileImage,
                        'totalPatient' => $totalPatient,
                        'totalPendingAppoint' => $totalPendingAppoint,
                        'monthTotalAppoint' => $monthTotalAppoint,
                        'totalEarning' => $totalEarning,
                        'totalSessions' => $totalSessions
                    ], 'Doctor Dashboard');
                }
            } 
        } else {
            $userData = DB::table('users')
                ->leftJoin('users_email_verification', 'users.id', '=', 'users_email_verification.user_id')
                ->leftJoin('contracts', 'users.id', '=', 'contracts.provider_id')
                ->where('users.id', $userId)
                ->select(
                    'users.*',
                    'users_email_verification.status as email_status',
                    'contracts.status as contract_status',
                    'contracts.date as contract_date'
                )
                ->orderByDesc('contracts.id')
                ->first();

            $userData->card_status = ($userData->id_card_front && $userData->id_card_back) ? 1 : 0;

            if ($userData->contract_date) {
                $userData->contract_date = date('m-d-Y', strtotime($userData->contract_date));
            }

            return $this->sendResponse($userData, 'User Data');
        }
    }
}

    public function check_status(Request $request)
    {
        $doc = auth()->user()->status;
        return $this->sendResponse($doc, 'Doctor status');
    }


}
