<?php

namespace App\Http\Controllers;

use App\Http\Controllers\PharmacyController;
use App\Session;
use App\User;
use App\Notification;
use App\Mail\EvisitBookMail;
use App\Mail\NewAppointmentPatientMail;
use App\Mail\NewAppointmentDoctorMail;
use Illuminate\Http\Request;
use App\Models\TblTransaction;
use App\Events\RealTimeMessage;
use App\Events\updateQuePatient;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Models\InClinics;

class MeezanPaymentController extends Controller
{
    protected $userName;
    protected $api_url;
    protected $password;
    protected $currency = 586;
    protected $amount;
    protected $returnUrl;

    public function __construct() {
        $mode = DB::table('services')->where('name', 'authorize_api_mode')->first();
        if($mode->status == "liveMode") {
            $this->userName = env('MEEZAN_API_USERNAME');
            $this->api_url = env('MEEZAN_API_URL');
            $this->password = env('MEEZAN_API_PASSWORD');
            $this->returnUrl = env('APP_URL')."/meezan/payment/return";
        } else {
            $this->userName = env('MEEZAN_API_SANDBOX_USERNAME');
            $this->api_url = env('MEEZAN_API_SANDBOX_URL');
            $this->password = env('MEEZAN_API_SANDBOX_PASSWORD');
            $this->returnUrl = env('APP_URL')."/meezan/payment/return";
        }
    }

    // 25130855
    //  <!--- One Phase Payment ---!>
    public function payment($data,$amount)
    {
        $user_id = auth()->user()->id;
        $description = urlencode($data);
        $this->amount = $amount;
        $data = explode('-',$data);
        $transaction = TblTransaction::create([
            'subject' => $data[0],
            'description' => $data[1],
            'currency' => 'PKR',
            'total_amount' => ($amount / 100),
            'user_id' => $user_id,
            'status' => 0,
            'transaction_id' => null,
        ]);

        $temp_id = $transaction->id;

        if($data[0] == 'Evisit'){
            $orderId = 'CHCCE-'.$data[1];
            $this->returnUrl = route('meezan.return', ['temp_id' => $temp_id]);
        }elseif($data[0] == 'Appointment'){
            $orderId = 'CHCCA-'.$data[1];
            $this->returnUrl = route('meezan.return', ['temp_id' => $temp_id]);
        }elseif($data[0] == 'Inclinic'){
            $orderId = 'CHCCI-'.$data[1];
            $this->returnUrl = route('meezan.return', ['temp_id' => $temp_id]);
        }
        else{
            $orderId = 'CHCCO-'.$data[1];
            $this->returnUrl = route('meezan.order.return', ['temp_id' => $temp_id]);
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url.'/register.do?userName='.$this->userName.'&password='.$this->password.'&orderNumber='.$orderId.'&amount='.$this->amount.'&currency='.$this->currency.'&returnUrl='.urlencode($this->returnUrl).'&clientId='.$user_id.'&description='.$description,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response);

        $transaction->transaction_id = $response->orderId;
        $transaction->save();

        return $response;

    }

