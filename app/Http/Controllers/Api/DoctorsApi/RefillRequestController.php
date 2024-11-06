<?php

namespace App\Http\Controllers\Api\DoctorsApi;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use App\RefillRequest;
use App\Prescription;
use Auth;
use DB;
use App\Cart;
use App\Models\AllProducts;
use App\Mail\RefillCompleteMailToPatient;
use App\Mail\RequestSessionPatientMail;
use App\Notification;
use Illuminate\Support\Facades\Mail;
use App\Events\RealTimeMessage;
use App\User;
use App\Helper;
use App\Referal;
use App\QuestDataTestCode;
use App\Session;
use Carbon\Carbon;
use Validator;


class RefillRequestController extends BaseController
{
    public function patient_refill_requests(){
        $user = auth()->user();
        $refills = RefillRequest::where('doctor_id', $user->id)
            ->where('granted', '0')
            ->where('session_req', '0')
            ->with([
                'prescription' => function ($q) {
                    $q->select('prescriptions.*', DB::raw('DATEDIFF(NOW(), prescriptions.created_at) AS days'));
                },
                'session' => function ($q) {
                    $q->select('id', 'sessions.*', DB::raw('DATE_FORMAT(date, "%M, %d %Y") as date'),
                     DB::raw('DATE_FORMAT(start_time, "%H:%i:%s") as start_time'),
                      DB::raw('DATE_FORMAT(end_time, "%H:%i:%s") as end_time'));
                },
                'patient', 'doctor', 'product'
            ])->get();
        if(!$refills->isEmpty()){
            $refillsData['code'] = 200;
            $refillsData['refills'] = $refills;
            return $this->sendResponse($refillsData,"Refill Request Found");
        } else{
            $refillsDataError['code'] = 200;
            return $this->sendError($refillsDataError,"No Refill Request Found");
        }
    }
    public function grant_refill(Request $request){
        $xc =RefillRequest::where('id', $request->refill_id)->update(['granted' => '1']);
        $refill = RefillRequest::where('id', $request->refill_id)->first();
        $pres = Prescription::where('id', $refill['pres_id'])->first();
        $price = DB::table('medicine_pricings')->where('id',$pres['price'])->first();
        $pres['price'] = $price->sale_price;
        $refill->pres = $pres;
        $cart = Cart::where('pres_id', $pres['id'])->first();
        $prod = AllProducts::where('id', $pres['medicine_id'])->first();
        $refill->prod = $prod;
        $pres_new = Prescription::create([
            'medicine_id' => $pres['medicine_id'],
            'session_id' => $refill['session_id'],
            'type' => $prod['mode'],
            'comment' => $request['instructions'],
            'usage' => 'Dosage: Every ' . $request['dose'] . ' for ' . $pres['med_days'] . ' days',
            'quantity' => $pres['qauntity'],
            'med_days' => $pres['med_days'],
            'med_unit' => $pres['med_unit'],
            'med_time' => $pres['med_time'],
            'price' => $pres['price'],
            'parent_id' => $pres['id']
        ]);
        Cart::create([
            'product_id' => $prod['id'],
            'name' => $prod['name'],
            'quantity' => $request['qauntity'],
            'price' => $pres['price'],
            'update_price' => $pres['price'],
            'product_mode' => $prod['mode'],
            'user_id' => $refill['patient_id'],
            'doc_id' => $refill['doctor_id'],
            'doc_session_id' => $refill['session_id'],
            'pres_id' => $pres_new['id'],
            'item_type' => 'prescribed',
            'status' => 'recommended',
            'product_image' => 'dummy_medicine.png',
            'refill_flag' => $request->refill_id,
            'map_marker_id' => $cart['map_marker_id']
        ]);
        try {
            $doc_user = DB::table('users')->where('id', $refill['doctor_id'])->first();
            $pat_user = DB::table('users')->where('id', $refill['patient_id'])->first();
            $data = [
                'doc_name' => $doc_user->name,
                'pat_name' => $pat_user->name,
                'pat_email' => $pat_user->email,
            ];
            Mail::to($pat_user->email)->send(new RefillCompleteMailToPatient($data));
            $text = "Refill Request from Dr " . $doc_user->name . " is granted";
            $noti= Notification::create([
                'user_id' =>  $pat_user->id,
                'type' => '/my/cart',
                'text' => $text,
                'refill_id' => $refill['id'],
            ]);
            $data = [
                'user_id' =>  $pat_user->id,
                'type' => '/my/cart',
                'text' => $text,
                'appoint_id' => "null",
                'session_id' => "null",
                'received' => 'false',
                'refill_id' => $refill['id'],
            ];
            // \App\Helper::firebase( $pat_user->id,'notification',$noti->id,$data);
            event(new RealTimeMessage($pat_user->id));
        } catch (\Exception $e) {
            Log::error($e);
        }
        $code['code'] =200;
        return $this->sendResponse($code,"Refill Granted Successfully!");
    }
    //refill request detail
    public function refill_request_detail($id){
       $refill_detail = RefillRequest::where('id', $id)->first();
       $patient_comment =$refill_detail->comment;
       $pres = Prescription::where('id', $refill_detail->pres_id)->first();
       $product = DB::table('tbl_products as product')->join('prescriptions as pres','product.id','=','pres.medicine_id')
       ->where('pres.medicine_id',$pres->medicine_id)
       ->select('product.name')
       ->first();
       $newtime = strtotime($pres->created_at);
       $date = date('M d, Y',$newtime);
       $refill_detailData['code'] =200;
       $refill_detailData['date'] =$date ;
       $refill_detailData['medicine_name'] =$product;
       $refill_detailData['number_of_days'] =$pres->med_days;
       $refill_detailData['unit'] =$pres->med_unit;
       $refill_detailData['quanity'] =$pres->quantity;
       $refill_detailData['dosage'] =$pres->med_time.'hrs';
       $refill_detailData['patient_comment'] =$refill_detail->comment;
       return $this->sendResponse($refill_detailData,"Refill request detail");
    }
    public function refill_sessions_detail($id){
        $user = Auth::user();
        $session = Session::find($id);
        $session->date = User::convert_utc_to_user_timezone($user->id, $session->created_at)['date'];
        if ($session->start_time == null) {
            $session->start_time = Helper::get_time_with_format($session->created_at);
        } else {
            $session->start_time = date('h:i A', strtotime('-15 minutes', strtotime($session->start_time)));
            $session->start_time = User::convert_utc_to_user_timezone($user->id, $session->start_time)['time'];
        }
        if ($session->end_time == null) {
            $session->end_time = Helper::get_time_with_format($session->updated_at);
        } else {
            $session->end_time = date('h:i A', strtotime('-15 minutes', strtotime($session->end_time)));
            $session->end_time = User::convert_utc_to_user_timezone($user->id, $session->end_time)['time'];
        }
        $user_id = $session->patient_id;
        $doctor_id = $session->doctor_id;
        $referred_doc = Referal::where('session_id', $session['id'])
            ->where('patient_id', $user_id)
            ->where('doctor_id', $doctor_id)
            ->leftjoin('users', 'referals.sp_doctor_id', 'users.id')
            ->select('users.name', 'users.last_name')
            ->get();
        if (count($referred_doc)) {
            $session->refered = "Dr." . $referred_doc[0]->name . " " . $referred_doc[0]->last_name;
        } else {
            $session->refered = null;
        }
        $doc = User::where('id', $session['doctor_id'])->first();
        $patient = User::where('id', $session['patient_id'])->first();
        $session->patient_name =$patient->name.' '.$patient->last_name;
        $session->doc_name = !empty($doc) ? $doc['name'] . ' ' . $doc['last_name']  : 'N/A';
        $pres = Prescription::where('session_id', $session['id'])->get();
        $pres_arr = [];
        foreach ($pres as $prod) {
            if ($prod['medicine_id'] != 0) {
                $product = AllProducts::find($prod['medicine_id']);
            } elseif($prod['imaging_id'] != 0){
                $product = AllProducts::find($prod['imaging_id']);
            } else {
                $product = QuestDataTestCode::where('TEST_CD', $prod['test_id'])->first();
            }
            if ($product->mode == 'lab-test') {
                $product->id = $product->TEST_CD;
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
            array_push($pres_arr, $prod);
        }
        $session->pres = $pres_arr;
        $user_type = auth()->user()->user_type;
        if($user_type == "doctor"){
            $percentage = DB::table('doctor_percentage')->where('doc_id',$doc->id)->first();
            $session->price = ($session->price * $percentage->percentage)/100;
            $session['code'] = 200;
            $session['user_type'] =$user_type;
            return $this->sendResponse($session,"Session Detail");
        }
        else{
            $sessionError['code'] = 200;
            return $this->sendError($sessionError,"you are not a doctor");
        }
    }
    //doctor request session instead of accepting refill request
    public function req_session(Request $request){
        $refill = RefillRequest::find($request['id']);
        $session_data = DB::table('sessions')->where('id', $refill->session_id)->first();
        $doc = User::find($refill->doctor_id);
        $pat = User::find($refill->patient_id);
        $doc_name = $doc->name . ' ' . $doc->last_name;
        $pat_name = $pat->name . ' ' . $pat->last_name;
        $doc_schedule = DB::table('doctor_schedules')->where('doctorId', $refill->doctor_id)->where('date','>=',today())->get();
        //dd($doc_schedule);
        $flag = '0'; //means session available
        if(count($doc_schedule) == 0){
            $doc_detail = array(
                'pat_name' => $pat_name,
                'doc_name' => $doc_name,
            );
           $flag = '1'; //means session is not available
           $message = "Schedule not available for future";
        }else{
            foreach ($doc_schedule as $sch) {
                $bookedSlots=DB::table('appointments')->where('date',$sch->date)->where('doctor_id',$refill->doctor_id)->count();
                $formatted_dt1=Carbon::parse($sch->start);
                $formatted_dt2=Carbon::parse($sch->end);
                $hours_diff = $formatted_dt1->diffInHours($formatted_dt2)*3;
                if($bookedSlots != $hours_diff){
                    $flag = '0'; //means session available
                    $message = "Schedule available for future";
                }
            }
        }
        $reqSession['code'] = 200;
        $reqSession['flag'] = $flag;
        $reqSession['message'] = $message;
        return $this->sendResponse($reqSession,"flag");
    }
    //session request send successfully if schedule is available
    public function send_session_req(Request $request){
        $refill = RefillRequest::find($request['id']);
        $session_data = DB::table('sessions')->where('id', $refill->session_id)->first();
        $doc = User::find($refill->doctor_id);
        $pat = User::find($refill->patient_id);
        $doc_name = $doc->name . ' ' . $doc->last_name;
        $pat_name = $pat->name . ' ' . $pat->last_name;
        // Uncomment the next line
        $data = RefillRequest::where('id', $request['id'])->update(['session_req' => '1']);
        try {
            $patient_data = array(
                'pat_name' => $pat_name,
                'doc_name' => $doc_name,
                'refill_comment' => $refill->comment,
            );
           Mail::to($pat->email)->send(new RequestSessionPatientMail($patient_data));
           $noti= Notification::create([
                'user_id' => $refill->patient_id,
                'text' => 'Dr. ' . $doc_name . ' requested a session with you',
                'type' => 'book/appointment/' . $session_data->specialization_id,
                'refill_id' => $request['id']
            ]);
            $data = [
                'user_id' =>  $refill->patient_id,
                'type' => 'book/appointment/' . $session_data->specialization_id,
                'text' => 'Dr. ' . $doc_name . ' requested a session with you',
                'appoint_id' => "null",
                'session_id' => "null",
                'received' => 'false',
                'refill_id' => $request['id']
            ];
            // \App\Helper::firebase($refill->patient_id,'notification',$noti->id,$data);
            event(new RealTimeMessage($refill->patient_id));
            $code['code']= 200;
            return $this->sendResponse($code,"Session Requested Successully!");
        } catch (\Throwable $th) {
            $code['code']= 200;
            $code['th']= $th;
            return $this->sendError($code,"Opps! Somthing Went Wrong!");
        }
        // $codeError['code']= 200;
        // return $this->sendResponse($codeError,"Session Requested Successully!");
    }
    //doctor creating schedule for sending session request
    public function request_session_schedule(Request $request){
        $validator = Validator::make($request->all(),[
            'date' => 'required',
            'startTimePicker' => 'required',
            'endTimePicker' => 'required'
        ]);
        if($validator->fails()){
            $validation['code'] = 200;
            $validation['validation'] = $validator->messages();
            return $this->sendError($validation,'Validation Error');
        } else{
            $doctorID=Auth::user()->id;
            $AvailabilityStart = $request->date.' '.$request->startTimePicker;
            $AvailabilityStart = User::convert_user_timezone_to_utc($doctorID,$AvailabilityStart)['datetime'];
            $AvailabilityEnd = $request->date.' '.$request->endTimePicker;
            $AvailabilityEnd = User::convert_user_timezone_to_utc($doctorID,$AvailabilityEnd)['datetime'];
            if($AvailabilityStart!=null && $AvailabilityEnd!=null){
                // $title = $request->AvailabilityTitle;
                $title ="Availability";
                $start =  date('H:i:s',strtotime($AvailabilityStart));
                $end =  date('H:i:s',strtotime($AvailabilityEnd));
                $date = date('Y-m-d',strtotime($AvailabilityStart));
                $startdate = date('Y-m-d H:i:s',strtotime($AvailabilityStart));
                $enddate = date('Y-m-d H:i:s',strtotime($AvailabilityEnd));
                // $color = $request->AvailabilityColor;
                $color = "#008000";
                if($title!=null && $start!=null && $end!=null && $color!=null){
                    $query=DB::table('doctor_schedules')->insert(
                        ['title' => $title, 'start' => $startdate,'end'=>$enddate,'color'=>$color,'slotStartTime'=>$start,'slotEndTime'=>$end,'doctorID'=>$doctorID,'date'=>$date]
                    );
                    if($query==true){
                        $code['code'] = 200;
                      return $this->sendResponse($code,'The Schedule has created');
                    } else{
                        $codeError['code'] = 200;
                        return $this->sendError($codeError,'The Schedule is not created');
                    }
                }
            }
        }

    }
}
