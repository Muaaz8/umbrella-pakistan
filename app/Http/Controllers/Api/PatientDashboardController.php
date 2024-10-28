<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use DB;
use Auth;
class PatientDashboardController extends BaseController
{
    public function patient_dashboard_info(){
        $user = Auth::user();
        $doctors = DB::table('sessions')
        ->where('patient_id',$user->id)
        ->where('status','!=','pending')
        ->groupBy('doctor_id')
        ->select('*')
        ->get();

        $orders = DB::table('tbl_orders')
        ->where('customer_id', $user->id)
        ->count();

        $reports = DB::table('quest_results')
        ->where('pat_id', $user->id)
        ->count();

        $patient_info["code"] = 200;
        $patient_info["doctors"] = count($doctors);
        $patient_info["orders"] = $orders;
        $patient_info["reports"] = $reports;

        // $patient_info["med_profile"] = $med_profile;
        return $this->sendResponse($patient_info,'Patient Dashboard Info');
    }
    public function reminder(){
        $user = Auth::user();
        // reminder
        $unread_reports = DB::table('quest_results')
        ->where('pat_id', $user->id)
        ->where('is_read',null)
        ->count();

        // $unread_reports_data = DB::table('quest_results')
        // ->where('pat_id', $user->id)
        // ->where('is_read',null)
        // ->get();

        $pending_appoints = DB::table('appointments')
        ->join('sessions','appointments.id','sessions.appointment_id')
        ->where('appointments.patient_id', $user->id)
        ->where('appointments.status','pending')
        ->where('sessions.status','!=','pending')
        ->count();
        // $pending_appointsData = DB::table('appointments')
        // ->join('sessions','appointments.id','sessions.appointment_id')
        // ->where('appointments.patient_id', $user->id)
        // ->where('appointments.status','pending')
        // ->where('sessions.status','!=','pending')
        // ->get();

        $pending_sessions = DB::table('sessions')
        ->where('patient_id', $user->id)
        ->where('appointment_id', null)
        ->where('status','!=','ended')
        ->where('reminder',null)
        ->orderby('id','desc')
        ->first();

        $cancel_sessions = DB::table('sessions')
        ->where('patient_id', $user->id)
        ->where('appointment_id', null)
        ->where('status','cancel')
        ->where('reminder',null)
        ->orderby('id','desc')
        ->first();

        // $pending_sessionsData = DB::table('sessions')
        // ->where('patient_id', $user->id)
        // ->where('appointment_id', null)
        // ->where('status','!=','ended')
        // ->where('reminder',null)
        // ->orderby('id','desc')
        // ->get();

        $med_profile = DB::table('medical_profiles')
        ->where('user_id',$user->id)
        ->count();

        if($pending_sessions!=null){
            $total_reminds = $unread_reports+$pending_appoints+1;
        }
        else{
            $total_reminds = $unread_reports+$pending_appoints;
        }
        $patient_info["reminder"]['unpaid_session'] = ($pending_sessions == null)? 0 : $pending_sessions;
        // $patient_info["reminder"]['appointment_data'] = $pending_appointsData;
        $patient_info["reminder"]['appointment'] = $pending_appoints;
        $patient_info["reminder"]['reports'] = $unread_reports;
        $patient_info["reminder"]['cancelled'] = $cancel_sessions;
        // $patient_info["reminder"]['unread_reports_data'] = $unread_reports_data;
        $patient_info["reminder"]['total_reminds'] = $total_reminds;
        return $this->sendResponse($patient_info,'Patient Dashboard Reminder');
    }
}