    public function payment_return()
    {
        $tempId = $_GET['temp_id'];
        $transaction = TblTransaction::find($tempId);

        $orderId = $transaction->transaction_id;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url.'/getOrderStatusExtended.do?userName='.$this->userName.'&password='.$this->password.'&orderId='.$orderId,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response);
        $description = explode("-",$response->orderDescription);
        if ($response->errorCode == 0) {
            if($description[0] == "Evisit"){
                if($response->orderStatus == 2){
                    $getSession = DB::table('sessions')->where('session_id', $description[1])->first();
                    Session::where('session_id', $description[1])->update(['status' => 'paid']);

                    $doctor_data = DB::table('users')->where('id', $getSession->doctor_id)->first();
                    $patient_data = DB::table('users')->where('id', $getSession->patient_id)->first();
                    $markDownData = [
                        'doc_name' => ucwords($doctor_data->name),
                        'pat_name' => ucwords($patient_data->name),
                        'pat_email' => $patient_data->email,
                        'doc_mail' => $doctor_data->email,
                        'amount' => $getSession->price,
                    ];
                    Mail::to($patient_data->email)->send(new EvisitBookMail($markDownData));

                    $text = "New Session Created by " . $patient_data->name . " " . $patient_data->last_name;
                    $notification_id = Notification::create([
                        'user_id' =>  $getSession->doctor_id,
                        'type' => '/doctor/patient/queue',
                        'text' => $text,
                        'appoint_id' => $getSession->id,
                    ]);
                    $transactionArr = [
                        'subject' => 'Evisit',
                        'description' => $description[1],
                        'currency' => 'PKR',
                        'total_amount' => ($response->amount/100),
                        'user_id' => auth()->user()->id,
                        'status' => 1,
                    ];
                    TblTransaction::where('description',$description[1])->update($transactionArr);

                    event(new RealTimeMessage($getSession->doctor_id));
                    event(new updateQuePatient('update patient que'));
                    return redirect()->route('waiting_room_pat', ['id' => \Crypt::encrypt($getSession->id)]);
                    // return redirect()->route('payment')->with('success', $response->actionCodeDescription);
                }else{
                    // $getSession = DB::table('sessions')->where('session_id', $description[1])->first();
                    $getSession = DB::table('sessions')->where('session_id', $description[1])->delete();
                    return redirect()->route('patient_online_doctors',['id'=>$description[2]])->with('error', $response->actionCodeDescription);
                }
            }
            if($description[0] == "Appointment"){
                if($response->orderStatus == 2){
                    $getSession = DB::table('sessions')->where('session_id', $description[1])->first();
                    Session::where('id', $getSession->id)->update(['status' => 'paid']);

                    try {
                        $doctor_data = DB::table('users')->where('id', $getSession->doctor_id)->first();
                        $patient_data = DB::table('users')->where('id', $getSession->patient_id)->first();
                        $getAppointment = DB::table('appointments')->where('id', $getSession->appointment_id)->first();
                        $d_date = User::convert_utc_to_user_timezone($doctor_data->id, $getAppointment->date . ' ' . $getAppointment->time);
                        $p_date = User::convert_utc_to_user_timezone($patient_data->id, $getAppointment->date . ' ' . $getAppointment->time);

                        $markDownData1 = [
                            'doc_name' => ucwords($doctor_data->name),
                            'pat_name' => ucwords($patient_data->name),
                            'time' => $p_date['time'],
                            'date' => $p_date['date'],
                            'pat_mail' => $patient_data->email,
                            'doc_mail' => $doctor_data->email,
                        ];
                        $markDownData2 = [
                            'doc_name' => ucwords($doctor_data->name),
                            'pat_name' => ucwords($patient_data->name),
                            'time' => $d_date['time'],
                            'date' => $d_date['date'],
                            'pat_mail' => $patient_data->email,
                            'doc_mail' => $doctor_data->email,
                        ];
                        Mail::to($patient_data->email)->send(new NewAppointmentPatientMail($markDownData1));
                        Mail::to($doctor_data->email)->send(new NewAppointmentDoctorMail($markDownData2));

                        $text = "New Appointment Created by " . $patient_data->name . " " . $patient_data->last_name;
                        $notification_id = Notification::create([
                            'user_id' =>  $getSession->doctor_id,
                            'type' => '/doctor/appointments',
                            'text' => $text,
                            'appoint_id' => $getSession->appointment_id,
                        ]);
                        $data = [
                            'user_id' =>  $getSession->doctor_id,
                            'type' => '/doctor/appointments',
                            'text' => $text,
                            'appoint_id' => $getSession->appointment_id,
                            'refill_id' => "null",
                            'received' => 'false',
                            'session_id' => 'null',
                        ];
                        $transactionArr = [
                            'subject' => 'Appointment',
                            'description' => $description[1],
                            'currency' => 'PKR',
                            'total_amount' => ($response->amount/100),
                            'user_id' => auth()->user()->id,
                            'status' => 1,
                        ];
                        TblTransaction::where('description',$description[1])->update($transactionArr);

                        event(new RealTimeMessage($getSession->doctor_id));
                        event(new updateQuePatient('update patient que'));
                    } catch (Exception $e) {
                        Log::error($e);
                    }
                    return redirect()->route('pat_appointments')->with("message", "Appointment Create Successfully");
                }else{
                    $getSession = DB::table('sessions')->where('session_id', $description[1])->first();

                    DB::table('appointments')->where('id', $getSession->appointment_id)->delete();
                    DB::table('sessions')->where('session_id', $description[1])->delete();

                    return redirect()->route('book_appointment',['id'=>$description[2]])->with('error', $response->actionCodeDescription);
                }
            }
            if($description[0] == "Order"){
                return $response;
            }
            if($description[0] == "Inclinic"){
                if($response->orderStatus == 2){
                    $pat = InClinics::where('user_id',$description[1])->update([
                        'status'=> 'pending'
                    ]);
                    $transactionArr = [
                        'subject' => 'Inclinic',
                        'description' => $description[1],
                        'currency' => 'PKR',
                        'total_amount' => ($response->amount/100),
                        'user_id' => auth()->user()->id,
                        'status' => 1,
                    ];
                    TblTransaction::where('description',$description[1])->update($transactionArr);
                    event(new \App\Events\InClinicPatientUpdate($description[1]));
                    return redirect()->route('inclinic_patient');
                }else{
                    return redirect()->back()->with('error',$response->actionCodeDescription);
                }
            }
        }
    }

    //  <!--- Mobile App Payment ---!>
    public function payment_app($data,$amount)
    {
        $user_id = auth()->user()->id;
        $description = urlencode($data);
        $this->amount = $amount;
        $data = explode('-',$data);
        $transaction = TblTransaction::create([
            'subject' => $data[0],
            'description' => $data[1],
            'currency' => 'PKR',
            'total_amount' => ($amount / 100),
            'user_id' => $user_id,
            'status' => 0,
            'transaction_id' => null,
        ]);

        $temp_id = $transaction->id;

        if($data[0] == 'Evisit'){
            $orderId = 'CHCCE-'.$data[1];
            $this->returnUrl = env('MOBILE_APP_URL')."/SendInviteScreen?temp_id=".$temp_id;
        }elseif($data[0] == 'Appointment'){
            $orderId = 'CHCCA-'.$data[1];
            // $this->returnUrl = env('MOBILE_APP_URL')."/SendInviteScreen";
        }elseif($data[0] == 'Inclinic'){
            $orderId = 'CHCCI-'.$data[1];
            // $this->returnUrl = env('MOBILE_APP_URL')."/SendInviteScreen";
        }
        else{
            $orderId = 'CHCCO-'.$data[1];
            $this->returnUrl = env('MOBILE_APP_URL')."/ThankYouScreen?temp_id=".$temp_id;
        }


        // $CURLOPT_URL = $this->api_url.'/register.do?userName='.$this->userName.'&password='.$this->password.'&orderNumber='.$orderId.'&amount='.$this->amount.'&currency='.$this->currency.'&returnUrl='.urlencode($this->returnUrl).'&clientId='.$user_id.'&description='.$description;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url.'/register.do?userName='.$this->userName.'&password='.$this->password.'&orderNumber='.$orderId.'&amount='.$this->amount.'&currency='.$this->currency.'&returnUrl='.urlencode($this->returnUrl).'&clientId='.$user_id.'&description='.$description,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response);

        $transaction->transaction_id = $response->orderId;
        $transaction->save();

        return $response;

    }

    public function payment_return_app()
    {
        $tempId = $_GET['temp_id'];
        $transaction = TblTransaction::find($tempId);

        $orderId = $transaction->transaction_id;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url.'/getOrderStatusExtended.do?userName='.$this->userName.'&password='.$this->password.'&orderId='.$orderId,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response);
        $description = explode("-",$response->orderDescription);
        if ($response->errorCode == 0) {
            if($description[0] == "Evisit"){
                if($response->orderStatus == 2){
                    $getSession = DB::table('sessions')->where('session_id', $description[1])->first();
                    Session::where('session_id', $description[1])->update(['status' => 'paid']);

                    $doctor_data = DB::table('users')->where('id', $getSession->doctor_id)->first();
                    $patient_data = DB::table('users')->where('id', $getSession->patient_id)->first();
                    $markDownData = [
                        'doc_name' => ucwords($doctor_data->name),
                        'pat_name' => ucwords($patient_data->name),
                        'pat_email' => $patient_data->email,
                        'doc_mail' => $doctor_data->email,
                        'amount' => $getSession->price,
                    ];
                    Mail::to($patient_data->email)->send(new EvisitBookMail($markDownData));

                    $text = "New Session Created by " . $patient_data->name . " " . $patient_data->last_name;
                    $notification_id = Notification::create([
                        'user_id' =>  $getSession->doctor_id,
                        'type' => '/doctor/patient/queue',
                        'text' => $text,
                        'appoint_id' => $getSession->id,
                    ]);

                    $transactionArr = [
                        'subject' => 'Evisit',
                        'description' => $description[1],
                        'currency' => 'PKR',
                        'total_amount' => ($response->amount/100),
                        'user_id' => auth()->user()->id,
                        'status' => 1,
                    ];
                    TblTransaction::where('description',$description[1])->update($transactionArr);

                    event(new RealTimeMessage($getSession->doctor_id));
                    event(new updateQuePatient('update patient que'));
                    return redirect()->route('waiting_room_pat', ['id' => \Crypt::encrypt($getSession->id)]);
                    // return redirect()->route('payment')->with('success', $response->actionCodeDescription);
                }else{
                    // $getSession = DB::table('sessions')->where('session_id', $description[1])->first();
                    $getSession = DB::table('sessions')->where('session_id', $description[1])->delete();
                    return redirect()->route('patient_online_doctors',['id'=>$description[2]])->with('error', $response->actionCodeDescription);
                }
            }
            if($description[0] == "Appointment"){
                if($response->orderStatus == 2){
                    $getSession = DB::table('sessions')->where('session_id', $description[1])->first();
                    Session::where('id', $getSession->id)->update(['status' => 'paid']);

                    try {
                        $doctor_data = DB::table('users')->where('id', $getSession->doctor_id)->first();
                        $patient_data = DB::table('users')->where('id', $getSession->patient_id)->first();
                        $getAppointment = DB::table('appointments')->where('id', $getSession->appointment_id)->first();
                        $d_date = User::convert_utc_to_user_timezone($doctor_data->id, $getAppointment->date . ' ' . $getAppointment->time);
                        $p_date = User::convert_utc_to_user_timezone($patient_data->id, $getAppointment->date . ' ' . $getAppointment->time);

                        $markDownData1 = [
                            'doc_name' => ucwords($doctor_data->name),
                            'pat_name' => ucwords($patient_data->name),
                            'time' => $p_date['time'],
                            'date' => $p_date['date'],
                            'pat_mail' => $patient_data->email,
                            'doc_mail' => $doctor_data->email,
                        ];
                        $markDownData2 = [
                            'doc_name' => ucwords($doctor_data->name),
                            'pat_name' => ucwords($patient_data->name),
                            'time' => $d_date['time'],
                            'date' => $d_date['date'],
                            'pat_mail' => $patient_data->email,
                            'doc_mail' => $doctor_data->email,
                        ];
                        Mail::to($patient_data->email)->send(new NewAppointmentPatientMail($markDownData1));
                        Mail::to($doctor_data->email)->send(new NewAppointmentDoctorMail($markDownData2));

                        $text = "New Appointment Created by " . $patient_data->name . " " . $patient_data->last_name;
                        $notification_id = Notification::create([
                            'user_id' =>  $getSession->doctor_id,
                            'type' => '/doctor/appointments',
                            'text' => $text,
                            'appoint_id' => $getSession->appointment_id,
                        ]);
                        $data = [
                            'user_id' =>  $getSession->doctor_id,
                            'type' => '/doctor/appointments',
                            'text' => $text,
                            'appoint_id' => $getSession->appointment_id,
                            'refill_id' => "null",
                            'received' => 'false',
                            'session_id' => 'null',
                        ];
                        $transactionArr = [
                            'subject' => 'Appointment',
                            'description' => $description[1],
                            'currency' => 'PKR',
                            'total_amount' => ($response->amount/100),
                            'user_id' => auth()->user()->id,
                            'status' => 1,
                        ];
                        TblTransaction::where('description',$description[1])->update($transactionArr);

                        event(new RealTimeMessage($getSession->doctor_id));
                        event(new updateQuePatient('update patient que'));
                    } catch (Exception $e) {
                        Log::error($e);
                    }
                    return redirect()->route('pat_appointments')->with("message", "Appointment Create Successfully");
                }else{
                    $getSession = DB::table('sessions')->where('session_id', $description[1])->first();

                    DB::table('appointments')->where('id', $getSession->appointment_id)->delete();
                    DB::table('sessions')->where('session_id', $description[1])->delete();

                    return redirect()->route('book_appointment',['id'=>$description[2]])->with('error', $response->actionCodeDescription);
                }
            }
            if($description[0] == "Order"){
                return $response;
            }
            if($description[0] == "Inclinic"){
                if($response->orderStatus == 2){
                    $pat = InClinics::where('user_id',$description[1])->update([
                        'status'=> 'pending'
                    ]);
                    $transactionArr = [
                        'subject' => 'Inclinic',
                        'description' => $description[1],
                        'currency' => 'PKR',
                        'total_amount' => ($response->amount/100),
                        'user_id' => auth()->user()->id,
                        'status' => 1,
                    ];
                    TblTransaction::where('description',$description[1])->update($transactionArr);
                    event(new \App\Events\InClinicPatientUpdate($description[1]));
                    return redirect()->route('inclinic_patient');
                }else{
                    return redirect()->back()->with('error',$response->actionCodeDescription);
                }
            }
        }
    }
}
