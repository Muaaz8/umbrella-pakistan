<?php

namespace App\Http\Controllers;

use App\City;
use App\Country;
use App\Http\Controllers\HL7Controller;
use App\Mail\labTestResultDoctorMail;
use App\Mail\labTestResultLabAdminMail;
use App\Mail\labTestResultPatientMail;
use App\QuestDataTestCode;
use App\QuestGetResultRequest;
use App\QuestResult;
use App\Specialization;
use App\State;
use App\User;
use Aranyasen\HL7\Message;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Log;

class QuestController extends Controller
{
    public function index()
    {

        $requisitions = DB::table('quest_labs')
            ->join('quest_requests', 'quest_labs.id', '=', 'quest_requests.quest_lab_id')
            ->join('lab_orders', 'quest_requests.order_id', '=', 'lab_orders.order_id')
            ->where('lab_orders.status', 'quest-forwarded')
            ->groupBy('quest_requests.id')
            ->orderByDesc('quest_requests.id')->paginate(9);
        foreach ($requisitions as $requisition) {
            $dateTime = new \DateTime($requisition->created_at);
            $requisition->created_at = $dateTime->format('M, dS Y');
            $requisition->names = json_decode($requisition->names);
        }
        // dd($requisitions);

        // $user = Auth::user();

        // $orders = DB::table('lab_orders')->groupBy('order_id')->get();
        // foreach ($orders as $order) {
        //     $order->collect_time = User::convert_utc_to_user_timezone($user->id, $order->collect_time)['time'];
        // }
        // dd($orders);
        return view('lab.quest.index', compact('requisitions'));
    }

    public function dash_index(Request $request)
    {
        if (isset($request->name)) {
            $requisitions = DB::table('quest_labs')
                ->join('quest_requests', 'quest_labs.id', '=', 'quest_requests.quest_lab_id')
                ->join('lab_orders', 'quest_requests.order_id', '=', 'lab_orders.order_id')
                ->where('lab_orders.status', 'quest-forwarded')
                ->where('lab_orders.order_id', 'like', '%' . $request->name . '%')
                ->groupBy('quest_requests.id')
                ->orderByDesc('quest_requests.id')->paginate(9);
        } else {
            $requisitions = DB::table('quest_labs')
                ->join('quest_requests', 'quest_labs.id', '=', 'quest_requests.quest_lab_id')
                ->join('lab_orders', 'quest_requests.order_id', '=', 'lab_orders.order_id')
                ->where('lab_orders.status', 'quest-forwarded')
                ->groupBy('quest_requests.id')
                ->orderByDesc('quest_requests.id')->paginate(9);
        }
        foreach ($requisitions as $requisition) {
            $requisition->created_at = User::convert_utc_to_user_timezone(auth()->user()->id,$requisition->created_at)['datetime'];
            $dateTime = new \DateTime($requisition->created_at);
            $requisition->created_at = $dateTime->format('M,dS Y h:i A');
            $requisition->names = json_decode($requisition->names);
        }
        // dd($requisitions);
        return view('dashboard_admin.Quest_orders.index', compact('requisitions'));
    }

    public function e_prescription()
    {
        $med = DB::table('medicine_order')->join('prescriptions_files', 'medicine_order.order_sub_id', 'prescriptions_files.order_id')->orderBy('medicine_order.id', 'desc')->get();
        foreach ($med as $m) {
            $m->created_at = User::convert_utc_to_user_timezone(auth()->user()->id, $m->created_at);
            $m->created_at = $m->created_at['date'] . ' ' . $m->created_at['time'];
        }
        return view('lab.quest.e_prescription', compact('med'));
    }

    public function dash_e_prescription(Request $request)
    {
        if (isset($request->name)) {
            $med = DB::table('medicine_order')
                ->join('prescriptions_files', 'medicine_order.order_sub_id', 'prescriptions_files.order_id')
                ->where('medicine_order.order_main_id', $request->name)
                ->orderBy('medicine_order.id', 'desc')
                ->paginate(10);
        } else {
            $med = DB::table('medicine_order')
                ->join('prescriptions_files', 'medicine_order.order_sub_id', 'prescriptions_files.order_id')
                ->orderBy('medicine_order.id', 'desc')
                ->paginate(10);
        }
        foreach ($med as $m) {
            $m->created_at = User::convert_utc_to_user_timezone(auth()->user()->id, $m->created_at);
            $m->created_at = $m->created_at['date'] . ' ' . $m->created_at['time'];
        }
        return view('dashboard_admin.E-Prescription.index', compact('med'));
    }
    public function dash_imaging_file(Request $request)
    {
        // if (isset($request->name)) {
        //     $med = DB::table('medicine_order')
        //         ->join('prescriptions_files', 'medicine_order.order_sub_id', 'prescriptions_files.order_id')
        //         ->where('medicine_order.order_main_id', $request->name)
        //         ->orderBy('medicine_order.id', 'desc')
        //         ->paginate(10);
        // } else {
            $med = DB::table('imaging_file')->orderby('id','desc')->paginate(10);
        // }
        foreach ($med as $m) {
            $m->names = DB::table('imaging_orders')
            ->join('tbl_products','imaging_orders.product_id','tbl_products.id')
            ->where('imaging_orders.order_id',$m->order_id)
            ->select('tbl_products.name')->get();
            $doc = DB::table('users')->where('id',$m->doctor_id)->first();
            $m->doc = $doc->name.' '.$doc->last_name;
            $m->created_at = User::convert_utc_to_user_timezone(auth()->user()->id, $m->created_at);
            $m->created_at = $m->created_at['date'] . ' ' . $m->created_at['time'];
        }
        return view('dashboard_admin.Imaging-File.index', compact('med'));
    }
    public function requisition($id)
    {
        $order = DB::table('quest_labs')
            ->join('users as patient', 'patient.id', '=', 'quest_labs.umd_patient_id')
        // ->join('users as doctor','doctor.upin','=','quest_labs.upin')
            ->join('states', 'states.id', '=', 'patient.state_id')
            ->join('cities', 'cities.id', '=', 'patient.city_id')
        // ->where('quest_labs.id', $id)
            ->select(
                'quest_labs.*',
                'patient.name',
                'patient.last_name',
                'patient.state_id',
                'patient.city_id',
                'patient.office_address',
                'patient.zip_code',
                'cities.name as city',
                'patient.phone_number',
                'patient.gender',
                'patient.date_of_birth',
                'states.state_code as state'
            )
            ->first();
        // dd($order);
        // foreach ($order as $order) {
        // $order->collect_time = User::convert_utc_to_user_timezone($user->id,$order->collect_time)['time'];
        // }
        // dd($order);
        return view('lab.quest.requisition', compact('order'));
    }

