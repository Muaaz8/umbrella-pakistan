<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use PDF;
use Illuminate\Support\Collection;

class FinanceController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $role=Auth::user()->user_type;
        if($role=='finance_admin')
        {
            return view('finance.admin.index');
        }
        if($role=='doctor')
        {
            return view('finance.doctor.index');
        }


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('finance.admin.add_transection');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function finance_admin_setting()
    {
        return view('dashboard_finance_admin.AccountSetting.index');
    }

    public function doctor_finance_reports()
    {
        $doctors = DB::table('users')->where('user_type','doctor')->where('active',1)->paginate(10);
        $data = DB::table('users')->where('user_type','doctor')->where('active',1)->get()->toArray();
        foreach($doctors as $doc)
        {
            $doc->payable = 0;
            $payable = DB::table('sessions')->where('doctor_id',$doc->id)->where('status','ended')->sum('price');
            $percent = DB::table('doctor_percentage')->where('doc_id',$doc->id)->first();
            $doc->payable = ($percent->percentage/100) * $payable;

            $online = DB::table('lab_orders')
            ->where('doc_id',$doc->id)
            ->where('status','quest-forwarded')
            ->where('type','Counter')
            ->groupby('order_id')
            ->count();

            $doc->payable += $online*3;
        }
        foreach($data as $doc)
        {
            $doc->payable = 0;
            $payable = DB::table('sessions')->where('doctor_id',$doc->id)->where('status','ended')->sum('price');
            $percent = DB::table('doctor_percentage')->where('doc_id',$doc->id)->first();
            $doc->payable = ($percent->percentage/100) * $payable;

            $online = DB::table('lab_orders')
            ->where('doc_id',$doc->id)
            ->where('status','quest-forwarded')
            ->where('type','Counter')
            ->groupby('order_id')
            ->count();

            $doc->payable += $online*3;
        }
        $data = json_encode($data);
        return view('dashboard_finance_admin.Doctor_Reports.index',compact('doctors','data'));
    }

    public function online_lab($id)
    {
        $user = auth()->user();
        $date = '';
        $doctor = DB::table('users')->where('id',$id)->first();
        $orders = DB::table('lab_orders')
        ->where('doc_id',$doctor->id)
        ->where('status','quest-forwarded')
        ->where('type','Counter')
        ->groupby('order_id')
        ->paginate(10);

        foreach($orders as $order)
        {
            $order->created_at = User::convert_utc_to_user_timezone($user->id,$order->created_at);
            $order->earning = 3;
        }
        return view('dashboard_finance_admin.Doctor_Reports.online_lab',compact('doctor','orders','date'));
    }

    public function online_lab_filter(Request $request)
    {
        $user = auth()->user();
        $date = $request->date;
        $doctor = DB::table('users')->where('id',$request->id)->first();
        $request->date = explode('-', $request->date);
        $startdate = date('Y-m-d', strtotime($request->date[0]));
        $enddate = date('Y-m-d', strtotime($request->date[1]));
        $orders = DB::table('lab_orders')
        ->where('doc_id',$doctor->id)
        ->where('status','quest-forwarded')
        ->where('type','Counter')
        ->whereDate('created_at', '>=', $startdate)
        ->whereDate('created_at', '<=', $enddate)
        ->groupby('order_id')
        ->paginate(10);

        foreach($orders as $order)
        {
            $order->created_at = User::convert_utc_to_user_timezone($user->id,$order->created_at);
            $order->earning = 3;
        }
        return view('dashboard_finance_admin.Doctor_Reports.online_lab',compact('doctor','orders','date'));
    }

    public function generate_doc_online_pdf(Request $request)
    {
        $user = auth()->user();
        $doctor = DB::table('users')->where('id',$request->id)->first();
        $doc_name = $doctor->name.' '.$doctor->last_name;
        if($request->date != null)
        {
            $request->date = explode('-', $request->date);
            $startdate = date('Y-m-d', strtotime($request->date[0]));
            $enddate = date('Y-m-d', strtotime($request->date[1]));
            $OnlineItems = DB::table('lab_orders')
            ->where('doc_id',$doctor->id)
            ->where('status','quest-forwarded')
            ->where('type','Counter')
            ->whereDate('created_at', '>=', $startdate)
            ->whereDate('created_at', '<=', $enddate)
            ->groupby('order_id')
            ->get();
        }
        else
        {
            $OnlineItems = DB::table('lab_orders')
            ->where('doc_id',$doctor->id)
            ->where('status','quest-forwarded')
            ->where('type','Counter')
            ->groupby('order_id')
            ->get();
        }

        foreach ($OnlineItems as $ot) {
            $test = DB::table('quest_data_test_codes')->where('TEST_CD', $ot->product_id)->first();
            $ot->price = $test->PRICE;
            $ot->sale_price = $test->SALE_PRICE;
            $ot->datetime = User::convert_utc_to_user_timezone($user->id, $ot->created_at);
            $ot->name = $test->DESCRIPTION;
            $ot->doc_name = $doctor->name.' '.$doctor->last_name;
            $ot->profit = $test->SALE_PRICE - $test->PRICE - 3;
        }
        $pdf = PDF::loadView('dashboard_finance_admin.pdf_pages.doc_online_pdf', compact('OnlineItems'))->output();
        return response()->streamDownload(
            fn () => print($pdf),
            "Doc_Online_Earnings_Details.pdf"
        );
    }

    public function generate_doc_online_csv(Request $request)
    {
        $user = auth()->user();
        $doctor = DB::table('users')->where('id',$request->id)->first();
        $doc_name = $doctor->name.' '.$doctor->last_name;
        if($request->date != null)
        {
            $request->date = explode('-', $request->date);
            $startdate = date('Y-m-d', strtotime($request->date[0]));
            $enddate = date('Y-m-d', strtotime($request->date[1]));
            $orders = DB::table('lab_orders')
            ->where('doc_id',$doctor->id)
            ->where('status','quest-forwarded')
            ->where('type','Counter')
            ->whereDate('created_at', '>=', $startdate)
            ->whereDate('created_at', '<=', $enddate)
            ->groupby('order_id')
            ->get();
        }
        else
        {
            $orders = DB::table('lab_orders')
            ->where('doc_id',$doctor->id)
            ->where('status','quest-forwarded')
            ->where('type','Counter')
            ->groupby('order_id')
            ->get();
        }

        $headers = array(
            'Content-Type' => 'text/csv'
          );
            $filename =  public_path('doctor_online_Earnings.xlxs');
            $handle = fopen($filename,'w');
            fputcsv($handle, [
                "Order ID",
                "Doctor Name",
                "Date",
                "Time",
                "Earning",
            ]);

        foreach($orders as $order)
        {
            $order->created_at = User::convert_utc_to_user_timezone($user->id,$order->created_at);
            $order->earning = 3;

            fputcsv($handle, [
                $order->order_id,
                $doc_name,
                $order->created_at['date'],
                $order->created_at['time'],
                '$'.$order->earning,
            ]);
        }
        fputcsv($handle, [
            '',
            '',
            '',
            '',
            '',
        ]);
        fputcsv($handle, [
            'Total',
            '',
            date('m-d-Y'),
            date('H:i A'),
            '$'.count($orders)*3,
        ]);

        fclose($handle);
        return response()->download($filename, "doctor_online_Earnings.csv", $headers);
    }

    public function evisit($id)
    {
        $user = auth()->user();
        $date = '';
        $ses_id = '';
        $doctor = DB::table('users')->where('id',$id)->first();
        $sessions = DB::table('sessions')->where('doctor_id',$doctor->id)->where('status','ended')->paginate(10);
        $percent = DB::table('doctor_percentage')->where('doc_id',$doctor->id)->first();

        foreach($sessions as $ses)
        {
            $ses->doc_fee = ($percent->percentage/100)*$ses->price;
            $ses->created_at = User::convert_utc_to_user_timezone($user->id,$ses->created_at);
        }
        return view('dashboard_finance_admin.Doctor_Reports.evisit',compact('sessions','doctor','date','ses_id'));
    }

    public function evisit_filter(Request $request)
    {
        $user = auth()->user();
        $date = $request->date;
        $ses_id = $request->s_id;
        $doctor = DB::table('users')->where('id',$request->id)->first();
        $percent = DB::table('doctor_percentage')->where('doc_id',$doctor->id)->first();
        if($request->s_id!=null)
        {
            $request->s_id = explode('-',$request->s_id);
            $sessions = DB::table('sessions')->where('doctor_id',$doctor->id)->where('session_id',$request->s_id[1])->paginate(10);
        }
        elseif($request->date!=null)
        {
            $request->date = explode('-', $request->date);
            $startdate = date('Y-m-d', strtotime($request->date[0]));
            $enddate = date('Y-m-d', strtotime($request->date[1]));
            $sessions = DB::table('sessions')
            ->where('doctor_id',$doctor->id)
            ->where('status','ended')
            ->whereDate('created_at', '>=', $startdate)
            ->whereDate('created_at', '<=', $enddate)
            ->paginate(10);
        }
        else
        {
            $sessions = DB::table('sessions')->where('doctor_id',$doctor->id)->where('status','ended')->paginate(10);
        }

        foreach($sessions as $ses)
        {
            $ses->doc_fee = ($percent->percentage/100)*$ses->price;
            $ses->created_at = User::convert_utc_to_user_timezone($user->id,$ses->created_at);
        }
        return view('dashboard_finance_admin.Doctor_Reports.evisit',compact('sessions','doctor','date','ses_id'));
    }

    public function generate_doc_evisit_pdf(Request $request)
    {
        $user = auth()->user();
        $doctor = DB::table('users')->where('id',$request->id)->first();
        $getpercentage = DB::table('doctor_percentage')->where('doc_id',$doctor->id)->first();
        $doc_name = $doctor->name.' '.$doctor->last_name;
        if($request->s_id!=null)
        {
            if($request->s_id[0]!='U')
            {
                $request->s_id = 'UEV-'.$request->s_id;
            }
            $request->s_id = explode('-',$request->s_id);
            $getSessionTotalSessions = DB::table('sessions')->where('doctor_id',$doctor->id)->where('session_id',$request->s_id[1])->get();
        }
        elseif($request->date!=null)
        {
            $request->date = explode('-', $request->date);
            $startdate = date('Y-m-d', strtotime($request->date[0]));
            $enddate = date('Y-m-d', strtotime($request->date[1]));
            $getSessionTotalSessions = DB::table('sessions')
            ->where('doctor_id',$doctor->id)
            ->where('status','ended')
            ->whereDate('created_at', '>=', $startdate)
            ->whereDate('created_at', '<=', $enddate)
            ->get();
        }
        else
        {
            $getSessionTotalSessions = DB::table('sessions')->where('doctor_id',$doctor->id)->where('status','ended')->get();
        }

        $total = 0;
        foreach($getSessionTotalSessions as $getSessionTotalSession)
        {
            $doc_price = ($getpercentage->percentage / 100) * $getSessionTotalSession->price;
            $getSessionTotalSession->doc_percent = $getpercentage->percentage;
            $getSessionTotalSession->doc_price = $doc_price;
            $getSessionTotalSession->card_fee = (2 / 100) * $getSessionTotalSession->price;
            $getSessionTotalSession->Net_profit = $getSessionTotalSession->price - $doc_price - $getSessionTotalSession->card_fee;
            $user_check = User::where('id', $getSessionTotalSession->patient_id)->first();
            $getSessionTotalSession->pat_name = $user_check->name . ' ' . $user_check->last_name;
            $getSessionTotalSession->doc_name = $doc_name;
            $getSessionTotalSession->datetime = User::convert_utc_to_user_timezone($user->id, $getSessionTotalSession->created_at);
        }
        $pdf = PDF::loadView('dashboard_finance_admin.pdf_pages.doc_evisit_pdf', compact('getSessionTotalSessions','doctor'))->output();
        //return $pdf->download('EarningDetails.pdf');
        return response()->streamDownload(
            fn () => print($pdf),
            "Doc_Evisit_Earning_Details.pdf"
        );
    }

    public function generate_doc_evisit_csv(Request $request)
    {
        $user = auth()->user();
        $doctor = DB::table('users')->where('id',$request->id)->first();
        $percent = DB::table('doctor_percentage')->where('doc_id',$doctor->id)->first();
        $doc_name = $doctor->name.' '.$doctor->last_name;
        if($request->s_id!=null)
        {
            $request->s_id = explode('-',$request->s_id);
            $sessions = DB::table('sessions')->where('doctor_id',$doctor->id)->where('session_id',$request->s_id[1])->get();
        }
        elseif($request->date!=null)
        {
            $request->date = explode('-', $request->date);
            $startdate = date('Y-m-d', strtotime($request->date[0]));
            $enddate = date('Y-m-d', strtotime($request->date[1]));
            $sessions = DB::table('sessions')
            ->where('doctor_id',$doctor->id)
            ->where('status','ended')
            ->whereDate('created_at', '>=', $startdate)
            ->whereDate('created_at', '<=', $enddate)
            ->get();
        }
        else
        {
            $sessions = DB::table('sessions')->where('doctor_id',$doctor->id)->where('status','ended')->get();
        }

        $headers = array(
            'Content-Type' => 'text/csv'
          );
            $filename =  public_path('doctor_evisit_Earnings.xlxs');
            $handle = fopen($filename,'w');
            fputcsv($handle, [
                "Session ID",
                "Doctor Name",
                "Date",
                "Time",
                "Earning",
            ]);
        $total = 0;
        foreach($sessions as $ses)
        {
            $ses->doc_fee = ($percent->percentage/100)*$ses->price;
            $ses->created_at = User::convert_utc_to_user_timezone($user->id,$ses->created_at);

            fputcsv($handle, [
                $ses->session_id,
                $doc_name,
                $ses->created_at['date'],
                $ses->created_at['time'],
                '$'.$ses->doc_fee,
            ]);
            $total += $ses->doc_fee;
        }

        fputcsv($handle, [
            '',
            '',
            '',
            '',
            '',
        ]);
        fputcsv($handle, [
            'Total',
            '',
            date('m-d-Y'),
            date('H:i A'),
            '$'.$total,
        ]);

        fclose($handle);
        return response()->download($filename, "doctor_evisit_Earnings.csv", $headers);
    }

    public function doc_payable_amount($id)
    {
        $user = auth()->user();
        $date = '';
        $doctor = DB::table('users')->where('user_type','doctor')->where('id',$id)->first();
        $doc_name = $doctor->name.' '.$doctor->last_name;
        $sessions = DB::table('sessions')->where('doctor_id',$doctor->id)->where('status','ended')->get();
        $percent = DB::table('doctor_percentage')->where('doc_id',$doctor->id)->first();
        $online = DB::table('lab_orders')
        ->where('doc_id',$doctor->id)
        ->where('status','quest-forwarded')
        ->where('type','Counter')
        ->groupby('order_id')
        ->get();
        $payable = [];
        foreach($sessions as $ses)
        {
            $ses->doc_fee = ($percent->percentage/100)*$ses->price;
            $ses->created_at = User::convert_utc_to_user_timezone($user->id,$ses->created_at);
            array_push($payable,array('type'=>'E-Visit','date'=>$ses->created_at['date'],'time'=>$ses->created_at['time'],'earning'=>$ses->doc_fee));
        }
        foreach($online as $on)
        {
            $on->created_at = User::convert_utc_to_user_timezone($user->id,$on->created_at);
            array_push($payable,array('type'=>'Online','date'=>$on->created_at['date'],'time'=>$on->created_at['time'],'earning'=>3));
        }
        $columns = array_column($payable, 'date');
        array_multisort($columns, SORT_DESC, $payable);
        // $payable = (new Collection($payable))->paginate(10);
        return view('dashboard_finance_admin.Doctor_Reports.payable_amount',compact('payable','doctor','date'));
    }

    public function doc_payable_amount_filter(Request $request)
    {
        $user = auth()->user();
        $date = $request->date;
        $sessions = null;
        $online = null;
        $doctor = DB::table('users')->where('user_type','doctor')->where('id',$request->id)->first();
        $doc_name = $doctor->name.' '.$doctor->last_name;
        $percent = DB::table('doctor_percentage')->where('doc_id',$doctor->id)->first();
        if($request->date != null)
        {
            $request->date = explode('-', $request->date);
            $startdate = date('Y-m-d', strtotime($request->date[0]));
            $enddate = date('Y-m-d', strtotime($request->date[1]));

            $sessions = DB::table('sessions')
            ->where('doctor_id',$doctor->id)
            ->where('status','ended')
            ->whereDate('created_at', '>=', $startdate)
            ->whereDate('created_at', '<=', $enddate)
            ->get();

            $online = DB::table('lab_orders')
            ->where('doc_id',$doctor->id)
            ->where('status','quest-forwarded')
            ->where('type','Counter')
            ->whereDate('created_at', '>=', $startdate)
            ->whereDate('created_at', '<=', $enddate)
            ->groupby('order_id')
            ->get();
        }
        else
        {
            $sessions = DB::table('sessions')->where('doctor_id',$doctor->id)->where('status','ended')->get();
            $online = DB::table('lab_orders')
            ->where('doc_id',$doctor->id)
            ->where('status','quest-forwarded')
            ->where('type','Counter')
            ->groupby('order_id')
            ->get();
        }
        $payable = [];
        if($sessions != null)
        {
            foreach($sessions as $ses)
            {
                $ses->doc_fee = ($percent->percentage/100)*$ses->price;
                $ses->created_at = User::convert_utc_to_user_timezone($user->id,$ses->created_at);
                array_push($payable,array('type'=>'E-Visit','date'=>$ses->created_at['date'],'time'=>$ses->created_at['time'],'earning'=>$ses->doc_fee));
            }
        }
        if($online != null)
        {
            foreach($online as $on)
            {
                $on->created_at = User::convert_utc_to_user_timezone($user->id,$on->created_at);
                array_push($payable,array('type'=>'Online','date'=>$on->created_at['date'],'time'=>$on->created_at['time'],'earning'=>3));
            }
        }

        if($payable != null)
        {
            $columns = array_column($payable, 'date');
            array_multisort($columns, SORT_DESC, $payable);
        }
        return view('dashboard_finance_admin.Doctor_Reports.payable_amount',compact('payable','doctor','date'));
    }

    public function generate_doc_payable_pdf(Request $request)
    {
        $user = auth()->user();
        $date = $request->date;
        $sessions = null;
        $online = null;
        $doctor = DB::table('users')->where('user_type','doctor')->where('id',$request->id)->first();
        $doc_name = $doctor->name.' '.$doctor->last_name;
        $percent = DB::table('doctor_percentage')->where('doc_id',$doctor->id)->first();
        if($request->date != null)
        {
            $request->date = explode('-', $request->date);
            $startdate = date('Y-m-d', strtotime($request->date[0]));
            $enddate = date('Y-m-d', strtotime($request->date[1]));

            $sessions = DB::table('sessions')
            ->where('doctor_id',$doctor->id)
            ->where('status','ended')
            ->whereDate('created_at', '>=', $startdate)
            ->whereDate('created_at', '<=', $enddate)
            ->get();

            $online = DB::table('lab_orders')
            ->where('doc_id',$doctor->id)
            ->where('status','quest-forwarded')
            ->where('type','Counter')
            ->whereDate('created_at', '>=', $startdate)
            ->whereDate('created_at', '<=', $enddate)
            ->groupby('order_id')
            ->get();
        }
        else
        {
            $sessions = DB::table('sessions')->where('doctor_id',$doctor->id)->where('status','ended')->get();
            $online = DB::table('lab_orders')
            ->where('doc_id',$doctor->id)
            ->where('status','quest-forwarded')
            ->where('type','Counter')
            ->groupby('order_id')
            ->get();
        }
        $payable = [];
        if($sessions != null)
        {
            foreach($sessions as $ses)
            {
                $ses->doc_fee = ($percent->percentage/100)*$ses->price;
                $ses->created_at = User::convert_utc_to_user_timezone($user->id,$ses->created_at);
                array_push($payable,array('type'=>'E-Visit','date'=>$ses->created_at['date'],'time'=>$ses->created_at['time'],'earning'=>$ses->doc_fee,'doc_name'=>$doc_name));
            }
        }
        if($online != null)
        {
            foreach($online as $on)
            {
                $on->created_at = User::convert_utc_to_user_timezone($user->id,$on->created_at);
                array_push($payable,array('type'=>'Online','date'=>$on->created_at['date'],'time'=>$on->created_at['time'],'earning'=>3,'doc_name'=>$doc_name));
            }
        }

        $columns = array_column($payable, 'date');
        array_multisort($columns, SORT_DESC, $payable);

        $pdf = PDF::loadView('dashboard_finance_admin.pdf_pages.doc_payable_pdf', compact('payable'))->output();
        //return $pdf->download('EarningDetails.pdf');
        return response()->streamDownload(
            fn () => print($pdf),
            "Doc_Amount_Payable_Details.pdf"
        );
    }

    public function generate_doc_payable_csv(Request $request)
    {
        $user = auth()->user();
        $date = $request->date;
        $sessions = null;
        $online = null;
        $doctor = DB::table('users')->where('user_type','doctor')->where('id',$request->id)->first();
        $doc_name = $doctor->name.' '.$doctor->last_name;
        $percent = DB::table('doctor_percentage')->where('doc_id',$doctor->id)->first();
        if($request->date != null)
        {
            $request->date = explode('-', $request->date);
            $startdate = date('Y-m-d', strtotime($request->date[0]));
            $enddate = date('Y-m-d', strtotime($request->date[1]));

            $sessions = DB::table('sessions')
            ->where('doctor_id',$doctor->id)
            ->where('status','ended')
            ->whereDate('created_at', '>=', $startdate)
            ->whereDate('created_at', '<=', $enddate)
            ->get();

            $online = DB::table('lab_orders')
            ->where('doc_id',$doctor->id)
            ->where('status','quest-forwarded')
            ->where('type','Counter')
            ->whereDate('created_at', '>=', $startdate)
            ->whereDate('created_at', '<=', $enddate)
            ->groupby('order_id')
            ->get();
        }
        else
        {
            $sessions = DB::table('sessions')->where('doctor_id',$doctor->id)->where('status','ended')->get();
            $online = DB::table('lab_orders')
            ->where('doc_id',$doctor->id)
            ->where('status','quest-forwarded')
            ->where('type','Counter')
            ->groupby('order_id')
            ->get();
        }
        $payable = [];
        if($sessions != null)
        {
            foreach($sessions as $ses)
            {
                $ses->doc_fee = ($percent->percentage/100)*$ses->price;
                $ses->created_at = User::convert_utc_to_user_timezone($user->id,$ses->created_at);
                array_push($payable,array('type'=>'E-Visit','date'=>$ses->created_at['date'],'time'=>$ses->created_at['time'],'earning'=>$ses->doc_fee));
            }
        }
        if($online != null)
        {
            foreach($online as $on)
            {
                $on->created_at = User::convert_utc_to_user_timezone($user->id,$on->created_at);
                array_push($payable,array('type'=>'Online','date'=>$on->created_at['date'],'time'=>$on->created_at['time'],'earning'=>3));
            }
        }

        $columns = array_column($payable, 'date');
        array_multisort($columns, SORT_DESC, $payable);
        $headers = array(
            'Content-Type' => 'text/csv'
            );
            $filename =  public_path('doctor_amount_payable.xlxs');
            $handle = fopen($filename,'w');
            fputcsv($handle, [
                "Type Of Earning",
                "Doctor Name",
                "Date",
                "Time",
                "Earning",
            ]);
        $total = 0;

        if($payable != null)
        {
            foreach($payable as $pay)
            {
                fputcsv($handle, [
                    $pay['type'],
                    $doc_name,
                    $pay['date'],
                    $pay['time'],
                    '$'.$pay['earning'],
                ]);
                $total += $pay['earning'];
            }

        }
        fputcsv($handle, [
            '',
            '',
            '',
            '',
            '',
        ]);
        fputcsv($handle, [
            'Total',
            '',
            date('m-d-Y'),
            date('H:i A'),
            '$'.$total,
        ]);

        fclose($handle);
        return response()->download($filename, "doctor_amount_payable.csv", $headers);
    }

    public function doc_paid_amount($id)
    {
        $user = auth()->user();
        $date = '';
        $doctor = DB::table('users')->where('user_type','doctor')->where('id',$id)->first();
        $doc_name = $doctor->name.' '.$doctor->last_name;
        $sessions = DB::table('sessions')
        ->where('doctor_id',$doctor->id)
        ->where('status','ended')
        ->where('doc_fee','unpaid')
        ->orwhere('doc_fee','!=',null)->get();
        $percent = DB::table('doctor_percentage')->where('doc_id',$doctor->id)->first();
        $online = DB::table('lab_orders')
        ->where('doc_id',$doctor->id)
        ->where('status','quest-forwarded')
        ->where('type','Counter')
        ->where('doc_fee','unpaid')
        ->orwhere('doc_fee','!=',null)
        ->groupby('order_id')
        ->get();
        $paid = [];
        foreach($sessions as $ses)
        {
            $ses->doc_fee = ($percent->percentage/100)*$ses->price;
            $ses->created_at = User::convert_utc_to_user_timezone($user->id,$ses->created_at);
            array_push($paid,array('type'=>'E-Visit','date'=>$ses->created_at['date'],'time'=>$ses->created_at['time'],'earning'=>$ses->doc_fee));
        }
        foreach($online as $on)
        {
            $on->created_at = User::convert_utc_to_user_timezone($user->id,$on->created_at);
            array_push($paid,array('type'=>'Online','date'=>$on->created_at['date'],'time'=>$on->created_at['time'],'earning'=>3));
        }
        $columns = array_column($paid, 'date');
        array_multisort($columns, SORT_DESC, $paid);
        // $paid = (new Collection($paid))->paginate(10);
        return view('dashboard_finance_admin.Doctor_Reports.paid_amount',compact('paid','doctor','date'));
    }

    public function doc_paid_amount_filter(Request $request)
    {
        $user = auth()->user();
        $date = $request->date;
        $sessions = null;
        $online = null;
        $doctor = DB::table('users')->where('user_type','doctor')->where('id',$request->id)->first();
        $doc_name = $doctor->name.' '.$doctor->last_name;
        $percent = DB::table('doctor_percentage')->where('doc_id',$doctor->id)->first();
        if($request->date != null)
        {
            $request->date = explode('-', $request->date);
            $startdate = date('Y-m-d', strtotime($request->date[0]));
            $enddate = date('Y-m-d', strtotime($request->date[1]));

            $sessions = DB::table('sessions')
            ->where('doctor_id',$doctor->id)
            ->where('status','ended')
            ->whereDate('created_at', '>=', $startdate)
            ->whereDate('created_at', '<=', $enddate)
            ->where('doc_fee','unpaid')
            ->orwhere('doc_fee','!=',null)
            ->get();

            $online = DB::table('lab_orders')
            ->where('doc_id',$doctor->id)
            ->where('status','quest-forwarded')
            ->where('type','Counter')
            ->whereDate('created_at', '>=', $startdate)
            ->whereDate('created_at', '<=', $enddate)
            ->where('doc_fee','unpaid')
            ->orwhere('doc_fee','!=',null)
            ->groupby('order_id')
            ->get();
        }
        else
        {
            $sessions = DB::table('sessions')
            ->where('doctor_id',$doctor->id)
            ->where('status','ended')
            ->where('doc_fee','unpaid')
            ->orwhere('doc_fee','!=',null)
            ->get();

            $online = DB::table('lab_orders')
            ->where('doc_id',$doctor->id)
            ->where('status','quest-forwarded')
            ->where('type','Counter')
            ->where('doc_fee','unpaid')
            ->orwhere('doc_fee','!=',null)
            ->groupby('order_id')
            ->get();
        }
        $paid = [];
        if($sessions != null)
        {
            foreach($sessions as $ses)
            {
                $ses->doc_fee = ($percent->percentage/100)*$ses->price;
                $ses->created_at = User::convert_utc_to_user_timezone($user->id,$ses->created_at);
                array_push($paid,array('type'=>'E-Visit','date'=>$ses->created_at['date'],'time'=>$ses->created_at['time'],'earning'=>$ses->doc_fee));
            }
        }
        if($online != null)
        {
            foreach($online as $on)
            {
                $on->created_at = User::convert_utc_to_user_timezone($user->id,$on->created_at);
                array_push($paid,array('type'=>'Online','date'=>$on->created_at['date'],'time'=>$on->created_at['time'],'earning'=>3));
            }
        }

        if($paid != null)
        {
            $columns = array_column($paid, 'date');
            array_multisort($columns, SORT_DESC, $paid);
        }
        return view('dashboard_finance_admin.Doctor_Reports.paid_amount',compact('paid','doctor','date'));
    }

    public function generate_doc_paid_pdf(Request $request)
    {
        $user = auth()->user();
        $date = $request->date;
        $sessions = null;
        $online = null;
        $doctor = DB::table('users')->where('user_type','doctor')->where('id',$request->id)->first();
        $doc_name = $doctor->name.' '.$doctor->last_name;
        $percent = DB::table('doctor_percentage')->where('doc_id',$doctor->id)->first();
        if($request->date != null)
        {
            $request->date = explode('-', $request->date);
            $startdate = date('Y-m-d', strtotime($request->date[0]));
            $enddate = date('Y-m-d', strtotime($request->date[1]));

            $sessions = DB::table('sessions')
            ->where('doctor_id',$doctor->id)
            ->where('status','ended')
            ->whereDate('created_at', '>=', $startdate)
            ->whereDate('created_at', '<=', $enddate)
            ->where('doc_fee','unpaid')
            ->orwhere('doc_fee','!=',null)
            ->get();

            $online = DB::table('lab_orders')
            ->where('doc_id',$doctor->id)
            ->where('status','quest-forwarded')
            ->where('type','Counter')
            ->whereDate('created_at', '>=', $startdate)
            ->whereDate('created_at', '<=', $enddate)
            ->where('doc_fee','unpaid')
            ->orwhere('doc_fee','!=',null)
            ->groupby('order_id')
            ->get();
        }
        else
        {
            $sessions = DB::table('sessions')
            ->where('doctor_id',$doctor->id)
            ->where('status','ended')
            ->where('doc_fee','unpaid')
            ->orwhere('doc_fee','!=',null)
            ->get();

            $online = DB::table('lab_orders')
            ->where('doc_id',$doctor->id)
            ->where('status','quest-forwarded')
            ->where('type','Counter')
            ->where('doc_fee','paid')
            ->orwhere('doc_fee','!=',null)
            ->groupby('order_id')
            ->get();
        }
        $payable = [];
        if($sessions != null)
        {
            foreach($sessions as $ses)
            {
                $ses->doc_fee = ($percent->percentage/100)*$ses->price;
                $ses->created_at = User::convert_utc_to_user_timezone($user->id,$ses->created_at);
                array_push($payable,array('type'=>'E-Visit','date'=>$ses->created_at['date'],'time'=>$ses->created_at['time'],'earning'=>$ses->doc_fee,'doc_name'=>$doc_name));
            }
        }
        if($online != null)
        {
            foreach($online as $on)
            {
                $on->created_at = User::convert_utc_to_user_timezone($user->id,$on->created_at);
                array_push($payable,array('type'=>'Online','date'=>$on->created_at['date'],'time'=>$on->created_at['time'],'earning'=>3,'doc_name'=>$doc_name));
            }
        }

        $columns = array_column($payable, 'date');
        array_multisort($columns, SORT_DESC, $payable);

        $pdf = PDF::loadView('dashboard_finance_admin.pdf_pages.doc_paid_pdf', compact('payable'))->output();
        //return $pdf->download('EarningDetails.pdf');
        return response()->streamDownload(
            fn () => print($pdf),
            "Doc_Amount_Paid_Details.pdf"
        );
    }

    public function generate_doc_paid_csv(Request $request)
    {
        $user = auth()->user();
        $date = $request->date;
        $sessions = null;
        $online = null;
        $doctor = DB::table('users')->where('user_type','doctor')->where('id',$request->id)->first();
        $doc_name = $doctor->name.' '.$doctor->last_name;
        $percent = DB::table('doctor_percentage')->where('doc_id',$doctor->id)->first();
        if($request->date != null)
        {
            $request->date = explode('-', $request->date);
            $startdate = date('Y-m-d', strtotime($request->date[0]));
            $enddate = date('Y-m-d', strtotime($request->date[1]));

            $sessions = DB::table('sessions')
            ->where('doctor_id',$doctor->id)
            ->where('status','ended')
            ->whereDate('created_at', '>=', $startdate)
            ->whereDate('created_at', '<=', $enddate)
            ->where('doc_fee','unpaid')
            ->orwhere('doc_fee','!=',null)
            ->get();

            $online = DB::table('lab_orders')
            ->where('doc_id',$doctor->id)
            ->where('status','quest-forwarded')
            ->where('type','Counter')
            ->whereDate('created_at', '>=', $startdate)
            ->whereDate('created_at', '<=', $enddate)
            ->where('doc_fee','unpaid')
            ->orwhere('doc_fee','!=',null)
            ->groupby('order_id')
            ->get();
        }
        else
        {
            $sessions = DB::table('sessions')
            ->where('doctor_id',$doctor->id)
            ->where('status','ended')
            ->where('doc_fee','unpaid')
            ->orwhere('doc_fee','!=',null)
            ->get();

            $online = DB::table('lab_orders')
            ->where('doc_id',$doctor->id)
            ->where('status','quest-forwarded')
            ->where('type','Counter')
            ->where('doc_fee','unpaid')
            ->orwhere('doc_fee','!=',null)
            ->groupby('order_id')
            ->get();
        }
        $payable = [];
        if($sessions != null)
        {
            foreach($sessions as $ses)
            {
                $ses->doc_fee = ($percent->percentage/100)*$ses->price;
                $ses->created_at = User::convert_utc_to_user_timezone($user->id,$ses->created_at);
                array_push($payable,array('type'=>'E-Visit','date'=>$ses->created_at['date'],'time'=>$ses->created_at['time'],'earning'=>$ses->doc_fee));
            }
        }
        if($online != null)
        {
            foreach($online as $on)
            {
                $on->created_at = User::convert_utc_to_user_timezone($user->id,$on->created_at);
                array_push($payable,array('type'=>'Online','date'=>$on->created_at['date'],'time'=>$on->created_at['time'],'earning'=>3));
            }
        }

        $columns = array_column($payable, 'date');
        array_multisort($columns, SORT_DESC, $payable);
        $headers = array(
            'Content-Type' => 'text/csv'
            );
            $filename =  public_path('doctor_amount_payable.xlxs');
            $handle = fopen($filename,'w');
            fputcsv($handle, [
                "Mode Of Payment",
                "Doctor Name",
                "Date",
                "Time",
                "Paid Amount",
            ]);
        $total = 0;

        if($payable != null)
        {
            foreach($payable as $pay)
            {
                fputcsv($handle, [
                    $pay['type'],
                    $doc_name,
                    $pay['date'],
                    $pay['time'],
                    '$'.$pay['earning'],
                ]);
                $total += $pay['earning'];
            }

        }
        fputcsv($handle, [
            '',
            '',
            '',
            '',
            '',
        ]);
        fputcsv($handle, [
            'Total',
            '',
            date('m-d-Y'),
            date('H:i A'),
            '$'.$total,
        ]);

        fclose($handle);
        return response()->download($filename, "doctor_amount_payable.csv", $headers);
    }

    public function vendors()
    {
        $vendors = DB::table('vendor_accounts')->groupby('vendor_number')->paginate(10);
        return view('dashboard_finance_admin.vendors.index',compact('vendors'));
    }

    public function vendor_details($id)
    {
        $user = auth()->user();
        $vendor = DB::table('vendor_accounts')->where('id',$id)->first();
        $prescriptions = DB::table('prescriptions')
        ->join('sessions','prescriptions.session_id','sessions.id')
        ->join('tbl_cart','prescriptions.id','tbl_cart.pres_id')
        ->where('tbl_cart.item_type','prescribed')
        ->where('tbl_cart.status','purchased')
        ->where('prescriptions.type',$vendor->category)
        ->orderBy('prescriptions.id','DESC')
        ->select('prescriptions.*','sessions.session_id as ses_id','sessions.id as sessi_id')
        ->get();
        //->paginate(10,['*'],'pres');
        $pres_earning = 0;
        $on_earning = 0;
        $Total_earning = 0;
        $payable = new Collection();
        $paid = new Collection();
        $not_performed = new Collection();
        $Amount_payable = 0;
        $Amount_paid = 0;
        $refund = 0;
        $OnlineItems = [];
        if($vendor->vendor != 'quest')
        {
            foreach($prescriptions as $key =>  $pres)
            {
                // if ($pres->type == 'lab-test')
                // {
                //     $test = DB::table('quest_data_test_codes')->where('TEST_CD', $pres->test_id)->first();
                //     if($vendor->vendor == 'quest')
                //     {
                //         $order = DB::table('lab_orders')->where('pres_id', $pres->id)->where('product_id', $pres->test_id)->first();
                //         $pres->name = $test->DESCRIPTION;
                //         $pres->sale_price = $test->SALE_PRICE;
                //         $pres->price = $test->PRICE;
                //         $pres->order_id = $order->order_id;
                //         $pres->pro_id = $pres->test_id;
                //         $pres->datetime = User::convert_utc_to_user_timezone($user->id, $pres->created_at);
                //     }
                //     else
                //     {
                //         unset($prescriptions[$key]);
                //     }
                // }
                if($pres->type == 'imaging')
                {
                    $test = DB::table('tbl_products')->where('id', $pres->imaging_id)->first();
                    if($test->vendor_id == $vendor->id || $test->vendor_id == null)
                    {
                        $order = DB::table('imaging_orders')->where('pres_id', $pres->id)->where('product_id', $pres->imaging_id)->first();
                        $loc = DB::table('imaging_selected_location')->where('session_id', $pres->sessi_id)->where('product_id',$pres->imaging_id)->first();
                        $price = DB::table('imaging_prices')->where('location_id', $loc->imaging_location_id)->where('product_id',$loc->product_id)->first();
                        $pres->name = $test->name;
                        $pres->sale_price = $price->price;
                        $pres->price = $price->actual_price;
                        $pres->order_id = $order->order_id;
                        $pres->pro_id = $pres->imaging_id;
                        $pres->datetime = User::convert_utc_to_user_timezone($user->id, $pres->created_at);
                    }
                    else
                    {
                        unset($prescriptions[$key]);
                    }
                }
                elseif($pres->type == 'medicine')
                {
                    $test = DB::table('tbl_products')->where('id', $pres->medicine_id)->first();
                    if($test->vendor_id == $vendor->id || $test->vendor_id == null)
                    {
                        $order = DB::table('medicine_order')->where('session_id', $pres->sessi_id)->first();
                        $price = DB::table('medicine_pricings')
                        ->where('id', $pres->price)
                        ->first();
                        $pres->name = $test->name;
                        $pres->sale_price = $price->sale_price;
                        $pres->price = $price->price;
                        $pres->order_id = $order->order_main_id;
                        $pres->pro_id = $pres->medicine_id;
                        $pres_earning += $pres->price;
                        $pres->datetime = User::convert_utc_to_user_timezone($user->id, $pres->created_at);
                    }
                    else
                    {
                        unset($prescriptions[$key]);
                    }
                }

                if($vendor->category == 'lab-test')
                {
                    $OnlineItems = DB::table('lab_orders')->where('vendor_fee','!=',null)->orderBy('id','DESC')->get();//paginate(10,'*','online');
                    foreach ($OnlineItems as $key => $ot) {
                        $test = DB::table('quest_data_test_codes')->where('TEST_CD', $ot->product_id)->first();
                        if($vendor->vendor == 'quest' && $ot->type != 'Counter')
                        {
                            $ot->price = $test->PRICE;
                            $ot->sale_price = $test->SALE_PRICE;
                            $ot->datetime = User::convert_utc_to_user_timezone($user->id, $ot->created_at);
                            $ot->name = $test->DESCRIPTION;
                            $pres_earning += $ot->sale_price - $ot->price;
                            if($ot->vendor_fee == 'unpaid')
                            {
                                $payable->push(array('id'=>$ot->id,'Type'=>'Prescribed','date'=>$ot->datetime['date'],'time'=>$ot->datetime['time'],'Earning'=>$ot->price));
                                $Amount_payable += $ot->price;
                            }
                            elseif($ot->vendor_fee == 'paid')
                            {
                                $paid->push(array('id'=>$ot->id,'Type'=>'Prescribed','date'=>$ot->datetime['date'],'time'=>$ot->datetime['time'],'Earning'=>$ot->price));
                                $Amount_paid += $ot->price;
                            }
                            elseif($ot->vendor_fee == 'not performed')
                            {
                                $not_performed->push(array('id'=>$ot->id,'Type'=>'Prescribed','date'=>$ot->datetime['date'],'time'=>$ot->datetime['time'],'Earning'=>$ot->price));
                            }
                            unset($OnlineItems[$key]);
                        }
                        elseif($vendor->vendor == 'quest')
                        {
                            $ot->price = $test->PRICE;
                            $ot->sale_price = $test->SALE_PRICE;
                            $ot->datetime = User::convert_utc_to_user_timezone($user->id, $ot->created_at);
                            $ot->name = $test->DESCRIPTION;
                            $on_earning += $ot->sale_price - $ot->price;
                            if($ot->vendor_fee == 'unpaid' || $ot->vendor_fee == null)
                            {
                                $payable->push(array('id'=>$ot->id,'Type'=>'Online','date'=>$ot->datetime['date'],'time'=>$ot->datetime['time'],'Earning'=>$ot->price));
                                $Amount_payable += $ot->price;
                            }
                            elseif($ot->vendor_fee == 'paid')
                            {
                                $paid->push(array('id'=>$ot->id,'Type'=>'Online','date'=>$ot->datetime['date'],'time'=>$ot->datetime['time'],'Earning'=>$ot->price));
                                $Amount_paid += $ot->price;
                            }
                            elseif($ot->vendor_fee == 'not performed')
                            {
                                $not_performed->push(array('id'=>$ot->id,'Type'=>'Online','date'=>$ot->datetime['date'],'time'=>$ot->datetime['time'],'Earning'=>$ot->price));
                            }
                        }
                        else
                        {
                            unset($OnlineItems[$key]);
                        }
                    }
                }
                elseif($vendor->category == 'imaging')
                {
                    $OnlineItems = DB::table('imaging_orders')->orderBy('id','DESC')->paginate(10,['*'],'online');
                    foreach ($OnlineItems as $ot) {
                        $test = DB::table('tbl_products')->where('id', $ot->product_id)->first();
                        if(($test->vendor_id == $vendor->id || $test->vendor_id == null) && ($ot->type == null || $ot->pres_id != ""))
                        {
                            $prescrip = DB::table('prescriptions')
                            ->where('id',$ot->pres_id)
                            ->first();
                            $loc = DB::table('imaging_selected_location')
                            ->where('session_id', $prescrip->session_id)
                            ->where('product_id', $prescrip->imaging_id)
                            ->first();
                            $price = DB::table('imaging_prices')
                            ->where('location_id',$loc->imaging_location_id)
                            ->where('product_id',$loc->product_id)
                            ->first();
                            $ot->price = $price->actual_price;
                            $ot->sale_price = $price->price;
                            $ot->datetime = User::convert_utc_to_user_timezone($user->id, $ot->created_at);
                            $ot->name = $test->name;
                            $pres_earning += $ot->price;
                            if($ot->vendor_fee == 'unpaid' || $ot->vendor_fee == null)
                            {
                                $payable->push(array('id'=>$ot->id,'Type'=>'Prescribed','date'=>$ot->datetime['date'],'time'=>$ot->datetime['time'],'Earning'=>$ot->price));
                                $Amount_payable += $ot->price;
                            }
                            else
                            {
                                $paid->push(array('id'=>$ot->id,'Type'=>'Prescribed','date'=>$ot->datetime['date'],'time'=>$ot->datetime['time'],'Earning'=>$ot->price));
                                $Amount_paid += $ot->price;
                            }
                            unset($OnlineItems[$key]);
                        }
                        elseif(($test->vendor_id == $vendor->id || $test->vendor_id == null) && $ot->type == 'Counter')
                        {
                            $prescrip = DB::table('prescriptions')
                            ->where('id',$ot->pres_id)
                            ->first();
                            $loc = DB::table('imaging_selected_location')
                            ->where('session_id', $prescrip->session_id)
                            ->where('product_id', $prescrip->imaging_id)
                            ->first();
                            $price = DB::table('imaging_prices')
                            ->where('location_id',$loc->imaging_location_id)
                            ->where('product_id',$loc->product_id)
                            ->first();
                            $ot->price = $price->actual_price;
                            $ot->sale_price = $price->price;
                            $ot->datetime = User::convert_utc_to_user_timezone($user->id, $ot->created_at);
                            $ot->name = $test->name;
                            $on_earning += $ot->price;
                            if($ot->vendor_fee == 'unpaid' || $ot->vendor_fee == null)
                            {
                                $payable->push(array('id'=>$ot->id,'Type'=>'Online','date'=>$ot->datetime['date'],'time'=>$ot->datetime['time'],'Earning'=>$ot->price));
                                $Amount_payable += $ot->price;
                            }
                            else
                            {
                                $paid->push(array('id'=>$ot->id,'Type'=>'Online','date'=>$ot->datetime['date'],'time'=>$ot->datetime['time'],'Earning'=>$ot->price));
                                $Amount_paid += $ot->price;
                            }
                        }
                        else
                        {
                            unset($OnlineItems[$key]);
                        }
                    }
                    $OnlineItems = new Collection();
                }
                elseif($vendor->category == 'medicine')
                {
                    $OnlineItems = DB::table('medicine_order')->orderBy('id','DESC')->paginate(10,['*'],'online');
                    foreach ($OnlineItems as $ot) {
                        $test = DB::table('tbl_products')->where('id', $ot->order_product_id)->first();
                        if(($test->vendor_id == $vendor->id || $test->vendor_id == null) && $ot->pro_mode != 'Counter')
                        {
                            $prescrip = DB::table('prescriptions')
                            ->where('session_id',$ot->session_id)
                            ->where('medicine_id',$ot->order_product_id)
                            ->first();
                            $price = DB::table('medicine_pricings')
                            ->where('id', $prescrip->price)
                            ->first();
                            $ot->price = $price->price;
                            $ot->sale_price = $price->sale_price;
                            $ot->datetime = User::convert_utc_to_user_timezone($user->id, $ot->created_at);
                            $ot->name = $test->name;
                            $on_earning += $ot->price;
                            if($ot->vendor_fee == 'unpaid' || $ot->vendor_fee == null)
                            {
                                $payable->push(array('id'=>$ot->id,'Type'=>'Prescribed','date'=>$ot->datetime['date'],'time'=>$ot->datetime['time'],'Earning'=>$ot->price));
                                $Amount_payable += $ot->price;
                            }
                            else
                            {
                                $paid->push(array('id'=>$ot->id,'Type'=>'Prescribed','date'=>$ot->datetime['date'],'time'=>$ot->datetime['time'],'Earning'=>$ot->price));
                                $Amount_paid += $ot->price;
                            }
                            unset($OnlineItems[$key]);
                        }
                        elseif($test->vendor_id == $vendor->id || $test->vendor_id == null)
                        {
                            $prescrip = DB::table('prescriptions')
                            ->where('session_id',$ot->session_id)
                            ->where('medicine_id',$ot->order_product_id)
                            ->first();
                            $price = DB::table('medicine_pricings')
                            ->where('id', $prescrip->price)
                            ->first();
                            $ot->price = $price->price;
                            $ot->sale_price = $price->sale_price;
                            $ot->datetime = User::convert_utc_to_user_timezone($user->id, $ot->created_at);
                            $ot->name = $test->name;
                            $on_earning += $ot->price;
                            if($ot->vendor_fee == 'unpaid' || $ot->vendor_fee == null)
                            {
                                $payable->push(array('id'=>$ot->id,'Type'=>'Online','date'=>$ot->datetime['date'],'time'=>$ot->datetime['time'],'Earning'=>$ot->price));
                                $Amount_payable += $ot->price;
                            }
                            else
                            {
                                $paid->push(array('id'=>$ot->id,'Type'=>'Online','date'=>$ot->datetime['date'],'time'=>$ot->datetime['time'],'Earning'=>$ot->price));
                                $Amount_paid += $ot->price;
                            }
                        }
                        else
                        {
                            unset($OnlineItems[$key]);
                        }
                    }
                    $OnlineItems = new Collection();
                }
                $Total_earning = $pres_earning + $on_earning;
            }
            if($paid != null)
            {
                $paid->sortBy('date');
            }
            if($payable != null)
            {
                $payable->sortBy('date');
            }
        }
        else
        {
            $vendor->profit = DB::table('quest_invoices')->sum('Profit');
            $pay_draw_fee = DB::table('quest_invoices')->where('Status','!=','Paid')->sum('Draw_fee');
            $paid_draw_fee = DB::table('quest_invoices')->where('Status','Paid')->sum('Draw_fee');
            $pay_amount = DB::table('quest_invoices')->where('Status','!=','Paid')->sum('Amount');
            $paid_amount = DB::table('quest_invoices')->where('Status','Paid')->sum('Amount');
            $vendor->paid = $paid_draw_fee + $paid_amount;
            $vendor->pay = $pay_draw_fee + $pay_amount;
            $vendor->total = $vendor->paid + $vendor->pay + $vendor->profit;
        }
        if($vendor->vendor == 'quest')
        {
            return view('dashboard_finance_admin.vendors.quest.index',compact('vendor'));
        }
        else
        {
            return view('dashboard_finance_admin.vendors.vendor_detail',compact('vendor','prescriptions','OnlineItems','Total_earning','pres_earning','on_earning','payable','paid','Amount_payable','Amount_paid'));
        }
    }

    public function vendor_fetch_pres_data(Request $request)
    {
        if($request->ajax())
        {
            $user = auth()->user();
            $vendor = DB::table('vendor_accounts')->where('id',$id)->first();
            $prescriptions = DB::table('prescriptions')
            ->join('sessions','prescriptions.session_id','sessions.id')
            ->join('tbl_cart','prescriptions.id','tbl_cart.pres_id')
            ->where('tbl_cart.item_type','prescribed')
            ->where('tbl_cart.status','purchased')
            ->where('prescriptions.type',$vendor->category)
            ->orderBy('prescriptions.id','DESC')
            ->select('prescriptions.*','sessions.session_id as ses_id','sessions.id as sessi_id')
            ->paginate(10,['*'],'pres');
            $pres_earning = 0;
            $on_earning = 0;
            $Total_earning = 0;
            foreach($prescriptions as $key =>  $pres)
            {
                if ($pres->type == 'lab-test')
                {
                    $test = DB::table('quest_data_test_codes')->where('TEST_CD', $pres->test_id)->first();
                    if($vendor->vendor == 'quest')
                    {
                        $order = DB::table('lab_orders')->where('pres_id', $pres->id)->where('product_id', $pres->test_id)->first();
                        $pres->name = $test->DESCRIPTION;
                        $pres->sale_price = $test->SALE_PRICE;
                        $pres->price = $test->PRICE;
                        $pres->order_id = $order->order_id;
                        $pres->pro_id = $pres->test_id;
                        $pres_earning += $pres->price;
                        $pres->datetime = User::convert_utc_to_user_timezone($user->id, $pres->created_at);
                    }
                    else
                    {
                        unset($prescriptions[$key]);
                    }
                }
                elseif($pres->type == 'imaging')
                {
                    $test = DB::table('tbl_products')->where('id', $pres->imaging_id)->first();
                    if($test->vendor_id == $vendor->id || $test->vendor_id == null)
                    {
                        $order = DB::table('imaging_orders')->where('pres_id', $pres->id)->where('product_id', $pres->imaging_id)->first();
                        $loc = DB::table('imaging_selected_location')->where('session_id', $pres->sessi_id)->where('product_id',$pres->imaging_id)->first();
                        $price = DB::table('imaging_prices')->where('location_id', $loc->imaging_location_id)->where('product_id',$loc->product_id)->first();
                        $pres->name = $test->name;
                        $pres->sale_price = $price->price;
                        $pres->price = $price->actual_price;
                        $pres->order_id = $order->order_id;
                        $pres->pro_id = $pres->imaging_id;
                        $pres_earning += $pres->price;
                        $pres->datetime = User::convert_utc_to_user_timezone($user->id, $pres->created_at);
                    }
                    else
                    {
                        unset($prescriptions[$key]);
                    }
                }
                elseif($pres->type == 'medicine')
                {
                    $test = DB::table('tbl_products')->where('id', $pres->medicine_id)->first();
                    if($test->vendor_id == $vendor->id || $test->vendor_id == null)
                    {
                        $order = DB::table('medicine_order')->where('session_id', $pres->sessi_id)->first();
                        $price = DB::table('medicine_pricings')
                        ->where('id', $pres->price)
                        ->first();
                        $pres->name = $test->name;
                        $pres->sale_price = $price->sale_price;
                        $pres->price = $price->price;
                        $pres->order_id = $order->order_main_id;
                        $pres->pro_id = $pres->medicine_id;
                        $pres_earning += $pres->price;
                        $pres->datetime = User::convert_utc_to_user_timezone($user->id, $pres->created_at);
                    }
                    else
                    {
                        unset($prescriptions[$key]);
                    }
                }
            }
            return view('dashboard_finance_admin.pagination_pages.vendor_pres',compact('prescriptions'))->render();
        }
    }

    public function vendor_fetch_online_data(Request $request)
    {
        if($request->ajax())
        {
            $user = auth()->user();
            $id = url()->previous();
            $id = $id[-1];
            $vendor = DB::table('vendor_accounts')->where('id',$id)->first();

            if($vendor->category == 'lab-test')
            {
                $OnlineItems = DB::table('lab_orders')->where('type','Counter')->orderBy('id','DESC')->get();
                foreach ($OnlineItems as $key => $ot) {
                    $test = DB::table('quest_data_test_codes')->where('TEST_CD', $ot->product_id)->first();
                    if($vendor->vendor == 'quest')
                    {
                        $ot->price = $test->PRICE;
                        $ot->sale_price = $test->SALE_PRICE;
                        $ot->datetime = User::convert_utc_to_user_timezone($user->id, $ot->created_at);
                        $ot->name = $test->DESCRIPTION;
                    }
                    else
                    {
                        unset($OnlineItems[$key]);
                    }
                }
            }
            elseif($vendor->category == 'imaging')
            {
                $OnlineItems = DB::table('imaging_orders')->orderBy('id','DESC')->paginate(10,['*'],'online');
                foreach ($OnlineItems as $ot) {
                    $test = DB::table('tbl_products')->where('id', $ot->product_id)->first();
                    if(($test->vendor_id == $vendor->id || $test->vendor_id == null) && $ot->type == 'Counter')
                    {
                        $prescrip = DB::table('prescriptions')
                        ->where('id',$ot->pres_id)
                        ->first();
                        $loc = DB::table('imaging_selected_location')
                        ->where('session_id', $prescrip->session_id)
                        ->where('product_id', $prescrip->imaging_id)
                        ->first();
                        $price = DB::table('imaging_prices')
                        ->where('location_id',$loc->imaging_location_id)
                        ->where('product_id',$loc->product_id)
                        ->first();
                        $ot->price = $price->actual_price;
                        $ot->sale_price = $price->price;
                        $ot->datetime = User::convert_utc_to_user_timezone($user->id, $ot->created_at);
                        $ot->name = $test->name;
                    }
                    else
                    {
                        unset($OnlineItems[$key]);
                    }
                }
                $OnlineItems = new Collection();
            }
            elseif($vendor->category == 'medicine')
            {
                $OnlineItems = DB::table('medicine_order')->orderBy('id','DESC')->get();//paginate(10,['*'],'online');
                foreach ($OnlineItems as $ot) {
                    $test = DB::table('tbl_products')->where('id', $ot->order_product_id)->first();
                    if($test->vendor_id == $vendor->id || $test->vendor_id == null)
                    {
                        $prescrip = DB::table('prescriptions')
                        ->where('session_id',$ot->session_id)
                        ->where('medicine_id',$ot->order_product_id)
                        ->first();
                        $price = DB::table('medicine_pricings')
                        ->where('id', $prescrip->price)
                        ->first();
                        $ot->price = $price->price;
                        $ot->sale_price = $price->sale_price;
                        $ot->datetime = User::convert_utc_to_user_timezone($user->id, $ot->created_at);
                        $ot->name = $test->name;
                    }
                    else
                    {
                        unset($OnlineItems[$key]);
                    }
                }
                $OnlineItems = new Collection();
            }
            $OnlineItems = $OnlineItems->paginate(10,['*'],'online');
            return view('dashboard_finance_admin.pagination_pages.vendor_online',compact('OnlineItems'))->render();
        }
    }

    public function vendor_payment($name,$id)
    {
        if($name=='lab-test')
        {
            DB::table('lab_orders')->where('id',$id)->update(['vendor_fee'=>'paid']);
            return redirect()->back();
        }
        elseif($name=='imaging')
        {

        }
        elseif($name=='medicine')
        {

        }
    }

    public function quest_amount($name,Request $request)
    {
        if($name=='Paid')
        {
            if($request->order_id!='')
            {
                $entries = DB::table('quest_invoices')->where('Order_id',$request->order_id)->where('status','Paid')->groupBy('Order_id')->paginate(10);
            }
            else
            {
                $entries = DB::table('quest_invoices')->where('status','Paid')->groupBy('Order_id')->paginate(10);
            }
            foreach($entries as $entry)
            {
                $entry->all = DB::table('quest_invoices')->where('Order_id',$entry->Order_id)->where('status','Paid')->get();
                $entry->amount = DB::table('quest_invoices')->where('Order_id',$entry->Order_id)->where('status','Paid')->sum('Amount');
                $entry->draw_fee = DB::table('quest_invoices')->where('Order_id',$entry->Order_id)->where('status','Paid')->sum('Draw_fee');
                $entry->profit = DB::table('quest_invoices')->where('Order_id',$entry->Order_id)->where('status','Paid')->sum('Profit');
                $entry->incomp = DB::table('quest_invoices')->where('Order_id',$entry->Order_id)->where('Status','incomplete')->count();
            }
            //$entries = DB::table('quest_invoices')->where('Status','Paid')->paginate(10);
            $pagename = 'Paid';
        }
        else if($name=='Payable')
        {
            if($request->order_id != '')
            {
                $entries = DB::table('quest_invoices')->where('Order_id',$request->order_id)->where('status','Unpaid')->groupBy('Order_id')->paginate(10);
            }
            else
            {
                $entries = DB::table('quest_invoices')->where('status','Unpaid')->groupBy('Order_id')->paginate(10);
            }
            foreach($entries as $entry)
            {
                $entry->all = DB::table('quest_invoices')->where('Order_id',$entry->Order_id)->where('status','Unpaid')->get();
                $entry->amount = DB::table('quest_invoices')->where('Order_id',$entry->Order_id)->where('status','Unpaid')->sum('Amount');
                $entry->draw_fee = DB::table('quest_invoices')->where('Order_id',$entry->Order_id)->where('status','Unpaid')->sum('Draw_fee');
                $entry->profit = DB::table('quest_invoices')->where('Order_id',$entry->Order_id)->where('status','Unpaid')->sum('Profit');
                $entry->incomp = DB::table('quest_invoices')->where('Order_id',$entry->Order_id)->where('Status','incomplete')->count();
            }
            // $entries = DB::table('quest_invoices')->where('Status','Unpaid')->paginate(10);
            $pagename = 'Payable';
        }
        else
        {
            if($request->order_id != '')
            {
                $entries = DB::table('quest_invoices')->where('Order_id',$request->order_id)->groupBy('Order_id')->paginate(10);
            }
            else
            {
                $entries = DB::table('quest_invoices')->groupBy('Order_id')->paginate(10);
            }
            foreach($entries as $entry)
            {
                $entry->all = DB::table('quest_invoices')->where('Order_id',$entry->Order_id)->get();
                $entry->amount = DB::table('quest_invoices')->where('Order_id',$entry->Order_id)->sum('Amount');
                $entry->draw_fee = DB::table('quest_invoices')->where('Order_id',$entry->Order_id)->sum('Draw_fee');
                $entry->profit = DB::table('quest_invoices')->where('Order_id',$entry->Order_id)->sum('Profit');
                $entry->incomp = DB::table('quest_invoices')->where('Order_id',$entry->Order_id)->where('Status','incomplete')->count();
            }
            // return view('dashboard_finance_admin.vendors.quest.invoicecollapse',compact('entries'));
            // dd($entries);
            // $entries = DB::table('quest_invoices')->where('status','Paid')->sum('Amount');
            $pagename = 'Invoices';
        }
        return view('dashboard_finance_admin.vendors.quest.invoicecollapse',compact('entries','pagename'));
    }

    public function add_quest_invoice($id,Request $request)
    {
        $input = $request->all();
        if($input!=null)
        {
            DB::table('quest_invoices')->where('id',$id)->update([
                "Invoice_number"=>$request->Invoice_number,
                "CPT"=>$request->CPT,
                "Service_code"=>$request->Service_code,
                "Draw_fee"=>$request->Draw_fee,
                "Profit"=>$request->Profit,
                "Status"=>$request->Status,
            ]);

            return redirect(url('/quest/amount/Invoices'));
        }
        $invoice = DB::table('quest_invoices')->where('id',$id)->first();
        return view('dashboard_finance_admin.vendors.quest.newinvoice',compact('invoice'));
    }

    public function mark_invoice_paid($id)
    {
        DB::table('quest_invoices')->where('id',$id)->update(['Status'=>'Paid']);
        return redirect()->back();
    }
}
