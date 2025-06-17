<?php

namespace App\Http\Controllers;

use App\ActivityLog;
use DateTime;
use DateTimeZone;
use App\Appointment;
use App\Cart;
use App\City;
use App\Events\RealTimeMessage;
use App\Helper;
use App\Http\Controllers\Controller;
use App\ImagingPrices;
use App\Mail\patientEvisitRecommendationMail;
use App\Mail\ReferDoctorToDoctorMail;
use App\Mail\ReferDoctorToPatientMail;
use App\Models\AllProducts;
use App\Models\MapMarkers;
use App\Models\PrescriptionsFile;
use App\Models\ProductCategory;
use App\Models\ProductsSubCategory;
use App\Events\redirectToCart;
use App\Notification;
use App\Prescription;
use App\QuestDataAOE;
use App\QuestDataTestCode;
use App\Repositories\AllProductsRepository;
use App\Jobs\UploadMediaJob;
use App\Session;
use App\State;
use App\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use PDF;

class RecommendationController extends Controller
{
    private $allProductsRepository;
    public function __construct(AllProductsRepository $allProductsRepo)
    {
        $this->allProductsRepository = $allProductsRepo;
    }

    public function add_diagnosis_and_notes(Request $request)
    {
        $session_id = $request['session_id'];
        Session::where('id', $session_id)->update(['provider_notes' => $request['notes'], 'diagnosis' => $request['diagnosis']]);
    }

