<?php

namespace App\Http\Controllers;
use App\DoctorSchedule;
use App\Holiday;
use App\Notification;
use App\Http\Controllers\Controller;
use App\Appointment;
use App\Events\RealTimeMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\User;
use DateTime;
use DateTimeZone;

class DoctorScheduleController extends Controller
{
    public $successStatus = 200;
    public $failureStatus = 500;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user=Auth::user();
        if($user->user_type=='doctor'){
            $schedule = DoctorSchedule::where('user_id', $user->id)->first();
            $holidays=Holiday::where('doc_id',$user->id)->get();
              // dd($holidays);
            return view('doctor_schedule', compact('schedule','holidays') );
        }
        else if($user->user_type=='admin'){

        }
        else{
            return view('welcome');
        }
    }

    public function add_doc_schedule(Request $request)
    {
        //dd($request);
        $doctorID=Auth::user()->id;
        $AvailabilityStartUser = $request->date.' '.$request->startTimePicker;
        //dd($AvailabilityStart);
        $AvailabilityStart = User::convert_user_timezone_to_utc($doctorID,$AvailabilityStartUser)['datetime'];
        //dd($AvailabilityStart);
        $AvailabilityEndUser = $request->date.' '.$request->endTimePicker;
        $AvailabilityEnd = User::convert_user_timezone_to_utc($doctorID,$AvailabilityEndUser)['datetime'];
        //dd($AvailabilityStart,$AvailabilityEnd);
        if($AvailabilityStart!=null && $AvailabilityEnd!=null)
        {
            //$rr=$request['AvailabilityStart'];
            //$rrr=$request['AvailabilityEnd'];
            //$AvailabilityStart = explode(" ", $rr);
            //$AvailabilityEnd = explode(" ", $rrr);
            //$aaa=$request["startTimePicker"];
            //$aa=$request["endTimePicker"];
            //$ssd = date("H:i:s", strtotime($aaa));
            //$eed = date("H:i:s", strtotime($aa));
            //$sd=$AvailabilityStart[0]." ".$ssd;
            //$ed=$AvailabilityEnd[0]." ".$eed;

            // $AvailabilityStart = explode(" ",$AvailabilityStart);
            //$AvailabilityEnd = explode(" ",$AvailabilityEnd);
            if($AvailabilityStartUser<$AvailabilityStart)
            {
                $title = $request->AvailabilityTitle;
                $start =  date('H:i:s',strtotime($AvailabilityStart));
                $end =  date('H:i:s',strtotime($AvailabilityEnd));
                $date = date('Y-m-d',strtotime($AvailabilityStart));
                $startdate = date('Y-m-d H:i:s',strtotime($AvailabilityStart));
                $enddate = date('Y-m-d H:i:s',strtotime($AvailabilityEnd));
                $color = $request->AvailabilityColor;
                if($title!=null && $start!=null && $end!=null && $color!=null)
                {
                    $query=DB::table('doctor_schedules')->insert(
                        ['title' => $title, 'start' => $startdate,'end'=>$enddate,'color'=>$color,'slotStartTime'=>$start,'slotEndTime'=>$end,'doctorID'=>$doctorID,'date'=>$date]
                    );
                    if($query==1)
                    {
                    return redirect()->back();
                    }

                }
            }
            else
            {
                $title = $request->AvailabilityTitle;
                $start =  date('H:i:s',strtotime($AvailabilityStart));
                $end =  date('H:i:s',strtotime($AvailabilityEnd));
                $date = date('Y-m-d',strtotime($AvailabilityEnd));
                $startdate = date('Y-m-d H:i:s',strtotime($AvailabilityStart));
                $enddate = date('Y-m-d H:i:s',strtotime($AvailabilityEnd));
                $color = $request->AvailabilityColor;
                if($title!=null && $start!=null && $end!=null && $color!=null)
                {
                    $query=DB::table('doctor_schedules')->insert(
                        ['title' => $title, 'start' => $startdate,'end'=>$enddate,'color'=>$color,'slotStartTime'=>$start,'slotEndTime'=>$end,'doctorID'=>$doctorID,'date'=>$date]
                    );
                    if($query==1)
                    {
                    return redirect()->back();
                    }

                }
            }
        }
        return redirect()->back();
    }

    public function add_therapy_event(Request $request)
    {
        $doctorID=Auth::user()->id;
        $AvailabilityStart = $request->date.' '.$request->startTimePicker;
        //$request->endTimePicker = date('h:i A',strtotime('+60 minutes',strtotime($request->startTimePicker)));
        //dd($AvailabilityStart);
        $AvailabilityStart = User::convert_user_timezone_to_utc($doctorID,$AvailabilityStart)['datetime'];
        //dd($AvailabilityStart);
        //$AvailabilityEnd = $request->date.' '.$request->endTimePicker;
        //$AvailabilityEnd = User::convert_user_timezone_to_utc($doctorID,$AvailabilityEnd)['datetime'];
        //dd($AvailabilityStart,$AvailabilityEnd);
        if($AvailabilityStart!=null)
        {
            $title = $request->AvailabilityTitle;
            $start =  date('H:i:s',strtotime($AvailabilityStart));
            //$end =  date('H:i:s',strtotime($AvailabilityEnd));
            $date = date('Y-m-d',strtotime($AvailabilityStart));
            $startdate = date('Y-m-d H:i:s',strtotime($AvailabilityStart));
            //$enddate = date('Y-m-d H:i:s',strtotime($AvailabilityEnd));
            $color = $request->AvailabilityColor;
            if($title!=null && $start!=null && $color!=null)
            {
                $query=DB::table('doctor_schedules')->insertGetId(
                    ['title' => $title, 'start' => $startdate,'color'=>$color,'slotStartTime'=>$start,'doctorID'=>$doctorID,'date'=>$date]
                );
                if($query>0)
                {
                    $permitted_chars = 'abcdefghijklmnopqrstuvwxyz';
                    $channelName = substr(str_shuffle($permitted_chars), 0, 8);
                    DB::table('therapy_session')->insert([
                        'event_id'=>$query,
                        'doctor_id'=>$doctorID,
                        'status'=>'pending',
                        'price'=>'25',
                        'channel'=>$channelName,
                        'date'=>$date,
                        'start_time'=>$startdate,
                        'time_zone'=>$request->time_zone,
                        'states'=>$request->state,
                        'created_at'=>date('Y-m-d H:i:s'),
                        'updated_at'=>date('Y-m-d H:i:s'),
                    ]);
                    $cncrn = '';
                    $serv = '';
                    if(isset($request->concerns)){
                        foreach($request->concerns as $concern){
                            $cncrn .= $concern.",";
                        }
                    }
                    if(isset($request->concerns)){
                        foreach($request->services as $service){
                            $serv .= $service.",";
                        }
                    }

                    DB::table('psychiatrist_info')->insert([
                        'doctor_id' => auth()->user()->id,
                        'concerns' => $cncrn,
                        'services' => $serv,
                        'help' => $request->helping?$request->helping:'',
                        'skills' => $request->skilled?$request->skilled:'',
                        'event_id' => $query,
                    ]);

                    return redirect()->route('add_therapy_schedule');
                }
            }
        }
        return redirect()->back();
    }

    public function edit_doc_schedule(Request $request)
    {
        $doctorID=Auth::user()->id;
        if($request->schedule_id != null && $request->date != null && $request->startTimePicker != null && $request->endTimePicker != null){
            $UpdateSchedule = DoctorSchedule::where('id',$request->schedule_id)->first();
            $AvailabilityStart = $UpdateSchedule->date.' '.$request->startTimePicker;
            $AvailabilityStart = User::convert_user_timezone_to_utc($doctorID,$AvailabilityStart)['datetime'];
            $AvailabilityEnd = $UpdateSchedule->date.' '.$request->endTimePicker;
            $AvailabilityEnd = User::convert_user_timezone_to_utc($doctorID,$AvailabilityEnd)['datetime'];
            $start =  date('H:i:s',strtotime($AvailabilityStart));
            $end =  date('H:i:s',strtotime($AvailabilityEnd));
            $date = date('Y-m-d',strtotime($AvailabilityStart));
            $startdate = date('Y-m-d H:i:s',strtotime($AvailabilityStart));
            $enddate = date('Y-m-d H:i:s',strtotime($AvailabilityEnd));

            $UpdateSchedule->start = $startdate;
            $UpdateSchedule->end = $enddate;
            $UpdateSchedule->slotStartTime = $start;
            $UpdateSchedule->slotEndTime = $end;
            $UpdateSchedule->date = $date;
            $UpdateSchedule->save();

        }
        return redirect()->back();
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
                    return $data;
                }
                elseif($request['time']<=$sch->slotStartTime && $request['etime']>=$sch->slotStartTime && $sch->title=='Therapy')
                {
                    $data = '1';
                    return $data;
                }
            }

        }
        $c_datetime = date('Y-m-d H:i:s');
        $c_time = User::convert_utc_to_user_timezone($user->id,$c_datetime)['datetime'];
        $c_time = date('H:i:s',strtotime($c_time));
        $c_date = User::convert_utc_to_user_timezone($user->id,$c_datetime)['datetime'];
        $c_date = date('Y-m-d',strtotime($c_date));
        $data = $c_date.' '.$c_time;
        return $data;
    }
    public function del_doc_schedule($id){
        $user=Auth::user();
        $schedule = DoctorSchedule::where('doctorID', $user->id)->where('id',$id)->first();
        if($schedule->title == 'Therapy')
        {
            $schedule = DoctorSchedule::where('doctorID', $user->id)->where('id',$id)->delete();
            DB::table('therapy_session')->where('event_id',$id)->delete();
        }
        else
        {
            $schedule = DoctorSchedule::where('doctorID', $user->id)->where('id',$id)->delete();
        }
        return redirect()->back();
    }

    public function add_doctor_schedule()
    {
        $user=Auth::user();
        if($user->user_type=='doctor'){
            $date = date('Y-m-d H:i:s');
            $date = User::convert_utc_to_user_timezone($user->id,$date)['datetime'];
            $time = date('H:i:s',strtotime($date));
            $date = date('Y-m-d',strtotime($date));
            $schedule = DoctorSchedule::where('doctorID', $user->id)->where('date','>=',date('Y-m-d'))->where('title','Availability')->orderBy('id','desc')->paginate(10);
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
            return view('dashboard_doctor.Schedule.index', compact('schedule','date') );
        }
        else{
            return view('dashboard_doctor.Schedule.index');
        }
    }
    public function add_therapy_schedule()
    {
        $user=Auth::user();
        if($user->user_type=='doctor'){
            $date = date('Y-m-d H:i:s');
            $date = User::convert_utc_to_user_timezone($user->id,$date)['datetime'];
            $time = date('H:i:s',strtotime($date));
            $date = date('Y-m-d',strtotime($date));
            $schedule = DoctorSchedule::where('doctorID', $user->id)->where('date','>=',date('Y-m-d'))->where('title','Therapy')->orderBy('id','desc')->paginate(10);
            foreach($schedule as $sc){
                $therapy_session = DB::table('therapy_session')->where('event_id',$sc->id)->first();
                $current_date_time = date('Y-m-d H:i:s');
                $end_date_time = date('Y-m-d H:i:s');
                $sc->enroll = '0';
                $sc->session_id = '0';
                $current_date_time = date('Y-m-d H:i:s',strtotime('+5 minutes',strtotime($current_date_time)));
                $end_date_time = date('Y-m-d H:i:s',strtotime('-15 minutes',strtotime($end_date_time)));
                if($current_date_time>=$sc->start && $end_date_time<=$sc->start || $therapy_session->status=='started')
                {
                    $sc->enroll = '1';
                }
                $sc->session_id = $therapy_session->id;
                $sc->time_zone = $therapy_session->time_zone;
                $sc->count = DB::table('therapy_patients')->where('session_id',$sc->session_id)->count();
                $sc->patients = DB::table('therapy_patients')->where('session_id',$sc->session_id)->get()->toArray();
                foreach($sc->patients as $pat)
                {
                    $user = DB::table('users')->where('id',$pat->patient_id)->first();
                    $pat->name = $user->name.' '.$user->last_name;
                }
                $sc->patients = json_encode($sc->patients);
                $sc->date = User::convert_utc_to_user_timezone($user->id,$sc->start)['date'];
                $sc->start = User::convert_utc_to_user_timezone($user->id,$sc->start)['time'];
                $sc->end = User::convert_utc_to_user_timezone($user->id,$sc->end)['time'];
                $sc->time = $time;
                $sc->cdate = date('m-d-Y',strtotime($date));
            }
            return view('dashboard_doctor.Therapy_Schedule.index', compact('schedule','date') );
        }
        else{
            return view('dashboard_doctor.Therapy_Schedule.index');
        }
    }

    public function edit_therapy_event(Request $request)
    {
        $doctorID=Auth::user()->id;
        $request->startTimePicker = date('H:i:s',strtotime($request->startTimePicker));
        $AvailabilityStart = $request->date.' '.$request->startTimePicker;
        $AvailabilityStart = User::convert_user_timezone_to_utc($doctorID,$AvailabilityStart)['datetime'];
        if($request->id != null && $request->date != null)
        {
            $start =  date('H:i:s',strtotime($AvailabilityStart));
            $date = date('Y-m-d',strtotime($AvailabilityStart));
            $startdate = date('Y-m-d H:i:s',strtotime($AvailabilityStart));
            DB::table('doctor_schedules')->where('id',$request->id)->update(['start'=>$startdate,'slotStartTime'=>$start,'date'=>$date]);
            DB::table('therapy_session')->where('event_id',$request->id)->update(['start_time'=>$startdate,'date'=>$date]);
        }
        return redirect()->back()->with('msg','updated successfully');
    }

    public function get_schedule_slots(Request $request)
    {
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
        return $slots;
    }
    public function get_therapy_slots(Request $request)
    {
        $user = auth()->user();
        $schedules = DB::table('doctor_schedules')->where('doctorID',$user->id)->where('id','!=',$request->id)
        ->where('date',$request->date)->orwhere('date',date('Y-m-d', strtotime('+1 day', strtotime($request->date))))->get();
        $date = User::convert_utc_to_user_timezone($user->id,date('Y-m-d H:i:s'))['datetime'];
        $todaytime = date('H:i:s',strtotime($date));
        $date = date('Y-m-d',strtotime($date));
        $slots = [];
        $time = '00:00:00';

        for($i=0;$i<288;$i++)
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
                        if($time>=$starttime && $time<$endtime && $sch->title=='Availability')
                        {
                            $count=1;
                        }
                        elseif($sch->title=='Therapy' && $time==$starttime)
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
            $time = date('H:i:s',strtotime('+5 minutes',strtotime($time)));
        }
        return $slots;
    }

    public function schedulePage()
    {
        return view('/add_schedule');
    }

    public function add_holiday(Request $request)
    {
        $id=$request['id']; //schedule
        $schedule= DoctorSchedule::find($id);
        // dd($schedule);
        return view('/holiday', compact('schedule'));
    }

    public function store_holiday(Request $request){
        // dd($request);
        $holiday=$request->validate([
            'date' =>  ['required', 'string', 'max:255'],


        ]);
        $userId=Auth::user()->id;
        Holiday::create([
            'doc_id' =>  $userId,
            'date' =>$holiday['date'],

        ]);

        return redirect()->route('doctor_schedule')->with('success','Successfully created.');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request);
        $request=$request->validate([
            'mon' =>  ['required', 'string', 'max:2'],
            'tues' =>  ['required', 'string', 'max:2'],
            'weds' =>  ['required', 'string', 'max:2'],
            'thurs' =>  ['required', 'string', 'max:2'],
            'fri' =>  ['required', 'string', 'max:2'],
            'sat' =>  ['required', 'string', 'max:2'],
            'sun' =>  ['required', 'string', 'max:2'],

            'from_time' =>  ['required', 'string', 'max:255'],
            'to_time' =>  ['required', 'string', 'max:255'],

        ]);
        $userId=Auth::user()->id;
        $user_type=Auth::user()->user_type;
        DoctorSchedule::create([
            'user_type' =>  $user_type,
            'user_id' =>  $userId,
            'mon' =>$request['mon'],
            'tues' =>  $request['tues'],
            'weds' => $request['weds'],
            'thurs' => $request['thurs'],
            'fri' => $request['fri'],
            'sat' => $request['sat'],
            'sun' => $request['sun'],
            'from_time' => $request['from_time'],
            'to_time' => $request['to_time'],
        ]);

        return redirect()->route('doctor_schedule')->with('success','Successfully created.');
    }


    public function schedule_list(Request $request)
    {
        $user = $request['id'];
        if( !empty( $user ) ){
            $schedule_list = DoctorSchedule::where('user_id', $user)->get();
            return $schedule_list;
        }
    }
    public function checkAlredyBookTiming(Request $request)
    {
        $patientId=Auth::user()->id;
        $docID = $request['id'];
        $time = $request['time'];
        $sdate = $request['sdate'];
        $newDate = date("Y-m-d", strtotime($sdate));
        $doc=DB::table('appointments')
            ->select('appointments.*')
            ->whereRaw('(DATE(date)=?)', [$newDate])
            ->whereRaw('(TIME(time)=?)', [$time])
            ->where('doctor_id',$docID)
            ->get();

        $already_booked_appointment=DB::table('appointments')
            ->select('appointments.*')
            ->whereRaw('(DATE(date)=?)', [$newDate])
            ->whereRaw('(TIME(time)=?)', [$time])
            ->where('patient_id',$patientId)
            ->where('status','pending')
            ->get();

        $count=count($doc);
        $count_already_booked_appointment=count($already_booked_appointment);
        if($count>0)
        {

            $message="That Slot Already Booked Please Select Another Solt";
            return $message;
        }
        else if($count_already_booked_appointment>0)
        {
            $message="You Book Already That Slot";
            return $message;
        }
        else{
            return "";
        }
    }
    public function timing(Request $request)
    {
            $user = Auth::user();
            $d = explode(' ',$request->sdate);
            //dd($request->sdate[0],$request->sdate[1]);
            $utc_start_datetime = User::convert_user_timezone_to_utc($user->id,$request->sdate);
            $user_current_datetime = User::convert_utc_to_user_timezone($user->id,date('Y-m-d H:i:s'));

            $docID = $request['id'];
            $doc_avi="Availability";
            // $temp = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now()->format('Y-m-d H:i:s'), $user->timeZone);
            $temp = new DateTime(Carbon::now()->format('Y-m-d H:i:s'));
            $temp->setTimezone(new DateTimeZone($user->timeZone));
            // $temp = explode(' ' ,$temp)[1];
            $temp = $temp->format('H:i:s');
            // dd($temp);

            $sdate = date('Y-m-d',strtotime($request->sdate));

            $newDatee=date('Y-m-d H:i:s',strtotime($utc_start_datetime['datetime']));


            $current_date_time = date('Y-m-d H:i:s');


            // dd(explode(' ',$sdate)[0]);
            $current_time = date('H:i:s');
            $time_check = date('H:i:s',strtotime('+20 minutes' ,strtotime($current_time)));
            // dd($sdate);
            if(date('Y-m-d',strtotime($utc_start_datetime['datetime']))==date('Y-m-d'))
            {
                // dd($utc_start_datetime['datetime']);
                $docTimings= DB::table('doctor_schedules')
                    ->select('doctor_schedules.*')
                    ->where('start', $newDatee)
                    ->where([['doctorID',$docID],['title',$doc_avi]])
                    ->get();
                // foreach($docTimings as $key=>$doc){
                //     $checking =  User::convert_utc_to_user_timezone($doc->doctorID,$doc->end)['time'];
                //     if($checking<=$user_current_datetime['time']){
                //         unset($docTimings[$key]);
                //     }
                // }
            }
            else
            {
                $docTimings= DB::table('doctor_schedules')
                    ->select('doctor_schedules.*')
                    ->where('start', $newDatee)
                    ->where([['doctorID',$docID],['title',$doc_avi]])
                    ->get();
            }
            //dd($docTimings);
            $bookedSlots=Appointment::where('date',date('Y-m-d',strtotime($utc_start_datetime['datetime'])))->where('doctor_id',$docID)->get();
            $time = [];

            //dd($bookedSlots);
            foreach($docTimings as $docTiming)
            {
                $current_date = User::convert_utc_to_user_timezone($docTiming->doctorID,$current_date_time)['date'];
                $interval=15;
                $break=5;
                $start = User::convert_utc_to_user_timezone($docTiming->doctorID,$docTiming->start)['datetime'];
                $end = User::convert_utc_to_user_timezone($docTiming->doctorID,$docTiming->end)['datetime'];
                $startTime = date('H:i:s',strtotime($start));
                $endTime = date('H:i:s',strtotime($end));
                $current_time = User::convert_utc_to_user_timezone($docTiming->doctorID,date('Y-m-d H:i:s'))['time'];
                $current_time = date('H:i:s',strtotime($current_time));
                $i=0;
                //dd($start,$end);

                while(strtotime($startTime) <= strtotime($endTime))
                {
                    $start = $startTime;
                    $end = date('H:i:s',strtotime('+'.$interval.' minutes',strtotime($startTime)));
                    $startTime = date('H:i:s',strtotime('+'.$interval+$break.' minutes',strtotime($startTime)));
                    $i++;
                    //dd(.'=='.$user_current_datetime['date'].'=='.strtotime($startTime).'=='.strtotime($endTime));
                    if(strtotime($startTime) <= strtotime($endTime) && date('m-d-Y',strtotime($sdate)) == $user_current_datetime['date'])
                    {
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
                    //dd($value['start']);
                    if($bookedSlots!=null)
                    {
                        foreach($bookedSlots as $slot)
                        {
                            $time_check = User::convert_utc_to_user_timezone($docID,$slot->date.' '.$slot->time)['datetime'];
                            $time_check = date('H:i:s',strtotime($time_check));
                            //dd($slot->time);
                            if($time_check==$value['start'] && $slot->status=="pending")
                            {
                                //dd($slot->time);
                                unset($time[$key]);
                            }
                        }
                    }
                }
                $time = array_values($time);
            }
            // $counter=0;
            $timingArray=[];
            foreach ($time as $timing) {
                // dd($timing);
                $start = User::convert_user_timezone_to_utc($docID,$timing['start'])['time'];
                $end = User::convert_user_timezone_to_utc($docID,$timing['end'])['time'];
                $start = User::convert_utc_to_user_timezone($user->id,$start)['time'];
                $end = User::convert_utc_to_user_timezone($user->id,$end)['time'];
                $t_start = $start;
                $t_end = $end;
                $start = date('H:i:s',strtotime($start));
                $end = date('H:i:s',strtotime($end));
                $checkArray=['start'=>$start,'end'=>$end,'t_start'=>$t_start,'t_end'=>$t_end];
                array_push($timingArray,$checkArray);
                // dd($timing);

            }
            //dd($timingArray);
            return response()->json(['data'=>$timingArray]);

    }


    /**
     * Display the specified resource.
     *
     * @param  \App\DoctorSchedule  $doctorSchedule
     * @return \Illuminate\Http\Response
     */
    public function show(DoctorSchedule $doctorSchedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DoctorSchedule  $doctorSchedule
     * @return \Illuminate\Http\Response
     */
     // public function edit(DoctorSchedule $doctorSchedule)
    // public function edit($id)
     public function edit($id)
    {
        // dd($id);
       // $id='1';
         $schedule = DoctorSchedule::find($id);
         // dd($schedule);
        // return redirect()->route('edit_schedule',['schedule'=>$schedule, 'id'=> $id]);
         return view('edit_schedule',['schedule'=>$schedule]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DoctorSchedule  $doctorSchedule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //dd($request);
        //dd($doctorSchedule);
        //$id='1';
        $schedule = DoctorSchedule::find($id);
        $request->validate([
            'mon' =>  ['required', 'string', 'max:2'],
            'tues' =>  ['required', 'string', 'max:2'],
            'weds' =>  ['required', 'string', 'max:2'],
            'thurs' =>  ['required', 'string', 'max:2'],
            'fri' =>  ['required', 'string', 'max:2'],
            'sat' =>  ['required', 'string', 'max:2'],
            'sun' =>  ['required', 'string', 'max:2'],

            'from_time' =>  ['required', 'string', 'max:255'],
            'to_time' =>  ['required', 'string', 'max:255'],
        ]);
        $schedule->update($request->all());

        return redirect()->route('doctor_schedule')->with('success','Schedule updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DoctorSchedule  $doctorSchedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        // dd($request['id']);
        $id=$request['id'];
        $schedule = DoctorSchedule::where('id', $id)->first();
        $schedule->delete();

        return redirect()->route('doctor_schedule')->with('success','Product deleted successfully');
    }

    public function calendar()
    {
        $user=Auth::user();
        if($user->user_type=='doctor'){
            $schedule = DoctorSchedule::where('user_id', $user->id)->first();
            $appointments = Appointment::where('doctor_id', $user->id)->get();
            $holidays=Holiday::where('doc_id',$user->id)->get();
              // dd($holidays);
            return view('doctor_schedule_calendar', compact('schedule','holidays','appointments','user') );

        }
        else{
            return view('welcome');
        }
    }
     public function store_calendar_holiday(Request $request){
        $userId=Auth::user()->id;
        $date=$request['date'];
        Holiday::create([
            'doc_id' =>  $userId,
            'date' =>$date,

        ]);
        Appointment::where('date',$date)->where('doctor_id',$userId)->update(['status' => 'moved']);
        $apps=Appointment::where('date',$date)->where('doctor_id',$userId)->get();
        foreach ($apps as $app) {
            Notification::create([
            'user_id' =>  $app['patient_id'],
            'text' =>  "Your appointment with ".$app['doctor_name']." requires a review",
            'status' =>  'new',
            'type' =>  '/specializations',
            'appoint_id' =>  $app['id'],
        ]);
        }
        // event(new RealTimeMessage('Hello World'));
        // Notification::create([
        //     'doc_id' =>  $userId,
        //     'date' =>$date,

        // ]);
    }
    public function all_holidays(Request $request){
        $userId=Auth::user()->id;
        $holidays=Holiday::where('doc_id',$userId)->get();
        return $holidays;
    }

    public function doctor_calendar()
    {
        $doctorID=Auth::user()->id;
        $events=DoctorSchedule::all()->where("doctorID",$doctorID);
        $datetime = new \DateTime(null, new \DateTimeZone("UTC"));
        $time = $datetime->format('H:i:A');$time[5]=" ";
        //dd($date);
        return view("doctor_calendar",compact('events','time'));
    }


    public function addEvent(Request $request)
    {
        //dd($request);
        $doctorID=Auth::user()->id;
        $AvailabilityTitle=$request['AvailabilityTitle'];
        $AvailabilityStart=$request['AvailabilityStart'];
        $AvailabilityEnd=$request['AvailabilityEnd'];
        $AvailabilityColor=$request['AvailabilityColor'];
        $Holidaytitle=$request['Holidaytitle'];
        $Holidaycolor=$request['Holidaycolor'];
        $Holidaystart=$request['Holidaystart'];
        $Holidayend=$request['Holidayend'];

        if($AvailabilityTitle!=null && $AvailabilityStart!=null && $AvailabilityEnd!=null && $AvailabilityColor!=null)
        {
            $rr=$request['AvailabilityStart'];
            $rrr=$request['AvailabilityEnd'];
            $AvailabilityStart = explode(" ", $rr);
            $AvailabilityEnd = explode(" ", $rrr);
            $aaa=$request["startTimePicker"];
            $aa=$request["endTimePicker"];
            $ssd = date("H:i:s", strtotime($aaa));
            $eed = date("H:i:s", strtotime($aa));
            $sd=$AvailabilityStart[0]." ".$ssd;
            $ed=$AvailabilityEnd[0]." ".$eed;
            $title = $request['AvailabilityTitle'];
            $start = $sd;
            $end = $ed;
            $color = $request['AvailabilityColor'];
            // dd($ssd);
            if($title!=null && $start!=null && $end!=null && $color!=null)
            {
                $query=DB::table('doctor_schedules')->insert(
                    ['title' => $title, 'start' => $start,'end'=>$end,'color'=>$color,'slotStartTime'=>$ssd,'slotEndTime'=>$eed,'doctorID'=>$doctorID,'date'=>$AvailabilityStart[0]]
                );
                if($query==1)
                {
                   return redirect()->back();
                }

            }
        }
        if($Holidaytitle!=null && $Holidaycolor!=null && $Holidaystart!=null && $Holidayend!=null)
        {
            $rr=$request['Holidaystart'];
            $rrr=$request['Holidayend'];
            $AvailabilityStart = explode(" ", $rr);
            $AvailabilityEnd = explode(" ", $rrr);
            $aaa=$request["HolidayStartTimePicker"];
            $aa=$request["HolidayEndTimePicker"];
            $ssd = date("H:i:s", strtotime($aaa));
            $eed = date("H:i:s", strtotime($aa));
            $sd=$AvailabilityStart[0]." ".$ssd;
            $ed=$AvailabilityEnd[0]." ".$eed;
            $title = $request['Holidaytitle'];
            $start = $sd;
            $end = $ed;
            $color = $request['Holidaycolor'];
            if($title!=null && $start!=null && $end!=null && $color!=null)
            {
                $query=DB::table('doctor_schedules')->insert(
                    ['title' => $title, 'start' => $start,'end'=>$end,'color'=>$color,'slotStartTime'=>$ssd,'slotEndTime'=>$eed,'doctorID'=>$doctorID,'date'=>$AvailabilityStart[0]]
                );
                if($query==1)
                {
                    return redirect()->back();
                }
            }
        }
    }
    public function updateEvent(Request $request)
    {

            $title=$request['title'];
            if($title==null)
            {
                $title="Availability";
            }
            $reason=$request["reason"];
            $delete=$request["delete"];
            $id=$request["id"];
            $start=$request["start"];
            $end=$request["end"];
            if($delete!=null && $id!=null)
            {
                $id = $request['id'];
                $db=DB::table('doctor_schedules')->where('id', $id)->update(['disable_reason'=>$reason,'color'=>'#FF0000','title'=>$reason]);
                if($db==1)
                {
                    $select_data=DB::table('doctor_schedules')->where('id',$id)->first();

                    $doc= DB::table('appointments')
                    ->select('appointments.*')
                    ->where('date',$select_data->date)
                    ->where('time','>=',$select_data->slotStartTime)
                    ->where('time','<=',$select_data->slotEndTime)
                    ->where('appointments.doctor_id',$select_data->doctorID)
                    ->get();
                    // dd($doc);
                    $count_appointments=count($doc);
                    if($count_appointments>0)
                    {
                        foreach($doc as $d)
                        {
                            DB::table("appointments")->where("id",$d->id)->update(
                                [
                                    "status"=>"make-reschedule",
                                    "reminder_one_status"=>"send",
                                    "reminder_two_status"=>"send",
                                ]
                            );
                            $get_patinet_email=DB::table('users')->where('id',$d->patient_id)->first();
                        $userEmail=$get_patinet_email->email;
                        //send User Mail Here And inform admin Here
                        }
                    }




                    return redirect()->back();
                }
            }
            else if($start!=null && $end!=null && $id!=null && $title!=null)
            {
                $rr=$request['start'];
                $rrr=$request['end'];
                $AvailabilityStart = explode(" ", $rr);
                $AvailabilityEnd = explode(" ", $rrr);
                $aaa=$request["EditStartTimePicker"];
                $aa=$request["EditEndTimePicker"];
                $ssd = date("H:i:s", strtotime($aaa));
                $eed = date("H:i:s", strtotime($aa));
                $sd=$AvailabilityStart[0]." ".$ssd;
                $ed=$AvailabilityEnd[0]." ".$eed;
                $id = $request['id'];
                $start = $sd;
                $end = $ed;
                $schedule = DoctorSchedule::find($id);
                $db=$schedule->update(['start' => $start, 'end'=>$end, 'title'=>$title,'slotStartTime'=>$ssd,'slotEndTime'=>$eed,'date'=>$AvailabilityStart[0]]);
                if($db==1)
                {
                    return redirect()->back();
                }
            }



    }


public function fetchAppointment(Request $request)
{

        $id=Auth::user()->id;
        $selectData=$request['selectData'];
        $doctorSlotStartTime=$request['doctorSlotStartTime'];
        $doctorSlotEndTime=$request['doctorSlotEndTime'];
        $starttime= date("H:i:s", strtotime($doctorSlotStartTime));
        $entime= date("H:i:s", strtotime($doctorSlotEndTime));


        $doc= DB::table('appointments')
            ->select('appointments.*')
            ->where('date',$selectData)
            ->where('time','>=',$starttime)
            ->where('time','<=',$entime)
            // ->whereRaw('(DATE(appointments.date)=?)', [$selectData])
            // ->whereRaw('(TIME(appointments.time)>=?)', [$doctorSlotStartTime])
            // ->whereRaw('(TIME(appointments.time)<=?)', [$doctorSlotEndTime])
            ->where('appointments.doctor_id',$id)
            ->where('appointments.status','pending')
            ->get();
//    return $doc;
   $userData["data"]=$doc;

   echo json_encode($userData);

}
}