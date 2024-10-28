<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use Auth;
use App\Country;
use App\State;
use App\City;
use DateTime;
use App\User;
use DB;
use DateTimeZone;
use File;
use Validator;
use App\MedicalProfile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Rules\NotSameAsOldPassword;


class ProfileController extends BaseController
{
    //profile and activity
    public function userinfo(){
        $data = Auth::user();
        $id = $data->id;
        $patient = $data;
        if($data != null){
            if($data->city_id != null){
                $city = City::where('id', $patient->city_id)->first();
                $patient->city = $city['name'];
            } else{
                $patient->city = 'None';
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
            // $patient->sessions = $userobj->get_recent_sessions($id);
            // $patient->activities = DB::select("SELECT *, CASE `type` WHEN 'login' THEN 'Last Logged In' WHEN 'logout' THEN 'Last Logged Out' WHEN 'prescription added' THEN 'Prescription Status' WHEN 'session recommendations' THEN 'Session Recommendation Status' WHEN 'session start' THEN 'Session Status' WHEN 'session end' THEN 'Session Ended Status' WHEN 'order' THEN 'Order Status' END AS `heading`, CASE `type` WHEN 'login' THEN 'badge-success' WHEN 'logout' THEN 'badge-warning' WHEN 'prescription added' THEN 'badge-info' WHEN 'session recommendations' THEN 'badge-info' WHEN 'session start' THEN 'badge-success' WHEN 'session end' THEN 'badge-warning' WHEN 'order' THEN 'badge-info' END AS `color` FROM `activity_log` WHERE user_id = '$id' AND `type` NOT IN ('record', 'product_del_request', 'product_created', 'product_category_created', 'product_sub_category_created') order by created_at desc");
            // foreach ($patient->activities as $activity) {
            //     $user_time_zone = Auth::user()->timeZone;
            //     $date = new DateTime($activity->created_at);
            //     $date->setTimezone(new DateTimeZone($user_time_zone));
            //     $activity->created_at = $date->format('D, M/d/Y, g:i a');
            // }
            $data_patient['code'] = 200;
            $data_patient['patient_profile'] = $patient;
            return $this->sendResponse($data_patient,"User Info");
        } else{
            $data_patient['code'] = 200;
        return $this->sendError($data_patient,"Somthing went wrong!");
        }
    }
    //profile picture
    public function profile_picture(Request $request){
        $id = Auth::user()->id;
        $profile_picture =$request->user_img;
        $fileName =null;
        if($request->hasFile('user_img')){
            $file = $request->file('user_img');
            $fileName = \Storage::disk('s3')->put('user_profile_images', $file);
            $data= DB::table('users')->where('id',$id)->update([
                'user_image' => $fileName,
            ]);
            $profileData['code'] = 200;
            return $this->SendResponse($profileData,"Profile Image Uploaded");
        } else{
            $profileData['code'] = 200;
            return $this->SendError($profileData,"Somthing Went Wrong!");
        }
    }
    //number update
    public function profile_number(Request $request){
        $id = Auth::user()->id;
        $phone_number = $request->phone_number;
        if($phone_number != ''){
            $data= DB::table('users')->where('id',$id)->update([
                'phone_number' => $phone_number,
            ]);
            $user_data['code'] = 200;
            return $this->sendResponse($user_data,"Phone Number Udpated");
        } else{
            $user_data['code'] = 200;
            return $this->sendError($user_data,"Error");
        }
    }
    //update profile data
    public function update_profileInfo(Request $request){
        $id = Auth::user()->id;
        $first_name = $request->name;
        $last_name = $request->last_name;
        $dob = $request->dob;
        $email = $request->email;
        $address = $request->address;
        $reason = $request->reason;
        $state_id = $request->state_id;
        $city_id = $request->city_id;
        $zip_code = $request->zip_code;
        $user = Auth::user();
        // dd($user);
        $data= DB::table('patients_redord')->insert([
            'name' => ($first_name)? $first_name : $user->name,
            'last_name' => ($last_name)? $last_name : $user->last_name,
            'date_of_birth' =>($dob)? $dob : $user->date_of_birth,
            'email' =>($email)? $email : $user->email,
            'office_address' => ($address)? $address: $user->office_address,
            'country_id' => 233,
            'state_id' => ($state_id)? $state_id : $user->state_id,
            'city_id' => ($city_id)? $city_id : $user->city_id,
            'zip_code' => ($zip_code)? $zip_code : $user->zip_code,
            'phone_number' => $user->phone_number,
            'user_image' => $user->user_image,
            'user_id' => $id
        ]);
        $user_info['code'] = 200;
        return $this->sendResponse($user_info,"Update request is submited for admin approval!");
    }
    //change password
    public function changepassword(Request $request){
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'old_password' => ['required',new NotSameAsOldPassword($request->new_password)],
            'new_password' => 'required|min:8',
            'confirm_password' =>'required_with:new_password|same:new_password|min:8'
        ]);
        if($validator->fails()) {
            $passchange['code'] = 200;
            $passchange['validator_error'] = $validator->errors();
            return $this->sendError($passchange,'Validation error');
        } else{
            if(!Hash::check($request->old_password, auth()->user()->password)){
                $passchange['code'] = 200;
                return $this->sendError($passchange,"Old Password Doesn't match!");
            }else{
                $passowrdchanged= User::whereId(auth()->user()->id)->update([
                    'password' => Hash::make($request->new_password)
                ]);
                $passowrd_changed['code'] = 200;
                $passowrd_changed['passowrdchanged'] = $passowrdchanged;
                return $this->sendResponse($passowrd_changed,"Password changed successfully!");
            }
        }
    }
    //medical profile
    public function medical_profile(){
        $user_id = auth()->user()->id;
        $med_prof_exists = MedicalProfile::where('user_id', $user_id)->get()->count();
        // dd($user_id);
        $profile ="";
        if($med_prof_exists >= 1){
            $profile = DB::table('medical_profiles')->where('user_id', $user_id)->orderByDesc('id')->first();
            $profile->family_history = json_decode($profile->family_history);
            $profile->medication = json_decode($profile->medication);
            $profile->immunization_history = json_decode($profile->immunization_history);
            $profile->updated_at = User::convert_utc_to_user_timezone($user_id, $profile->updated_at)['time'];
            $profile->previous_symp = explode(",",$profile->previous_symp);
            array_pop($profile->previous_symp);
            // $profile->user_medical_history = Auth::user()->med_record_file;
            $profile_data['code'] = 200;
            $profile_data['profile'] = $profile;
            $profile_data['user_medical_history'] = Auth::user()->med_record_file;
            return $this->sendResponse($profile_data,"medical Profile");
        } elseif($med_prof_exists == 0){
            $profile_data['code'] = 200;
            $profile_data['profile'] = '';
            $profile_data['user_medical_history'] = Auth::user()->med_record_file;
            return $this->sendError($profile_data,"medical Profile");
        } else{
            $profile_data['code'] = 200;
            return $this->sendError($profile_data,"No Medical Profile Found");
        }
    }
    public function isabel_symptoms(){
        $is_disease = DB::table('isabel_symptoms')->select('symptom_name')->get();
        $data['code'] = 200;
        $data['is_disease'] = $is_disease;
        return $this->sendResponse($data,"symptoms");
    }
    public function immunization(){
        $immunization = array("pneumovax", "h1n1", "annual_flu", "hepatitis_b", "tetanus", "others");
        $data['code'] = 200;
        $data['immunization'] = $immunization;
        return $this->sendResponse($data,"symptoms");
    }
    public function diseases(){
        $disease = array("cancer", "hypertension", "heart-disease", "diabetes", "stroke", "mental", "drugs", "glaucoma", "bleeding", "others");
        $data['code'] = 200;
        $data['disease'] = $disease;
        return $this->sendResponse($data,"symptoms");
    }
    public function family_list(){
        $family['code'] = 200;
        $family['family_list'] = ['Father','Mother','Sister','Brother','Daugther','Son','Grand Father','Grand Mother'];
        return $this->sendResponse($family,'Family List');
    }
    public function allergies(Request $request){
       $user = Auth::user()->id;
       $user_allergies=  MedicalProfile::where('user_id', $user)->first();
       $allergies = $request->allergies;
       if($user_allergies ==null){
            MedicalProfile::create([
                'user_id' => $user,
                'allergies' => $allergies,
                'previous_symp' => '',
                'immunization_history' => '[]',
                'family_history' => '[]',
                'medication' => '[]',
                'surgeries' => '',
                'comment' => '',
            ]);
            $code['code'] = 200;
            return $this->sendResponse($code,"allergies Created");
       } else{
            $user_allergies->update([
                'user_id' => $user,
                'allergies' =>  $allergies
            ]);
            $code['code'] = 200;
            return $this->sendResponse($code,"allergies updated");
       }
    }
    public function medical_condition(Request $request){
        $user = Auth::user()->id;
        $user_medicalCondition=  MedicalProfile::where('user_id', $user)->first();
        if($user_medicalCondition ==null){
            $sympt = "";
            if ($request->symptoms != null) {
                $av =$request->symptoms;
                foreach($av as $symp) {
                        $sympt .= $symp . ",";
                }
                MedicalProfile::create([
                    'user_id' => $user,
                    'allergies' => '',
                    'previous_symp' => $sympt,
                    'immunization_history' => '[]',
                    'family_history' => '[]',
                    'medication' => '[]',
                    'surgeries' => '',
                    'comment' => '',
                ]);
                $code['code'] = 200;
                return $this->sendResponse($code,"medical condition Created");
            } else{
                $code =200;
                return $this->sendError($code,"Please select symptoms");
            }
        } else{
            $sympt = "";
            if ($request->symptoms != null) {
                $av =$request->symptoms;
                foreach($av as $symp) {
                        $sympt .= $symp . ",";
                }
                $user_medicalCondition->update([
                    'user_id' => $user,
                    'previous_symp' =>$sympt
                ]);
                $code['code'] = 200;
                return $this->sendResponse($code,"medical condition update");
            }else{
                $code =200;
                return $this->sendError($code,"Please select symptoms");
            }
        }
    }
    public function family_history(Request $request){
        $user = Auth::user()->id;
        $user_family=  MedicalProfile::where('user_id', $user)->first();
        if($user_family ==null){
            $family_history = [];
            $family =$request->family;
            $disease = $request->disease;
            $age = $request->age;
            $new= ['family' => $family, 'disease' => $disease, 'age' => $age];
            array_push($family_history,$new);
            if($family != ''){
                $family_history = json_encode($family_history);
                MedicalProfile::create([
                    'user_id' => $user,
                    'allergies' => '',
                    'previous_symp' => '',
                    'immunization_history' => '[]',
                    'family_history' => $family_history,
                    'medication' => '[]',
                    'surgeries' => '',
                    'comment' => '',
                ]);
                $code = 200;
                return $this->sendResponse($code,"Family history created");
            }
        } else{
            $family_history = [];
            $family =$request->family;
            $disease = $request->disease;
            $age = $request->age;
            $new= ['family' => $family, 'disease' => $disease, 'age' => $age];
            $old_familyHistory = $user_family->family_history;
            $decodeFamilyHistory = json_decode($old_familyHistory);
            if($decodeFamilyHistory != '[]') {
                foreach($decodeFamilyHistory as $key => $history){
                    array_push($family_history,$history);
                }
                array_push($family_history,$new);
                $family_history = json_encode($family_history);
                $user_family->update([
                    'user_id' => $user,
                    'family_history' => $family_history,
                ]);
            } else{
                $family_history = json_encode($family_history);
                $user_family->update([
                'user_id' => $user,
                'family_history' => $family_history,
                ]);
            }

            $code = 200;
            return $this->sendResponse($code,"Family history updated");
        }
    }
    public function immunization_history(Request $request){
        $user = Auth::user()->id;
        $user_immunization=  MedicalProfile::where('user_id', $user)->first();
        $immunization_history = [];
        $immun_name =$request->immun_name;
        $when =$request->immun_when;
        $newImun = ['name' => $immun_name , 'when' =>$when,'flag' => 'yes'];
        array_push($immunization_history,$newImun);
        if($user_immunization ==null){
            if($immun_name != ''){
                $immunization_history = json_encode($immunization_history);
                MedicalProfile::create([
                'user_id' => $user,
                'allergies' => '',
                'previous_symp' => '',
                'immunization_history' => $immunization_history,
                'family_history' => '[]',
                'medication' => '[]',
                'surgeries' => '',
                'comment' => '',
                ]);
                $code = 200;
                return $this->sendResponse($code,"Immunization history created");
            }
        } else{
            $oldImun= $user_immunization->immunization_history;
            $immunization_history = [];
            $immun_name =$request->immun_name;
            $when =$request->immun_when;
            if($oldImun != '[]') {
                    foreach(json_decode($oldImun) as $userImun){
                        array_push($immunization_history,$userImun);
                    }
                    array_push($immunization_history,$newImun);
                    $immunization_history = json_encode($immunization_history);
                    $user_immunization->update([
                        'user_id' => $user,
                        'immunization_history' => $immunization_history,
                        'flag'=> "yes"
                    ]);
            } else{
                array_push($immunization_history,$newImun);
                $immunization_history = json_encode($immunization_history);
                    $user_immunization->update([
                    'user_id' => $user,
                    'immunization_history' => $immunization_history,
                    'flag'=> "yes"
                    ]);

            }
            $code = 200;
            return $this->sendResponse($code,"Immunization history updated");
        }
    }
    public function medication_history(Request $request){
        $user = Auth::user()->id;
        $user_medication=  MedicalProfile::where('user_id', $user)->first();
        $medication_history = [];
        $med_name = $request->med_name;
        $med_dosage = $request->med_dosage;
        $newMedication =['med_name' => $med_name , 'med_dosage' => $med_dosage];
        array_push($medication_history,$newMedication);
        if($user_medication ==null){
            if ($request['med_name'] != null) {
                $medication_history = json_encode($medication_history);
                MedicalProfile::create([
                    'user_id' => $user,
                    'allergies' => '',
                    'previous_symp' => '',
                    'immunization_history' =>'[]',
                    'family_history' => '[]',
                    'medication' => $medication_history,
                    'surgeries' => '',
                    'comment' => '',
                ]);
                $code = 200;
                return $this->sendResponse($code,"Medication history created");
            }
        } else{
            $oldMedication =$user_medication->medication;
            $medication_history = [];
            $med_name =$request->med_name;
            $dosage =$request->med_dosage;
            if($oldMedication != '[]'){
                foreach(json_decode($oldMedication) as $medication){
                    array_push($medication_history,$medication);
                }
                    array_push($medication_history,$newMedication);
                    $user_medication->update([
                        'user_id' => $user,
                        'medication' => $medication_history,
                    ]);
            } else{
                array_push($medication_history,$newMedication);
                $medication_history = json_encode($medication_history);
                $user_medication->update([
                    'user_id' => $user,
                    'medication' => $medication_history,
                ]);
            }
            $code = 200;
            return $this->sendResponse($code,"Medication history updated");
        }
    }
    public function surgeries(Request $request){
        $user = Auth::user()->id;
        $user_surgeries=  MedicalProfile::where('user_id', $user)->first();
        $surgeries = $request->surgeries;
        if($user_surgeries ==null){
             MedicalProfile::create([
                 'user_id' => $user,
                 'allergies' => null,
                 'previous_symp' => '',
                 'immunization_history' => '[]',
                 'family_history' => '[]',
                 'medication' => '[]',
                 'surgeries' => $surgeries,
                 'comment' => '',
             ]);
             $code['code'] = 200;
             return $this->sendResponse($code,"Surgeries Created");
        } else{
             $user_surgeries->update([
                 'user_id' => $user,
                 'surgeries' => $surgeries
             ]);
             $code['code'] = 200;
             return $this->sendResponse($code,"Surgeries updated");
        }
    }
    public function user_comments(Request $request){
        $user = Auth::user()->id;
        $user_comments=  MedicalProfile::where('user_id', $user)->first();
        $comments = $request->comments;
        if($user_comments ==null){
             MedicalProfile::create([
                 'user_id' => $user,
                 'allergies' => null,
                 'previous_symp' => '',
                 'immunization_history' => '[]',
                 'family_history' => '[]',
                 'medication' => '[]',
                 'surgeries' => '',
                 'comment' => $comments,
             ]);
             $code['code'] = 200;
             return $this->sendResponse($code,"Comments Created");
        } else{
             $user_comments->update([
                 'user_id' => $user,
                 'comment' => $comments,
             ]);
             $code['code'] = 200;
             return $this->sendResponse($code,"Comments updated");
        }
    }
    public function medical_record_file(Request $request){
        $user = Auth::user()->id;
        $filename =null;
        if (request()->hasFile('upload_file')) {
            $image = request()->file('upload_file');
            $filename = \Storage::disk('s3')->put('medical_records', $image);
           User::where('id', $user)->update([
                'med_record_file' => $filename,
            ]);
        }
        $code['code'] =200;
        return $this->sendResponse($code,"medical file uploaded");
    }
    public function patient_activity(){
        $data = Auth::user();
        $id = $data->id;
        $perPage = 20;
        $activities = DB::table('activity_log')
            ->select('*', DB::raw("CASE `type` WHEN 'login' THEN 'Last Logged In' WHEN 'logout' THEN 'Last Logged Out' WHEN 'prescription added' THEN 'Prescription Status' WHEN 'session recommendations' THEN 'Session Recommendation Status' WHEN 'session start' THEN 'Session Status' WHEN 'session end' THEN 'Session Ended Status' WHEN 'order' THEN 'Order Status' END AS `heading`"),
            DB::raw("CASE `type` WHEN 'login' THEN 'badge-success' WHEN 'logout' THEN 'badge-warning' WHEN 'prescription added' THEN 'badge-info' WHEN 'session recommendations' THEN 'badge-info' WHEN 'session start' THEN 'badge-success' WHEN 'session end' THEN 'badge-warning' WHEN 'order' THEN 'badge-info' END AS `color`"))
            ->where('user_id', $id)
            ->whereNotIn('type', ['record', 'product_del_request', 'product_created', 'product_category_created', 'product_sub_category_created'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
            $patient['code'] = 200;
        $patient['patient_activity'] =$activities;
        return $this->sendResponse($patient,'Patient Login Activities');
    }
}
