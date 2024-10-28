<?php

namespace App;

use App\User;
use App\Pharmacy;
use App\Notification;
use App\VideoLinks;
use App\Session;
use App\ActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use App\Models\AllProducts;
use Carbon\Carbon;
use App\Prescription;
use App\Cart;
use App\DoctorSchedule;
use App\Events\RealTimeMessage;

class RestApis extends Model
{
    protected $tbl_Categories = 'product_categories';
    protected $tbl_SubCategories = 'products_sub_categories';
    protected $tbl_Products = 'tbl_products';
    protected $tbl_cart = 'tbl_cart';
    protected $tbl_maps = 'tbl_map_markers';
    protected $tbl_prescriptions = 'prescriptions';
    protected $tbl_faq = 'tbl_faq';
    protected $tbl_users = 'users';
    protected $tbl_orders = 'tbl_orders';
    protected $medical_profiles = 'medical_profiles';
    protected $tbl_sessions = 'sessions';
    protected $tbl_symptoms = 'symptoms';
    protected $tbl_appointments = 'appointments';
    protected $tbl_specializations = 'specializations';
    protected $tbl_doctor_schedules = 'doctor_schedules';
    protected $tbl_video_links = 'video_links';


    public function checkUserExist($user_email, $username)
    {
        $data = User::where('email', $user_email)
            ->orWhere('username', $username)
            ->first();

        if (!empty($data)) {
            return 1;
        } else {
            return 0;
        }
    }

    public function createNewUser($request)
    {
        $request['password'] = Hash::make($request['password']);
        $create_user = User::Create($request);
        return $create_user;
    }

    public function getProductParentCategories($arr = "")
    {
        // Set Parameters of Products
        $whereData = $arr;
        $query = DB::table($this->tbl_Categories)->select('*')
            ->where($whereData)
            ->whereNotIn('id', ['30'])
            ->orderBy('name', 'asc')
            ->get();
        return $query;
    }

    public function getProductSubCategoriesByID($arr)
    {
        // Set Parameters of Products
        $whereData = $arr;
        $query = DB::table($this->tbl_SubCategories)->select('*')
            ->where($whereData)
            ->orderBy('title', 'asc')
            ->get();
        return $query;
    }

    public function getProductsByParam($arr = "")
    {

        // Set Limit
        $limit = 12;
        if (isset($arr['limit'])) {
            $limit = $arr['limit'];
        }

        // Set Parameters of Products
        $whereData = $arr;
        unset($whereData['parent_category']);
        unset($whereData['sub_category']);
        unset($whereData['limit']);
        unset($whereData['test_type']);

        // Check Categories
        if (isset($arr['parent_category']) && isset($arr['sub_category'])) {
            $parent_id = $arr['parent_category'];
            $sub_id = $arr['sub_category'];
            $query =
                DB::table($this->tbl_Products)
                ->select('*')
                ->where($whereData)
                ->whereRaw("find_in_set('$parent_id',`parent_category`)")
                ->whereRaw("find_in_set('$sub_id',`sub_category`)")
                ->where('product_status', 1)
                ->where('is_approved', 1)
                ->whereNotIn('parent_category', ['30'])
                ->orderBy('id', 'desc')
                ->limit($limit)
                ->get();
        } elseif (isset($arr['parent_category'])) {
            $parent_id = $arr['parent_category'];
            $query =
                DB::table($this->tbl_Products)
                ->select('*')
                ->where($whereData)
                ->whereRaw("find_in_set('$parent_id',`parent_category`)")
                ->where('product_status', 1)
                ->where('is_approved', 1)
                ->whereNotIn('parent_category', ['30'])
                ->orderBy('id', 'desc')
                ->limit($limit)
                ->get();
        } elseif (isset($arr['sub_category'])) {
            $sub_id = $arr['sub_category'];
            $query =
                DB::table($this->tbl_Products)
                ->select('*')
                ->where($whereData)
                ->whereRaw("find_in_set('$sub_id',`sub_category`)")
                ->where('product_status', 1)
                ->where('is_approved', 1)
                ->whereNotIn('parent_category', ['30'])
                ->orderBy('id', 'desc')
                ->limit($limit)
                ->get();
        } elseif (isset($arr['test_type'])) {
            $test_type = $arr['test_type'];

            if ($test_type === 'panel') {
                $query =
                    DB::table($this->tbl_Products)
                    ->select('*')
                    ->where($whereData)
                    ->where('panel_name', '!=', '""')
                    ->where('product_status', 1)
                    ->where('is_approved', 1)
                    ->whereNotIn('parent_category', ['30'])
                    ->orderBy('id', 'desc')
                    ->limit($limit)
                    ->get();
            } else {
                $query =
                    DB::table($this->tbl_Products)
                    ->select('*')
                    ->where($whereData)
                    ->whereRaw("panel_name IS NULL")
                    ->where('product_status', 1)
                    ->where('is_approved', 1)
                    ->whereNotIn('parent_category', ['30'])
                    ->orderBy('id', 'desc')
                    ->limit($limit)
                    ->get();
            }
        } else {
            $query =
                DB::table($this->tbl_Products)
                ->select('*')
                ->where($whereData)
                ->where('product_status', 1)
                ->where('is_approved', 1)
                ->whereNotIn('parent_category', ['30'])
                ->orderBy('id', 'desc')
                ->limit($limit)
                ->get();
        }

        return $query;
    }

