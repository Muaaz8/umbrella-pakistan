<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use App\Mail\RefillRequestToDoctorMail;
use App\Events\RealTimeMessage;
use App\Notification;
use Auth;
use DB;
use Carbon\Carbon;
use App\Session;
use App\User;
use App\Cart;
use App\Prescription;
use App\QuestDataTestCode;
use App\Models\AllProducts;
use App\Referal;
use App\RefillRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Laracasts\Flash\Flash;


class MedicationController extends BaseController
{
    public function current_medication(Request $request){
        $id = Auth::user()->id;
        $params = $request->query();
        // dd($params);
        $pres = DB::table('sessions')
                ->where('sessions.patient_id', $id)
                ->join('prescriptions', 'prescriptions.session_id', '=', 'sessions.id')
                ->join('tbl_cart','prescriptions.id','tbl_cart.pres_id')
                ->where('type', 'medicine')
                ->leftJoin('refill_requests', 'prescriptions.id', '=', 'refill_requests.pres_id')
                ->join('tbl_products', 'tbl_products.id', '=', 'prescriptions.medicine_id')
                ->join('users', 'users.id', '=', 'sessions.doctor_id')
                ->when(array_key_exists("session_id", $params) && !empty($_GET['session_id']), function ($query) {
                    return $query->where('sessions.id', $_GET['session_id']);
                })
                ->when(array_key_exists("dates", $params) && !empty($params['dates']), function ($query) {
                    if (array_key_exists("dates", $_GET) && !empty($_GET['dates'])) {
                        $dateExplode = explode(" - ", $_GET['dates']);
                        $startDate = Carbon::createFromTimeStamp(strtotime($dateExplode[0]))->format('Y-m-d');
                        $endDate = Carbon::createFromTimeStamp(strtotime($dateExplode[1]))->format('Y-m-d');
                        return $query->whereBetween('sessions.created_at', [$startDate, $endDate]);
                    }
                })
                ->select('users.name as fname', 'users.last_name as lname', 'prescriptions.id as id',
                'tbl_products.featured_image as prod_img', 'prescriptions.medicine_id','prescriptions.med_days',
                'tbl_products.name as prod_name', 'users.name as fname','prescriptions.updated_at as total_days',
                    'users.last_name as lname', 'sessions.date as session_date',
                    'sessions.id as session_id', 'users.id as doc_id', 'sessions.start_time as start_time',
                    'sessions.end_time as end_time','sessions.doctor_id','sessions.session_id as ses_id','refill_requests.granted','refill_requests.session_req','tbl_cart.status as prescription_status'
                // ,'refill_requests.*'
                    )
                    ->orderBy('id','DESC')
                    ->paginate(20);

        if(!$pres->isEmpty()){
            $currentMedication['code'] = 200;
            $currentMedication['current_mediacation'] = $pres;
            return $this->sendResponse($currentMedication,"Current Medication");
        } else{
            $currentMedication['code'] = 200;
            return $this->sendError($currentMedication,"No Current Medication");

        }
    }
    public function current_medication_detail($id){
        $user = Auth::user();
        $session = Session::find($id);
        if($session != null){
            $session->date = User::convert_utc_to_user_timezone($user->id, $session->created_at)['date'];
        $doc = User::where('id', $session['doctor_id'])->first();
        $session->doc_name = !empty($doc) ? $doc['name'] . ' ' . $doc['last_name']  : 'N/A';
        $pres = Prescription::where('session_id', $session['id'])->get();
        $pres_arr = [];
            if(!$pres->isEmpty()){
                foreach ($pres as $prod) {
                    if ($prod['medicine_id'] != 0) {
                        $product = AllProducts::find($prod['medicine_id']);
                    } elseif($prod['imaging_id'] != 0){
                        $product = AllProducts::find($prod['imaging_id']);
                    } else {
                        $product = QuestDataTestCode::where('TEST_CD', $prod['test_id'])->first();
                    }
                    // dd($prod);
                    if ($product->mode == 'lab-test') {
                        $product->id = $product->TEST_CD;
                        // dd($product);
                    }
                    $cart = Cart::where('pres_id', $prod['id'])
                        ->where('product_id', $product->id)
                        ->where('refill_flag', '0')
                        ->first();

                    if (isset($cart['status']))
                        $prod->cart_status = $cart['status'];
                    else
                        $prod->cart_status = '';
                    $prod->prod_detail = $product;
                    // dd($prod);
                    array_push($pres_arr, $prod);
                }
                $session->code = 200;
                $session->pres = $pres_arr;
                return $this->sendResponse($session,"Medician Details");
            } else{
                $errorCode['code'] = 200;
                return $this->sendError($errorCode,"No Details");
            }
        } else{
            $errorCode['code'] = 200;
            return $this->sendError($errorCode,"Session not found");
        }
    }
    public function refill_request(Request $request){
        if (empty($request->note)) {
            $code['code'] = 200;
            return $this->sendError($code,"Note Cannot be empty");
        } else {
            $prescription = Prescription::where('id', $request->prescription_id)->first();
            $session_id = Session::where('id', $prescription->session_id)->first();
            $refilldata = RefillRequest::create([
                'pres_id' => $request->prescription_id,
                'prod_id' => $prescription->medicine_id,
                'session_id' => $session_id->id,
                'patient_id' => $session_id->patient_id,
                'doctor_id' => $session_id->doctor_id,
                'comment' => $request->note,
                'granted' => '0',
                'session_req' => '0'
            ]);
            try {
                $user = DB::table('users')->where('id', $session_id->doctor_id)->first();
                $data = [
                    'doc_name' => $user->name,
                    'doc_email' => $user->email,
                ];
                Mail::to($user->email)->send(new RefillRequestToDoctorMail($data));
                $text="Dr. ".$user->last_name." you have a new refill request";
                $noti =Notification::create([
                    'user_id' =>  $user->id,
                    'type' => '/patient/refill/requests',
                    'text' => $text,
                    'session_id' => $prescription->session_id,
                ]);
                $data = [
                    'user_id' =>  $user->id,
                    'type' => '/patient/refill/requests',
                    'text' => $text,
                    'appoint_id' => $prescription->session_id,
                    'session_id' => "null",
                    'received' => 'false',
                    'refill_id' => 'null',
                ];
                event(new RealTimeMessage($user->id));
                // \App\Helper::firebase($user->id,'notification',$noti->id,$data);
            } catch (\Exception $e) {
                $log = Log::error($e);
            }
           if($refilldata != null){
            $refilldataInfo['code'] = 200;
            $refilldataInfo['refilldata'] = $refilldata;
                return $this->sendResponse($refilldataInfo,"Refill request Submited");
           } else{
                $code['code'] = 200;
                return $this->sendError($code,"Refill request Submited");
           }

        }
    }

