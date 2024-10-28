<?php

namespace App\Http\Controllers\Api\DoctorsApi;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use App\Models\TblTransaction;
use App\User;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ixudra\Curl\Facades\Curl;
use Log;

class PaymentController extends BaseController
{
    public function doctor_wallet(){
        $user_time_zone = auth()->user()->timeZone;
        $user_type = Auth::user()->user_type;
        $user_id = Auth::user()->id;
        $getpercentage = DB::table('doctor_percentage')->where('doc_id', $user_id)->first();
        $getSessionTotals = DB::table("sessions")->where('doctor_id', $user_id)->where('status', 'ended')->get();
        $totalDoctorSessionIncom = 0;
        foreach ($getSessionTotals as $getSessionTotal) {
            $doc_price = ($getpercentage->percentage / 100) * $getSessionTotal->price;
            $totalDoctorSessionIncom += $doc_price;
        }
        $totalEarning = $totalDoctorSessionIncom;
        $currentYear = date('Y');
        $getDoctorSessionTotals = DB::table("sessions")
            ->where('doctor_id', $user_id)
            ->where('status', 'ended')
            ->whereRaw('YEAR(created_at) = ?', [$currentYear])
            ->get();
        $currentYeartotalsessionAmount = 0;
        foreach ($getDoctorSessionTotals as $getDoctorSessionTotal) {
            $doc_price = ($getpercentage->percentage / 100) * $getDoctorSessionTotal->price;
            $currentYeartotalsessionAmount += $doc_price;
        }
        $totalEarningCurrentYear = $currentYeartotalsessionAmount;
        $currentMonth = date('m');
        $currentMonthDoctorTotals = DB::table("sessions")
            ->where('doctor_id', $user_id)
            ->where('status', 'ended')
            ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
            ->get();

        $month_lab_approval_earning = DB::table('lab_orders')
            ->where('lab_orders.status', 'quest-forwarded')
            ->where('lab_orders.type', 'Counter')
            ->where('lab_orders.doc_id', $user_id)
            ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
            ->groupBy('lab_orders.order_id')
            ->get();
        $month_lab_approval_earning = count($month_lab_approval_earning)*3;

        $currentMonthDoctorTotalAmount = 0;
        foreach ($currentMonthDoctorTotals as $currentMonthDoctorTotal) {
            $doc_price = ($getpercentage->percentage / 100) * $currentMonthDoctorTotal->price;
            $currentMonthDoctorTotalAmount += $doc_price;
        }
        $totalEarningCurrentMonth = $currentMonthDoctorTotalAmount + $month_lab_approval_earning;
        $currentDay = date('d');
        $currentDayDoctorTotals = DB::table("sessions")
            ->where('doctor_id', $user_id)
            ->where('status', 'ended')
            ->whereRaw('DAY(created_at) = ?', [$currentDay])
            ->get();

        $day_lab_approval_earning = DB::table('lab_orders')
            ->where('lab_orders.status', 'quest-forwarded')
            ->where('lab_orders.type', 'Counter')
            ->where('lab_orders.doc_id', $user_id)
            ->whereRaw('DAY(created_at) = ?', [$currentDay])
            ->groupBy('lab_orders.order_id')
            ->get();
        $day_lab_approval_earning = count($day_lab_approval_earning)*3;

        $currentDayDoctorTotalAmount = 0;
        foreach ($currentDayDoctorTotals as $currentDayDoctorTotals) {
            $doc_price = ($getpercentage->percentage / 100) * $currentDayDoctorTotals->price;
            $currentDayDoctorTotalAmount += $doc_price;
        }
        $totalEarningCurrentDay = $currentDayDoctorTotalAmount + $day_lab_approval_earning;

        $lab_approval_earning = DB::table('lab_orders')
            ->where('lab_orders.status', 'quest-forwarded')
            ->where('lab_orders.type', 'Counter')
            ->where('lab_orders.doc_id', $user_id)
            ->groupBy('lab_orders.order_id')
            ->get();
        $lab_approval_earning = count($lab_approval_earning)*3;
        // if ($request->from_date != null && $request->to_date != null) {
        //     $doctorHistory = DB::table("sessions")
        //         ->join("users", 'users.id', '=', 'sessions.patient_id')
        //         ->where('sessions.doctor_id', $user_id)
        //         ->where('sessions.status', 'ended')
        //         ->where('sessions.date', '>=', $request->from_date)
        //         ->where('sessions.date', '<=', $request->to_date)
        //         ->select('sessions.*', 'users.*')
        //         ->paginate(10);
        //     foreach ($doctorHistory as $doc_history) {
        //         $doc_history->price = ($getpercentage->percentage / 100) * $doc_history->price;
        //         $doc_history->date = User::convert_utc_to_user_timezone($user_id, $doc_history->start_time)['date'];
        //         $date = new DateTime($doc_history->start_time);
        //         $date = date('h:i A',strtotime('-15 minutes',strtotime($doc_history->start_time)));
        //         $date = User::convert_utc_to_user_timezone($user_id,$date)['time'];
        //         $doc_history->start_time = $date;
        //         $date1 = new DateTime($doc_history->end_time);
        //         $date1 = date('h:i A',strtotime('-15 minutes',strtotime($doc_history->end_time)));
        //         $date1 = User::convert_utc_to_user_timezone($user_id,$date1)['time'];
        //         $doc_history->end_time = $date1;
        //     }
        // } else {
            $doctorHistory = DB::table("sessions")
                ->join("users", 'users.id', '=', 'sessions.patient_id')
                ->where('sessions.doctor_id', $user_id)
                ->where('sessions.status', 'ended')
                ->whereRaw('MONTH(sessions.created_at) = ?', [$currentMonth])
                ->select('sessions.*', 'users.name', 'users.last_name')
                ->orderBy('sessions.id', 'DESC')
                ->paginate(10);

            foreach ($doctorHistory as $doc_history) {
                $doc_history->price = ($getpercentage->percentage / 100) * $doc_history->price;
                $doc_history->date = User::convert_utc_to_user_timezone($user_id, $doc_history->start_time)['date'];
                $date = new DateTime($doc_history->start_time);
                $date = date('h:i A',strtotime('-15 minutes',strtotime($doc_history->start_time)));
                $date = User::convert_utc_to_user_timezone($user_id,$date)['time'];
                $doc_history->start_time = $date;

                $date1 = new DateTime($doc_history->end_time);
                $date1 = date('h:i A',strtotime('-15 minutes',strtotime($doc_history->end_time)));
                $date1 = User::convert_utc_to_user_timezone($user_id,$date1)['time'];
                $doc_history->end_time = $date1;
            }
         
            $doctor_wallet['code'] = 200;
            $doctor_wallet['user_type'] = $user_type;
            $doctor_wallet['totalEarning'] = $totalEarning;
            $doctor_wallet['getpercentage'] = $getpercentage;
            $doctor_wallet['current_balance'] = $totalEarning+$lab_approval_earning;
            $doctor_wallet['totalEarningCurrentMonth'] = $totalEarningCurrentMonth;
            $doctor_wallet['totalEarningCurrentYear'] = $totalEarningCurrentYear;
            $doctor_wallet['totalEarningCurrentDay'] = $totalEarningCurrentDay;
            $doctor_wallet['totalEarningCurrentDay'] = $totalEarningCurrentDay;
            $doctor_wallet['lab_approval_earning'] = $lab_approval_earning;
            $doctor_wallet['doctorHistory'] = $doctorHistory;
            return $this->sendResponse($doctor_wallet,"Doctor Wallet");
    }
    public function doctor_wallet_dateFilter(Request $request){
        $user_time_zone = auth()->user()->timeZone;
        $user_type = Auth::user()->user_type;
        $user_id = Auth::user()->id;
        $getpercentage = DB::table('doctor_percentage')->where('doc_id', $user_id)->first();
        $getSessionTotals = DB::table("sessions")->where('doctor_id', $user_id)->where('status', 'ended')->get();
        $totalDoctorSessionIncom = 0;
        foreach ($getSessionTotals as $getSessionTotal) {
            $doc_price = ($getpercentage->percentage / 100) * $getSessionTotal->price;
            $totalDoctorSessionIncom += $doc_price;
        }
        $totalEarning = $totalDoctorSessionIncom;
        $currentYear = date('Y');
        $getDoctorSessionTotals = DB::table("sessions")
            ->where('doctor_id', $user_id)
            ->where('status', 'ended')
            ->whereRaw('YEAR(created_at) = ?', [$currentYear])
            ->get();
        $currentYeartotalsessionAmount = 0;
        foreach ($getDoctorSessionTotals as $getDoctorSessionTotal) {
            $doc_price = ($getpercentage->percentage / 100) * $getDoctorSessionTotal->price;
            $currentYeartotalsessionAmount += $doc_price;
        }
        $totalEarningCurrentYear = $currentYeartotalsessionAmount;
        $currentMonth = date('m');
        $currentMonthDoctorTotals = DB::table("sessions")
            ->where('doctor_id', $user_id)
            ->where('status', 'ended')
            ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
            ->get();

        $month_lab_approval_earning = DB::table('lab_orders')
            ->where('lab_orders.status', 'quest-forwarded')
            ->where('lab_orders.type', 'Counter')
            ->where('lab_orders.doc_id', $user_id)
            ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
            ->groupBy('lab_orders.order_id')
            ->get();
        $month_lab_approval_earning = count($month_lab_approval_earning)*3;

        $currentMonthDoctorTotalAmount = 0;
        foreach ($currentMonthDoctorTotals as $currentMonthDoctorTotal) {
            $doc_price = ($getpercentage->percentage / 100) * $currentMonthDoctorTotal->price;
            $currentMonthDoctorTotalAmount += $doc_price;
        }
        $totalEarningCurrentMonth = $currentMonthDoctorTotalAmount + $month_lab_approval_earning;
        $currentDay = date('d');
        $currentDayDoctorTotals = DB::table("sessions")
            ->where('doctor_id', $user_id)
            ->where('status', 'ended')
            ->whereRaw('DAY(created_at) = ?', [$currentDay])
            ->get();

        $day_lab_approval_earning = DB::table('lab_orders')
            ->where('lab_orders.status', 'quest-forwarded')
            ->where('lab_orders.type', 'Counter')
            ->where('lab_orders.doc_id', $user_id)
            ->whereRaw('DAY(created_at) = ?', [$currentDay])
            ->groupBy('lab_orders.order_id')
            ->get();
        $day_lab_approval_earning = count($day_lab_approval_earning)*3;

        $currentDayDoctorTotalAmount = 0;
        foreach ($currentDayDoctorTotals as $currentDayDoctorTotals) {
            $doc_price = ($getpercentage->percentage / 100) * $currentDayDoctorTotals->price;
            $currentDayDoctorTotalAmount += $doc_price;
        }
        $totalEarningCurrentDay = $currentDayDoctorTotalAmount + $day_lab_approval_earning;

        $lab_approval_earning = DB::table('lab_orders')
            ->where('lab_orders.status', 'quest-forwarded')
            ->where('lab_orders.type', 'Counter')
            ->where('lab_orders.doc_id', $user_id)
            ->groupBy('lab_orders.order_id')
            ->get();
        $lab_approval_earning = count($lab_approval_earning)*3;
        if ($request->from_date != null && $request->to_date != null) {
            $doctorHistory = DB::table("sessions")
                ->join("users", 'users.id', '=', 'sessions.patient_id')
                ->where('sessions.doctor_id', $user_id)
                ->where('sessions.status', 'ended')
                ->where('sessions.date', '>=', $request->from_date)
                ->where('sessions.date', '<=', $request->to_date)
                ->select('sessions.*', 'users.*')
                ->paginate(10);
            foreach ($doctorHistory as $doc_history) {
                $doc_history->price = ($getpercentage->percentage / 100) * $doc_history->price;
                $doc_history->date = User::convert_utc_to_user_timezone($user_id, $doc_history->start_time)['date'];
                $date = new DateTime($doc_history->start_time);
                $date = date('h:i A',strtotime('-15 minutes',strtotime($doc_history->start_time)));
                $date = User::convert_utc_to_user_timezone($user_id,$date)['time'];
                $doc_history->start_time = $date;
                $date1 = new DateTime($doc_history->end_time);
                $date1 = date('h:i A',strtotime('-15 minutes',strtotime($doc_history->end_time)));
                $date1 = User::convert_utc_to_user_timezone($user_id,$date1)['time'];
                $doc_history->end_time = $date1;
            }
            $doctor_wallet['code'] = 200;
            $doctor_wallet['user_type'] = $user_type;
            $doctor_wallet['totalEarning'] = $totalEarning;
            $doctor_wallet['getpercentage'] = $getpercentage;
            $doctor_wallet['current_balance'] = $totalEarning+$lab_approval_earning;
            $doctor_wallet['totalEarningCurrentMonth'] = $totalEarningCurrentMonth;
            $doctor_wallet['totalEarningCurrentYear'] = $totalEarningCurrentYear;
            $doctor_wallet['totalEarningCurrentDay'] = $totalEarningCurrentDay;
            $doctor_wallet['totalEarningCurrentDay'] = $totalEarningCurrentDay;
            $doctor_wallet['lab_approval_earning'] = $lab_approval_earning;
            $doctor_wallet['doctorHistory'] = $doctorHistory;
            return $this->sendResponse($doctor_wallet,"Doctor Wallet with date filter");
        } else{
            $doctor_walletError['code'] = 200;
            return $this->sendError($doctor_walletError,'Date Filter Required');
        }
    }
}