    public function viewQuestTestReport($get_request_id)
    {
        $res = QuestGetResultRequest::find($get_request_id);
        if (!empty($res->json_response)) {
            $res_arr = json_decode($res->json_response);
            $base_64_String = $res_arr->results[0]->hl7Message->message;
            $hl7String = base64_decode($base_64_String);
            $str_arr = explode('\n', $hl7String);
            $str_hl7 = "";
            foreach ($str_arr as $segment) {
                $str_hl7 .= $segment . "\r\n";
            }
            // dd($str_hl7);
            $hl7controller = new HL7Controller();
            $report = $hl7controller->hl7Decode($str_hl7);
            if ($report['performed'][0] == "TNP") {
                return "Test not performed because " . $report['performed'][1];
            } else {
                // if($report['patient_matching']=="Patient matched"){
                return view('lab.quest.result_report', compact('report'));
                // }else{
                // return "Authorization Error";
                // }
            }
        } else {
            echo 'Sorry no reports found in quest records.';
        }
    }
    public function viewAllQuestTestReports()
    {
        $reports = QuestGetResultRequest::all();
        // dd($reports);
        return view('lab.quest.results_table', compact('reports'));
    }
    public function get_lab_report_using_base64_req()
    {
        return view('lab.quest.base64_form');
    }
    public function submit_base64_req(Request $request)
    {
        $hl7String = base64_decode($request->base64);
        $str_arr = explode('\n', $hl7String);
        $str_hl7 = "";
        foreach ($str_arr as $segment) {
            $str_hl7 .= $segment . "\r\n";
        }
        // dd($str_hl7);
        $hl7controller = new HL7Controller();
        $report = $hl7controller->hl7Decode($str_hl7);
        if ($report['performed'][0] == "TNP") {
            return "Test not performed because " . $report['performed'][1];
        } else {
            // if($report['patient_matching']=="Patient matched"){
            return view('lab.quest.result_report', compact('report'));
            // }else{
            // return "Authorization Error";
            // }
        }
    }
    public function get_lab_report_using_hl7_req()
    {
        return view('lab.quest.hl7_form');
    }
    public function submit_hl7_req(Request $request)
    {
        $hl7String = $request->hl7;
        $str_arr = explode('\n', $hl7String);
        $str_hl7 = "";
        foreach ($str_arr as $segment) {
            $str_hl7 .= $segment . "\r\n";
        }

        // dd($str_hl7);
        $hl7controller = new HL7Controller();
        $report = $hl7controller->hl7Decode($str_hl7);
        // dd($report);

        if ($report['performed'][0] == "TNP") {
            return "Test not performed because " . $report['performed'][1];
        } else {
            // if($report['patient_matching']=="Patient matched"){
            return view('lab.quest.result_report', compact('report'));
            // }else{
            // return "Authorization Error";
            // }
        }
    }
    public function viewAllQuestRequisitions()
    {
        $user = auth()->user();
        // $requisitions=QuestLab::where('umd_patient_id',$user->id)->orderByDesc('id')->paginate(8);
        $requisitions = DB::table('quest_labs')
            ->join('quest_requests', 'quest_labs.order_id', '=', 'quest_requests.order_id')
            ->join('lab_orders', 'quest_requests.order_id', '=', 'lab_orders.sub_order_id')
            ->where('quest_labs.umd_patient_id', $user->id)
            ->groupBy('quest_requests.id')
            ->orderByDesc('quest_requests.id')->paginate(8);
        foreach ($requisitions as $requisition) {
            $dateTime = new \DateTime($requisition->created_at);
            $requisition->created_at = $dateTime->format('M, dS Y');
            $requisition->names = explode('","', $requisition->names);
            $temp = "";
            foreach ($requisition->names as $key => $name) {
                $requisition->names[$key] = trim($requisition->names[$key], '["');
                $requisition->names[$key] = trim($requisition->names[$key], '"]');
                $temp .= $requisition->names[$key] . ' , ';
            }
            $requisition->names = rtrim($temp, ", ");
            // dd($requisition);
        }
        // dd($requisitions);
        return view('lab.quest.all_requisitions', compact('requisitions'));
    }
    public function patient_viewAllQuestRequisitions()
    {
        $user = auth()->user();
        // $requisitions=QuestLab::where('umd_patient_id',$user->id)->orderByDesc('id')->paginate(8);
        $requisitions = DB::table('quest_labs')
            ->join('quest_requests', 'quest_labs.id', 'quest_requests.quest_lab_id')
            ->where('quest_labs.umd_patient_id', $user->id)
            ->select('quest_labs.*', 'quest_requests.requisition_file')
            ->groupBy('quest_requests.id')
            ->orderByDesc('quest_requests.id')
            ->paginate(9);
        foreach ($requisitions as $requisition) {
            $requisition->created_at = User::convert_utc_to_user_timezone($user->id, $requisition->created_at)['datetime'];
            $dateTime = new \DateTime($requisition->created_at);
            $requisition->created_at = $dateTime->format('M, dS Y');
            $requisition->names = json_decode($requisition->names);
            //dd(json_decode($requisition->names));
            // $requisition->names = explode('","', $requisition->names);
            // $temp = "";

            // foreach ($requisition->names as $key => $name) {
            //     $requisition->names[$key] = trim($requisition->names[$key], '["');
            //     $requisition->names[$key] = trim($requisition->names[$key], '"]');
            //     $temp .= $requisition->names[$key] . ' , ';
            // }
            // $requisition->names = rtrim($temp, ", ");
            // dd($requisition);
        }

        return view('dashboard_patient.Lab.requisition', compact('requisitions'));
    }
    public function doctor_viewAllQuestRequisitions()
    {
        $user = auth()->user();
        // $requisitions=QuestLab::where('umd_patient_id',$user->id)->orderByDesc('id')->paginate(8);
        $requisitions = DB::table('quest_labs')
            ->join('quest_requests', 'quest_labs.id', 'quest_requests.quest_lab_id')
            ->where('quest_labs.umd_patient_id', $user->id)
            ->select('quest_labs.*', 'quest_requests.requisition_file')
            ->groupBy('quest_requests.id')
            ->orderByDesc('quest_requests.id')
            ->paginate(9);
        foreach ($requisitions as $requisition) {
            $requisition->created_at = User::convert_utc_to_user_timezone($user->id, $requisition->created_at)['datetime'];
            $dateTime = new \DateTime($requisition->created_at);
            $requisition->created_at = $dateTime->format('M, dS Y');
            $requisition->names = json_decode($requisition->names);
            //dd(json_decode($requisition->names));
            // $requisition->names = explode('","', $requisition->names);
            // $temp = "";

            // foreach ($requisition->names as $key => $name) {
            //     $requisition->names[$key] = trim($requisition->names[$key], '["');
            //     $requisition->names[$key] = trim($requisition->names[$key], '"]');
            //     $temp .= $requisition->names[$key] . ' , ';
            // }
            // $requisition->names = rtrim($temp, ", ");
            // dd($requisition);
        }

        return view('dashboard_doctor.Lab.doctor_requisition', compact('requisitions'));
    }

