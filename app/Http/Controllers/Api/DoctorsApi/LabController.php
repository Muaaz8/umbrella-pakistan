<?php

namespace App\Http\Controllers\Api\DoctorsApi;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use DB;
use App\QuestResult;
use Auth;
use App\User;
use App\VendorAccount;
use App\State;
use App\QuestLab;

class LabController extends BaseController
{
    public function all_doctor_lab_reports(){
        $user = auth()->user();
        $patients = DB::table('quest_results')
                ->join('users', 'quest_results.pat_id', 'users.id')
                ->where('quest_results.doc_id', $user->id)
                ->groupBy('quest_results.pat_id')
                ->orderByDesc('quest_results.id')
                ->where('quest_results.status', 'success')
                ->select('quest_results.*', 'users.email')
                ->get();
        if(!$patients->isEmpty()){
            $patientData['code'] = 200;
            $patientData['patients'] = $patients;
            return $this->sendResponse($patients,"Patients Reports");
        } else{
            $patientDataError['code'] = 200;
            return $this->sendError($patientDataError,"No Reports Found");
        }
    }
    public function pat_lab_report($id){
        $reports = QuestResult::where('doc_id', auth()->user()->id)
                ->where('pat_id', $id)
                ->where('status', 'success')
                ->orderByDesc('id')
                ->get();
        if(!$reports->isEmpty()){
            $lab_reports['code'] = 200;
            $lab_reports['reports'] = $reports;
            return $this->sendResponse($lab_reports,"Patient Reports");
        } else{
            $lab_reportsError['code'] = 200;
            return $this->sendError($lab_reportsError,"No Reports Found");
        }
    }
    public function doctor_online_lab_approvals(){
        $pending_requisitions = DB::table('lab_orders')
            ->join('quest_data_test_codes', 'lab_orders.product_id', 'quest_data_test_codes.TEST_CD')
            ->where('lab_orders.status', 'forwarded_to_doctor')
            ->where('lab_orders.doc_id', Auth::user()->id)
            ->orderByDesc('lab_orders.order_id')
            ->groupBy('lab_orders.order_id')
            ->paginate(9);
        $pending_requisitions_test_name = DB::table('lab_orders')
            ->join('quest_data_test_codes', 'lab_orders.product_id', 'quest_data_test_codes.TEST_CD')
            ->where('lab_orders.status', 'forwarded_to_doctor')
            ->where('lab_orders.doc_id', Auth::user()->id)
            ->orderByDesc('lab_orders.order_id')
            ->select('quest_data_test_codes.TEST_NAME', 'lab_orders.order_id')
            ->get()->toArray();
        foreach ($pending_requisitions as $requisition) {
            $requisition->date = User::convert_utc_to_user_timezone($requisition->user_id, $requisition->created_at);
            $requisition->date = $requisition->date['date'];
            $user = User::find($requisition->user_id);
            $state = State::find($user->state_id);
            $requisition->user_state = $state->name;
            $doctors = DB::table('doctor_licenses')
                ->join('users', 'users.id', 'doctor_licenses.doctor_id')
                ->where('doctor_licenses.state_id', $user->state_id)
                ->select('users.name', 'users.last_name', 'users.id as user_id')
                ->get()->toArray();
            $requisition->doctors = $doctors;

            if ($requisition->doc_id != null) {
                $doc = User::find($requisition->doc_id);
                $requisition->decline_status = "Approval Requested To Dr." . $doc->name . ' ' . $doc->last_name;
            } else {
                $requisition->decline_status = "Not assigned to any doctor yet";
            }
        }
        $pending_onlineTest['code'] =200;
        $pending_onlineTest['pending_requisitions'] =$pending_requisitions;
        $pending_onlineTest['pending_requisitions_test_name'] =$pending_requisitions_test_name;
        return $this->sendResponse($pending_onlineTest,"Pending Online Labs");
    }
    public function approved_labs(){
        $user = Auth::user();
        $orders = DB::table('lab_orders')
            ->join('quest_data_test_codes', 'lab_orders.product_id', 'quest_data_test_codes.TEST_CD')
            ->where('lab_orders.status', 'essa-forwarded')
            ->where('lab_orders.type', 'Counter')
            ->where('lab_orders.doc_id', $user->id)
            ->orderByDesc('lab_orders.order_id')
            ->groupBy('lab_orders.order_id')
            ->select('lab_orders.order_id','lab_orders.created_at')
            ->paginate(10);
        // dd($orders);
        // $orders_test_name = DB::table('lab_orders')
        //     ->join('quest_data_test_codes', 'lab_orders.product_id', 'quest_data_test_codes.TEST_CD')
        //     ->where('lab_orders.status', 'essa-forwarded')
        //     ->where('lab_orders.type', 'Counter')
        //     ->where('doc_id', $user->id)
        //     ->orderByDesc('lab_orders.order_id')
        //     ->select('quest_data_test_codes.TEST_NAME', 'lab_orders.order_id')
        //     ->get()->toArray();
        foreach ($orders as $order) {
            $order->created_at = User::convert_utc_to_user_timezone($user->id, $order->created_at)['datetime'];
            $order->created_at = date("m-d-Y h:iA", strtotime($order->created_at));
            $labs = DB::table('lab_orders')
            ->join('quest_data_test_codes', 'lab_orders.product_id', 'quest_data_test_codes.TEST_CD')
            ->where('lab_orders.status', 'essa-forwarded')
            ->where('lab_orders.type', 'Counter')
            ->select('quest_data_test_codes.TEST_NAME')
            ->where('lab_orders.order_id', $order->order_id)->get()->toArray();
            $order->lab_test_name =$labs;
        }
        $approved_labs['code'] = 200;
        $approved_labs['orders'] = $orders;
        // $approved_labs['orders_test_name'] = $orders_test_name;
        return $this->sendResponse($approved_labs,"Approved Labs");
    }
    public function doctor_pending_requisitions(){
        $user = auth()->user();
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
            ->select( 'lab_orders.order_id','lab_orders.date','lab_orders.status')
            ->paginate(10);

            foreach($pending_requisitions as $pr)
            {
                $labs = DB::table('lab_orders')
                ->join('quest_data_test_codes', 'lab_orders.product_id', 'quest_data_test_codes.TEST_CD')
                ->where('lab_orders.order_id', $pr->order_id)
                ->select('quest_data_test_codes.TEST_NAME', 'lab_orders.sub_order_id','lab_orders.status')
                ->get()->toArray();
                $pr->this_order_labs = $labs;
            }
        $doc_requisitions['code'] = 200;
        $doc_requisitions['pending_requisitions_test_name'] = $pending_requisitions;
        return $this->sendResponse($doc_requisitions,'Doctor Requisiitons');
    }
    public function doc_lab_requisitions(){
        $user = auth()->user();
        $requisitions = DB::table('quest_labs')
            ->join('quest_requests', 'quest_labs.id', 'quest_requests.quest_lab_id')
            ->where('quest_labs.umd_patient_id', $user->id)
            ->select('quest_labs.*', 'quest_requests.requisition_file')
            ->groupBy('quest_requests.id')
            ->orderByDesc('quest_requests.id')
            ->paginate(10);
        if($requisitions->isEmpty()){
            $doc_lab_requisitions['code'] = 200;
            return $this->sendResponse($doc_lab_requisitions,"No Doctor Lab Requisitions Found");
        }else{
            foreach ($requisitions as $requisition) {
                $requisition->created_at = User::convert_utc_to_user_timezone($user->id, $requisition->created_at)['datetime'];
                $dateTime = new \DateTime($requisition->created_at);
                $requisition->created_at = $dateTime->format('M, dS Y');
                $requisition->names = json_decode($requisition->names);
            }
            $doc_lab_reports['code'] = 200;
            $doc_lab_reports['requisitions'] = $requisitions;
            return $this->sendResponse($requisitions,"Doctor Lab Requisitions");
        }
    }
    public function online_lab_order_accept(Request $request){
        $testsData = [];
        $item = DB::table('lab_orders')
            ->join('quest_data_test_codes', 'lab_orders.product_id', 'quest_data_test_codes.TEST_CD')
            ->where('lab_orders.order_id', $request->order_id)
            ->where('status','forwarded_to_doctor')
            ->where('lab_orders.type', 'Counter')
            ->get();
        foreach ($item as $i) {
            array_push($testsData, ['testCode' => $i->product_id, 'testName' => $i->TEST_NAME, 'aoes' => '']);
        }
        $account = VendorAccount::where('vendor', 'quest')->first();
        $doctor = User::find($item[0]->doc_id);
        $patient = User::find($item[0]->user_id);

        $timestamp = time();
        $lab_ref_num = 'UMD' . $item[0]->user_id . 'Q' . $timestamp;
        $orderedtestcode = json_encode($testsData);
        $name = json_encode($testsData);
        $testAoes = json_encode($testsData);
        $collect_date = date('Y-m-d', strtotime($item[0]->created_at));
        $collect_time = date('H:i:s', strtotime($item[0]->created_at));
        $doc_name = $doctor->last_name . ' ,' . $doctor->name;
        $barcode = $account->number . $lab_ref_num;
        $arr_specimen = array(
            [
                'client_num' => '73917104',
                'lab_referance' => $lab_ref_num,
                'patient_name' => $patient->last_name . ', ' . $patient->name,
                'barcode' => $account->number . $lab_ref_num,
            ],
        );
        $specimen_labels = json_encode($arr_specimen);
        $comment = '';
        $client_bill = '$2y$10$iguHq2BCqFaGg1tI3eZDWujOwENMEmJDYdA7Ywl11Iwv1r/NNmmgu';
        $patient_bill = '';
        $third_party_bill = '';
        $order = QuestLab::create([
            'order_id' => $item[0]->order_id,
            'umd_patient_id' => $item[0]->user_id,
            'quest_patient_id' => $item[0]->user_id,
            'abn' => '',
            'billing_type' => 'Client',
            'diagnosis_code' => 'V725',
            'vendor_account_id' => $account->id,
            'orderedtestcode' => $orderedtestcode, 'names' => $name, 'aoe' => $testAoes, 'collect_date' => $collect_date,
            'collect_time' => $collect_time, 'lab_reference_num' => $lab_ref_num, 'npi' => $doctor->nip_number,
            'ssn' => '', 'insurance_num' => '', 'room' => '', 'result_notification' => 'Normal',
            'group_num' => '', 'relation' => 'Self', 'upin' => $doctor->upin, 'ref_physician_id' => $doc_name,
            'temp' => '', 'icd_diagnosis_code' => '', 'psc_hold' => 1, 'barcode' => $barcode,
            'specimen_labels' => $specimen_labels, 'comment' => $comment, 'client_bill' => $client_bill,
            'patient_bill' => $patient_bill, 'third_party_bill' => $third_party_bill,
        ]);

        $order->zip_code = $patient->zip_code;
        $hl7_obj = new \App\Http\Controllers\HL7Controller();
        $hl7_obj->new_hl7Encode($order);
        DB::table('lab_orders')
            ->where('order_id', $request->order_id)
            ->where('type', 'Counter')
            ->where('status','forwarded_to_doctor')
            ->update(['status' => 'essa-forwarded']);
            $online_order['code'] = 200;
            $online_order['online_lab_id'] = $request->order_id;
        return $this->sendResponse($online_order,"Online Lab Order Accepted");
    }
    public function online_lab_order_cancel(Request $request){
        DB::table('lab_orders')->where('order_id', $request->order_id)->update(['status' => 'lab-editor-approval']);
        $online_order['code'] = 200;
        $online_order['order_id'] = $request->order_id;
        return $this->sendResponse($online_order,"Online Lab Order Canceled");
    }
    public function recent_lab_buy(){
        $id = Auth::user()->id;
        $lab_orders = DB::table('lab_orders')->where('user_id',$id)->pluck('product_id');
        $lab_name = DB::table('quest_data_test_codes')->whereIn('TEST_CD',$lab_orders)->orderBy('id', 'DESC')->get();
        $recent_labs['code'] = 200;
        $recent_labs['lab_name'] = $lab_name;
        return $this->sendResponse($recent_labs,"Doctor Recent Labs");
    }
    public function doc_popular_lab_test(){
        $lab_orders = DB::table('lab_orders as lo')
            ->join('quest_data_test_codes as qcode','qcode.TEST_CD','=','lo.product_id')
            ->select('qcode.TEST_NAME','qcode.PRICE', DB::raw('count(*) as popularity'))
            ->groupBy('lo.product_id')
            ->where('lo.user_id','=',Auth::user()->id)
            ->orderBy('popularity','DESC')
            ->get();
        $popular['code'] = 200;
        $popular['popular_lab'] = $lab_orders;
        return $this->sendResponse($popular,"List of popular labs");
    }
    public function lab_requisition_search(Request $request){
        $search = $request->order_id;
        $user = auth()->user();
        $requisitions = DB::table('quest_labs')
            ->join('quest_requests', 'quest_labs.id', 'quest_requests.quest_lab_id')
            ->where('quest_labs.umd_patient_id', $user->id)
            ->where('quest_requests.order_id', $search)
            ->select('quest_labs.*', 'quest_requests.requisition_file')
            ->groupBy('quest_requests.id')
            ->orderByDesc('quest_requests.id')
            ->get();
        foreach ($requisitions as $requisition) {
            $requisition->created_at = User::convert_utc_to_user_timezone($user->id, $requisition->created_at)['datetime'];
            $dateTime = new \DateTime($requisition->created_at);
            $requisition->created_at = $dateTime->format('M, dS Y');
            $requisition->names = json_decode($requisition->names);
            $requisition->LabFile =  \App\Helper::get_files_url($requisition->requisition_file);
        }
        if(!$requisitions->isEmpty()){
            $Datarequisitions['code']= 200;
            $Datarequisitions['requisitions']= $requisitions;
            return $this->sendResponse($Datarequisitions,"Lab Requisitions List");
        } else{
            $Datarequisitions['code']= 200;
            return $this->sendError($Datarequisitions,"No Lab Requisitions List");
        }

    }
    public function doc_lab_requisition_pending_search(Request $request){
        $user = auth()->user();
        $search = $request->lab_name;
        $pending_requisitions = DB::table('lab_orders')
            ->join('quest_data_test_codes', 'lab_orders.product_id', 'quest_data_test_codes.TEST_CD')
            ->where('quest_data_test_codes.TEST_NAME','LIKE', '%'.$search.'%')
            ->where('lab_orders.user_id', $user->id)
            ->where(function ($query) {
                return $query
                    ->where('lab_orders.status', 'lab-editor-approval')
                    ->orwhere('lab_orders.status', 'forwarded_to_doctor');
            })
            ->orderByDesc('lab_orders.order_id')
            ->groupBy('lab_orders.order_id')
            ->get();
        foreach ($pending_requisitions as $requisition) {
            $requisition->date = User::convert_utc_to_user_timezone($user->id, $requisition->created_at);
            $requisition->date = $requisition->date['date'];
        }
        $requisitionData['code'] = 200;
        $requisitionData['pending_requisitions'] = $pending_requisitions;
        return $this->sendResponse($requisitionData,"Requisition Pending");
    }
    public function approved_labs_search(Request $request){
        $user = Auth::user();
        $search = $request->lab_name;
        $orders = DB::table('lab_orders')
            ->join('quest_data_test_codes', 'lab_orders.product_id', 'quest_data_test_codes.TEST_CD')
            ->where('lab_orders.status', 'essa-forwarded')
            ->where('quest_data_test_codes.TEST_NAME','LIKE', '%'.$search.'%')
            ->where('lab_orders.type', 'Counter')
            ->where('lab_orders.doc_id', $user->id)
            ->orderByDesc('lab_orders.order_id')
            ->groupBy('lab_orders.order_id')
            ->select('lab_orders.order_id','lab_orders.created_at')
            ->paginate(10);
        foreach ($orders as $order) {
            $order->created_at = User::convert_utc_to_user_timezone($user->id, $order->created_at)['datetime'];
            $order->created_at = date("m-d-Y h:iA", strtotime($order->created_at));
            $labs = DB::table('lab_orders')
            ->join('quest_data_test_codes', 'lab_orders.product_id', 'quest_data_test_codes.TEST_CD')
            ->where('lab_orders.status', 'essa-forwarded')
            ->where('lab_orders.type', 'Counter')
            ->select('quest_data_test_codes.TEST_NAME')
            ->where('lab_orders.order_id', $order->order_id)->get()->toArray();
            $order->lab_test_name =$labs;
        }
        $approved_labs['code'] = 200;
        $approved_labs['orders'] = $orders;
        return $this->sendResponse($approved_labs,"Approved Labs Result");
    }
    public function pending_onlinelab(Request $request){
        $search = $request->lab_name;
        $pending_requisitions = DB::table('lab_orders')
            ->join('quest_data_test_codes', 'lab_orders.product_id', 'quest_data_test_codes.TEST_CD')
            ->where('lab_orders.status', 'forwarded_to_doctor')
            ->where('quest_data_test_codes.TEST_NAME','LIKE', '%'.$search.'%')
            ->where('lab_orders.doc_id', Auth::user()->id)
            ->orderByDesc('lab_orders.order_id')
            ->groupBy('lab_orders.order_id')
            ->paginate(10);
        $pending_requisitions_test_name = DB::table('lab_orders')
            ->join('quest_data_test_codes', 'lab_orders.product_id', 'quest_data_test_codes.TEST_CD')
            ->where('lab_orders.status', 'forwarded_to_doctor')
            ->where('lab_orders.doc_id', Auth::user()->id)
            ->orderByDesc('lab_orders.order_id')
            ->select('quest_data_test_codes.TEST_NAME', 'lab_orders.order_id')
            ->get()->toArray();
        foreach ($pending_requisitions as $requisition) {
            $requisition->date = User::convert_utc_to_user_timezone($requisition->user_id, $requisition->created_at);
            $requisition->date = $requisition->date['date'];
            $user = User::find($requisition->user_id);
            $state = State::find($user->state_id);
            $requisition->user_state = $state->name;
            $doctors = DB::table('doctor_licenses')
                ->join('users', 'users.id', 'doctor_licenses.doctor_id')
                ->where('doctor_licenses.state_id', $user->state_id)
                ->select('users.name', 'users.last_name', 'users.id as user_id')
                ->get()->toArray();
            $requisition->doctors = $doctors;

            if ($requisition->doc_id != null) {
                $doc = User::find($requisition->doc_id);
                $requisition->decline_status = "Approval Requested To Dr." . $doc->name . ' ' . $doc->last_name;
            } else {
                $requisition->decline_status = "Not assigned to any doctor yet";
            }
        }
        $pending_onlineTest['code'] =200;
        $pending_onlineTest['pending_requisitions'] =$pending_requisitions;
        $pending_onlineTest['pending_requisitions_test_name'] =$pending_requisitions_test_name;
        return $this->sendResponse($pending_onlineTest,"Pending Online Labs");
    }
}
