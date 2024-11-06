<?php

namespace App\Http\Controllers\Api\DoctorsApi;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use App\DoctorSchedule;
use App\Holiday;
use App\Notification;
use App\Appointment;
use App\Events\RealTimeMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Events\updateQuePatient;
use Carbon\Carbon;
use App\User;
use DateTime;
use DateTimeZone;
use Validator;
use App\State;
use App\Session;

class DoctorScheduleController extends BaseController
{
    public function doctor_schedule_list(){
        $user=Auth::user();
        if($user->user_type=='doctor'){
            $date = date('Y-m-d H:i:s');
            $date = User::convert_utc_to_user_timezone($user->id,$date)['datetime'];
            $time = date('H:i:s',strtotime($date));
            $date = date('Y-m-d',strtotime($date));
            $schedule = DoctorSchedule::where('doctorID', $user->id)->where('date','>=',$date)->orderBy('id','desc')->paginate(10);
            $appointments_count = [];
            foreach($schedule as $sc){
                $sc->appointments = DB::table('appointments')
                ->join('sessions','appointments.id','sessions.appointment_id')
                ->where('appointments.doctor_id',$user->id)
                ->where('appointments.date',$sc->date)
                ->where('appointments.status','pending')
                ->where('sessions.status','paid')
                ->orderBy('appointments.time')
                ->select('appointments.*')->get();
                foreach($sc->appointments as $app){
                    $app->appointments_count = DB::table('appointments')
                    ->join('sessions', 'appointments.id', 'sessions.appointment_id')
                    ->where('appointments.doctor_id', $user->id)
                    ->where('appointments.id',$app->id)
                    ->where('sessions.status','=','paid')
                    ->orderBy('appointments.created_at', 'DESC')
                    ->count();
                }
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
                $sc->schedule_date =$date;
            }

            $scheduleData['code']= 200;
            $scheduleData['schedule']= $schedule;
            // $scheduleData['appointments_count']=
            if($scheduleData != ''){
                return $this->sendResponse($scheduleData,"Doctor Scheduled List");
            } else{
                $scheduleDataError['code']= 200;
                return $this->sendError($scheduleDataError, "No list Found");
            }
        }
        else{
            $scheduleDataError['code']= 200;
            return $this->sendError($scheduleDataError,"Opps! somthing Went Wrong");
        }
    }
    public function schedule_check(Request $request){
        $user=Auth::user();
        $datetime = User::convert_user_timezone_to_utc($user->id,$request['date'].' '.$request['time']);
        $from = date('Y-m-d', strtotime('-1 day', strtotime($request['date'])));
        $to = date('Y-m-d', strtotime('+1 day', strtotime($request['date'])));
        $schedule = DoctorSchedule::where('doctorID', $user->id)->where('id','!=',$request['id'])
        ->whereBetween('date',[$from,$to])->get();
        $data='';
        foreach($schedule as $sch)
        {
            $sch->start = User::convert_utc_to_user_timezone($user->id,$sch->start);
            $sch->end = User::convert_utc_to_user_timezone($user->id,$sch->end);
            $sch->slotStartTime = date('H:i:s',strtotime($sch->start['time']));
            $sch->slotEndTime = date('H:i:s',strtotime($sch->end['time']));
            if(date('Y-m-d',strtotime($sch->start['datetime'])) == $request['date'])
            {
                if($request['time']<=$sch->slotStartTime && $request['etime']>=$sch->slotEndTime && $sch->title=='Availability')
                {
                    $data = '1';
                    $scheduleCheck['code'] = 200;
                    $scheduleCheck['schedule_check'] = $data;
                    return $this->sendError($scheduleCheck,"Schedule already exist in between date time");
                }
                elseif($request['time']<=$sch->slotStartTime && $request['etime']>=$sch->slotStartTime && $sch->title=='Therapy')
                {
                    $data = '1';
                    $scheduleCheck['code'] = 200;
                    $scheduleCheck['schedule_check'] = $data;
                    return $this->sendError($scheduleCheck,"Schedule already exist in between date time");
                }
            }

        }
        $c_datetime = date('Y-m-d H:i:s');
        $c_time = User::convert_utc_to_user_timezone($user->id,$c_datetime)['datetime'];
        $c_time = date('H:i:s',strtotime($c_time));
        $c_date = User::convert_utc_to_user_timezone($user->id,$c_datetime)['datetime'];
        $c_date = date('Y-m-d',strtotime($c_date));
        $data = $c_date.' '.$c_time;
        $schedule['code'] = 200;
        $schedule['shedule_data'] = $data;
        return $this->sendResponse($schedule,"You can create schedule");

    }
    public function get_schedule_slots(Request $request){
        $user = auth()->user();
        $from = date('Y-m-d', strtotime('-1 day', strtotime($request->date)));
        $to = date('Y-m-d', strtotime('+1 day', strtotime($request->date)));
        $schedules = DB::table('doctor_schedules')
        ->where('doctorID',$user->id)->where('id','!=',$request->id)
        ->whereBetween('date',[$from,$to])->get();
        $date = User::convert_utc_to_user_timezone($user->id,date('Y-m-d H:i:s'))['datetime'];
        $todaytime = date('H:i:s',strtotime($date));
        $date = date('Y-m-d',strtotime($date));
        $slots = [];
        $time = '00:00:00';

        for($i=0;$i<48;$i++)
        {
            $count = 0;
            if($date == $request->date)
            {
                if($time < $todaytime)
                {
                    $count=1;
                }
            }
            if($count==0)
            {
                foreach($schedules as $sch)
                {
                    $startdatetime = User::convert_utc_to_user_timezone($user->id,$sch->start);
                    $starttime = date('H:i:s',strtotime($startdatetime['time']));
                    $enddatetime = User::convert_utc_to_user_timezone($user->id,$sch->end);
                    $endtime = date('H:i:s',strtotime($enddatetime['time']));
                    if(date('Y-m-d',strtotime($startdatetime['datetime'])) == $request->date)
                    {
                        if($time>$starttime && $time<$endtime && $sch->title=='Availability')
                        {
                            $count=1;
                        }
                        elseif($time==$starttime && $sch->title=='Therapy')
                        {
                            $count=1;
                        }
                    }
                }
            }
            if($count==0)
            {
                array_push($slots,['time'=>date('h:i A',strtotime($time))]);
            }
            $time = date('H:i:s',strtotime('+30 minutes',strtotime($time)));
        }
        $getSlots['code'] = 200;
        $getSlots['slots'] = $slots;
        return $this->sendResponse($getSlots,'Time slots');
    }
    public function schedule_create(Request $request){
        $validator = Validator::make($request->all(), [
            'date' => 'required',
            'startTime' => 'required',
            'endTime' => 'required',
        ]);
        if($validator->fails()) {
            $validation['code'] = 200;
            $validation['validation_error'] = $validator->errors();
            return $this->sendError($validation,'Validation error');
        } else{
            $doctorID=Auth::user()->id;
            // $AvailabilityStart = $request->date.' '.$request->startTime;
            $AvailabilityStartUser = $request->date.' '.$request->startTime;
            $AvailabilityStart = User::convert_user_timezone_to_utc($doctorID,$AvailabilityStartUser)['datetime'];
            $AvailabilityEndUser = $request->date.' '.$request->endTime;
            $AvailabilityEnd = User::convert_user_timezone_to_utc($doctorID,$AvailabilityEndUser)['datetime'];
            if($AvailabilityStart != null && $AvailabilityEnd != null){
                if($AvailabilityStartUser<$AvailabilityStart)
                {
                    $title = 'Availability';
                    $start =  date('H:i:s',strtotime($AvailabilityStart));
                    $end =  date('H:i:s',strtotime($AvailabilityEnd));
                    $date = date('Y-m-d',strtotime($AvailabilityStart));
                    $startdate = date('Y-m-d H:i:s',strtotime($AvailabilityStart));
                    $enddate = date('Y-m-d H:i:s',strtotime($AvailabilityEnd));
                    $color = '#008000';
                    if($title !=null && $start !=null && $end !=null && $color !=null){
                        $query=DB::table('doctor_schedules')->insert(
                            ['title' => $title, 'start' => $startdate,'end'=>$enddate,'color'=>$color,'slotStartTime'=>$start,'slotEndTime'=>$end,'doctorID'=>$doctorID,'date'=>$date]
                        );
                        if($query==1){
                            $create_Shedule['code'] = 200;
                            $create_Shedule['schedule_created'] =$query;
                            return $this->sendResponse($create_Shedule,"Schedule Created!");
                        }
                    }
                } else{
                    $title = 'Availability';
                    $start =  date('H:i:s',strtotime($AvailabilityStart));
                    $end =  date('H:i:s',strtotime($AvailabilityEnd));
                    $date = date('Y-m-d',strtotime($AvailabilityEnd));
                    $startdate = date('Y-m-d H:i:s',strtotime($AvailabilityStart));
                    $enddate = date('Y-m-d H:i:s',strtotime($AvailabilityEnd));
                    $color = '#008000';
                    if($title !=null && $start !=null && $end !=null && $color !=null){
                        $query=DB::table('doctor_schedules')->insert(
                            ['title' => $title, 'start' => $startdate,'end'=>$enddate,'color'=>$color,'slotStartTime'=>$start,'slotEndTime'=>$end,'doctorID'=>$doctorID,'date'=>$date]
                        );
                        if($query==1){
                            $create_Shedule['code'] = 200;
                            $create_Shedule['schedule_created'] =$query;
                            return $this->sendResponse($create_Shedule,"Schedule Created!");
                        }
                    }
                }
            }
            $create_SheduleError['code'] = 200;
            return $this->sendError($create_SheduleError,"Schedule Not Created!");
        }
    }