    public function view_pending_requisitions()
    {
        $user = auth()->user();
        // $requisitions=QuestLab::where('umd_patient_id',$user->id)->orderByDesc('id')->paginate(8);
        $pending_requisitions = DB::table('lab_orders')
            ->join('quest_data_test_codes', 'lab_orders.product_id', 'quest_data_test_codes.TEST_CD')
            ->where('lab_orders.user_id', $user->id)
            ->where(function ($query) {
                return $query
                    ->where('lab_orders.status', 'lab-editor-approval')
                    ->orwhere('lab_orders.status', 'forwarded_to_doctor');
            })
            ->orderByDesc('lab_orders.order_id')
            ->groupBy('lab_orders.order_id')
            ->paginate(9);
        $pending_requisitions_test_name = DB::table('lab_orders')
            ->join('quest_data_test_codes', 'lab_orders.product_id', 'quest_data_test_codes.TEST_CD')
            ->where('lab_orders.user_id', $user->id)
            ->where(function ($query) {
                return $query
                    ->where('lab_orders.status', 'lab-editor-approval')
                    ->orwhere('lab_orders.status', 'forwarded_to_doctor');
            })
            ->orderByDesc('lab_orders.order_id')
            ->select('quest_data_test_codes.TEST_NAME', 'lab_orders.order_id')
            ->get()->toArray();

        foreach ($pending_requisitions as $requisition) {
            $requisition->date = User::convert_utc_to_user_timezone($user->id, $requisition->created_at);
            $requisition->date = $requisition->date['date'];
        }
        //dd($pending_requisitions);
        return view('dashboard_patient.Lab.pending_requisition', compact('pending_requisitions', 'pending_requisitions_test_name'));
    }