    public function searchProducts($arr = "")
    {
        // Set Limit
        $limit = 12;
        if (isset($arr['limit'])) {
            $limit = $arr['limit'];
        }

        // Set Parameters of Products
        $whereData = $arr;
        $var = '%' . $whereData['keyword'] . '%';

        unset($whereData['keyword']);
        unset($whereData['limit']);

        $query = DB::table($this->tbl_Products)->select('*')
            ->where('name', 'LIKE', $var)
            ->where($whereData)
            ->where('product_status', '=', 1)
            ->where('is_approved', '=', 1)
            ->whereNotIn('mode', ['substance-abuse'])
            ->limit($limit)
            ->get();
        return $query;
    }

    public function getMedicalProfiles($arr = "")
    {
        // Set Parameters of Patient Medical Profiles
        $whereData = $arr;
        $query = DB::table($this->medical_profiles)
            ->join($this->tbl_users, $this->medical_profiles . '.user_id', '=', $this->tbl_users . '.id')
            ->select(
                $this->medical_profiles . '.id',
                $this->tbl_users . '.id as user_id',
                $this->tbl_users . '.user_type',
                $this->tbl_users . '.username',
                $this->tbl_users . '.name',
                $this->tbl_users . '.last_name',
                $this->tbl_users . '.email',
                $this->tbl_users . '.date_of_birth',
                $this->tbl_users . '.phone_number',
                DB::raw("'/asset_admin/images/medical_record/' as URL"),
                $this->tbl_users . '.med_record_file',
                $this->medical_profiles . '.allergies',
                $this->medical_profiles . '.previous_symp',
                $this->medical_profiles . '.family_history',
                $this->medical_profiles . '.comment',
                $this->medical_profiles . '.created_at',
                $this->medical_profiles . '.updated_at',

            )
            ->where($whereData)
            ->get();
        return $query;
    }

    public function updateMedicalHistory($arr = "")
    {
        $user_id = $arr['user_id'];
        $checkProfile = MedicalProfile::where('user_id', $user_id)->first();
        if (!empty($checkProfile->id)) {
            $updateMedicalProfile = MedicalProfile::where('user_id', $user_id)->update(
                [
                    'user_id' => $arr['user_id'],
                    'allergies' => $arr['allergies'],
                    'previous_symp' => $arr['previous_symp'],
                    'family_history' => json_encode($arr['family_history']),
                    'comment' => json_encode($arr['comment']),
                ]
            );
            return $updateMedicalProfile;
        } else {
            $createMedicalProfile = MedicalProfile::create([
                'user_id' => $arr['user_id'],
                'allergies' => $arr['allergies'],
                'previous_symp' => $arr['previous_symp'],
                'family_history' => json_encode($arr['family_history']),
                'comment' => json_encode($arr['comment']),
            ]);
            return $createMedicalProfile;
        }
    }

