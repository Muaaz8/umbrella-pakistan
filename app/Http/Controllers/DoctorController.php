<?php

namespace App\Http\Controllers;

use App\User;
use App\Symptom;
use App\Specialization;
use App\AgoraAynalatics;
use App\Appointment;
use App\Helper;
use App\Session;
use App\State;
use App\City;
use App\MedicalProfile;
use App\Prescription;
use App\Models\AllProducts;
use App\Models\Symptoms_Checker;
use App\Jobs\SendMailJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\RefillRequest;
use App\Cart;
use App\Events\CheckDoctorStatus;
use App\Events\loadOnlineDoctor;
use App\Events\RealTimeMessage;
use App\Events\updateQuePatient;
use App\Events\EndConferenceCall;
use App\Referal;
use App\Notification;
use Illuminate\Support\Facades\Mail;
use App\Mail\EvisitBooking;
use App\Mail\PasswordReset;
use App\Mail\EvisitBookMail;
use App\Mail\ReferDoctorToDoctorMail;
use App\Mail\RequestSessionPatientMail;
use App\Mail\ReferDoctorToPatientMail;
use App\Mail\RefillCompleteMailToPatient;
use Illuminate\Support\Facades\Http;
use App\Mail\RefillRequestToDoctorMail;
use App\Mail\SendEmail;
use App\QuestDataAOE;
use App\QuestDataTestCode;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Image;
use Response;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function doc_acc_settings()
    {
        $user = Auth()->user();
        return view('dashboard_doctor.Account_settings.index', compact('user'));
    }
    public function update_doc_pass(Request $request)
    {
        $request->validate([
            'current_pass' => 'required',
            'new_pass' => [
                'required',
                Password::min(8)->letters()->mixedCase()->numbers()->symbols()
            ],
        ]);
        $user = auth()->user();
        if (Hash::check($request['current_pass'], $user->password)) {
            $user->password = Hash::make($request['new_pass']);
            $user->save();
            Mail::to($user->email)->send(new PasswordReset($user->name));
            return redirect()->back()->with('success', 'Password Updated Successfully');
        } else {
            return redirect()->back()->with('error', 'The old password did not match.');
        }
    }
    public function check_prescription_completed(Request $request)
    {
        $items = DB::table('prescriptions')->where('session_id', $request->id)->get();
        $error = "success";
        foreach ($items as $item) {
            if ($item->type == 'lab-test') {
                $getTestAOE = QuestDataAOE::select("TEST_CD AS TestCode", "AOE_QUESTION AS QuestionShort", "AOE_QUESTION_DESC AS QuestionLong")
                    ->where('TEST_CD', $item->test_id)
                    ->groupBy('AOE_QUESTION_DESC')
                    ->get();
                $count = count($getTestAOE);
                if ($count > 0) {
                    $res = DB::table('patient_lab_recomend_aoe')->where('testCode', $item->test_id)->where('session_id', $request->id)->first();
                    if ($res == null) {
                        $product = \App\QuestDataTestCode::where('TEST_CD', $item->test_id)->first();
                        $error = "lab-error_" . $product->DESCRIPTION;
                    }
                }
            } else if ($item->type == 'imaging') {
                $getTestAOE = QuestDataAOE::select("TEST_CD AS TestCode", "AOE_QUESTION AS QuestionShort", "AOE_QUESTION_DESC AS QuestionLong")
                    ->where('TEST_CD', $item->test_id)
                    ->groupBy('AOE_QUESTION_DESC')
                    ->get();
                $count = count($getTestAOE);
                if ($count > 0) {
                    $res = DB::table('patient_lab_recomend_aoe')->where('testCode', $item->test_id)->where('session_id', $request->id)->first();
                    if ($res == null) {
                        $product = \App\QuestDataTestCode::where('TEST_CD', $item->test_id)->first();
                        $error = "lab-error_" . $product->DESCRIPTION;
                    }
                }
            }
        }
        return $error;
    }

    public function inclinic_check_prescription_completed(Request $request)
    {
        $items = DB::table('prescriptions')->where('session_id', "0")->where('parent_id', $request->session_id)->get();
        $error = "success";
        foreach ($items as $item) {
            if ($item->type == 'lab-test' || $item->type == 'imaging') {
                $getTestAOE = QuestDataAOE::select("TEST_CD AS TestCode", "AOE_QUESTION AS QuestionShort", "AOE_QUESTION_DESC AS QuestionLong")
                    ->where('TEST_CD', $item->test_id)
                    ->groupBy('AOE_QUESTION_DESC')
                    ->get();
                $count = count($getTestAOE);
                if ($count > 0) {
                    $res = DB::table('patient_lab_recomend_aoe')->where('testCode', $item->test_id)->where('session_id', $request->id)->first();
                    if ($res == null) {
                        $product = \App\QuestDataTestCode::where('TEST_CD', $item->test_id)->first();
                        $error = "lab-error_" . $product->TEST_NAME;
                    }
                }
            }else if($item->type == 'medicine'){
                if($item->usage == null){
                    $product = DB::table('tbl_products')->where('id', $item->medicine_id)->first();
                    $error = $product->name;
                }
            }
        }
        return $error;
    }
    public function dash_all()
    {
        $user = Auth::user();
        $user_id = $user->id;
        if ($user->user_type == 'admin') {
            $doctors = DB::table('users')->where('user_type', 'doctor')->paginate(8);
            return view('doctors', compact('doctors', 'user'));
        } else if ($user->user_type == 'doctor') {
            $doctors = DB::table('users')
                ->where('users.user_type', 'doctor')
                ->join('specializations', 'specializations.id', '=', 'users.specialization')
                ->select('users.*', 'specializations.name as spec')
                ->paginate(8);

            return view('dashboard_doctor.All_doctor.index', compact('doctors', 'user', 'user_id'));
        } else if ($user->user_type == 'patient') {
            $user_state = $user->state_id;
            $state = State::find($user_state);
            if ($state->active == 1) {
                $doctors = DB::table('users')
                    //->select('name',' last_name', 'specialization')
                    ->where('patient_id', $user->id)
                    ->join('sessions', 'users.id', '=', 'sessions.doctor_id')
                    ->join('specializations', 'specializations.id', '=', 'users.specialization')
                    ->select('users.*', 'specializations.name as spec')
                    ->groupBy('doctor_id')
                    ->where('sessions.status', 'ended')
                    ->paginate(8);
                return view('doctors', compact('doctors', 'user', 'user_id'));
            } else {
                return redirect()->route('errors', '101');
            }
        } else
            return view('welcome');
    }
    public function all()
    {
        $user = Auth::user();
        $user_id = $user->id;
        if ($user->user_type == 'admin') {
            $doctors = DB::table('users')->where('user_type', 'doctor')->paginate(8);
            return view('doctors', compact('doctors', 'user'));
        } else if ($user->user_type == 'doctor') {
            $doctors = DB::table('users')
                ->where('users.user_type', 'doctor')
                ->join('specializations', 'specializations.id', '=', 'users.specialization')
                ->select('users.*', 'specializations.name as spec')
                ->paginate(8);

            return view('doctors', compact('doctors', 'user', 'user_id'));
        } else if ($user->user_type == 'patient') {
            $user_state = $user->state_id;
            $state = State::find($user_state);
            if ($state->active == 1) {
                $doctors = DB::table('users')
                    //->select('name',' last_name', 'specialization')
                    ->where('patient_id', $user->id)
                    ->join('sessions', 'users.id', '=', 'sessions.doctor_id')
                    ->join('specializations', 'specializations.id', '=', 'users.specialization')
                    ->select('users.*', 'specializations.name as spec')
                    ->groupBy('doctor_id')
                    ->where('sessions.status', 'ended')
                    ->paginate(8);
                return view('doctors', compact('doctors', 'user', 'user_id'));
            } else {
                return redirect()->route('errors', '101');
            }
        } else
            return view('welcome');
    }

    public function getonlinedoctors($id)
    {
        $user = Auth::user();
        if ($user->user_type == 'patient') {

            $state = DB::table('users')->where('users.id', $user->id)->select('users.state_id')->first();
            $state = $state->state_id;
            $doctors = DB::table('doctor_licenses')
                ->join('users', 'doctor_licenses.doctor_id', 'users.id')
                ->join('specializations', 'specializations.id', 'users.specialization')
                ->where('doctor_licenses.state_id', $state)
                ->where('users.specialization', $id)
                ->where('users.status', 'online')
                ->where('users.active', '1')
                ->where('doctor_licenses.is_verified', '1')
                ->select('users.*', 'specializations.name as sp_name')
                ->get();
            foreach ($doctors as $doctor) {
                $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);
            }
            $session = null;
            $price = DB::table('specalization_price')->where('spec_id', $id)->first();
            if ($price != null) {
            if ($price->follow_up_price != null) {
                $session = DB::table('sessions')->where('patient_id', $user->id)
                    ->join('specializations', 'sessions.specialization_id', 'specializations.id')
                    ->join('specalization_price', 'sessions.specialization_id', 'specalization_price.spec_id')
                    ->select('specializations.name as sp_name', 'specalization_price.follow_up_price as price')
                    ->where('specialization_id', $id)->first();
            }}else{
                return view('errors.101');
            }
            return view('online_doctors', compact('doctors', 'session', 'id'));
        } else {
            return redirect()->route('home');
        }
    }
    public function dash_getonlinedoctors($id)
    {
        $user = Auth::user();
        if ($user->user_type == 'patient') {
            if($id != '21')
            {
                // $states = DB::table('states')->where('active','1')->get();
                // $state = DB::table('states')->where('id',$loc_id)->first();
                $doctors = DB::table('users')
                    ->join('specializations', 'specializations.id', 'users.specialization')
                    ->where('users.specialization', $id)
                    ->where('users.status', 'online')
                    ->where('users.active', '1')
                    ->select('users.*', 'specializations.name as sp_name')
                    ->paginate(10);
                foreach ($doctors as $doctor) {
                    $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);
                }
                // $session = null;
                // $price = DB::table('specalization_price')->where('spec_id', $id)->first();
                // if ($price != null) {
                //     if ($price->follow_up_price != null) {
                //         $session = DB::table('sessions')->where('patient_id', $user->id)
                //             ->join('specializations', 'sessions.specialization_id', 'specializations.id')
                //             ->join('specalization_price', 'sessions.specialization_id', 'specalization_price.spec_id')
                //             ->select('specializations.name as sp_name', 'specalization_price.follow_up_price as price')
                //             ->where('specialization_id', $id)->first();
                //     }
                // }else{
                //     return view('errors.101');
                // }
                // return view('dashboard_patient.Evisit.online_doctor', compact('doctors', 'session', 'id'));
                return view('dashboard_patient.Evisit.online_doctor', compact('doctors', 'id'));
            }else{
                $flag = 'session';
                return view('dashboard_patient.Evisit.patient_health',compact('user','flag','loc_id'));
            }
        } else {
            return redirect()->route('home');
        }
    }

    public function admin_doctor_profile_management(){
        $doctors = DB::table('users')->where('user_type','doctor')->get();
        return view('dashboard_admin.Profile.index', compact('doctors'));
    }

    public function seo_doctor_profile_management(){
        $doctors = DB::table('users')->where('user_type','doctor')->get();
        return view('dashboard_SEO.Profile.index', compact('doctors'));
    }

    public function dash_getonlinedoctors_ajax(Request $request)
    {
        $user = auth()->user();
        $id = $request->spec_id;
        $loc_id = $request->loc_id;
        $doctors = DB::table('users')
        ->join('specializations', 'specializations.id', 'users.specialization')
        ->where('users.specialization', $request->spec_id)
        ->where('users.status', 'online')
        ->where('users.active', '1')
        ->select('users.*', 'specializations.name as sp_name')
        ->get();

        //dd($doctors);
        foreach ($doctors as $doctor) {

            $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);
        }
        $session = null;
        // $price = DB::table('specalization_price')->where('spec_id', $id)->first();
        $price = DB::table('specalization_price')->where('spec_id', $request->spec_id)->first();
        if($price==null)
        {
            return '1';
        }
        if ($price != null) {
        if ($price->follow_up_price != null) {
            $session = DB::table('sessions')->where('patient_id', $user->id)
                ->join('specializations', 'sessions.specialization_id', 'specializations.id')
                ->join('specalization_price', 'sessions.specialization_id', 'specalization_price.spec_id')
                ->select('specializations.name as sp_name', 'specalization_price.follow_up_price as price')
                ->where('specialization_id', $request->spec_id)->first();
        }}else{
            return view('errors.101');
        }
        $symp = DB::table('isabel_symptoms')->get();
        // return view('dashboard_patient.Evisit.online_doctor', compact('doctors', 'session', 'id'));
        return view('dashboard_patient.Evisit.loadOnlineDoctors', compact('doctors', 'session', 'price','symp','id','loc_id'));
    }

    public function dash_get_online_doctors($id,$loc)
    {
        $user = Auth::user();
        $loc_id = $loc;
        if ($user->user_type == 'patient') {
                $states = DB::table('states')->where('active', '1')->get();
                $state = DB::table('states')->where('id',$loc_id)->first();
                $doctors = DB::table('doctor_licenses')
                    ->join('users', 'doctor_licenses.doctor_id', 'users.id')
                    ->join('specializations', 'specializations.id', 'users.specialization')
                    ->where('doctor_licenses.state_id', $loc_id)
                    ->where('users.specialization', $id)
                    ->where('users.status', 'online')
                    ->where('users.active', '1')
                    ->where('doctor_licenses.is_verified', '1')
                    ->select('users.*', 'specializations.name as sp_name')
                    ->paginate(10);
                //dd($doctors);
                foreach ($doctors as $doctor) {

                    $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);
                }
                $session = null;
                // $price = DB::table('specalization_price')->where('spec_id', $id)->first();
                $price = DB::table('specalization_price')->where('spec_id', $id)->where('state_id', $loc_id)->first();

                if ($price != null) {
                if ($price->follow_up_price != null) {
                    $session = DB::table('sessions')->where('patient_id', $user->id)
                        ->join('specializations', 'sessions.specialization_id', 'specializations.id')
                        ->join('specalization_price', 'sessions.specialization_id', 'specalization_price.spec_id')
                        ->select('specializations.name as sp_name', 'specalization_price.follow_up_price as price')
                        ->where('specialization_id', $id)->first();
                }}else{
                    return view('errors.101');
                }
                $symp = DB::table('isabel_symptoms')->get();
                // return view('dashboard_patient.Evisit.online_doctor', compact('doctors', 'session', 'id'));
                return view('dashboard_patient.Evisit.online_doctor', compact('doctors', 'session', 'id', 'price','symp','loc_id','states','state'));
        } else {
            return redirect()->route('home');
        }
    }

    public function send_doc_online_alert(Request $request)
    {
        $user = auth()->user();
        if($user->user_type == "patient")
        {
            // $job = (new \App\Jobs\SendMailJob($request->loc_id,$request->spec_id))
            // ->delay(
            // 	now()
            // 	->addSeconds(2)
            // );

            // dispatch($job);
            // return "success";
            $doctors = DB::table('doctor_licenses')
            ->join('users', 'doctor_licenses.doctor_id', 'users.id')
            ->where('doctor_licenses.state_id', $request->loc_id)
            ->where('users.specialization', $request->spec_id)
            ->where('users.active', '1')
            ->where('doctor_licenses.is_verified', '1')
            ->select('users.*')
            ->get();

            $data = [
                'name' => "doctor",
                'subject' => 'To Get Online For Patient',
                'body' => 'A patient is requested for an E-Visit and there is an opportunity to grab that patient for which you have to get online at',
            ];
            foreach ($doctors as $key => $doctor)
            {
                try
                {
                    $data['name'] = $doctor->name.' '.$doctor->last_name;
                    Mail::to($doctor->email)->send(new SendEmail($data));
                }
                catch(Exception $error){
                    return "error";
                }
            }

            return "success";
        }

        return "error";
    }

    public function patient_health_store(Request $request){
        if(count($request->all()) < 14){
            return redirect()->back()->with('error','Please fill the complete form');
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
            $loc_id = $request->loc_id;
            return view('dashboard_patient.Evisit.mood_disorder', compact('id','flag','loc_id'));
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
        $loc_id = $request->loc_id;
        return view('dashboard_patient.Evisit.anxiety_scale', compact('id','flag','loc_id'));
    }
    public function anxiety_scale_store(Request $request){
        // dd($request->all());
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

        DB::table('psychiatry_form')->where('id',$id)->update([
            'anxiety_scale' => $anxiety_scale,
            'updated_at' => NOW(),
        ]);
        $loc_id = $request->loc_id;
        if($flag == 'session'){
            return redirect()->route('psych_patient_online_doctors',['id'=>'21','loc'=>$loc_id]);
        }else if($flag == 'appointment'){
            return redirect()->route('psych_book_appointment',['id'=>'21','loc'=>$loc_id]);
        }
    }

    public function loadonlinedoctors($id)
    {
        $user = Auth::user();
        if ($user->user_type == 'patient') {
            $state = DB::table('users')->where('users.id', $user->id)->select('users.state_id')->first();
            $state = $state->state_id;
            $doctors = DB::table('doctor_licenses')
                ->join('users', 'doctor_licenses.doctor_id', 'users.id')
                ->join('specializations', 'specializations.id', 'users.specialization')
                ->where('doctor_licenses.state_id', $state)
                ->where('users.specialization', $id)
                ->where('users.status', 'online')
                ->where('users.active', '1')
                ->where('doctor_licenses.is_verified', '1')
                ->select('users.*', 'specializations.name as sp_name')
                ->get();

            foreach ($doctors as $doctor) {

                $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);
            }
            return view('patient.loadOnlineDoctors', compact('doctors', 'id'));
        } else {
            return redirect()->route('home');
        }
    }

    public function load_online_doctors($id)
    {
        $user = Auth::user();
        if ($user->user_type == 'patient') {
            $state = DB::table('users')->where('users.id', $user->id)->select('users.state_id')->first();
            $state = $state->state_id;
            $doctors = DB::table('doctor_licenses')
                ->join('users', 'doctor_licenses.doctor_id', 'users.id')
                ->join('specializations', 'specializations.id', 'users.specialization')
                ->where('doctor_licenses.state_id', $state)
                ->where('users.specialization', $id)
                ->where('users.status', 'online')
                ->where('users.active', '1')
                ->where('doctor_licenses.is_verified', '1')
                ->select('users.*', 'specializations.name as sp_name')
                ->get();
            // dd($doctors);

            // $doctors = DB::table('users')->where('user_type', 'doctor')
            //     ->join('doctor_licenses','doctor_licenses.doctor_id','users.id')
            //     ->join('specializations','specializations.id','users.specialization')
            //     ->join('states','states.id','users.state_id')
            //     ->where('users.specialization', $id)
            //     ->where('users.status', 'online')
            //     ->where('users.active', '1')
            //     ->where('doctor_licenses.is_verified','1')
            //     ->groupBy('doctor_licenses.doctor_id')
            //     ->select('users.*','specializations.name as sp_name')
            //     ->get();

            // dd($doctor);

            foreach ($doctors as $doctor) {

                $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);
            }
            return view('dashboard_patient.Evisit.loadOnlineDoctors', compact('doctors', 'id'));
        } else {
            return redirect()->route('home');
        }
    }

    public function store_symptoms_inquiry(Request $request)
    {
        // dd($request);
        $symp = $request->validate([
            'doc_sp_id' =>  ['required'],
            'doc_id' =>  ['required', 'string', 'max:255'],
            'Headache' =>  ['required', 'string', 'max:2'],
            'Flu' =>  ['required', 'string', 'max:2'],
            'Fever' =>  ['required', 'string', 'max:2'],
            'Nausea' =>  ['required', 'string', 'max:2'],
            'Others' =>  ['required', 'string', 'max:2'],
            'problem' =>  ['required', 'string', 'max:255'],
        ]);

        $patient_id = Auth::user()->id;
        $doc_id = $symp['doc_id'];
        // dd($patient_id);

        $symp_id = Symptom::create([
            'patient_id' =>  $patient_id,
            'doctor_id' => $symp['doc_id'],
            'headache' => $symp['Headache'],
            'flu' => $symp['Flu'],
            'fever' => $symp['Fever'],
            'nausea' => $symp['Nausea'],
            'others' => $symp['Others'],
            'description' => $symp['problem'],
            'status' => 'pending'
        ])->id;

        $session_price = "";
        if ($request->price != null) {
            $session_price = $request->price;
        } else {
            $session_price_get = DB::table('specalization_price')->where('spec_id', $request->doc_sp_id)->first();
            $session_price = $session_price_get->initial_price;
        }

        // $check_session_already_have=DB::table('sessions')
        //     ->where('doctor_id',$doc_id)
        //     ->where('patient_id',$patient_id)
        //     ->where('specialization_id',$request->doc_sp_id)
        //     ->count();
        //     // dd($check_session_already_have);


        // if($check_session_already_have>0)
        // {
        //     $session_price_get=DB::table('specalization_price')->where('spec_id',$request->doc_sp_id)->first();
        //     if($session_price_get->follow_up_price!=null)
        //     {
        //         $session_price=$session_price_get->follow_up_price;
        //     }else{
        //         $session_price=$session_price_get->initial_price;
        //     }

        // }
        // else{
        //     $session_price_get=DB::table('specalization_price')->where('spec_id',$request->doc_sp_id)->first();
        //     $session_price=$session_price_get->initial_price;

        // }
        // dd($session_price);
        $timestamp = time();
        $date = date('Y-m-d', $timestamp);
        $permitted_chars = 'abcdefghijklmnopqrstuvwxyz';
        $channelName = substr(str_shuffle($permitted_chars), 0, 8);
        $session_id = Session::create([
            'patient_id' =>  $patient_id,
            'doctor_id' =>  $doc_id,
            'date' =>  $date,
            'status' => 'pending',
            'queue' => 0,
            'symptom_id' => $symp_id,
            'remaining_time' => 'full',
            'channel' => $channelName,
            'price' => $session_price,
            'specialization_id' => $request->doc_sp_id,
        ])->id;

        return redirect()->route('session_payment_page', ['id' => $session_id]);
    }
    public function dash_store_symptoms_inquiry(Request $request)
    {
        $symp = $request->validate([
            'doc_sp_id' =>  ['required'],
            'doc_id' =>  ['required', 'string', 'max:255'],
            'problem' =>  ['required', 'string', 'max:255'],
        ]);

        $patient_id = Auth::user()->id;
        $doc_id = $symp['doc_id'];

        $symp_id = 0;

        $check_session_already_have = DB::table('sessions')
            ->where('doctor_id', $symp['doc_id'])
            ->where('patient_id', $patient_id)
            ->where('specialization_id', $request->doc_sp_id)
            ->count();

        $session_price = "";
        if ($check_session_already_have > 0) {
            $session_price_get = User::find($request->doc_id);
            // consultation_fee
            // followup_fee
            if ($session_price_get->followup_fee != null) {
                $session_price = $session_price_get->followup_fee;
            } else {
                $session_price = $session_price_get->consultation_fee;
            }
        } else {
            $session_price_get = User::find($request->doc_id);
            $session_price = $session_price_get->consultation_fee;
        }

        $timestamp = time();
        $date = date('Y-m-d', $timestamp);
        $permitted_chars = 'abcdefghijklmnopqrstuvwxyz';
        $channelName = substr(str_shuffle($permitted_chars), 0, 8);
        $get_last_session = DB::table('sessions')->where('doctor_id', $doc_id)->where('status', 'invitation sent')->orderBy('id', 'desc')->first();
        $queue = 0;
        if ($get_last_session != null) {
            $queue = $get_last_session->queue + 1;
        } else {
            $queue = 1;
        }

        $new_session_id;
        $randNumber=rand(11,99);
        $getLastSessionId = DB::table('sessions')->orderBy('id', 'desc')->first();
        if ($getLastSessionId != null) {
            $new_session_id = $getLastSessionId->session_id + 1+$randNumber;
        } else {
            $new_session_id = rand(311111,399999);
        }

        $session_id = Session::create([
            'patient_id' =>  $patient_id,
            'doctor_id' =>  $doc_id,
            'date' =>  $date,
            'status' => 'pending',
            'queue' => $queue,
            'symptom_id' => '',
            'remaining_time' => 'full',
            'channel' => $channelName,
            'price' => $session_price,
            'specialization_id' => $request->doc_sp_id,
            'session_id' => $new_session_id,
            'validation_status' => "valid",
        ])->id;

        $session = Session::find($session_id);
        $data = "Evisit-".$new_session_id."-1";
        $pay = new \App\Http\Controllers\MeezanPaymentController();
        $res = $pay->payment($data,($session->price*100));
        if (isset($res) && $res->errorCode == 0) {
            return redirect($res->formUrl);
        }else{
            return redirect()->back()->with('error','Sorry, we are currently facing server issues. Please try again later.');
        }
    }

    public function session_payment_page($session_id)
    {
        $session_data = DB::table('sessions')->where('id', $session_id)->first();
        $price = $session_data->price;
        $years = [];
        $current_year = Carbon::now()->format('Y');
        array_push($years, $current_year);
        $j = 15;
        for ($i = 0; $i < $j; $i++) {
            $get_year = $current_year += 1;
            array_push($years, $get_year);
        }
        $states = State::where('country_code', 'US')->get();
        return view('pay_session', compact('session_id', 'states', 'years', 'price'));
    }

    public function dash_session_payment_page($session_id)
    {
        $session_id = \Crypt::decrypt($session_id);
        $id = Auth::user()->id;
        $session_data = DB::table('sessions')->where('id', $session_id)->first();
        if($session_data->status == "pending")
        {
            $price = $session_data->price;
            $years = [];
            $current_year = Carbon::now()->format('Y');
            array_push($years, $current_year);
            $j = 15;
            for ($i = 0; $i < $j; $i++) {
                $get_year = $current_year += 1;
                array_push($years, $get_year);
            }
            $states = State::where('country_code', 'US')->get();
            $cards = DB::table('card_details')->where('user_id', $id)->get();

            return view('dashboard_patient.Evisit.payment', compact('session_id', 'states', 'years', 'price', 'cards'));
        }
        else
        {
            return redirect()->route("New_Patient_Dashboard");
        }
    }

    public function session_search(Request $request){
        $user = auth()->user();
        $user_type = $user->user_type;
        $user_time_zone = $user->timeZone;
        $user_state = Auth::user()->state_id;
        $state = State::find($user_state);
        if($request->session_id != null && $request->datefilter == null){
            if ($state->active == 1) {
                $user_id = $user->id;
                $sessions = Session::where('doctor_id', $user_id)
                    ->where('status', 'ended')
                    ->where('session_id', $request->session_id)
                    ->orderByDesc('id')
                    ->paginate(7);
                // $sessions=[];
                foreach ($sessions as $session) {

                    $getPersentage = DB::table('doctor_percentage')->where('doc_id', $user_id)->first();
                    $session->price = ($getPersentage->percentage / 100) * $session->price;

                    if ($session->status == 'ended' && $session->start_time != 'null' && $session->end_time != 'null') {
                        $session->date = User::convert_utc_to_user_timezone($user->id, $session->created_at)['date'];

                        $session->start_time = date('h:i A', strtotime('-15 minutes', strtotime($session->start_time)));
                        $session->start_time = User::convert_utc_to_user_timezone($user->id, $session->start_time)['time'];

                        $session->end_time = date('h:i A', strtotime('-15 minutes', strtotime($session->end_time)));
                        $session->end_time = User::convert_utc_to_user_timezone($user->id, $session->end_time)['time'];
                        // dd($session->end_time);
                        $pat = User::where('id', $session['patient_id'])->first();
                        $session->pat_name = $pat['name'] . " " . $pat['last_name'];

                        $links = AgoraAynalatics::where('channel', $session['channel'])->first();
                        if ($links != null) {
                            $recording = $links->video_link;
                            $session->recording = $recording;
                        } else {
                            $session->recording = 'No recording';
                        }

                        $referred_doc = Referal::where('session_id', $session['id'])
                            ->where('patient_id', $session['patient_id'])
                            ->where('doctor_id', $user_id)
                            ->leftjoin('users', 'referals.sp_doctor_id', 'users.id')
                            ->select('users.name', 'users.last_name')
                            ->get();
                        if (count($referred_doc)) {
                            $session->refered = "You Referred the Patient to Dr." . $referred_doc[0]->name . " " . $referred_doc[0]->last_name;
                        } else {
                            $session->refered = null;
                        }
                        $session->sympptoms = DB::table('symptoms')->where('id',$session['symptom_id'])->first();

                        $pres = Prescription::where('session_id', $session['id'])->get();
                        $pres_arr = [];
                        foreach ($pres as $prod) {
                            if ($prod['type'] == 'medicine') {
                                $product = AllProducts::where('id', $prod['medicine_id'])->first();
                            } else if ($prod['type'] == 'imaging') {
                                $product = AllProducts::where('id', $prod['imaging_id'])->first();
                            } else if ($prod['type'] == 'lab-test') {
                                $product = QuestDataTestCode::where('TEST_CD', $prod['test_id'])
                                    ->first();
                            }
                            $cart = Cart::where('doc_session_id', $session['id'])
                                ->where('pres_id', $prod->id)->first();
                            // dd($cart);
                            $prod->prod_detail = $product;
                            if (isset($cart->status))
                                $prod->cart_status = $cart->status;
                            else
                                $prod->cart_status = 'No record';
                            // dd($prod);
                            array_push($pres_arr, $prod);
                        }
                        $session->pres = $pres_arr;
                        // array_push($sessions,$session);
                    }
                }
            }
        }else if($request->session_id == null && $request->datefilter != null){
            $start_date = explode(' - ',$request->datefilter)[0];
            $end_date = explode(' - ',$request->datefilter)[1];

            $start_date = date('Y-m-d', strtotime($start_date));
            $end_date = date('Y-m-d', strtotime($end_date));

            // dd($start_date,$end_date);
            if ($state->active == 1) {
                $user_id = $user->id;
                $sessions = Session::where('doctor_id', $user_id)
                    ->where('status', 'ended')
                    ->where('date', '>=' ,$start_date)
                    ->where('date', '<=' ,$end_date)
                    ->paginate(7);
                // $sessions=[];
                foreach ($sessions as $session) {

                    $getPersentage = DB::table('doctor_percentage')->where('doc_id', $user_id)->first();
                    $session->price = ($getPersentage->percentage / 100) * $session->price;

                    if ($session->status == 'ended' && $session->start_time != 'null' && $session->end_time != 'null') {
                        $session->date = User::convert_utc_to_user_timezone($user->id, $session->created_at)['date'];

                        $session->start_time = date('h:i A', strtotime('-15 minutes', strtotime($session->start_time)));
                        $session->start_time = User::convert_utc_to_user_timezone($user->id, $session->start_time)['time'];

                        $session->end_time = date('h:i A', strtotime('-15 minutes', strtotime($session->end_time)));
                        $session->end_time = User::convert_utc_to_user_timezone($user->id, $session->end_time)['time'];
                        // dd($session->end_time);
                        $pat = User::where('id', $session['patient_id'])->first();
                        $session->pat_name = $pat['name'] . " " . $pat['last_name'];

                        $links = AgoraAynalatics::where('channel', $session['channel'])->first();
                        if ($links != null) {
                            $recording = $links->video_link;
                            $session->recording = $recording;
                        } else {
                            $session->recording = 'No recording';
                        }

                        $referred_doc = Referal::where('session_id', $session['id'])
                            ->where('patient_id', $session['patient_id'])
                            ->where('doctor_id', $user_id)
                            ->leftjoin('users', 'referals.sp_doctor_id', 'users.id')
                            ->select('users.name', 'users.last_name')
                            ->get();
                        if (count($referred_doc)) {
                            $session->refered = "You Referred the Patient to Dr." . $referred_doc[0]->name . " " . $referred_doc[0]->last_name;
                        } else {
                            $session->refered = null;
                        }
                        $session->sympptoms = DB::table('symptoms')->where('id',$session['symptom_id'])->first();

                        $pres = Prescription::where('session_id', $session['id'])->get();
                        $pres_arr = [];
                        foreach ($pres as $prod) {
                            if ($prod['type'] == 'medicine') {
                                $product = AllProducts::where('id', $prod['medicine_id'])->first();
                            } else if ($prod['type'] == 'imaging') {
                                $product = AllProducts::where('id', $prod['imaging_id'])->first();
                            } else if ($prod['type'] == 'lab-test') {
                                $product = QuestDataTestCode::where('TEST_CD', $prod['test_id'])
                                    ->first();
                            }
                            $cart = Cart::where('doc_session_id', $session['id'])
                                ->where('pres_id', $prod->id)->first();
                            // dd($cart);
                            $prod->prod_detail = $product;
                            if (isset($cart->status))
                                $prod->cart_status = $cart->status;
                            else
                                $prod->cart_status = 'No record';
                            // dd($prod);
                            array_push($pres_arr, $prod);
                        }
                        $session->pres = $pres_arr;
                        // array_push($sessions,$session);
                    }
                }
            }
        }else if($request->session_id != null && $request->datefilter != null){
            $start_date = explode(' - ',$request->datefilter)[0];
            $end_date = explode(' - ',$request->datefilter)[1];

            $start_date = date('Y-m-d', strtotime($start_date));
            $end_date = date('Y-m-d', strtotime($end_date));

            // dd($start_date,$end_date);
            if ($state->active == 1) {
                $user_id = $user->id;
                $sessions = Session::where('doctor_id', $user_id)
                    ->where('status', 'ended')
                    ->where('session_id', $request->session_id)
                    ->where('date', '>=' ,$start_date)
                    ->where('date', '<=' ,$end_date)
                    ->paginate(7);
                    // dd($sessions);
                // $sessions=[];
                foreach ($sessions as $session) {

                    $getPersentage = DB::table('doctor_percentage')->where('doc_id', $user_id)->first();
                    $session->price = ($getPersentage->percentage / 100) * $session->price;

                    if ($session->status == 'ended' && $session->start_time != 'null' && $session->end_time != 'null') {
                        $session->date = User::convert_utc_to_user_timezone($user->id, $session->created_at)['date'];

                        $session->start_time = date('h:i A', strtotime('-15 minutes', strtotime($session->start_time)));
                        $session->start_time = User::convert_utc_to_user_timezone($user->id, $session->start_time)['time'];

                        $session->end_time = date('h:i A', strtotime('-15 minutes', strtotime($session->end_time)));
                        $session->end_time = User::convert_utc_to_user_timezone($user->id, $session->end_time)['time'];
                        // dd($session->end_time);
                        $pat = User::where('id', $session['patient_id'])->first();
                        $session->pat_name = $pat['name'] . " " . $pat['last_name'];

                        $links = AgoraAynalatics::where('channel', $session['channel'])->first();
                        if ($links != null) {
                            $recording = $links->video_link;
                            $session->recording = $recording;
                        } else {
                            $session->recording = 'No recording';
                        }

                        $referred_doc = Referal::where('session_id', $session['id'])
                            ->where('patient_id', $session['patient_id'])
                            ->where('doctor_id', $user_id)
                            ->leftjoin('users', 'referals.sp_doctor_id', 'users.id')
                            ->select('users.name', 'users.last_name')
                            ->get();
                        if (count($referred_doc)) {
                            $session->refered = "You Referred the Patient to Dr." . $referred_doc[0]->name . " " . $referred_doc[0]->last_name;
                        } else {
                            $session->refered = null;
                        }
                        $session->sympptoms = DB::table('symptoms')->where('id',$session['symptom_id'])->first();

                        $pres = Prescription::where('session_id', $session['id'])->get();
                        $pres_arr = [];
                        foreach ($pres as $prod) {
                            if ($prod['type'] == 'medicine') {
                                $product = AllProducts::where('id', $prod['medicine_id'])->first();
                            } else if ($prod['type'] == 'imaging') {
                                $product = AllProducts::where('id', $prod['imaging_id'])->first();
                            } else if ($prod['type'] == 'lab-test') {
                                $product = QuestDataTestCode::where('TEST_CD', $prod['test_id'])
                                    ->first();
                            }
                            $cart = Cart::where('doc_session_id', $session['id'])
                                ->where('pres_id', $prod->id)->first();
                            // dd($cart);
                            $prod->prod_detail = $product;
                            if (isset($cart->status))
                                $prod->cart_status = $cart->status;
                            else
                                $prod->cart_status = 'No record';
                            // dd($prod);
                            array_push($pres_arr, $prod);
                        }
                        $session->pres = $pres_arr;
                        // array_push($sessions,$session);
                    }
                }
            }
        }else{
            if ($state->active == 1) {
                $user_id = $user->id;
                $sessions = Session::where('doctor_id', $user_id)
                    ->where('status', 'ended')
                    ->orderByDesc('id')
                    ->paginate(15);
                // $sessions=[];
                foreach ($sessions as $session) {

                    $getPersentage = DB::table('doctor_percentage')->where('doc_id', $user_id)->first();
                    $session->price = ($getPersentage->percentage / 100) * $session->price;

                    if ($session->status == 'ended' && $session->start_time != 'null' && $session->end_time != 'null') {
                        $session->date = User::convert_utc_to_user_timezone($user->id, $session->created_at)['date'];

                        $session->start_time = date('h:i A', strtotime('-15 minutes', strtotime($session->start_time)));
                        $session->start_time = User::convert_utc_to_user_timezone($user->id, $session->start_time)['time'];

                        $session->end_time = date('h:i A', strtotime('-15 minutes', strtotime($session->end_time)));
                        $session->end_time = User::convert_utc_to_user_timezone($user->id, $session->end_time)['time'];
                        // dd($session->end_time);
                        $pat = User::where('id', $session['patient_id'])->first();
                        $session->pat_name = $pat['name'] . " " . $pat['last_name'];

                        $links = AgoraAynalatics::where('channel', $session['channel'])->first();
                        if ($links != null) {
                            $recording = $links->video_link;
                            $session->recording = $recording;
                        } else {
                            $session->recording = 'No recording';
                        }

                        $referred_doc = Referal::where('session_id', $session['id'])
                            ->where('patient_id', $session['patient_id'])
                            ->where('doctor_id', $user_id)
                            ->leftjoin('users', 'referals.sp_doctor_id', 'users.id')
                            ->select('users.name', 'users.last_name')
                            ->get();
                        if (count($referred_doc)) {
                            $session->refered = "You Referred the Patient to Dr." . $referred_doc[0]->name . " " . $referred_doc[0]->last_name;
                        } else {
                            $session->refered = null;
                        }
                        $session->sympptoms = DB::table('symptoms')->where('id',$session['symptom_id'])->first();

                        $pres = Prescription::where('session_id', $session['id'])->get();
                        $pres_arr = [];
                        foreach ($pres as $prod) {
                            if ($prod['type'] == 'medicine') {
                                $product = AllProducts::where('id', $prod['medicine_id'])->first();
                            } else if ($prod['type'] == 'imaging') {
                                $product = AllProducts::where('id', $prod['imaging_id'])->first();
                            } else if ($prod['type'] == 'lab-test') {
                                $product = QuestDataTestCode::where('TEST_CD', $prod['test_id'])
                                    ->first();
                            }
                            $cart = Cart::where('doc_session_id', $session['id'])
                                ->where('pres_id', $prod->id)->first();
                            // dd($cart);
                            $prod->prod_detail = $product;
                            if (isset($cart->status))
                                $prod->cart_status = $cart->status;
                            else
                                $prod->cart_status = 'No record';
                            // dd($prod);
                            array_push($pres_arr, $prod);
                        }
                        $session->pres = $pres_arr;
                        // array_push($sessions,$session);
                    }
                }
            }
        }
        // dd($sessions);
        return view('dashboard_doctor.All_Session.index', compact('user_type', 'sessions'));
    }

    public function session_payment(Request $request)
    {
        // dd($request);
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
        $session = DB::table('sessions')->where('id', $request->session_id)->first();
        $name = $request->card_holder_name . $request->card_holder_name_middle;
        $city = City::find($request->city)->name;
        $state = State::find($request->state)->name;

        $input = [
            'info' => [
                'subject' => $request->subject,
                'user_id' => $session->patient_id,
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

        // dd($responce);
        if ($responce->success == 'true') {
            Session::where('id', $request->session_id)->update(['status' => 'paid']);
            try {

                $doctor = User::where('id', $session->doctor_id)->first();
                $patient = DB::table('users')->where('id', $session->patient_id)->first();
                $markDown = [
                    'doc_name' => ucwords($doctor->name),
                    'pat_name' => ucwords($patient->name),
                    'pat_email' => $patient->email,
                    'amount' => number_format((int)$request->amount_charge, 2),
                ];
                //mailgun send email to patient
                //Mail::to('baqir.redecom@gmail.com')->send(new EvisitBookMail($markDown));
                Mail::to($patient->email)->send(new EvisitBookMail($markDown));
                //  Mail::to($patient->email)->send(new EvisitBooking($getSessions));
            } catch (\Exception $e) {
                Log::error($e);
            }
            return redirect()->route('waiting_room', $request->session_id);
        } else {
            if (isset($responce->errors)) {
                foreach ($responce->errors as $error) {
                    $erroras = $error;
                }
                return redirect()->route('session_payment_page', ['id' => $request->session_id])->with('error_message', $erroras);
            } else {

                return redirect()->route('session_payment_page', ['id' => $request->session_id])->with('message', $responce->message);
            }
        }
    }

    public function dash_session_payment(Request $request)
    {
        // dd($request);
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
        $session = DB::table('sessions')->where('id', $request->session_id)->first();
        $name = $request->card_holder_name . $request->card_holder_name_middle;
        $city = City::find($request->city)->name;
        $state = State::find($request->state)->name;

        $input = [
            'info' => [
                'subject' => $request->subject,
                'user_id' => $session->patient_id,
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

        // dd($responce);
        if ($responce->success == 'true') {
            Session::where('id', $request->session_id)->update(['status' => 'paid']);
            try {

                $doctor = User::where('id', $session->doctor_id)->first();
                $patient = DB::table('users')->where('id', $session->patient_id)->first();
                $markDown = [
                    'doc_name' => ucwords($doctor->name),
                    'pat_name' => ucwords($patient->name),
                    'pat_email' => $patient->email,
                    'amount' => number_format((int)$request->amount_charge, 2),
                ];
                //mailgun send email to patient
                //Mail::to('baqir.redecom@gmail.com')->send(new EvisitBookMail($markDown));
                Mail::to($patient->email)->send(new EvisitBookMail($markDown));
                //  Mail::to($patient->email)->send(new EvisitBooking($getSessions));
            } catch (\Exception $e) {
                Log::error($e);
            }
            return redirect()->route('patient_waiting_room', $request->session_id);
        } else {
            if (isset($responce->errors)) {
                foreach ($responce->errors as $error) {
                    $erroras = $error;
                }
                return redirect()->route('patient_session_payment_page', ['id' => $request->session_id])->with('error_message', $erroras);
            } else {

                return redirect()->route('patient_session_payment_page', ['id' => $request->session_id])->with('message', $responce->message);
            }
        }
    }


    public function waiting_room(Request $request)
    {
        $session_id = $request['id'];
        $session = Session::find($request['id']);
        $pat_id = auth()->user()->id;
        // dd($session);
        $all_waiting = Session::where('doctor_id', $session['doctor_id'])
            ->where('patient_id', '!=', $pat_id)
            ->where('id', '<', $session_id)
            ->where('status', 'invitation sent')
            // ->whereNotIn('patient_id',$pat_id)
            ->groupBy('patient_id')
            ->get();

        $waiting_count = count($all_waiting);
        // dd($all_waiting);

        if ($session['status'] == 'invitation sent')
            $status = 'true';
        else
            $status = 'false';
        // dd($status);
        $doctor = User::where('id', $session['doctor_id'])->first();
        $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);

        return view('waiting_room', compact('doctor', 'session_id', 'status', 'waiting_count'));
    }

    public function waiting_room_pat(Request $request)
    {
        $session_id = $request['id'];
        $session_id = \Crypt::decrypt($session_id);
        // dd($session_id);
        $session = Session::find($session_id);
        if($session->status=="started")
        {
            return redirect()->route('pat_video_page', ['id' => \Crypt::encrypt($session->id)]);
        }
        elseif($session->status!="ended")
        {
            $pat_id = auth()->user()->id;
            // dd($session);
            $all_waiting = Session::where('doctor_id', $session['doctor_id'])
                ->where('patient_id', '!=', $pat_id)
                ->where('id', '<', $session_id)
                ->where('status', 'invitation sent')
                // ->whereNotIn('patient_id',$pat_id)
                ->groupBy('patient_id')
                ->get();

            $waiting_count = count($all_waiting);
            // dd($all_waiting);

            if ($session['status'] == 'invitation sent')
                $status = 'true';
            else
                $status = 'false';
            // dd($status);
            $doctor = User::where('id', $session['doctor_id'])->first();
            $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);

            return view('dashboard_patient.Waiting_room.index', compact('doctor', 'session_id', 'status', 'waiting_count'));
        }
        else
        {
            return redirect()->route('New_Patient_Dashboard');
        }
    }

    public function dash_waiting_room(Request $request,$id)
    {
        $request['id'] = \Crypt::decrypt($request['id']);
        $session_id = $request['id'];
        $session = Session::find($request['id']);
        $pat_id = auth()->user()->id;
        $all_waiting = Session::where('doctor_id', $session['doctor_id'])
            ->where('patient_id', '!=', $pat_id)
            ->where('id', '<', $session_id)
            ->where('status', 'invitation sent')
            // ->whereNotIn('patient_id',$pat_id)
            ->groupBy('patient_id')
            ->get();

        $waiting_count = count($all_waiting);

        if ($session['status'] == 'invitation sent')
            $status = 'true';
        else
            $status = 'false';
        // dd($status);
        $doctor = User::where('id', $session['doctor_id'])->first();
        $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);

        return view('dashboard_patient.Waiting_room.index', compact('doctor', 'session_id', 'status', 'waiting_count'));
    }
    public function destroy_waiting(Request $request)
    {
        $id = $request['id'];
        // $symp=Symptom::where('id',$id)->first();
        // $symp->delete();
        return redirect()->back();
    }
    // public function doctor_waiting_room(Request $request)
    // {

    //     $docid = $request['docid'];
    //     $pat_id = Auth::user()->id;
    //     $doc_status = User::find($docid)->status;
    //     if ($doc_status == 'online') {
    //         $all_waiting = Session::where('doctor_id', $docid)->where('patient_id', '!=', $pat_id)
    //             ->where('status', 'invitation sent')
    //             // ->whereNotIn('patient_id',$pat_id)
    //             ->groupBy('patient_id')
    //             ->get();
    //         $count = count($all_waiting);
    //         return $count;
    //     } else {
    //         return 'offline';
    //     }
    // }
    //doctor side
    public function doc_waiting_room(Request $request)
    {

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
            // dd($appointments);

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
                    $mints += 10;
                    $pat = DB::table('users')->where('id', $single_session['patient_id'])->select('name', 'last_name', 'user_image')->first();
                    $pat->user_image = \App\Helper::check_bucket_files_url($pat->user_image);

                    $patient_full_name = $pat->name . " " . $pat->last_name;
                    $single_session['patient_full_name'] = $patient_full_name;
                    $single_session['user_image'] = $pat->user_image;
                    array_push($sessions, $single_session);

                    try {
                        $firebase_ses = Session::where('id', $single_session['session_id'])->first();
                        $firebase_ses->received = false;
                        // \App\Helper::firebase($firebase_ses->doctor_id,'updateQuePatient',$firebase_ses->id,$firebase_ses);
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                    event(new updateQuePatient('update patient que'));
                }
                // return response()->json(['session'=>$sessions]);
                return view('waiting_room_doctor', compact('sessions'));
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
                        $mints += 10;
                        $pat = DB::table('users')->where('id', $single_session['patient_id'])->select('name', 'last_name', 'user_image')->first();
                        $pat->user_image = \App\Helper::check_bucket_files_url($pat->user_image);

                        $patient_full_name = $pat->name . " " . $pat->last_name;
                        $single_session['patient_full_name'] = $patient_full_name;
                        $single_session['user_image'] = $pat->user_image;
                        array_push($sessions, $single_session);
                        try {
                            $firebase_ses = Session::where('id', $single_session['session_id'])->first();
                            $firebase_ses->received = false;
                            // \App\Helper::firebase($firebase_ses->doctor_id,'updateQuePatient',$firebase_ses->id,$firebase_ses);
                        } catch (\Throwable $th) {
                            //throw $th;
                        }
                        event(new updateQuePatient('update patient que'));
                    }
                    // return response()->json($session_data);
                    return view('waiting_room_doctor', compact('sessions'));
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
                        $mints += 10;
                        $pat = DB::table('users')->where('id', $single_session['patient_id'])->select('name', 'last_name', 'user_image')->first();
                        $pat->user_image = \App\Helper::check_bucket_files_url($pat->user_image);
                        $patient_full_name = $pat->name . " " . $pat->last_name;
                        $single_session['patient_full_name'] = $patient_full_name;
                        $single_session['user_image'] = $pat->user_image;
                        array_push($sessions, $single_session);
                        try {
                            $firebase_ses = Session::where('id', $single_session['session_id'])->first();
                            $firebase_ses->received = false;
                            // \App\Helper::firebase($firebase_ses->doctor_id,'updateQuePatient',$firebase_ses->id,$firebase_ses);
                        } catch (\Throwable $th) {
                            //throw $th;
                        }
                        event(new updateQuePatient('update patient que'));
                    }
                    // return response()->json(['session'=>$sessions]);
                    return view('waiting_room_doctor', compact('sessions'));
                }
            }
        } else {
            return redirect()->route('errors', '101');
        }
    }


    public function waiting_room_load($id)
    {
        // $request->appointment_id;
        // $doc_id=$request->doctor_id;
        $doc_id = $id;
        $doc_zone = User::find($doc_id);

        $nowDate = Carbon::now()->timezone($doc_zone->timeZone)->format('Y-m-d');
        // dd($nowDate);

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
                $mints += 10;
                $pat = DB::table('users')->where('id', $single_session['patient_id'])->select('name', 'last_name', 'user_image')->first();
                $pat->user_image = \App\Helper::check_bucket_files_url($pat->user_image);
                $patient_full_name = $pat->name . " " . $pat->last_name;
                $single_session['patient_full_name'] = $patient_full_name;
                $single_session['user_image'] = $pat->user_image;
                array_push($sessions, $single_session);
                try {
                    $firebase_ses = Session::where('id', $single_session['session_id'])->first();
                    $firebase_ses->received = false;
                    // \App\Helper::firebase($firebase_ses->doctor_id,'updateQuePatient',$firebase_ses->id,$firebase_ses);
                } catch (\Throwable $th) {
                    //throw $th;
                }
                event(new updateQuePatient('update patient que'));
            }

            // return response()->json(['session'=>$sessions]);
            return view('load_patient_waiting_room', compact('sessions'));
        } else {

            $timestamp = date("Y-m-d H:i:s");
            $current_date_time = Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, 'UTC')->setTimezone('UTC');
            $app_date_time = $appointments->date . ' ' . $appointments->time;
            $appoint_dateTime = Carbon::createFromFormat('Y-m-d H:i:s', $app_date_time, auth()->user()->timeZone)->setTimezone('UTC');
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
                    $mints += 10;
                    $pat = DB::table('users')->where('id', $single_session['patient_id'])->select('name', 'last_name', 'user_image')->first();
                    $pat->user_image = \App\Helper::check_bucket_files_url($pat->user_image);
                    $patient_full_name = $pat->name . " " . $pat->last_name;
                    $single_session['patient_full_name'] = $patient_full_name;
                    $single_session['user_image'] = $pat->user_image;
                    array_push($sessions, $single_session);
                    try {
                        $firebase_ses = Session::where('id', $single_session['session_id'])->first();
                        $firebase_ses->received = false;
                        // \App\Helper::firebase($firebase_ses->doctor_id,'updateQuePatient',$firebase_ses->id,$firebase_ses);
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                    event(new updateQuePatient('update patient que'));
                }
                // return response()->json($session_data);
                return view('load_patient_waiting_room', compact('sessions'));
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
                    $mints += 10;
                    $pat = DB::table('users')->where('id', $single_session['patient_id'])->select('name', 'last_name', 'user_image')->first();
                    $pat->user_image = \App\Helper::check_bucket_files_url($pat->user_image);
                    $patient_full_name = $pat->name . " " . $pat->last_name;
                    $single_session['patient_full_name'] = $patient_full_name;
                    $single_session['user_image'] = $pat->user_image;
                    array_push($sessions, $single_session);
                    try {
                        $firebase_ses = Session::where('id', $single_session['session_id'])->first();
                        $firebase_ses->received = false;
                        // \App\Helper::firebase($firebase_ses->doctor_id,'updateQuePatient',$firebase_ses->id,$firebase_ses);
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                    event(new updateQuePatient('update patient que'));
                }


                return view('load_patient_waiting_room', compact('sessions'));
                // return response()->json(['session'=>$sessions]);
            }
        }
    }

    public function waiting_room_load_doc($id)
    {
        // $request->appointment_id;
        // $doc_id=$request->doctor_id;
        $doc_id = $id;
        $doc_zone = User::find($doc_id);

        $nowDate = Carbon::now()->timezone($doc_zone->timeZone)->format('Y-m-d');
        // dd($nowDate);

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
                ->select('id as session_id', 'patient_id', 'appointment_id','status')
                ->get();

            $sessions = [];
            $mints = 5;
            $doc_joined_pat = Session::where('doctor_id', $doc_id)
            ->where('status', 'doctor joined')
            ->orderBy('queue', 'ASC')
            ->select('id as session_id', 'patient_id', 'appointment_id','status')
            ->first();
            if($doc_joined_pat == null)
            {
                $mints = 5;
            }
            else
            {
                $pat = DB::table('users')->where('id', $doc_joined_pat['patient_id'])->select('name', 'last_name', 'user_image')->first();
                $pat->user_image = \App\Helper::check_bucket_files_url($pat->user_image);
                $patient_full_name = $pat->name . " " . $pat->last_name;
                $doc_joined_pat['patient_full_name'] = $patient_full_name;
                $doc_joined_pat['user_image'] = $pat->user_image;
                array_push($sessions, $doc_joined_pat);
                try {
                    $firebase_ses = Session::where('id', $doc_joined_pat['session_id'])->first();
                    $firebase_ses->received = false;
                    // \App\Helper::firebase($firebase_ses->doctor_id,'updateQuePatient',$firebase_ses->id,$firebase_ses);
                } catch (\Throwable $th) {
                    //throw $th;
                }
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
                try {
                    $firebase_ses = Session::where('id', $single_session['session_id'])->first();
                    $firebase_ses->received = false;
                    // \App\Helper::firebase($firebase_ses->doctor_id,'updateQuePatient',$firebase_ses->id,$firebase_ses);
                } catch (\Throwable $th) {
                    //throw $th;
                }
                event(new updateQuePatient('update patient que'));
            }

            // return response()->json(['session'=>$sessions]);
            return view('dashboard_doctor.doc_waiting_room.load_patient_waiting_room', compact('sessions'));
        } else {

            $timestamp = date("Y-m-d H:i:s");
            $current_date_time = Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, 'UTC')->setTimezone('UTC');
            $app_date_time = $appointments->date . ' ' . $appointments->time;
            $appoint_dateTime = Carbon::createFromFormat('Y-m-d H:i:s', $app_date_time, auth()->user()->timeZone)->setTimezone('UTC');
            $date = strtotime($appoint_dateTime);
            $date_time_plus = date('Y-m-d H:i:s', strtotime("+5 minute", $date));
            $date_time_subtract = date('Y-m-d H:i:s', strtotime("-15 minute", $date));


            if ($current_date_time >= $date_time_subtract  && $current_date_time <= $date_time_plus) {
                $a_sessions = Session::where('doctor_id', $doc_id)
                    ->where('appointment_id', $appointments->appointment_id)
                    ->groupBy('patient_id')
                    ->select('id as session_id', 'patient_id', 'appointment_id','status')
                    ->get()
                    ->toArray();

                $e_sessions = Session::where('doctor_id', $doc_id)
                    ->where('status', 'invitation sent')
                    ->where('appointment_id', '=', null)
                    ->groupBy('patient_id')
                    ->orderBy('queue', 'ASC')
                    ->select('id as session_id', 'patient_id', 'appointment_id','status')
                    ->get()
                    ->toArray();

                $data_sessions = array_merge($a_sessions, $e_sessions);

                $sessions = [];
                $mints = 5;
                $doc_joined_pat = Session::where('doctor_id', $doc_id)
                ->where('status', 'doctor joined')
                ->orderBy('queue', 'ASC')
                ->select('id as session_id', 'patient_id', 'appointment_id','status')
                ->first();
                if($doc_joined_pat == null)
                {
                    $mints = 5;
                }
                else
                {
                    $pat = DB::table('users')->where('id', $doc_joined_pat['patient_id'])->select('name', 'last_name', 'user_image')->first();
                    $pat->user_image = \App\Helper::check_bucket_files_url($pat->user_image);
                    $patient_full_name = $pat->name . " " . $pat->last_name;
                    $doc_joined_pat['patient_full_name'] = $patient_full_name;
                    $doc_joined_pat['user_image'] = $pat->user_image;
                    array_push($sessions, $doc_joined_pat);
                    try {
                        $firebase_ses = Session::where('id', $doc_joined_pat['session_id'])->first();
                        $firebase_ses->received = false;
                        // \App\Helper::firebase($firebase_ses->doctor_id,'updateQuePatient',$firebase_ses->id,$firebase_ses);
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
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
                    try {
                        $firebase_ses = Session::where('id', $single_session['session_id'])->first();
                        $firebase_ses->received = false;
                        // \App\Helper::firebase($firebase_ses->doctor_id,'updateQuePatient',$firebase_ses->id,$firebase_ses);
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                    event(new updateQuePatient('update patient que'));
                }
                // return response()->json($session_data);
                return view('dashboard_doctor.doc_waiting_room.load_patient_waiting_room', compact('sessions'));
            } else {
                $data_sessions = Session::where('doctor_id', $doc_id)
                    ->where('status', 'invitation sent')
                    ->groupBy('patient_id')
                    ->orderBy('queue', 'ASC')
                    ->select('id as session_id', 'patient_id', 'appointment_id','status')
                    ->get();

                $sessions = [];
                $mints = 5;
                $doc_joined_pat = Session::where('doctor_id', $doc_id)
                ->where('status', 'doctor joined')
                ->orderBy('queue', 'ASC')
                ->select('id as session_id', 'patient_id', 'appointment_id','status')
                ->first();
                if($doc_joined_pat == null)
                {
                    $mints = 5;
                }
                else
                {
                    $pat = DB::table('users')->where('id', $doc_joined_pat['patient_id'])->select('name', 'last_name', 'user_image')->first();
                    $pat->user_image = \App\Helper::check_bucket_files_url($pat->user_image);
                    $patient_full_name = $pat->name . " " . $pat->last_name;
                    $doc_joined_pat['patient_full_name'] = $patient_full_name;
                    $doc_joined_pat['user_image'] = $pat->user_image;
                    array_push($sessions, $doc_joined_pat);
                    try {
                        //code...
                        $firebase_ses = Session::where('id', $doc_joined_pat['session_id'])->first();
                        $firebase_ses->received = false;
                        // \App\Helper::firebase($firebase_ses->doctor_id,'updateQuePatient',$firebase_ses->id,$firebase_ses);
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
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
                    try {
                        //code...
                        $firebase_ses = Session::where('id', $single_session['session_id'])->first();
                        $firebase_ses->received = false;
                        // \App\Helper::firebase($firebase_ses->doctor_id,'updateQuePatient',$firebase_ses->id,$firebase_ses);
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                    event(new updateQuePatient('update patient que'));
                }


                return view('dashboard_doctor.doc_waiting_room.load_patient_waiting_room', compact('sessions'));
                // return response()->json(['session'=>$sessions]);
            }
        }
    }


    public function waiting_room_my(Request $request)
    {

        $doc_id = auth()->user()->id;
        $doc_zone = auth()->user()->timeZone;

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
                $mints += 10;
                $pat = DB::table('users')->where('id', $single_session['patient_id'])->select('name', 'last_name', 'user_image')->first();
                $pat->user_image = \App\Helper::check_bucket_files_url($pat->user_image);
                $patient_full_name = $pat->name . " " . $pat->last_name;
                $single_session['patient_full_name'] = $patient_full_name;
                $single_session['user_image'] = $pat->user_image;
                array_push($sessions, $single_session);
                try {
                    $firebase_ses = Session::where('id', $single_session['session_id'])->first();
                    $firebase_ses->received = false;
                    // \App\Helper::firebase($firebase_ses->doctor_id,'updateQuePatient',$firebase_ses->id,$firebase_ses);
                } catch (\Throwable $th) {
                    //throw $th;
                }
                event(new updateQuePatient('update patient que'));
            }

            // return response()->json(['session'=>$sessions]);
            return view('load_patient_waiting_room', compact('sessions'));
        } else {

            $timestamp = date("Y-m-d H:i:s");
            $current_date_time = Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, 'UTC')->setTimezone('UTC');
            $app_date_time = $appointments->date . ' ' . $appointments->time;
            $appoint_dateTime = Carbon::createFromFormat('Y-m-d H:i:s', $app_date_time, auth()->user()->timeZone)->setTimezone('UTC');
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
                    $mints += 10;
                    $pat = DB::table('users')->where('id', $single_session['patient_id'])->select('name', 'last_name', 'user_image')->first();
                    $pat->user_image = \App\Helper::check_bucket_files_url($pat->user_image);
                    $patient_full_name = $pat->name . " " . $pat->last_name;
                    $single_session['patient_full_name'] = $patient_full_name;
                    $single_session['user_image'] = $pat->user_image;
                    array_push($sessions, $single_session);
                    try {
                        $firebase_ses = Session::where('id', $single_session['session_id'])->first();
                        $firebase_ses->received = false;
                        // \App\Helper::firebase($firebase_ses->doctor_id,'updateQuePatient',$firebase_ses->id,$firebase_ses);
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                    event(new updateQuePatient('update patient que'));
                }
                // return response()->json($session_data);
                return view('load_patient_waiting_room', compact('sessions'));
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
                    $mints += 10;
                    $pat = DB::table('users')->where('id', $single_session['patient_id'])->select('name', 'last_name', 'user_image')->first();
                    $pat->user_image = \App\Helper::check_bucket_files_url($pat->user_image);
                    $patient_full_name = $pat->name . " " . $pat->last_name;
                    $single_session['patient_full_name'] = $patient_full_name;
                    $single_session['user_image'] = $pat->user_image;
                    array_push($sessions, $single_session);
                    try {
                        $firebase_ses = Session::where('id', $single_session['session_id'])->first();
                        $firebase_ses->received = false;
                        // \App\Helper::firebase($firebase_ses->doctor_id,'updateQuePatient',$firebase_ses->id,$firebase_ses);
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                    event(new updateQuePatient('update patient que'));
                }


                return view('load_patient_waiting_room', compact('sessions'));
                // return response()->json(['session'=>$sessions]);
            }
        }
    }

    public function waiting_room_my_doc(Request $request)
    {

        $doc_id = auth()->user()->id;
        $doc_zone = auth()->user()->timeZone;

        $nowDate = Carbon::now()->timezone($doc_zone)->format('Y-m-d');

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
                ->select('id as session_id', 'patient_id', 'appointment_id','status')
                ->get();

            $sessions = [];
            $mints = 5;
            $doc_joined_pat = Session::where('doctor_id', $doc_id)
            ->where('status', 'doctor joined')
            ->orderBy('queue', 'ASC')
            ->select('id as session_id', 'patient_id', 'appointment_id','status')
            ->first();
            if($doc_joined_pat == null)
            {
                $mints = 5;
            }
            else
            {
                $pat = DB::table('users')->where('id', $doc_joined_pat['patient_id'])->select('name', 'last_name', 'user_image')->first();
                $pat->user_image = \App\Helper::check_bucket_files_url($pat->user_image);
                $patient_full_name = $pat->name . " " . $pat->last_name;
                $doc_joined_pat['patient_full_name'] = $patient_full_name;
                $doc_joined_pat['user_image'] = $pat->user_image;
                array_push($sessions, $doc_joined_pat);
                try {
                    $firebase_ses = Session::where('id', $doc_joined_pat['session_id'])->first();
                    $firebase_ses->received = false;
                    // \App\Helper::firebase($firebase_ses->doctor_id,'updateQuePatient',$firebase_ses->id,$firebase_ses);
                }catch (\Throwable $th) {
                    //throw $th;
                }
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
                try {
                    $firebase_ses = Session::where('id', $single_session['session_id'])->first();
                    $firebase_ses->received = false;
                    // \App\Helper::firebase($firebase_ses->doctor_id,'updateQuePatient',$firebase_ses->id,$firebase_ses);
                } catch (\Throwable $th) {
                    //throw $th;
                }
                event(new updateQuePatient('update patient que'));
            }

            // return response()->json(['session'=>$sessions]);
            return view('dashboard_doctor.doc_waiting_room.load_patient_waiting_room', compact('sessions'));
        } else {

            $timestamp = date("Y-m-d H:i:s");
            $current_date_time = Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, 'UTC')->setTimezone('UTC');
            $app_date_time = $appointments->date . ' ' . $appointments->time;
            $appoint_dateTime = Carbon::createFromFormat('Y-m-d H:i:s', $app_date_time, auth()->user()->timeZone)->setTimezone('UTC');
            $date = strtotime($appoint_dateTime);
            $date_time_plus = date('Y-m-d H:i:s', strtotime("+5 minute", $date));
            $date_time_subtract = date('Y-m-d H:i:s', strtotime("-15 minute", $date));


            if ($current_date_time >= $date_time_subtract  && $current_date_time <= $date_time_plus) {
                $a_sessions = Session::where('doctor_id', $doc_id)
                    ->where('appointment_id', $appointments->appointment_id)
                    ->groupBy('patient_id')
                    ->select('id as session_id', 'patient_id', 'appointment_id','status')
                    ->get()
                    ->toArray();

                $e_sessions = Session::where('doctor_id', $doc_id)
                    ->where('status', 'invitation sent')
                    ->where('appointment_id', '=', null)
                    ->groupBy('patient_id')
                    ->orderBy('queue', 'ASC')
                    ->select('id as session_id', 'patient_id', 'appointment_id','status')
                    ->get()
                    ->toArray();

                $data_sessions = array_merge($a_sessions, $e_sessions);

                $sessions = [];
                $mints = 5;
                $doc_joined_pat = Session::where('doctor_id', $doc_id)
                ->where('status', 'doctor joined')
                ->orderBy('queue', 'ASC')
                ->select('id as session_id', 'patient_id', 'appointment_id','status')
                ->first();
                if($doc_joined_pat == null)
                {
                    $mints = 5;
                }
                else
                {
                    $pat = DB::table('users')->where('id', $doc_joined_pat['patient_id'])->select('name', 'last_name', 'user_image')->first();
                    $pat->user_image = \App\Helper::check_bucket_files_url($pat->user_image);
                    $patient_full_name = $pat->name . " " . $pat->last_name;
                    $doc_joined_pat['patient_full_name'] = $patient_full_name;
                    $doc_joined_pat['user_image'] = $pat->user_image;
                    array_push($sessions, $doc_joined_pat);
                    try {
                        //code...
                        $firebase_ses = Session::where('id', $doc_joined_pat['session_id'])->first();
                        $firebase_ses->received = false;
                        // \App\Helper::firebase($firebase_ses->doctor_id,'updateQuePatient',$firebase_ses->id,$firebase_ses);
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
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
                    try {
                        //code...
                        $firebase_ses = Session::where('id', $single_session['session_id'])->first();
                        $firebase_ses->received = false;
                        // \App\Helper::firebase($firebase_ses->doctor_id,'updateQuePatient',$firebase_ses->id,$firebase_ses);
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                    event(new updateQuePatient('update patient que'));
                }
                // return response()->json($session_data);
                return view('dashboard_doctor.doc_waiting_room.load_patient_waiting_room', compact('sessions'));
            } else {
                $data_sessions = Session::where('doctor_id', $doc_id)
                    ->where('status', 'invitation sent')
                    ->groupBy('patient_id')
                    ->orderBy('queue', 'ASC')
                    ->select('id as session_id', 'patient_id', 'appointment_id','status')
                    ->get();

                $sessions = [];
                $mints = 5;
                $doc_joined_pat = Session::where('doctor_id', $doc_id)
                ->where('status', 'doctor joined')
                ->orderBy('queue', 'ASC')
                ->select('id as session_id', 'patient_id', 'appointment_id','status')
                ->first();
                if($doc_joined_pat == null)
                {
                    $mints = 5;
                }
                else
                {
                    $pat = DB::table('users')->where('id', $doc_joined_pat['patient_id'])->select('name', 'last_name', 'user_image')->first();
                    $pat->user_image = \App\Helper::check_bucket_files_url($pat->user_image);
                    $patient_full_name = $pat->name . " " . $pat->last_name;
                    $doc_joined_pat['patient_full_name'] = $patient_full_name;
                    $doc_joined_pat['user_image'] = $pat->user_image;
                    array_push($sessions, $doc_joined_pat);
                    try {
                        //code...
                        $firebase_ses = Session::where('id', $doc_joined_pat['session_id'])->first();
                        $firebase_ses->received = false;
                        // \App\Helper::firebase($firebase_ses->doctor_id,'updateQuePatient',$firebase_ses->id,$firebase_ses);
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
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
                    try {
                        //code...
                        $firebase_ses = Session::where('id', $single_session['session_id'])->first();
                        $firebase_ses->received = false;
                        // \App\Helper::firebase($firebase_ses->doctor_id,'updateQuePatient',$firebase_ses->id,$firebase_ses);
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                    event(new updateQuePatient('update patient que'));
                }


                return view('dashboard_doctor.doc_waiting_room.load_patient_waiting_room', compact('sessions'));
                // return response()->json(['session'=>$sessions]);
            }
        }
    }

    public function doctor_patient_queue(Request $request)
    {
        $user = auth()->user();
        // $user_state = $user->state_id;
        // $state = State::find($user_state);
        // if ($state->active == 1) {
            $session_check = Session::where('doctor_id',$user->id)->where('status','started')->first();
            if($session_check!=null){
                return redirect()->route('doc_video_page', ['id' => \Crypt::encrypt($session_check->id)]);
            } else{
            $doc_id = $user->id;
            $doc_zone = $user->timeZone;
            $nowDate = Carbon::now()->timezone($doc_zone)->format('Y-m-d');
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
                    ->select('id as session_id', 'patient_id', 'appointment_id','status')
                    ->get();

                $sessions = [];
                $mints = 5;
                $doc_joined_pat = Session::where('doctor_id', $doc_id)
                ->where('status', 'doctor joined')
                ->orderBy('queue', 'ASC')
                ->select('id as session_id', 'patient_id', 'appointment_id','status')
                ->first();
                if($doc_joined_pat == null)
                {
                    $mints = 5;
                }
                else
                {
                    $pat = DB::table('users')->where('id', $doc_joined_pat['patient_id'])->select('name', 'last_name', 'user_image')->first();
                    $pat->user_image = \App\Helper::check_bucket_files_url($pat->user_image);
                    $patient_full_name = $pat->name . " " . $pat->last_name;
                    $doc_joined_pat['patient_full_name'] = $patient_full_name;
                    $doc_joined_pat['user_image'] = $pat->user_image;
                    array_push($sessions, $doc_joined_pat);
                    try {
                        //code...
                        $firebase_ses = Session::where('id', $doc_joined_pat['session_id'])->first();
                        $firebase_ses->received = false;
                        // \App\Helper::firebase($firebase_ses->doctor_id,'updateQuePatient',$firebase_ses->id,$firebase_ses);
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
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
                    try {
                        //code...
                        $firebase_ses = Session::where('id', $single_session['session_id'])->first();
                        $firebase_ses->received = false;
                        // \App\Helper::firebase($firebase_ses->doctor_id,'updateQuePatient',$firebase_ses->id,$firebase_ses);
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                    event(new updateQuePatient('update patient que'));
                }
                return view('dashboard_doctor.doc_waiting_room.index', compact('sessions'));
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
                        ->select('id as session_id', 'patient_id', 'appointment_id','status')
                        ->get()
                        ->toArray();

                    $e_sessions = Session::where('doctor_id', $doc_id)
                        ->where('status', 'invitation sent')
                        ->where('appointment_id', '=', null)
                        ->groupBy('patient_id')
                        ->orderBy('queue', 'ASC')
                        ->select('id as session_id', 'patient_id', 'appointment_id','status')
                        ->get()
                        ->toArray();

                    $data_sessions = array_merge($a_sessions, $e_sessions);

                    $sessions = [];
                    $mints = 5;
                    $doc_joined_pat = Session::where('doctor_id', $doc_id)
                    ->where('status', 'doctor joined')
                    ->orderBy('queue', 'ASC')
                    ->select('id as session_id', 'patient_id', 'appointment_id','status')
                    ->first();
                    if($doc_joined_pat == null)
                    {
                        $mints = 5;
                    }
                    else
                    {
                        $pat = DB::table('users')->where('id', $doc_joined_pat['patient_id'])->select('name', 'last_name', 'user_image')->first();
                        $pat->user_image = \App\Helper::check_bucket_files_url($pat->user_image);
                        $patient_full_name = $pat->name . " " . $pat->last_name;
                        $doc_joined_pat['patient_full_name'] = $patient_full_name;
                        $doc_joined_pat['user_image'] = $pat->user_image;
                        array_push($sessions, $doc_joined_pat);
                        try {
                            //code...
                            $firebase_ses = Session::where('id', $doc_joined_pat['session_id'])->first();
                            $firebase_ses->received = false;
                            // \App\Helper::firebase($firebase_ses->doctor_id,'updateQuePatient',$firebase_ses->id,$firebase_ses);
                        } catch (\Throwable $th) {
                            //throw $th;
                        }
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
                        try {
                            //code...
                            $firebase_ses = Session::where('id', $single_session['session_id'])->first();
                            $firebase_ses->received = false;
                            // \App\Helper::firebase($firebase_ses->doctor_id,'updateQuePatient',$firebase_ses->id,$firebase_ses);
                        } catch (\Throwable $th) {
                            //throw $th;
                        }
                        event(new updateQuePatient('update patient que'));
                    }
                    // return response()->json($session_data);
                    return view('dashboard_doctor.doc_waiting_room.index', compact('sessions'));
                } else {
                    $data_sessions = Session::where('doctor_id', $doc_id)
                        ->where('status', 'invitation sent')
                        ->groupBy('patient_id')
                        ->orderBy('queue', 'ASC')
                        ->select('id as session_id', 'patient_id', 'appointment_id','status')
                        ->get();

                    $sessions = [];
                    $mints = 5;
                    $doc_joined_pat = Session::where('doctor_id', $doc_id)
                    ->where('status', 'doctor joined')
                    ->orderBy('queue', 'ASC')
                    ->select('id as session_id', 'patient_id', 'appointment_id','status')
                    ->first();
                    if($doc_joined_pat == null)
                    {
                        $mints = 5;
                    }
                    else
                    {
                        $pat = DB::table('users')->where('id', $doc_joined_pat['patient_id'])->select('name', 'last_name', 'user_image')->first();
                        $pat->user_image = \App\Helper::check_bucket_files_url($pat->user_image);
                        $patient_full_name = $pat->name . " " . $pat->last_name;
                        $doc_joined_pat['patient_full_name'] = $patient_full_name;
                        $doc_joined_pat['user_image'] = $pat->user_image;
                        array_push($sessions, $doc_joined_pat);
                        try {
                            //code...
                            $firebase_ses = Session::where('id', $doc_joined_pat['session_id'])->first();
                            $firebase_ses->received = false;
                            // \App\Helper::firebase($firebase_ses->doctor_id,'updateQuePatient',$firebase_ses->id,$firebase_ses);
                        } catch (\Throwable $th) {
                            //throw $th;
                        }
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
                        try {
                            //code...
                            $firebase_ses = Session::where('id', $single_session['session_id'])->first();
                            $firebase_ses->received = false;
                            // \App\Helper::firebase($firebase_ses->doctor_id,'updateQuePatient',$firebase_ses->id,$firebase_ses);
                        } catch (\Throwable $th) {
                            //throw $th;
                        }
                        event(new updateQuePatient('update patient que'));
                    }
                    // return response()->json(['session'=>$sessions]);
                    return view('dashboard_doctor.doc_waiting_room.index', compact('sessions'));
                }
            }
            }
        // } else {
        //     return redirect()->route('errors', '101');
        // }
    }

    public function modal_change_status(Request $request)
    {
        event(new loadOnlineDoctor('run'));
        $doc = auth()->user();

        if ($doc->status == 'online') {
            $inQueue = Session::where(['doctor_id' => $doc->id, 'status' => 'invitation sent'])->first();
            if (isset($inQueue->id)) {
                $data = "online";
            } else {
                User::where('id', $doc['id'])->update(['status' => 'offline']);
                $data = "offline";
            }
        } else {
            $data = "online";
        }
        try {
            $data1 = DB::table('users')->where('id',$doc->id)->select('id','status')->first();
            $data1->id = (string)$doc->id;
            $data1->received = "false";
            // \App\Helper::firebaseOnlineDoctor('loadOnlineDoctor',$doc->id,$data1);
        } catch (\Throwable $th) {
            throw $th;
        }
        return $data;
    }

    public function change_status(Request $request)
    {
        event(new loadOnlineDoctor('run'));
        $doc = auth()->user();
        if ($doc->status == 'online') {
            $inQueue = Session::where(['doctor_id' => $doc->id, 'status' => 'invitation sent'])->first();
            if (isset($inQueue->id)) {
                return 'online';
            } else {
                User::where('id', $doc['id'])->update(['status' => 'offline']);
                try {
                    $data = DB::table('users')->where('id',$doc->id)->select('id','status')->first();
                    $data->id = (string)$doc->id;
                    $data->received = "false";
                    // \App\Helper::firebaseOnlineDoctor('loadOnlineDoctor',$doc->id,$data);
                } catch (\Throwable $th) {
                    throw $th;
                }
                return 'offline';
            }
        } else {
            User::where('id', $doc['id'])->update(['status' => 'online']);
            try {
                $data = DB::table('users')->where('id',$doc->id)->select('id','status')->first();
                $data->id = (string)$doc->id;
                $data->received = "false";
                // \App\Helper::firebaseOnlineDoctor('loadOnlineDoctor',$doc->id,$data);
            } catch (\Throwable $th) {
                throw $th;
            }
            return 'online';
        }
    }
    public function check_status(Request $request)
    {
        //addcommit
        // event(new loadOnlineDoctor('run'));
        $doc = auth()->user()->status;
        //dd($doc);
        return $doc;
    }

    public function patient_check_status(Request $request)
    {
        $user = User::find($request->id);
        $session = DB::table('sessions')->where('id',$request->session_id)->first();
        $doc = $user->status;
        return $doc;
    }

    public function change_doc_online_status(Request $request)
    {
        event(new loadOnlineDoctor('run'));
        $user = User::find($request->user);
        $doc = auth()->user()->id;
        try {
            $data = DB::table('users')->where('id',$doc->id)->select('id','status')->first();
            if($data->status == "online"){
                $data->status = "offline";
            }else{
                $data->status = "online";
            }
            $data->id = (string)$doc->id;
            $data->received = "false";
            // \App\Helper::firebaseOnlineDoctor('loadOnlineDoctor',$doc->id,$data);
        } catch (\Throwable $th) {
            throw $th;
        }
        if ($doc != null) {
            Log::info($request->user . " = " . $doc);
            if ($user->id == $doc) {
                return 'yes';
            } else {
                return 'no';
            }
        }
    }
    public function all_patients(Request $request)
    {
        $user = auth()->user();
        // dd($user);
        $patients = DB::table('sessions')
            ->join('users', 'sessions.patient_id', '=', 'users.id')
            ->where('sessions.doctor_id', $user->id)
            ->orderBy('sessions.date', 'DESC')
            ->groupBy('sessions.patient_id')
            ->select('sessions.*', 'users.user_image', DB::raw('MAX(sessions.id) as last_id'))
            ->paginate(12);
        // if ($user->specialization != '1') {
        //     $patients = DB::table('referals')
        //         ->where('sp_doctor_id', $user->id)
        //         ->where('referals.status', 'accepted')
        //         ->join('users', 'referals.patient_id', '=', 'users.id')
        //         ->join('sessions', 'sessions.patient_id', '=', 'users.id')
        //         ->select('*', 'referals.doctor_id as doc_id', 'users.user_image', DB::raw('MAX(sessions.id) as last_id'))
        //         ->orderBy('sessions.date', 'DESC')
        //         ->groupBy('sessions.patient_id')
        //         ->paginate(12);
        //     //  dd($patients);
        // } else {
        //     $patients = DB::table('sessions')
        //         ->join('users', 'sessions.patient_id', '=', 'users.id')
        //         ->where('doctor_id', $user->id)
        //         ->orderBy('date', 'DESC')
        //         ->groupBy('patient_id')
        //         ->select('sessions.*', 'users.user_image', DB::raw('MAX(sessions.id) as last_id'))
        //         ->paginate(12);
        //     // dd($patients);

        // }
        foreach ($patients as $patient) {
            // dd($user['specialization']);
            $user_details = User::where('id', $patient->patient_id)->first();
            $patient->pat_name = $user_details['name'] . " " . $user_details['last_name'];
            // if ($user['specialization'] != '1' && $user['specialization'] != null) {
            //     $user = User::where('id', $patient->doctor_id)->first();
            //     // dd($user);
            //     $patient->doc_name = $user['name'] . " " . $user['last_name'];
            // }
            // // echo $patient->doc_name;
            // else {
            //     $user_details = User::where('id', $patient->patient_id)->first();
            //     $patient->pat_name = $user_details['name'] . " " . $user_details['last_name'];
            // }

            $session = Session::find($patient->last_id);
            $patient->last_visit = Helper::get_date_with_format($session->date);
            $patient->last_diagnosis = $session->diagnosis;
            $patient->user_image = \App\Helper::check_bucket_files_url($patient->user_image);
        }
        // die();
        $all_patients = $patients;
        return view('patients', compact('all_patients'));
    }
    public function dash_all_patients(Request $request)
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
        return view('dashboard_doctor.All_patient.index', compact('all_patients'));
    }
    public function doc_pay_details(Request $request)
    {
        $doc = User::find($request['id']);
        $doc_name = Helper::get_name($request['id']);
        return view('superadmin.doc_pay_details', compact('doc', 'doc_name'));
    }
    public function check_queue(Request $request)
    {
        // $tt = NOW(). '- INTERVAL 2 MINUTE';
        $doc_id = $request['doc_id'];
        $pat_id = auth()->user()->id;
        $session_id = $request['session_id'];
        $session = Session::find($session_id);
        // $sessions=Session::where('status','invitation sent')->where('queue',1)->orderByDesc('id')->get();
        // $ended=Session::where('status','ended')->where('queue',1)->first();
        // $db=DB::table('sessions')->select('*')->where('updated_at', '>' ,$tt )->orderBy('id','desc')->get();
        $all_waiting = Session::where('doctor_id', $doc_id)
            ->where('patient_id', '!=', $pat_id)
            ->where('sequence', '<', $session['sequence'])
            // ->where('status','invitation sent')
            ->where('status', 'invitation sent')->orWhere('status', 'doctor joined')
            ->where('queue', 1)
            ->groupBy('patient_id')
            ->get();

        // $dt = new DateTime();
        return  $all_waiting;
    }
    public function waiting_pat(Request $request)
    {
        event(new RealTimeMessage($request['doc_id']));

        $doc_id = $request['doc_id'];

        $session_id = $request['session_id'];
        $session = Session::find($session_id);
        $pat_id = auth()->user()->id;
        $all_waiting = Session::where('doctor_id', $doc_id)
            ->where('patient_id', '!=', $pat_id)
            // ->where('id','<',$session_id)
            ->where('sequence', '<', $session['sequence'])
            ->where('status', 'invitation sent')->orWhere('status', 'doctor joined')
            ->where('queue', 1)
            ->groupBy('patient_id')
            ->get();
        return $all_waiting;
    }
    // public function waiting_room_my(Request $request)
    // {
    //     $doc_id=auth()->user()->id;
    //     $sessions=Session::where('doctor_id',$doc_id)
    //                 ->where('status', 'invitation sent')
    //                 ->groupBy('patient_id')
    //                 ->orderBy('', 'ASC')
    //                 ->get();
    //     foreach($sessions as $session){
    //         $pat=User::where('id',$session['patient_id'])->first();
    //         $session->patient=$pat;
    //     }
    //     return view('load_patient_waiting_room',compact('sessions'));
    // }


    public function get_refill_requests()
    {
        $user = auth()->user();
        $refills = RefillRequest::where('doctor_id', $user['id'])
            ->where('granted', '0')
            ->where('session_req', '0')
            ->with([
                'prescription' => function ($q) {
                    $q->select('prescriptions.*', DB::raw('DATEDIFF(NOW(), prescriptions.created_at) AS days'));
                },
                'session' => function ($q) {
                    $q->select('id', 'sessions.*', DB::raw('DATE_FORMAT(date, "%M, %d %Y") as date'), DB::raw('DATE_FORMAT(start_time, "%H:%i:%s") as start_time'), DB::raw('DATE_FORMAT(end_time, "%H:%i:%s") as end_time'));
                },
                'patient', 'doctor', 'product'
            ])->get();
        // $refills = DB::table('refill_requests')
        // ->join('prescriptions','refill_requests.pres_id','prescriptions.id')
        // ->where('doctor_id', $user['id'])
        // ->where('granted', '0')
        // ->where('session_req', '0')
        // ->select('refill_requests.*','prescriptions.med_days','prescriptions.med_unit','prescriptions.med_time','prescriptions.updated_at','prescriptions.quantity')
        // ->get();
        //dd($refills);
        return view('doctor.pharmacy.refill_requests', compact('refills'));
    }
    public function patient_refill_requests()
    {
        $user = auth()->user();
        $refills = RefillRequest::where('doctor_id', $user['id'])
            ->where('granted', '0')
            ->where('session_req', '0')
            ->with([
                'prescription' => function ($q) {
                    $q->select('prescriptions.*', DB::raw('DATEDIFF(NOW(), prescriptions.created_at) AS days'));
                },
                'session' => function ($q) {
                    $q->select('id', 'sessions.*', DB::raw('DATE_FORMAT(date, "%M, %d %Y") as date'), DB::raw('DATE_FORMAT(start_time, "%H:%i:%s") as start_time'), DB::raw('DATE_FORMAT(end_time, "%H:%i:%s") as end_time'));
                },
                'patient', 'doctor', 'product'
            ])->get();
        // $refills = DB::table('refill_requests')
        // ->join('prescriptions','refill_requests.pres_id','prescriptions.id')
        // ->where('doctor_id', $user['id'])
        // ->where('granted', '0')
        // ->where('session_req', '0')
        // ->select('refill_requests.*','prescriptions.med_days','prescriptions.med_unit','prescriptions.med_time','prescriptions.updated_at','prescriptions.quantity')
        // ->get();
        //dd($refills);
        return view('dashboard_doctor.Refill.index', compact('refills'));
    }
    public function grant_refill(Request $request)
    {
        //dd($request);
        RefillRequest::where('id', $request->refill_id)->update(['granted' => '1']);
        $refill = RefillRequest::where('id', $request->refill_id)->first();
        // dd($refill);
        $pres = Prescription::where('id', $refill['pres_id'])->first();
        $price = DB::table('medicine_pricings')->where('id',$pres['price'])->first();
        $pres['price'] = $price->sale_price;
        $refill->pres = $pres;
        $cart = Cart::where('pres_id', $pres['id'])->first();
        $prod = AllProducts::where('id', $pres['medicine_id'])->first();
        // dd($prod);
        $refill->prod = $prod;
        //dd($cart);
        //dd($request['dose'],$pres['med_days'],$request['qauntity']);

        $pres_new = Prescription::create([
            'medicine_id' => $pres['medicine_id'],
            'session_id' => $refill['session_id'],
            'type' => $prod['mode'],
            'comment' => $request['instructions'],
            'usage' => 'Dosage: Every ' . $request['dose'] . ' for ' . $pres['med_days'] . ' days',
            'quantity' => $request['qauntity'],
            'med_days' => $pres['med_days'],
            'med_unit' => $pres['med_unit'],
            'med_time' => $pres['med_time'],
            'price' => $pres['price'],
            'parent_id' => $pres['id']
        ]);

        Cart::create([
            'product_id' => $prod['id'],
            'name' => $prod['name'],
            'product_image' => $prod['featured_image'],
            'quantity' => $request['qauntity'],
            'price' => $pres['price'],
            'update_price' => $pres['price'],
            'product_mode' => $prod['mode'],
            'user_id' => $refill['patient_id'],
            'doc_id' => $refill['doctor_id'],
            'doc_session_id' => $refill['session_id'],
            'pres_id' => $pres_new['id'],
            'item_type' => 'prescribed',
            'status' => 'recommended',
            'refill_flag' => $request->refill_id,
            'map_marker_id' => $cart['map_marker_id']
        ]);
        try {
            $doc_user = DB::table('users')->where('id', $refill['doctor_id'])->first();
            $pat_user = DB::table('users')->where('id', $refill['patient_id'])->first();
            $data = [
                'doc_name' => $doc_user->name,
                'pat_name' => $pat_user->name,
                'pat_email' => $pat_user->email,
            ];
            // Mail::to('baqir.redecom@gmail.com')->send(new RefillCompleteMailToPatient($data));
            Mail::to($pat_user->email)->send(new RefillCompleteMailToPatient($data));

            $text = "Refill Request from Dr " . $doc_user->name . " is granted";
            $notification_id = Notification::create([
                'user_id' =>  $pat_user->id,
                'type' => '/my/cart',
                'text' => $text,
                'refill_id' => $refill['id'],
            ]);
            $data = [
                'user_id' =>  $pat_user->id,
                'type' => '/my/cart',
                'text' => $text,
                'refill_id' => $refill['id'],
                'received' => 'false',
                'session_id' => 'null',
                'appoint_id' => 'null',
            ];
            // \App\Helper::firebase($pat_user->id,'notification',$notification_id->id,$data);
            event(new RealTimeMessage($pat_user->id));
        } catch (\Exception $e) {
            Log::error($e);
        }
        return redirect()->back();
    }




    public function getSpecializedDoctors(Request $request)
    {
        $session = Session::where('id', $request['session'])->first();
        $spec_docs = User::where('user_type', 'doctor')->where('id', '!=', auth()->user()->id)->where('specialization', $request['id'])->where('status', '!=', 'ban')->where('active', 1)->get();
        $referBtn = 0;
        foreach ($spec_docs as $doc) {
            $refered = Referal::where('session_id', $session['session_id'])->where('sp_doctor_id', $doc->id)->first();
            if ($refered != null) {
                $doc->refered = true;
                $referBtn = 1;
                $doc->refer_id = $refered->id;
            } else {
                $doc->refered = false;
            }
            $doc->user_image = \App\Helper::check_bucket_files_url($doc->user_image);
        }
        return array($spec_docs, $referBtn);
    }



    public function cancelReferal(Request $request)
    {
        $referBtn = 0;
        $session_id = "";

        $ref_rec = Referal::find($request['id']);

        $session_id = $ref_rec->session_id;

        $session_data = Session::find($session_id);

        $res = Referal::where('id', $request['id'])->delete();
        if ($res) {
            $spec_docs = User::where('user_type', 'doctor')->where('id', '!=', auth()->user()->id)->where('specialization', $session_data->specialization_id)->get();
            foreach ($spec_docs as $doc) {
                $refered = Referal::where('session_id', $session_id)->where('sp_doctor_id', $doc->id)->first();
                if ($refered != null) {
                    $doc->refered = true;
                    $referBtn = 1;
                    $doc->refer_id = $refered->id;
                } else {
                    $doc->refered = false;
                }

                $doc->user_image = \App\Helper::check_bucket_files_url($doc->user_image);
            }
            return array($spec_docs, $referBtn);
        }
    }
    public function newSendReferal(Request $request)
    {

        $res = Referal::create([
            'session_id' => $request->session,
            'doctor_id' => $request->doctor_id,
            'sp_doctor_id' => $request->refer_doc_id,
            'patient_id' => $request->patient_id,
            'comment' => $request->comment,
            'status' => 'created'
        ]);

        if ($res) {
            $spec_docs = DB::table('users')
                ->where('user_type', 'doctor')
                ->where('id', '!=', $request->doctor_id)
                ->where('specialization', $request->spec)
                ->where('status', '!=', 'ban')
                ->where('active', 1)
                ->paginate(4);

            $referBtn = 0;
            foreach ($spec_docs as $doc) {
                $refered = Referal::where('session_id', $request->session)->where('sp_doctor_id', $doc->id)->first();
                if ($refered != null) {
                    $doc->refered = true;
                    $referBtn = 1;
                    $doc->refer_id = $refered->id;
                } else {
                    $doc->refered = false;
                }
                $doc->user_image = \App\Helper::check_bucket_files_url($doc->user_image);
            }
            return view('dashboard_doctor.Video.refer_paginate', compact('spec_docs','referBtn'));
        }
    }
    public function newCancelReferal(Request $request)
    {
        $res = DB::table('referals')->where('id', $request->refer_id)->delete();
        if($request->ajax()){
            if ($res) {
                $spec_docs = DB::table('users')
                    ->where('user_type', 'doctor')
                    ->where('id', '!=', $request->doctor_id)
                    ->where('specialization', $request->spec)
                    ->where('status', '!=', 'ban')
                    ->where('active', 1)
                    ->paginate(4);

                $referBtn = 0;
                foreach ($spec_docs as $doc) {
                    $refered = Referal::where('session_id', $request->session)->where('sp_doctor_id', $doc->id)->first();
                    if ($refered != null) {
                        $doc->refered = true;
                        $referBtn = 1;
                        $doc->refer_id = $refered->id;
                    } else {
                        $doc->refered = false;
                    }
                    $doc->user_image = \App\Helper::check_bucket_files_url($doc->user_image);
                }
                return view('dashboard_doctor.Video.refer_paginate', compact('spec_docs','referBtn'));
            }
        }
    }

    public function sendReferal(Request $request)
    {
        $referBtn = 0;
        $input = $request->all();
        $doc_id = auth()->user()->id;
        $check_refer = Referal::where('session_id', $input['session'])->first();
        if ($check_refer != null) {
            Referal::where('session_id', $input['session'])->delete();

            $session = Session::where('id', $input['session'])->first();
            $res = Referal::create([
                'session_id' => $input['session'],
                'doctor_id' => $doc_id,
                'sp_doctor_id' => $input['id'],
                'patient_id' => $session['patient_id'],
                'comment' => $input['comment'],
                'status' => 'created'
            ]);
            if ($res) {
                $spec_docs = User::where('user_type', 'doctor')
                    ->where('id', '!=', auth()->user()->id)
                    ->where('specialization', $session->specialization_id)
                    ->get();
                foreach ($spec_docs as $doc) {
                    $refered = Referal::where('session_id', $input['session'])->where('sp_doctor_id', $doc->id)->first();

                    if ($refered != null) {
                        $doc->refered = true;
                        $referBtn = 1;
                        $doc->refer_id = $refered->id;
                    } else {
                        $doc->refered = false;
                    }
                    $doc->user_image = \App\Helper::check_bucket_files_url($doc->user_image);
                }
                return array($spec_docs, $referBtn);
            }
        } else {

            $session = Session::where('id', $input['session'])->first();
            $res = Referal::create([
                'session_id' => $input['session'],
                'doctor_id' => $doc_id,
                'sp_doctor_id' => $input['id'],
                'patient_id' => $session['patient_id'],
                'comment' => $input['comment'],
                'status' => 'created'
            ]);
            if ($res) {
                $spec_docs = User::where('user_type', 'doctor')
                    ->where('id', '!=', auth()->user()->id)
                    ->where('specialization', $session->specialization_id)
                    ->get();
                foreach ($spec_docs as $doc) {
                    $refered = Referal::where('session_id', $input['session'])->where('sp_doctor_id', $doc->id)->first();

                    if ($refered != null) {
                        $doc->refered = true;
                        $referBtn = 1;
                        $doc->refer_id = $refered->id;
                    } else {
                        $doc->refered = false;
                    }

                    $doc->user_image = \App\Helper::check_bucket_files_url($doc->user_image);
                }
                return array($spec_docs, $referBtn);
            }
        }
    }




    public function get_dosage_info(Request $request)
    {
        $id = $request['refill_id'];
        $response = RefillRequest::where('id', $id)
            ->with([
                'prescription' => function ($q) {
                    $q->select('prescriptions.*', DB::raw('DATEDIFF(NOW(), prescriptions.created_at) AS days'));
                },
                'session' => function ($q) {
                    $q->select('id', 'sessions.*', DB::raw('DATE_FORMAT(date, "%M, %d %Y") as date'), DB::raw('DATE_FORMAT(start_time, "%H:%i:%s") as start_time'), DB::raw('DATE_FORMAT(end_time, "%H:%i:%s") as end_time'));
                },
                'patient', 'doctor', 'product'
            ])->first();

        // $response['refill'] = RefillRequest::find($id);
        // $response['pres'] = Prescription::find($response['refill']['pres_id']);
        // $response['prod'] = AllProducts::find($response['refill']['prod_id']);
        //dd($response);
        return $response;
    }

    public function request_session_schedule(Request $request)
    {
        //dd($request);
        $doctorID=Auth::user()->id;
        $AvailabilityStart = $request->date.' '.$request->startTimePicker;
        $AvailabilityStart = User::convert_user_timezone_to_utc($doctorID,$AvailabilityStart)['datetime'];
        $AvailabilityEnd = $request->date.' '.$request->endTimePicker;
        $AvailabilityEnd = User::convert_user_timezone_to_utc($doctorID,$AvailabilityEnd)['datetime'];
        if($AvailabilityStart!=null && $AvailabilityEnd!=null)
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
                   $this->send_session_req($request);
                }

            }
        }
        return redirect()->back();
    }

    public function req_session(Request $request)
    {
        $refill = RefillRequest::find($request['id']);
        $session_data = DB::table('sessions')->where('id', $refill->session_id)->first();
        $doc = User::find($refill->doctor_id);
        $pat = User::find($refill->patient_id);
        $doc_name = $doc->name . ' ' . $doc->last_name;
        $pat_name = $pat->name . ' ' . $pat->last_name;
        $doc_schedule = DB::table('doctor_schedules')->where('doctorId', $refill->doctor_id)->where('date','>=',today())->get();
        $flag = '0';
        if(count($doc_schedule) == 0){
            $doc_detail = array(
                'pat_name' => $pat_name,
                'doc_name' => $doc_name,
            );
            $flag = '1';
        }else{
            foreach ($doc_schedule as $sch) {
                $bookedSlots=DB::table('appointments')->where('date',$sch->date)->where('doctor_id',$refill->doctor_id)->count();
                $formatted_dt1=Carbon::parse($sch->start);
                $formatted_dt2=Carbon::parse($sch->end);
                $hours_diff = $formatted_dt1->diffInHours($formatted_dt2)*3;
                if($bookedSlots != $hours_diff){
                    $flag = '0';
                }
            }
        }
        return $flag;
    }

    public function send_session_req(Request $request)
    {
        $refill = RefillRequest::find($request['id']);
        $session_data = DB::table('sessions')->where('id', $refill->session_id)->first();
        $doc = User::find($refill->doctor_id);
        $pat = User::find($refill->patient_id);
        $doc_name = $doc->name . ' ' . $doc->last_name;
        $pat_name = $pat->name . ' ' . $pat->last_name;
        // Uncomment the next line
        RefillRequest::where('id', $request['id'])->update(['session_req' => '1']);

        try {
            $patient_data = array(
                'pat_name' => $pat_name,
                'doc_name' => $doc_name,
                'refill_comment' => $refill->comment,
            );
            Mail::to($pat->email)->send(new RequestSessionPatientMail($patient_data));
            $notification_id = Notification::create([
                'user_id' => $refill->patient_id,
                'text' => 'Dr. ' . $doc_name . ' requested a session with you',
                'type' => 'book/appointment/' . $session_data->specialization_id,
                'refill_id' => $request['id']
            ]);
            $data = [
                'user_id' => $refill->patient_id,
                'text' => 'Dr. ' . $doc_name . ' requested a session with you',
                'type' => 'book/appointment/' . $session_data->specialization_id,
                'refill_id' => $request['id'],
                'received' => 'false',
                'session_id' => 'null',
                'appoint_id' => 'null',
            ];
            // \App\Helper::firebase($u->id,'notification',$notification_id->id,$data);
            event(new RealTimeMessage($refill->patient_id));
        } catch (\Throwable $th) {
            return redirect()->back();
        }
        return redirect()->back();
    }
    public function our_doctors(Request $request)
    {
        if (Auth::check()) {
            $doctors = User::where('user_type', 'doctor')->where('active', '1')
                ->where('state_id', Auth::user()->state_id)
                ->where('country_id', Auth::user()->country_id)
                ->addSelect([
                    'spec' => Specialization::select('name')
                        ->whereColumn('id', 'users.specialization')
                ])
                ->orderByDesc('rating')->paginate(24);
            foreach ($doctors as $doctor) {
                $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);
            }
        } else {
            $doctors = User::where('user_type', 'doctor')->where('active', '1')
                ->addSelect([
                    'spec' => Specialization::select('name')
                        ->whereColumn('id', 'users.specialization')
                ])
                ->orderByDesc('rating')->paginate(24);
            foreach ($doctors as $doctor) {
                $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);
            }
        }

        // return view('all_doctors', compact('doctors'));
        return view('website_pages.doctors', compact('doctors'));
    }


    public function getstatesSelect(Request $request)
    {
        $keyword = $request['term'];

        $data = DB::table('states')
            ->select('id', 'name as text')
            ->where('name', 'like', '%' . $keyword . '%')->where('country_code', 'US')
            ->get();

        return json_encode($data);
    }

    public function isabel_inquiry(Request $request){
        //dd($request->all());
        $sym = IsabelController::proquery($request->symptoms);
        if($sym != "Invalid Authentication."){
            $pro_sym = IsabelController::getsymptoms($sym,$request->Pregnancy);
            $isabel_symp_id = DB::table('isabel_session_diagnosis')->insertGetId([
                'triage_api_url' => $pro_sym->triage_api_url,
                'diagnoses' => serialize($pro_sym->diagnoses),
            ]);
        }else{
            $isabel_symp_id = 0;
        }


        $patient_id = Auth::user()->id;
        $doc_id = $request->doc_id;

        $temp = '';
        foreach ($request->symptoms as $prob) {
            $temp = $temp . $prob . ",";
        }
        $symp_id = Symptom::create([
            'patient_id' =>  $patient_id,
            'doctor_id' => $request->doc_id,
            'headache' => '0',
            'flu' => '0',
            'fever' => '0',
            'nausea' => '0',
            'others' => '0',
            'description' => $request->problem,
            'symptoms_text' => $temp,
            'status' => 'pending',
        ])->id;

        $check_session_already_have = DB::table('sessions')
            ->where('doctor_id', $request->doc_id)
            ->where('patient_id', $patient_id)
            ->where('specialization_id', $request->doc_sp_id)
            ->count();


        $session_price = "";
        if ($check_session_already_have > 0) {
            $session_price_get = DB::table('specalization_price')->where('spec_id', $request->doc_sp_id)->where('state_id', auth()->user()->state_id)->first();
            if ($session_price_get->follow_up_price != null) {
                $session_price = $session_price_get->follow_up_price;
            } else {
                $session_price = $session_price_get->initial_price;
            }
        } else {
            $session_price_get = DB::table('specalization_price')->where('spec_id', $request->doc_sp_id)->where('state_id', auth()->user()->state_id)->first();
            $session_price = $session_price_get->initial_price;
        }

        $timestamp = time();
        $date = date('Y-m-d', $timestamp);
        $permitted_chars = 'abcdefghijklmnopqrstuvwxyz';
        $channelName = substr(str_shuffle($permitted_chars), 0, 8);
        $get_last_session = DB::table('sessions')->where('doctor_id', $doc_id)->where('status', 'invitation sent')->orderBy('id', 'desc')->first();
        $queue = 0;
        if ($get_last_session != null) {
            $queue = $get_last_session->queue + 1;
        } else {
            $queue = 1;
        }
        $new_session_id;
        if(isset($request->ses_id)){
            $session_id = $request->ses_id;
            $sesion = DB::table('sessions')->where('id',$session_id)->select('session_id')->first();
            $new_session_id = $sesion->session_id;
            Session::where('id',$session_id)->update([
                'patient_id' =>  $patient_id,
                'doctor_id' =>  $doc_id,
                'date' =>  $date,
                'status' => 'paid',
                'queue' => $queue,
                'symptom_id' => $symp_id,
                'remaining_time' => 'full',
                'channel' => $channelName,
                'price' => $session_price,
                'specialization_id' => $request->doc_sp_id,
                'session_id' => $new_session_id,
                'isabel_diagnosis_id' => $isabel_symp_id,
                'location_id' => $request->loc_id,
                'validation_status' => "valid",
            ]);
            return redirect()->route('waiting_room_pat', ['id' => \Crypt::encrypt($session_id)]);
        }else{
            $randNumber=rand(11,99);
            $getLastSessionId = DB::table('sessions')->orderBy('id', 'desc')->first();
            if ($getLastSessionId != null) {
                $new_session_id = $getLastSessionId->session_id + 1+$randNumber;
            } else {
                $new_session_id = rand(311111,399999);
            }
            $session_id = Session::create([
                'patient_id' =>  $patient_id,
                'doctor_id' =>  $doc_id,
                'date' =>  $date,
                'status' => 'pending',
                'queue' => $queue,
                'symptom_id' => $symp_id,
                'remaining_time' => 'full',
                'channel' => $channelName,
                'price' => $session_price,
                'specialization_id' => $request->doc_sp_id,
                'session_id' => $new_session_id,
                'isabel_diagnosis_id' => $isabel_symp_id,
                'location_id' => $request->loc_id,
                'validation_status' => "valid",
            ])->id;
            return redirect()->route('patient_session_payment_page', ['id' => \Crypt::encrypt($session_id)]);

        }

    }

    public function dash_paid_getonlinedoctors($id,$ses_id)
    {
        $ses_id = $ses_id;
        $user = Auth::user();
        if ($user->user_type == 'patient') {
            $state = DB::table('sessions')->where('id', $ses_id)->first();
            $loc_id = $state->location_id;
            $state = DB::table('states')->where('id', $loc_id)->first();
            $doctors = DB::table('doctor_licenses')
                ->join('users', 'doctor_licenses.doctor_id', 'users.id')
                ->join('specializations', 'specializations.id', 'users.specialization')
                ->where('doctor_licenses.state_id', $loc_id)
                ->where('users.specialization', $id)
                ->where('users.status', 'online')
                ->where('users.active', '1')
                ->where('doctor_licenses.is_verified', '1')
                ->select('users.*', 'specializations.name as sp_name')
                ->paginate(10);
            //dd($doctors);
            foreach ($doctors as $doctor) {

                $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);
            }
            $session = null;
            // $price = DB::table('specalization_price')->where('spec_id', $id)->first();
            $price = DB::table('specalization_price')->where('spec_id', $id)->where('state_id', $loc_id)->first();

            if ($price != null) {
            if ($price->follow_up_price != null) {
                $session = DB::table('sessions')->where('patient_id', $user->id)
                    ->join('specializations', 'sessions.specialization_id', 'specializations.id')
                    ->join('specalization_price', 'sessions.specialization_id', 'specalization_price.spec_id')
                    ->select('specializations.name as sp_name', 'specalization_price.follow_up_price as price')
                    ->where('specialization_id', $id)->first();
            }}else{
                return view('errors.101');
            }
            // if(count($doctors)==0)
            // {
            //     SendMailJob::dispatch($loc_id,$id);
            //     //\Illuminate\Support\Facades\Artisan::call('queue:work');
            // }
            $symp = DB::table('isabel_symptoms')->get();
            // return view('dashboard_patient.Evisit.online_doctor', compact('doctors', 'session', 'id'));
            return view('dashboard_patient.EvisitPaid.online_doctor', compact('doctors', 'session', 'id', 'price','symp','ses_id','loc_id','state'));
        } else {
            return redirect()->route('home');
        }
    }

    public function doctor_therapy_video($id)
    {
        if(auth()->user()->user_type == 'doctor'){
            $therapy_patients = DB::table('therapy_patients')->where('session_id',$id)->get();
            $patients = DB::table('therapy_patients')->where('session_id',$id)->count();
            if($patients>0)
            {
                $time_check = DB::table('therapy_session')->where('id',$id)->first();
                if($time_check->end_time==null)
                {
                    DB::table('therapy_session')->where('id',$id)->update(['status'=>'started','end_time'=>date('Y-m-d H:i:s')]);
                }
                $therapy_session = DB::table('therapy_session')->where('id',$id)->first();
                $doc = DB::table('users')->where('id',$therapy_session->doctor_id)->first();
                $therapy_session->doc_name =  'Dr.'.$doc->name.' '.$doc->last_name;
                $therapy_session->pat_name = '';
                $therapy_patients = DB::table('therapy_patients')->where('session_id',$id)->get()->toArray();
                $therapy_patients = json_encode($therapy_patients);
                $time = strtotime(date('Y-m-d H:i:s'))-strtotime($therapy_session->end_time);
                return view('dashboard_doctor.Video.conference_video',compact('therapy_session','therapy_patients','time'));
            }
            return redirect()->back()->with('msg','No patient enrolled therefore you could not start a session');
        }else{

            return redirect()->route('wrong_address');
        }
    }

    public function end_therapy_video($id)
    {
        DB::table('therapy_session')->where('id',$id)->update(['status'=>'ended','end_time'=>date('Y-m-d H:i:s')]);
        event(new EndConferenceCall($id));
        return redirect()->route('home');
    }

    public function view_psychiatrist_form(){
        $information = DB::table('psychiatrist_info')->where('doctor_id',auth()->user()->id)->first();
        $states = DB::table('doctor_licenses')
        ->join('states', 'states.id', 'doctor_licenses.state_id')
        ->where('doctor_id',auth()->user()->id)
        ->get();
        return view('dashboard_doctor.psychiatrist_profile.psychiatrist_info',compact('information','states'));
    }

    public function doctor_profile_management(){
        return view('dashboard_doctor.Profile.index');
    }

    public function get_doctor_details(){
        $doctor = DB::table('doctor_details')->where('doctor_id',auth()->user()->id)->first();
        $doctor->certificates = json_decode($doctor->certificates);
        $doctor->conditions = json_decode($doctor->conditions);
        $doctor->procedures = json_decode($doctor->procedures);
        return response()->json(['doctor'=>$doctor]);
    }

    public function get_doctor_details_by_id($id){
        $doctor = DB::table('doctor_details')->where('doctor_id',$id)->first();
        if($doctor){
            $doctor->certificates = json_decode($doctor->certificates);
            $doctor->conditions = json_decode($doctor->conditions);
            $doctor->procedures = json_decode($doctor->procedures);
            return response()->json(['doctor'=>$doctor]);
        }
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
                'helping' => $request->helping,
                'issue' => $request->issue,
                'specialties' => $request->specialties,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        return redirect()->route('doctor_profile_management');
    }

    public function admin_add_doctor_details(Request $request){
        $doctor = DB::table('doctor_details')->where('doctor_id',$request->doctor_id)->first();
        if($doctor){
            $certi = json_encode($request->certificates);
            $condi = json_encode($request->conditions);
            $proce = json_encode($request->procedures);
            DB::table('doctor_details')->where('doctor_id',$request->doctor_id)->update([
                'doctor_id' => $request->doctor_id,
                'certificates' => $certi,
                'conditions' => $condi,
                'procedures' => $proce,
                'location' => $request->location,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'about' => $request->about,
                'education' => $request->education,
                'helping' => $request->helping,
                'issue' => $request->issue,
                'specialties' => $request->specialties,
                'experience' => $request->experience,
                'updated_at' => now(),
            ]);
        }else{
            $certi = json_encode($request->certificates);
            $condi = json_encode($request->conditions);
            $proce = json_encode($request->procedures);
            DB::table('doctor_details')->insert([
                'doctor_id' => $request->doctor_id,
                'certificates' => $certi,
                'conditions' => $condi,
                'procedures' => $proce,
                'location' => $request->location,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'about' => $request->about,
                'education' => $request->education,
                'helping' => $request->helping,
                'issue' => $request->issue,
                'specialties' => $request->specialties,
                'experience' => $request->experience,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        if(Auth::user()->user_type == "admin"){
            return redirect()->route('admin_doctor_profile_management');
        }elseif(Auth::user()->user_type == "admin_seo"){
            return redirect()->route('seo_doctor_profile_management');
        }
    }

    public function edit_psychiatrist_form($id){
        $information = DB::table('psychiatrist_info')
            ->join('therapy_session','psychiatrist_info.event_id','therapy_session.event_id')
            ->where('psychiatrist_info.event_id',$id)
            ->where('psychiatrist_info.doctor_id',auth()->user()->id)
            ->select('psychiatrist_info.*','therapy_session.start_time as date')
            ->first();
        $information->date = User::convert_utc_to_user_timezone(auth()->user()->id,$information->date);
        $information->edate = date('Y-m-d',strtotime($information->date['datetime']));
        $information->date = $information->date['date'];
        $states = DB::table('doctor_licenses')
        ->join('states', 'states.id', 'doctor_licenses.state_id')
        ->where('doctor_id',auth()->user()->id)
        ->get();

        return view('dashboard_doctor.psychiatrist_profile.edit_therapy_schedule',compact('information','states'));
    }


    public function update_therapy_event(Request $request){
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
        DB::table('psychiatrist_info')->where('event_id',$request->event_id)->update([
            'concerns' => $cncrn,
            'services' => $serv,
            'help' => $request->helping?$request->helping:'',
            'skills' => $request->skilled?$request->skilled:'',
        ]);

        $doctorID=Auth::user()->id;
        $request->startTimePicker = date('H:i:s',strtotime($request->startTimePicker));
        $AvailabilityStart = $request->date.' '.$request->startTimePicker;
        $convert = date('Y-m-d H:i:s', strtotime($AvailabilityStart));
        $AvailabilityStart = User::convert_user_timezone_to_utc($doctorID,$AvailabilityStart)['datetime'];
        if($request->event_id != null && $request->date != null)
        {
            $start =  date('H:i:s',strtotime($AvailabilityStart));
            $date = date('Y-m-d',strtotime($AvailabilityStart));
            $startdate = date('Y-m-d H:i:s',strtotime($AvailabilityStart));
            DB::table('doctor_schedules')->where('id',$request->event_id)->update(['start'=>$startdate,'slotStartTime'=>$start,'date'=>$date]);
            DB::table('therapy_session')->where('event_id',$request->event_id)->update(['start_time'=>$startdate,'date'=>$date,'time_zone'=>$request->time_zone,'states'=>$request->state]);
        }
        return redirect()->route('add_therapy_schedule')->with('msg','updated successfully');
    }

    public function upload_license(Request $request){
        if(request()->hasFile('front_license')){
            $file = request()->file('front_license');
            $frontimageName = "doctors/" .date('YmdHis').''.$file->getClientOriginalName();
            $img = Image::make($file);
            $img->resize(1000, 1000, function ($constraint) {
                $constraint->aspectRatio();
            });
            //detach method is the key! Hours to find it... :/
            $resource = $img->stream()->detach();
            $filename = \Storage::disk('s3')->put(
                $frontimageName,
                $resource
            );
        }else{
            $frontimageName = '';
        }
        if(request()->hasFile('back_license')){
            $file = request()->file('back_license');
            $backimageName = "doctors/" .date('YmdHis').''.$file->getClientOriginalName();
            $img = Image::make($file);
            $img->resize(1000, 1000, function ($constraint) {
                $constraint->aspectRatio();
            });
            //detach method is the key! Hours to find it... :/
            $resource = $img->stream()->detach();
            $backfilename = \Storage::disk('s3')->put(
                $backimageName,
                $resource
            );
        }else{
            $backimageName = '';
        }
        DB::table('users')->where('id',auth()->user()->id)->update([
            'id_card_front' => $frontimageName,
            'id_card_back' => $backimageName,
        ]);
        return redirect()->back();
    }
    public function get_symptom_data(Request $request){
        $symptomsChecker = Symptoms_Checker::where('user_id',$request->patient_id)->latest()->first();
        return Response::json($symptomsChecker);
    }
}