    public function store_pres(Request $request)
    {
        $session_id = $request['session_id'];
        $session = Session::where('id', $session_id)->first();
        if($session->validation_status == "valid")
        {
            Session::where('id', $session_id)->update(['provider_notes' => $request['note'], 'diagnosis' => $request['diagnosis']]);
            return redirect()->route('recommendations.display', ['session_id' => $session_id]);
        }
        else
        {
            return redirect()->route('doctor_dashboard');
        }
    }
    public function display(Request $request)
    {
        $ses = Session::where('id', $request->session_id)->first();
        if($ses->validation_status == "valid")
        {
            $items = [];
            $pro_lists = Prescription::where('session_id', $request['session_id'])->get();
            foreach ($pro_lists as $pro_list) {
                if ($pro_list->type == "lab-test") {
                    $labData = \App\QuestDataTestCode::where('TEST_CD', $pro_list->test_id)->first();
                    $getTestAOE = QuestDataAOE::select("TEST_CD AS TestCode", "AOE_QUESTION AS QuestionShort", "AOE_QUESTION_DESC AS QuestionLong")
                        ->where('TEST_CD', $pro_list->test_id)
                        ->groupBy('AOE_QUESTION_DESC')
                        ->get();
                    $count = count($getTestAOE);
                    if ($count > 0) {
                        $labData->aoes = 1;
                    } else {
                        $labData->aoes = 0;
                    }
                    $labData->pres_id = $pro_list->id;
                    $items[] = $labData;
                } else if ($pro_list->type == "imaging") {
                    $labData = \App\QuestDataTestCode::where('TEST_CD', $pro_list->test_id)->first();
                    $getTestAOE = QuestDataAOE::select("TEST_CD AS TestCode", "AOE_QUESTION AS QuestionShort", "AOE_QUESTION_DESC AS QuestionLong")
                        ->where('TEST_CD', $pro_list->test_id)
                        ->groupBy('AOE_QUESTION_DESC')
                        ->get();
                    $count = count($getTestAOE);
                    if ($count > 0) {
                        $labData->aoes = 1;
                    } else {
                        $labData->aoes = 0;
                    }
                    $labData->pres_id = $pro_list->id;
                    $items[] = $labData;
                } else if ($pro_list->type == "medicine") {
                    if ($pro_list->usage != null) {
                        $getRes = $this->allProductsRepository->find($pro_list->medicine_id);
                        $getRes->usage = $pro_list->usage;
                        $getRes->pres_id = $pro_list->id;
                        $items[] = $getRes;
                    } else {
                        $getRes = $this->allProductsRepository->find($pro_list->medicine_id);
                        $getRes->usage = "";
                        $getRes->pres_id = $pro_list->id;
                        $items[] = $getRes;
                    }
                }
            }
            $pres = collect($items);
            // dd($pres);
            $getSession = Session::where('id', $request->session_id)->first();

            return view('dashboard_doctor.session.recommendations', compact('getSession', 'pres'));
        }
        else
        {
            return redirect()->route('doctor_dashboard');
        }
        //return view('session.recommendations', compact('pharmacy','session','pres','refered'));
    }
    public function store(Request $request)
    {
        $session_id = $request['session_id'];
        $diagnosis = $request['diagnosis'];
        $notes = $request['note'];
        $session = Session::find($session_id);
        $patient_user = User::find($session->patient_id);
        $doctor_user = User::find($session->doctor_id);

        if ($session['appointment_id'] != null) {
            Appointment::where('id', $session['appointment_id'])->update(['status' => 'complete']);
        }
        $pres_list = Prescription::where('session_id', $session_id)->get();
        $items = count($pres_list);
        $dataMarge = [];
        $prePharma = [];
        $preLab = [];
        $preImaging = [];
        Session::where('id', $session_id)->update(['validation_status' => 'expired']);
        if ($items > 0) {
            foreach ($pres_list as $pres) {
                $product = DB::table('tbl_products')->where('id', $pres->medicine_id)->first();
                if ($pres->type == "medicine") {
                    // $med_unit = DB::table('medicine_units')->where('unit',$pres->med_unit)->first();
                    $price = DB::table('vendor_products')
                        ->where('product_id', $pres->medicine_id)
                        // ->where('unit_id',$med_unit->id)
                        ->first();
                    $prescription = DB::table('prescriptions')->where('id',$pres->id)->first();
                    if($product->is_single){
                        $pres->price = ($prescription->med_time*$prescription->med_days)*$price->selling_price;
                    }else{
                        $pres->price = $price->selling_price;
                    }
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
                        // 'unit' => $pres->med_unit,
                        'days' => $pres->med_days,
                        'quantity' => $pres->quantity,
                        'usage' => $pres->usage,
                        'comment' => $pres->comment,
                    ];
                    array_push($prePharma, $singleItemMedicine);
                } else if ($pres->type == "lab-test") {
                    $lab_test_price = DB::table('vendor_products')
                        ->where('product_id', $pres->medicine_id)
                        // ->where('unit_id',$med_unit->id)
                        ->first();
                    Cart::create([
                        'product_id' => $pres->test_id,
                        'name' => $lab_test_price->TEST_NAME,
                        'quantity' => $pres->quantity,
                        'price' => $lab_test_price->selling_price,
                        'update_price' => $lab_test_price->selling_price * $pres->quantity,
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
                        'test_name' => $lab_test_price->TEST_NAME,
                        'quantity' => $pres->quantity,
                        'comment' => $pres->comment,
                    ];
                    array_push($preLab, $singleItemTest);
                } else if ($pres->type == "imaging") {
                    $lab_test_price = DB::table('vendor_products')
                        ->where('product_id', $pres->medicine_id)
                        // ->where('unit_id',$med_unit->id)
                        ->first();
                    Cart::create([
                        'product_id' => $pres->test_id,
                        'name' => $lab_test_price->TEST_NAME,
                        'quantity' => $pres->quantity,
                        'price' => $lab_test_price->selling_price,
                        'update_price' => $lab_test_price->selling_price * $pres->quantity,
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
                        'test_name' => $lab_test_price->TEST_NAME,
                        'quantity' => $pres->quantity,
                        'comment' => $pres->comment,
                    ];
                    array_push($preLab, $singleItemTest);
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

        // try {
            array_push($dataMarge, array('patient' => $patient_user));
            array_push($dataMarge, array('doctor' => $doctor_user));
            array_push($dataMarge, array('rec_test' => $preLab));
            array_push($dataMarge, array('rec_pharma' => $prePharma));
            array_push($dataMarge, array('rec_imaging' => $preImaging));
            array_push($dataMarge, array('pat_email' => ucwords($patient_user->email)));
            array_push($dataMarge, array('session' => $session));
            $pdf = PDF::loadView('onlineprescriptionPdf',compact('dataMarge'));
            Mail::send('emails.prescriptionEmail', ['user_data'=>$patient_user], function ($message) use ($patient_user,$dataMarge,$pdf) {
                $message->to($patient_user->email)->subject('patient prescription')->attachData($pdf->output(), "prescription.pdf");
            });

            $pdfData = $pdf->output();

            $tempFile = tmpfile();
            fwrite($tempFile, $pdfData);
            $metaData = stream_get_meta_data($tempFile);
            $filePath = $metaData['uri'];

            UploadMediaJob::dispatch($filePath,$patient_user);

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
        // } catch (\Exception $e) {
        //     Log::error($e);
        // }
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
            // dd($data,$data1);

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
                return redirect()->route('doctor_queue');
            }
        }
        $sessionData = Session::find($session_id);
        $sessionData->received = false;
        event(new redirectToCart($session->id));
        return redirect()->route('doctor_queue');
    }
    public function display_session(Request $request)
    {

        $user_type = auth()->user()->user_type;

        $user_timeZone = auth()->user()->timeZone;

        $session_id = $request['session_id'];
        $session = Session::find($session_id);

        $getPersentage = DB::table('doctor_percentage')->where('doc_id', $session['doctor_id'])->first();
        $session->price = ($getPersentage->percentage / 100) * $session->price;

        $pat_id = $session['patient_id'];
        $pat = User::find($pat_id);
        $products = Prescription::where('session_id', $session_id)->orderBy('id', 'ASC')->get();
        foreach ($products as $prod) {
            if ($prod->test_id != 0) {
                $productTbl = QuestDataTestCode::where('TEST_CD', $prod->test_id)->first();
                $prod->name = $productTbl->DESCRIPTION;
                $prod->mode = 'lab-test';
            } else if ($prod->medicine_id != 0) {
                $productTbl = AllProducts::find($prod->medicine_id);
                $prod->name = $productTbl->name;
                $prod->mode = $productTbl->mode;
            } else {
                $productTbl = AllProducts::find($prod->imaging_id);
                $prod->name = $productTbl->name;
                $prod->mode = $productTbl->mode;
            }
        }
        $session->date = Helper::get_date_with_format($session->date);
        if ($session->start_time == null) {
            $session->start_time = Helper::get_time_with_format($session->created_at);
        } else {


            $date = new DateTime($session->start_time);
            $date->setTimezone(new DateTimeZone($user_timeZone));
            $session->start_time = $date->format('h:i:s A');

            // $session->start_time=Carbon::createFromFormat('Y-m-d H:i:s',$session->start_time,$user_timeZone)->setTimezone('UTC');
            // $createdAt = Carbon::parse($session->start_time);
            // $session->start_time = $createdAt->format('h:i:s A');
            // $session->start_time = Helper::get_time_with_format($session->start_time);
        }
        if ($session->end_time == null) {
            $session->end_time = Helper::get_time_with_format($session->updated_at);
        } else {


            $date = new DateTime($session->end_time);
            $date->setTimezone(new DateTimeZone($user_timeZone));
            $session->end_time = $date->format('h:i:s A');

            // $session->end_time=Carbon::createFromFormat('Y-m-d H:i:s',$session->end_time,$user_timeZone)->setTimezone('UTC');
            // $createdAt = Carbon::parse($session->end_time);
            // $session->end_time = $createdAt->format('h:i:s A');
            // $session->end_time = Helper::get_time_with_format($session->end_time);
        }

        // dd($session);
        return view('session.recommendations_final', compact('products', 'session', 'user_type', 'pat'));
    }
    public function eprescription($cart_items, $billing, $order_id)
    {
        $presc_meds = array();
        $med_check = false;
        foreach ($cart_items as $key => $item) {
            if ($item['product_mode'] == 'medicine') {
                $med_check = true;
                if (!in_array($item['doc_session_id'], $presc_meds)) {
                    $presc_meds[$item['doc_session_id']]['last_name'] = $billing['last_name'];
                    $presc_meds[$item['doc_session_id']]['first_name'] = $billing['first_name'];
                    $presc_meds[$item['doc_session_id']]['address'] = $billing['address'];
                    $presc_meds[$item['doc_session_id']]['city'] = $billing['city'];
                    $presc_meds[$item['doc_session_id']]['state'] = $billing['state'];
                    $presc_meds[$item['doc_session_id']]['zip_code'] = $billing['zip_code'];
                    $presc_meds[$item['doc_session_id']]['phone_number'] = $billing['phone_number'];
                    $presc_meds[$item['doc_session_id']]['email_address'] = $billing['email_address'];
                    $presc_meds[$item['doc_session_id']]['phone_number'] = $billing['phone_number'];
                    $session = Session::find($item['doc_session_id']);
                    $doc = User::find($session->doctor_id);
                    $patient = User::find($session->patient_id);
                    $state = State::find($doc->state_id);
                    $city = City::find($doc->city_id);
                    $presc_meds[$item['doc_session_id']]['patient_dob'] = $patient->date_of_birth;
                    $presc_meds[$item['doc_session_id']]['patient_gender'] = $session->gender;
                    $presc_meds[$item['doc_session_id']]['patient_id'] = $session->patient_id;
                    $presc_meds[$item['doc_session_id']]['phy_id'] = $doc->id;
                    $presc_meds[$item['doc_session_id']]['phy_phone_number'] = $doc->phone_number;
                    $presc_meds[$item['doc_session_id']]['phy_address'] = $doc->office_address;
                    $presc_meds[$item['doc_session_id']]['phy_city'] = $city->name;
                    $presc_meds[$item['doc_session_id']]['phy_state'] = $state->name;
                    $presc_meds[$item['doc_session_id']]['phy_zip_code'] = $doc->zip_code;
                    $presc_meds[$item['doc_session_id']]['NPI'] = $doc->nip_number;
                    $presc_meds[$item['doc_session_id']]['signature'] = $doc->add_signature;
                    $presc_meds[$item['doc_session_id']]['date'] = date('d-M-Y');
                }
                $pres = Prescription::find($item['pres_id']);
                $item['med_days'] = $pres->med_days;
                $item['med_unit'] = $pres->med_unit;
                $item['med_time'] = $pres->med_time;

                $presc_meds[$item['doc_session_id']]['items'][$key] = $item;
            }
        }
        // dd($presc_meds);
        if ($med_check) {
            // dd($presc_meds);
            foreach ($presc_meds as $key => $session_meds) {
                // dd($session_meds['items'][0]['name']);
                $session_meds['first_key'] = array_key_first($session_meds['items']);

                $pdf = PDF::loadView('all_products.rxOutreach.e_prescription', $session_meds);
                $timestamp = time();
                $file_name = 'e_prescriptions/' . $timestamp . '.pdf';
                $status = \Storage::disk('s3')->put($file_name, $pdf->output());
                $response = $this->decodeAndFax(\App\Helper::get_files_url($file_name));
                $json = json_decode($response);
                PrescriptionsFile::create([
                    'session_id' => $key,
                    'doctor_id' => $session_meds['phy_id'],
                    'patient_id' => $session_meds['patient_id'],
                    'order_id' => $order_id,
                    'filename' => $file_name,
                    'response' => $response,
                    'status' => $json->status,
                ]);
            }
        }
        return true;
    }
    public function new_eprescription($presc_meds)
    {
        foreach ($presc_meds as $key => $session_meds) {
            $pdf = PDF::loadView('all_products.rxOutreach.new_e_prescription', $session_meds);
            $timestamp = time();
            $file_name = 'e_prescriptions/' . $timestamp . '.pdf';
            $status = \Storage::disk('s3')->put($file_name, $pdf->output());
            $response = $this->decodeAndFax(\App\Helper::get_files_url($file_name));
            $json = json_decode($response);
            PrescriptionsFile::create([
                'session_id' => $key,
                'doctor_id' => $session_meds['phy_id'],
                'patient_id' => $session_meds['patient_id'],
                'order_id' => $session_meds['order_sub_id'],
                'filename' => $file_name,
                'response' => $response,
                'status' => $json->status,
            ]);
        }
        return true;
    }

    public function new_imaging_order($presc_meds)
    {
        foreach ($presc_meds as $key => $session_meds) {
            $pdf = PDF::loadView('all_products.rxOutreach.imaging_requisition', $session_meds);
            $timestamp = time();
            $file_name = 'imaging-orders/' . $timestamp . '.pdf';
            $status = \Storage::disk('s3')->put($file_name, $pdf->output());
            $response = $this->decodeAndFax(\App\Helper::get_files_url($file_name));
            $json = json_decode($response);
            DB::table('imaging_file')->insert([
                'session_id' => $key,
                'doctor_id' => $session_meds['phy_id'],
                'patient_id' => $session_meds['patient_id'],
                'order_id' => $session_meds['order_main_id'],
                'filename' => $file_name,
                'response' => $response,
                'status' => $json->status,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ]);
        }
        return true;
    }
    public function decodeAndFax($file)
    {
        // dd($file);
        $base64 = base64_encode(file_get_contents($file));
        // dd($base64);
        $curl = curl_init();
        if (env('APP_TYPE') == 'production') {
            $url = 'https://www.umbrellamd.com/api/proccedToEFax';
        } else if (env('APP_TYPE') == 'staging') {
            $url = 'https://www.umbrellamd-video.com/api/proccedToEFax';
        } else if (env('APP_TYPE') == 'testing') {
            $url = 'https://demo.umbrellamd-video.com/api/proccedToEFax';
        } else {
            $url = 'https://demo.umbrellamd-video.com/api/proccedToEFax';
        }
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                    "destinations": [
                        {
                            "to_name": "Haris Unar",
                            "to_company": "UHCS LLC.",
                            "fax_number": "+85230109089"
                        },
                        {
                            "to_name": "Abdul Musavir",
                            "to_company": "UHCS LLC.",
                            "fax_number": "+13306492731"
                        },
                        {
                            "to_name": "Ibad",
                            "to_company": "UHCS LLC.",
                            "fax_number": "+32061390231"
                        }
                    ],
                    "fax_options": {
                        "image_resolution": "STANDARD",
                        "include_cover_page": true,
                        "cover_page_options": {
                            "from_name": "Umbrellamd Health Care Systems",
                            "subject": "Pharmacy prescription",
                            "message": ""
                        },
                        "retry_options": {
                            "non_billable": 2,
                            "billable": 3,
                            "human_answer": 1
                        }
                    },
                    "documents": [
                            {
                                "document_type": "PDF",
                                "document_content":  "' . $base64 . '"
                            }
                        ]
                    }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        // echo $response;
        // $json=json_decode($response);
        // dd($json->status);

        return $response;
    }
    // EFAX API FUNCTIONS

    public function proccedToEFax(Request $request)
    {
        $requestBody = json_encode($request->all());

        $tokenResponse = $this->generateEFaxToken();

        if (isset($tokenResponse->access_token)) {

            $accessToken = $tokenResponse->access_token;
            $responseEFax = $this->requestToEFax($accessToken, $requestBody);

            if (!isset($responseEFax->errors)) {
                $response = [
                    'eFaxResponse' => $responseEFax,
                    'message' => 'ok',
                    'status' => true,
                ];
            } else {
                $response = [
                    'eFaxResponse' => $responseEFax->errors,
                    'message' => 'request body error',
                    'status' => false,
                ];
            }
        } else {
            $response = [
                'eFaxResponse' => null,
                'message' => 'token error',
                'status' => false,
            ];
        }
        echo json_encode($response);
    }

    public function generateEFaxToken()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => env('EFAX_API_URL') . '/tokens',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'grant_type=client_credentials',
            CURLOPT_HTTPHEADER => array(
                'content-type: application/x-www-form-urlencoded;charset=UTF-8',
                'Authorization: Basic OWQyYTcxMjItZjZkYi00ZDhhLWIxZjEtZjgzMGZhMzA4MTcyOm9QVTIzSEVBRWxUa1EvbnY=',
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response);
    }

    public function requestToEFax($accessToken, $requestBody)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => env('EFAX_API_URL') . '/faxes',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $requestBody,
            CURLOPT_HTTPHEADER => array(
                'user-id:' . env('EFAX_USERID'),
                'content-type: application/json',
                'Authorization: Bearer ' . $accessToken,
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response);
    }
}