    public function getPatientSessions($arr = "")
    {
        // Set Parameters of Patient Sessions
        $get_session_data =  DB::table($this->tbl_sessions)
            ->join($this->tbl_users, $this->tbl_sessions . '.doctor_id', '=', $this->tbl_users . '.id')
            ->join($this->tbl_symptoms, $this->tbl_sessions . '.symptom_id', '=', $this->tbl_symptoms . '.id')
            ->select(
                $this->tbl_sessions . '.id as session_id',
                $this->tbl_sessions . '.start_time',
                $this->tbl_sessions . '.end_time',
                $this->tbl_sessions . '.provider_notes',
                $this->tbl_sessions . '.created_at',
                $this->tbl_sessions . '.status as session_status',
                $this->tbl_sessions . '.diagnosis',
                $this->tbl_sessions . '.symptom_id',
                $this->tbl_users . '.name as doctor_first_name',
                $this->tbl_users . '.last_name as doctor_last_name',
                $this->tbl_symptoms . '.headache as symptoms_headache',
                $this->tbl_symptoms . '.fever as symptoms_fever',
                $this->tbl_symptoms . '.flu as symptoms_flu',
                $this->tbl_symptoms . '.nausea as symptoms_nausea',
                $this->tbl_symptoms . '.others as symptoms_others',
                $this->tbl_symptoms . '.description as symptoms_description'
            )
            ->where($this->tbl_sessions . '.patient_id', $arr['patient_id'])
            ->where($this->tbl_sessions . '.status', 'ended')
            ->orderByDesc($this->tbl_sessions . '.id')
            ->get();

        return $get_session_data;
    }

    public function getPatientSessions_3($arr = "")
    {
        // Set Parameters of Patient Sessions
        $get_session_data =  DB::table($this->tbl_sessions)
            ->join($this->tbl_users, $this->tbl_sessions . '.doctor_id', '=', $this->tbl_users . '.id')
            ->join($this->tbl_symptoms, $this->tbl_sessions . '.symptom_id', '=', $this->tbl_symptoms . '.id')
            ->select(
                $this->tbl_sessions . '.id as session_id',
                $this->tbl_sessions . '.start_time',
                $this->tbl_sessions . '.end_time',
                $this->tbl_sessions . '.provider_notes',
                $this->tbl_sessions . '.created_at',
                $this->tbl_sessions . '.status as session_status',
                $this->tbl_users . '.name as doctor_first_name',
                $this->tbl_users . '.last_name as doctor_last_name',

            )
            ->where($this->tbl_sessions . '.patient_id', $arr['patient_id'])
            ->where($this->tbl_sessions . '.status', 'ended')
            ->orderByDesc($this->tbl_sessions . '.id')
            ->get();

        return $get_session_data;
    }


    public function getPatientSessions_2($session_id)
    {
        // Set Parameters of Patient Sessions
        $get_session_data =  DB::table($this->tbl_sessions)
            ->join($this->tbl_users, $this->tbl_sessions . '.doctor_id', '=', $this->tbl_users . '.id')
            ->join($this->tbl_symptoms, $this->tbl_sessions . '.symptom_id', '=', $this->tbl_symptoms . '.id')
            ->select(
                // $this->tbl_sessions . '.id as session_id',
                // $this->tbl_sessions . '.start_time',
                // $this->tbl_sessions . '.end_time',
                // $this->tbl_sessions . '.provider_notes',
                // $this->tbl_sessions . '.created_at',
                // $this->tbl_sessions . '.status as session_status',
                // $this->tbl_sessions . '.diagnosis',
                $this->tbl_sessions . '.symptom_id',
                // $this->tbl_users . '.name as doctor_first_name',
                // $this->tbl_users . '.last_name as doctor_last_name',
                $this->tbl_symptoms . '.headache as symptoms_headache',
                $this->tbl_symptoms . '.fever as symptoms_fever',
                $this->tbl_symptoms . '.flu as symptoms_flu',
                $this->tbl_symptoms . '.nausea as symptoms_nausea',
                $this->tbl_symptoms . '.others as symptoms_others',
                $this->tbl_symptoms . '.description as symptoms_description'
            )
            ->where($this->tbl_sessions . '.id', $session_id)
            //->where($this->tbl_sessions . '.status', 'ended')
            ->first();

        return $get_session_data;
    }


