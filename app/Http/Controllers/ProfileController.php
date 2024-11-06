<?php

namespace App\Http\Controllers;

use App\ActivityLog;
use App\Notification;
use App\City;
use App\Country;
use App\Events\RealTimeMessage;
use App\Mail\SendEmail;
use Illuminate\Support\Facades\Mail;
use App\Mail\UpdateProfileAdminMail;
use App\DoctorCertificate;
use App\Http\Controllers\Controller;
use App\Profile;
use App\Specialization;
use App\State;
use App\User;
use DateTime;
use DateTimeZone;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Rules\Phone;
use Validator;
use Hash;


class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function view_profile($username=null)
    {
        if($username!=null){
            $id = auth()->user()->id;
            $user = DB::table('users')->where('username', $username)->first();
            if(isset($user->id)){
                // dd($user);
                if($id==$user->id){
                    if($user->user_type=='doctor'){
                        $doctor=$user;
                        // doctor profile picture from S3

                        $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);
                        $doctor->city = City::find($doctor->city_id);
                        $doctor->state = State::find($doctor->state_id);
                        $doctor->country = Country::find($doctor->country_id);
                        $doctor->spec = Specialization::find($doctor->specialization);

                        // doctor certificates
                        $doctor->certificates = DB::table('doctor_certificates')->where('doc_id', $id)->get();
                        foreach ($doctor->certificates as $cert) {
                            if ($cert->certificate_file != "")
                            {
                                $cert->certificate_file = \App\Helper::get_files_url($cert->certificate_file);
                            }
                        }

                        // Query will modify later
                        $doctor->activities = DB::select("SELECT *, CASE `type` WHEN 'login' THEN 'Last Logged In' WHEN 'logout' THEN 'Last Logged Out' WHEN 'prescription added' THEN 'Prescription Status' WHEN 'session recommendations' THEN 'Session Recommendation Status' WHEN 'session start' THEN 'Session Status' WHEN 'session end' THEN 'Session Ended Status' WHEN 'order' THEN 'Order Status' WHEN 'record' THEN 'Report View Status' END AS `heading`, CASE `type` WHEN 'login' THEN 'badge-success' WHEN 'logout' THEN 'badge-warning' WHEN 'prescription added' THEN 'badge-info' WHEN 'session recommendations' THEN 'badge-info' WHEN 'session start' THEN 'badge-success' WHEN 'session end' THEN 'badge-warning' WHEN 'order' THEN 'badge-info' WHEN 'record' THEN 'badge-success' END AS `color` FROM `activity_log` WHERE user_id = '$id' AND `type` NOT IN ('record', 'product_del_request', 'product_created', 'product_category_created', 'product_sub_category_created') order by created_at desc");
                        // dd($activities);
                        foreach ($doctor->activities as $activity) {
                        $user_time_zone = Auth::user()->timeZone;
                        $date = new DateTime($activity->created_at);
                        $date->setTimezone(new DateTimeZone($user_time_zone));
                        $activity->created_at = $date->format('D, M/d/Y, g:i a');
                        }

                        if ($doctor->specialization != '1') {
                        $doctor->all_patients = DB::table('referals')
                            ->where('sp_doctor_id', $doctor->id)
                            ->where('referals.status', 'accepted')
                            ->join('users', 'referals.patient_id', '=', 'users.id')
                            ->join('sessions', 'sessions.patient_id', '=', 'users.id')
                            ->select('*', 'referals.doctor_id as doc_id', DB::raw('MAX(sessions.id) as last_id'))
                            ->orderBy('sessions.date', 'DESC')
                            ->groupBy('sessions.patient_id')
                            ->get();
                        // dd($patients);
                        } else {
                        $doctor->all_patients = DB::table('sessions')->where('sessions.doctor_id', auth()->user()->id)
                            ->groupBy('sessions.patient_id')
                            ->join('users', 'sessions.patient_id', '=', 'users.id')
                            ->select('users.*')
                            ->get();
                        // dd($patients);
                        }
                        foreach ($doctor->all_patients as $patient) {

                            $patient->user_image = \App\Helper::check_bucket_files_url($patient->user_image);

                        }
                        // dd($doctor);
                        return view('doctor.profile.view_profile', compact('doctor'));
                    }else if($user->user_type=='patient'){
                        $patient = $user;
                        if ($patient->city_id == null) {
                            $patient->city = 'None';
                        } else {
                            $city = City::where('id', $patient->city_id)->first();
                            $patient->city = $city['name'];
                        }
                        if ($patient->state_id == null) {
                            $patient->state = 'None';
                        } else {
                            $state = State::where('id', $patient->state_id)->first();
                            $patient->state = $state['name'];
                        }
                        if ($patient->country_id == null) {
                            $patient->country = 'None';
                        } else {
                            $country = Country::where('id', $patient->country_id)->first();
                            $patient->country = $country['name'];
                        }

                        $patient->user_image = \App\Helper::check_bucket_files_url($patient->user_image);
                        $userobj = new User();
                        $patient->sessions = $userobj->get_recent_sessions($id);

                        // Activity Query will modify later...!!
                        // $activities = ActivityLog::where('user_id', $id)->orderByDesc('id')->paginate(9);
                        $patient->activities = DB::select("SELECT *, CASE `type` WHEN 'login' THEN 'Last Logged In' WHEN 'logout' THEN 'Last Logged Out' WHEN 'prescription added' THEN 'Prescription Status' WHEN 'session recommendations' THEN 'Session Recommendation Status' WHEN 'session start' THEN 'Session Status' WHEN 'session end' THEN 'Session Ended Status' WHEN 'order' THEN 'Order Status' END AS `heading`, CASE `type` WHEN 'login' THEN 'badge-success' WHEN 'logout' THEN 'badge-warning' WHEN 'prescription added' THEN 'badge-info' WHEN 'session recommendations' THEN 'badge-info' WHEN 'session start' THEN 'badge-success' WHEN 'session end' THEN 'badge-warning' WHEN 'order' THEN 'badge-info' END AS `color` FROM `activity_log` WHERE user_id = '$id' AND `type` NOT IN ('record', 'product_del_request', 'product_created', 'product_category_created', 'product_sub_category_created') order by created_at desc");

                        foreach ($patient->activities as $activity) {
                            $user_time_zone = Auth::user()->timeZone;
                            $date = new DateTime($activity->created_at);
                            $date->setTimezone(new DateTimeZone($user_time_zone));
                            $activity->created_at = $date->format('D, M/d/Y, g:i a');
                        }

                        return view('patient.profile.view_profile', compact('patient'));

                    }else{
                        return redirect()->route('welcome_page');
                    }
                }else{
                    return redirect()->route('welcome_page');
                }
            }else{
                return view('errors.404');
            }

        }else{
            return redirect()->route('/contact_us');
        }

    }


