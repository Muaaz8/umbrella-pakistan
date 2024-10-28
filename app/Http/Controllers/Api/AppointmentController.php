<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Validator;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use App\Events\RealTimeMessage;
use App\Events\updateQuePatient;
use App\Mail\NewAppointmentDoctorMail;
use App\Mail\NewAppointmentPatientMail;
use App\Mail\RescheduleAppointmentDocotrMail;
use App\Mail\RescheduleAppointmentPatientMail;
use App\Mail\CancelAppointmentAccountantMail;
use App\Mail\CancelAppointmentDoctorMail;
use App\Mail\CancelAppointmentPatientMail;
use App\Mail\EvisitBookMail;
use Auth;
use App\State;
use App\City;
use App\Notification;
use DB;
use App\Symptom;
use Carbon\Carbon;
use App\Session;
use DateTime;
use App\Referal;
use App\Appointment;
use App\User;
use App\Models\TblTransaction;
use App\Prescription;
use App\Cart;
use App\Models\AllProducts;
use App\QuestDataTestCode;
use Mail;
use DateTimeZone;
use App\DoctorSchedule;



class AppointmentController extends BaseController
{
    public function specializations(Request $request){
        if(Auth::check()){
            $user_state = Auth::user()->state_id;
            $registered_state = State::find($user_state);
            $active_states = State::where('active',1)->get();
            $location = State::where('id',$user_state)->first();
            $price = DB::table('specalization_price')->where('state_id',$user_state)->first();
            if($request->location_id !=''){
                $priceLoc = DB::table('specalization_price')->where('state_id',$request->location_id)->first();
                if($priceLoc !=''){
                    $location = State::where('id',$request->location_id)->first();
                    $specializations = DB::table('specializations')
                    ->join('specalization_price', 'specalization_price.spec_id', 'specializations.id')
                    ->where('specalization_price.state_id', $request->location_id)
                    ->where('specializations.status', '1')
                    ->select('specializations.*', 'specalization_price.follow_up_price as follow_up_price', 'specalization_price.initial_price as initial_price')
                    ->get();
                } else{
                    $specializations = 'No available specializations';
                }
            } else {
                if($price != ''){
                    $specializations = DB::table('specializations')
                    ->join('specalization_price', 'specalization_price.spec_id', 'specializations.id')
                    ->where('specalization_price.state_id', $user_state)
                    ->where('specializations.status', '1')
                    ->select('specializations.*', 'specalization_price.follow_up_price as follow_up_price', 'specalization_price.initial_price as initial_price')
                    ->get();
                } else{
                    $specializations = 'No available specializations';
                }
            }
                $specializationsData['code'] = 200;
                $specializationsData['registered_state'] = $registered_state;
                $specializationsData['active_states'] = $active_states;
                $specializationsData['evisit_specializations'] = $specializations;
                return $this->sendResponse($specializationsData,"Specializations found");
        } else{
            $specializationsData['code'] = 200;
            return $this->sendError($specializationsData,"Your are not Login");
        }
    }
    public function book_appointment(Request $request, $id){
        $user = Auth::user();
        $user_state = Auth::user()->state_id;
        $user->ses_id = '';
        $state = DB::table('users')->where('users.id', $user->id)->select('users.state_id')->first();
        $state = $state->state_id;
        $active_states = State::where('active',1)->get();
        $price = DB::table('specalization_price')->where('spec_id', $id)->where('state_id',$user_state)->first();
        $location = State::where('id',$user_state)->first();
        if($id != '21'){
            if($request->location_id !=''){
                $priceLoc = DB::table('specalization_price')->where('spec_id', $id)->where('state_id',$request->location_id)->first();
                if($priceLoc != ''){
                    $doctors = DB::table('doctor_licenses')
                    ->join('users', 'doctor_licenses.doctor_id', 'users.id')
                    ->join('specializations', 'specializations.id', 'users.specialization')
                    ->leftJoin('doctor_schedules',function ($join) {
                        $join->on('doctor_schedules.doctorID', '=' , 'users.id');
                        $join->where('doctor_schedules.title','=','Availability');
                        $join->where('doctor_schedules.end','>',date('Y-m-d H:i:s'));
                    })
                    ->where('doctor_licenses.state_id', $request->location_id)
                    ->where('users.specialization', $id)
                    ->where('users.active', '1')
                    ->where('doctor_licenses.is_verified', '1')
                    ->select('users.*', 'specializations.name as sp_name','doctor_schedules.title')
                    ->groupBy('users.id')
                    ->orderby('doctor_schedules.doctorID','DESC')
                    ->paginate(10);
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
                } else{
                    $doctors ='No Doctors Available in Selected State.';
                }
            } else{
                if($price !=''){
                    $doctors = DB::table('doctor_licenses')
                    ->join('users', 'doctor_licenses.doctor_id', 'users.id')
                    ->join('specializations', 'specializations.id', 'users.specialization')
                    ->leftJoin('doctor_schedules',function ($join) {
                        $join->on('doctor_schedules.doctorID', '=' , 'users.id');
                        $join->where('doctor_schedules.title','=','Availability');
                        $join->where('doctor_schedules.end','>',date('Y-m-d H:i:s'));
                    })
                    ->where('doctor_licenses.state_id', $user_state)
                    ->where('users.specialization', $id)
                    ->where('users.active', '1')
                    ->where('doctor_licenses.is_verified', '1')
                    ->select('users.*', 'specializations.name as sp_name','doctor_schedules.title')
                    ->groupBy('users.id')
                    ->orderby('doctor_schedules.doctorID','DESC')
                    ->paginate(10);
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
                } else{
                    $doctors ='No Doctors Available in Selected State.';
                }
            }
            $appointment['code'] = 200;
            $appointment['user'] = $user;
            $appointment['doctors'] = $doctors;
            $appointment['registered_state'] = $state;
            $appointment['active_states'] = $active_states;
            $appointment['location'] = $location;
            $appointment['price'] = $price;
            $appointment['id'] = $id;
            return $this->sendResponse($appointment,"All Doctors");
        } else{
            $appointment['code'] = 200;
            return $this->sendError($appointment,"No Doctors found");
        }

        // // else{
        // //     $flag = 'appointment';
        // //     return view('dashboard_patient.Evisit.patient_health',compact('user','flag'));
        // // }
        // return view('dashboard_patient.Appointments.book_appointment', compact('doctors', 'session', 'id', 'user'));

    }
    public function patient_health_store(Request $request){
        if(count($request->all()) < 14){
            $formData['code'] = 200;
            return $this->sendError($formData,'Please fill the complete form');
        }else{
            $request->validate([
                'user_name' =>  ['required'],
                'date' =>  ['required'],
                'Question1' =>  ['required'],
                'Question2' =>  ['required'],
                'Question3' =>  ['required'],
                'Question4' =>  ['required'],
                'Question5' =>  ['required'],
                'Question6' =>  ['required'],
                'Question7' =>  ['required'],
                'Question8' =>  ['required'],
                'Question9' =>  ['required'],
                'col_total' =>  ['required'],
                'question10' =>  ['required'],
            ]);
            $flag = $request->flag;
            $pat_health = array(
                'Question1' => $request->Question1,
                'Question2' => $request->Question2,
                'Question3' => $request->Question3,
                'Question4' => $request->Question4,
                'Question5' => $request->Question5,
                'Question6' => $request->Question6,
                'Question7' => $request->Question7,
                'Question8' => $request->Question8,
                'Question9' => $request->Question9,
                'add_columns_totals' => $request->col_total,
                'question10' => $request->question10,
            );
            $pat_health = serialize($pat_health);
            $id = DB::table('psychiatry_form')->insertGetId([
                'user_id' => Auth::user()->id,
                'user_name' => $request->user_name,
                'date' => $request->date,
                'patient_health' => $pat_health,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ]);

            $data['code'] =200;
            $data['id'] =$id;
            $data['flag'] =$flag;
            return $this->sendResponse($data,"mood disorder store");
        }
    }
    public function mood_disorder_store(Request $request){
        $id = $request->id;
        $flag = $request->flag;
        $mood_disorder = array(
            "Question1a" => $request->Question1a,
            "Question1b" => $request->Question1b,
            "Question1c" => $request->Question1c,
            "Question1d" => $request->Question1d,
            "Question1e" => $request->Question1e,
            "Question1f" => $request->Question1f,
            "Question1g" => $request->Question1g,
            "Question1h" => $request->Question1h,
            "Question1i" => $request->Question1i,
            "Question1j" => $request->Question1j,
            "Question1k" => $request->Question1k,
            "Question1l" => $request->Question1l,
            "Question1m" => $request->Question1m,
            "Question2" => $request->Question2,
            "Question3" => $request->Question3,
            "Question4" => $request->Question4,
            "Question5" =>  $request->Question5,
        );
        $mood_disorder = serialize($mood_disorder);
        DB::table('psychiatry_form')->where('id',$id)->update([
            'mood_disorder' => $mood_disorder,
            'updated_at' => NOW(),
        ]);
        $data['code'] =200;
        $data['id'] =$id;
        $data['flag'] =$flag;
        return $this->sendResponse($data,"mood disorder store");
        // return view('dashboard_patient.Evisit.anxiety_scale', compact('id','flag'));
    }
    public function anxiety_scale_store(Request $request){
        $id = $request->id;
        $request->validate([
            "anxiety" => ['required'],
            "tension" => ['required'],
            "fears" => ['required'],
            "insomnia" => ['required'],
            "intellectual" => ['required'],
            "depressed" => ['required'],
            "muscular" => ['required'],
            "sensory" => ['required'],
            "cardio" => ['required'],
            "respiratory" => ['required'],
            "gastro" => ['required'],
            "genitourinary" => ['required'],
            "autonomic" => ['required'],
            "behavior" => ['required'],
        ]);
        $flag = $request->flag;
        $anxiety_scale = array(
            "anxiety" => $request->anxiety,
            "tension" => $request->tension,
            "fears" => $request->fears,
            "insomnia" => $request->insomnia,
            "intellectual" => $request->intellectual,
            "depressed" => $request->depressed,
            "muscular" => $request->muscular,
            "sensory" => $request->sensory,
            "cardio" => $request->cardio,
            "respiratory" => $request->respiratory,
            "gastro" => $request->gastro,
            "genitourinary" => $request->genitourinary,
            "autonomic" => $request->autonomic,
            "behavior" => $request->behavior,
        );

        $anxiety_scale = serialize($anxiety_scale);
        // dd($anxiety_scale);
        DB::table('psychiatry_form')->where('id',$id)->update([
            'anxiety_scale' => $anxiety_scale,
            'updated_at' => NOW(),
        ]);
        // if($flag == 'session'){
        //     return redirect()->route('psych_patient_online_doctors',['id'=>'21']);
        // }
        if($flag == 'appointment'){
            $anxiety_scale_store['code'] = 200;
            $anxiety_scale_store['id'] =21;
            return $this->sendResponse($anxiety_scale_store,"Psychiatrists anxiety scale stored");
        }elseif ($flag == 'evisit') {
            $anxiety_scale_store['code'] = 200;
            $anxiety_scale_store['id'] =21;
            return $this->sendResponse($anxiety_scale_store,"Psychiatrists anxiety scale stored");
        } else{
            $anxiety_scale_store['code'] = 200;
            return $this->sendError($anxiety_scale_store,"Something Went Wrong");
        }
    }