    public function getPrescribedMedicines($arr = "")
    {
        // Set Parameters of Products
        // $whereData = $arr;
        $query = DB::table($this->tbl_prescriptions)
            ->join($this->tbl_Products, $this->tbl_prescriptions . '.medicine_id', '=', $this->tbl_Products . '.id')
            ->select(
                $this->tbl_prescriptions . '.session_id',
                $this->tbl_prescriptions . '.medicine_id as product_id',
                $this->tbl_Products . '.name as product_name',
                $this->tbl_prescriptions . '.type as product_mode',
                $this->tbl_prescriptions . '.comment as prescription_comment',
                $this->tbl_prescriptions . '.usage',
                $this->tbl_prescriptions . '.quantity',
                DB::raw("(SELECT status FROM tbl_cart WHERE pres_id = $this->tbl_prescriptions.id) as product_status")
            )
            ->where($this->tbl_prescriptions . '.session_id', $arr['session_id'])
            ->get();
        return $query;
    }

    public function getAppointments($arr = "")
    {
        $limit = 12;
        if (isset($arr['limit'])) {
            $limit = $arr['limit'];
        }

        // Set Parameters of Products
        $whereData = $arr;
        unset($whereData['limit']);

        $query = DB::table($this->tbl_appointments)->select('*')
            ->where($whereData)
            ->limit($limit)
            ->get();
        return $query;
    }

    public function createAppointment($data)
    {
        $create_app = DB::table($this->tbl_appointments)->insert([
            'patient_id' => $data->patient_id,
            'doctor_id' => $data->doctor_id,
            'patient_name' => $data->patient_name,
            'doctor_name' => $data->doctor_name,
            'email' => $data->email,
            'phone' => $data->phone,
            'date' => $data->date,
            'time' => $data->time,
            'problem' => $data->problem,
            'status' => $data->status,
            'day' => $data->day,
            'created_at' => NOW(),
        ]);
        return $create_app;
    }

    public function getUserProfile($arr = "")
    {
        // Set Parameters of Products
        $whereData = $arr;
        $query = DB::table($this->tbl_users)->select('*', DB::raw("'/asset_admin/images/user_profile/' AS user_profile_link"))
            ->where($whereData)
            ->get();
        return $query;
    }

    public function updateUserProfile($data, $id)
    {
        $whereData = $data;
        unset($whereData['password']);
        unset($whereData['username']);

        if (isset($data['password'])) {
            $user = User::where('id', $id)
                ->update(['password' => Hash::make($data['password'])]);
            return 3;
        } elseif (isset($data['username'])) {
            $check_username = DB::table($this->tbl_users)->select('username')
                ->where(['username' => $data['username']])
                ->get();
            if (count($check_username) > 0) {
                return 1;
            } else {
                DB::table($this->tbl_users)
                    ->where(['id' => $id])
                    ->update($data);
                return 2;
            }
        } else {
            DB::table($this->tbl_users)
                ->where(['id' => $id])
                ->update($data);
            return 4;
        }
    }

    public function getAppointmentDoctors($arr = "")
    {
        $patient_id = $arr['patient_id'];
        $query = DB::select("SELECT users.`id` AS doctor_id, CONCAT(users.`name`,' ',users.`last_name`) AS doctor_name, specializations.`name` AS specialization, '0' AS refered_doctor FROM `sessions` LEFT JOIN users ON sessions.doctor_id=users.id LEFT JOIN `specializations` ON users.`specialization`=specializations.id WHERE `sessions`.`patient_id` = '$patient_id' GROUP BY sessions.`doctor_id` UNION ALL SELECT users.`id` AS doctor_id, CONCAT(users.`name`,' ',users.`last_name`) AS doctor_name, specializations.`name` AS specialization, '1' AS refered_doctor FROM `referals` LEFT JOIN users ON referals.sp_doctor_id=users.id LEFT JOIN `specializations` ON users.`specialization`=specializations.id WHERE referals.patient_id = '$patient_id' GROUP BY referals.`sp_doctor_id`");
        return $query;
    }