    public function edit_schedule($id){
        // dd($id);
        $user = Auth::user();
        $date = date('Y-m-d H:i:s');
        $date = User::convert_utc_to_user_timezone($user->id,$date)['datetime'];
        $time = date('H:i:s',strtotime($date));
        $date = date('Y-m-d',strtotime($date));
        $schedule = DoctorSchedule::where('id',$id)->where('doctorID', $user->id)->where('date','>=',$date)->orderBy('id','desc')->get();
        $data ='';
        foreach($schedule as $sc){
           $data = $sc->appointments = DB::table('appointments')
            ->join('sessions','appointments.id','sessions.appointment_id')
            ->where('appointments.doctor_id',$user->id)
            ->where('appointments.date',$sc->date)
            ->where('appointments.status','pending')
            ->where('sessions.status','paid')
            ->orderBy('appointments.time')
            ->select('appointments.*')->count();
        }
       if($data == 0){
            $scheduleData['code'] = 200;
            $scheduleData['doc_shedule'] = DoctorSchedule::find($id);
        return $this->sendResponse($scheduleData,"Schedule info!");
       } else{
            $scheduleError['code'] = 200;
            return $this->sendResponse($scheduleError,"Sorry! You Can't Edit This schedule, Appointment found!");
       }
        $scheduleError['code'] = 200;
        return $this->sendError($scheduleError,"Opps! Somthing Went Wrong");
    }
    public function update_schedule(Request $request , $id){
        $schedule = DoctorSchedule::find($id);
        $user = Auth::user();
        $date = date('Y-m-d H:i:s');
        $date = User::convert_utc_to_user_timezone($user->id,$date)['datetime'];
        $time = date('H:i:s',strtotime($date));
        $date = date('Y-m-d',strtotime($date));

        $doctorID=Auth::user()->id;
            $AvailabilityStart = $request->date.' '.$request->startTime;
            $AvailabilityStart = User::convert_user_timezone_to_utc($doctorID,$AvailabilityStart)['datetime'];
            $AvailabilityEnd = $request->date.' '.$request->endTime;
            $AvailabilityEnd = User::convert_user_timezone_to_utc($doctorID,$AvailabilityEnd)['datetime'];

        if($AvailabilityStart != null && $AvailabilityEnd != null){
            $title = 'Availability';
            $start =  date('H:i:s',strtotime($AvailabilityStart));
            $end =  date('H:i:s',strtotime($AvailabilityEnd));
            $date = date('Y-m-d',strtotime($AvailabilityStart));
            $startdate = date('Y-m-d H:i:s',strtotime($AvailabilityStart));
            $enddate = date('Y-m-d H:i:s',strtotime($AvailabilityEnd));
            $color = '#008000';
            if($title !=null && $start !=null && $end !=null && $color !=null){
            $schedule->update([
                'title' => $title,
                 'start' => $startdate,
                 'end'=>$enddate,
                 'color'=>$color,
                 'slotStartTime'=>$start,
                 'slotEndTime'=>$end,
                //  'doctorID'=>$doctorID,
                 'date'=>$date
            ]);
            $scheduledata['code'] = 200;
            $scheduledata['schedule'] = $schedule;
            return $this->sendResponse($scheduledata,"Schedule Updated Successfully!");
            }
        } else{
            $scheduledataError['code'] = 200;
            return $this->sendError($scheduledataError, "Opss! Somthing Went Wrong!");
        }
    }
    public function delete_schedule($id){
        $user = Auth::user();
        $date = date('Y-m-d H:i:s');
        $date = User::convert_utc_to_user_timezone($user->id,$date)['datetime'];
        $time = date('H:i:s',strtotime($date));
        $date = date('Y-m-d',strtotime($date));

        $schedule = DoctorSchedule::where('id',$id)->where('doctorID', $user->id)->where('date','>=',$date)->orderBy('id','desc')->get();
        $data ='';

        foreach($schedule as $sc){
           $data = $sc->appointments = DB::table('appointments')
            ->join('sessions','appointments.id','sessions.appointment_id')
            ->where('appointments.doctor_id',$user->id)
            ->where('appointments.date',$sc->date)
            ->where('appointments.status','pending')
            ->where('sessions.status','paid')
            ->orderBy('appointments.time')
            ->select('appointments.*')->count();
        }
       if($data == 0){
        $scheduleDel = DoctorSchedule::find($id);
        $scheduleDel->delete();
        $scheduleDelData['code'] = 200;
        $scheduleDelData['id'] = $id;
        return $this->sendResponse($scheduleDelData,"Schedule Deleted Successfully!");
       } else{
        $scheduleDelDataError['code'] = 200;
            return $this->sendResponse($scheduleDelDataError,"Sorry! You Can't delete This schedule, Appointment found!");
       }
        $scheduleDelDataError['code'] = 200;
        return $this->sendError($scheduleDelDataError,"Opps! Somthing Went Wrong");
    }
    public function doctor_patient_queue(){
        //
        $user = auth()->user();
        $user_state = $user->state_id;
        $state = State::find($user_state);
        if ($state->active == 1) {
            $doc_id = $user->id;
            $doc_zone = $user->timeZone;
            $nowDate = Carbon::now()->timezone($doc_zone)->format('Y-m-d');
            $appointments = DB::table('appointments')
                ->join('sessions', 'appointments.id', 'sessions.appointment_id')
                ->where('appointments.date', '=', $nowDate)
                ->where('appointments.status', '=', 'pending')
                ->where('sessions.doctor_id', '=', $doc_id)
                ->select('appointments.time', 'appointments.date', 'appointments.id as appointment_id')
                ->first();
            if ($appointments == null) {
                $data_sessions = Session::where('doctor_id', $doc_id)
                    ->where('status', 'invitation sent')
                    ->groupBy('patient_id')
                    ->orderBy('queue', 'ASC')
                    ->select('id as session_id', 'patient_id', 'appointment_id')
                    ->get();
                $sessions = [];
                $mints = 5;
                foreach ($data_sessions as $single_session) {
                    Session::where('id', $single_session['session_id'])->update(['que_message' => 'Your Doctor Will Be Available In Approximately ' . $mints . ' Mints', 'join_enable' => '1']);
                    $firebaseSession =Session::where('id', $single_session['session_id'])->first();
                    $mints += 10;
                    $pat = DB::table('users')->where('id', $single_session['patient_id'])->select('name', 'last_name', 'user_image')->first();
                    $pat->user_image = \App\Helper::check_bucket_files_url($pat->user_image);
                    $patient_full_name = $pat->name . " " . $pat->last_name;
                    $single_session['patient_full_name'] = $patient_full_name;
                    $single_session['user_image'] = $pat->user_image;
                    array_push($sessions, $single_session);
                    event(new updateQuePatient('update patient que'));
                    // try {
                    //     $firebaseSession->received = false;
                    //     // \App\Helper::firebase($user->id,'updateQuePatient',$single_session['session_id'],$firebaseSession );
                    // } catch (\Throwable $th) {
                    //     //throw $th;
                    // }
                }
                $patient_queue['code'] =200;
                $patient_queue['sessions'] =$sessions;
                return $this->sendResponse($patient_queue,"Session, Patient waiting Room");
                // return view('dashboard_doctor.doc_waiting_room.index', compact('sessions'));
            } else {
                $timestamp = date('Y-m-d H:i:s', strtotime(User::convert_utc_to_user_timezone($user->id, date("Y-m-d H:i:s"))['datetime']));
                $current_date_time = date('Y-m-d H:i:s', strtotime(User::convert_utc_to_user_timezone($user->id, Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, 'UTC')->setTimezone('UTC'))['datetime']));
                $app_date_time = $appointments->date . ' ' . $appointments->time;
                $appoint_dateTime = date('Y-m-d H:i:s', strtotime(User::convert_utc_to_user_timezone($user->id, $app_date_time)['datetime']));
                $date = strtotime($appoint_dateTime);
                $date_time_plus = date('Y-m-d H:i:s', strtotime("+5 minute", $date));
                $date_time_subtract = date('Y-m-d H:i:s', strtotime("-15 minute", $date));
                if ($current_date_time >= $date_time_subtract  && $current_date_time <= $date_time_plus) {
                    $a_sessions = Session::where('doctor_id', $doc_id)
                        ->where('appointment_id', $appointments->appointment_id)
                        ->groupBy('patient_id')
                        ->select('id as session_id', 'patient_id', 'appointment_id')
                        ->get()
                        ->toArray();
                    $e_sessions = Session::where('doctor_id', $doc_id)
                        ->where('status', 'invitation sent')
                        ->where('appointment_id', '=', null)
                        ->groupBy('patient_id')
                        ->orderBy('queue', 'ASC')
                        ->select('id as session_id', 'patient_id', 'appointment_id')
                        ->get()
                        ->toArray();
                    $data_sessions = array_merge($a_sessions, $e_sessions);
                    $sessions = [];
                    $mints = 5;
                    foreach ($data_sessions as $single_session) {
                        Session::where('id', $single_session['session_id'])->update(['que_message' => 'Your Doctor Will Be Available In Approximately ' . $mints . ' Mints', 'join_enable' => '1']);
                        $firebaseSession =Session::where('id', $single_session['session_id'])->first();
                        $mints += 10;
                        $pat = DB::table('users')->where('id', $single_session['patient_id'])->select('name', 'last_name', 'user_image')->first();
                        $pat->user_image = \App\Helper::check_bucket_files_url($pat->user_image);
                        $patient_full_name = $pat->name . " " . $pat->last_name;
                        $single_session['patient_full_name'] = $patient_full_name;
                        $single_session['user_image'] = $pat->user_image;
                        array_push($sessions, $single_session);
                        event(new updateQuePatient('update patient que'));
                        try {
                            $firebaseSession->received = false;
                            // \App\Helper::firebase($user->id,'updateQuePatient',$single_session['session_id'],$firebaseSession );
                        } catch (\Throwable $th) {
                            //throw $th;
                        }
                    }
                    $patient_queue['code'] =200;
                    $patient_queue['sessions'] =$sessions;
                    return $this->sendResponse($sessions,"Session, Patient waiting Room");
                } else {
                    $data_sessions = Session::where('doctor_id', $doc_id)
                        ->where('status', 'invitation sent')
                        ->groupBy('patient_id')
                        ->orderBy('queue', 'ASC')
                        ->select('id as session_id', 'patient_id', 'appointment_id')
                        ->get();
                    $sessions = [];
                    $mints = 5;
                    foreach ($data_sessions as $single_session) {
                        Session::where('id', $single_session['session_id'])->update(['que_message' => 'Your Doctor Will Be Available In Approximately ' . $mints . ' Mints', 'join_enable' => '1']);
                        $firebaseSession =Session::where('id', $single_session['session_id'])->first();
                        $mints += 10;
                        $pat = DB::table('users')->where('id', $single_session['patient_id'])->select('name', 'last_name', 'user_image')->first();
                        $pat->user_image = \App\Helper::check_bucket_files_url($pat->user_image);
                        $patient_full_name = $pat->name . " " . $pat->last_name;
                        $single_session['patient_full_name'] = $patient_full_name;
                        $single_session['user_image'] = $pat->user_image;
                        array_push($sessions, $single_session);
                        event(new updateQuePatient('update patient que'));
                        try {
                            $firebaseSession->received = false;
                            // \App\Helper::firebase($user->id,'updateQuePatient',$single_session['session_id'],$firebaseSession );
                        } catch (\Throwable $th) {
                            //throw $th;
                        }
                    }
                    $patient_queue['code'] =200;
                    $patient_queue['sessions'] =$sessions;
                    return $this->sendResponse($sessions,"Evisit,patient waiting Room");
                    // return view('dashboard_doctor.doc_waiting_room.index', compact('sessions'));
                }
            }
        } else {
            $patient_queueError['code'] =200;
            return $this->sendError($patient_queueError,"Opss! Somthing Went Wrong!");
        }
    }

}