    public function search_doctor(Request $request){
        $user = Auth::user();
        $user->ses_id = '';
        $state = DB::table('users')->where('users.id', $user->id)->select('users.state_id')->first();
        $state = $state->state_id;
        $doctors = DB::table('doctor_licenses')
            ->join('users', 'doctor_licenses.doctor_id', 'users.id')
            ->join('specializations', 'specializations.id', 'users.specialization')
            ->where('doctor_licenses.state_id', $state)
            ->where('users.specialization','!=',21)
            ->where('users.active', '1')
            ->where('doctor_licenses.is_verified', '1')
            ->where('users.name','LIKE','%'. $request->name . '%')
            ->select('users.*', 'specializations.name as sp_name')
            ->get();
        if(!$doctors->isEmpty()){
            $doctorData['code'] = 200;
            $doctorData['doctors'] = $doctors;
            return $this->sendResponse($doctorData,"doctor found");
        } else{
            $doctorData['code'] = 200;
            return $this->sendError($doctorData,"No doctor found!");
        }
    }
    public function doctor_schedule($id){
        $nowDate = Carbon::now();
        $query = DB::table('doctor_schedules')->where('doctorID',$id)->whereDate('date', '>=',$nowDate)->select('date','slotStartTime','slotEndTime')->get();
        if(!$query->isEmpty()){
            $docSchedule['code'] =200;
            $docSchedule['query'] =$query;
            return $this->sendResponse($docSchedule,"Doctor Schedule date and time");
        } else{
            $docScheduleError['code'] =200;
            return $this->sendError($docScheduleError,"No Schedule found for doctor ID ");
        }
    }
    public function get_doctor_date($id){
        $user_id =Auth::user()->id;
        $nowDate = date('Y-m-d H:i:s');
        $dates = DoctorSchedule::where('doctorID', $id)->where('title', 'Availability')->where('start', '>=', $nowDate)->orderBy('date', 'asc')->get();
        if ($dates != '[]') {
            foreach ($dates as $date) {
                $date->date = User::convert_utc_to_user_timezone($user_id, $date->start)['date'];
                $date->start = User::convert_utc_to_user_timezone($user_id, $date->start)['datetime'];
                $date->end = User::convert_utc_to_user_timezone($user_id, $date->end)['datetime'];
            }
            $data['code'] = 200;
            $data['dates'] = $dates;
            return $this->sendResponse($data,"date available");
        } else {
            $data['code'] = 200;
            return $this->sendResponse($data,"No date available");
        }

    }
    // public function get_doctor_time_slots($date){
    //     $nowDate = Carbon::now();
    //     $slots = DB::table('doctor_schedules')->where('date',$date)->select('date','slotStartTime','slotEndTime')->get();