    public function view_doctor_pending_requisitions()
    {
        $user = auth()->user();
        // $requisitions=QuestLab::where('umd_patient_id',$user->id)->orderByDesc('id')->paginate(8);
        $pending_requisitions = DB::table('lab_orders')
            ->join('quest_data_test_codes', 'lab_orders.product_id', 'quest_data_test_codes.TEST_CD')
            ->where('lab_orders.user_id', $user->id)
            ->where(function ($query) {
                return $query
                    ->where('lab_orders.status', 'lab-editor-approval')
                    ->orwhere('lab_orders.status', 'forwarded_to_doctor');
            })
            ->orderByDesc('lab_orders.order_id')
            ->groupBy('lab_orders.order_id')
            ->paginate(9);
        $pending_requisitions_test_name = DB::table('lab_orders')
            ->join('quest_data_test_codes', 'lab_orders.product_id', 'quest_data_test_codes.TEST_CD')
            ->where('lab_orders.user_id', $user->id)
            ->where(function ($query) {
                return $query
                    ->where('lab_orders.status', 'lab-editor-approval')
                    ->orwhere('lab_orders.status', 'forwarded_to_doctor');
            })
            ->orderByDesc('lab_orders.order_id')
            ->select('quest_data_test_codes.TEST_NAME', 'lab_orders.order_id')
            ->get()->toArray();

        foreach ($pending_requisitions as $requisition) {
            $requisition->date = User::convert_utc_to_user_timezone($user->id, $requisition->created_at);
            $requisition->date = $requisition->date['date'];
        }
        //dd($pending_requisitions);
        return view('dashboard_doctor.Lab.doctor_pending_requisition', compact('pending_requisitions', 'pending_requisitions_test_name'));
    }

    public function fetchPendingResults()
    {
        // Fetching all reports
        $arr = ['resultServiceType' => 'hl7'];
        $json_req = json_encode($arr);
        // fetch all pending results on hub
        $response = $this->fetchCurlRequest($json_req);
        $hl7_obj = new HL7Controller();
        $result_obj = new QuestGetResultRequest();
        $result_obj->resultServiceType = $json_req;
        $result_obj->json_response = $response;
        $response_arr = json_decode($response, true);
        // Quest request ID=>contains different control id for each result
        $quest_request_id = $response_arr['requestId'];
        $hl7_ack_msgs = [];
        if ($response_arr['results'] != null) {
            // only save result object if there is any result
            $result_obj->save();
            foreach ($response_arr['results'] as $iteration => $result) {
                $control_id = $result['hl7Message']['controlId'];
                $hl7 = base64_decode($result['hl7Message']['message']);
                // decoded_hl7 contains arrays of used segments and error handling of order, patient and provider
                $decoded_hl7 = $hl7_obj->hl7Decode($hl7);
                // dd($decoded_hl7);
                // store report file and get name
                $report_filename = $this->get_report_filename($decoded_hl7['report_pdf'], $iteration);
                // success case:
                if (($decoded_hl7['patient_matching']['error_flag'] == 0) &&
                    ($decoded_hl7['provider_matching']['error_flag'] == 0) &&
                    ($decoded_hl7['order_matching']['error_flag'] == 0)
                ) {
                    $status = 'success';
                    $patient_matching = '';
                    $provider_matching = '';
                    $order_matching = '';
                    Log::channel('questResults')->info('Result file: ' . $report_filename . ' |quest_get_result_requests ' . $result_obj->id);
                } //error case
                else {
                    $status = 'error';
                    $patient_matching = serialize($decoded_hl7['patient_matching']);
                    $provider_matching = serialize($decoded_hl7['provider_matching']);
                    $order_matching = serialize($decoded_hl7['order_matching']);
                    Log::channel('questResults')->info('Error:: Result file: ' . $report_filename . ' |quest_get_result_requests ' . $result_obj->id);
                    Log::channel('questErrorLogging')->info('Error:: Result file: ' . $report_filename . ' |quest_get_result_requests ' . $result_obj->id);
                }
                $quest_result = QuestResult::create([
                    'get_request_id' => $result_obj->id,
                    'pat_first_name' => $decoded_hl7['patient_info']['fname'],
                    'pat_last_name' => $decoded_hl7['patient_info']['lname'],
                    'pat_id' => $decoded_hl7['patient_info']['pat_id'],
                    'pat_gender' => $decoded_hl7['patient_info']['gender'],
                    'pat_dob' => $decoded_hl7['patient_info']['dob'],
                    'doc_id' => $decoded_hl7['provider_matching']['ID'],
                    'doc_npi' => $decoded_hl7['provider_matching']['NPI'],
                    'doc_name' => $decoded_hl7['provider_matching']['name'],
                    'get_quest_request_id' => $quest_request_id,
                    'control_id' => $control_id,
                    'base64_message' => $result['hl7Message']['message'],
                    'hl7_message' => $hl7,
                    'file' => $report_filename,
                    'status' => $status,
                    'patient_matching' => $patient_matching,
                    'provider_matching' => $provider_matching,
                    'order_matching' => $order_matching,
                    'flag' => '0',
                ]);
                // create HL7 ACK for single received result
                $hl7_ack_msg = $hl7_obj->createACKmessage($control_id);
                $hl7_ack_msg = str_replace("\n", "\r", $hl7_ack_msg);
                $quest_result->ack = $hl7_ack_msg;
                $quest_result->save();
                // ACK message in quest format
                $ack = base64_encode($hl7_ack_msg);
                $arr = [
                    'message' => $ack,
                    'controlId' => $control_id,
                ];
                $patientInfo = DB::table('users')->where('id', $decoded_hl7['patient_info']['pat_id'])->first();
                $providerInfo = DB::table('users')->where('id', $decoded_hl7['provider_matching']['ID'])->first();
                $labAdminInfo = DB::table('users')->where('user_type', 'admin_lab')->first();

                $patientMarkDown = [
                    'pat_name' => $patientInfo->name,
                    'pat_email' => $patientInfo->email,
                ];

                $providerMarkDown = [
                    'doc_name' => $providerInfo->name,
                    'doc_email' => $providerInfo->email,
                ];

                $labAdminMarkDown = [
                    'labAdmin_name' => $labAdminInfo->name,
                    'labAdmin_email' => $labAdminInfo->email,
                ];

                // Mail::to('baqir.redecom@gmail.com')->send(new labTestResultPatientMail($patientMarkDown));
                // Mail::to('baqir.redecom@gmail.com')->send(new labTestResultDoctorMail($providerMarkDown));
                // Mail::to('baqir.redecom@gmail.com')->send(new labTestResultLabAdminMail($labAdminMarkDown));

                Mail::to($patientInfo->email)->send(new labTestResultPatientMail($patientMarkDown));
                Mail::to($providerInfo->email)->send(new labTestResultDoctorMail($providerMarkDown));
                Mail::to($labAdminInfo->email)->send(new labTestResultLabAdminMail($labAdminMarkDown));
                // send Quest reult to patient and doctor -----disabled
                // $hl7_obj->sendReportMail($quest_result,$decoded_hl7['patient_info']['pat_id'],$decoded_hl7['provider_matching']['ID']);
                array_push($hl7_ack_msgs, $arr);
            }
        } else {
            return "No results";
        }
        //    dd($hl7_ack_msgs);
        // $json_req = json_encode($hl7_ack_msgs);

        // send collection of ACK messages to Quest
        $this->sendACKtoQuest($hl7_ack_msgs, $quest_request_id, $result_obj);
        return 'done';
    }
    public function sendACKtoQuest($hl7_ack_msgs, $quest_request_id, $result_obj)
    {
        $arr = [
            'resultServiceType' => 'hl7',
            'requestId' => $quest_request_id,
            'ackMessages' => $hl7_ack_msgs,
        ];
        $json_req = json_encode($arr);
        $result_obj->json_ack = $json_req;
        $result_obj->save();
        // dd($json_req);
        $response = $this->sendACKCurlRequest($json_req);
        // dd($response);
    }

