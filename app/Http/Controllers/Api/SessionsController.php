<?php

namespace App\Http\Controllers\Api;
use App\ActivityLog;
use App\AgoraAynalatics;
use App\Cart;
use App\Events\DoctorJoinedVideoSession;
use App\Events\patientJoinCall;
use App\MedicalProfile;
use App\Models\AllProducts;
use App\Models\ProductCategory;
use App\Models\ProductsSubCategory;
use App\Prescription;
use App\QuestDataAOE;
use App\QuestDataTestCode;
use App\Referal;
use App\Specialization;
use App\Symptom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Events\RealTimeMessage;
use App\Events\updateDoctorWaitingRoom;
use App\Mail\patientEvisitInvitationMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Carbon\CarbonInterval;
use Carbon\Carbon;
use App\Notification;
use App\Session;
use App\User;
use App\Helper;

class SessionsController extends BaseController
{
    public function createSession(Request $request)
    {

        $symp = $request->validate([
            'doc_sp_id' => ['required'],
            'doc_id' => ['required', 'max:255'],
            'problem' => ['required', 'string', 'max:255'],
        ]);

        $patient_id = Auth::user()->id;
        $doc_id = $symp['doc_id'];
        $symp_id = 0;

        $check_session_already_have = DB::table('sessions')
            ->where('doctor_id', $symp['doc_id'])
            ->where('patient_id', $patient_id)
            ->where('specialization_id', $request->doc_sp_id)
            ->count();

        $pending_sessions = DB::table('sessions')
            ->where('doctor_id', $symp['doc_id'])
            ->where('patient_id', $patient_id)
            ->where('specialization_id', $request->doc_sp_id)
            ->where('status', 'pending')
            ->orWhere('status', 'invitation sent')
            ->orWhere('status', 'doctor joined')
            ->orWhere('status', 'started')
            ->orderBy('id', 'desc')
            ->first();

        if ($pending_sessions > 0) {
            return $this->sendResponse($pending_sessions, 'You already have a pending session with this doctor');
        }
        $session_price = "";
        if ($check_session_already_have > 0) {
            $session_price_get = User::find($request->doc_id);
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

        $new_session_id = 0;
        $randNumber = rand(11, 99);
        $getLastSessionId = DB::table('sessions')->orderBy('id', 'desc')->first();
        if ($getLastSessionId != null) {
            $new_session_id = $getLastSessionId->session_id + 1 + $randNumber;
        } else {
            $new_session_id = rand(311111, 399999);
        }

        $session_id = Session::create([
            'patient_id' => $patient_id,
            'doctor_id' => $doc_id,
            'date' => $date,
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
        if ($request->payment_method == "credit-card") {
            $session = Session::find($session_id);
            $data = "Evisit-" . $new_session_id . "-1";
            $pay = new \App\Http\Controllers\MeezanPaymentController();
            $res = $pay->payment_app($data, ($session->price * 100));
            if (isset($res) && $res->errorCode == 0) {
                return $this->sendResponse(['method' => 'credit-card', 'url' => $res->formUrl, 'session_id' => $session_id], 'Payment link generated successfully');

            } else {
                return $this->sendError([], 'Payment link not generated');
            }
        } elseif ($request->payment_method == "first-visit") {
            $session = Session::find($session_id);
            $session->status = "paid";
            $session->save();
            $session_id = $session->id;
            return $this->sendResponse(['method' => 'first-visit', 'session_id' => $session_id], 'Session created successfully');

        } else {
            Session::find($session_id)->delete();
            return $this->sendError([], 'Payment method not found');
        }
    }
    public function getSession($id)
    {
        $session = Session::select('patient_id', 'doctor_id', 'status', 'queue', 'channel', 'que_message', 'remaining_time', 'status')->find($id);
        if (empty($session)) {
            return $this->sendError([], 'Session not found');
        }
        $doctor = User::select('name', 'last_name', 'user_image')->find($session->doctor_id);
        $doctor->user_image = Helper::check_bucket_files_url($doctor->user_image);
        $session->doctor = $doctor;

        return $this->sendResponse(['session' => $session], 'Session found successfully');
    }
    public function sessionInvite($id)
    {
        $res = Session::where('id', $id)->update(['status' => 'invitation sent', 'invite_time' => now()]);
        if ($res == 1) {
            $sessionData = Session::where('id', $id)->first();
            $doc_name = \App\Helper::get_name($sessionData->doctor_id);
            $pat_name = \App\Helper::get_name($sessionData->patient_id);
            $notification_id = Notification::create([
                'user_id' => $sessionData->doctor_id,
                'text' => 'E-visit Joining Request Send by ' . $pat_name,
                'session_id' => $id,
                'type' => 'doctor/patient/queue',
            ]);
            $data = [
                'user_id' => $sessionData->doctor_id,
                'text' => 'E-visit Joining Request Send by ' . $pat_name,
                'session_id' => $id,
                'type' => 'doctor/patient/queue',
                'received' => 'false',
                'appoint_id' => 'null',
                'refill_id' => 'null',
            ];

            event(new RealTimeMessage($sessionData->doctor_id));
            event(new updateDoctorWaitingRoom('new_patient_listed'));
            try {
                $doctor = DB::table('users')->where('id', $sessionData->doctor_id)->first();
                $markDown = [
                    'doc_email' => $doctor->email,
                    'doc_name' => ucwords($doc_name),
                    'pat_name' => ucwords($pat_name),
                ];
                Mail::to($doctor->email)->send(new patientEvisitInvitationMail($markDown));
            } catch (\Exception $e) {
                Log::error($e);
            }

            //get doctor all session order by ASC
            $doc_all_session = Session::where('doctor_id', $sessionData->doctor_id)
                ->where('status', 'invitation sent')
                ->orWhere('status', 'doctor joined')
                ->orderBy('id', 'ASC')
                ->get();
            //count doctor session
            $session_count = count($doc_all_session);
            if ($session_count > 1) {
                $mints = 5;
                foreach ($doc_all_session as $single_session) {
                    Session::where('id', $single_session['id'])->update(['que_message' => 'Your Doctor Will Be Available In Approximately ' . $mints . ' Mints']);
                    $mints += 10;
                }
            } else {
                foreach ($doc_all_session as $single_session) {
                    Session::where('id', $single_session['id'])->update(['que_message' => 'Your Doctor Will Be Available In Approximately 5 Mints']);
                }
            }
            $getData = Session::where('id', $id)->where('patient_id', auth()->user()->id)->first();
            return $this->sendResponse($getData, 'Session invitation sent successfully');
        } else {
            return $this->sendError([], 'Session invitation not sent');
        }
    }
    public function waitingPatientJoinCall(Request $request)
    {
        $id = $request->session_id;
        $getSession = Session::where('id', $id)->first();

        $userTypeCheck = User::where('id', $getSession->doctor_id)->first();
        $patUser = User::where('id', $getSession->patient_id)->first();
        if ($patUser->med_record_file != null) {
            $patUser->med_record_file = \App\Helper::get_files_url($patUser->med_record_file);
        }

        if ($userTypeCheck->user_type == 'doctor') {
            Session::where('id', $id)->update(['status' => 'doctor joined']);
            $queue = Session::where('id', $id)->first();
            if ($queue->queue < 2) {
                Session::where('id', $id)->update(['response_time' => now()]);
            }

            event(new DoctorJoinedVideoSession($getSession->doctor_id, $getSession->patient_id, $id));
            ActivityLog::create([
                'activity' => 'joined session with ' . $patUser->name . " " . $patUser->last_name,
                'type' => 'session start',
                'user_id' => $getSession->doctor_id,
                'user_type' => 'doctor',
                'identity' => $id,
                'party_involved' => $getSession->patient_id,
            ]);
        }
        return response()->json(['message' => 'waiting for patient to join']);
    }
    public function videoJoin($id, Request $request)
    {
        try {
            if (!is_numeric($id) || $id <= 0) {
                return $this->sendError('Invalid session ID', [], 400);
            }

            if (!auth()->check()) {
                return $this->sendError('Unauthorized', [], 401);
            }

            $user_id = auth()->user()->id;
            $getSession = Session::where('id', $id)->first();
            $doctor = DB::table('users')
                ->join('specializations', 'specializations.id', 'users.specialization')
                ->where('users.id', $getSession->doctor_id)
                ->select('users.name', 'users.last_name', 'users.id', 'specializations.name as sp_name', 'specializations.id as sp_id')
                ->first();

            if (!$getSession) {
                return $this->sendError('Session not found', [], 404);
            }

            $userTypeCheck = User::where('id', $user_id)->first();
            if (!$userTypeCheck) {
                return $this->sendError('User not found', [], 404);
            }

            $patUser = User::where('id', $getSession->patient_id)->first();
            if (!$patUser) {
                return $this->sendError('Patient not found', [], 404);
            }

            $docUser = User::where('id', $getSession->doctor_id)->first();
            if (!$docUser) {
                return $this->sendError('Doctor not found', [], 404);
            }

            if ($patUser->med_record_file != null) {
                try {
                    $patUser->med_record_file = Helper::get_files_url($patUser->med_record_file);
                } catch (\Exception $e) {
                    Log::error("Failed to get file URL for user {$patUser->id}: " . $e->getMessage());
                    $patUser->med_record_file = null;
                }
            }

            if ($userTypeCheck->user_type == 'doctor') {
                try {
                    $medicines = [];
                    $medicines['products'] = AllProducts::where(['mode' => 'medicine', 'medicine_type' => 'prescribed'])
                        ->get()
                        ->toArray();

                    $medicines['category'] = ProductsSubCategory::where(['parent_id' => 38])
                        ->get()
                        ->toArray();

                    $labs = QuestDataTestCode::whereRaw("TEST_CD NOT LIKE '#%%' ESCAPE '#'")
                        ->whereIn('id', [
                            '3327',
                            '4029',
                            '1535',
                            '3787',
                            '47',
                            '1412',
                            '1484',
                            '1794',
                            '3194',
                            '3352',
                            '3566',
                            '3769',
                            '4446',
                            '18811',
                            '11363',
                            '899',
                            '16846',
                            '3542',
                            '229',
                            '747',
                            '6399',
                            '7573',
                            '16814',
                        ])
                        ->where('TEST_CD', '!=', '92613')
                        ->where('TEST_CD', '!=', '11196')
                        ->where('LEGAL_ENTITY', 'DAL')
                        ->orWhere('PRICE', '!=', null)
                        ->get();

                    foreach ($labs as $lab) {
                        try {
                            $res = DB::table('prescriptions')->where('test_id', $lab->TEST_CD)
                                ->where('session_id', $id)
                                ->first();

                            $lab->added = ($res != null) ? 'yes' : 'no';

                            $getTestAOE = QuestDataAOE::select(
                                "TEST_CD AS TestCode",
                                "AOE_QUESTION AS QuestionShort",
                                "AOE_QUESTION_DESC AS QuestionLong"
                            )
                                ->where('TEST_CD', $lab->TEST_CD)
                                ->groupBy('AOE_QUESTION_DESC')
                                ->get()
                                ->toArray();

                            $count = count($getTestAOE);
                            $lab->aoes = ($count > 0) ? 1 : 0;
                            $lab->aoeQuestions = ($count > 0) ? $getTestAOE : '';
                        } catch (\Exception $e) {
                            Log::error("Error processing lab {$lab->TEST_CD}: " . $e->getMessage());
                            continue;
                        }
                    }

                    $imaging = AllProducts::where('mode', 'imaging')->get()->toArray();
                    $img_categories = ProductCategory::where('category_type', 'imaging')->get()->toArray();

                    $symp = Symptom::find($getSession->symptom_id);
                    $symptoms = $this->getSymptoms($symp);
                    $symp_desc = ($symp != null) ? $symp['description'] : '';

                    $user_obj = new User();
                    $patient_meds = $user_obj->get_current_medicines($getSession->patient_id);
                    $patient_labs = $user_obj->get_lab_reports($getSession->patient_id);
                    $img_reports = $user_obj->get_imaging_reports($getSession->patient_id);
                    $prev_sessions = $user_obj->get_sessions($getSession->patient_id);
                    $pat_age = $user_obj->get_age($getSession->patient_id);

                    $medical_profile = MedicalProfile::where('user_id', $getSession->patient_id)
                        ->orderByDesc('id')
                        ->first();

                    $specs = Specialization::where('status', '1')->get()->toArray();

                    return $this->sendResponse([
                        'user_type' => 'doctor',
                        'medicines' => $medicines,
                        'pat_age' => $pat_age,
                        'labs' => $labs,
                        'imaging' => $imaging,
                        'symptoms' => $symptoms,
                        'symp_desc' => $symp_desc,
                        'patUser' => $patUser,
                        'getSession' => $getSession,
                        'medical_profile' => $medical_profile,
                        'prev_sessions' => $prev_sessions,
                        'patient_meds' => $patient_meds,
                        'specs' => $specs,
                        'patient_labs' => $patient_labs,
                        'img_categories' => $img_categories,
                        'img_reports' => $img_reports
                    ], 'Session joined successfully');

                } catch (\Exception $e) {
                    Log::error("Doctor video join error: " . $e->getMessage());
                    return $this->sendError('Failed to load doctor session data', [], 500);
                }

            } else if ($userTypeCheck->user_type == 'patient') {
                try {
                    $user_obj = new User();

                    $history = [
                        'medicines' => $user_obj->get_current_medicines($getSession->patient_id),
                        'sessions' => $user_obj->get_sessions($getSession->patient_id),
                        'labs' => $user_obj->get_lab_reports($getSession->patient_id),
                        'imaging' => $user_obj->get_imaging_reports($getSession->patient_id)
                    ];

                    $pat_age = $user_obj->get_age($getSession->patient_id);

                    $spec = new Specialization();
                    $spname = $spec->speciality($getSession->doctor_id);
                    $docUser->specialization = $spname;

                    $symp = Symptom::find($getSession->symptom_id);
                    $symptoms = $this->getSymptoms($symp);
                    $symp_desc = ($symp != null) ? $symp['description'] : '';

                    $medical_profile = MedicalProfile::where('user_id', $getSession->patient_id)
                        ->orderByDesc('id')
                        ->first();

                    try {
                        ActivityLog::create([
                            'activity' => 'joined session with ' . $docUser->name . " " . $docUser->last_name,
                            'type' => 'session start',
                            'user_id' => $getSession->patient_id,
                            'user_type' => 'doctor',
                            'party_involved' => $getSession->doctor_id,
                        ]);
                    } catch (\Exception $e) {
                        Log::error("Failed to create activity log: " . $e->getMessage());
                    }

                    try {
                        event(new patientJoinCall($getSession->doctor_id, $getSession->patient_id, $id));
                    } catch (\Exception $e) {
                        Log::error("Failed to fire patientJoinCall event: " . $e->getMessage());
                    }

                    if ($getSession->remaining_time == 'full') {
                        $time_in_minutes = Specialization::where('id', $doctor->sp_id)->first();
                        $time_in_minutes = $time_in_minutes->consultation_time;
                        $start_time = Carbon::now()->addMinutes($time_in_minutes)->format('Y-m-d H:i:s');
                        Session::where('id', $id)->update(['start_time' => $start_time, 'remaining_time' => $time_in_minutes . ' minute : 00 seconds']);
                        $getSession->remaining_time = $time_in_minutes . ' minute : 00 seconds';
                    } else {
                        $nowTime = strtotime(Carbon::now()->format('Y-m-d H:i:s'));
                        $end_time = strtotime($getSession->start_time);
                        if ($end_time > $nowTime) {
                            $diff = $end_time - $nowTime;
                            $getTime = CarbonInterval::seconds($diff)->cascade()->forHumans();
                            $breakTime = explode(' ', $getTime);
                            if (in_array('seconds', $breakTime) && in_array('minute', $breakTime) || in_array('minutes', $breakTime)) {
                                $existTime = $breakTime[0] . ' ' . $breakTime[1] . ' : ' . $breakTime[2] . ' ' . $breakTime[3];
                                Session::where('id', $id)->update(['remaining_time' => $existTime]);
                                $getSession->remaining_time = $existTime;
                            } else if (!in_array('seconds', $breakTime) && in_array('minute', $breakTime) || in_array('minutes', $breakTime)) {
                                $existTime = $breakTime[0] . ' ' . $breakTime[1] . ' : 00 seconds';
                                Session::where('id', $id)->update(['remaining_time' => $existTime]);
                                $getSession->remaining_time = $existTime;
                            } else if (in_array('seconds', $breakTime) && !in_array('minute', $breakTime) || in_array('minutes', $breakTime)) {
                                $existTime = '00 minute : ' . $breakTime[0] . ' ' . $breakTime[1];
                                Session::where('id', $id)->update(['remaining_time' => $existTime]);
                                $getSession->remaining_time = $existTime;
                            }
                        }
                    }

                    return $this->sendResponse([
                        'user_type' => 'patient',
                        'docUser' => $docUser,
                        'pat_age' => $pat_age,
                        'getSession' => $getSession,
                        'medical_profile' => $medical_profile,
                        'symptoms' => $symptoms,
                        'symp_desc' => $symp_desc,
                        'history' => $history,
                        'patUser' => $patUser
                    ], 'Session joined successfully');

                } catch (\Exception $e) {
                    Log::error("Patient video join error: " . $e->getMessage());
                    return $this->sendError('Failed to load patient session data', [], 500);
                }
            } else {
                return $this->sendError('Invalid user type', [], 400);
            }

        } catch (\Exception $e) {
            Log::error("Video join general error: " . $e->getMessage());
            return $this->sendError('An unexpected error occurred', [], 500);
        }
    }
    public function getSymptoms($symp)
    {
        if ($symp != null) {
            $symptoms = '';
            if ($symp['flu'] == '1') {
                $symptoms .= 'flu, ';
            }
            if ($symp['headache'] == '1') {
                $symptoms .= 'headache, ';
            }

            if ($symp['nausea'] == '1') {
                $symptoms .= 'nausea, ';
            }

            if ($symp['fever'] == '1') {
                $symptoms .= 'fever, ';
            }

            if ($symp['others'] == '1') {
                $symptoms .= 'others';
            }

            $symp_desc = $symp['description'];
        } else {
            $symptoms = 'Not specified';
            $symp_desc = '';
        }
        return $symptoms;
    }

    public function pat_sessions_record()
    {
        $user = auth()->user();
        $user_type = $user->user_type;
        $user_time_zone = $user->timeZone;
        if ($user_type == 'patient') {
            $user_id = $user->id;
            $sessions = Session::where('patient_id', $user_id)
                ->where('status', 'ended')
                ->where('remaining_time', '!=', 'full')
                ->orderByDesc('id')
                ->paginate(7);
            foreach ($sessions as $session) {
                if ($session->start_time != 'null' && $session->end_time != 'null') {
                    $session->date = User::convert_utc_to_user_timezone($user->id, $session->created_at)['date'];

                    $session->start_time = date('h:i A', strtotime('-15 minutes', strtotime($session->start_time)));
                    $session->start_time = User::convert_utc_to_user_timezone($user->id, $session->start_time)['time'];

                    $session->end_time = date('h:i A', strtotime('-15 minutes', strtotime($session->end_time)));
                    $session->end_time = User::convert_utc_to_user_timezone($user->id, $session->end_time)['time'];

                    $doc = User::where('id', $session['doctor_id'])->first();
                    $session->doc_name = $doc['name'] . " " . $doc['last_name'];
                    $links = AgoraAynalatics::where('channel', $session['channel'])->first();
                    if ($links != null) {
                        $recording = $links->video_link;
                        $session->recording = $recording;
                    } else {
                        $session->recording = 'No recording';
                    }
                    $pres = Prescription::where('session_id', $session['id'])->get();
                    $pres_arr = [];

                    $referred_doc = Referal::where('session_id', $session['id'])
                        ->where('patient_id', $user_id)
                        ->where('doctor_id', $doc->id)
                        ->leftjoin('users', 'referals.sp_doctor_id', 'users.id')
                        ->select('users.name', 'users.last_name')
                        ->get();
                    if (count($referred_doc)) {
                        $session->refered = "Dr." . $referred_doc[0]->name . " " . $referred_doc[0]->last_name;
                    } else {
                        $session->refered = null;
                    }
                    $session->sympptoms = DB::table('symptoms')->where('id', $session['symptom_id'])->first();

                    foreach ($pres as $prod) {


                        if ($prod['type'] == 'medicine') {
                            $product = AllProducts::where('id', $prod['medicine_id'])->first();
                        } else if ($prod['type'] == 'imaging') {
                            $product = QuestDataTestCode::where('TEST_CD', $prod['test_id'])->first();
                        } else if ($prod['type'] == 'lab-test') {
                            $product = QuestDataTestCode::where('TEST_CD', $prod['test_id'])->first();
                        }

                        $cart = Cart::where('doc_session_id', $session['id'])->where('pres_id', $prod->id)->first();
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
                }
            }
            return $this->sendResponse($sessions, 'Sessions found successfully');
        }
    }

    public function doc_sessions_record(Request $request)
    {
        $user = auth()->user();
        $user_type = $user->user_type;
        $user_time_zone = $user->timeZone;
        if ($user_type == 'doctor') {
                $user_id = $user->id;
                $sessions = Session::where('doctor_id', $user_id)
                    ->where('status', 'ended')
                    ->where('remaining_time','!=','full')
                    ->orderByDesc('id')
                    ->paginate(7);
                foreach ($sessions as $session) {
                    if ($session->status == 'ended' && $session->start_time != 'null' && $session->end_time != 'null') {
                        $session->date = User::convert_utc_to_user_timezone($user->id, $session->created_at)['date'];

                        $session->start_time = date('h:i A', strtotime('-15 minutes', strtotime($session->start_time)));
                        $session->start_time = User::convert_utc_to_user_timezone($user->id, $session->start_time)['time'];

                        $session->end_time = date('h:i A', strtotime('-15 minutes', strtotime($session->end_time)));
                        $session->end_time = User::convert_utc_to_user_timezone($user->id, $session->end_time)['time'];

                        $pat = User::where('id', $session['patient_id'])->first();
                        $session->pat_name = $pat['name'] . " " . $pat['last_name'];
                        //refered doctor
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
                        //prescription
                        $pres = Prescription::where('session_id', $session['id'])->get();
                        $pres_arr = [];
                        foreach ($pres as $prod) {
                            if ($prod['type'] == 'medicine') {
                                $product = AllProducts::where('id', $prod['medicine_id'])->first();
                            } else if ($prod['type'] == 'imaging') {
                                $product = QuestDataTestCode::where('TEST_CD', $prod['test_id'])
                                    ->first();
                            } else if ($prod['type'] == 'lab-test') {
                                $product = QuestDataTestCode::where('TEST_CD', $prod['test_id'])
                                    ->first();
                            }
                            $cart = Cart::where('doc_session_id', $session['id'])
                                ->where('pres_id', $prod->id)->first();
                            $prod->prod_detail = $product;
                            if (isset($cart->status))
                                $prod->cart_status = $cart->status;
                            else
                                $prod->cart_status = 'No record';
                            array_push($pres_arr, $prod);
                        }
                        $session->pres = $pres_arr;
                    }
                }

            return $this->sendResponse($sessions, 'get doctor sessions successfully');
        }
    }

    public function sessionCheck()
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return $this->sendError([], 'User not authenticated', 401);
            }

            $user_type = $user->user_type;

            if ($user_type == 'doctor') {
                $session = Session::where('doctor_id', $user->id)
                    ->where('status', 'started')
                    ->orWhere('status', 'doctor joined')
                    ->first();

                if ($session) {
                    return $this->sendResponse($session, 'You have a session to join.');
                } else {
                    return $this->sendResponse([], 'No active session found for doctor');
                }
            } elseif ($user_type == 'patient') {
                $session = Session::where('patient_id', $user->id)
                    ->where('status', 'started')
                    ->orWhere('status', 'invitation sent')
                    ->orWhere('status', 'doctor joined')
                    ->first();

                if ($session) {
                    return $this->sendResponse($session, 'You have a session to join.');
                } else {
                    return $this->sendResponse([], 'No active session found for patient');
                }
            } else {
                return $this->sendError([], 'Invalid user type', 400);
            }

        } catch (\Exception $e) {
            return $this->sendError([], 'Something went wrong: ' . $e->getMessage(), 500);
        }
    }


}