    public function getAppointmentDoctorsAvailability($arr = "")
    {

        $doctor_id = $arr['doctor_id'];
        $date = $arr['date'];

        $query = DB::table($this->tbl_doctor_schedules)
            ->where(['doctorID' => $doctor_id, 'date' => $date])
            ->get();

        return $query;
    }

    public function getPatientPreviousDoctors($arr = "")
    {
        $patient_id = $arr['patient_id'];
        $query = DB::select("SELECT users.*, specializations.`name` AS specialization, '0' AS refered_doctor FROM `sessions` LEFT JOIN users ON sessions.doctor_id=users.id LEFT JOIN `specializations` ON users.`specialization`=specializations.id WHERE `sessions`.`patient_id` = '$patient_id' GROUP BY sessions.`doctor_id` UNION ALL SELECT users.*, specializations.`name` AS specialization, '1' AS refered_doctor FROM `referals` LEFT JOIN users ON referals.sp_doctor_id=users.id LEFT JOIN `specializations` ON users.`specialization`=specializations.id WHERE referals.patient_id = '$patient_id' GROUP BY referals.`sp_doctor_id`");
        return $query;
    }

    public function getOnlineDoctors($arr = "")
    {
        $query = User::where(['active' => 1, 'status' => 'online', 'user_type' => 'doctor'])
            ->orderBy('name')
            ->get();
        return $query;
    }

    public function createSymptomsForSession($arr = "")
    {
        $arr['created_at'] = NOW();
        $symp = Symptom::create($arr);
        return $symp;
    }

    public function createPaymentForSession($arr = "")
    {
        $date = Carbon::today();
        $data = $arr;

        $data['date'] = $date;
        $data['remaining_time'] = 'full';
        $data['status'] = 'paid';
        $data['queue'] = 0;

        $session_id = Session::create($data)->id;
        return $session_id;
    }

    public function createSentInvitation($arr = "")
    {
        $video_create = $this->createVideoLink($arr);
        // $create_room = $this->createRoom($arr);
        $update_session_sequence = $this->updateSessionsSequence($arr);
        $create_notification = $this->createNotificationForSession($arr);

        return 1;
    }

    public function createVideoLink($arr)
    {

        $create = VideoLinks::create([
            'session_id' => $arr['session_id'],
            'doctor_link' => $arr['doctor_link'],
            'patient_link' => $arr['patient_link'],
            'room_id' => $arr['room_id'],
        ]);

        return $create;
    }

    public function createRoom($arr)
    {
        $date = Carbon::today();
        $create = LsvRoom::create([
            'agent' => $arr['patient_id'],
            'visitor' => $arr['doctor_id'],
            'agenturl' => $arr['patient_link'],
            'visitorurl' => $arr['doctor_link'],
            'password' => 'd41d8cd98f00b204e9800998ecf8427e',
            'roomId' => $arr['room_id'],
            'datetime' => $date,
            'duration' => '6',
            'shortagenturl' => $arr['short_agent_url'],
            'shortvisitorurl' => $arr['short_visitor_url'],
            'is_active' => '1',
            'session_id' => $arr['session_id']
        ])->id;

        return $create;
    }

    public function updateSessionsSequence($arr)
    {
        $last_invite = Session::where('doctor_id', $arr['doctor_id'])->max('sequence');
        $sequence = $last_invite + 1;
        $session = Session::find($arr['session_id'])->update(['status' => 'invitation sent', 'queue' => 1, 'sequence' => $sequence]);
        return $session;
    }

    public function createNotificationForSession($arr)
    {
        $patient = User::find($arr['patient_id']);
        $patient_name = $patient->name . " " . $patient->last_name;

        $create = Notification::create([
            'user_id' => $arr['doctor_id'],
            'text' => 'Start session with ' . $patient_name,
            'session_id' => $arr['session_id'],
            'type' => 'session_created',
        ]);
        $data = [
            'user_id' => $arr['doctor_id'],
            'text' => 'Start session with ' . $patient_name,
            'session_id' => $arr['session_id'],
            'type' => 'session_created',
            'refill_id' => "null",
            'appoint_id' => 'null',
            'received' => 'false',
        ];
        try {
            //code...
            \App\Helper::firebase($arr['doctor_id'],'notification',$create->id,$data);
        } catch (\Throwable $th) {
            //throw $th;
        }
        event(new RealTimeMessage($patient->id));
        return $create;
    }

