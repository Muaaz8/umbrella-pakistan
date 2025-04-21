<?php

namespace App\Http\Controllers\Api;

use App\ActivityLog;
use App\Helper;
use App\Http\Controllers\Controller;
use App\Repositories\TblOrdersRepository;
use App\MedicalProfile;
use App\User;
use Auth;
use DB;
use Illuminate\Http\Request;

class PatientsController extends BaseController
{

    private $tblOrdersRepository;

    public function __construct(TblOrdersRepository $tblOrdersRepo)
    {

        $this->tblOrdersRepository = $tblOrdersRepo;
    }

    public function get_patient_dasboard_info()
    {
        $user = Auth::user();
        $doctors = DB::table('sessions')
            ->where('patient_id', $user->id)
            ->where('status', '!=', 'pending')
            ->groupBy('doctor_id')
            ->select('*')
            ->get();

        $orders = DB::table('tbl_orders')
            ->where('customer_id', $user->id)
            ->get();

        $reports = DB::table('quest_results')
            ->where('pat_id', $user->id)
            ->get();

        $unread_reports = DB::table('quest_results')
            ->where('pat_id', $user->id)
            ->where('is_read', null)
            ->count();

        $pending_appoints = DB::table('appointments')
            ->join('sessions', 'appointments.id', 'sessions.appointment_id')
            ->where('appointments.patient_id', $user->id)
            ->where('appointments.status', 'pending')
            ->where('sessions.status', '!=', 'pending')
            ->count();

        $pending_sessions = DB::table('sessions')
            ->where('patient_id', $user->id)
            ->where('appointment_id', null)
            ->where('status', '!=', 'ended')
            ->where('reminder', null)
            ->orderby('id', 'desc')
            ->first();

        $med_profile = DB::table('medical_profiles')
            ->where('user_id', $user->id)
            ->count();

        $chat = DB::table('chat')->where('token_status', 'unsolved')->where('user_id', $user->id)->get();

        if ($pending_sessions != null) {
            $total_reminds = $unread_reports + $pending_appoints + 1;
        } else {
            $total_reminds = $unread_reports + $pending_appoints;
        }
        $session = DB::table('sessions')->where('patient_id', Auth::user()->id)->where('status', 'ended')->where('remaining_time', '!=', 'full')->orderby('id', 'desc')->first();
        if ($session == null) {
            $ses_id = 0;
            $ses_feed = 1;
        } else {
            $ses_id = $session->id;
            $ses_feed = $session->feedback_flag;
        }

        return $this->sendResponse([
            'doctors' => $doctors,
            'chat' => $chat,
            'orders' => $orders,
            'reports' => $reports,
            'unread_reports' => $unread_reports,
            'pending_appoints' => $pending_appoints,
            'pending_sessions' => $pending_sessions,
            'total_reminds' => $total_reminds,
            'med_profile' => $med_profile,
            'ses_id' => $ses_id,
            'ses_feed' => $ses_feed
        ], 'Patient Dashboard Info');
    }

    public function store_medical_history_new(Request $request)
    {
        $symptoms = "";
        if ($request['symp'] != null) {
            foreach ($request['symp'] as $symp) {
                $symptoms .= $symp . ",";
            }
        }
        $immunization_history = array();
        $immunization = array("pneumovax", "h1n1", "annual_flu", "hepatitis_b", "tetanus", "others");
        if ($request['immun_name'] != null) {
            for ($i = 0; $i < count($request->immun_when); $i++) {
                $temp['name'] = $request->immun_name[$i];
                $temp['when'] = $request->immun_when[$i];
                $temp['flag'] = 'yes';
                array_push($immunization_history, $temp);
            }
        }
        $immunization_history = json_encode($immunization_history);

        $family_history = array();
        $diseases = array("cancer", "hypertension", "heart-disease", "diabetes", "stroke", "mental", "drugs", "glaucoma", "bleeding", "others");
        if ($request['family'] != null) {
            for ($i = 0; $i < count($request->family); $i++) {
                $fam_arr['family'] = $request->family[$i];
                $fam_arr['disease'] = $request->disease[$i];
                $fam_arr['age'] = $request->age[$i];
                array_push($family_history, $fam_arr);
            }
        }
        $family_history = json_encode($family_history);


        $medication_history = array();
        if ($request['med_name'] != null) {
            for ($i = 0; $i < count($request->med_name); $i++) {
                $med_arr['med_name'] = $request->med_name[$i];
                $med_arr['med_dosage'] = $request->med_dosage[$i];
                array_push($medication_history, $med_arr);
            }
        }
        $medication_history = json_encode($medication_history);
        $user = auth()->user()->id;
        $med = MedicalProfile::where('user_id', $user)->get()->count();
        if ($med > 0) {
            $update = true;
            $pro = MedicalProfile::where('user_id', $user)->orderByDesc('id')
                ->first()->update([
                        'allergies' => $request['allergies'],
                        'previous_symp' => $symptoms,
                        'immunization_history' => $immunization_history,
                        'family_history' => $family_history,
                        'surgeries' => $request['surgeries'],
                        'comment' => $request['comm'],
                        'medication' => $medication_history,
                    ]);
        } else {
            $update = false;
            $pro = MedicalProfile::create([
                'user_id' => $user,
                'allergies' => $request['allergies'],
                'previous_symp' => $symptoms,
                'immunization_history' => $immunization_history,
                'family_history' => $family_history,
                'surgeries' => $request['surgeries'],
                'comment' => $request['comm'],
                'medication' => $medication_history,
            ]);
        }
        if ($request->hasFile('certificate')) {
            $images = $request->file('certificate');
            foreach ($images as $image) {
                $filename = \Storage::disk('s3')->put('medical_records', $image);
                DB::table('medical_records')->insert([
                    'user_id' => $user,
                    'record_file' => $filename,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
        if ($update) {
            return $this->sendResponse($pro, 'Successfully Updated Medical History Record');
        } else {
            return $this->sendResponse($pro, 'Successfully Created Medical History Record');
        }
    }
    public function pat_medical_profile()
    {
        $user_id = auth()->user()->id;
        $med_prof_exists = MedicalProfile::where('user_id', $user_id)->get()->count();
        $update = false;
        $med_files = DB::table('medical_records')->where('user_id',$user_id)->get();

        if ($med_prof_exists >= 1) {
            $update = true;
            $profile = DB::table('medical_profiles')->where('user_id', $user_id)->orderByDesc('id')->first();
            $profile->family_history = json_decode($profile->family_history);
            $profile->medication = json_decode($profile->medication);
            $profile->immunization_history = json_decode($profile->immunization_history);
            $profile->updated_at = User::convert_utc_to_user_timezone($user_id, $profile->updated_at)['datetime'];
            $profile->previous_symp = explode(",",$profile->previous_symp);
            array_pop($profile->previous_symp);
            $diseases = array("cancer", "hypertension", "heart-disease", "diabetes", "stroke", "mental", "drugs", "glaucoma", "bleeding", "others");

            return $this->sendResponse(['profile' => $profile, 'update' => $update, 'diseases' => $diseases, 'med_files' => $med_files], 'Medical Profile');
        }

        return $this->sendResponse(['profile' => null, 'update' => $update, 'diseases' => [], 'med_files' => $med_files], 'Medical Profile');
    }

}