//dashboard_doctor
public function view_DocProfile($username)
    {
        if($username!=null){
            $id = auth()->user()->id;
            $user = DB::table('users')->where('username', $username)->first();
            if(isset($user->id)){
                // dd($user);
                if($id==$user->id){
                    if($user->user_type=='doctor'){
                        $doctor=$user;
                        // doctor profile picture from S3

                        $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);
                        $doctor->city = City::find($doctor->city_id);
                        $doctor->state = State::find($doctor->state_id);
                        $doctor->country = Country::find($doctor->country_id);
                        $doctor->spec = Specialization::find($doctor->specialization);
                        $doctor->license = DB::table('doctor_licenses')
                        ->join('states','states.id','doctor_licenses.state_id')
                        ->where('doctor_id',$id)
                        ->where('is_verified',1)
                        ->select('states.name')->get();
                        // doctor certificates
                        $doctor->certificates = DB::table('doctor_certificates')->where('doc_id', $id)->get();
                        foreach ($doctor->certificates as $cert) {
                        if ($cert->certificate_file != "") {
                            $cert->certificate_file = \App\Helper::get_files_url($cert->certificate_file);
                        }
                        }

                        // Query will modify later
                        // $doctor->activities = DB::select("SELECT *, CASE `type` WHEN 'login' THEN 'Last Logged In'
                        // WHEN 'logout' THEN 'Last Logged Out' WHEN 'prescription added' THEN 'Prescription Status'
                        // WHEN 'session recommendations' THEN 'Session Recommendation Status' WHEN 'session start'
                        // THEN 'Session Status' WHEN 'session end' THEN 'Session Ended Status' WHEN 'order' THEN 'Order Status'
                        // WHEN 'record' THEN 'Report View Status' END AS `heading`, CASE `type` WHEN 'login' THEN 'badge-success'
                        // WHEN 'logout' THEN 'badge-warning' WHEN 'prescription added' THEN 'badge-info' WHEN
                        // 'session recommendations' THEN 'badge-info' WHEN 'session start' THEN 'badge-success' WHEN
                        // 'session end' THEN 'badge-warning' WHEN 'order' THEN 'badge-info' WHEN 'record' THEN 'badge-success'
                        // END AS `color` FROM `activity_log` WHERE user_id = '$id' AND `type`
                        // NOT IN ('record', 'product_del_request', 'product_created', 'product_category_created',
                        // 'product_sub_category_created') order by created_at desc LIMIT 3");
                        $data = DB::table('activity_log')->where('user_id',$id)->select('*')->orderBy('created_at','desc')->paginate(10);
                        // dd($activities);
                        foreach ($data as $dt) {
                            $user_time_zone = Auth::user()->timeZone;
                            $date = new DateTime($dt->created_at);
                            $date->setTimezone(new DateTimeZone($user_time_zone));
                            $dt->created_at = $date->format('D, M/d/Y, g:i a');
                        }

                        $doctor->all_patients = DB::table('sessions')->where('sessions.doctor_id', auth()->user()->id)
                            ->groupBy('sessions.patient_id')
                            ->where('sessions.status', '!=', 'pending')
                            ->join('users', 'sessions.patient_id', '=', 'users.id')
                            ->select('users.*')
                            ->get();

                        // if ($doctor->specialization != '1') {
                        // $doctor->all_patients = DB::table('referals')
                        //     ->where('sp_doctor_id', $doctor->id)
                        //     ->where('referals.status', 'accepted')
                        //     ->join('users', 'referals.patient_id', '=', 'users.id')
                        //     ->join('sessions', 'sessions.patient_id', '=', 'users.id')
                        //     ->select('*', 'referals.doctor_id as doc_id', DB::raw('MAX(sessions.id) as last_id'))
                        //     ->orderBy('sessions.date', 'DESC')
                        //     ->groupBy('sessions.patient_id')
                        //     ->get();
                        // // dd($patients);
                        // } else {
                        // $doctor->all_patients = DB::table('sessions')->where('sessions.doctor_id', auth()->user()->id)
                        //     ->groupBy('sessions.patient_id')
                        //     ->join('users', 'sessions.patient_id', '=', 'users.id')
                        //     ->select('users.*')
                        //     ->get();

                        // }
                        foreach ($doctor->all_patients as $patient)
                        {
                            $patient->user_image = \App\Helper::check_bucket_files_url($patient->user_image);
                        }
                        return view('dashboard_doctor.Profile.view_profile', compact('doctor','data'));
                    }

                    else if($user->user_type=='patient'){
                        $patient = $user;
                        if ($patient->city_id == null) {
                            $patient->city = 'None';
                        } else {
                            $city = City::where('id', $patient->city_id)->first();
                            $patient->city = $city['name'];
                        }
                        if ($patient->state_id == null) {
                            $patient->state = 'None';
                        } else {
                            $state = State::where('id', $patient->state_id)->first();
                            $patient->state = $state['name'];
                        }
                        if ($patient->country_id == null) {
                            $patient->country = 'None';
                        } else {
                            $country = Country::where('id', $patient->country_id)->first();
                            $patient->country = $country['name'];
                        }

                        $patient->user_image = \App\Helper::check_bucket_files_url($patient->user_image);
                        $userobj = new User();
                        $patient->sessions = $userobj->get_recent_sessions($id);

                        // Activity Query will modify later...!!
                        // $activities = ActivityLog::where('user_id', $id)->orderByDesc('id')->paginate(9);
                        // $patient->activities = DB::select("SELECT *, CASE `type` WHEN 'login' THEN 'Last Logged In'
                        // WHEN 'logout' THEN 'Last Logged Out' WHEN 'prescription added' THEN 'Prescription Status' WHEN
                        // 'session recommendations' THEN 'Session Recommendation Status' WHEN 'session start' THEN 'Session Status'
                        // WHEN 'session end' THEN 'Session Ended Status' WHEN 'order' THEN 'Order Status' END AS
                        // `heading`, CASE `type` WHEN 'login' THEN 'badge-success' WHEN 'logout' THEN 'badge-warning'
                        // WHEN 'prescription added' THEN 'badge-info' WHEN 'session recommendations' THEN 'badge-info'
                        // WHEN 'session start' THEN 'badge-success' WHEN 'session end' THEN 'badge-warning' WHEN 'order'
                        // THEN 'badge-info' END AS `color` FROM `activity_log` WHERE user_id = '$id' AND `type` NOT IN
                        // ('record', 'product_del_request', 'product_created', 'product_category_created', 'product_sub_category_created')
                        // order by created_at desc")->paginate(10);
                        $data = DB::table('activity_log')->where('user_id',$id)->select('*')->orderBy('created_at','desc')->paginate(10);
                        // $activities_arr = array();
                        // foreach($patient->activities as $act){
                        //     array_push($activities_arr,$act);
                        // }
                        // dd($patient->activities,$activities_arr);
                        foreach ($data as $dt) {
                            $user_time_zone = Auth::user()->timeZone;
                            $date = new DateTime($dt->created_at);
                            $date->setTimezone(new DateTimeZone($user_time_zone));
                            $dt->created_at = $date->format('D, M/d/Y, g:i a');
                        }

                        return view('dashboard_patient.Profile.view_profile', compact('patient','data'));

                    }else{
                        return redirect()->route('welcome_page');
                    }
                }elseif(auth()->user()->user_type == 'editor_imaging'){
                    return redirect()->back();
                }else{
                    return redirect()->route('welcome_page');
                }
            }else{
                return view('errors.404');
            }

        }else{
            return redirect()->route('/contact_us');
        }

    }
    function admin_fetch_data(Request $request)
    {
        $id = $request->user_id;
     if($request->ajax())
     {
      $activities = DB::table('activity_log')->select('*')->where('user_id', $id)->orderBy('created_at','desc')->paginate(10);
      foreach ($activities as $dt) {
            $user_time_zone = Auth::user()->timeZone;
            $date = new DateTime($dt->created_at);
            $date->setTimezone(new DateTimeZone($user_time_zone));
            $dt->created_at = $date->format('D, M/d/Y, g:i a');
        }
      return view('dashboard_admin.doctors.all_doctors.pagination_data', compact('activities'))->render();
     }
    }

    function fetch_data(Request $request)
    {
        $id = auth()->user()->id;
     if($request->ajax())
     {
      $data = DB::table('activity_log')->select('*')->where('user_id', $id)->orderBy('created_at','desc')->paginate(10);
      foreach ($data as $dt) {
            $user_time_zone = Auth::user()->timeZone;
            $date = new DateTime($dt->created_at);
            $date->setTimezone(new DateTimeZone($user_time_zone));
            $dt->created_at = $date->format('D, M/d/Y, g:i a');
        }
      return view('dashboard_patient.Profile.pagination_data', compact('data'))->render();
     }
    }


    function editprofilepicture(Request $request){
        $user = auth()->user() ;
        // dd($user);
        $image = $request->file('filename');
        $filename = \Storage::disk('s3')->put('user_profile_images', $image);
        DB::table('users')->where('id',$user->id)->update([
            'user_image' => $filename,
        ]);
        if($user->user_type == 'patient'){
            return redirect('/editPatientDetail');
        }else if ($user->user_type == 'doctor'){
            return redirect('/doctor/edit/profile');
        }
    }

    function editprofilenumber(Request $request){
        $id = auth()->user()->id;
        $validateData = $request->validate([
            'phoneNumber' => ['required','min:10','max:11', 'gt:0', new Phone],
        ]);
        $user = DB::table('users')->where('id',$id)->update([
            'phone_number' => $validateData['phoneNumber'],
        ]);

        return redirect('/editPatientDetail');
    }



    public function getPatientProfileDetail()
    {
        $id = auth()->user()->id;
        $patient_data = DB::table('users')->where('id', $id)->first();
        $patient_data->user_image=\App\Helper::check_bucket_files_url($patient_data->user_image);
        $patient_data->city = City::where('id', $patient_data->city_id)->first();
        $patient_data->state = State::where('id', $patient_data->state_id)->first();
        $patient_data->country = Country::where('id', $patient_data->country_id)->first();
        $patient_data->states = State::where('country_id', $patient_data->country_id)->get();
        $patient_data->cities = City::where('state_id', $patient_data->state_id)->get();
        $time=time();
        $current_date=date('Y-m-d',$time);
        $min_date=$date = strtotime($current_date.' -1 day');
        $min_age_date=date('Y-m-d', $min_date);
        // dd($min_date);
        return view('patient.profile.edit_profile', compact(
            'patient_data','min_age_date'
        ));
    }




    //edit->patient:
    public function editPatientDetail()
    {
        $id = auth()->user()->id;
        $patient_data = DB::table('users')->where('id', $id)->first();
        $patient_data->user_image=\App\Helper::check_bucket_files_url($patient_data->user_image);
        $patient_data->city = City::where('id', $patient_data->city_id)->first();
        $patient_data->state = State::where('id', $patient_data->state_id)->first();
        $patient_data->country = Country::where('id', $patient_data->country_id)->first();
        $patient_data->states = State::where('country_id', $patient_data->country_id)->get();
        $patient_data->cities = City::where('state_id', $patient_data->state_id)->get();
        $time=time();
        $current_date=date('Y-m-d',$time);
        $min_date=$date = strtotime($current_date.' -1 day');
        $min_age_date=date('Y-m-d', $min_date);
        // dd($min_date);

        $update = DB::table('patients_redord')->where('user_id',$id)->where('edited','0')->count();

        return view('dashboard_patient.Profile.edit_profile', compact(
            'patient_data','min_age_date','update'
        ));
    }





    public function getDoctorDetail(Request $request)
    {
        $id = auth()->user()->id;
        $doctor_data = DB::table('users')->where('id', $id)->first();
        $doctor_data->user_image=\App\Helper::check_bucket_files_url($doctor_data->user_image);
        $doctor_data->city = City::where('id', $doctor_data->city_id)->first();
        $doctor_data->state = State::where('id', $doctor_data->state_id)->first();
        $doctor_data->country = Country::where('id', $doctor_data->country_id)->first();
        $doctor_data->spec = Specialization::where('id', $doctor_data->specialization)->first();
        $specs = Specialization::all();
        // $countries = Country::all();
        $doctor_data->cities = City::where('state_id', $doctor_data->state_id)->get();
        $doctor_data->states = State::where('country_id', $doctor_data->country_id)->get();
        $time=time();
        $current_date=date('Y-m-d',$time);
        $min_date=$date = strtotime($current_date.' -18 year');
        $min_age_date=date('Y-m-d', $min_date);

        // dd($min_date);
        return view('doctor.profile.edit_profile', compact(
            'doctor_data',
            'specs','min_age_date'
        ));
    }








    public function editDoctorDetail(Request $request)
    {
        $id = auth()->user()->id;
        $doctor_data = DB::table('users')->where('id', $id)->first();
        $doctor_data->user_image = \App\Helper::check_bucket_files_url($doctor_data->user_image);
        $doctor_data->city = City::where('id', $doctor_data->city_id)->first();
        $doctor_data->state = State::where('id', $doctor_data->state_id)->first();
        $doctor_data->country = Country::where('id', $doctor_data->country_id)->first();
        $doctor_data->spec = Specialization::where('id', $doctor_data->specialization)->first();
        $specs = Specialization::all();
        // $countries = Country::all();
        $doctor_data->cities = City::where('state_id', $doctor_data->state_id)->get();
        $doctor_data->states = State::where('country_id', $doctor_data->country_id)->get();
        $time=time();
        $current_date=date('Y-m-d',$time);
        $min_date=$date = strtotime($current_date.' -18 year');
        $min_age_date=date('Y-m-d', $min_date);

        $update = DB::table('doctor_profile_update')->where('user_id',$id)->where('edited','0')->count();
        // dd($up);
        return view('dashboard_doctor.Profile.edit_profile', compact(
            'doctor_data',
            'specs','min_age_date',
            'update'
        ));
    }







    public function updatePatientProfile(Request $request)
    {
        $input=$request->all();
        $datecheck =$input['dob'];
        $date = str_replace('-', '/', $datecheck);
        $newd_o_b = date("Y-m-d", strtotime($date));
        if (str_contains($datecheck, "/")) {
            $newd_o_b;
        } else {
            $datecheck;
        }
        $time=time();
        $current_date=date('Y-m-d',$time);
        $min_date=$date = strtotime($current_date.' -1 day');
        $min_age_date=date('Y-m-d', $min_date);
        if($datecheck>$min_age_date){
            Flash::error('Date of birth cannot be future date.');
            return back();
        }

        $validateData = $request->validate([
            'fname' => ['required', 'string'],
            'dob' => ['required'],
            'phoneNumber' => ['required','min:10','max:11', 'gt:0', new Phone],
            'address' => 'required',
            // 'state' => ['required'],
            // 'country' => ['required'],
            // 'city' => ['required'],
            // 'bio' => ['string'],
            // 'zip_code' => ['required'],
            // 'image' =>  ['required'],
        ]);
        $id=auth()->user()->id;
        $patient=User::find($id);



        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = \Storage::disk('s3')->put('user_profile_images', $image);
            $res = DB::table('users')
                ->where('id', $id)
                ->update([
                    'name' => $validateData['fname'],
                    'date_of_birth' => $newd_o_b,
                    'phone_number' => $validateData['phoneNumber'],
                    'office_address' => $validateData['address'],
                    // 'country_id' => $validateData['country'],
                    // 'state_id' => $validateData['state'],
                    // 'city_id' => $validateData['city'],
                    'bio' => $request['bio'],
                    // 'zip_code' => $validateData['zip_code'],
                    'user_image' => $filename,
                ]);
        } else {
            DB::table('users')
                ->where('id', $id)
                ->update([
                    'name' => $validateData['fname'],
                    'date_of_birth' => $newd_o_b,
                    'phone_number' => $validateData['phoneNumber'],
                    'office_address' => $validateData['address'],
                    // 'country_id' => $validateData['country'],
                    // 'state_id' => $validateData['state'],
                    // 'city_id' => $validateData['city'],
                    'bio' => $request['bio'],
                    // 'zip_code' => $validateData['zip_code'],
                ]);
        }
        if (isset($input['lname'])) {
            $patient->last_name = $input['lname'];
            $patient->save();
        }
        if ($patient->hasRole('temp_patient')) {
            // dd($validateData);
            $patient->removeRole('temp_patient');
            $patient->assignRole('patient');
            return redirect()->route('medical_profile');
        }
        // dd($request);
        Flash::success('Profile Updated successfully.');
        return redirect()->route('profile',$patient->username);
    }



    public function updatePatient(Request $request)
    {
        $input=$request->all();
        $datecheck =$input['dob'];
        $date = str_replace('-', '/', $datecheck);
        $newd_o_b = date("Y-m-d", strtotime($date));
        $admin = DB::table('users')->where('user_type','=','admin')->first();
        if (str_contains($datecheck, "/")) {
            $newd_o_b;
        } else {
            $datecheck;
        }
        $time=time();
        $current_date=date('Y-m-d',$time);
        $min_date=$date = strtotime($current_date.' -1 day');
        $min_age_date=date('Y-m-d', $min_date);
        if($datecheck>$min_age_date){
            Flash::error('Date of birth cannot be future date.');
            return back();
        }

        $validateData = $request->validate([
            'fname' => ['required', 'string'],
            'lname' => ['required', 'string'],
            'dob' => ['required'],
            // 'phoneNumber' => ['required','min:10','max:11', 'gt:0', new Phone],
            'address' => 'required',
            'state' => ['required'],
            'email' => ['required'],
          //  'country' => ['required'],
            'city' => ['required'],
            // 'bio' => ['string'],
            'zip_code' => ['required'],
            'reason' =>  ['required'],
        ]);
        // dd($validateData);
        $id=auth()->user()->id;
        $patient=User::find($id);

        $query = DB::table('users')->where('id',$input['user_id'])->first();

        // dd($query);

        $old = array(
            'name' => $query->name,
            'last_name' => $query->last_name,
            'email'=> $query->email,
            'date_of_birth' => $query->date_of_birth,
            // 'phone_number' => $query->phone_number,
            'office_address' => $query->office_address,
            'state_id' => $query->state_id,
            'city_id' => $query->city_id,
            // 'bio' => $query->bio,
            'zip_code' => $query->zip_code,
        );

        $new = array(
            'name' => $validateData['fname'],
            'last_name' => $validateData['lname'],
            'email'=> $validateData['email'],
            'date_of_birth' => $newd_o_b,
            // 'phone_number' => $validateData['phoneNumber'],
            'office_address' => $validateData['address'],
            'state_id' => $validateData['state'],
            'city_id' => $validateData['city'],
            // 'bio' => $request['bio'],
            'zip_code' => $validateData['zip_code'],
        );

        $differenceArray1 = array_diff($old, $new);

        // dd($differenceArray1);
        if ($differenceArray1) {
            // $image = $request->file('image');
            // $filename = \Storage::disk('s3')->put('user_profile_images', $image);
            DB::table('patients_redord')->insert([
                'user_id' => $input['user_id'],
                'name' => $validateData['fname'],
                'last_name' => $validateData['lname'],
                'date_of_birth' => $newd_o_b,
                'office_address' => $validateData['address'],
                'state_id' => $validateData['state'],
                'city_id' => $validateData['city'],
                'zip_code' => $validateData['zip_code'],
                'email'=> $validateData['email'],
                'reason'=> $validateData['reason'],

            ]);
        $notification_id = Notification::create([
            'text' => 'Profile updation By ' .$validateData['fname'],
            'user_id' => $admin->id,
            'user_type' => 'admin',
            'type' => '/admin/patient_records'
        ]);
        $data = [
            'text' => 'Profile updation By ' .$validateData['fname'],
            'user_id' => $admin->id,
            'type' => '/admin/patient_records',
            'session_id' => "null",
            'received' => 'false',
            'appoint_id' => 'null',
            'refill_id' => 'null',
        ];
        try {
            // \App\Helper::firebase($admin->id,'notification',$notification_id->id,$data);
        } catch (\Throwable $th) {
            //throw $th;
        }
        event(new RealTimeMessage($admin->id));
        try {
            Mail::to($admin->email)->send(new UpdateProfileAdminMail());

            return redirect()->back()->with('success', 'Profile Update Request Sent to Admin Successfully');

        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('success', 'Profile Update Request Sent to Admin Successfully');

        }
    }else{
        return redirect()->back()->with('error', 'you have not made any changes');
    }

        // dd($request);
        // return redirect()->back()->with('success', 'data updated successfully!');
    }




    public function updatePatientRecords(Request $request)
    {
        $input=$request->all();
        $datecheck =$input['dob'];
        $date = str_replace('-', '/', $datecheck);
        $newd_o_b = date("Y-m-d", strtotime($date));
        if (str_contains($datecheck, "/")) {
            $newd_o_b;
        } else {
            $datecheck;
        }
        $time=time();
        $current_date=date('Y-m-d',$time);
        $min_date=$date = strtotime($current_date.' -1 day');
        $min_age_date=date('Y-m-d', $min_date);
        if($datecheck>$min_age_date){
            Flash::error('Date of birth cannot be future date.');
            return back();
        }

        $id=auth()->user()->id;
        $patient=User::find($id);
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = \Storage::disk('s3')->put('user_profile_images', $image);
            $res = DB::table('users')
                ->where('id', $id)
                ->update([
                    'name' => 'fname',
                    'last_name' => $validateData['lname'],
                    'date_of_birth' => $newd_o_b,
                    'phone_number' => $validateData['phoneNumber'],
                    'office_address' => $validateData['address'],
                    // 'country_id' => $validateData['country'],
                    // 'state_id' => $validateData['state'],
                    // 'city_id' => $validateData['city'],
                    'bio' => $request['bio'],
                    // 'zip_code' => $validateData['zip_code'],
                   // 'user_image' => $filename,
                ]);
        } else {
            DB::table('users')
                ->where('id', $id)
                ->update([
                    'name' => 'fname',
                    'last_name' => 'lname',
                    'date_of_birth' => $newd_o_b,
                    'phone_number' => 'phoneNumber',
                    'office_address' => 'address',
                    // 'country_id' => $validateData['country'],
                    // 'state_id' => $validateData['state'],
                    // 'city_id' => $validateData['city'],
                    'bio' => $request['bio'],
                    // 'zip_code' => $validateData['zip_code'],
                ]);
        }
        if (isset($input['lname'])) {
            $patient->last_name = $input['lname'];
            $patient->save();
        }
        if ($patient->hasRole('temp_patient')) {
            // dd($validateData);
            $patient->removeRole('temp_patient');
            $patient->assignRole('patient');
            // return redirect()->route('medical_profile');
            return redirect()->back()->with('success', 'data updated successfully!');
        }
        // DB::table('patients_redord')->insert([
        //             'name' => $validateData['fname'],
        //             'last_name' => $validateData['lname'],
        //             'date_of_birth' => $newd_o_b,
        //             'phone_number' => $validateData['phoneNumber'],
        //             'office_address' => $validateData['address'],
        //             // 'country_id' => $validateData['country'],
        //              'state_id' => $validateData['state'],
        //              'city_id' => $validateData['city'],
        //             'bio' => $request['bio'],
        //              'zip_code' => $validateData['zip_code'],
        //         ]);
        // dd($request);
        return redirect()->back()->with('success', 'data updated successfully!');
    }



    public function adminApprovalPatUpdateProfile($id){
        $res = DB::table('patients_redord')
        ->select('patients_redord.*')
        ->where('id', $id)
        ->where('edited', '0')
        ->get();
        // dd($res);
        $res = ($res[0]);
        $user = DB::table('users')
        ->where('id', $res->user_id)
        ->first();
        // dd($user->email);

        $update = DB::table('users')
            ->where('id', $res->user_id)
            ->update([
                'name' => $res->name,
                'last_name' => $res->last_name,
                'date_of_birth' => $res->date_of_birth,
                'email' => $res->email,
                'office_address' => $res->office_address,
                'state_id' => $res->state_id,
                'city_id' => $res->city_id,
                'zip_code' => $res->zip_code,
            ]);
            // dd($update);
            DB::table('patients_redord')
            ->select('patients_redord.*')
            ->where('id', $id)
            ->update(['edited' => '1']);
        $notification_id = Notification::create([
            'text' => 'Profile Updated',
            'user_id' => $res->user_id,
            'type' => '/editPatientDetail',
        ]);
        $data = [
            'text' => 'Profile Updated',
            'user_id' => $res->user_id,
            'type' => '/editPatientDetail',
            'session_id' => "null",
            'received' => 'false',
            'appoint_id' => 'null',
            'refill_id' => 'null',
        ];
        try {

            // \App\Helper::firebase($res->user_id,'notification',$notification_id->id,$data);
        } catch (\Throwable $th) {
            //throw $th;
        }
        event(new RealTimeMessage($res->user_id));
        try {
            Mail::to($user->email)->send(new UpdateProfileAdminMail());

            return redirect()->back()->with('success', 'data updated successfully!');

        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('success', 'data updated successfully!');

        }

    }
    public function adminCancelPatUpdateProfile($id){

        $res = DB::table('patients_redord')
        ->select('patients_redord.*')
        ->where('id', $id)
        ->where('edited', '0')
        ->first();
        DB::table('patients_redord')->delete($id);
        // $res = ($res[0]);
        // dd($res);

        $notification_id = Notification::create([
            'text' => 'Profile Updated Cancelled By Admin',
            'user_id' => $res->user_id,
            'type' => '/editPatientDetail',
        ]);
        $data = [
            'text' => 'Profile Updated Cancelled By Admin',
            'user_id' => $res->user_id,
            'type' => '/editPatientDetail',
            'session_id' => "null",
            'received' => 'false',
            'appoint_id' => 'null',
            'refill_id' => 'null',
        ];
        try {
            // \App\Helper::firebase($res->user_id,'notification',$notification_id->id,$data);

        } catch (\Throwable $th) {
            //throw $th;
        }
        event(new RealTimeMessage($res->user_id));
        return redirect()->back()->with('success', 'Updation Cancelled successfully!');

    }

    public function adminCancelDocUpdateProfile($id){
        $res = DB::table('doctor_profile_update')
        ->select('doctor_profile_update.*')
        ->where('id', $id)
        ->where('edited', '0')
        ->first();
        DB::table('doctor_profile_update')->delete($id);
        // $res = ($res[0]);
        // dd($res);
        $notification_id = Notification::create([
            'text' => 'Profile Updated Cancelled By Admin',
            'user_id' => $res->user_id,
            'type' => '/doctor/edit/profile',
        ]);
        $data = [
            'text' => 'Profile Updated Cancelled By Admin',
            'user_id' => $res->user_id,
            'type' => '/doctor/edit/profile',
            'session_id' => "null",
            'received' => 'false',
            'appoint_id' => 'null',
            'refill_id' => 'null',
        ];
        try {

            // \App\Helper::firebase($res->user_id,'notification',$notification_id->id,$data);
        } catch (\Throwable $th) {
            //throw $th;
        }
        event(new RealTimeMessage($res->user_id));
        return redirect()->back()->with('success', 'Updation Cancelled successfully!');
    }

    public function updateProfile($id){
        $res = DB::table('doctor_profile_update')
        ->select('doctor_profile_update.*')
        ->where('id', $id)
        ->where('edited', '0')
        ->first();
        $user = DB::table('users')->where('id','=',$res->user_id)->first();
        // dd($res,$user);

        $tt = DB::table('users')
            ->where('id', $res->user_id)
            ->update([
                'name' => $res->name,
                'last_name' => $res->last_name,
                'date_of_birth' => $res->date_of_birth,
                'phone_number' => $res->phone_number,
                'email' => $res->email,
                'office_address' => $res->office_address,
                'state_id' => $res->state_id,
                'city_id' => $res->city_id,
                'bio' => $res->bio,
                'zip_code' => $res->zip_code,
            ]);
        DoctorCertificate::create([
            'certificate_file' => $res->certificate,
            'doc_id' => $res->user_id,
        ]);
        DB::table('doctor_profile_update')
        ->select('doctor_profile_update.*')
        ->where('id', $id)
        ->update(['edited' => '1']);

        $notification_id = Notification::create([
            'text' => 'Profile Updated',
            'user_id' => $res->user_id,
            'type' => '/doctor/edit/profile',
        ]);
        $data = [
            'text' => 'Profile Updated',
            'user_id' => $res->user_id,
            'type' => '/doctor/edit/profile',
            'session_id' => "null",
            'received' => 'false',
            'appoint_id' => 'null',
            'refill_id' => 'null',
        ];
        try {

            // \App\Helper::firebase($res->user_id,'notification',$notification_id->id,$data);
        } catch (\Throwable $th) {
            //throw $th;
        }
        event(new RealTimeMessage($res->user_id));
        try {
            Mail::to($user->email)->send(new UpdateProfileAdminMail());

            return redirect()->back()->with('success', 'data updated successfully!');

        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('success', 'data updated successfully!');

        }

    }




    public function updateDoctorProfile(Request $request)
    {
        // dd($request);
        $input=$request->all();
        $datecheck =$input['dob'];
        $date = str_replace('-', '/', $datecheck);
        $newd_o_b = date("Y-m-d", strtotime($date));
        if (str_contains($datecheck, "/")) {
            $newd_o_b;
        } else {
            $datecheck;
        }

        $validateData = $request->validate([
            'fname' => ['required', 'string'],
            'lname' => ['required', 'string'],
            // 'email' =>  ['required', 'email'],
            'phoneNumber' => ['required','max:15','gt:0', new Phone],
            'address' => ['required'],
            // 'country' => ['required'],
            // 'state' => ['required'],
            // 'city' => ['required'],
            // 'bio' => ['required'],
            'specialization' => ['required'],
            // 'zip_code' => ['required'],
        ]);
        $time=time();
        $current_date=date('Y-m-d',$time);
        $min_date=$date = strtotime($current_date.' -18 year');
        $min_age_date=date('Y-m-d', $min_date);
        if($datecheck>$min_age_date){
            Flash::error('Age must be greater than 18.');
            return back();
        }
        $id = auth()->user()->id;
        $doctor=User::find($id);
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = \Storage::disk('s3')->put('user_profile_images', $image);
            // dd($imageName);
            DB::table('users')
                ->where('id', $id)
                ->update([
                    'name' => $validateData['fname'],
                    'last_name' => $validateData['lname'],
                    'date_of_birth' => $newd_o_b,
                    // 'email' => $validateData['email'],
                    'phone_number' => $validateData['phoneNumber'],
                    'office_address' => $validateData['address'],
                    // 'country_id' => $validateData['country'],
                    // 'state_id' => $validateData['state'],
                    // 'city_id' => $validateData['city'],
                    'bio' => $request['bio'],
                    'specialization' => $validateData['specialization'],
                    'user_image' => $imageName,
                    // 'zip_code' => $validateData['zip_code'],
                ]);
            $files = $request->file('file');
            if ($files != '') {
                foreach ($files as $file) {
                    $filename = \Storage::disk('s3')->put('doctor_certificates', $file);
                    DoctorCertificate::create([
                        'certificate_file' => $filename,
                        'doc_id' => $id,
                    ]);
                }
                // dd($file);
            }
        } else {
            DB::table('users')
                ->where('id', $id)
                ->update([
                    'name' => $validateData['fname'],
                    'last_name' => $validateData['lname'],
                    'date_of_birth' => $newd_o_b,
                    'phone_number' => $validateData['phoneNumber'],
                    'office_address' => $validateData['address'],
                    // 'country_id' => $validateData['country'],
                    // 'state_id' => $validateData['state'],
                    // 'city_id' => $validateData['city'],
                    'bio' => $request['bio'],
                    'specialization' => $validateData['specialization'],
                    // 'zip_code' => $validateData['zip_code'],
                ]);
            $files = $request->file('file');
            if ($files != '') {
                foreach ($files as $file) {
                    $filename = \Storage::disk('s3')->put('doctor_certificates', $file);
                    DoctorCertificate::create([
                        'certificate_file' => $filename,
                        'doc_id' => $id,
                    ]);
                }
                // dd($file);
            }
        }
        Flash::success('Profile Updated successfully.');
        return redirect()->route('profile',$doctor->username);
    }


