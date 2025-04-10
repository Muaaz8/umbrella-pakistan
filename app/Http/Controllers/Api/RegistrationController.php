<?php

namespace App\Http\Controllers\Api;
use App\Models\Contract;
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
use Image;


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

    protected function create(array $data)
    {
        if (!isset($_POST['g-recaptcha-response'])) {
            return $this->sendError([], 'Captcha not found');
        }
        
        $captcha = $_POST['g-recaptcha-response'];
        $secretKey = "6LctFXkqAAAAAIMmlIukFW8I-pb_-iUeAhB-LQ7O";
        $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secretKey . '&response=' . $captcha);
        $responseData = json_decode($response, true);
        
        if ($responseData['success'] !== true) {
            return $this->sendError([], 'Captcha not verified');
        }
        
        $user_type = $data['user_type'];
        
        $datecheck = $data['date_of_birth'];
        $newd_o_b = $this->formatDateOfBirth($datecheck);
        
        $userData = [
            'user_type' => $user_type,
            'name' => $data['name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'username' => $data['name'] . '_' . $data['last_name'],
            'country_id' => $data['country'],
            'city_id' => $data['city'],
            'state_id' => '',
            'password' => Hash::make($data['password']),
            'date_of_birth' => $newd_o_b,
            'phone_number' => $data['phone_number'],
            'gender' => $data['gender'],
            'terms_and_cond' => $data['terms_and_cond'],
            'timeZone' => $data['timezone'],
        ];
        
        if ($user_type == 'patient') {
            $userData += [
                'office_address' => "",
                'zip_code' => '',
            ];
        } else {
            $userData += [
                'office_address' => $data['address'],
                'zip_code' => null,
                'nip_number' => $data['npi'],
                'consultation_fee' => $data['consultation_fee'],
                'followup_fee' => $data['follow_up_fee'],
                'active' => '1',
                'upin' => '',
                'specialization' => $data['specializations'],
                'signature' => $data['signature'],
            ];
            
            $userData += $this->processImageUploads();
        }
        
        $user = User::create($userData);
        
        $hash_to_verify = base_convert(rand(10e12, 10e16), 10, 36);
        $otp = rand(100000, 999999);
        
        DB::table('users_email_verification')->insert([
            'verification_hash_code' => $hash_to_verify,
            'user_id' => $user->id,
            'otp' => $otp,
        ]);
        
        $emailData = [
            'hash' => $hash_to_verify,
            'user_id' => $user->id,
            'to_mail' => $user->email,
            'otp' => $otp,
        ];
        
        $this->sendNotifications($user, $emailData, $otp);
        
        if ($user_type != 'patient') {
            DB::table('doctor_percentage')->insert([
                'doc_id' => $user->id,
                'percentage' => 70,
            ]);
            
            Contract::create([
                'slug' => 'UMB' . time(),
                'provider_id' => $user->id,
                'provider_name' => $data['name'] . ' ' . $data['last_name'],
                'provider_address' => $data['address'],
                'provider_email_address' => $data['email'],
                'provider_speciality' => $data['specializations'],
                'date' => date('Y-m-d'),
                'session_percentage' => 70,
                'signature' => $data['signature'],
                'status' => 'signed',
            ]);
        }
        
        return $this->sendResponse($user, 'User Created Successfully!');
    }

    private function formatDateOfBirth($datecheck)
    {
        if (str_contains($datecheck, "/")) {
            return date("Y-m-d", strtotime($datecheck));
        }
        
        $date = str_replace('-', '/', $datecheck);
        return date("Y-m-d", strtotime($date));
    }

    private function processImageUploads()
    {
        $imageData = [
            'id_card_front' => 'doctors/' . date('YmdHis'),
            'id_card_back' => 'doctors/' . date('YmdHis'),
            'user_image' => 'user.png'
        ];
        
        $uploadFields = [
            'id_front_side' => 'id_card_front',
            'id_back_side' => 'id_card_back',
            'profile_pic' => 'user_image'
        ];
        
        foreach ($uploadFields as $requestField => $dataField) {
            if (request()->hasFile($requestField)) {
                $file = request()->file($requestField);
                $folder = $dataField === 'user_image' ? 'user_profile_images/' : 'doctors/';
                $imageName = $folder . date('YmdHis') . $file->getClientOriginalName();
                
                $img = Image::make($file);
                $img->resize(1000, 1000, function ($constraint) {
                    $constraint->aspectRatio();
                });
                
                $resource = $img->stream()->detach();
                \Storage::disk('s3')->put($imageName, $resource);
                
                $imageData[$dataField] = $imageName;
            }
        }
        
        return $imageData;
    }
    
    private function sendNotifications($user, $emailData, $otp)
    {
        try {
            Mail::to($user->email)->queue(new UserVerificationEmail($emailData)); // Consider using queue() instead of send()
        } catch (Exception $e) {
            Log::error($e);
        }
                try {
            $whatsapp = new \App\Http\Controllers\WhatsAppController();
            $res = $whatsapp->send_otp_message($user->phone_number, $otp);
            Log::error($res);
        } catch (Exception $e) {
            Log::error($e);
        }
    }

}