    public function createActivityLog($arr = "")
    {

        $patient = User::find($arr['patient_id']);
        $patient_name = $patient->name . " " . $patient->last_name;

        $create = ActivityLog::create([
            'activity' => 'joined session with ' . $patient_name,
            'type' => 'session start',
            'user_id' => $arr['doctor_id'],
            'user_type' => 'doctor',
            'party_involved' => $arr['patient_id']
        ]);

        return $create;
    }

    public function getVideoLinks($arr = "")
    {
        $VideoLinks = DB::table($this->tbl_video_links)->select('*')
            ->where('session_id', $arr['session_id'])
            ->get();
        return $VideoLinks;
    }

    public function getDoctorStatus($arr = "")
    {
        $checkOnlineStatus = DB::table($this->tbl_users)->select('active as doctor_status')
            ->where('id', $arr['doctor_id'])
            ->get();
        $status = "";
        if (count($checkOnlineStatus) > 0) {
            $status = $checkOnlineStatus[0]->doctor_status == "1" ? 'Online' : 'Offline';
        }
        return $status;
    }

    public function updateDoctorStatus($data, $id)
    {
        $update = User::where('id', $id)
            ->update($data);

        $user = User::where('id', $id)->get();

        $status = "";
        if (count($user) > 0) {
            $status = $user[0]->active == "1" ? 'Online' : 'Offline';
        }
        return $status;
    }