    public function pat_lab_report($patient_id = null)
    {
        if (auth()->user()->user_type == 'patient') {
            $reports = QuestResult::where('pat_id', auth()->user()->id)
                ->where('status', 'success')
                ->orwhere('status', 'error')
                ->orderByDesc('id')
                ->paginate(8);
        } else if (auth()->user()->user_type == 'doctor') {
            $reports = QuestResult::where('doc_id', auth()->user()->id)
                ->where('pat_id', $patient_id)
                ->orwhere('status', 'error')
                ->where('status', 'success')
                ->orderByDesc('id')
                ->paginate(8);
        }
        // dd($reports);
        $hl7_obj = new HL7Controller();
        foreach ($reports as $report) {
            $decoded = $hl7_obj->hl7Decode($report->hl7_message);
            $report->type = $decoded['result_type'];
            $report->doctor = User::getName($report->doc_id);
            $report->patient = User::getName($report->pat_id);
            $test_names = [];
            foreach ($decoded['arrOBR'] as $obr) {
                if (!in_array($obr['name'], $test_names)) {
                    array_push($test_names, $obr['name']);
                }
            }
            $sp_date = explode(' ', $decoded['arrOBR'][0]['specimen_collection_date']);
            $res_date = date('m/d/Y', strtotime($report->created_at));
            $report->specimen_date = $sp_date[0];
            $report->result_date = $res_date;
            // $report->order_date=$report->created_at;
            // dd($res_date.' :: '.$sp_date[0]);
            end($test_names); // move the internal pointer to the end of the array
            $key = key($test_names); // fetches the key of the element pointed to by the internal pointer

            // var_dump($key);
            $all_test_names = '';
            foreach ($test_names as $k => $test) {
                if ($k != $key) {
                    $all_test_names .= $test;
                }
            }
            $report->test_names = $all_test_names;

            // dd($report);
        }
        return view('dashboard_doctor.Lab.pat_lab_report', compact('reports'));
    }

