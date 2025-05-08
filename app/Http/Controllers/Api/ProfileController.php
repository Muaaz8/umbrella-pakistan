<?php

namespace App\Http\Controllers\Api;

use App\City;
use App\Country;
use App\Http\Controllers\Controller;
use App\Specialization;
use App\State;
use App\User;
use App\Rules\Phone;
use App\Notification;
use App\Events\RealTimeMessage;
use Auth;
use DateTime;
use DateTimeZone;
use DB;
use Flash;
use Illuminate\Http\Request;

class ProfileController extends BaseController
{
    public function view_profile($username)
    {
        if ($username != null) {
            $id = auth()->user()->id;
            $user = DB::table('users')->where('id', $id)->select(
                "id",
                "user_type",
                "username",
                "name",
                "last_name",
                "email",
                "temp_password",
                "date_of_birth",
                "phone_number",
                "office_address",
                "zip_code",
                "nip_number",
                "upin",
                "specialization",
                "id_card_front",
                "id_card_back",
                "terms_and_cond",
                "signature",
                "remember_token",
                "created_at",
                "updated_at",
                "status",
                "time_from",
                "time_to",
                "user_image",
                "bio",
                "active",
                "created_by",
                "country_id",
                "city_id",
                "state_id",
                "med_record_file",
                "provider",
                "provider_id",
                "rating",
                "gender",
            )->first();
            if (isset($user->id)) {
                if ($id == $user->id) {
                    if($user->user_type=='doctor'){
                        $doctor=$user;

                        $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);
                        $doctor->pending_approval = DB::table('doctor_fee_approvals')->where('doctor_id',$id)->orderBy("id","desc")->first();
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

                        if($doctor->pending_approval != null){
                                $doctor->pending_approval;
                        }else{
                                $doctor->pending_approval = null;
                        }

                        foreach ($doctor->certificates as $cert) {
                        if ($cert->certificate_file != "") {
                            $cert->certificate_file = \App\Helper::get_files_url($cert->certificate_file);
                        }
                        }

                        $doctor->activity = DB::table('activity_log')->where('user_id',$id)->select('*')->orderBy('created_at','desc')->get();
                        foreach ($doctor->activity as $dt) {
                            $user_time_zone = Auth::user()->timeZone;
                            $date = new DateTime($dt->created_at);
                            $date->setTimezone(new DateTimeZone($user_time_zone));
                            $dt->created_at = $date->format('D, M/d/Y, g:i a');
                        }

                        $session_patients = DB::table('sessions')
                            ->join('users', 'sessions.patient_id', '=', 'users.id')
                            ->where('sessions.doctor_id', auth()->user()->id)
                            ->where('sessions.status', '!=', 'pending')
                            ->groupBy('sessions.patient_id')
                            ->select('users.*')
                            ->get();

                        $inclinic_patients = DB::table('in_clinics')
                            ->join('users', 'in_clinics.user_id', 'users.id')
                            ->where('in_clinics.doctor_id', $user->id)
                            ->where('in_clinics.status', 'ended')
                            ->orderBy('in_clinics.created_at', 'DESC')
                            ->groupBy('in_clinics.user_id')
                            ->select('users.*')
                            ->get();

                        return $this->sendResponse(['user' => $doctor], 'Doctor Profile retrieved successfully.');
                    } else if ($user->user_type == 'patient') {
                        $patient = $user;
                        if ($patient->city_id == null) {
                            $patient->city = 'None';
                        } else {
                            $patient->city = $patient->city_id;
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
                            $patient->country = "Pakistan";
                        }

                        $patient->user_image = \App\Helper::check_bucket_files_url($patient->user_image);
                        $userobj = new User();

                        $data = DB::table('activity_log')->where('user_id', $id)->select('*')->orderBy('created_at', 'desc')->paginate(10);

                        foreach ($data as $dt) {
                            $user_time_zone = Auth::user()->timeZone;
                            $date = new DateTime($dt->created_at);
                            $date->setTimezone(new DateTimeZone($user_time_zone));
                            $dt->created_at = $date->format('D, M/d/Y, g:i a');
                        }

                        return $this->sendResponse(['user' => $patient], 'Patient Profile retrieved successfully.');
                    } else {
                        return $this->sendError([], 'User not found.');
                    }
                } else {
                    return $this->sendError([], 'User not found.');
                }
            } else {
                return $this->sendError([], 'User not found.');
            }

        } else {
            return $this->sendError([], 'User not found.');
        }

    }
    function editprofilenumber(Request $request)
    {
        $id = auth()->user()->id;
        $validateData = $request->validate([
            'phoneNumber' => ['required', 'min:10', 'max:11', 'gt:0', new Phone],
        ]);
        $user = DB::table('users')->where('id', $id)->update([
            'phone_number' => $validateData['phoneNumber'],
        ]);

        return $this->sendResponse(['user' => $user], 'Phone number updated successfully.');
    }
    public function updatePatient(Request $request)
    {
        $input = $request->all();
        $datecheck = $input['dob'];
        $date = str_replace('-', '/', $datecheck);
        $newd_o_b = date("Y-m-d", strtotime($date));
        $admin = DB::table('users')->where('user_type', '=', 'admin')->first();
        if (str_contains($datecheck, "/")) {
            $newd_o_b;
        } else {
            $datecheck;
        }
        $time = time();
        $current_date = date('Y-m-d', $time);
        $min_date = $date = strtotime($current_date . ' -1 day');
        $min_age_date = date('Y-m-d', $min_date);
        if ($datecheck > $min_age_date) {
            Flash::error('Date of birth cannot be future date.');
            return back();
        }

        $validateData = $request->validate([
            'fname' => ['required', 'string'],
            'lname' => ['required', 'string'],
            'dob' => ['required'],
            'address' => 'required',
            'email' => ['required'],
            'reason' => ['required'],
        ]);

        $id = auth()->user()->id;
        $patient = User::find($id);

        $query = DB::table('users')->where('id', $input['user_id'])->first();

        $old = array(
            'name' => $query->name,
            'last_name' => $query->last_name,
            'email' => $query->email,
            'date_of_birth' => $query->date_of_birth,
            'office_address' => $query->office_address,
        );

        $new = array(
            'name' => $validateData['fname'],
            'last_name' => $validateData['lname'],
            'email' => $validateData['email'],
            'date_of_birth' => $newd_o_b,
            'office_address' => $validateData['address'],
        );

        $differenceArray1 = array_diff($old, $new);
        if ($differenceArray1) {
            DB::table('users')->where('id', $id)->update([
                'name' => $validateData['fname'],
                'last_name' => $validateData['lname'],
                'date_of_birth' => $newd_o_b,
                'office_address' => $validateData['address'],
                'email' => $validateData['email'],
            ]);
            return $this->sendResponse(['user' => $patient], 'Profile updated successfully.');
        } else {
            return $this->sendError([], 'No changes made.');
        }

    }

    function editprofilepicture(Request $request)
    {
        $user = auth()->user();
        $image = $request->file('filename');
        $filename = \Storage::disk('s3')->put('user_profile_images', $image);
        DB::table('users')->where('id', $user->id)->update([
            'user_image' => $filename,
        ]);
        if ($user->user_type == 'patient') {
            return $this->sendResponse(['user' => $user], 'Profile picture updated successfully.');
        } else if ($user->user_type == 'doctor') {
            return $this->sendResponse(['user' => $user], 'Profile picture updated successfully.');
        }
    }

    public function updateFees(Request $request)
    {
        $id = auth()->user()->id;
        $admin = User::role('admin')->first();
        $validateData = $request->validate([
            'consultation_fee' => ['nullable', 'gt:299', 'lt:21000'],
            'followup_fee' => ['nullable', 'gt:299', 'lt:21000'],
        ]);

        $doctor = DB::table('doctor_fee_approvals')->where('doctor_id', $id )->where('is_approved' , 'pending')->orderBy('id' , 'desc')->first();
        if ($doctor) {
            DB::table('doctor_fee_approvals')->where('doctor_id', $id)->update([
                'consultation_fee' => $validateData['consultation_fee'],
                'followup_fee' => $validateData['followup_fee'],
            ]);
        } else {
            DB::table('doctor_fee_approvals')->insert([
                'doctor_id' => $id,
                'consultation_fee' => $validateData['consultation_fee'],
                'followup_fee' => $validateData['followup_fee'],
            ]);
        }

        Notification::create([
            'user_id' =>  $admin->id,
            'type' => "/admin/fee-approval",
            'text' => 'New Fee Approval Request',
        ]);
        event(new RealTimeMessage($admin->id));

        return $this->sendResponse(['fee_approval' => $doctor], 'Fees updated successfully.');
    }

}