//new update doc
public function updateDocProfile(Request $request)
{
    $input=$request->all();
    $datecheck =$input['dob'];
    $date = str_replace('-', '/', $datecheck);
    $newd_o_b = date("Y-m-d", strtotime($date));
    $admin = DB::table('users')->where('user_type','=','admin')->first();
    if (str_contains($datecheck, "/")) {
        $newd_o_b;
    } else {
        $datecheck;
    }
    $validateData = $request->validate([
        'user_id' => ['required'],
        'fname' => ['required', 'string'],
        'lname' => ['required', 'string'],
        'email' =>  ['required', 'email'],
        'phoneNumber' => ['required'],
        'address' => ['required'],
        'state' => ['required'],
        'city' => ['required'],
        'zip_code' => ['required'],
        'reason' => ['required'],
    ]);
    $time=time();
    $current_date=date('Y-m-d',$time);
    $min_date=$date = strtotime($current_date.' -18 year');
    $min_age_date=date('Y-m-d', $min_date);
    if($datecheck>$min_age_date){
        Flash::error('Age must be greater than 18.');
        return back();
    }
    $id = auth()->user()->id;
    $doctor=User::find($id);

    $query = DB::table('users')->where('id',$input['user_id'])->first();

    // dd($query);

    $old = array(
        'name' => $query->name,
        'last_name' => $query->last_name,
        'email'=> $query->email,
        'date_of_birth' => $query->date_of_birth,
        'phone_number' => $query->phone_number,
        'office_address' => $query->office_address,
        'state_id' => $query->state_id,
        'city_id' => $query->city_id,
        'bio' => $query->bio,
        'zip_code' => $query->zip_code,
    );

    $new = array(
        'name' => $validateData['fname'],
        'last_name' => $validateData['lname'],
        'email'=> $validateData['email'],
        'date_of_birth' => $newd_o_b,
        'phone_number' => $validateData['phoneNumber'],
        'office_address' => $validateData['address'],
        'state_id' => $validateData['state'],
        'city_id' => $validateData['city'],
        'bio' => $request['bio'],
        'zip_code' => $validateData['zip_code'],
    );

    $differenceArray1 = array_diff($old, $new);

    // dd($old,$new,$differenceArray1);

    if (count($differenceArray1)) {
        $insertId = DB::table('doctor_profile_update')->insertGetId([
            'user_id' => $input['user_id'],
            'name' => $validateData['fname'],
            'last_name' => $validateData['lname'],
            'email'=> $validateData['email'],
            'date_of_birth' => $newd_o_b,
            'phone_number' => $validateData['phoneNumber'],
            'office_address' => $validateData['address'],
            'state_id' => $validateData['state'],
            'city_id' => $validateData['city'],
            'bio' => $request['bio'],
            'zip_code' => $validateData['zip_code'],
            'reason' => $validateData['reason'],
        ]);
        if ($request->hasFile('certificate')) {
            $files = $request->file('certificate');
            $filename = \Storage::disk('s3')->put('doctor_certificates', $files);
            DB::table('doctor_profile_update')->where('id',$insertId)->update([
                'certificate'=>$filename,
            ]);
            // DoctorCertificate::create([
            //     'certificate_file' => $filename,
            //     'doc_id' => $id,]);
        }
            // dd('ok');
        $notification_id = Notification::create([
            'text' => 'Profile updation By ' .$validateData['fname'],
            'user_id' => $admin->id,
            'user_type' => 'admin',
            'type' => '/admin/doctor/profile_update'
        ]);
        $data = [
            'text' => 'Profile updation By ' .$validateData['fname'],
            'user_id' => $admin->id,
            'type' => '/admin/doctor/profile_update',
            'session_id' => "null",
            'received' => 'false',
            'appoint_id' => 'null',
            'refill_id' => 'null',
        ];
        try {

            // \App\Helper::firebase($res->user_id,'notification',$notification_id->id,$data);
        } catch (\Throwable $th) {
            //throw $th;
        }
        event(new RealTimeMessage($admin->id));
        try {
            Mail::to($admin->email)->send(new UpdateProfileAdminMail());

            return redirect()->back()->with('success', 'Profile Update Request Sent to Admin Successfully');

        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('success', 'Profile Update Request Sent to Admin Successfully');

        }
    } else {
        if ($request->hasFile('certificate')) {
            $files = $request->file('certificate');
            $filename = \Storage::disk('s3')->put('doctor_certificates', $files);
            DB::table('doctor_profile_update')->insert([
                'user_id' => $input['user_id'],
                'name' => $query->name,
                'last_name' => $query->last_name,
                'email'=> $query->email,
                'date_of_birth' => $query->date_of_birth,
                'phone_number' => $query->phone_number,
                'office_address' => $query->office_address,
                'state_id' => $query->state_id,
                'city_id' => $query->city_id,
                'bio' => $query->bio,
                'zip_code' => $query->zip_code,
                'reason' => $validateData['reason'],
                'certificate'=>$filename,
            ]);
            return redirect()->back()->with('success', 'Certificate Addition Request Sent to Admin Successfully');
        }else{
            return redirect()->back()->with('error', 'you have not made any changes');
        }
    }
}




    //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function show(Profile $profile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function edit(Profile $profile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Profile $profile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profile $profile)
    {
        //
    }
    public function view_Api_password($token,$email){
        $get_token = $token;
        $get_email = $email;
        $user = DB::table('password_resets')->where('email',$get_email)->where('token',$get_token)->first();
        if($user != null){
            return view('auth.passwords.ApiPasswordReset',compact('get_token','get_email'));
        } else{
            return 404;
        }
    }
    public function update_Api_password(Request $request){
        $email = $request->email;
        $user = User::where('email',$email)->first();
        $id = $user->id;
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'password' => 'required|confirmed|min:8',
        ]);
        if($validator->fails()){
            return redirect()->back()->withErrors($validator);
        } else{
           DB::table('users')->where('id',$id)->update([
                'password' =>  Hash::make($request->password),
            ]);
            return redirect()->route('login');
        }

    }
}