    public function lab_reports()
    {
        if (auth()->user()->user_type == 'admin_lab') {
            $reports = QuestResult::where('status', 'success')
                ->orwhere('status', 'error')
                ->orderByDesc('created_at')
                ->paginate(8);
        } else {
            return redirect()->back();
        }
        // dd($reports);
        $hl7_obj = new HL7Controller();
        foreach ($reports as $report) {
            $report->condition = 0;
            $decoded = $hl7_obj->hl7Decode($report->hl7_message);
            $report->type = $decoded['result_type'];
            $report->doctor = User::getName($report->doc_id);
            $report->patient = User::getName($report->pat_id);
            $report->pat_email = User::getEmail($report->pat_id);
            $report->doc_email = User::getEmail($report->doc_id);
            $test_names = [];
            foreach ($decoded['arrOBR'] as $obr) {
                if (!in_array($obr['name'], $test_names)) {
                    array_push($test_names, $obr['name']);
                }
            }
            //dd($decoded['arrOBX'][0]);
            foreach ($decoded['arrOBX'][0]['OBX'] as $obx) {
                if ($obx['Results'] != "NOT APPLICABLE" && $obx['Abnormal'] != "N") {
                    $report->condition = 1;
                }
            }
            $sp_date = explode(' ', $decoded['arrOBR'][0]['specimen_collection_date']);
            $res_date = date('m/d/Y', strtotime($report->created_at));
            $report->specimen_date = $sp_date[0];
            $report->result_date = $res_date;
            // $report->order_date=$report->created_at;
            // dd($res_date.' :: '.$sp_date[0]);
            end($test_names); // move the internal pointer to the end of the array
            $key = key($test_names); // fetches the key of the element pointed to by the internal pointer

            // var_dump($key);
            $all_test_names = '';
            foreach ($test_names as $k => $test) {
                if ($k != $key) {
                    $all_test_names .= $test;
                }
            }
            $report->test_names = $all_test_names;

            // dd($report);
        }
        return view('dashboard_Lab_admin.Patient_lab_reports.lab_reports', compact('reports'));
    }
    public function admin_lab_reports()
    {
        if (auth()->user()->user_type == 'admin') {
            $reports = QuestResult::where('status', 'success')
                ->orwhere('status', 'error')
                ->orderByDesc('created_at')
                ->paginate(8);
        } else {
            return redirect()->back();
        }
        // dd($reports);
        $hl7_obj = new HL7Controller();
        foreach ($reports as $report) {
            $report->condition = 0;
            $decoded = $hl7_obj->hl7Decode($report->hl7_message);
            $report->type = $decoded['result_type'];
            $report->doctor = User::getName($report->doc_id);
            $report->patient = User::getName($report->pat_id);
            $report->pat_email = User::getEmail($report->pat_id);
            $report->doc_email = User::getEmail($report->doc_id);
            $test_names = [];
            foreach ($decoded['arrOBR'] as $obr) {
                if (!in_array($obr['name'], $test_names)) {
                    array_push($test_names, $obr['name']);
                }
            }
            //dd($decoded['arrOBX'][0]);
            foreach ($decoded['arrOBX'][0]['OBX'] as $obx) {
                if ($obx['Results'] != "NOT APPLICABLE" && $obx['Abnormal'] != "N") {
                    $report->condition = 1;
                }
            }
            $sp_date = explode(' ', $decoded['arrOBR'][0]['specimen_collection_date']);
            $res_date = date('m/d/Y', strtotime($report->created_at));
            $report->specimen_date = $sp_date[0];
            $report->result_date = $res_date;
            // $report->order_date=$report->created_at;
            // dd($res_date.' :: '.$sp_date[0]);
            end($test_names); // move the internal pointer to the end of the array
            $key = key($test_names); // fetches the key of the element pointed to by the internal pointer

            // var_dump($key);
            $all_test_names = '';
            foreach ($test_names as $k => $test) {
                if ($k != $key) {
                    $all_test_names .= $test;
                }
            }
            $report->test_names = $all_test_names;

            // dd($report);
        }
        return view('dashboard_admin.lab_reports.index', compact('reports'));
    }

