<?php

namespace App\Http\Controllers;

use App\Session;
use App\Notification;
use App\Mail\EvisitBookMail;
use Illuminate\Http\Request;
use App\Events\RealTimeMessage;
use App\Events\updateQuePatient;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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


    //  <!--- One Phase Payment ---!>
    public function payment($data,$amount)
    {
        $description = urlencode($data);
        $this->amount = $amount;
        $data = explode('-',$data);
        if($data[0] == 'Evisit'){
            $orderId = 'CHCCE-'.$data[1];
        }
        $user_id = auth()->user()->id;
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
        if ($response->errorCode == 0) {
            session()->put('mdOrderId', $response->orderId);
        }
        return $response;

    }

    public function payment_return()
    {
        $orderId = session()->get('mdOrderId');
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
        }
    }
}
