<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\User;
use App\QuestResult;

class LabController extends BaseController
{
    public function lab_requisition(){
        $user = auth()->user();
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
    public function lab_requisition_pending(){
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
            // ->select('quest_data_test_codes.*')
            ->paginate(9);
        // $pending_requisitions_test_name = DB::table('lab_orders')
        //     ->join('quest_data_test_codes', 'lab_orders.product_id', 'quest_data_test_codes.TEST_CD')
        //     ->where('lab_orders.user_id', $user->id)
        //     ->where(function ($query) {
        //         return $query
        //             ->where('lab_orders.status', 'lab-editor-approval')
        //             ->orwhere('lab_orders.status', 'forwarded_to_doctor');
        //     })
        //     ->orderByDesc('lab_orders.order_id')
        //     ->select('quest_data_test_codes.TEST_NAME', 'lab_orders.order_id')
        //     ->get()->toArray();
        foreach ($pending_requisitions as $requisition) {
            $requisition->date = User::convert_utc_to_user_timezone($user->id, $requisition->created_at);
            $requisition->date = $requisition->date['date'];
        }
        $requisitionData['code'] = 200;
        $requisitionData['pending_requisitions'] = $pending_requisitions;
        // $requisitionData['pending_requisitions_test_name'] = $pending_requisitions_test_name;
        if($requisitionData != null){
            return $this->sendResponse($requisitionData,"Requisition Pending");
        } else{
            $requisitioninfo['code'] = 200;
            return $this->sendError($requisitioninfo,"No Pending Requisition");
        }
    }
    public function view_requisition($id){
        $user = Auth::user()->id;
        $requisitions = DB::table('quest_labs')
        ->join('quest_requests', 'quest_labs.id', 'quest_requests.quest_lab_id')
        ->where('quest_labs.id',$id)
        ->where('quest_labs.umd_patient_id', $user)
        ->select('quest_requests.requisition_file')->first();
        $file_name  =$requisitions->requisition_file;
        $requisitions =  \App\Helper::get_files_url($requisitions->requisition_file);
        $requisitionsView['code'] = 200;
        $requisitionsView['requistion_file'] = $requisitions;
        $requisitionsView['file_name'] = $file_name;
        return $this->sendResponse($requisitionsView,'requisition file');
    }
    public function lab_report(){
        if (auth()->user()->user_type == 'patient') {
            $reports = QuestResult::where('pat_id', auth()->user()->id)
                ->where('status', 'success')
                ->orderByDesc('id')
                ->get();
            if(!$reports->isEmpty()){
                $reportsData['code'] = 200;
                $reportsData['code'] = $reports;
                return $this->sendResponse($reportsData,"Lab Reports");
            } else{
                $reportsData['code'] = 200;
                return $this->sendError($reportsData,"No Lab Reports");
            }
        } 
    }
    public function recent_lab_buy(){
        $id = Auth::user()->id;
        $lab_orders = DB::table('lab_orders')->where('user_id',$id)->pluck('product_id');
        $lab_name = DB::table('quest_data_test_codes')->whereIn('TEST_CD',$lab_orders)->orderBy('id', 'DESC')->get();
        $recent_labs['code'] = 200;
        $recent_labs['lab_name'] = $lab_name;
        return $this->sendResponse($recent_labs,"Recent Labs");
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
    public function lab_requisition_pending_search(Request $request){
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
        if($requisitionData != null){
            return $this->sendResponse($requisitionData,"Requisition Pending");
        } else{
            $requisitioninfo['code'] = 200;
            return $this->sendError($requisitioninfo,"No Pending Requisition");
        }
    }
}