    public function lab_reports_view_patient($id)
    {
        if($id!=null){
            $user = DB::table('users')->where('id', $id)->first();
            if(isset($user->id)){
                if($id==$user->id){
                    if($user->user_type=='patient'){
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

                      return view('dashboard_Lab_admin.Patient_lab_reports.patient_profile', compact('patient'));
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
    public function lab_reports_view_doctor($id)
    {
        if ($id != null) {
            $user = DB::table('users')->where('id', $id)->first();
            if (isset($user->id)) {
                if ($id == $user->id) {
                    $doctor = $user;
                    // doctor profile picture from S3

                    $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);
                    $doctor->city = City::find($doctor->city_id);
                    $doctor->state = State::find($doctor->state_id);
                    $doctor->country = Country::find($doctor->country_id);
                    $doctor->spec = Specialization::find($doctor->specialization);
                    $doctor->license = DB::table('doctor_licenses')
                        ->join('states', 'states.id', 'doctor_licenses.state_id')
                        ->where('doctor_id', $id)
                        ->where('is_verified', 1)
                        ->select('states.name')->get();
                    // doctor certificates
                    $doctor->certificates = DB::table('doctor_certificates')->where('doc_id', $id)->get();
                    foreach ($doctor->certificates as $cert) {
                        if ($cert->certificate_file != "") {
                            $cert->certificate_file = \App\Helper::get_files_url($cert->certificate_file);
                        }
                    }
                    $doctor->all_patients = DB::table('sessions')->where('sessions.doctor_id', auth()->user()->id)
                        ->groupBy('sessions.patient_id')
                        ->where('sessions.status', '!=', 'pending')
                        ->join('users', 'sessions.patient_id', '=', 'users.id')
                        ->select('users.*')
                        ->get();

                    foreach ($doctor->all_patients as $patient) {
                        $patient->user_image = \App\Helper::check_bucket_files_url($patient->user_image);
                    }
                    return view('dashboard_Lab_admin.Patient_lab_reports.doctor_profile', compact('doctor'));
                } else {
                    return view('errors.404');
                }
            }
        }
    }

    public function patient_lab_reports($patient_id = null)
    {
        if (auth()->user()->user_type == 'patient') {
            $reports = QuestResult::where('pat_id', auth()->user()->id)
                ->where('status', 'success')
                ->orderByDesc('id')
                ->paginate(8);
        } else if (auth()->user()->user_type == 'doctor') {
            $reports = QuestResult::where('doc_id', auth()->user()->id)
                ->where('pat_id', $patient_id)
                ->where('status', 'success')
                ->orderByDesc('id')
                ->paginate(8);
        }
        // dd($reports);
        $hl7_obj = new HL7Controller();
        foreach ($reports as $report) {
            $decoded = $hl7_obj->hl7Decode($report->hl7_message);
            $report->type = $decoded['result_type'];
            $report->doctor = User::getName($report->doc_id);
            $report->patient = User::getName($report->pat_id);
            $test_names = [];
            foreach ($decoded['arrOBR'] as $obr) {
                if (!in_array($obr['name'], $test_names)) {
                    array_push($test_names, $obr['name']);
                }
            }
            $sp_date = explode(' ', $decoded['arrOBR'][0]['specimen_collection_date']);
            $res_date = date('m/d/Y', strtotime($report->created_at));
            $report->specimen_date = $sp_date[0];
            $report->result_date = $res_date;
            // $report->order_date=$report->created_at;
            // dd($res_date.' :: '.$sp_date[0]);
            end($test_names); // move the internal pointer to the end of the array
            $key = key($test_names); // fetches the key of the element pointed to by the internal pointer

            // var_dump($key);
            $all_test_names = '';
            foreach ($test_names as $k => $test) {
                if ($k != $key) {
                    $all_test_names .= $test;
                }
            }
            $report->test_names = $all_test_names;

            // dd($report);
        }
        return view('lab.quest.patient_lab_reports', compact('reports'));
    }

    public function patient_view_lab_report($id)
    {
        if ($id != null) {
            DB::table('quest_results')->where('id', $id)->update(['is_read' => 1]);
            $url = DB::table('quest_results')->where('id', $id)->first();
            return redirect(url(\App\Helper::get_files_url($url->file)));
        } else {
            return redirect(url()->previous());
        }
    }

    public function patient_dash_lab_reports($patient_id = null)
    {
        if (auth()->user()->user_type == 'patient') {
            // $reports = QuestResult::where('pat_id', auth()->user()->id)
            //     ->where('status', 'success')
            //     ->orWhere('status', 'error')
            //     ->orderByDesc('id')
            //     ->paginate(8);
            $reports = QuestResult::where('pat_id', auth()->user()->id)
                ->where(function ($query) {
                    return $query
                        ->where('status', 'success')
                        ->orWhere('status', 'error');
                })
                ->orderByDesc('id')
                ->paginate(8);
        } else if (auth()->user()->user_type == 'doctor') {
            $reports = QuestResult::where('doc_id', auth()->user()->id)
                ->where('pat_id', $patient_id)
                ->where('status', 'success')
                ->orwhere('status', 'error')
                ->orderByDesc('id')
                ->paginate(8);
        }
        // $hl7_obj = new HL7Controller();
        // foreach ($reports as $report) {
        //     $decoded = $hl7_obj->hl7Decode($report->hl7_message);
        //     $report->type = $decoded['result_type'];
        //     $report->doctor = User::getName($report->doc_id);
        //     $report->patient = User::getName($report->pat_id);
        //     $test_names = [];
        //     foreach ($decoded['arrOBR'] as $obr) {
        //         if (!in_array($obr['name'], $test_names)) {
        //             array_push($test_names, $obr['name']);
        //         }
        //     }
        //     $sp_date = explode(' ', $decoded['arrOBR'][0]['specimen_collection_date']);
        //     $res_date = date('m/d/Y', strtotime($report->created_at));
        //     $report->specimen_date = $sp_date[0];
        //     $report->result_date = $res_date;
        //     // $report->order_date=$report->created_at;
        //     // dd($res_date.' :: '.$sp_date[0]);
        //     end($test_names); // move the internal pointer to the end of the array
        //     $key = key($test_names); // fetches the key of the element pointed to by the internal pointer

        //     // var_dump($key);
        //     $all_test_names = '';
        //     foreach ($test_names as $k => $test) {
        //         if ($k != $key) {
        //             $all_test_names .= $test;
        //         }
        //     }
        //     $report->test_names = $all_test_names;

        //     // dd($report);
        // }
        return view('dashboard_patient.Lab.index', compact('reports'));
    }

    public function all_test()
    {
        $labs = QuestDataTestCode::whereRaw("TEST_CD NOT LIKE '#%%' ESCAPE '#'")
        // ->whereIn('id', ['3327', '4029', '1535', '3787', '47', '1412',
        //     '1484', '1794', '3194', '3352', '3566', '3769',
        //     '4446', '18811', '11363', '899', '16846', '3542',
        //     '229', '747', '6399', '7573', '16814',
        // ])
            ->where('TEST_CD', '!=', '92613')
            ->where('TEST_CD', '!=', '11196')
            ->where('LEGAL_ENTITY', 'DAL')
            ->orWhere('PRICE', '!=', null)
            ->get();
        dd($labs);
    }
    public function failed_requests()
    {
        if (auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'admin_lab') {
            $results = QuestResult::where('status', '!=', 'success')
                ->where('status', '!=', 'resolved')
                ->get();
            $hl7_obj = new HL7Controller();
            foreach ($results as $result) {
                $decoded = $hl7_obj->hl7Decode($result->hl7_message);
                $result->patient_matching = $decoded['patient_matching'];
                $result->placer_order_num = $decoded['arrOBR'][0]['placer_order_num'];
                $result->filler_order_num = $decoded['arrOBR'][0]['filler_order_num'];
            }
            // dd($results);

            return view('lab.quest.quest_failed_requests', compact('results'));
        } else {
            return redirect()->route('home');
        }
    }

    public function dash_failed_requests()
    {
        if (auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'admin_lab') {
            $results = QuestResult::where('status', '!=', 'success')
                ->where('status', '!=', 'resolved')
                ->paginate(10);
            $hl7_obj = new HL7Controller();
            foreach ($results as $result) {
                $decoded = $hl7_obj->hl7Decode($result->hl7_message);
                $result->patient_matching = $decoded['patient_matching'];
                $result->placer_order_num = $decoded['arrOBR'][0]['placer_order_num'];
                $result->filler_order_num = $decoded['arrOBR'][0]['filler_order_num'];
            }
            // dd($results);

            return view('dashboard_admin.Quest_orders.failed', compact('results'));
        } else {
            return redirect()->route('home');
        }
    }
    public function resolve_request($id)
    {
        QuestResult::find($id)->update(['status' => 'resolved']);
        return redirect()->route('quest_failed_requests');
    }
    public function fetchCurlRequest($json_req)
    {
        if (env('APP_TYPE') == 'production') {
            $url = 'https://hubservices.quanum.com/rest/results/v1/retrieval/getResults';
            $token = env('PRODUCTION_QUEST_TOKEN');
        } else {
            $url = 'https://certhubservices.quanum.com/rest/results/v1/retrieval/getResults';
            $token = env('QUEST_TOKEN');
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $json_req,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic ' . $token,
                'Content-Type: application/json',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
    public function sendACKCurlRequest($json_req)
    {
        if (env('APP_TYPE') == 'production') {
            $url = 'https://hubservices.quanum.com/rest/results/v1/retrieval/acknowledgeResults';
            $token = env('PRODUCTION_QUEST_TOKEN');
        } else {
            $url = 'https://certhubservices.quanum.com/rest/results/v1/retrieval/acknowledgeResults';
            $token = env('QUEST_TOKEN');
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $json_req,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic ' . $token,
                'Content-Type: application/json',
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
    public function get_report_filename($report_pdf, $iteration)
    {
        $pdf_decoded = base64_decode($report_pdf);
        $timestamp = time();
        $file_name = 'lab_reports/' . $timestamp . '.pdf';
        $status = \Storage::disk('s3')->put($file_name, $pdf_decoded);
        if ($status) {
            return $file_name;
        } else {
            return $status;
        }

        // $timestamp = time() . $iteration;
        // //Write data back to pdf file
        // try {
        //     if($_SERVER['SERVER_NAME']=='umbrellamd-video.com'){
        //         $pdf = fopen('/var/www/html/umbrellamd/public/uploads/lab_reports/' . $timestamp . '.pdf', 'w');
        //     }else if($_SERVER['SERVER_NAME']=='demo.umbrellamd-video.com'){
        //         $pdf = fopen('/var/www/html/umbrellamd-demo/public/uploads/lab_reports/' . $timestamp . '.pdf', 'w');
        //     }else if($_SERVER['SERVER_NAME']=='umbrellamd.com'){
        //         $pdf = fopen('/var/www/html/umbrellamd8.0/public/uploads/lab_reports/' . $timestamp . '.pdf', 'w');
        //     }else{
        //         $pdf = fopen('uploads/lab_reports/' . $timestamp . '.pdf', 'w');
        //     }
        //     fwrite($pdf, $pdf_decoded);
        //     //close output file
        //     fclose($pdf);
        // } catch (ErrorException $e) {
        //     Log::channel('questResults')->info('Error saving file');
        // }
        // $file_name = $timestamp . '.pdf';
    }
    public function show_failed_requests($result_id)
    {
        if (auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'admin_lab') {
            $result = QuestResult::find($result_id);
            // dd($result->provider_matching);
            $result->patient_matching = unserialize($result->patient_matching);
            $result->provider_matching = unserialize($result->provider_matching);
            $result->order_matching = unserialize($result->order_matching);
            // dd($result->order_matching);
            return view('lab.quest.show_quest_failed_requests', compact('result'));
        } else {
            return redirect()->route('home');
        }
    }
    public function doctor_lab_patients()
    {
        $user = auth()->user();
        if ($user->user_type == 'doctor') {
            $patients = QuestResult::where('doc_id', $user->id)
                ->groupBy('pat_id')
                ->orderByDesc('id')
                ->where('status', 'success')
                ->paginate(8);
            // dd($patients);
            return view('lab.quest.results_table', compact('patients'));
        } else {
            return redirect()->route('home');
        }
    }
    public function all_doctor_lab_reports()
    {
        $user = auth()->user();
        if ($user->user_type == 'doctor') {
            $patients = DB::table('quest_results')
                ->join('users', 'quest_results.pat_id', 'users.id')
                ->where('quest_results.doc_id', $user->id)
                ->groupBy('quest_results.pat_id')
                ->orderByDesc('quest_results.id')
                ->where('quest_results.status', 'success')
                ->select('quest_results.*', 'users.email')
                ->paginate(8);
            // $patients=QuestResult::where('doc_id',$user->id)
            //                     ->groupBy('pat_id')
            //                     ->orderByDesc('id')
            //                     ->where('status','success')
            //                     ->paginate(8);
            //dd($patients);
            return view('dashboard_doctor.Lab.leb_reports', compact('patients'));
        } else {
            return redirect()->route('home');
        }
    }
}
