<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use App\ActivityLog;
use App\City;
use App\Models\ProductsSubCategory;
use App\Events\CountCartItem;
use App\Events\RealTimeMessage;
use Flash;
use App\ImagingOrder;
use App\ImagingPrices;
use App\LabOrder;
use App\Mail\OrderConfirmationEmail;
use App\Models\AllProducts;
use App\Models\TblTransaction;
use App\Notification;
use App\Pharmacy;
use App\Prescription;
use App\RefillRequest;
use App\QuestDataAOE;
use App\QuestLab;
use App\Repositories\AllProductsRepository;
use App\Session as SessionModel;
use App\State;
use App\TblCart as AppTblCart;
use App\User;
use App\VendorAccount;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use DB;

class PaymentController extends BaseController
{
    private $Pharmacy;
    private $allProductsRepository;

    public function __construct(Pharmacy $Pharmacy, AllProductsRepository $allProductsRepo)
    {
        $this->Pharmacy = $Pharmacy;
        $this->allProductsRepository = $allProductsRepo;
    }

    public function get_card_details(Request $request){
        $billingDetails = DB::table('card_details')->where('id',$request->id)->select('billing')->first();
        $billing = unserialize($billingDetails->billing);
        $shippingDetails = DB::table('card_details')->where('id',$request->id)->select('shipping')->first();
        $shipping = unserialize($shippingDetails->shipping);
        $oldcards['code'] = 200;
        $oldcards['billing'] = $billing;
        $oldcards['shipping'] = $shipping;
        return $this->sendResponse($oldcards,'Old cards');
    }
    public function authorize_create_new_order(Request $request){
        if(strlen($request->exp_month) == 1){
            $request->exp_month = "0".$request->exp_month;
        }
        $user = Auth::user();

        $cartPreLab = [];
        $cartCntLab = [];

        $cartPreMed = [];
        $cartCntMed = [];

        $cartPreImg = [];
        $cartCntImg = [];

        $orderAllIds = [];
        //get medicine items from tbl_product table
        $getAllCartProducts = DB::table('tbl_cart')
            ->where('user_id', $user->id)
            ->where('show_product', '1')
            ->where('status', 'recommended')
            ->get();
        $orderId = '';
        $dateString = Carbon::now()->format('yHis');
        $getLastOrderId = DB::table('tbl_orders')->orderBy('id', 'desc')->first();
        $randNumber=rand(1,100);
        if ($getLastOrderId != null) {
            $orderId = $getLastOrderId->order_id + 1+$randNumber;
        } else {
            $orderId = $dateString+$randNumber;
        }
        foreach ($getAllCartProducts as $item) {
            if ($item->item_type == 'counter' && $item->product_mode == 'lab-test') {
                $item->orderSubId = $orderId . $item->product_id;
                $item->orderSystemId = $orderId;
                array_push($orderAllIds, $orderId . $item->product_id);
                array_push($cartCntLab, $item);
            } else if ($item->item_type == 'prescribed' && $item->product_mode == 'lab-test') {
                $item->orderSubId = $orderId . $item->product_id;
                $item->orderSystemId = $orderId;
                array_push($orderAllIds, $orderId . $item->product_id);
                array_push($cartPreLab, $item);
            } else if ($item->item_type == 'counter' && $item->product_mode == 'medicine') {
                $item->orderSubId = $orderId . $item->product_id;
                $item->orderSystemId = $orderId;
                array_push($orderAllIds, $orderId . $item->product_id);
                array_push($cartCntMed, $item);
            } else if ($item->item_type == 'prescribed' && $item->product_mode == 'medicine') {
                $item->orderSubId = $orderId . $item->product_id;
                $item->orderSystemId = $orderId;
                array_push($orderAllIds, $orderId . $item->product_id);
                array_push($cartPreMed, $item);
            } else if ($item->item_type == 'counter' && $item->product_mode == 'imaging') {
                $item->orderSubId = $orderId . $item->product_id;
                $item->orderSystemId = $orderId;
                array_push($orderAllIds, $orderId . $item->product_id);
                array_push($cartCntImg, $item);
            } else if ($item->item_type == 'prescribed' && $item->product_mode == 'imaging') {
                $item->orderSubId = $orderId . $item->product_id;
                $item->orderSystemId = $orderId;
                array_push($orderAllIds, $orderId . $item->product_id);
                array_push($cartPreImg, $item);
            }
        }
        $totalPaymentToCharge = $request->payAble;
        $billing = array(
            'number' => substr($request->card_number,0,1) . 'xxx-xxxx-xxxx-' . substr($request->card_number, -4),
            'expiration_month' => $request->exp_month,
            'expiration_year' => $request->exp_year,
            "csc" => $request->cvc,
            "name" => $request->card_holder_name." ".$request->card_holder_last_name,
            "email" => $request->email,
            "street_address" => $request->address,
            "city" => $request->city,
            "state" => $request->state_code,
            "zip" => $request->zipcode,
            'phoneNumber' => $request->phoneNumber,
        );
        if (isset($request->shipping_customer_name)) {
            $shipping = array(
                "name" => $request->shipping_customer_name,
                "email" => $request->shipping_customer_email,
                "phone" => $request->shipping_customer_phone,
                "street_address" => $request->shipping_customer_address,
                "city" => $request->shipping_customer_city,
                "state" => $request->shipping_customer_state,
                "zip" => $request->shipping_customer_zip,
            );
        } else {
            $shipping = array(
                "name" => $request->card_holder_name." ".$request->card_holder_last_name,
                "email" => $request->email,
                "phone" => $request->phoneNumber,
                "street_address" => $request->address,
                "city" => $request->city,
                "state" => $request->state_code,
                "zip" => $request->zipcode,
            );
        }
        $input = [
            'user' => [
                'description' => $request->card_holder_name,
                'email' => $request->email,
                'firstname' => $request->card_holder_name,
                'lastname' => $request->card_holder_last_name,
                'phoneNumber' => $request->phoneNumber,
            ],
            'info' => [
                'subject' => "Order",
                'user_id' => $user->id,
                'description' => $orderId,
                'amount' => $totalPaymentToCharge,
            ],
            'billing_info' => [
                'amount' => $totalPaymentToCharge,
                'credit_card' => [
                    'number' => $request->card_number,
                    'expiration_month' => $request->exp_month,
                    'expiration_year' => $request->exp_year,
                ],
                'integrator_id' => 'Order-' . $orderId,
                'csc' => $request->cvc,
                'billing_address' => [
                    'name' => $request->card_holder_name." ".$request->card_holder_last_name,
                    'street_address' => $request->address,
                    'city' => $request->city,
                    'state' => $request->state_code,
                    'zip' => $request->zipcode,
                ]
            ]
        ];
        $pay = new \App\Http\Controllers\PaymentController();
        $response = ($pay->new_createCustomerProfile($input));
        $flag = true;
        if ($response['messages']['message'][0]['text'] == 'Successful.') {
            $agent = 'web';
            $transactionArr = [
                'subject' => 'Order',
                'description' => $orderId,
                'total_amount' => $totalPaymentToCharge,
                'user_id' => $user->id,
            ];
            TblTransaction::create($transactionArr);
            if ($flag) {
                $profileId = $response['transactionResponse']['profile']['customerProfileId'];
                $paymentId = $response['transactionResponse']['profile']['customerPaymentProfileId'];
                DB::table('card_details')->insert([
                    'user_id' => Auth::user()->id,
                    'customerProfileId' => $profileId,
                    'customerPaymentProfileId' => $paymentId,
                    'card_number' => substr($request->card_number, -4),
                    'billing' => serialize($billing),
                    'shipping' => serialize($shipping),
                    'card_type' =>substr($request->card_number, 0,1),
                ]);
            }
            $this->seprate_order_create($cartCntLab, $cartPreLab, $cartPreMed, $cartCntMed, $cartPreImg, $cartCntImg, $shipping);
            DB::table('tbl_orders')->insert([
                'order_state' => $user->state_id,
                'order_id' => $orderId,
                'order_sub_id' => serialize($orderAllIds),
                'customer_id' => $user->id,
                'total' => $totalPaymentToCharge,
                'total_tax' => 0,
                'billing' => serialize($billing),
                'shipping' => serialize($shipping),
                'payment' => serialize($response),
                'payment_title' => 'Direct Bank Transfer',
                'payment_method' => 'via Authorize.net',
                'cart_items' => '',
                "lab_order_approvals" => '',
                'currency' => 'US',
                'order_status' => 'paid',
                'agent' => $agent,
                'created_at' => Carbon::now(),
            ]);
            $this->orderNotify($orderId, $getAllCartProducts->toArray(), $totalPaymentToCharge, $billing['number'], $billing['name'], "478066367",$billing['email'],$shipping['email']);
            foreach ($getAllCartProducts as $ci) {
                if ($ci->refill_flag != '0') {
                    RefillRequest::where('id', $ci->refill_flag)->update(['granted' => null]);
                }
                DB::table('tbl_cart')->where('id', $ci->id)->update([
                    'status' => 'purchased',
                    'purchase_status' => '0',
                    'checkout_status' => '0',
                ]);
            }
            $successPayemnt['code'] = 200;
            $successPayemnt['order_id'] =$orderId;
            return $this->sendResponse($successPayemnt,"Payment successfull!");
        } else {
            $code = $response['messages']['message'][0]['code'];
            $message = TblTransaction::errorCode($code);
            DB::table('error_log')->insert([
                'user_id' => $user->id,
                'Error_code' => $code,
                'Error_text' => $response['messages']['message'][0]['text'],
            ]);
            Flash::error($message);
            $errorPayemnt['code'] = 200;
            $errorPayemnt['message'] = $message;
            return $this->sendError($errorPayemnt,"Payment not unsuccessfull!");
        }
    }
    public function orderNotify($order_main_id, $order_cart_items, $orderAmount, $cardNumber, $cardHolder, $transaction_id, $billingEmail,$shippingEmail){
        $orderDate=DB::table('tbl_orders')->where('order_id',$order_main_id)->first();
        try {
            $users = User::where('id', Auth::user()->id)
                ->orWhere('user_type', 'admin')
                ->orWhere('user_type', 'admin_lab')
                ->orWhere('user_type', 'editor_lab')
                ->orWhere('user_type', 'admin_imaging')
                ->orWhere('user_type', 'editor_imaging')
                ->orWhere('user_type', 'admin_pharmacy')
                ->orWhere('user_type', 'editor_pharmacy')
                ->get();


            $get_order_total = $orderAmount;


            foreach ($users as $u) {
                $time = User::convert_user_timezone_to_utc($u->id, $orderDate->created_at);
                $userDetails = array(
                    'cardNumber' => $cardNumber,
                    'cardHolder' => $cardHolder,
                    'transaction_id' => $transaction_id,
                    'order_total' => $get_order_total,
                    'order_date' => $time['datetime'],
                    'order_id' => $order_main_id,
                    'pat_email' => Auth::user()->email
                );
                if($u->id==Auth::user()->id)
                {
                    if($billingEmail != $shippingEmail){
                        Mail::to($shippingEmail)->send(new OrderConfirmationEmail($order_cart_items, $userDetails));
                    }
                     Mail::to($u->email)->send(new OrderConfirmationEmail($order_cart_items, $userDetails));
                } else{
                    DB::table('emails_to_be_sent')->insert([
                        'reciever_id' => $u->id,
                        'template_name' => 'patientOrderPlace',
                        'markdowndata' => serialize($userDetails),
                        'order_cart_item' => serialize($order_cart_items),
                        'status' => 'pending',
                    ]);
                }
                $text = "New Order Place By " . Auth::user()->name;
                if($u->user_type=='doctor')
                {
                    Notification::create([
                        'user_id' => $u->id,
                        'type' => '/doctor/order',
                        'text' => $text,
                    ]);
                }
                else if($u->user_type=='patient'){
                    Notification::create([
                        'user_id' => $u->id,
                        'type' => '/patient/all/orders',
                        'text' => $text,
                    ]);
                }
                else if($u->user_type=='admin'){
                    Notification::create([
                        'user_id' => $u->id,
                        'type' => '/admin/all/orders',
                        'text' => $text,
                    ]);
                }
                if($u->id==Auth::user()->id)
                {
                    ActivityLog::create([
                        'user_id' => $u->id,
                        'activity' => 'purchased',
                        'identity' => 'xx',
                        'type' => '/orders',
                        'user_type' => 'patient',
                        'text' => $text,
                    ]);
                }
                $text = "New Order Place By " . $order_main_id;
                event(new RealTimeMessage('Hello World'));
            }
        } catch (\Exception $e) {
            Log::error($e);
        }
    }
    public function seprate_order_create($cartCntLab, $cartPreLab, $cartPreMed, $cartCntMed, $cartPreImg, $cartCntImg, $shippingDetails){
        if ($cartCntLab != null) {
            foreach ($cartCntLab as $order) {
                LabOrder::create([
                    'user_id' => Auth::user()->id,
                    'order_id' => $order->orderSystemId,
                    'product_id' => $order->product_id,
                    'session_id' => $order->doc_session_id,
                    'pres_id' => $order->pres_id,
                    'status' => 'lab-editor-approval',
                    'date' => date('Y-m-d'),
                    'time' => 0,
                    'type' => 'Counter',
                    'map_marker_id' => 0,
                    'price' => $order->update_price,
                    'sub_order_id' => $order->orderSubId,
                ]);
            }
            $text = "New Online Lab Order Place By " . Auth::user()->name;
            $lab_admins = DB::table('users')->where('user_type','admin_lab')->get();
            foreach($lab_admins as $admin)
            {
                Notification::create([
                    'user_id' => $admin->id,
                    'type' => '/unassigned/lab/orders',
                    'text' => $text,
                ]);
            }
        }
        if ($cartPreLab != null) {
            $account = VendorAccount::where('vendor', 'quest')->first();
            $testsData = [];

            foreach ($cartPreLab as $item) {
                LabOrder::create([
                    'user_id' => Auth::user()->id,
                    'order_id' => $item->orderSystemId,
                    'product_id' => $item->product_id,
                    'session_id' => $item->doc_session_id,
                    'pres_id' => $item->pres_id,
                    'status' => 'essa-forwarded',
                    'type' => 'Prescribed',
                    'date' => date('Y-m-d'),
                    'time' => 0,
                    'map_marker_id' => 0,
                    'price' => $item->update_price,
                    'sub_order_id' => $item->orderSubId,
                ]);

                $data = DB::table('patient_lab_recomend_aoe')->where('session_id', $item->doc_session_id)->where('testCode', $item->product_id)->first();
                $aoes = '';
                if ($data != null) {
                    $aoes = unserialize($data->aoes);
                    array_push($testsData, ['testCode' => $item->product_id, 'testName' => $item->name, 'aoes' => $aoes]);
                } else {
                    array_push($testsData, ['testCode' => $item->product_id, 'testName' => $item->name, 'aoes' => '']);
                }
            }

            $doctor = User::find($item->doc_id);
            $patient = User::find($item->user_id);

            $timestamp = time();
            $lab_ref_num = 'UMD' . $item->user_id . 'Q' . $timestamp;
            $orderedtestcode = json_encode($testsData);
            $name = json_encode($testsData);
            $testAoes = json_encode($testsData);
            $collect_date = date('Y-m-d', strtotime($item->created_at));
            $collect_time = date('H:i:s', strtotime($item->created_at));
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
                'order_id' => $item->orderSystemId,
                'umd_patient_id' => $item->user_id,
                'quest_patient_id' => $item->user_id,
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
        }
        if ($cartPreImg != null) {
            foreach ($cartPreImg as $key => $order) {
                ImagingOrder::create([
                    'user_id' => Auth::user()->id,
                    'order_id' => $order->orderSystemId,
                    'product_id' => $order->product_id,
                    'session_id' => $order->doc_session_id,
                    'pres_id' => $order->pres_id,
                    'location_id' => $order->location_id,
                    'status' => 'pending',
                    'price' => $order->update_price,
                    'sub_order_id' => $order->orderSubId,
                ]);
                $order_id = $order->orderSubId;
                $doctor = User::find($order->doc_id);
                $patient = User::find($order->user_id);
                $state = State::find($doctor->state_id);
                $city = City::find($doctor->city_id);
                $p_city = City::find($patient->city_id);
                $p_state = State::find($patient->state_id);
                $orderDate = User::convert_utc_to_user_timezone($patient->id, Carbon::now()->format('Y-m-d H:i:s'));
                $date = str_replace('-', '/',  $patient->date_of_birth);
                $patient->date_of_birth = date('m/d/Y', strtotime($date));

                $presc_meds[$order->doc_session_id]['first_name'] = $patient->name." ".$patient->last_name;;
                $presc_meds[$order->doc_session_id]['address'] = $patient->office_address;
                $presc_meds[$order->doc_session_id]['city'] = $p_city->name;
                $presc_meds[$order->doc_session_id]['state'] = $p_state->name;
                $presc_meds[$order->doc_session_id]['zip_code'] = $patient->zip_code;
                $presc_meds[$order->doc_session_id]['email_address'] = $patient->email;
                $presc_meds[$order->doc_session_id]['phone_number'] = $patient->phone_number;
                $presc_meds[$order->doc_session_id]['patient_dob'] = $patient->date_of_birth;
                $presc_meds[$order->doc_session_id]['patient_gender'] = $patient->gender;
                $presc_meds[$order->doc_session_id]['patient_id'] = $patient->id;
                $presc_meds[$order->doc_session_id]['phy_id'] = $doctor->id;
                $presc_meds[$order->doc_session_id]['order_sub_id'] = $order->orderSubId;
                $presc_meds[$order->doc_session_id]['order_main_id'] = $order->orderSystemId;
                $presc_meds[$order->doc_session_id]['phy_by'] = $doctor->name . ' ' . $doctor->last_name;
                $presc_meds[$order->doc_session_id]['phy_phone_number'] = $doctor->phone_number;
                $presc_meds[$order->doc_session_id]['phy_address'] = $doctor->office_address;
                $presc_meds[$order->doc_session_id]['phy_city'] = $city->name;
                $presc_meds[$order->doc_session_id]['phy_state'] = $state->name;
                $presc_meds[$order->doc_session_id]['phy_zip_code'] = $doctor->zip_code;
                $presc_meds[$order->doc_session_id]['NPI'] = $doctor->nip_number;
                $presc_meds[$order->doc_session_id]['signature'] = $doctor->signature;
                $presc_meds[$order->doc_session_id]['date'] = $orderDate['date'];

                $pres = Prescription::find($order->pres_id);
                $img = DB::table('tbl_products')->where('id',$pres->imaging_id)->first();
                $loc = DB::table('imaging_locations')->where('id',$order->location_id)->first();
                $presc_meds[$order->doc_session_id]['items'][$key]['name'] = $img->name;
                $presc_meds[$order->doc_session_id]['items'][$key]['address'] = $loc->address;
                $presc_meds[$order->doc_session_id]['items'][$key]['zip_code'] = $loc->zip_code;
            }
            $recom_obj = new \App\Http\Controllers\RecommendationController($this->allProductsRepository);
            $recom_obj->new_imaging_order($presc_meds);
        }
        if ($cartCntImg != null) {
            foreach ($cartCntImg as $order) {
                ImagingOrder::create([
                    'user_id' => Auth::user()->id,
                    'order_id' => $order->orderSystemId,
                    'product_id' => $order->product_id,
                    'session_id' => $order->doc_session_id,
                    'pres_id' => $order->pres_id,
                    'location_id' => $order->location_id,
                    'status' => 'pending',
                    'price' => $order->update_price,
                    'sub_order_id' => $order->orderSubId,
                ]);
            }
        }
        if ($cartPreMed != null) {
            $presc_meds = array();
            foreach ($cartPreMed as $key => $item) {
                DB::table('medicine_order')->insert([
                    'user_id' => $item->user_id,
                    'order_main_id' => $item->orderSystemId,
                    'order_sub_id' => $item->orderSubId,
                    'order_product_id' => $item->product_id,
                    'pro_mode' => 'Prescribed',
                    'update_price' => $item->update_price,
                    'session_id' => $item->doc_session_id,
                    'status' => 'order-placed',
                ]);
                $doctor = User::find($item->doc_id);
                $patient = User::find($item->user_id);
                $state = State::find($doctor->state_id);
                $city = City::find($doctor->city_id);
                $orderDate = User::convert_utc_to_user_timezone($patient->id, Carbon::now()->format('Y-m-d H:i:s'));
                $date = str_replace('-', '/',  $patient->date_of_birth);
                $patient->date_of_birth = date('m/d/Y', strtotime($date));
                $presc_meds[$item->doc_session_id]['first_name'] = $patient->name." ".$patient->last_name;;
                $presc_meds[$item->doc_session_id]['address'] = $shippingDetails['street_address'];
                $presc_meds[$item->doc_session_id]['city'] = $shippingDetails['city'];
                $presc_meds[$item->doc_session_id]['state'] = $shippingDetails['state'];
                $presc_meds[$item->doc_session_id]['zip_code'] = $shippingDetails['zip'];
                $presc_meds[$item->doc_session_id]['email_address'] = $patient->email;
                $presc_meds[$item->doc_session_id]['phone_number'] = $shippingDetails['phone'];
                $presc_meds[$item->doc_session_id]['patient_dob'] = $patient->date_of_birth;
                $presc_meds[$item->doc_session_id]['patient_gender'] = $patient->gender;
                $presc_meds[$item->doc_session_id]['patient_id'] = $patient->id;
                $presc_meds[$item->doc_session_id]['phy_id'] = $doctor->id;
                $presc_meds[$item->doc_session_id]['order_sub_id'] = $item->orderSubId;
                $presc_meds[$item->doc_session_id]['order_main_id'] = $item->orderSystemId;
                $presc_meds[$item->doc_session_id]['phy_by'] = $doctor->name . ' ' . $doctor->last_name;
                $presc_meds[$item->doc_session_id]['phy_phone_number'] = $doctor->phone_number;
                $presc_meds[$item->doc_session_id]['phy_address'] = $doctor->office_address;
                $presc_meds[$item->doc_session_id]['phy_city'] = $city->name;
                $presc_meds[$item->doc_session_id]['phy_state'] = $state->name;
                $presc_meds[$item->doc_session_id]['phy_zip_code'] = $doctor->zip_code;
                $presc_meds[$item->doc_session_id]['NPI'] = $doctor->nip_number;
                $presc_meds[$item->doc_session_id]['signature'] = $doctor->signature;
                $presc_meds[$item->doc_session_id]['date'] = $orderDate['date'];
                $pres = Prescription::find($item->pres_id);
                $item->med_days = $pres->med_days;
                $item->med_unit = $pres->med_unit;
                $item->med_time = $pres->med_time;
                $item->medicine_usage = $item->med_unit . " " . $item->med_time . " " . $item->med_days;
                $presc_meds[$item->doc_session_id]['items'][$key] = $item;
            }
            $recom_obj = new \App\Http\Controllers\RecommendationController($this->allProductsRepository);
            $recom_obj->new_eprescription($presc_meds);
        }
        if ($cartCntMed != null) {
            foreach ($cartCntMed as $item) {
                DB::table('medicine_order')->insert([
                    'user_id' => $item->user_id,
                    'order_main_id' => $item->orderSystemId,
                    'order_sub_id' => $item->orderSubId,
                    'order_product_id' => $item->product_id,
                    'pro_mode' => 'Counter',
                    'update_price' => $item->update_price,
                    'session_id' => $item->doc_session_id,
                    'status' => 'order-placed',
                ]);
            }
        }
    }
    public function oldCard_payment(Request $request){
        $user = Auth::user();
        if (isset($request->shipping_customer_name)) {
            $shipping = array(
                "name" => $request->shipping_customer_name,
                "email" => $request->shipping_customer_email,
                "phone" => $request->shipping_customer_phone,
                "street_address" => $request->shipping_customer_address,
                "city" => $request->shipping_customer_city,
                "state" => $request->shipping_customer_state,
                "zip" => $request->shipping_customer_zip,
            );
        } else{
            $shipping = array(
                "name" => $request->card_holder_name." ".$request->card_holder_last_name,
                "email" => $request->email,
                "phone" => $request->phoneNumber,
                "street_address" => $request->address,
                "city" => $request->city,
                "state" => $request->state_code,
                "zip" => $request->zipcode,
            );
        }

        $billing = array(
            'number' => substr($request->card_number,0,1) . 'xxx-xxxx-xxxx-' . substr($request->card_number, -4),
            'expiration_month' => $request->exp_month,
            'expiration_year' => $request->exp_year,
            "csc" => $request->cvc,
            "name" => $request->card_holder_name." ".$request->card_holder_last_name,
            "email" => $request->email,
            "street_address" => $request->address,
            "city" => $request->city,
            "state" => $request->state_code,
            "zip" => $request->zipcode,
            'phoneNumber' => $request->phoneNumber,
        );
        $cartPreLab = [];
        $cartCntLab = [];

        $cartPreMed = [];
        $cartCntMed = [];

        $cartPreImg = [];
        $cartCntImg = [];

        $orderAllIds = [];
        //get medicine items from tbl_product table
        $getAllCartProducts = DB::table('tbl_cart')
            ->where('user_id', Auth::user()->id)
            ->where('show_product', '1')
            ->where('status', 'recommended')
            ->get();
        $orderId = '';
        $dateString = Carbon::now()->format('yHis');
        $getLastOrderId = DB::table('tbl_orders')->orderBy('id', 'desc')->first();
        $randNumber=rand(1,100);
        if ($getLastOrderId != null) {
            $orderId = $getLastOrderId->order_id + 1+$randNumber;
        } else {
            $orderId = $dateString+$randNumber;
        }
        foreach ($getAllCartProducts as $item) {
            if ($item->item_type == 'counter' && $item->product_mode == 'lab-test') {
                $item->orderSubId = $orderId . $item->product_id;
                $item->orderSystemId = $orderId;
                array_push($orderAllIds, $orderId . $item->product_id);
                array_push($cartCntLab, $item);
            } else if ($item->item_type == 'prescribed' && $item->product_mode == 'lab-test') {
                $item->orderSubId = $orderId . $item->product_id;
                $item->orderSystemId = $orderId;
                array_push($orderAllIds, $orderId . $item->product_id);
                array_push($cartPreLab, $item);
            } else if ($item->item_type == 'counter' && $item->product_mode == 'medicine') {
                $item->orderSubId = $orderId . $item->product_id;
                $item->orderSystemId = $orderId;
                array_push($orderAllIds, $orderId . $item->product_id);
                array_push($cartCntMed, $item);
            } else if ($item->item_type == 'prescribed' && $item->product_mode == 'medicine') {
                $item->orderSubId = $orderId . $item->product_id;
                $item->orderSystemId = $orderId;
                array_push($orderAllIds, $orderId . $item->product_id);
                array_push($cartPreMed, $item);
            } else if ($item->item_type == 'counter' && $item->product_mode == 'imaging') {
                $item->orderSubId = $orderId . $item->product_id;
                $item->orderSystemId = $orderId;
                array_push($orderAllIds, $orderId . $item->product_id);
                array_push($cartCntImg, $item);
            } else if ($item->item_type == 'prescribed' && $item->product_mode == 'imaging') {
                $item->orderSubId = $orderId . $item->product_id;
                $item->orderSystemId = $orderId;
                array_push($orderAllIds, $orderId . $item->product_id);
                array_push($cartPreImg, $item);
            }
        }
        // $orderId = '';
        // $dateString = Carbon::now()->format('yHis');
        // $getLastOrderId = DB::table('tbl_orders')->orderBy('id', 'desc')->first();
        // $randNumber=rand(1,100);
        if ($getLastOrderId != null) {
            $orderId = $getLastOrderId->order_id + 1+$randNumber;
        } else {
            $orderId = $dateString+$randNumber;
        }
        $totalPaymentToCharge = $request->payAble;
        $query = DB::table('card_details')
            ->where('id', $request->card_no)
            ->get();
        $pay = new \App\Http\Controllers\PaymentController();
        $profile = $query[0]->customerProfileId;
        $payment = $query[0]->customerPaymentProfileId;
        $amount = $request->payAble;
        $response = ($pay->new_createPaymentwithCustomerProfile($amount, $profile, $payment));
        DB::table('card_details')->where('id', $request->card_no)->update([
            'shipping' => serialize($shipping),
        ]);
        if ($response['messages']['message'][0]['text'] == 'Successful.') {
            $agent = 'web';
            $transactionArr = [
                'subject' => 'Order',
                'description' => $orderId,
                'total_amount' => $totalPaymentToCharge,
                'user_id' => Auth::user()->id,
            ];
            TblTransaction::create($transactionArr);
            $this->seprate_order_create($cartCntLab, $cartPreLab, $cartPreMed, $cartCntMed, $cartPreImg, $cartCntImg, $shipping);
            DB::table('tbl_orders')->insert([
                'order_state' => $user->state_id,
                'order_id' => $orderId,
                'order_sub_id' => serialize($orderAllIds),
                'customer_id' => Auth::user()->id,
                'total' => $totalPaymentToCharge,
                'total_tax' => 0,
                'billing' => serialize($billing),
                'shipping' => serialize($shipping),
                'payment' => serialize($response),
                'payment_title' => 'Direct Bank Transfer',
                'payment_method' => 'via Authorize.net',
                'cart_items' => '',
                "lab_order_approvals" => '',
                'currency' => 'US',
                'order_status' => 'paid',
                'agent' => $agent,
                'created_at' => Carbon::now(),
            ]);
            $this->orderNotify($orderId, $getAllCartProducts->toArray(), $totalPaymentToCharge, $billing['number'], $billing['name'], "478066367",$billing['email'],$shipping['email']);
            foreach ($getAllCartProducts as $ci) {
                if ($ci->refill_flag != '0') {
                    RefillRequest::where('id', $ci->refill_flag)->update(['granted' => null]);
                }
                DB::table('tbl_cart')->where('id', $ci->id)->update([
                    'status' => 'purchased',
                    'purchase_status' => '0',
                    'checkout_status' => '0',
                ]);
            }
            $successPayemnt['code'] = 200;
            $successPayemnt['order_id'] =$orderId;
            return $this->sendResponse($successPayemnt,"Payment successfull!");
        } else {
            $code = $response['messages']['message'][0]['code'];
            $message = TblTransaction::errorCode($code);
            DB::table('error_log')->insert([
                'user_id' => Auth::user()->id,
                'Error_code' => $code,
                'Error_text' => $response['messages']['message'][0]['text'],
            ]);
            Flash::error($message);
            $errorPayemnt['code'] = 200;
            $errorPayemnt['message'] = $message;
            return $this->sendError($errorPayemnt,"Payment not successfull!");
        }
    }
    public function oldCards(){
        $user = Auth::user();
        $query = DB::table('card_details')
        ->where('user_id',$user->id)
        ->get();
        $cardData['code'] = 200;
        $cardData['cards'] = $query;
        return $this->sendResponse($cardData,'Cards List');
    }
}