    //     return $this->sendResponse($slots,"Doctor Schedule slots based on date");
    // }
    public function get_doctor_time_slots(Request $request){
        $doc_id =$request->doctor_id;
        $user = Auth::user();
        $d = explode(' ',$request->date);
        $utc_start_datetime = User::convert_user_timezone_to_utc($user->id,$request->date);
        $user_current_datetime = User::convert_utc_to_user_timezone($user->id,date('Y-m-d H:i:s'));
        $docID = $doc_id;
        $doc_avi="Availability";
        $temp = new DateTime(Carbon::now()->format('Y-m-d H:i:s'));
        $temp->setTimezone(new DateTimeZone($user->timeZone));
        $temp = $temp->format('H:i:s');
        $sdate = date('Y-m-d',strtotime($request->date));
        $newDatee=date('Y-m-d H:i:s',strtotime($utc_start_datetime['datetime']));
        $current_date_time = date('Y-m-d H:i:s');
        $current_time = date('H:i:s');
        $time_check = date('H:i:s',strtotime('+20 minutes' ,strtotime($current_time)));
        if($sdate== date('Y-m-d')){
            $docTimings= DB::table('doctor_schedules')
                ->select('doctor_schedules.*')
                ->where('start', $newDatee)
                ->where('doctorID',$docID)
                ->where('title',$doc_avi)
                ->get();
            foreach($docTimings as $key=>$doc){
                $checking =  User::convert_utc_to_user_timezone($doc->doctorID,$doc->end)['time'];
                if($checking<=$user_current_datetime['time']){
                    $doc=null;
                }
            }
        }
        else{
            $docTimings= DB::table('doctor_schedules')
                ->select('doctor_schedules.*')
                ->where('start', $newDatee)
                ->where('doctorID',$docID)
                ->where('title',$doc_avi)
                ->get();
        }
        $bookedSlots=Appointment::where('date',$sdate)->where('doctor_id',$docID)->get();
        $time = [];
        foreach($docTimings as $docTiming){
            $current_date = User::convert_utc_to_user_timezone($docTiming->doctorID,$current_date_time)['date'];
            $interval=15;
            $break=5;
            $start = User::convert_utc_to_user_timezone($docTiming->doctorID,$docTiming->start)['datetime'];
            $end = User::convert_utc_to_user_timezone($docTiming->doctorID,$docTiming->end)['datetime'];
            $startTime = date('H:i:s',strtotime($start));
            $endTime = date('H:i:s',strtotime($end));
            $current_time = User::convert_utc_to_user_timezone($docTiming->doctorID,date('Y-m-d H:i:s'))['time'];
            $current_time = date('H:i:s',strtotime($user_current_datetime['time']));
            $i=0;
            while(strtotime($startTime) <= strtotime($endTime)){
                $start = $startTime;
                $end = date('H:i:s',strtotime('+'.$interval.' minutes',strtotime($startTime)));
                $startTime = date('H:i:s',strtotime('+'.$interval+$break.' minutes',strtotime($startTime)));
                $i++;
                if(strtotime($startTime) <= strtotime($endTime) && date('m-d-Y',strtotime($sdate)) == $user_current_datetime['date']){
                    if(strtotime($start) >= strtotime($current_time)){
                        $t_start=date('h:i A',strtotime($start));
                        $t_end=date('h:i A',strtotime($end));
                        array_push($time,array('start'=>$start,'end'=>$end,'t_start'=>$t_start,'t_end'=>$t_end));
                    }
                }
                else if(strtotime($startTime) <= strtotime($endTime))
                {
                    $t_start=date('h:i A',strtotime($start));
                    $t_end=date('h:i A',strtotime($end));
                    array_push($time,array('start'=>$start,'end'=>$end,'t_start'=>$t_start,'t_end'=>$t_end));
                }
            }
            foreach($time as $key=>$value)
            {
                if($bookedSlots!=null)
                {
                    foreach($bookedSlots as $slot)
                    {
                        $time_check = User::convert_utc_to_user_timezone($docID,$slot->date.' '.$slot->time)['datetime'];
                        $time_check = date('H:i:s',strtotime($time_check));
                        if($time_check==$value['start'] && $slot->status=="pending")
                        {
                            unset($time[$key]);
                        }
                    }
                }
            }
            $time = array_values($time);
        }
        $timingArray=[];
        foreach ($time as $timing) {
            $start = User::convert_user_timezone_to_utc($docID,$timing['start'])['time'];
            $end = User::convert_user_timezone_to_utc($docID,$timing['end'])['time'];
            $start = User::convert_utc_to_user_timezone($user->id,$start)['time'];
            $end = User::convert_utc_to_user_timezone($user->id,$end)['time'];
            $t_start = $start;
            $t_end = $end;
            $start = date('H:i:s',strtotime($start));
            $end = date('H:i:s',strtotime($end));
            $checkArray=['start'=>$start,'end'=>$end,'t_start'=>$t_start,'t_end'=>$t_end];
            $tim =array_push($timingArray,$checkArray);
        }
        // return response()->json(['data'=>$timingArray]);
        $available_slots['code'] = 200;
        $available_slots['timingArray'] = $timingArray;
        return $this->sendResponse($available_slots,"Doctor Available slotes based on date");
    }
    public function create_appointment(Request $request){
        $user = Auth::user();
        $firstname = $user->name;
        $lastname = $user->last_name;
        $email = $user->email;
        $phone = $user->phone_number;
        $location_id = $request->location_id;
        $data = $request->validate([
            'provider' =>  ['required', 'string', 'max:255'],
            'problem' => ['required'],
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
        $problems = '';
        for ($i = 0; $i < count($data['problem']); $i++) {
            $problems = $problems . $data['problem'][$i] . ',' ;
        }
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
            'patient_name' =>  $firstname . " " . $lastname,
            'doctor_name' => $provider_name,
            'email' => $email,
            'phone' => $phone,
            'problem' => $problems,
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
        $temp = $problems;
        $symp = Symptom::create([
            'patient_id' => $patient_id,
            'doctor_id' => $pro_id,
            'headache' => 0,
            'flu' => 0,
            'fever' => 0,
            'nausea' => 0,
            'others' => 0,
            'symptoms_text' => $temp,
            'description' => 'NaN',
            'status' => 'pending'
        ]);
        $symp->save();
        $temp = explode(',',$temp);
        array_pop($temp);
        $sym = \App\Http\Controllers\IsabelController::proquery($temp);
        if($sym != "Invalid Authentication."){
            $pro_sym = \App\Http\Controllers\IsabelController::getsymptoms($sym,$request->Pregnancy);
            $isabel_symp_id = DB::table('isabel_session_diagnosis')->insertGetId([
                'triage_api_url' => $pro_sym->triage_api_url,
                'diagnoses' => serialize($pro_sym->diagnoses),
            ]);
        }else{
            // dd('tes');
            $isabel_symp_id = 0;
        }
        // $pro_sym = \App\Http\Controllers\IsabelController::getsymptoms($sym,'n');
        // $isabel_symp_id = DB::table('isabel_session_diagnosis')->insertGetId([
        //     'triage_api_url' => $pro_sym->triage_api_url,
        //     'diagnoses' => serialize($pro_sym->diagnoses),
        // ]);
        $check_session_already_have = DB::table('sessions')
            ->where('doctor_id', $pro_id)
            ->where('patient_id', $patient_id)
            ->where('specialization_id', $request->spec_id)
            ->count();
        $session_price = "";
        if ($check_session_already_have > 0) {
            $session_price_get = DB::table('specalization_price')->where('spec_id', $request->spec_id)->where('state_id', auth()->user()->state_id)->first();
            if ($session_price_get->follow_up_price != null) {
                $session_price = $session_price_get->follow_up_price;
            } else {
                $session_price = $session_price_get->initial_price;
            }
        } else {
            $session_price_get = DB::table('specalization_price')->where('spec_id', $request->spec_id)->where('state_id', auth()->user()->state_id)->first();
            $session_price = $session_price_get->initial_price;
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
            $session_id = Session::create([
                'patient_id' =>  $patient_id,
                'appointment_id' =>  $app_id,
                'doctor_id' =>  $pro_id,
                'date' => date('Y-m-d', (strtotime($datetime['datetime']))),
                'status' => 'pending',
                'queue' => 0,
                'symptom_id' => $symp->id,
                'remaining_time' => 'full',
                'channel' => $channelName,
                'join_enable' => null,
                'created_at' => $sessiondate,
                'updated_at' => $sessiondate,
                'specialization_id' => $request->spec_id,
                'price' => $session_price,
                'session_id' => $new_session_id,
                'location_id' => $location_id,
                'isabel_diagnosis_id' => $isabel_symp_id,
                'validation_status' => "valid",
                'start_time' => date('Y-m-d H:i:s', (strtotime($datetime['datetime']))),
                'end_time' => date('Y-m-d H:i:s', (strtotime('15 min', strtotime($datetime['datetime'])))),
            ])->id;
            if($session_id){
                $appointmentCreated['code'] =200;
                $appointmentCreated['session_id'] =$session_id;
                return $this->sendResponse($appointmentCreated,"Session created");
            } else{
                $appointmentCreated['code'] =200;
                return $this->sendError($appointmentCreated,"Session Not Created");
            }
    }
    public function appointment_payment($session_id){
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
        $price = DB::table('sessions')->where('id', $session_id)->first();
        $price = $price->price;
        $cards = DB::table('card_details')->where('user_id', $id)->get();
        $payment_data['code'] = 200;
        $payment_data['years'] = $years;
        $payment_data['session_id'] = $session_id;
        $payment_data['states'] = $states;
        $payment_data['session_data'] = $session_data;
        $payment_data['cards'] = $cards;
        $payment_data['price'] = $price;
        return $this->SendResponse($payment_data,"appointment Payment");
    }
    public function payment_old_cards_apppointment(Request $request){
        $id = auth()->user()->id;
        $session_id = $request->session_id;
        $getSession = DB::table('sessions')->where('id', $request->session_id)->first();
            $query = DB::table('card_details')
                ->where('id', $request->card_id)
                ->get();
                // dd($getSession);
            $pay = new \App\Http\Controllers\PaymentController();
            $profile = $query[0]->customerProfileId;
            $payment = $query[0]->customerPaymentProfileId;
            $amount = $request->amount_charge;
            $response = ($pay->new_createPaymentwithCustomerProfile($amount, $profile, $payment));
            $flag = false;
        if($response['messages']['message'][0]['text'] == 'Successful.') {
        Session::where('id', $request->session_id)->update(['status' => 'paid']);
            try {
                $doctor_data = DB::table('users')->where('id', $getSession->doctor_id)->first();
                $patient_data = DB::table('users')->where('id', $getSession->patient_id)->first();
                $getAppointment = DB::table('appointments')->where('id', $getSession->appointment_id)->first();
                $d_date = User::convert_utc_to_user_timezone($doctor_data->id, $getAppointment->date . ' ' . $getAppointment->time);
                $p_date = User::convert_utc_to_user_timezone($patient_data->id, $getAppointment->date . ' ' . $getAppointment->time);
                $markDownData1 = [
                    'doc_name' => ucwords($doctor_data->name),
                    'pat_name' => ucwords($patient_data->name),
                    'time' => $p_date['time'],
                    'date' => $p_date['date'],
                    'pat_mail' => $patient_data->email,
                    'doc_mail' => $doctor_data->email,
                ];
                $markDownData2 = [
                    'doc_name' => ucwords($doctor_data->name),
                    'pat_name' => ucwords($patient_data->name),
                    'time' => $d_date['time'],
                    'date' => $d_date['date'],
                    'pat_mail' => $patient_data->email,
                    'doc_mail' => $doctor_data->email,
                ];
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
                    'session_id' => "null",
                    'received' => 'false',
                    'refill_id' => 'null',
                ];
                $getSession->received = false;
                \App\Helper::firebase($getSession->doctor_id,'notification',$notification_id->id,$data);
                \App\Helper::firebase($getSession->doctor_id,'updateQuePatient',$session_id,$getSession);
                event(new RealTimeMessage($getSession->doctor_id));
                event(new updateQuePatient('update patient que'));
            } catch (Exception $e) {
                Log::error($e);
            }
            $paymentOld['code'] = 200;
            return $this->sendResponse($paymentOld, "Appointment Create Successfully");
        } else{
            $code = $response['messages']['message'][0]['code'];
            $message = TblTransaction::errorCode($code);
            DB::table('error_log')->insert([
                'user_id' => $id,
                'Error_code' => $code,
                'Error_text' => $response['messages']['message'][0]['text'],
            ]);
            $paymentOld['code'] = 200;
            $paymentOld['message'] = $message;
            return $this->sendError($paymentOld, "Something Went Wrong");
        }
    }
    public function create_new_card_apppointment(Request $request){
        $id = auth()->user()->id;
        $getSession = DB::table('sessions')->where('id', $request->session_id)->first();
        $validator = Validator::make($request->all(), [
            'card_holder_name' => 'required',
            'card_holder_last_name' => 'required',
            'card_num' => 'required|integer|min:16',
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
        if($validator->fails()) {
            $errors['code'] =200;
            $errors['validation'] = $validator->errors();
            return $this->sendError($errors,'Validation error');
        }
        // $name = $request->card_holder_name . $request->card_holder_name_middle;
        // $city = City::find($request->city)->name;
        // $state = State::find($request->state)->name;

        $getSession = DB::table('sessions')->where('id', $request->session_id)->first();
        $name = $request->card_holder_name . $request->card_holder_name_middle;
        $city = City::find($request->city)->name;
        $state = State::find($request->state)->name;

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

        $pay = new \App\Http\Controllers\PaymentController();
        $response = ($pay->new_createCustomerProfile($input));
        // print_r($response); die();
        $flag = true;
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
                    "phoneNumber" => $request->phoneNumber
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
            try {
                $doctor_data = DB::table('users')->where('id', $getSession->doctor_id)->first();
                $patient_data = DB::table('users')->where('id', $getSession->patient_id)->first();
                $getAppointment = DB::table('appointments')->where('id', $getSession->appointment_id)->first();
                $d_date = User::convert_utc_to_user_timezone($doctor_data->id, $getAppointment->date . ' ' . $getAppointment->time);
                $p_date = User::convert_utc_to_user_timezone($patient_data->id, $getAppointment->date . ' ' . $getAppointment->time);

                $markDownData1 = [
                    'doc_name' => ucwords($doctor_data->name),
                    'pat_name' => ucwords($patient_data->name),
                    'time' => $p_date['time'],
                    'date' => $p_date['date'],
                    'pat_mail' => $patient_data->email,
                    'doc_mail' => $doctor_data->email,
                ];
                $markDownData2 = [
                    'doc_name' => ucwords($doctor_data->name),
                    'pat_name' => ucwords($patient_data->name),
                    'time' => $d_date['time'],
                    'date' => $d_date['date'],
                    'pat_mail' => $patient_data->email,
                    'doc_mail' => $doctor_data->email,
                ];
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
                    'session_id' => "null",
                    'received' => 'false',
                    'refill_id' => 'null',
                ];
                $getSession->received = false;
                \App\Helper::firebase($getSession->doctor_id,'notification',$notification_id->id,$data);
                \App\Helper::firebase($getSession->doctor_id,'updateQuePatient',$request->session_id,$getSession);

                event(new RealTimeMessage($getSession->doctor_id));
                event(new updateQuePatient('update patient que'));
            } catch (Exception $e) {
                Log::error($e);
            }
            $card_created['code'] = 200;
            return $this->sendResponse($card_created, "Appointment Create Successfully");
        } else {
            $code = $response['messages']['message'][0]['code'];
            $message = TblTransaction::errorCode($code);
            DB::table('error_log')->insert([
                'user_id' => $id,
                'Error_code' => $code,
                'Error_text' => $response['messages']['message'][0]['text'],
            ]);
            $card_created['code'] = 200;
            $card_created['session_id'] =  $request->session_id;
            return $this->sendError($card_created, "Error Message");
            // return redirect()->route('appoint_payment', ['id' => $request->session_id])->with('error_message', $message);
        }
    }
    public function all_appointment(){
        $user = Auth::user();
        $today = date('Y-m-d');
        $todayTime = date('h:i A');
        $user_state = $user->state_id;
        $state = State::find($user_state);
        if ($state->active == 1) {
            $appointments = DB::table('appointments')
                ->join('users', 'appointments.doctor_id', 'users.id')
                ->join('sessions', 'appointments.id', 'sessions.appointment_id')
                ->where('appointments.patient_id', $user->id)
                ->where('sessions.status','!=','pending')
                ->orderBy('appointments.created_at', 'DESC')
                ->select('appointments.*', 'sessions.id as sesssion_id', 'sessions.que_message as msg', 'sessions.join_enable', 'users.specialization as spec_id')
                ->paginate(20);
            foreach ($appointments as $app) {
                $ddd = date('Y-m-d H:i:s', strtotime("$app->date $app->time"));
                $app->time = User::convert_utc_to_user_timezone($user->id, $ddd)['time'];
                $app->date = User::convert_utc_to_user_timezone($user->id, $ddd)['date'];
            }
            $appoint['code'] = 200;
            $appoint['appointments'] = $appointments;
            return $this->sendResponse($appoint,"All Appointments");
        } else {
            $appoint['code'] = 200;
            return $this->sendError($appoint,"No Appointments");
        }
    }
    public function appointment_search(Request $request){
        $search = $request->doctor_name;
        $user = Auth::user();
        $today = date('Y-m-d');
        $todayTime = date('h:i A');
        $user_state = $user->state_id;
        $state = State::find($user_state);
        if ($state->active == 1) {
            $appointments = DB::table('appointments')
                ->join('users', 'appointments.doctor_id', 'users.id')
                ->join('sessions', 'appointments.id', 'sessions.appointment_id')
                ->where('appointments.patient_id', $user->id)
                ->where('users.name', $search)
                ->where('sessions.status','!=','pending')
                ->orderBy('appointments.created_at', 'DESC')
                ->select('appointments.*', 'sessions.id as sesssion_id', 'sessions.que_message as msg', 'sessions.join_enable', 'users.specialization as spec_id')
                ->paginate(20);
            foreach ($appointments as $app) {
                $ddd = date('Y-m-d H:i:s', strtotime("$app->date $app->time"));
                $app->time = User::convert_utc_to_user_timezone($user->id, $ddd)['time'];
                $app->date = User::convert_utc_to_user_timezone($user->id, $ddd)['date'];
            }
            $appoint['code'] = 200;
            $appoint['appointments'] = $appointments;
            return $this->sendResponse($appoint,"All Appointments");
        } else {
            $appoint['code'] = 200;
            return $this->sendError($appoint,"No Appointments");
        }
    }
    public function patient_appointment_cancel(Request $request){
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
                'session_id' => "null",
                'received' => 'false',
                'refill_id' => 'null',
            ];
            \App\Helper::firebase($getAppointment->patient_id,'notification',$notification_id->id,$data);
            event(new RealTimeMessage($getAppointment->patient_id));
        } catch (Exception $e) {
            Log::error($e);
        }
        $appointmentcancle['code'] =200;
        return $this->sendResponse($appointmentcancle,"Appointment Successfully Cancelled");
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