    public function get_age($dob)
    {
        $birthDate = explode("/", $dob);
        $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[0], $birthDate[2]))) > date("md")
            ? ((date("Y") - $birthDate[2]) - 1)
            : (date("Y") - $birthDate[2]));
        return $age;
    }

    public function converToObjToArray($data)
    {
        return json_decode(json_encode($data), true);
    }

    public function getPatientDetailForSession($arr = "")
    {
        $session_id = $arr['session_id'];
        $getSessions = Session::where('id', $session_id)->first();
        $getPatient = User::where('id', $getSessions->patient_id)->first();

        $medical_profile['user_id'] = $getPatient->id;

        $userArray = $this->converToObjToArray($getPatient);
        $userArray['patient_id'] = $userArray['id'];

        $data_patient = [
            'patient_name' => $getPatient->name . " " . $getPatient->last_name,
            'patient_age' => $this->get_age($getPatient->date_of_birth),
            'symptoms' => $this->getPatientSessions_2($session_id),
            'medical_profile' => $this->getMedicalProfiles($medical_profile),
            'visit_history' => $this->getPatientSessions_3($userArray)
        ];
        return $data_patient;
    }

    public function getSessionDetailsByID($arr = "")
    {
        $session_id = $arr['session_id'];

        $get_session_data =  DB::table($this->tbl_sessions)
            ->join($this->tbl_users, $this->tbl_sessions . '.doctor_id', '=', $this->tbl_users . '.id')
            ->join($this->tbl_symptoms, $this->tbl_sessions . '.symptom_id', '=', $this->tbl_symptoms . '.id')
            ->select(
                $this->tbl_sessions . '.id as session_id',
                $this->tbl_sessions . '.provider_notes',
                $this->tbl_sessions . '.date',
                $this->tbl_sessions . '.status as session_status',
                $this->tbl_users . '.name as doctor_first_name',
                $this->tbl_users . '.last_name as doctor_last_name',
                $this->tbl_sessions . '.symptom_id',
                $this->tbl_users . '.name as doctor_first_name',
                $this->tbl_users . '.last_name as doctor_last_name',
                $this->tbl_symptoms . '.headache as symptoms_headache',
                $this->tbl_symptoms . '.fever as symptoms_fever',
                $this->tbl_symptoms . '.flu as symptoms_flu',
                $this->tbl_symptoms . '.nausea as symptoms_nausea',
                $this->tbl_symptoms . '.others as symptoms_others',
                $this->tbl_symptoms . '.description as symptoms_description'
            )
            ->where($this->tbl_sessions . '.id', $session_id)
            ->where($this->tbl_sessions . '.status', 'ended')
            ->get();

        $data = [
            'session_details' => $get_session_data,
            'precribed_medicines' => $this->getPrescribedMedicines($arr)
        ];

        return $data;
    }

    public function updateNotesDiagnosisToPatient($data, $id)
    {

        $update = Session::where('id', $id)->update(['provider_notes' => $data['provider_notes'], 'diagnosis' => $data['diagnosis']]);
        return $update;
    }

    public function getNearbyLabsPharmacy($arr = "")
    {
        $pharmacy = new Pharmacy();
        $getLocation = $pharmacy->get_lat_long_of_zipcode($arr['zipcode']);
        if (count($getLocation) == 0) {
            return ['No Location Found.'];
        } else {
            $getNearbyLocations = $pharmacy->get_nearby_places($getLocation[0]->lat, $getLocation[0]->long);
        }
        return $getNearbyLocations;
    }

    public function updateDiagnosisAndNotes($arr, $session_id)
    {

        $val['provider_notes'] = '<p>' . $arr['provider_notes'] . '</p>';
        $val['diagnosis'] = $arr['diagnosis'];

        $query = DB::table($this->tbl_sessions)
            ->where(['id' => $session_id])
            ->update($val);

        return $query;
    }

    public function addPrescribedMedicines($arr = "")
    {

        $query = Prescription::create($arr);
        return $query;
    }

    public function deletePrescribedMedicines($pres_id)
    {

        $query = Prescription::where(['id' => $pres_id])->delete();
        return $query;
    }

    public function updatePrescribedMedicines($data, $pres_id)
    {

        $query = DB::table($this->tbl_prescriptions)
            ->where(['id' => $pres_id])
            ->update($data);
        return $query;
    }

    public function endVideoSession($arr = "")
    {

        // Get Session ID
        $session_id = $arr['session_id'];

        // Data for end sesison
        $endSessionData = [
            'end_time' => NOW(),
            'queue' => 0,
            'cart_flag' => 5,
            'status' => 'ended'
        ];

        // End Session
        DB::table($this->tbl_sessions)->where(['id' => $session_id])->update($endSessionData);

        return 'Session ended successfully.';
    }

    public function addPresscribeMedicinesToCart($arr = "")
    {

        // Get Session ID
        $session_id = $arr['session_id'];
        $msg = '';

        $data = DB::table($this->tbl_sessions)
            ->join($this->tbl_prescriptions, $this->tbl_sessions . '.id', '=', $this->tbl_prescriptions . '.session_id')
            ->join($this->tbl_Products, $this->tbl_prescriptions . '.medicine_id', '=', $this->tbl_Products . '.id')
            ->select(
                $this->tbl_sessions . '.id as session_id',
                $this->tbl_Products . '.id as product_id',
                $this->tbl_Products . '.name',
                $this->tbl_prescriptions . '.quantity',
                $this->tbl_Products . '.sale_price as price',
                $this->tbl_sessions . '.patient_id AS user_id',
                $this->tbl_sessions . '.id AS doc_session_id',
                $this->tbl_sessions . '.doctor_id AS doc_id',
                $this->tbl_prescriptions . '.id as pres_id',
                $this->tbl_prescriptions . '.type as product_mode',
                DB::raw("(prescriptions.`quantity` * tbl_products.`sale_price`) AS update_price"),
                DB::raw("'recommended' AS `status`"),
                DB::raw("'3' AS `map_marker_id`"),
                DB::raw("'db' AS `item_type`"),
            )
            ->where($this->tbl_sessions . '.id', $session_id)
            ->get();

        if (count($data) > 0) {
            $data2 = $this->converToObjToArray($data);
            foreach ($data2 as $key => $val) {
                Cart::create($val);
            }
            $msg = 'Prescribed medicines added to the cart.';
        } else {
            $msg = 'Prescribed medicines not found.';
        }

        return $msg;
    }

    public function sessionEndActivityLog($arr = "")
    {

        $data = Session::find($arr['session_id']);
        $data2 = User::find($data->patient_id);
        ActivityLog::create([
            'activity' => 'prescribed medicines to ' . $data2['name'] . " " . $data2['last_name'],
            'type' => 'prescription added',
            'user_id' => $data['doctor_id'],
            'user_type' => 'doctor',
            'party_involved' => $data['patient_id']
        ]);
        return 'Activity log is created.';
    }

    public function getLocationsByZipcodeImaging($arr = "")
    {
        $phar = new Pharmacy();
        $data['data'] = $phar->get_lat_long_of_zipcode_imaging($arr['zipcode']);
        $data1 = $phar->get_nearby_places_imaging($data['data'][0]->lat, $data['data'][0]->long);
        return $data1;
    }

    public function getPriceByLocationImaging($arr = "")
    {

        return ImagingPrices::where('location_id', $arr['location_id'])->where('product_id', $arr['product_id'])->get();
    }

    public function setDoctorAvailability($arr = "")
    {
        $arr['color'] = '#008000';
        $create = DoctorSchedule::Create($arr)->id;
        return 'Success';
    }

    public function updateDoctorAvailability($id, $arr = "")
    {
        $arr['color'] = '#008000';
        $update = DoctorSchedule::where('id', $id)->update($arr);
        return $update;
    }


    public function getPatientByDoctor($arr = "")
    {
        $query = DB::table($this->tbl_sessions)
            ->join($this->tbl_users, $this->tbl_sessions . '.patient_id', '=', $this->tbl_users . '.id')
            ->select(
                $this->tbl_sessions . '.id as session_id',
                $this->tbl_sessions . '.patient_id',
                $this->tbl_users . '.name as first_name',
                $this->tbl_users . '.last_name',
                $this->tbl_sessions . '.appointment_id',
                $this->tbl_sessions . '.date',
                $this->tbl_sessions . '.start_time',
                $this->tbl_sessions . '.end_time',
                $this->tbl_sessions . '.provider_notes',
                $this->tbl_sessions . '.patient_rating',
                $this->tbl_sessions . '.patient_feedback',
                $this->tbl_sessions . '.status',
                $this->tbl_sessions . '.diagnosis',
                $this->tbl_sessions . '.patient_suggestions',
                $this->tbl_sessions . '.remaining_time',
                $this->tbl_sessions . '.cart_flag',
                $this->tbl_sessions . '.queue',
                $this->tbl_sessions . '.sequence',
                $this->tbl_sessions . '.feedback_flag',
            )
            ->where($arr)
            ->orderByDesc($this->tbl_sessions . '.id')
            ->get();
        return $query;
    }

    public function getPatientQueue($arr = "")
    {
        $query = DB::table($this->tbl_sessions)
            ->join($this->tbl_users, $this->tbl_sessions . '.doctor_id', '=', $this->tbl_users . '.id')
            ->select(
                $this->tbl_sessions . '.id as session_id',
                $this->tbl_sessions . '.patient_id',
                $this->tbl_users . '.name as first_name',
                $this->tbl_users . '.last_name',
                $this->tbl_sessions . '.appointment_id',
                $this->tbl_sessions . '.date',
                $this->tbl_sessions . '.start_time',
                $this->tbl_sessions . '.end_time',
                $this->tbl_sessions . '.provider_notes',
                $this->tbl_sessions . '.patient_rating',
                $this->tbl_sessions . '.patient_feedback',
                $this->tbl_sessions . '.status',
                $this->tbl_sessions . '.diagnosis',
                $this->tbl_sessions . '.patient_suggestions',
                $this->tbl_sessions . '.remaining_time',
                $this->tbl_sessions . '.cart_flag',
                $this->tbl_sessions . '.queue',
                $this->tbl_sessions . '.sequence',
                $this->tbl_sessions . '.feedback_flag',
            )
            ->where($arr)
            ->where($this->tbl_sessions . '.status', 'invitation sent')
            ->where($this->tbl_sessions . '.queue', 1)
            ->groupBy($this->tbl_sessions . '.patient_id')
            ->orderBy($this->tbl_sessions . '.sequence', 'ASC')
            ->get();
        return $query;
    }

    public function getCityStateByZipcode($arr = "")
    {
        $query = DB::table('tbl_zip_codes_cities')
            ->where($arr)
            ->get();
        return $query;
    }
}