    public function current_medication_search(Request $request){
        $id = auth()->user()->id;
        $params = $request->query();
        if($request->session_id!=null && $request->dates!=null) {
            $request->dates = explode(" - ", $request->dates);
            $startdate = date('Y-m-d',strtotime($request->dates[0]));
            $enddate = date('Y-m-d',strtotime($request->dates[1]));
            // dd($params);
            $pres = DB::table('sessions')
                ->where('sessions.patient_id', $id)
                ->join('prescriptions', 'prescriptions.session_id', '=', 'sessions.id')
                ->join('tbl_cart','prescriptions.id','tbl_cart.pres_id')
                ->where('type', 'medicine')
                ->where('sessions.date','>=',$startdate)
                ->where('sessions.session_id','=',$request->session_id)
                ->where('sessions.date','<=',$enddate)
                ->leftJoin('refill_requests', 'prescriptions.id', '=', 'refill_requests.pres_id')
                ->join('tbl_products', 'tbl_products.id', '=', 'prescriptions.medicine_id')
                ->join('users', 'users.id', '=', 'sessions.doctor_id')
                ->when(array_key_exists("session_id", $params) && !empty($_GET['session_id']), function ($query) {
                    return $query->where('sessions.id', $_GET['session_id']);
                })
                ->when(array_key_exists("dates", $params) && !empty($params['dates']), function ($query) {
                    if (array_key_exists("dates", $_GET) && !empty($_GET['dates'])) {
                        $dateExplode = explode(" - ", $_GET['dates']);
                        $startDate = Carbon::createFromTimeStamp(strtotime($dateExplode[0]))->format('Y-m-d');
                        $endDate = Carbon::createFromTimeStamp(strtotime($dateExplode[1]))->format('Y-m-d');
                        return $query->whereBetween('sessions.created_at', [$startDate, $endDate]);
                    }
                })
                ->select('users.name as fname', 'users.last_name as lname', 'prescriptions.id as id',
                'tbl_products.featured_image as prod_img', 'prescriptions.medicine_id','prescriptions.med_days',
                'tbl_products.name as prod_name', 'users.name as fname','prescriptions.updated_at as total_days',
                    'users.last_name as lname', 'sessions.date as session_date',
                    'sessions.id as session_id', 'users.id as doc_id', 'sessions.start_time as start_time',
                    'sessions.end_time as end_time','sessions.doctor_id','sessions.session_id as ses_id','refill_requests.granted','refill_requests.session_req','tbl_cart.status as prescription_status'
                // ,'refill_requests.*'
                    )
                    ->orderBy('id','DESC')
                    ->get();

            $currentMedication['code'] = 200;
            $currentMedication['medications'] = $pres;
            return $this->sendResponse($currentMedication,'medicaion search');
        } elseif($request->session_id!=null){
            $params = $request->query();
            $pres = DB::table('sessions')
                    ->where('sessions.patient_id', $id)
                    ->join('prescriptions', 'prescriptions.session_id', '=', 'sessions.id')
                    ->join('tbl_cart','prescriptions.id','tbl_cart.pres_id')
                    ->where('type', 'medicine')
                    ->where('sessions.session_id','=',$request->session_id)
                    ->leftJoin('refill_requests', 'prescriptions.id', '=', 'refill_requests.pres_id')
                    ->join('tbl_products', 'tbl_products.id', '=', 'prescriptions.medicine_id')
                    ->join('users', 'users.id', '=', 'sessions.doctor_id')
                    ->when(array_key_exists("session_id", $params) && !empty($_GET['session_id']), function ($query) {
                        return $query->where('sessions.id', $_GET['session_id']);
                    })
                    ->when(array_key_exists("dates", $params) && !empty($params['dates']), function ($query) {
                        if (array_key_exists("dates", $_GET) && !empty($_GET['dates'])) {
                            $dateExplode = explode(" - ", $_GET['dates']);
                            $startDate = Carbon::createFromTimeStamp(strtotime($dateExplode[0]))->format('Y-m-d');
                            $endDate = Carbon::createFromTimeStamp(strtotime($dateExplode[1]))->format('Y-m-d');
                            return $query->whereBetween('sessions.created_at', [$startDate, $endDate]);
                        }
                    })
                    ->select('users.name as fname', 'users.last_name as lname', 'prescriptions.id as id',
                    'tbl_products.featured_image as prod_img', 'prescriptions.medicine_id','prescriptions.med_days',
                    'tbl_products.name as prod_name', 'users.name as fname','prescriptions.updated_at as total_days',
                        'users.last_name as lname', 'sessions.date as session_date',
                        'sessions.id as session_id', 'users.id as doc_id', 'sessions.start_time as start_time',
                        'sessions.end_time as end_time','sessions.doctor_id','sessions.session_id as ses_id','refill_requests.granted','refill_requests.session_req','tbl_cart.status as prescription_status'
                    // ,'refill_requests.*'
                        )
                        ->orderBy('id','DESC')
                        ->get();
            // dd($pres);

            $currentMedication['code'] = 200;
            $currentMedication['medications'] = $pres;
            return $this->sendResponse($currentMedication,'medicaion search');
        } elseif($request->dates!=null) {
            $request->dates = explode(" - ", $request->dates);
            $startdate = date('Y-m-d',strtotime($request->dates[0]));
            $enddate = date('Y-m-d',strtotime($request->dates[1]));
            $pres = DB::table('sessions')
            ->where('sessions.patient_id', $id)
            ->join('prescriptions', 'prescriptions.session_id', '=', 'sessions.id')
            ->join('tbl_cart','prescriptions.id','tbl_cart.pres_id')
            ->where('type', 'medicine')
            ->where('sessions.date','>=',$startdate)
            ->where('sessions.date','<=',$enddate)
            ->leftJoin('refill_requests', 'prescriptions.id', '=', 'refill_requests.pres_id')
            ->join('tbl_products', 'tbl_products.id', '=', 'prescriptions.medicine_id')
            ->join('users', 'users.id', '=', 'sessions.doctor_id')
            ->when(array_key_exists("session_id", $params) && !empty($_GET['session_id']), function ($query) {
                return $query->where('sessions.id', $_GET['session_id']);
            })
            ->when(array_key_exists("dates", $params) && !empty($params['dates']), function ($query) {
                if (array_key_exists("dates", $_GET) && !empty($_GET['dates'])) {
                    $dateExplode = explode(" - ", $_GET['dates']);
                    $startDate = Carbon::createFromTimeStamp(strtotime($dateExplode[0]))->format('Y-m-d');
                    $endDate = Carbon::createFromTimeStamp(strtotime($dateExplode[1]))->format('Y-m-d');
                    return $query->whereBetween('sessions.created_at', [$startDate, $endDate]);
                }
            })
            ->select('users.name as fname', 'users.last_name as lname', 'prescriptions.id as id',
            'tbl_products.featured_image as prod_img', 'prescriptions.medicine_id','prescriptions.med_days',
            'tbl_products.name as prod_name', 'users.name as fname','prescriptions.updated_at as total_days',
                'users.last_name as lname', 'sessions.date as session_date',
                'sessions.id as session_id', 'users.id as doc_id', 'sessions.start_time as start_time',
                'sessions.end_time as end_time','sessions.doctor_id','sessions.session_id as ses_id','refill_requests.granted','refill_requests.session_req','tbl_cart.status as prescription_status'
            // ,'refill_requests.*'
                )
                ->orderBy('id','DESC')
                ->get();
            $currentMedication['code'] = 200;
            $currentMedication['medications'] = $pres;
            return $this->sendResponse($currentMedication,'medicaion search');
        }

    }
}
