<?php

namespace App\Http\Controllers\Api\DoctorsApi;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use App\Notification;
use App\Events\RealTimeMessage;
use App\Mail\UpdateProfileAdminMail;
use DB;
use App\City;
use App\Country;
use App\ActivityLog;
use App\DoctorCertificate;
use App\Specialization;
use App\State;
use App\User;
use Auth;
use DateTime;
use DateTimeZone;
use Validator;
use Illuminate\Support\Facades\Storage;
use Hash;
use App\Rules\NotSameAsOldPassword;


class ProfileController extends BaseController
{
    public function view_DocProfile(){
        $id = auth()->user()->id;
        $user = DB::table('users')->where('id', $id)->get(['id','user_type','user_image','office_address','specialization', 'email', 'name', 'last_name','phone_number','date_of_birth','upin','nip_number','gender','country_id','city_id','state_id','zip_code','timeZone'])->first();
        if(isset($user->id)){
            if($id == $user->id){
                if($user->user_type=='doctor'){
                    $doctor= $user;
                    $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);
                    $doctor->city = City::find($doctor->city_id);
                    $doctor->state = State::find($doctor->state_id);
                    $doctor->country = Country::find($doctor->country_id);
                    $doctor->spec = Specialization::find($doctor->specialization)->name;
                    $doctor->license = DB::table('doctor_licenses')
                    ->join('states','states.id','doctor_licenses.state_id')
                    ->where('doctor_id',$id)
                    ->where('is_verified',1)
                    ->select('states.name')->get();
                    $data = DB::table('activity_log')->where('user_id',$id)->select('*')->orderBy('created_at','desc')->paginate(10);
                    // dd($activities);
                    foreach ($data as $dt) {
                        $user_time_zone = Auth::user()->timeZone;
                        $date = new DateTime($dt->created_at);
                        $date->setTimezone(new DateTimeZone($user_time_zone));
                        $dt->created_at = $date->format('D, M/d/Y, g:i a');
                    }
                    // $doctor->all_patients = DB::table('sessions')->where('sessions.doctor_id', auth()->user()->id)
                    //     ->groupBy('sessions.patient_id')
                    //     ->where('sessions.status', '!=', 'pending')
                    //     ->join('users', 'sessions.patient_id', '=', 'users.id')
                    //     ->select('users.*')
                    //     ->get();
                    // foreach ($doctor->all_patients as $patient){
                    //     $patient->user_image = \App\Helper::check_bucket_files_url($patient->user_image);
                    // }
                    // $doctor['doctor'] =$doctor;
                    // $doctor['activity_log'] =$data;
                    $doc_info =['code'=> 200,'doctor_profile'=>$doctor];
                    return $this->sendResponse($doc_info,"Doctor Profile Info");
                } else{
                    $code['code'] =200;
                    return $this->sendError($code,'Sorry you are not a doctor');
                }
            } else{
                $code['code'] =200;
                return $this->sendError($code,'user id not matched to doctor ID');
            }
        } else{
            $code['code'] =200;
            return $this->sendError($code,'Somthing Went Wrong!');
        }

    }
    public function doc_activity(){
        $id = auth()->user()->id;
        $data = DB::table('activity_log')->where('user_id',$id)->select('*')->orderBy('created_at','desc')->paginate(10);
        // dd($activities);
        foreach ($data as $dt) {
            $user_time_zone = Auth::user()->timeZone;
            $date = new DateTime($dt->created_at);
            $date->setTimezone(new DateTimeZone($user_time_zone));
            $dt->created_at = $date->format('D, M/d/Y, g:i a');
        }
        $doc_activity['code'] = 200;
        $doc_activity['activity_log']= $data;
        return $this->sendResponse($doc_activity,'Doctor log Activity');
    }
    public function doc_certificate(){
        // doctor certificates
        $user =Auth::user();
        $certificates = DB::table('doctor_certificates')->where('doc_id', $user->id)->get();
        if($certificates != '[]'){
            $doctor_certificate = [];
            foreach($certificates as $cert) {
                $cert = \App\Helper::get_files_url($cert->certificate_file);
            }
            $doctor_certificate['code'] = 200;
            $doctor_certificate['certificates'] = $cert;
            return $this->sendResponse($doctor_certificate,'doctor Certificates');
        } else{
            $doc_certificate['code'] = 200;
            return $this->sendResponse($doc_certificate,'No Certificates Found');
        }
    }
    public function profile_picture(Request $request){
        $id = Auth::user()->id;
        $profile_picture =$request->user_img;
        $fileName =null;
        if($request->hasFile('user_img')){
            $file = $request->file('user_img');
            $fileName = \Storage::disk('s3')->put('doc_profile_images', $file);
            $data= DB::table('users')->where('id',$id)->update([
                'user_image' => $fileName,
            ]);
            $profileData['code'] = 200;
            return $this->SendResponse($profileData,"Doctor profile image uploaded");
        } else{
            $profileData['code'] = 200;
            return $this->SendError($profileData,"Somthing Went Wrong!");
        }
    }
    public function phone_number(Request $request){
        $id = Auth::user()->id;
        $phone_number = $request->phone_number;
        if($phone_number != ''){
            $data= DB::table('users')->where('id',$id)->update([
                'phone_number' => $phone_number,
            ]);
            $user_data['code'] = 200;
            return $this->sendResponse($user_data,"Doctor Phone Number Udpated");
        } else{
            $user_data['code'] = 200;
            return $this->sendError($user_data,"Something went wrong");
        }
    }
    // public function update_profileInfo(Request $request){
    //     $validator =Validator::make($request->all(),[
    //         'name' => 'required'
    //     ]);
    //     if($validator->fails()){
    //         $error['code'] = 200;
    //         $error['validator_error'] = $validator->errors();
    //         return $this->sendError($error,'validation Error!');
    //     } else{
    //         $id = Auth::user()->id;
    //         $first_name = $request->name;
    //         $last_name = $request->last_name;
    //         $dob = $request->dob;
    //         $email = $request->email;
    //         $address = $request->address;
    //         $reason = $request->reason;
    //         $state_id = $request->state_id;
    //         $phone_number = $request->phone_number;
    //         $city_id = $request->city_id;
    //         $zip_code = $request->zip_code;
    //         $user = Auth::user();
    //         $data= DB::table('patients_redord')->insert([
    //             'name' => ($first_name)? $first_name : $user->name,
    //             'last_name' => ($last_name)? $last_name : $user->last_name,
    //             'date_of_birth' =>($dob)? $dob : $user->date_of_birth,
    //             'email' =>($email)? $email : $user->email,
    //             'office_address' => ($address)? $address: $user->office_address,
    //             'country_id' => 233,
    //             'state_id' => ($state_id)? $state_id : $user->state_id,
    //             'city_id' => ($city_id)? $city_id : $user->city_id,
    //             'zip_code' => ($zip_code)? $zip_code : $user->zip_code,
    //             'phone_number' => ($phone_number)? $phone_number : $user->phone_number,
    //             'user_image' => $user->user_image,
    //             'user_id' => $id
    //         ]);
    //         $user_info['code'] = 200;
    //         return $this->sendResponse($user_info,"Update request is submited for admin approval!");
    //     }

    // }
    //new update doc
    public function update_profileInfo(Request $request)
    {

        $input=$request->all();
        // $datecheck =$input['dob'];
        // $date = str_replace('-', '/', $datecheck);
        // $newd_o_b = date("Y-m-d", strtotime($date));
        $admin = DB::table('users')->where('user_type','=','admin')->first();
        // if (str_contains($datecheck, "/")) {
        //     $newd_o_b;
        // } else {
        //     $datecheck;
        // }
        $validator =Validator::make($request->all(),[
                'first_name' => ['required', 'string'],
                'last_name' => ['required', 'string'],
                'phone_number' => ['required'],
                'address' => ['required'],
                'state_id' => ['required'],
                'city_id' => ['required'],
                'zip_code' => ['required'],
                'reason' => ['required'],
        ]);
        if($validator->fails()){
            $error['code'] = 200;
            $error['validator_error'] = $validator->errors();
            return $this->sendError($error,'validation Error!');
        } else{
            // $time=time();
            // $current_date=date('Y-m-d',$time);
            // $min_date=$date = strtotime($current_date.' -18 year');
            // $min_age_date=date('Y-m-d', $min_date);
            // if($datecheck>$min_age_date){
            //     $agecheck['code'] = 200;
            //     return $this->sendError($agecheck,'Age must be greater than 18');
            // }
            $id = auth()->user()->id;
            $doctor=User::find($id);
            $query = DB::table('users')->where('id',$id)->first();
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
                'name' => $request->first_name,
                'last_name' => $request->last_name,
                // 'email'=> $request->email,
                // 'date_of_birth' => $newd_o_b,
                'phone_number' => $request->phone_number,
                'office_address' => $request->address,
                'state_id' => $request->state_id,
                'city_id' => $request->city_id,
                'bio' => $request->bio,
                'zip_code' => $request->zip_code,
            );
            $differenceArray1 = array_diff($old, $new);
            if (count($differenceArray1)) {
                $insertId = DB::table('doctor_profile_update')->insertGetId([
                    'user_id' => $id,
                    'name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email'=> $query->email,
                    'date_of_birth' => $query->date_of_birth,
                    'phone_number' => $request->phone_number,
                    'office_address' => $request->address,
                    'state_id' => $request->state_id,
                    'city_id' => $request->city_id,
                    'bio' => $request->bio,
                    'zip_code' => $request->zip_code,
                    'reason' => $request->reason,
                ]);
                if ($request->hasFile('certificate')) {
                    $files = $request->file('certificate');
                    $filename = \Storage::disk('s3')->put('doctor_certificates', $files);
                    DB::table('doctor_profile_update')->where('id',$insertId)->update([
                        'certificate'=>$filename,
                    ]);
                }
                $notification_id = Notification::create([
                    'text' => 'Profile updation By ' .$request->first_name,
                    'user_id' => $admin->id,
                    'user_type' => 'admin',
                    'type' => '/admin/doctor/profile_update'
                ]);
                $data = [
                    'text' => 'Profile updation By ' .$request->first_name,
                    'user_id' => $admin->id,
                    'type' => '/admin/doctor/profile_update',
                    'session_id' => "null",
                    'received' => 'false',
                    'appoint_id' => 'null',
                    'refill_id' => 'null',
                ];
                try {
                    \App\Helper::firebase($res->user_id,'notification',$notification_id->id,$data);
                } catch (\Throwable $th) {
                    //throw $th;
                }
                event(new RealTimeMessage($admin->id));
                try {
                    Mail::to($admin->email)->send(new UpdateProfileAdminMail());
                    $updateProfile['code'] = 200;
                    return $this->sendResponse($updateProfile, 'Profile Update Request Sent to Admin Successfully');

                } catch (\Throwable $th) {
                    //throw $th;
                    $updateProfile['code'] = 200;
                    return $this->sendResponse($updateProfile, 'Profile Update Request Sent to Admin Successfully');

                }
            }
        }

    }
    public function doc_certificate_update(Request $request){
            $id = auth()->user()->id;
            $doctor=User::find($id);
            $query = DB::table('users')->where('id',$id)->first();
            // dd($query);
            if ($request->hasFile('certificate')) {
                $files = $request->file('certificate');
                $filename = \Storage::disk('s3')->put('doctor_certificates', $files);
                DB::table('doctor_profile_update')->insert([
                    'user_id' => $id,
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
                    'reason' => $request->reason,
                    'certificate'=>$filename,
                ]);
                $updateDoc['code'] = 200;
                return $this->sendResponse($updateDoc, 'Certificate Addition Request Sent to Admin Successfully');
            }else{
                $updateDoc['code'] = 200;
                return $this->sendError($updateDoc, 'you have not made any changes');
            }
    }
    public function change_password(Request $request){
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'old_password' => ['required',new NotSameAsOldPassword($request->new_password)],
            'new_password' => 'required|min:8',
            'password_confirmation' => 'required_with:new_password|same:new_password|min:8'
        ]);
        if($validator->fails()) {
            $passchange['code'] = 200;
            $passchange['validator_error'] = $validator->errors();
            return $this->sendError($passchange,'Validation error');
        } else{
            if(!Hash::check($request->old_password, auth()->user()->password)){
                $passchange['code'] = 200;
                return $this->sendError($passchange,"Old Password Doesn't match!");
            } else{
                $passowrdchanged= User::whereId(auth()->user()->id)->update([
                    'password' => Hash::make($request->new_password)
                ]);
                $passowrd_changed['code'] = 200;
                $passowrd_changed['passowrdchanged'] = $passowrdchanged;
                return $this->sendResponse($passowrd_changed,"Password changed successfully!");
            }
        }
    }
}
