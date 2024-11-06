<?php

namespace App\Http\Controllers\Api\Video;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use App\Session;
use App\User;
use Carbon\Carbon;
use DB;
use App\Notification;
use App\Prescription;
use App\Models\PrescriptionsFile;
use App\Appointment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use PDF;
use App\Cart;
use App\City;
use App\Events\RealTimeMessage;
use App\QuestDataAOE;
use App\QuestDataTestCode;
use App\ImagingPrices;
use App\Mail\patientEvisitRecommendationMail;
use App\Mail\ReferDoctorToDoctorMail;
use App\Mail\ReferDoctorToPatientMail;
use App\ActivityLog;
use App\Helper;
use App\Events\redirectToCart;

class RecommendationController extends BaseController
{
    public function provider_notes_update(Request $request){
        $session_id = $request->session_id;
        Session::where('id', $session_id)->update(['provider_notes' => $request->note, 'diagnosis' => $request->diagnosis]);
        $providerNote['code'] = 200;
        $providerNote['session_id'] = $session_id;
        return $this->sendResponse($providerNote,"provider notes and diagnosis update");
    }
    public function recommendation_store(Request $request){

        $session_id = $request->session_id;
        $diagnosis = $request->diagnosis;
        $notes = $request->note;
        $session = Session::find($session_id);
        $patient_user = User::find($session->patient_id);
        if($session['appointment_id'] != null) {
            Appointment::where('id', $session['appointment_id'])->update(['status' => 'complete']);
        }
        $pres_list = Prescription::where('session_id', $session_id)->get();
        $items = count($pres_list);
        $dataMarge = [];
        $prePharma = [];
        $preLab = [];
        $preImaging = [];
        if ($items > 0) {
            foreach ($pres_list as $pres) {
                $product = DB::table('tbl_products')->where('id', $pres->medicine_id)->first();
                if ($pres->type == "medicine") {
                    $med_unit = DB::table('medicine_units')->where('unit',$pres->med_unit)->first();
                    $med_day = DB::table('medicine_days')->where('days',$pres->med_days)->first();
                    $price = DB::table('medicine_pricings')
                    ->where('product_id', $pres->medicine_id)
                    ->where('unit_id',$med_unit->id)
                    ->where('days_id',$med_day->id)
                    ->first();
                    $pres->price = $price->sale_price;
                    $up = DB::table('prescriptions')->where('id',$pres->id)->update(['price' => $price->id]);
                    Cart::create([
                        'product_id' => $pres->medicine_id,
                        'name' => $product->name,
                        'quantity' => $pres->quantity,
                        'price' => $pres->price,
                        'update_price' => $pres->price * $pres->quantity,
                        'product_mode' => $pres->type,
                        'user_id' => $session->patient_id,
                        'doc_id' => $session->doctor_id,
                        'doc_session_id' => $session_id,
                        'pres_id' => $pres->id,
                        'item_type' => 'prescribed',
                        'status' => 'recommended',
                        'checkout_status' => 1,
                        'purchase_status' => 1,
                        'product_image' => $product->featured_image
                    ]);
                    $singleItemMedicine = [
                        'medicine_name' => $product->name,
                        'quantity' => $pres->quantity,
                        'usage' => $pres->usage,
                        'comment' => $pres->comment,
                    ];
                    array_push($prePharma, $singleItemMedicine);
                } else if ($pres->type == "lab-test") {
                    $lab_test_price = QuestDataTestCode::where('TEST_CD', $pres->test_id)->first();
                    Cart::create([
                        'product_id' => $pres->test_id,
                        'name' => $lab_test_price->DESCRIPTION,
                        'quantity' => $pres->quantity,
                        'price' => $lab_test_price->SALE_PRICE,
                        'update_price' => $lab_test_price->SALE_PRICE * $pres->quantity,
                        'product_mode' => $pres->type,
                        'user_id' => $session->patient_id,
                        'doc_id' => $session->doctor_id,
                        'doc_session_id' => $session_id,
                        'pres_id' => $pres->id,
                        'item_type' => 'prescribed',
                        'status' => 'recommended',
                        'checkout_status' => 1,
                        'purchase_status' => 1,
                        'product_image' => $lab_test_price->featured_image,
                    ]);
                    $singleItemTest = [
                        'test_name' => $lab_test_price->DESCRIPTION,
                        'quantity' => $pres->quantity,
                        'comment' => $pres->comment,
                    ];
                    array_push($preLab, $singleItemTest);
                } else if ($pres->type == "imaging") {
                    $product = DB::table('tbl_products')->where('id', $pres->imaging_id)->first();
                    $location = DB::table('imaging_selected_location')->where('session_id', $pres->session_id)->where('product_id', $pres->imaging_id)->first();
                    $imaging_price = ImagingPrices::where('location_id', $location->imaging_location_id)->where('product_id',$pres->imaging_id)->first();
                    Cart::create([
                        'product_id' => $pres->imaging_id,
                        'name' => $product->name,
                        'quantity' => $pres->quantity,
                        'price' => $imaging_price->price,
                        'update_price' => $imaging_price->price * $pres->quantity,
                        'product_mode' => $pres->type,
                        'user_id' => $session->patient_id,
                        'doc_id' => $session->doctor_id,
                        'doc_session_id' => $session_id,
                        'pres_id' => $pres->id,
                        'item_type' => 'prescribed',
                        'status' => 'recommended',
                        'checkout_status' => 1,
                        'purchase_status' => 1,
                        'product_image' => $product->featured_image,
                        'location_id' => $location->imaging_location_id
                    ]);
                    $singleItemImaging = [
                        'imaging_name' => $product->name,
                        'quantity' => $pres->quantity,
                        'comment' => $pres->comment,
                    ];
                    array_push($preImaging, $singleItemImaging);
                }
            }
            Session::where('id', $session_id)->update([
                'diagnosis' => $diagnosis,
                'provider_notes' => $notes,
                'queue' => 0,
                'status' => 'ended',
                'join_enable' => '0',
                'cart_flag' => '1'
            ]);
        } else {
           Session::where('id', $session_id)->update(['cart_flag' => '1']);
        }
        try {
            array_push($dataMarge, array('pat_name' => ucwords($patient_user->name)));
            array_push($dataMarge, array('rec_test' => $preLab));
            array_push($dataMarge, array('rec_pharma' => $prePharma));
            array_push($dataMarge, array('rec_imaging' => $preImaging));
            array_push($dataMarge, array('pat_email' => ucwords($patient_user->email)));
            if($dataMarge[1]['rec_test'] != [] || $dataMarge[2]['rec_pharma'] != [] || $dataMarge[3]['rec_imaging'] != []){
                Mail::to($patient_user->email)->send(new patientEvisitRecommendationMail($dataMarge));
            }
            $text = "Session Complete Please Check Recommendations";
            $notification_id = Notification::create([
                'user_id' => $patient_user->id,
                'type' => '/my/cart',
                'text' => $text,
                'session_id' => $session_id,
            ]);
            $data = [
                'user_id' => $patient_user->id,
                'type' => '/my/cart',
                'text' => $text,
                'session_id' => $session_id,
                'received' => 'false',
                'appoint_id' => 'null',
                'refill_id' => 'null',
            ];
            // \App\Helper::firebase($patient_user->id,'notification',$notification_id->id,$data);
            event(new RealTimeMessage($patient_user->id));
        } catch (\Exception $e) {
            Log::error($e);
        }
        if ($items > 0) {
            ActivityLog::create([
                'activity' => 'Create session recommendations for ' . $patient_user->name . " " . $patient_user->last_name,
                'type' => 'session recommendations',
                'user_id' => $session->doctor_id,
                'user_type' => 'doctor',
                'identity' => $pres_list[0]->id,
                'party_involved' => $session->patient_id,
            ]);
        }
        $referal = DB::table('referals')->where('session_id', $session_id)->first();
        if ($referal != null) {
            $admin_detail = DB::table('users')->where('users.user_type', 'admin')->first();
            $doc_detail = DB::table('users')->where('users.id', $referal->doctor_id)->first();
            $refer_doc_detail = DB::table('users')->where('users.id', $referal->sp_doctor_id)->first();
            $pat_detail = DB::table('users')->where('users.id', $referal->patient_id)->first();
            $data = [
                'pat_name' => $pat_detail->name,
                'ref_from_name' => $doc_detail->name,
                'ref_to_name' => $refer_doc_detail->name,
                'ref_to_email' => $refer_doc_detail->email,
            ];
            $data1 = [
                'pat_name' => $pat_detail->name,
                'ref_from_name' => $doc_detail->name,
                'ref_to_name' => $refer_doc_detail->name,
                'pat_email' => $pat_detail->email,
                'comment' => $referal->comment,
            ];
            $admin_data = [
                'pat_name' => $pat_detail->name,
                'ref_from_name' => $doc_detail->name,
                'ref_to_name' => $refer_doc_detail->name,
                'pat_email' => $pat_detail->email,
                'comment' => $referal->comment,
            ];
            try {
                Mail::to($pat_detail->email)->send(new ReferDoctorToPatientMail($data));
                Mail::to($refer_doc_detail->email)->send(new ReferDoctorToDoctorMail($data1));
                Mail::to($admin_detail->email)->send(new ReferDoctorToDoctorMail($admin_data));
                $notification_id = Notification::create([
                    'user_id' => $referal->sp_doctor_id,
                    'text' => 'New patient is refered to you from Dr.' . $doc_detail->name,
                    'type' => '/patient/detail/' . $referal->patient_id,
                    'status' => 'new',
                    'session_id' => $session_id,
                ]);
                $text = 'Dr.'.$refer_doc_detail->name.' '.$refer_doc_detail->last_name.' reffered (Click & book an appointment)';
                $notification_id2 = Notification::create([
                    'user_id' => $referal->patient_id,
                    'text' => $text,
                    'type' => '/view/doctor/'.\Crypt::encrypt($refer_doc_detail->id),
                    'status' => 'new',
                    'session_id' => $session_id,
                ]);
                $data = [
                    'user_id' => $referal->patient_id,
                    'text' => $text,
                    'type' => '/view/doctor/'.\Crypt::encrypt($refer_doc_detail->id),
                    'session_id' => $session_id,
                    'received' => 'false',
                    'appoint_id' => 'null',
                    'refill_id' => 'null',
                ];
                // \App\Helper::firebase($referal->patient_id,'notification',$notification_id2->id,$data);
                event(new RealTimeMessage($referal->patient_id));
                $data = [
                    'user_id' => $referal->sp_doctor_id,
                    'text' => 'New patient is refered to you from Dr.' . $doc_detail->name,
                    'type' => '/patient/detail/' . $referal->patient_id,
                    'session_id' => $session_id,
                    'received' => 'false',
                    'appoint_id' => 'null',
                    'refill_id' => 'null',
                ];
                // \App\Helper::firebase($referal->patient_id,'notification',$notification_id->id,$data);
                event(new RealTimeMessage($referal->sp_doctor_id));
            } catch (\Throwable $th) {
                $sessionData = Session::find($session_id);
                $sessionData->received = false;
                event(new redirectToCart($session->id));
                // \App\Helper::firebase($sessionData->patient_id,'redirectToCart',$sessionData->id,$sessionData);
                $recommendation['code'] = 200;
                return $this->sendResponse($recommendation,'Recommendation Completed');
            }
        }
        $sessionData = Session::find($session_id);
        $sessionData->received = false;
        event(new redirectToCart($session->id));
        // \App\Helper::firebase($sessionData->patient_id,'redirectToCart',$sessionData->id,$sessionData);
        $recommendation['code'] = 200;
        return $this->sendResponse($recommendation,'Recommendation Completed');
    }
    public function prescriptions_dosageInfo($session_id){
        $prescriptions = DB::table('prescriptions')
                            ->where('session_id',$session_id)
                            ->get(['session_id','medicine_id','usage']);
        $prescriptionData['code'] = 200;
        $prescriptionData['prescriptions'] = $prescriptions;
        return $this->sendResponse($prescriptionData,'dosage Info');
    }
}
