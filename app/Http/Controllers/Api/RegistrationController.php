<?php

namespace App\Http\Controllers\Api;
use Log;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Http\Request;
use App\User;
use DB;
use Exception;
use File;
use Barryvdh\DomPDF\PDF;
use App\DoctorLicense;
use App\Specialization;
use App\State;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Str;
use Carbon\Carbon;
use Mail;
use Auth;
use App\ActivityLog;
use App\Session;
use App\Mail\UserVerificationEmail;
use App\Mail\SendResetLinkEmail;
use App\Mail\ApiPasswordReset;
use Illuminate\Auth\Events\PasswordReset;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Response;


class RegistrationController extends BaseController
{
    //step 1
    public function patient_registration(Request $request){
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);
        if($validator->fails()) {
            $data['validation_error'] =$validator->errors();
            return $this->sendError($data,"validation Fail",Response::HTTP_UNPROCESSABLE_ENTITY);
          } else{
            return $this->sendResponse([],'Validation Passed');
        }
    }
    public function get_state(Request $request){
        $validator = Validator::make($request->all(), [
            'zip_code' => 'required',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] =$validator->errors();
            return $this->sendError($data,"validation Fail",Response::HTTP_UNPROCESSABLE_ENTITY);
        } else{
            //state by zipcode
            $states = DB::table('tbl_zip_codes_cities')
            ->join('states','tbl_zip_codes_cities.state','states.name')
            ->join('cities','tbl_zip_codes_cities.city','cities.name')
            ->where('tbl_zip_codes_cities.zip_code',$request['zip_code'])
            ->select('tbl_zip_codes_cities.*','states.id as state_id','cities.id as city_id')
            ->first();
            // dd($states);
            if($states != null){
                $data['states'] =$states;
                return $this->sendResponse($data,'State and city found');
            } else{
                $data['states'] =$states;
                return $this->sendError($data,"State and City not found",Response::HTTP_NOT_FOUND);
                // return $this->sendError($states,'State and City not found');
            }
        }
    }
    public function store_patient(Request $request){
        // $timeZone = $geo_info['timezone'];
        $data = json_decode($request->data);
        $fileData = $request->file;
        $fileName =null;
        if(request()->hasFile('file'))
        {
            $file = request()->file('file');
            $fileName = \Storage::disk('s3')->put('medical_records', $file);
        }
        // if user is patient
        if($request->rep_radio =="0"){
            $datecheck = $request->date_of_birth;
            // dd($datecheck);
            $date = str_replace('-', '/', $datecheck);
            $newd_o_b = date("Y-m-d", strtotime($date));
            if (str_contains($datecheck, "/")) {
                $newd_o_b;
            }
            $user = User::create([
                'user_type' => 'patient',
                'name' => $data->name,
                'last_name' => $data->last_name,
                'email' => $data->email,
                'username' => $data->username,
                'country_id' => '233',
                'city_id' => $data->city,
                'state_id' => $data->state,
                'password' => Hash::make($data->password),
                'date_of_birth' => $newd_o_b,
                'phone_number' => $data->phone_number ,
                'med_record_file' => ($fileName)? $fileName : " ",
                'office_address' => $data->address,
                'zip_code' => $data->zip_code,
                'gender' => $data->gender,
                'terms_and_cond' => $data->terms_and_cond,
                'timeZone' => "",
            ]);
            $x = rand(10e12, 10e16);
            $hash_to_verify = base_convert($x, 10, 36);
            $data = [
                'hash' => $hash_to_verify,
                'user_id' => $user->id,
                'to_mail' => $user->email,
            ];
            try {
                Mail::to($user->email)->send(new UserVerificationEmail($data));
            } catch (Exception $e) {
                Log::error($e);
            }
            DB::table('users_email_verification')->insert([
                'verification_hash_code' => $hash_to_verify,
                'user_id' => $user->id,
            ]);

            $data_email["email"] = $user->email;
            $data_email["title"] = "Terms And Conditions";
            $time = DB::table('documents')->where('name','term of use')->select('updated_at')->first();
            $data_email["revised"] = date('m-d-Y',strtotime($time->updated_at));
            $pdf = app()->make(PDF::class);
            $pdf = $pdf->loadView('terms.index', $data_email);
            \Storage::disk('s3')->put('term_and_conditions/' . $user->name . '_term_and_conditions.pdf', $pdf->output());
            DB::table('user_term_and_condition_status')->insert([
                'term_and_condition_file' => 'term_and_conditions/' . $user->name . '_term_and_conditions.pdf',
                'user_id' => $user->id,
                'status' => 1,
            ]);
            $data['user'] =$user;
            return $this->sendResponse($data,'User Created Successfully!');
        } else{
            $datecheck = $data->date_of_birth;
            // dd($datecheck);
            $date = str_replace('-', '/', $datecheck);
            $newd_o_b = date("Y-m-d", strtotime($date));
            if (str_contains($datecheck, "/")) {
                $newd_o_b;
            }
            $user = User::create([
                'user_type' => 'patient',
                'email' => $data->email,
                'username' => $data->username,
                'password' => Hash::make($data->password),
                'name' => $data->name,
                'last_name' => $data->last_name,
                'med_record_file' => $fileName,
                'country_id' => '233',
                'city_id' => $data->city,
                'state_id' => $data->state,
                'date_of_birth' => $newd_o_b,
                'phone_number' => $data->phone_number,
                'office_address' => $data->address,
                'zip_code' => $data->zip_code,
                'gender' => $data->gender,
                'representative_name' => $data->fullname,
                'representative_relation' =>$data->relation,
                'terms_and_cond' => $data->terms_and_cond,
                'timeZone' => "",
            ]);
            $x = rand(10e12, 10e16);
            $hash_to_verify = base_convert($x, 10, 36);
            $data = [
                'hash' => $hash_to_verify,
                'user_id' => $user->id,
                'to_mail' => $user->email,
            ];
            try {
                Mail::to($user->email)->send(new UserVerificationEmail($data));
            } catch (Exception $e) {
                Log::error($e);
            }
            DB::table('users_email_verification')->insert([
                'verification_hash_code' => $hash_to_verify,
                'user_id' => $user->id,
            ]);
            $data_email["email"] = $user->email;
            $data_email["title"] = "Terms And Conditions";
            $time = DB::table('documents')->where('name','term of use')->select('updated_at')->first();
            $data_email["revised"] = date('m-d-Y',strtotime($time->updated_at));
            $pdf = app()->make(PDF::class);
            $pdf = $pdf->loadView('terms.index', $data_email);
            \Storage::disk('s3')->put('term_and_conditions/' . $user->name . '_term_and_conditions.pdf', $pdf->output());
            DB::table('user_term_and_condition_status')->insert([
                'term_and_condition_file' => 'term_and_conditions/' . $user->name . '_term_and_conditions.pdf',
                'user_id' => $user->id,
                'status' => 1,
            ]);
            try {
                $adminUsers = DB::table('users')->where('user_type', 'admin')->get();
                foreach ($adminUsers as $adminUser) {
                    $admin_data_email["email"] =  $adminUser->email;
                    $admin_data_email["title"] = "Terms And Conditions";
                    Mail::send('emails.termAndConditionDoctorEmail', $admin_data_email, function ($message1) use ($admin_data_email, $pdf) {
                        $message1->to($admin_data_email["email"])->subject($admin_data_email["title"])->attachData($pdf->output(), "TermsAndConditions.pdf");
                    });
                }
                Mail::send('emails.termAndConditionDoctorEmail', $data_email, function ($message) use ($data_email, $pdf) {
                    $message->to($data_email["email"])->subject($data_email["title"])->attachData($pdf->output(), "TermsAndConditions.pdf");
                });
            } catch (Exception $e) {
                Log::info($e);
            }
            $data['user'] =$user;
            return $this->sendResponse($data,'User Created Successfully!');
        }
    }

    // doctor registration
    public function validation_doctor(Request $request){
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] =$validator->errors();
            return $this->sendError($data,"validation Fail",Response::HTTP_UNPROCESSABLE_ENTITY);
          } else{
            return $this->sendResponse([],'Validation Passed');
        }
    }
    public function get_doc_state(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'last_name' => 'required',
            'gender' => 'required',
            'date_of_birth' => 'required',
            'phone_number' => 'required',
            'zip_code' => 'required',
            'appartment' => 'required',
            'address' => 'required',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] =$validator->errors();
            return $this->sendError($data,"validation Fail",Response::HTTP_UNPROCESSABLE_ENTITY);
        } else{
             //state by zipcode
            $states = DB::table('tbl_zip_codes_cities')
            ->join('states','tbl_zip_codes_cities.state','states.name')
            ->join('cities','tbl_zip_codes_cities.city','cities.name')
            ->where('tbl_zip_codes_cities.zip_code',$request['zip_code'])
            ->select('tbl_zip_codes_cities.*','states.id as state_id','cities.id as city_id')
            ->first();
            if(isset($states) != null){
                return $this->sendResponse([],'Validation Passed');
            } else{
                $data['states'] = $states;
                return $this->sendError($states,'State and City not found',Response::HTTP_NOT_FOUND);
            }
        }

    }
    public function npi_validation(Request $request){
        $validator = Validator::make($request->all(), [
            'npi' => 'required',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] =$validator->errors();
            return $this->sendError($data,"validation Fail",Response::HTTP_UNPROCESSABLE_ENTITY);
          } else{
            return $this->sendResponse([],'Validation Passed');
        }
    }
    public function doc_specialization(){
            $specialization = Specialization::where('status',1)->get();
            $data['specialization'] = $specialization;
            return $this->sendResponse($data,'Validation Passed');
    }
    public function lienced_state(){
        $states = State::where('country_code', 'US')->get();
        $data['states'] = $states;
        return $this->sendResponse($data,'Lienced States');
    }
    public function store_doctor(Request $request){
        $data =json_decode($request->data,true);
        $validator = Validator::make($data, [
            'signature' => 'required',
        ]);
        if($validator->fails()) {
            $data_d['validation_error'] = $validator->errors();
            return $this->sendError($data_d,"validation Fail",Response::HTTP_UNPROCESSABLE_ENTITY);
        } else{
            $frontImgfileName =null;
            $backImgfileName =null;
            $signature = $data['signature'];
            if(request()->hasFile('front_img'))
            {
                $file = request()->file('front_img');
                $frontImgfileName = \Storage::disk('s3')->put('medical_records', $file);
                // // $file = request()->file('record');
                // $frontImgfileName =md5($file->getClientOriginalName()).time().".".$file->getClientOriginalExtension();
                // $file->move(public_path().'/uploads/',$frontImgfileName);
            }
            if(request()->hasFile('back_img'))
            {
                $file = request()->file('back_img');
                $backImgfileName = \Storage::disk('s3')->put('medical_records', $file);
                // // $file = request()->file('record');
                // $backImgfileName =md5($file->getClientOriginalName()).time().".".$file->getClientOriginalExtension();
                // $file->move(public_path().'/uploads/',$backImgfileName);
            }
            $datecheck = $data['date_of_birth'];
            // dd($datecheck);
            $date = str_replace('-', '/', $datecheck);
            $newd_o_b = date("Y-m-d", strtotime($date));
            if (str_contains($datecheck, "/")) {
                $newd_o_b;
            }
            $user =user::create([
                'user_type' =>'doctor',
                'name' => $data['name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'username' => $data['username'],
                'country_id' => "233",
                'city_id' => $data['city'],
                'state_id' => $data['state'],
                'password' =>Hash::make($data['password']),
                'date_of_birth' => $newd_o_b,
                'phone_number' => $data['phone_number'],
                'office_address' => $data['address'],
                'zip_code' => $data['zip_code'],
                'nip_number' => $data['npi'],
                'upin' => $data['upin'],
                'specialization' => $data['specializations'],
                'gender' => $data['gender'],
                'id_card_front' => $frontImgfileName,
                'id_card_back' => $backImgfileName,
                'terms_and_cond' => $data['terms_and_cond'],
                'signature' => $data['signature'],
                'timeZone' => '',
            ]);
            $licenced = $data['licensed_states'];
            foreach($licenced as $licence) {
                DoctorLicense::create([
                    'doctor_id' => $user->id,
                    'state_id' => $licence
                ]);
            }
            $x = rand(10e12, 10e16);
            $hash_to_verify = base_convert($x, 10, 36);
            $data = [
                'hash' => $hash_to_verify,
                'user_id' => $user->id,
                'to_mail' => $user->email,
            ];
            try {
                Mail::to($user->email)->send(new UserVerificationEmail($data));
            } catch (Exception $e) {
                Log::error($e);
            }
            DB::table('users_email_verification')->insert([
                'verification_hash_code' => $hash_to_verify,
                'user_id' => $user->id,
            ]);
            $doctorData['user'] = $user;
            return $this->sendResponse($doctorData,'Doctor Created Successfully!');
        }
    }
    //login
    public function login(Request $request)
    {
        $login = $request->input('email');
        $password = $request->input('password');
        $timeZone = 'Asia/Karachi';
    
        if (!$login || !$password) {
            return $this->sendError([], "Invalid email or password", Response::HTTP_UNAUTHORIZED);
        }
    
        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_number';
        $credentials = [$fieldType => $login, 'password' => $password];
    
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
    
            if (!$user || !$user->id) {
                return $this->sendError([], "User authentication failed.", Response::HTTP_UNAUTHORIZED);
            }
    
            \DB::table('users')->where('id', $user->id)->update(['timeZone' => $timeZone]);
    
            $user_info = DB::table('users')
                ->leftJoin('users_email_verification', 'users.id', '=', 'users_email_verification.user_id')
                ->where('users.id', $user->id)
                ->select('users.name', 'users.id', 'users.last_name','users.email', 'users.phone_number' , 'users_email_verification.status as email_status')
                ->first();
    
            if ($user->user_type == 'doctor' && $user->active != '1') {
                return $this->sendError([], "Your account is not active.", Response::HTTP_UNAUTHORIZED);
            }
    
            $token = $user->createToken('MyApp')->plainTextToken;
    
            return $this->sendResponse([
                'user' => $user_info,
                'user_type' => $user->user_type,
                'token' => $token,
                'email_verification_status' => $user_info->email_status ?? null,
            ], 'User logged in successfully.');
        }
    
        return $this->sendError([], "Invalid email or password", Response::HTTP_UNAUTHORIZED);
    }
    //logout
    public function logout(Request $request){
        $user = auth('sanctum')->user();
        $type = $user->user_type;
        if ($type == 'doctor') {
            ActivityLog::create([
                'activity' => 'logged out',
                'type' => 'logout',
                'user_id' => $user->id,
                'user_type' => 'doctor',
            ]);
            Session::where('doctor_id', $user->id)->where('remaining_time','!=','full')->update(['status' => 'ended', 'queue' => 0]);
            Session::where('doctor_id', $user->id)->where('status','invitation sent')->orwhere('status','doctor joined')->update(['status' => 'paid']);
            $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
            return $this->sendResponse([],"User Logout!");
        }
        if ($type == 'patient') {
            ActivityLog::create([
                'activity' => 'logged out',
                'type' => 'logout',
                'user_id' => $user->id,
                'user_type' => 'patient',
            ]);
            Session::where('patient_id', $user->id)->where('remaining_time','!=','full')->update(['status' => 'ended', 'queue' => 0]);
            // $user = request()->user(); //or Auth::user()
            $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
            return $this->sendResponse([],"User Logout!");
        }
    }
    // reset password
    public function reset_password(Request $request){
        $user = DB::table('users')->where('email', '=', $request->email)->first();
        //Check if the user exists
        if($user == null) {
            return $this->sendError([],'User not found',Response::HTTP_NOT_FOUND);
        } else{
            $token = Str::random(20);
            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);
            $this->sendResetEmail($request->email ,$token);
            return $this->sendResponse([],"email varified");
        }

    }
    public function sendResetEmail($email, $token){
        //Retrieve the user from the database
        $user = DB::table('users')->where('email', $email)->select('name', 'email')->first();
        // $tokenV = DB::table('password_resets')->where('email',$user->email)->where('token',$token)->select('token', 'email')->first();
        //Generate, the password reset link. The token generated is embedded in the link
        $link = url('').'/api/pasasword/reset/'. $token.'/'. urlencode($email);
        Mail::to($user->email)->send(new ApiPasswordReset($link));
    }
    public function email_varification(Request $request){
        $mail = $request->email;
        $user = User::where('email',$mail)->first();
        if($user != "" ){
            $check_email = DB::table('users_email_verification')->where('user_id', $user->id)->first();
            if($check_email->status ==1){
                $data['status'] = "varified";
                return $this->sendResponse($data,"email varified");
            } else{
                $data['status'] = "not varified";
                return $this->sendError($data,"email not varified",Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        } else{
            return $this->sendError([],"invalid email",Response::HTTP_UNPROCESSABLE_ENTITY);
        }

    }
    // public function after_login_verification_check(){
    //     $user=auth()->user();
    //     if($user->user_type=='doctor'){
    //         if($user->active=='1'){
    //             $date = User::convert_utc_to_user_timezone($user->id,date('Y-m-d H:i:s'))['datetime'];
    //             $date = explode(" ",$date)[0];
    //             $contract = DB::table('contracts')->where('provider_id',$user->id)->first();
    //             if($date >= $contract->date){
    //                 $timestamp = date("d-m-Y h:i:s a");
    //                 $date = \Carbon\Carbon::createFromFormat('d-m-Y h:i:s a',$timestamp,'UTC')->setTimezone('UTC');
    //                 $user->last_activity=$date;
    //                 $user->save();
    //                 return $next($request);
    //             } else {
    //                 $user = DB::table('users')
    //                 ->join('users_email_verification','users.id','users_email_verification.user_id')
    //                 ->where('users.id',auth()->user()->id)
    //                 ->select('users.*','users_email_verification.status as email_status')
    //                 ->first();
    //                 if ($user->id_card_front == '' && $user->id_card_back == '') {
    //                     $id_card_status = 0;
    //                 }elseif($user->id_card_front == '' || $user->id_card_back == ''){
    //                     $id_card_status = 0;
    //                 }else{
    //                     $id_card_status = 1;
    //                 }
    //                 $user->contract_date = DB::table('contracts')->where('provider_id',Auth()->user()->id)->orderby('id','desc')->first();
    //                 if(isset($user->contract_date)){
    //                     $user->contract_date->date = date('m-d-Y', strtotime($user->contract_date->date));
    //                     $contract = 1;
    //                 } else{
    //                     $contract = 0;
    //                 }
    //             }
    //         } elseif($user->active=='0' && $user->status=='ban'){
    //             $doctor_status = 0;
    //         } else{
    //             $user = DB::table('users')
    //             ->join('users_email_verification','users.id','users_email_verification.user_id')
    //             ->where('users.id',auth()->user()->id)
    //             ->select('users.*','users_email_verification.status as email_status')
    //             ->first();
    //             if($user->email_status){
    //                 $email_verification_status = $user->email_status;
    //             } elseif ($user->id_card_front == '' && $user->id_card_back == '') {
    //                 $id_card_status = 0;
    //             } elseif($user->id_card_front == '' || $user->id_card_back == ''){
    //                 $id_card_status = 0;
    //             } else{
    //                 $id_card_status = 1;
    //             }
    //             if($user->id_card_front !=''){
    //                 $id_card_status = 1;
    //             }
    //             $user->contract_date = DB::table('contracts')->where('provider_id',Auth()->user()->id)->orderby('id','desc')->first();
    //             if(isset($user->contract_date)){
    //                 $user->contract_date->date = date('m-d-Y', strtotime($user->contract_date->date));
    //                 $contract = 1;
    //             } else{
    //                $contract = 0;
    //             }
    //             $userData['email_verification_status'] = $email_verification_status;
    //             $userData['id_card_status'] = $id_card_status;
    //             $userData['contract'] = $contract;
    //             return $this->sendResponse($userData,'doctor info');
    //         }
    //     } else{
    //         return $next($request);
    //     }
    // }
    public function upload_id_Card(Request $request){
        $doctor = Auth::user();
        if(request()->hasFile('front_img'))
        {
            $file = request()->file('front_img');
            $frontImgfileName = \Storage::disk('s3')->put('medical_records', $file);
        }
        if(request()->hasFile('back_img'))
        {
            $file = request()->file('back_img');
            $backImgfileName = \Storage::disk('s3')->put('medical_records', $file);
        }
        $doctor->update([
            'id_card_front' => $frontImgfileName,
            'id_card_back' => $backImgfileName,
        ]);
        return $this->sendResponse([],'id card uploaded');
    }
    public function resend_verification(){
        $doctor = Auth::user();
        try {
            $x = rand(10e12, 10e16);
            $hash_to_verify = base_convert($x, 10, 36);
            $data = [
                'hash' => $hash_to_verify,
                'user_id' => $doctor->id,
                'to_mail' => $doctor->email,
            ];
            DB::table('users_email_verification')->where('user_id', $doctor->id)->update([
                'verification_hash_code' => $hash_to_verify,
            ]);
            Mail::to($doctor->email)->send(new UserVerificationEmail($data));

            $emailSend['status'] = 'Email Resend';
            return $this->sendResponse($emailSend,'Email Resend');
        } catch (Exception $e) {
            Log::error($e);
            $emailSend['status'] = $e;
            return $this->sendError($emailSend,'Email Resend');
        }
    }
    public function sessionCheck($session_id){
       $session_detail =  DB::table('sessions')->where('id',$session_id)->first();
       $sessionData['session_detail'] = $session_detail;
       return $this->sendResponse($sessionData,'Session details');
    }

}
