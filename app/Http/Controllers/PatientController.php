<?php

namespace App\Http\Controllers;

use App\ActivityLog;
use App\Cart;
use App\QuestResult;
use App\Events\CountCartItem;
use App\Events\RealTimeMessage;
use App\Events\HandRaise;
use App\Events\PatientCallEnd;
use App\Helper;
use App\Http\Controllers\Controller;
use App\Mail\RefillRequestToDoctorMail;
use App\Mail\UserVerificationEmail;
use App\Mail\EvisitBookMail;
use App\Mail\TherapyPatientEnrolled;
use App\MedicalProfile;
use App\Models\InClinics;
use App\Models\AllProducts;
use App\Models\TblOrders;
use App\Models\TblTransaction;
use App\City;
use App\Prescription;
use App\Referal;
use App\QuestDataTestCode;
use App\RefillRequest;
use App\Notification;
use App\Repositories\TblOrdersRepository;
use App\Models\Documents;
use App\Session;
use App\State;
use App\User;
use App\DoctorSchedule;
use Carbon\Carbon;
use App\TblCart as AppTblCart;
use DateTime;
use DateTimeZone;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Laracasts\Flash\Flash;

class PatientController extends Controller
{
    private $tblOrdersRepository;

    public function pat_acc_settings()
    {
        $user = Auth()->user();
        $user->user_image = \App\Helper::check_bucket_files_url($user->user_image);
        return view('dashboard_patient.AccountSetting.index',compact('user'));
    }

    public function pat_medical_profile()
    {
        $user_id = auth()->user()->id;
        $med_prof_exists = MedicalProfile::where('user_id', $user_id)->get()->count();
        $update = false;
        $med_files = DB::table('medical_records')->where('user_id',$user_id)->get();

        if ($med_prof_exists >= 1) {
            $update = true;
            $profile = DB::table('medical_profiles')->where('user_id', $user_id)->orderByDesc('id')->first();
            //  dd($profile);
            $profile->family_history = json_decode($profile->family_history);
            $profile->medication = json_decode($profile->medication);
            $profile->immunization_history = json_decode($profile->immunization_history);
            $profile->updated_at = User::convert_utc_to_user_timezone($user_id, $profile->updated_at)['datetime'];
            $profile->previous_symp = explode(",",$profile->previous_symp);
            array_pop($profile->previous_symp);
            $diseases = array("cancer", "hypertension", "heart-disease", "diabetes", "stroke", "mental", "drugs", "glaucoma", "bleeding", "others");

            return view('dashboard_patient.Medical_profile.index', compact('profile', 'update', 'diseases','med_files'));
        }

        return redirect()->route('patient_update_medical_profile')->with('med_files', $med_files);
        // return view('dashboard_patient.Medical_profile.update_medical_form');
    }

    public function dash_update_medical_profile(Request $request)
    {
        $user_id = auth()->user()->id;
        $med_prof_exists = MedicalProfile::where('user_id', $user_id)->get()->count();
        $update = false;
        if ($med_prof_exists >= 1) {
            $update = true;
            $profile = MedicalProfile::where('user_id', $user_id)->orderByDesc('id')->first();
            //  dd($profile);
            $profile->family_history = json_decode($profile->family_history);
            $profile->immunization_history = json_decode($profile->immunization_history);

            //  dd($profile);

            // dd($profile->immunization_history);
            // dd($profile->family_history[array_key_last($profile->family_history)]->disease);
            $diseases = array("cancer", "hypertension", "heart-disease", "diabetes", "stroke", "mental", "drugs", "glaucoma", "bleeding", "others");

            return view('dashboard_patient.Medical_profile.updateform', compact('profile', 'update', 'diseases'));
        }

        return view('dashboard_patient.Medical_profile.index', compact('profile','update'));
    }

    public function delete_patient_medical_record($id)
    {
        DB::table('medical_records')->where('id',$id)->delete();
        return redirect()->back()->with('record deleted successfully');
    }

    public function dash_update_medical_profile_new(){
            $user_id = auth()->user()->id;
            $med_prof_exists = MedicalProfile::where('user_id', $user_id)->get()->count();
            $update = false;
            $diseases = array("cancer", "hypertension", "heart-disease", "diabetes", "stroke", "mental", "drugs", "glaucoma", "bleeding");
            $is_diseases = DB::table('isabel_symptoms')->select('symptom_name')->get();
            $immunization = array("pneumovax", "h1n1", "annual_flu", "hepatitis_b", "tetanus", "others");
            $med_files = DB::table('medical_records')->where('user_id',$user_id)->get();
            //$med_file = User::where('id',$user_id)->select('med_record_file')->first();
            // dd($med_file);
            if ($med_prof_exists >= 1) {
                $update = true;
                $profile = DB::table('medical_profiles')->where('user_id', $user_id)->orderByDesc('id')->first();
                $profile->previous_symp = explode(",",$profile->previous_symp);
                array_pop($profile->previous_symp);
                $profile->family_history = json_decode($profile->family_history);
                $profile->immunization_history = json_decode($profile->immunization_history);
                $profile->medication = json_decode($profile->medication);
                $profile->med_file = User::where('id',$user_id)->select('med_record_file')->first();
                //  dd($profile->med_file);
                // dd($profile->immunization_history);
                if($profile->immunization_history != '[]'){
                    foreach($profile->immunization_history as $immu_his){
                        $immu_his->when = date('m-d-Y', strtotime($immu_his->when));
                    }
                }
                // dd($profile->immunization_history);
                // dd($profile->family_history[array_key_last($profile->family_history)]->disease);


                return view('dashboard_patient.Medical_profile.update_medical_form', compact('profile', 'update', 'diseases','immunization','is_diseases','med_files'));
            }
            return view('dashboard_patient.Medical_profile.update_medical_form', compact( 'update', 'diseases','immunization','is_diseases','med_files'));
    }

    public function my_doctors(){
        $user = Auth()->user();
        $doctors = DB::table('sessions')
        ->join('users', 'users.id', 'doctor_id')
        ->join('specializations', 'sessions.specialization_id', 'specializations.id')
        ->where('patient_id',$user->id)
        ->where('sessions.status', '!=', 'pending')
        ->groupBy('doctor_id')
        ->select('users.*','specializations.name as sp_name')
        ->paginate(10);

        foreach($doctors as $doctor){
            $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);
        }
        return view('dashboard_patient.My_doctor.index', compact('doctors'));
    }

    public function my_reports(){
        if (auth()->user()->user_type == 'patient') {
            $reports = QuestResult::where('pat_id', auth()->user()->id)
                ->where('status', 'success')
                ->orderByDesc('id')
                ->paginate(8);
        } else if (auth()->user()->user_type == 'doctor') {
            $reports = QuestResult::where('doc_id', auth()->user()->id)
                ->where('pat_id',$patient_id)
                ->where('status', 'success')
                ->orderByDesc('id')
                ->paginate(8);
        }
        // dd($reports);
        $hl7_obj = new HL7Controller();
        foreach ($reports as $report) {
            $decoded = $hl7_obj->hl7Decode($report->hl7_message);
            $report->type = $decoded['result_type'];
            $report->doctor = User::getName($report->doc_id);
            $report->patient = User::getName($report->pat_id);
            $test_names = [];
            foreach ($decoded['arrOBR'] as $obr) {
                if (!in_array($obr['name'], $test_names)) {
                    array_push($test_names, $obr['name']);
                }
            }
            $sp_date = explode(' ', $decoded['arrOBR'][0]['specimen_collection_date']);
            $res_date = date('m/d/Y', strtotime($report->created_at));
            $report->specimen_date = $sp_date[0];
            $report->result_date = $res_date;
            // $report->order_date=$report->created_at;
            // dd($res_date.' :: '.$sp_date[0]);
            end($test_names); // move the internal pointer to the end of the array
            $key = key($test_names); // fetches the key of the element pointed to by the internal pointer

            // var_dump($key);
            $all_test_names = '';
            foreach ($test_names as $k => $test) {
                if ($k != $key) {
                    $all_test_names .= $test;
                }
            }
            $report->test_names = $all_test_names;
        }

        $user = auth()->user();
        $tblOrders = DB::table('imaging_orders')
            ->join('tbl_products', 'imaging_orders.product_id', '=', 'tbl_products.id')
            ->join('users', 'users.id', '=', 'imaging_orders.user_id')
            ->join('states', 'states.id', '=', 'users.state_id')
            ->join('cities', 'cities.id', '=', 'users.city_id')
            ->join('tbl_orders', 'tbl_orders.order_id', '=', 'imaging_orders.order_id')
            ->join('sessions', 'sessions.id', '=', 'imaging_orders.session_id')
            ->join('users as doc', 'doc.id', '=', 'sessions.doctor_id')
            ->where('users.id', $user->id)
            ->select(
                'tbl_products.name as name',
                'tbl_products.sale_price as total',
                'imaging_orders.*',
                'users.name as fname',
                'users.last_name as lname',
                'users.office_address as address',
                'cities.name as order_city',
                'states.name as order_state',
                'imaging_orders.status as order_status',
                // 'imaging_locations.name as lab_name',
                // 'imaging_locations.address as lab_address',
                'tbl_orders.payment_title',
                'tbl_orders.payment_method',
                'tbl_orders.currency',
                'sessions.id as session_id',
                'doc.name as doc_fname',
                'doc.last_name as doc_lname',
                'doc.nip_number',
                'doc.upin'
                // 'imaging_orders.sub_order_id as order_id'
            )
            ->orderBy('imaging_orders.status')
            ->get();
        foreach ($tblOrders as $img_ord) {
            $img_ord->date = User::convert_utc_to_user_timezone($user->id,$img_ord->created_at)['date'];
            $img_ord->time = User::convert_utc_to_user_timezone($user->id,$img_ord->created_at)['time'];
            if ($img_ord->report != null) {
                $img_ord->report = \App\Helper::get_files_url($img_ord->report);
            }
        }
        return view('dashboard_patient.myReports.index', compact('reports','tblOrders'));
    }

    public function __construct(TblOrdersRepository $tblOrdersRepo)
    {

        $this->tblOrdersRepository = $tblOrdersRepo;
    }
    public function add_discount_in_cart($slug)
    {
        $user = auth()->user();
        if($user->user_type == 'patient')
        {
            $getProductMetaData = DB::table('quest_data_test_codes')
            ->select(
                'TEST_CD AS product_id',
                'mode',
                'TEST_NAME AS name',
                'SALE_PRICE AS sale_price',
                'featured_image',
                DB::raw('"quest_data_test_codes" as tbl_name')
            )
            ->where('slug', $slug)
            ->first();
            $count=DB::table('tbl_cart')
            ->where('user_id',$user->id)
            ->where('product_id',$getProductMetaData->product_id)
            ->where('product_mode','lab-test')
            ->where('item_type','counter')
            ->where('status','recommended')
            ->first();
            $coupon = DB::table('coupon_code')->where('coupon_code','UHCSFREECBC')->first();
            $checkFirst = DB::table('tbl_cart')
            ->where('coupon_code_id', $coupon->id)
            ->where('user_id', Auth::user()->id)
            ->first();
            if($count!=null)
            {
                return redirect()->back()->with('error_message','item already in a cart');
            }elseif($checkFirst!=null){
                return redirect()->back()->with('error_message','Coupon is already applied to this account.');
            }
            elseif($coupon==null)
            {
                return redirect()->back()->with('error_message','coupon is expired');
            }
            elseif($coupon->status != 1 || $coupon->expiry_date<= date('Y-m-d'))
            {
                return redirect()->back()->with('error_message','coupon is expired');
            }
            else
            {
                $data['session_id'] = '';
                $data['cart_row_id'] = rand();
                $data['product_id']=$getProductMetaData->product_id;
                $data['name'] = $getProductMetaData->name;
                $data['product_image'] = $getProductMetaData->featured_image;
                $data['prescription'] = '';
                $data['design_view'] = '';
                $data['strip_per_pack'] = 0;
                $data['quantity'] = 1;
                $data['price'] = $getProductMetaData->sale_price;
                $data['discount'] = 0;
                $data['created_at'] = Carbon::now();
                $data['updated_at'] = Carbon::now();
                $data['user_id'] = $user->id;
                $data['doc_session_id'] = 0;
                $data['doc_id'] = 0;
                $data['pres_id'] = 0;
                $data['update_price'] = 0;
                $data['product_mode'] = $getProductMetaData->mode;
                $data['item_type'] = 'counter';
                $data['status'] = 'recommended';
                $data['map_marker_id'] = '';
                $data['location_id'] = '';
                $data['show_product'] = 1;
                $data['coupon_code_id'] = $coupon->id;
                $cart = AppTblCart::Create($data);
                // event(new CountCartItem($user->id));
                // try {
                //     // \App\Helper::firebase(Auth()->user()->id,'cart',$cart->id,$data);
                // } catch (\Throwable $th) {
                //     //throw $th;
                // }
            }
            return redirect()->route('user_cart')->with('msg','Coupon is applied proceed to checkout');
        }
        else
        {
            return redirect()->back()->with('error_message','Only patients can avail this offer');
        }
    }
    public function add_to_cart(Request $request)
    {
        $user_id=Auth::user()->id;

        $getProductMetaData='';
        if ($request->pro_mode=="lab-test" || $request->pro_mode=="imaging")
        {
            $count=DB::table('tbl_cart')
                ->where('user_id',$user_id)
                ->where('product_id',$request->pro_id)
                ->where('product_mode',$request->pro_mode)
                ->where('item_type','counter')
                ->where('status','recommended')
                ->first();
            if($count!=null)
            {
                return response()->json(array('check' => '1'), 200);
            }
            else
            {
                $getProductMetaData = DB::table('quest_data_test_codes')
                ->join('vendor_products','quest_data_test_codes.TEST_CD','vendor_products.product_id')
                ->select(
                    'vendor_products.id AS product_id',
                    'vendor_products.discount',
                    'quest_data_test_codes.mode',
                    'quest_data_test_codes.TEST_NAME AS name',
                    'vendor_products.selling_price AS sale_price',
                    'quest_data_test_codes.featured_image',
                    DB::raw('"quest_data_test_codes" as tbl_name')
                )
                ->where('vendor_products.id', $request->pro_id)
                ->first();
                $data['session_id'] = '';
                $data['cart_row_id'] = rand();
                $data['product_id']=$getProductMetaData->product_id;
                $data['name'] = $getProductMetaData->name;
                $data['product_image'] = $getProductMetaData->featured_image;
                $data['prescription'] = '';
                $data['design_view'] = '';
                $data['strip_per_pack'] = 0;
                $data['quantity'] = $request->pro_qty;
                $data['price'] = $getProductMetaData->sale_price;
                $data['discount'] = $getProductMetaData->discount ?? 0;
                $data['created_at'] = Carbon::now();
                $data['updated_at'] = Carbon::now();
                $data['user_id'] = $user_id;
                $data['doc_session_id'] = 0;
                $data['doc_id'] = 0;
                $data['pres_id'] = 0;
                if($getProductMetaData->discount != null && $getProductMetaData->discount > 0){
                    $data['update_price'] = $getProductMetaData->sale_price - ($getProductMetaData->sale_price * $getProductMetaData->discount) / 100;
                }else{
                    $data['update_price'] = $getProductMetaData->sale_price;
                }
                $data['product_mode'] = $getProductMetaData->mode;
                $data['item_type'] = 'counter';
                $data['status'] = 'recommended';
                $data['map_marker_id'] = '';
                $data['location_id'] = '';
                $cart = AppTblCart::Create($data);
                event(new CountCartItem($user_id));
                try {
                    // \App\Helper::firebase(Auth()->user()->id,'cart',$cart->id,$data);
                } catch (\Throwable $th) {
                    //throw $th;
                }
                return "ok";
            }

        }
        else
        {
            $count=DB::table('tbl_cart')
                ->where('user_id',$user_id)
                ->where('product_id',$request->pro_id)
                ->where('product_mode',$request->pro_mode)
                ->where('prescription',$request->unit)
                ->where('item_type','counter')
                ->where('status','recommended')
                ->first();
            if($count!=null)
            {
                $qty=$count->quantity+$request->quantity;
                $pricing = DB::table('vendor_products')->where('id',$request->pro_id)->first();
                $count=DB::table('tbl_cart')
                    ->where('user_id',$user_id)
                    ->where('product_id',$request->pro_id)
                    ->where('product_mode',$request->pro_mode)
                    ->where('item_type','counter')
                    ->where('status','recommended')
                    ->update(['quantity'=>$qty,'price'=>$qty*$pricing->selling_price,'update_price'=>$qty*$pricing->selling_price]);
                    event(new CountCartItem($user_id));
                    return "ok";
            }
            else
            {
                $getProductMetaData = DB::table('tbl_products')
                    ->join('vendor_products','tbl_products.id','vendor_products.product_id')
                    ->select(
                        'vendor_products.id as product_id',
                        'vendor_products.discount',
                        'tbl_products.name',
                        'vendor_products.selling_price',
                        'tbl_products.mode',
                        'tbl_products.featured_image'
                    )
                    ->where('vendor_products.id', $request->pro_id)
                    ->first();
                $pricing = DB::table('vendor_products')->where('id',$request->pro_id)->first();

                $data['session_id'] = '';
                $data['cart_row_id'] = rand();
                $data['product_id']=$getProductMetaData->product_id;
                $data['name'] = $getProductMetaData->name;
                $data['product_image'] = $getProductMetaData->featured_image;
                $data['prescription'] = $request->unit;
                $data['design_view'] = '';
                $data['strip_per_pack'] = 0;
                $data['quantity'] = $request->quantity;
                $data['price'] = $getProductMetaData->selling_price*$request->quantity;
                $data['discount'] = $getProductMetaData->discount ?? 0;
                $data['created_at'] = Carbon::now();
                $data['updated_at'] = Carbon::now();
                $data['user_id'] = $user_id;
                $data['doc_session_id'] = 0;
                $data['doc_id'] = 0;
                $data['pres_id'] = 0;
                if($getProductMetaData->discount != null && $getProductMetaData->discount > 0){
                    $data['update_price'] = ($getProductMetaData->selling_price - ($getProductMetaData->selling_price * $getProductMetaData->discount) / 100)*$request->quantity;
                }else{
                    $data['update_price'] = $getProductMetaData->selling_price*$request->quantity;
                }
                $data['product_mode'] = $getProductMetaData->mode;
                $data['item_type'] = 'counter';
                $data['status'] = 'recommended';
                $data['map_marker_id'] = '';
                $data['location_id'] = '';

                $cart = AppTblCart::Create($data);
                event(new CountCartItem($user_id));
                return "ok";
            }
        }
    }

    public function add_medical_profile(Request $request)
    {
        $user_id = auth()->user()->id;
        $med_prof_exists = MedicalProfile::where('user_id', $user_id)->get()->count();
        $update = false;
        // dd($user_id);
        if ($med_prof_exists >= 1) {
            $update = true;
            $profile = MedicalProfile::where('user_id', $user_id)->orderByDesc('id')->first();
            //  dd($profile);
            $profile->family_history = json_decode($profile->family_history);
            $profile->immunization_history = json_decode($profile->immunization_history);

            //  dd($profile);

            // dd($profile->immunization_history);
            // dd($profile->family_history[array_key_last($profile->family_history)]->disease);
            $diseases = array("cancer", "hypertension", "heart-disease", "diabetes", "stroke", "mental", "drugs", "glaucoma", "bleeding", "others");
            $immunization = array("pneumovax", "h1n1", "annual_flu", "hepatitis_b", "tetanus", "others");

            return view('add_medical_profile', compact('profile', 'update', 'diseases'));
        }

        return view('add_medical_profile', compact('update'));
    }

    public function medical_profile(Request $request)
    {
        $user_id = auth()->user()->id;
        $profilee = MedicalProfile::where('user_id', $user_id)->get();
        $countExist = count($profilee);
        $update = false;
        //check if medical profile already exists
        if ($countExist > 0) {
            $update = true;
            // dd($profilee);
            return view('medical_profile', compact('profilee', 'update'));
        } else {
            return view('add_medical_profile', compact('update'));
        }
    }
    public function store_medical_history(Request $request)
    {
        $symptoms = "";
        if ($request['symp'] != null) {
            foreach ($request['symp'] as $symp) {
                $symptoms .= $symp . ",";
            }
        }
        $immunization_history = array();
        $immunization = array("pneumovax", "h1n1", "annual_flu", "hepatitis_b", "tetanus", "others");
        if ($request['immunization_history'] != null) {
            foreach ($immunization as $imm) {
                $temp['name'] = $imm;
                // $temp['flag']='';
                $temp['when'] = $request["when_" . $imm];
                if (in_array($imm, $request['immunization_history'])) {
                    $temp['flag'] = 'yes';
                } else {
                    $temp['flag'] = 'no';
                }
                array_push($immunization_history, $temp);
            }
        }
        $immunization_history = json_encode($immunization_history);

        $family_history = array();
        $diseases = array("cancer", "hypertension", "heart-disease", "diabetes", "stroke", "mental", "drugs", "glaucoma", "bleeding", "others");
        foreach ($diseases as $dis) {
            if ($dis == 'others') {
                if (!empty($request['f_others'])) {
                    $array = array("disease" => $request['f_others_name'], "family" => $request['f_' . $dis], "age" => $request['f_' . $dis . '_age']);
                    array_push($family_history, $array);
                }
            } else if (!empty($request['f_' . $dis])) {
                $array = array("disease" => $dis, "family" => $request['f_' . $dis], "age" => $request['f_' . $dis . '_age']);
                array_push($family_history, $array);
            }
        }
        $family_history = json_encode($family_history);
        $user = auth()->user()->id;
        $med = MedicalProfile::where('user_id', $user)->get()->count();
        if ($med > 0) {
            $update = true;
            $pro = MedicalProfile::where('user_id', $user)->orderByDesc('id')
                ->first()->update([
                    'allergies' => $request['allergies'],
                    'previous_symp' => $symptoms,
                    'immunization_history' => $immunization_history,
                    'family_history' => $family_history,
                    'comment' => $request['comment']
                ]);
        } else {
            $update = false;
            $pro = MedicalProfile::create([
                'user_id' => $user,
                'allergies' => $request['allergies'],
                'previous_symp' => $symptoms,
                'immunization_history' => $immunization_history,
                'family_history' => $family_history,
                'comment' => $request['comment']
            ]);
        }
        if (request()->hasFile('med_record')) {
            $image = request()->file('med_record');
            $filename = \Storage::disk('s3')->put('medical_records', $image);
            User::where('id', $user)->update([
                'med_record_file' => $filename,
            ]);
        }
        // $user_id=$user;
        //$profile=MedicalProfile::where('user_id',$user_id)->get();
        // return view('/home');
        // return redirect()->route('home')->with('currentRole','patient');
        // return view('medical_profile',compact('profile'));
        if ($update) {
            return redirect()->route('New_Patient_Dashboard')->with('success', 'Successfully Updated Medical History Record');
        } else {
            return redirect()->route('New_Patient_Dashboard')->with('success', 'Successfully Updated Medical History Record');
            //return redirect()->route('patient_medical_profile');
        }
    }

    public function store_medical_history_new(Request $request)
    {
        // dd($request);
        $symptoms = "";
        if ($request['symp'] != null) {
            foreach ($request['symp'] as $symp) {
                $symptoms .= $symp . ",";
            }
        }
        $immunization_history = array();
        $immunization = array("pneumovax", "h1n1", "annual_flu", "hepatitis_b", "tetanus", "others");
        if ($request['immun_name'] != null) {
            for ($i=0; $i < count($request->immun_when) ; $i++) {
                $temp['name'] = $request->immun_name[$i];
                $temp['when'] = $request->immun_when[$i];
                $temp['flag'] = 'yes';
                array_push($immunization_history, $temp);
            }
        }
        $immunization_history = json_encode($immunization_history);

        $family_history = array();
        $diseases = array("cancer", "hypertension", "heart-disease", "diabetes", "stroke", "mental", "drugs", "glaucoma", "bleeding", "others");
        if ($request['family'] != null) {
            for ($i=0; $i < count($request->family) ; $i++) {
                $fam_arr['family'] = $request->family[$i];
                $fam_arr['disease'] = $request->disease[$i];
                $fam_arr['age'] = $request->age[$i];
                array_push($family_history, $fam_arr);
            }
        }
        $family_history = json_encode($family_history);


        $medication_history = array();
        if ($request['med_name'] != null) {
            for ($i=0; $i < count($request->med_name) ; $i++) {
                $med_arr['med_name'] = $request->med_name[$i];
                $med_arr['med_dosage'] = $request->med_dosage[$i];
                // $med_arr['age'] = $request->age[$i];
                array_push($medication_history, $med_arr);
            }
        }
        $medication_history = json_encode($medication_history);
        // dd($family_history);
        $user = auth()->user()->id;
        $med = MedicalProfile::where('user_id', $user)->get()->count();
        if ($med > 0) {
            $update = true;
            $pro = MedicalProfile::where('user_id', $user)->orderByDesc('id')
                ->first()->update([
                    'allergies' => $request['allergies'],
                    'previous_symp' => $symptoms,
                    'immunization_history' => $immunization_history,
                    'family_history' => $family_history,
                    'surgeries' => $request['surgeries'],
                    'comment' => $request['comm'],
                    'medication' => $medication_history,
                ]);
        } else {
            $update = false;
            $pro = MedicalProfile::create([
                'user_id' => $user,
                'allergies' => $request['allergies'],
                'previous_symp' => $symptoms,
                'immunization_history' => $immunization_history,
                'family_history' => $family_history,
                'surgeries' => $request['surgeries'],
                'comment' => $request['comm'],
                'medication' => $medication_history,
            ]);
        }
        if ($request->hasFile('certificate')) {
            $images = $request->file('certificate');
            foreach($images as $image)
            {
                $filename = \Storage::disk('s3')->put('medical_records', $image);
                DB::table('medical_records')->insert([
                    'user_id'=>$user,
                    'record_file'=>$filename,
                    'created_at'=>date('Y-m-d H:i:s'),
                    'updated_at'=>date('Y-m-d H:i:s'),
                ]);
            }
        }
        // $user_id=$user;
        //$profile=MedicalProfile::where('user_id',$user_id)->get();
        // return view('/home');
        // return redirect()->route('home')->with('currentRole','patient');
        // return view('medical_profile',compact('profile'));
        if ($update) {
            return redirect()->route('New_Patient_Dashboard')->with('success', 'Successfully Updated Medical History Record');
        } else {
            return redirect()->route('New_Patient_Dashboard')->with('success', 'Successfully Updated Medical History Record');
            //return redirect()->route('patient_medical_profile');
        }
    }
    public function view_patient_record(Request $request)
    {
        $user_type = auth()->user()->user_type;
        // $id=4;//$request['id'];
        $id = $request['id'];
        $pat = User::find($id);
        $user = $pat;
        $pat_name = Helper::get_name($id);
        $pat_info = User::patient_info($id);

        $sessions = User::get_full_session_details($id);
        $user_obj = new User();
        $tblOrders = $this->tblOrdersRepository->getOrdersByUserID($id);
        foreach ($tblOrders as $order) {
            $order->created_at = User::convert_utc_to_user_timezone($user->id,$order->created_at)['datetime'];
        }
        $history['patient_meds'] = $user_obj->get_current_medicines($id); //$patient_meds[0]->prod->name
        $history['patient_labs'] = $user_obj->get_lab_reports($id);
        $history['patient_imaging'] = $user_obj->get_imaging_reports($id);
        // dd($history['patient_meds']);
        $medical_profile = MedicalProfile::where('user_id', $id)->orderByDesc('id')->first();
        $last_updated = "";
        if ($medical_profile != null) {
            $last_updated = Helper::get_date_with_format($medical_profile['updated_at']);
        }

        if (auth()->user()->user_type == 'doctor') {
            ActivityLog::create([
                'activity' => 'viewed record of ' . $pat_name,
                'type' => 'record',
                'user_id' => auth()->user()->id,
                'user_type' => 'doctor',
                'party_involved' => $id,
            ]);
        }
        // dd($sessions);
        return view('patient.patient_record', ['sessionss' => $sessions], compact(
            'pat',
            'user',
            'pat_info',
            'pat_name',
            'medical_profile',
            'last_updated',
            'user_type',
            'history',
            'tblOrders',
        ));
    }

    public function fetch_pending_imagings($user_id,Request $request)
    {
        if($request->ajax())
        {
            $imagings = DB::table('sessions')
            ->join('tbl_cart','sessions.id','tbl_cart.doc_session_id')
            ->where('tbl_cart.user_id',$user_id)
            ->where('tbl_cart.product_mode','imaging')
            ->where('tbl_cart.item_type','prescribed')
            ->where('tbl_cart.status','purchased')
            ->select('tbl_cart.*','sessions.date as session_date')
            ->latest()
            ->paginate(4,['*'],'pimagings');
            return view('dashboard_doctor.All_patient.pimagings_page',compact('imagings'));
        }
        else{
            $imagings = DB::table('sessions')
            ->join('tbl_cart','sessions.id','tbl_cart.doc_session_id')
            ->where('tbl_cart.user_id',$user_id)
            ->where('tbl_cart.product_mode','imaging')
            ->where('tbl_cart.item_type','prescribed')
            ->where('tbl_cart.status','purchased')
            ->latest()
            ->select('tbl_cart.*','sessions.date as session_date')
            ->paginate(4,['*'],'pimagings');
            return $imagings;
        }
    }

    public function fetch_pending_labs($user_id,Request $request)
    {
        if($request->ajax())
        {
            $labs = DB::table('sessions')
            ->join('tbl_cart','sessions.id','tbl_cart.doc_session_id')
            ->where('tbl_cart.user_id',$user_id)
            ->where('tbl_cart.product_mode','lab-test')
            ->where('tbl_cart.item_type','prescribed')
            ->where('tbl_cart.status','purchased')
            ->select('tbl_cart.*','sessions.date as session_date')
            ->latest()
            ->paginate(4,['*'],'plabs');
            return view('dashboard_doctor.All_patient.plabs_page',compact('labs'));
        }
        else
        {
            $labs = DB::table('sessions')
            ->join('tbl_cart','sessions.id','tbl_cart.doc_session_id')
            ->where('tbl_cart.user_id',$user_id)
            ->where('tbl_cart.product_mode','lab-test')
            ->where('tbl_cart.item_type','prescribed')
            ->where('tbl_cart.status','purchased')
            ->select('tbl_cart.*','sessions.date as session_date')
            ->latest()
            ->paginate(4,['*'],'plabs');
            return $labs;
        }
    }

    public function dash_view_patient_record(Request $request)
    {
        // dd('abc');
        $user_type = auth()->user()->user_type;
        // $id=4;//$request['id'];
        $id = $request['id'];
        $pat = User::find($id);
        $user = $pat;
        $pat_name = Helper::get_name($id);
        $pat_info = User::patient_info($id);
        $sessions = User::get_full_session_details($id);
        $inclinic = InClinics::with(['user','prescriptions','doctor'])->where('user_id',$id)->orderBy('id','desc')->paginate(10);
        $user_obj = new User();
        $tblOrders = $this->tblOrdersRepository->getOrdersByUserID($id);
        foreach ($tblOrders as $order) {
            $order->created_at = User::convert_utc_to_user_timezone($user->id,$order->created_at);
        }
        foreach ($sessions as $session) {
            $session->date = User::convert_utc_to_user_timezone($user->id, $session->created_at)['date'];

            $session->start_time = date('h:i A',strtotime('-15 minutes',strtotime($session->start_time)));
            $session->start_time = User::convert_utc_to_user_timezone($user->id, $session->start_time)['time'];

            $session->end_time = date('h:i A',strtotime('-15 minutes',strtotime($session->end_time)));
            $session->end_time = User::convert_utc_to_user_timezone($user->id, $session->end_time)['time'];
            // dd($session->created_at);
        }
        $history['patient_meds'] = $user_obj->get_current_medicines($id); //$patient_meds[0]->prod->name
        $history['patient_labs'] = $user_obj->get_lab_reports($id);
        $history['patient_imaging'] = $user_obj->get_imaging_reports($id);
        $history['patient_pending_labs'] = $this->fetch_pending_labs($id,new Request);
        //$history['patient_pending_labs'] = $user_obj->get_pending_labs($id);
        $history['patient_pending_imagings'] = $this->fetch_pending_imagings($id,new Request);
        //$history['patient_pending_imagings'] = $user_obj->get_pending_imagings($id);
        $medical_profile = MedicalProfile::where('user_id', $id)->orderByDesc('id')->first();
        $last_updated = "";
        $box['med_price'] = DB::table('medicine_order')->where('user_id', $id)->sum('update_price');
        $orderLabs = DB::table('lab_orders')
            ->join('quest_data_test_codes', 'quest_data_test_codes.TEST_CD', 'lab_orders.product_id')
            ->join('prescriptions', 'prescriptions.test_id', 'lab_orders.product_id')
            ->where('lab_orders.status','!=', 'pending')
            ->where('lab_orders.user_id', $id)
            ->groupBy('lab_orders.id')
            ->select('lab_orders.*', 'quest_data_test_codes.DESCRIPTION', 'quest_data_test_codes.SALE_PRICE', 'prescriptions.quantity',)
            ->get();
        $box['lab_price'] = 0;
        foreach($orderLabs as $order){
            $box['lab_price'] += $order->SALE_PRICE;
        }
        $box['imaging_price'] = DB::table('imaging_orders')->where('user_id', $id)->sum('price');
        $box['sessions'] = DB::table('sessions')->where('patient_id',$id)->where('status','!=','pending')->count();
        if ($medical_profile != null) {
            $last_updated = Helper::get_date_with_format($medical_profile['updated_at']);
        }

        if (auth()->user()->user_type == 'doctor') {
            ActivityLog::create([
                'activity' => 'viewed record of ' . $pat_name,
                'type' => 'record',
                'user_id' => auth()->user()->id,
                'user_type' => 'doctor',
                'party_involved' => $id,
            ]);
            return view('dashboard_doctor.All_patient.patient_detail', ['sessionss' => $sessions], compact(
                'pat',
                'user',
                'pat_info',
                'pat_name',
                'medical_profile',
                'last_updated',
                'user_type',
                'history',
                'tblOrders',
                'inclinic',
            ));
        }elseif(auth()->user()->user_type == 'admin'){
            // $state = DB::table('states')->where('id',$pat_info->state_id)->first();
            // $city = DB::table('cities')->where('id',$pat_info->city_id)->first();
            // $pat_info->state = $state->name;
            // $pat_info->city = $city->name;
            return view('dashboard_admin.manage_users.patient_details', ['sessionss' => $sessions], compact(
                'pat',
                'user',
                'pat_info',
                'pat_name',
                'medical_profile',
                'last_updated',
                'user_type',
                'history',
                'tblOrders',
                'box'
            ));
        }
        // dd($sessions);
    }
    public function patient_payment_details(Request $request)
    {
        $patient = User::find($request['id']);
        $pat_name = Helper::get_name($request['id']);

        $datasset = DB::table('tbl_orders')->where('customer_id', $request['id'])->get();
        $lab = 0;
        $medicien = 0;
        $imaging = 0;
        foreach ($datasset as $data) {
            $items = unserialize($data->cart_items);
            foreach ($items as $item) {
                if ($item['product_mode'] == 'medicine') {
                    $medicien += $item['update_price'];
                }
                if ($item['product_mode'] == 'imaging') {
                    $imaging += $item['update_price'];
                }
                if ($item['product_mode'] == 'lab-test') {
                    $lab += $item['update_price'];
                }
            }
        }
        return view('superadmin.patient_payment_details', compact('patient', 'pat_name', 'lab', 'medicien', 'imaging'));
    }

    public function register_medical_profile(Request $request)
    {
        // $user_id=auth()->user()->id;
        // $med_prof_exists=MedicalProfile::where('user_id',$user_id)->get()->count();
        // if($med_prof_exists>=1){
        // $prof=MedicalProfile::where('user_id',$user_id)->orderByDesc('id')->first();
        // dd($prof);
        // return view('medical_profile')->with('profile',$prof);
        // }
        $med_prof = true;
        return view('patient.medical_profile.register_medical_profile', compact('med_prof'));
    }

    public function current_medication(Request $request)
    {
        $id = auth()->user()->id;
        $params = $request->query();
        if ($request->session_id == 'requested') {
            $pres = DB::table('sessions')->where('sessions.patient_id', $id)
                ->where('type', 'medicine')
                ->join('prescriptions', 'prescriptions.session_id', '=', 'sessions.id')
                ->join('tbl_cart','prescriptions.id','tbl_cart.pres_id')
                ->join('refill_requests', 'prescriptions.id', '=', 'refill_requests.pres_id')
                ->where('refill_requests.session_req', '1')
                ->join('tbl_products', 'tbl_products.id', '=', 'refill_requests.prod_id')
                ->join('users', 'users.id', '=', 'sessions.doctor_id')
                ->select(
                    'prescriptions.*',
                    'refill_requests.*',
                    'tbl_products.featured_image as prod_img',
                    'tbl_products.name as prod_name',
                    'users.name as fname',
                    'users.last_name as lname',
                    'sessions.date as session_date',
                    'sessions.id as session_id',
                    'users.id as doc_id',
                    'sessions.start_time as start_time',
                    'sessions.end_time as end_time','sessions.session_id as ses_id'
                )->paginate(6);
            // foreach ($pres as $pr) {
            //     $pr->date = Helper::get_date_with_format($pr['date']);
            //     $pr->start_time = Helper::get_time_with_format($pr['start_time']);
            //     $pr->end_time = Helper::get_time_with_format($pr['end_time']);
            // }
        } elseif($request->session_id!=null && $request->dates!=null) {

            $request->dates = explode(" - ", $request->dates);
            $startdate = date('Y-m-d',strtotime($request->dates[0]));
            $enddate = date('Y-m-d',strtotime($request->dates[1]));
            $pres = DB::table('sessions')
                ->where('sessions.patient_id', $id)
                ->join('prescriptions', 'prescriptions.session_id', '=', 'sessions.id')
                ->join('tbl_cart','prescriptions.id','tbl_cart.pres_id')
                ->where('type', 'medicine')
                ->leftJoin('refill_requests', 'prescriptions.id', '=', 'refill_requests.pres_id')
                ->join('tbl_products', 'tbl_products.id', '=', 'prescriptions.medicine_id')
                ->join('users', 'users.id', '=', 'sessions.doctor_id')
                ->where('sessions.session_id',$request->session_id)
                ->where('sessions.date','>=',$startdate)
                ->where('sessions.date','<=',$enddate)
                ->select('users.name as fname', 'users.last_name as lname', 'prescriptions.id as id',
                'tbl_products.featured_image as prod_img', 'prescriptions.medicine_id','prescriptions.med_days',
                'tbl_products.name as prod_name', 'users.name as fname','prescriptions.updated_at as total_days',
                 'users.last_name as lname', 'sessions.date as session_date',
                 'sessions.id as session_id', 'users.id as doc_id', 'sessions.start_time as start_time',
                 'sessions.end_time as end_time','sessions.doctor_id','sessions.session_id as ses_id'
                // ,'refill_requests.*'
                 )
                 ->paginate(6);
            }
            elseif($request->session_id!=null){
                $pres = DB::table('sessions')
                    ->where('sessions.patient_id', $id)
                    ->join('prescriptions', 'prescriptions.session_id', '=', 'sessions.id')
                    ->join('tbl_cart','prescriptions.id','tbl_cart.pres_id')
                    ->where('type', 'medicine')
                    ->leftJoin('refill_requests', 'prescriptions.id', '=', 'refill_requests.pres_id')
                    ->join('tbl_products', 'tbl_products.id', '=', 'prescriptions.medicine_id')
                    ->join('users', 'users.id', '=', 'sessions.doctor_id')
                    ->where('sessions.session_id',$request->session_id)
                    ->select('users.name as fname', 'users.last_name as lname', 'prescriptions.id as id',
                    'tbl_products.featured_image as prod_img', 'prescriptions.medicine_id','prescriptions.med_days',
                    'tbl_products.name as prod_name', 'users.name as fname','prescriptions.updated_at as total_days',
                     'users.last_name as lname', 'sessions.date as session_date',
                     'sessions.id as session_id', 'users.id as doc_id', 'sessions.start_time as start_time',
                     'sessions.end_time as end_time','sessions.doctor_id','sessions.session_id as ses_id'
                    // ,'refill_requests.*'
                     )
                     ->paginate(6);
                }
                elseif($request->dates!=null) {
                    $request->dates = explode(" - ", $request->dates);
                    $startdate = date('Y-m-d',strtotime($request->dates[0]));
                    $enddate = date('Y-m-d',strtotime($request->dates[1]));
                    $pres = DB::table('sessions')
                        ->where('sessions.patient_id', $id)
                        ->join('prescriptions', 'prescriptions.session_id', '=', 'sessions.id')
                        ->join('tbl_cart','prescriptions.id','tbl_cart.pres_id')
                        ->where('type', 'medicine')
                        ->leftJoin('refill_requests', 'prescriptions.id', '=', 'refill_requests.pres_id')
                        ->join('tbl_products', 'tbl_products.id', '=', 'prescriptions.medicine_id')
                        ->join('users', 'users.id', '=', 'sessions.doctor_id')
                        ->where('sessions.date','>=',$startdate)
                        ->where('sessions.date','<=',$enddate)
                        ->select('users.name as fname', 'users.last_name as lname', 'prescriptions.id as id',
                        'tbl_products.featured_image as prod_img', 'prescriptions.medicine_id','prescriptions.med_days',
                        'tbl_products.name as prod_name', 'users.name as fname','prescriptions.updated_at as total_days',
                         'users.last_name as lname', 'sessions.date as session_date',
                         'sessions.id as session_id', 'users.id as doc_id', 'sessions.start_time as start_time',
                         'sessions.end_time as end_time','sessions.doctor_id','sessions.session_id as ses_id'
                        // ,'refill_requests.*'
                         )
                         ->paginate(6);
                    // dd($pres);
                    }
                else {
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
                         'sessions.end_time as end_time','sessions.doctor_id','sessions.session_id as ses_id'
                        // ,'refill_requests.*'
                         )
                         ->orderBy('id','DESC')
                         ->paginate(6);
                    }
                //  dd($pres);
            foreach ($pres  as  $sess_p) {
                $cart = Cart::where('pres_id', $sess_p->id)->first();
                $ref = RefillRequest::where('pres_id', $sess_p->id)->orderByDesc('id')->first();

                //refill requests
                if ($cart != null) {
                    $prod = AllProducts::where('id', $sess_p->medicine_id)->first();
                    $sess_p->status = $cart['status'];
                    $sess_p->prod = $prod;
                    if ($ref != null) {
                        $sess_p->granted = $ref['granted'];
                        $sess_p->session_req = $ref['session_req'];
                    } else {
                        $sess_p->granted = null;
                        $sess_p->session_req = null;
                    }
                    // $sess_p->date = Helper::get_date_with_format($sess_p->session_date);
                    // $sess_p->start_time = Helper::get_time_with_format($sess_p->start_time);
                    // $sess_p->end_time = Helper::get_time_with_format($sess_p->end_time);
                    $doc = User::find($sess_p->doctor_id);
                    $sess_p->doc = $doc['name'] . ' ' . $doc['last_name'];
                    $sess_p->doc_id = $doc['id'];
                }
            }
            //dd($pres);
            foreach ($pres as $p) {
                //$datetime=date('Y-m-d h:i A',strtotime($tblOrder->created_at));
                $p->session_date = User::convert_utc_to_user_timezone($id,$p->start_time)['date'];
                //$p->total_days = User::convert_utc_to_user_timezone($id,$p->total_days)['date'];
                $p->start_time = date('h:i A',strtotime('-15 minutes',strtotime($p->start_time)));
                $p->start_time = User::convert_utc_to_user_timezone($id,$p->start_time)['time'];
                $p->end_time = date('h:i A',strtotime('-15 minutes',strtotime($p->end_time)));
                $p->end_time = User::convert_utc_to_user_timezone($id,$p->end_time)['time'];
                $med_day = explode(' ',$p->med_days);
                $p->total_days = date('d-m-Y',strtotime('+'.$med_day[0].' days',strtotime($p->total_days)));
                $p->total_days = (int)abs(round(strtotime($p->total_days) / 86400));
                $p->current_days = date('d-m-Y',strtotime(gmdate("d-m-Y")));
                $p->current_days = (int)abs(round(strtotime($p->current_days) / 86400));
                //$tblOrder->created_at = User::convert_utc_to_user_timezone($user->id,$tblOrder->created_at);
            }
        return view('dashboard_patient.Pharmacy.Current_Medications', compact('pres'));
    }

    public function current_meds(Request $request)
    {
        $id = auth()->user()->id;
        $params = $request->query();
        // dd($request);
        if ($request['sessions'] == 'requested') {
            $pres = DB::table('sessions')->where('sessions.patient_id', $id)
                ->where('type', 'medicine')
                ->join('refill_requests', 'prescriptions.id', '=', 'refill_requests.pres_id')
                ->where('refill_requests.session_req', '1')
                ->join('tbl_products', 'tbl_products.id', '=', 'refill_requests.prod_id')
                ->join('users', 'users.id', '=', 'sessions.doctor_id')
                ->select(
                    'prescriptions.*',
                    'refill_requests.*',
                    'tbl_products.featured_image as prod_img',
                    'tbl_products.name as prod_name',
                    'users.name as fname',
                    'users.last_name as lname',
                    'sessions.date as session_date',
                    'sessions.id as session_id',
                    'users.id as doc_id',
                    'sessions.start_time as start_time',
                    'sessions.end_time as end_time'
                )->paginate(6);
            // foreach ($pres as $pr) {
            //     $pr->date = Helper::get_date_with_format($pr['date']);
            //     $pr->start_time = Helper::get_time_with_format($pr['start_time']);
            //     $pr->end_time = Helper::get_time_with_format($pr['end_time']);
            // }
        } else {
            $pres = DB::table('sessions')
                ->where('sessions.patient_id', $id)
                ->join('prescriptions', 'prescriptions.session_id', '=', 'sessions.id')
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
                 'sessions.end_time as end_time','sessions.doctor_id'
                // ,'refill_requests.*'
                 )
                 ->paginate(6);
                //  dd($pres);
            foreach ($pres as $sess_p) {
                $cart = Cart::where('pres_id', $sess_p->id)->first();
                $ref = RefillRequest::where('pres_id', $sess_p->id)->orderByDesc('id')->first();

                //refill requests
                if ($cart != null) {
                    $prod = AllProducts::where('id', $sess_p->medicine_id)->first();
                    $sess_p->status = $cart['status'];
                    $sess_p->prod = $prod;
                    if ($ref != null) {
                        $sess_p->granted = $ref['granted'];
                        $sess_p->session_req = $ref['session_req'];
                    } else {
                        $sess_p->granted = null;
                        $sess_p->session_req = null;
                    }
                    // $sess_p->date = Helper::get_date_with_format($sess_p->session_date);
                    // $sess_p->start_time = Helper::get_time_with_format($sess_p->start_time);
                    // $sess_p->end_time = Helper::get_time_with_format($sess_p->end_time);
                    $doc = User::find($sess_p->doctor_id);
                    $sess_p->doc = $doc['name'] . ' ' . $doc['last_name'];
                    $sess_p->doc_id = $doc['id'];
                }
            }
            //dd($pres);
            foreach ($pres as $p) {
                //$datetime=date('Y-m-d h:i A',strtotime($tblOrder->created_at));
                $p->session_date = User::convert_utc_to_user_timezone($id,$p->start_time)['date'];
                //$p->total_days = User::convert_utc_to_user_timezone($id,$p->total_days)['date'];
                $p->start_time = User::convert_utc_to_user_timezone($id,$p->start_time)['time'];
                $p->end_time = User::convert_utc_to_user_timezone($id,$p->end_time)['time'];
                $med_day = explode(' ',$p->med_days);
                $p->total_days = date('d-m-Y',strtotime('+'.$med_day[0].' days',strtotime($p->total_days)));
                $p->total_days = (int)abs(round(strtotime($p->total_days) / 86400));
                $p->current_days = date('d-m-Y',strtotime(gmdate("d-m-Y")));
                $p->current_days = (int)abs(round(strtotime($p->current_days) / 86400));
                //$tblOrder->created_at = User::convert_utc_to_user_timezone($user->id,$tblOrder->created_at);
            }
            //dd( $pres);
        }
        return view('patient.pharmacy.current_meds', compact('pres'));
    }

    public function refill_request(Request $request)
    {
        //dd($request);
        if (empty($request['note'])) {
            Flash::error('Refill not be empty');
            return redirect()->back();
        } else {
            $pres = Prescription::where('id', $request['id'])->first();
            $sess = Session::where('id', $pres['session_id'])->first();
            RefillRequest::create([
                'pres_id' => $request['id'],
                'prod_id' => $pres['medicine_id'],
                'session_id' => $sess['id'],
                'patient_id' => $sess['patient_id'],
                'doctor_id' => $sess['doctor_id'],
                'comment' => $request['note'],
                'granted' => '0',
            ]);
            try {
                $user = DB::table('users')->where('id', $sess['doctor_id'])->first();
                $data = [
                    'doc_name' => $user->name,
                    'doc_email' => $user->email,
                ];
                //Mail::to('baqir.redecom@gmail.com')->send(new RefillRequestToDoctorMail($data));
                Mail::to($user->email)->send(new RefillRequestToDoctorMail($data));
                $text="Dr. ".$user->last_name." you have a new refill request";
                $notification_id = Notification::create([
                    'user_id' =>  $user->id,
                    'type' => '/patient/refill/requests',
                    'text' => $text,
                    'session_id' => $pres['session_id'],
                ]);
                $data = [
                    'user_id' =>  $user->id,
                    'type' => '/patient/refill/requests',
                    'text' => $text,
                    'session_id' => $pres['session_id'],
                    'received' => 'false',
                    'appoint_id' => 'null',
                    'refill_id' => 'null',
                ];
                try {
                    // \App\Helper::firebase($user->id,'notification',$notification_id->id,$data);
                } catch (\Throwable $th) {
                    //throw $th;
                }
                event(new RealTimeMessage($user->id));

            } catch (\Exception $e) {
                Log::error($e);
            }
            return redirect()->back()->with(['msg' => 'successful']);
        }
    }

    public function request_refill(Request $request)
    {
        //dd($request);
        if (empty($request['comment'])) {
            Flash::error('Refill not be empty');
            return redirect('current_medications');
        } else {
            $pres = Prescription::where('id', $request['id'])->first();
            $sess = Session::where('id', $pres['session_id'])->first();
            RefillRequest::create([
                'pres_id' => $request['id'],
                'prod_id' => $pres['medicine_id'],
                'session_id' => $sess['id'],
                'patient_id' => $sess['patient_id'],
                'doctor_id' => $sess['doctor_id'],
                'comment' => $request['comment'],
                'granted' => '0',
            ]);
            try {
                $user = DB::table('users')->where('id', $sess['doctor_id'])->first();
                $data = [
                    'doc_name' => $user->name,
                    'doc_email' => $user->email,
                ];
                //Mail::to('baqir.redecom@gmail.com')->send(new RefillRequestToDoctorMail($data));
                Mail::to($user->email)->send(new RefillRequestToDoctorMail($data));
                $text="Dr. ".$user->last_name." you have a new refill request";
                Notification::create([
                    'user_id' =>  $user->id,
                    'type' => '/patient/refill/requests',
                    'text' => $text,
                    'session_id' => $pres['session_id'],
                ]);
                event(new RealTimeMessage($user->id));

            } catch (\Exception $e) {
                Log::error($e);
            }
            return redirect()->back()->with(['msg' => 'successful']);
        }
    }
    public function referal_requests()
    {
        $user = auth()->user();
        $referals = DB::table('referals')
            ->where('patient_id', $user->id)
            ->where('referals.status', 'pending')
            ->join('users', 'users.id', '=', 'referals.sp_doctor_id')
            ->join('specializations', 'specializations.id', '=', 'users.specialization')
            ->select(
                'users.name as fname',
                'users.last_name as lname',
                'referals.*',
                'specializations.*',
                'referals.id as ref_id'
            )
            ->get();
        // dd($referals);
        return view('patient.referal_request', compact('referals'));
    }

    public function session_search(Request $request){
        $user = auth()->user();
        $user_type = $user->user_type;
        $user_time_zone = $user->timeZone;
        $user_state = Auth::user()->state_id;
        $state = State::find($user_state);
        if($request->session_id != null && $request->datefilter == null){
            if ($state->active == 1) {
                $user_id = $user->id;
                $sessions = Session::where('patient_id', $user_id)
                    ->where('status', 'ended')
                    ->where('session_id', $request->session_id)
                    ->orderByDesc('id')
                    ->paginate(7);
                // $sessions=[];
                foreach ($sessions as $session) {

                    // $getPersentage = DB::table('doctor_percentage')->where('doc_id', $user_id)->first();
                    // $session->price = ($getPersentage->percentage / 100) * $session->price;

                    if ($session->status == 'ended' && $session->start_time != 'null' && $session->end_time != 'null') {
                        $session->date = User::convert_utc_to_user_timezone($user->id, $session->created_at)['date'];

                        $session->start_time = date('h:i A', strtotime('-15 minutes', strtotime($session->start_time)));
                        $session->start_time = User::convert_utc_to_user_timezone($user->id, $session->start_time)['time'];

                        $session->end_time = date('h:i A', strtotime('-15 minutes', strtotime($session->end_time)));
                        $session->end_time = User::convert_utc_to_user_timezone($user->id, $session->end_time)['time'];
                        // dd($session->end_time);
                        $doc = User::where('id', $session['doctor_id'])->first();
                        $session->doc_name = $doc['name'] . " " . $doc['last_name'];

                        // $links = AgoraAynalatics::where('channel', $session['channel'])->first();
                        // if ($links != null) {
                        //     $recording = $links->video_link;
                        //     $session->recording = $recording;
                        // } else {
                        //     $session->recording = 'No recording';
                        // }

                        $referred_doc = Referal::where('session_id', $session['id'])
                            ->where('patient_id', $session['patient_id'])
                            ->where('doctor_id', $user_id)
                            ->leftjoin('users', 'referals.sp_doctor_id', 'users.id')
                            ->select('users.name', 'users.last_name')
                            ->get();
                        if (count($referred_doc)) {
                            $session->refered = "You Referred the Patient to Dr." . $referred_doc[0]->name . " " . $referred_doc[0]->last_name;
                        } else {
                            $session->refered = null;
                        }
                        $session->sympptoms = DB::table('symptoms')->where('id',$session['symptom_id'])->first();

                        $pres = Prescription::where('session_id', $session['id'])->get();
                        $pres_arr = [];
                        foreach ($pres as $prod) {
                            if ($prod['type'] == 'medicine') {
                                $product = AllProducts::where('id', $prod['medicine_id'])->first();
                            } else if ($prod['type'] == 'imaging') {
                                $product = AllProducts::where('id', $prod['imaging_id'])->first();
                            } else if ($prod['type'] == 'lab-test') {
                                $product = QuestDataTestCode::where('TEST_CD', $prod['test_id'])
                                    ->first();
                            }
                            $cart = Cart::where('doc_session_id', $session['id'])
                                ->where('pres_id', $prod->id)->first();
                            // dd($cart);
                            $prod->prod_detail = $product;
                            if (isset($cart->status))
                                $prod->cart_status = $cart->status;
                            else
                                $prod->cart_status = 'No record';
                            // dd($prod);
                            array_push($pres_arr, $prod);
                        }
                        $session->pres = $pres_arr;
                        // array_push($sessions,$session);
                    }
                }
            }
        }else if($request->session_id == null && $request->datefilter != null){
            $start_date = explode(' - ',$request->datefilter)[0];
            $end_date = explode(' - ',$request->datefilter)[1];

            $start_date = date('Y-m-d', strtotime($start_date));
            $end_date = date('Y-m-d', strtotime($end_date));

            if ($state->active == 1) {
                $user_id = $user->id;
                $sessions = Session::where('patient_id', $user_id)
                    ->where('status', 'ended')
                    ->where('date', '>=' ,$start_date)
                    ->where('date', '<=' ,$end_date)
                    ->paginate(7);
                // $sessions=[];
                // dd($sessions);
                foreach ($sessions as $session) {

                    // $getPersentage = DB::table('doctor_percentage')->where('doc_id', $user_id)->first();
                    // $session->price = ($getPersentage->percentage / 100) * $session->price;

                    if ($session->status == 'ended' && $session->start_time != 'null' && $session->end_time != 'null') {
                        $session->date = User::convert_utc_to_user_timezone($user->id, $session->created_at)['date'];

                        $session->start_time = date('h:i A', strtotime('-15 minutes', strtotime($session->start_time)));
                        $session->start_time = User::convert_utc_to_user_timezone($user->id, $session->start_time)['time'];

                        $session->end_time = date('h:i A', strtotime('-15 minutes', strtotime($session->end_time)));
                        $session->end_time = User::convert_utc_to_user_timezone($user->id, $session->end_time)['time'];
                        // dd($session->end_time);
                        $doc = User::where('id', $session['doctor_id'])->first();
                        $session->doc_name = $doc['name'] . " " . $doc['last_name'];

                        // $links = AgoraAynalatics::where('channel', $session['channel'])->first();
                        // if ($links != null) {
                        //     $recording = $links->video_link;
                        //     $session->recording = $recording;
                        // } else {
                        //     $session->recording = 'No recording';
                        // }

                        $referred_doc = Referal::where('session_id', $session['id'])
                            ->where('patient_id', $session['patient_id'])
                            ->where('doctor_id', $user_id)
                            ->leftjoin('users', 'referals.sp_doctor_id', 'users.id')
                            ->select('users.name', 'users.last_name')
                            ->get();
                        if (count($referred_doc)) {
                            $session->refered = "You Referred the Patient to Dr." . $referred_doc[0]->name . " " . $referred_doc[0]->last_name;
                        } else {
                            $session->refered = null;
                        }
                        $session->sympptoms = DB::table('symptoms')->where('id',$session['symptom_id'])->first();

                        $pres = Prescription::where('session_id', $session['id'])->get();
                        $pres_arr = [];
                        foreach ($pres as $prod) {
                            if ($prod['type'] == 'medicine') {
                                $product = AllProducts::where('id', $prod['medicine_id'])->first();
                            } else if ($prod['type'] == 'imaging') {
                                $product = AllProducts::where('id', $prod['imaging_id'])->first();
                            } else if ($prod['type'] == 'lab-test') {
                                $product = QuestDataTestCode::where('TEST_CD', $prod['test_id'])
                                    ->first();
                            }
                            $cart = Cart::where('doc_session_id', $session['id'])
                                ->where('pres_id', $prod->id)->first();
                            // dd($cart);
                            $prod->prod_detail = $product;
                            if (isset($cart->status))
                                $prod->cart_status = $cart->status;
                            else
                                $prod->cart_status = 'No record';
                            // dd($prod);
                            array_push($pres_arr, $prod);
                        }
                        $session->pres = $pres_arr;
                        // array_push($sessions,$session);
                    }
                }
            }
        }else if($request->session_id != null && $request->datefilter != null){
            $start_date = explode(' - ',$request->datefilter)[0];
            $end_date = explode(' - ',$request->datefilter)[1];

            $start_date = date('Y-m-d', strtotime($start_date));
            $end_date = date('Y-m-d', strtotime($end_date));

            // dd($start_date,$end_date);
            if ($state->active == 1) {
                $user_id = $user->id;
                $sessions = Session::where('patient_id', $user_id)
                    ->where('status', 'ended')
                    ->where('session_id', $request->session_id)
                    ->where('date', '>=' ,$start_date)
                    ->where('date', '<=' ,$end_date)
                    ->paginate(7);
                // $sessions=[];
                foreach ($sessions as $session) {

                    // $getPersentage = DB::table('doctor_percentage')->where('doc_id', $user_id)->first();
                    // $session->price = ($getPersentage->percentage / 100) * $session->price;

                    if ($session->status == 'ended' && $session->start_time != 'null' && $session->end_time != 'null') {
                        $session->date = User::convert_utc_to_user_timezone($user->id, $session->created_at)['date'];

                        $session->start_time = date('h:i A', strtotime('-15 minutes', strtotime($session->start_time)));
                        $session->start_time = User::convert_utc_to_user_timezone($user->id, $session->start_time)['time'];

                        $session->end_time = date('h:i A', strtotime('-15 minutes', strtotime($session->end_time)));
                        $session->end_time = User::convert_utc_to_user_timezone($user->id, $session->end_time)['time'];
                        // dd($session->end_time);
                        $doc = User::where('id', $session['doctor_id'])->first();
                        $session->doc_name = $doc['name'] . " " . $doc['last_name'];

                        // $links = AgoraAynalatics::where('channel', $session['channel'])->first();
                        // if ($links != null) {
                        //     $recording = $links->video_link;
                        //     $session->recording = $recording;
                        // } else {
                        //     $session->recording = 'No recording';
                        // }

                        $referred_doc = Referal::where('session_id', $session['id'])
                            ->where('patient_id', $session['patient_id'])
                            ->where('doctor_id', $user_id)
                            ->leftjoin('users', 'referals.sp_doctor_id', 'users.id')
                            ->select('users.name', 'users.last_name')
                            ->get();
                        if (count($referred_doc)) {
                            $session->refered = "You Referred the Patient to Dr." . $referred_doc[0]->name . " " . $referred_doc[0]->last_name;
                        } else {
                            $session->refered = null;
                        }
                        $session->sympptoms = DB::table('symptoms')->where('id',$session['symptom_id'])->first();

                        $pres = Prescription::where('session_id', $session['id'])->get();
                        $pres_arr = [];
                        foreach ($pres as $prod) {
                            if ($prod['type'] == 'medicine') {
                                $product = AllProducts::where('id', $prod['medicine_id'])->first();
                            } else if ($prod['type'] == 'imaging') {
                                $product = AllProducts::where('id', $prod['imaging_id'])->first();
                            } else if ($prod['type'] == 'lab-test') {
                                $product = QuestDataTestCode::where('TEST_CD', $prod['test_id'])
                                    ->first();
                            }
                            $cart = Cart::where('doc_session_id', $session['id'])
                                ->where('pres_id', $prod->id)->first();
                            // dd($cart);
                            $prod->prod_detail = $product;
                            if (isset($cart->status))
                                $prod->cart_status = $cart->status;
                            else
                                $prod->cart_status = 'No record';
                            // dd($prod);
                            array_push($pres_arr, $prod);
                        }
                        $session->pres = $pres_arr;
                        // array_push($sessions,$session);
                    }
                }
            }
        }else{
            if ($state->active == 1) {
                $user_id = $user->id;
                $sessions = Session::where('patient_id', $user_id)
                    ->where('status', 'ended')
                    ->orderByDesc('id')
                    ->paginate(15);
                // $sessions=[];
                foreach ($sessions as $session) {

                    // $getPersentage = DB::table('doctor_percentage')->where('doc_id', $user_id)->first();
                    // $session->price = ($getPersentage->percentage / 100) * $session->price;

                    if ($session->status == 'ended' && $session->start_time != 'null' && $session->end_time != 'null') {
                        $session->date = User::convert_utc_to_user_timezone($user->id, $session->created_at)['date'];

                        $session->start_time = date('h:i A', strtotime('-15 minutes', strtotime($session->start_time)));
                        $session->start_time = User::convert_utc_to_user_timezone($user->id, $session->start_time)['time'];

                        $session->end_time = date('h:i A', strtotime('-15 minutes', strtotime($session->end_time)));
                        $session->end_time = User::convert_utc_to_user_timezone($user->id, $session->end_time)['time'];
                        // dd($session->end_time);
                        $doc = User::where('id', $session['doctor_id'])->first();
                        $session->doc_name = $doc['name'] . " " . $doc['last_name'];

                        // $links = AgoraAynalatics::where('channel', $session['channel'])->first();
                        // if ($links != null) {
                        //     $recording = $links->video_link;
                        //     $session->recording = $recording;
                        // } else {
                        //     $session->recording = 'No recording';
                        // }

                        $referred_doc = Referal::where('session_id', $session['id'])
                            ->where('patient_id', $session['patient_id'])
                            ->where('doctor_id', $user_id)
                            ->leftjoin('users', 'referals.sp_doctor_id', 'users.id')
                            ->select('users.name', 'users.last_name')
                            ->get();
                        if (count($referred_doc)) {
                            $session->refered = "You Referred the Patient to Dr." . $referred_doc[0]->name . " " . $referred_doc[0]->last_name;
                        } else {
                            $session->refered = null;
                        }
                        $session->sympptoms = DB::table('symptoms')->where('id',$session['symptom_id'])->first();

                        $pres = Prescription::where('session_id', $session['id'])->get();
                        $pres_arr = [];
                        foreach ($pres as $prod) {
                            if ($prod['type'] == 'medicine') {
                                $product = AllProducts::where('id', $prod['medicine_id'])->first();
                            } else if ($prod['type'] == 'imaging') {
                                $product = AllProducts::where('id', $prod['imaging_id'])->first();
                            } else if ($prod['type'] == 'lab-test') {
                                $product = QuestDataTestCode::where('TEST_CD', $prod['test_id'])
                                    ->first();
                            }
                            $cart = Cart::where('doc_session_id', $session['id'])
                                ->where('pres_id', $prod->id)->first();
                            // dd($cart);
                            $prod->prod_detail = $product;
                            if (isset($cart->status))
                                $prod->cart_status = $cart->status;
                            else
                                $prod->cart_status = 'No record';
                            // dd($prod);
                            array_push($pres_arr, $prod);
                        }
                        $session->pres = $pres_arr;
                        // array_push($sessions,$session);
                    }
                }
            }
        }
        // dd($sessions);
        return view('dashboard_patient.Session.index', compact('user_type', 'sessions'));
    }

    public function accept_referal(Request $request)
    {
        // dd($request['id']);
        $id = $request['id'];
        Referal::where('id', $id)->update(['status' => 'accepted']);
        return redirect()->back();
    }
    public function decline_referal(Request $request)
    {
        $id = $request['id'];
        Referal::where('id', $id)->update(['status' => 'decloned']);
        return redirect()->back();
    }
    public function session_requested(Request $request)
    {
    }
    public function findPatientRecord()
    {
        return view("findPatientRecord");
    }
    public function getPatientRecord(Request $request)
    {
        $trackingId = $request->search;
        $countOrder = TblOrders::where("order_id", $trackingId)->count();
        if ($countOrder != 0) {
            $findUser = TblOrders::where("order_id", $trackingId)->first();
            // if($findUser['customer_id']=="GUEST")
            // {

            $tblOrders = DB::table('tbl_orders')->select('*')
                ->where('order_id', '=', $trackingId)
                ->get();
            $userID = $findUser['customer_id'];

            $data['order_data'] = $tblOrders[0];

            $order_status = $data['order_data']->order_status;
            $orderState = $data['order_data']->order_state;
            $billing = unserialize($data['order_data']->billing);
            $shipping = unserialize($data['order_data']->shipping);
            $payment = unserialize($data['order_data']->payment);
            $order_sub_id = unserialize($data['order_data']->order_sub_id);
            $cart_items = unserialize($findUser['cart_items']);

            $cart_data = [];
            $get_var = unserialize($findUser['cart_items']);
            $basic_info = [];
            $quantities = [];

            foreach ($get_var as $key => $item) {
                $qt = $item['product_qty'];
                array_push($quantities, $qt);
                $qry = DB::table('tbl_products')
                    ->select('name as product_name', 'sale_price')
                    ->where('id', '=', $item['product_id'])
                    ->get();
                array_push($basic_info, $qry);
            }
            $i = 0;
            foreach ($basic_info as $key => $val) {
                $cart_data['products'][$i]['product_name'] = $val[0]->product_name;
                $cart_data['products'][$i]['sale_price'] = $val[0]->sale_price;
                $cart_data['products'][$i]['quantity'] = $quantities[$i];
                $cart_data['products'][$i]['cost'] = $val[0]->sale_price * $quantities[$i];
                $cart_data['total'][] = $val[0]->sale_price * $quantities[$i];
                $i++;
            }
            // dd($order_status);
            return view("findPatientRecord", compact('billing', 'shipping', 'payment', 'cart_data', 'order_sub_id', 'userID', 'orderState', 'order_status'));
            // }
            // else
            // {
            //     $allUserId=TblOrders::where("customer_id",$findUser['customer_id'])->get();
            //     dd(count($allUserId));
            //     return view("findPatientRecord",compact('findUser'));
            // }
        } else {
            // dd('ok');
            return view("findPatientRecord")->with("message", "Please Provide Valid Tracking ID");
        }
    }
    public function resendVerificationMail(Request $request)
    {
        $userEmail = Auth::user()->email;
        $userId = Auth::user()->id;

        $x = rand(10e12, 10e16);
        $hash_to_verify = base_convert($x, 10, 36);
        $otp = rand(100000, 999999);
        $data = [
            'hash' => $hash_to_verify,
            'otp' => $otp,
            'user_id' => $userId,
            'to_mail' => $userEmail,
        ];
        try {
            Mail::to($userEmail)->send(new UserVerificationEmail($data));
        } catch (Exception $e) {
            Log::error($e);
        }
        DB::table('users_email_verification')
            ->where('user_id', $userId)
            ->update([
                'verification_hash_code' => $hash_to_verify,
                'otp' => $otp,
            ]);
        return redirect()->back()->with('message', 'Resend email verification link successfully. please check your email.');
    }

    public function appointment_reschedule($app, $spec)
    {
        $user = Auth::user();
        $appoint = DB::table('appointments')->where('id',$app)->first();
        $doc_check = DB::table('users')->where('id',$appoint->doctor_id)->first();
        if($doc_check!=null){
            $doctors=DB::table('users')
            ->join('specializations','specializations.id','users.specialization')
            ->where('users.id',$appoint->doctor_id)
            ->select('specializations.name as spec','users.*')
            ->get();
            $today = date("Y:m:d", strtotime(Carbon::today()));
            $today[4]='-';$today[7]='-';
            $Availabale_dates = DoctorSchedule::where('doctorID',$appoint->doctor_id)->where('title','Availability')->where('date','>=',$today)->orderBy('date', 'asc')->groupBy('date')->get();
            return view('appointments.book_appointment',compact('Availabale_dates','doctors','user','spec'));
        }

        $doctors = DB::table('users')
            ->join('doctor_schedules', 'doctor_schedules.doctorID', 'users.id')
            ->join('specializations', 'specializations.id', 'users.specialization')
            ->join('states', 'states.id', 'users.state_id')
            ->where('doctor_schedules.date', '>=', Carbon::now())
            ->where('users.specialization', $spec)
            ->where('users.user_type', 'doctor')
            ->where('users.active', '1')
            ->select('users.*', 'specializations.name as sp_name')
            ->get();
        //dd($doctors);

        $already_session_did = DB::table('sessions')
            ->join('users','sessions.doctor_id','users.id')
            ->where('patient_id', $user->id)
            ->where('specialization_id', $spec)
            ->select('sessions.*','users.user_image as user_image')
            ->groupBy('doctor_id')
            ->get();

        $refered_doctors = DB::table('referals')
            ->where('patient_id', $user->id)
            ->where('sp_doctor_id', $spec)
            ->where('status', 'accepted')
            ->groupBy('sp_doctor_id')
            ->get();
        foreach ($already_session_did as $a_s_d) {

            $doc = DB::table('users')
                ->join('specializations', 'specializations.id', 'users.specialization')
                ->where('users.id', $a_s_d->doctor_id)
                ->select('users.*', 'specializations.name as sp_name')
                ->first();
                $a_s_d->user_image = \App\Helper::check_bucket_files_url($doc->user_image);
                $a_s_d->name = $doc->name;
                $a_s_d->last_name = $doc->last_name;
                $a_s_d->sp_name = $doc->sp_name;
                $a_s_d->specialization = $doc->specialization;
                $a_s_d->id = $doc->id;
        }
        foreach ($refered_doctors as $r_d) {

            $doc = DB::table('users')
                ->join('specializations', 'specializations.id', 'users.specialization')
                ->where('users.id', $r_d->doctor_id)
                ->select('users.*', 'specializations.name as sp_name')
                ->first();
            $r_d->user_image = \App\Helper::check_bucket_files_url($doc->user_image);
            $r_d->name = $doc->name;
            $r_d->last_name = $doc->last_name;
            $r_d->sp_name = $doc->sp_name;
            $r_d->specialization = $doc->specialization;
            $r_d->id = $doc->id;
        }

        foreach ($doctors as $doctor) {


                $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);

        }
        //dd($already_session_did);
        return view('reschedule_appointment', compact('doctors', 'refered_doctors', 'already_session_did', 'app'));
    }

    public function reschedule_appointment($app, $spec)
    {
        $user = Auth::user();
        $appoint = DB::table('appointments')->where('id',$app)->first();
        $doc_check = DB::table('users')->where('id',$appoint->doctor_id)->first();
        if($doc_check!=null){
            $doctors=DB::table('users')
            ->join('specializations','specializations.id','users.specialization')
            ->where('users.id',$appoint->doctor_id)
            ->select('specializations.name as spec','users.*')
            ->get();
            $today = date("Y:m:d", strtotime(Carbon::today()));
            $today[4]='-';$today[7]='-';
            $Availabale_dates = DoctorSchedule::where('doctorID',$appoint->doctor_id)->where('title','Availability')->where('date','>=',$today)->orderBy('date', 'asc')->groupBy('date')->get();
            return view('appointments.book_appointment',compact('Availabale_dates','doctors','user','spec'));
        }

        $doctors = DB::table('users')
            ->join('doctor_schedules', 'doctor_schedules.doctorID', 'users.id')
            ->join('specializations', 'specializations.id', 'users.specialization')
            ->join('states', 'states.id', 'users.state_id')
            ->where('doctor_schedules.date', '>=', Carbon::now())
            ->where('users.specialization', $spec)
            ->where('users.user_type', 'doctor')
            ->where('users.active', '1')
            ->select('users.*', 'specializations.name as sp_name')
            ->get();
        //dd($doctors);

        $already_session_did = DB::table('sessions')
            ->join('users','sessions.doctor_id','users.id')
            ->where('patient_id', $user->id)
            ->where('specialization_id', $spec)
            ->select('sessions.*','users.user_image as user_image')
            ->groupBy('doctor_id')
            ->get();

        $refered_doctors = DB::table('referals')
            ->where('patient_id', $user->id)
            ->where('sp_doctor_id', $spec)
            ->where('status', 'accepted')
            ->groupBy('sp_doctor_id')
            ->get();
        foreach ($already_session_did as $a_s_d) {

            $doc = DB::table('users')
                ->join('specializations', 'specializations.id', 'users.specialization')
                ->where('users.id', $a_s_d->doctor_id)
                ->select('users.*', 'specializations.name as sp_name')
                ->first();
                $a_s_d->user_image = \App\Helper::check_bucket_files_url($doc->user_image);
                $a_s_d->name = $doc->name;
                $a_s_d->last_name = $doc->last_name;
                $a_s_d->sp_name = $doc->sp_name;
                $a_s_d->specialization = $doc->specialization;
                $a_s_d->id = $doc->id;
        }
        foreach ($refered_doctors as $r_d) {

            $doc = DB::table('users')
                ->join('specializations', 'specializations.id', 'users.specialization')
                ->where('users.id', $r_d->doctor_id)
                ->select('users.*', 'specializations.name as sp_name')
                ->first();
            $r_d->user_image = \App\Helper::check_bucket_files_url($doc->user_image);
            $r_d->name = $doc->name;
            $r_d->last_name = $doc->last_name;
            $r_d->sp_name = $doc->sp_name;
            $r_d->specialization = $doc->specialization;
            $r_d->id = $doc->id;
        }

        foreach ($doctors as $doctor) {


                $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);

        }
        //dd($already_session_did);
        return view('reschedule_appointment', compact('doctors', 'refered_doctors', 'already_session_did', 'app'));
    }

    public function view_doctor($id)
    {
        $id = \Crypt::decrypt($id);
        $doc = DB::table('users')->where('id',$id)->first();
        if($doc){
            $doc->details = DB::table('doctor_details')->where('doctor_id',$id)->first();
            $doc->specializations = DB::table('specializations')->where('id',$doc->specialization)->first();
            if($doc->details){
                $doc->details->certificates = json_decode($doc->details->certificates);
                $doc->details->conditions = json_decode($doc->details->conditions);
                $doc->details->procedures = json_decode($doc->details->procedures);
            }
        }
        $doc->user_image = \App\Helper::check_bucket_files_url($doc->user_image);
        return view('dashboard_patient.Profile.view_doc_profile',compact('doc'));
    }

    public function session_reminder($id)
    {
        $id = \Crypt::decrypt($id);
        $session = DB::table('sessions')->where('id',$id)->first();
        if($session->status == 'pending')
        {

            DB::table('sessions')->where('id',$id)->update([
                'reminder' => 0,
            ]);
            return redirect(url('/patient/payment/session/'.\Crypt::encrypt($session->id)));
        }
        elseif($session->status == 'paid' || $session->status == 'invitation sent')
        {
            DB::table('sessions')->where('id',$id)->update([
                'reminder' => 0,
            ]);
            return redirect(url('/waiting/room/'.\Crypt::encrypt($session->id)));
        }
        elseif($session->status == 'cancel')
        {
            DB::table('sessions')->where('id',$id)->update([
                'reminder' => 0,
            ]);
            return redirect(url()->previous());
        }
        else{
            return redirect(url()->previous());
        }
    }

    public function add_sign(Request $request)
    {
        return redirect(url()->previous());
        // if($request['signature'] != null){
        //     DB::table('users')
        //     ->where('user_type', 'admin')
        //     ->where('username','admin')
        //     ->update([
        //         'signature' => $request['signature'],
        //     ]);
        //     return redirect(url('/'));
        // }
        // else{
        //     return redirect(url()->previous());
        // }
    }

    public function therapy_events()
    {
        $state_id = auth()->user()->state_id;
        $date_time = date('Y-m-d H:i:s');
        $date_time = date('Y-m-d H:i:s',strtotime('-5 minutes',strtotime($date_time)));
        $events = DB::table('therapy_session')
        ->where('therapy_session.status','started')
        ->orwhere('therapy_session.start_time','>=',$date_time)
        ->where('therapy_session.status','!=','ended')
        ->select('therapy_session.*')->paginate(10);
        foreach($events as $ev)
        {
            $ev->start_time = User::convert_utc_to_user_timezone(auth()->user()->id,$ev->start_time);
            $doc = User::find($ev->doctor_id);
            $ev->doc_name = $doc->name.' '.$doc->last_name;
            $ev->enroll = DB::table('therapy_patients')->where('session_id',$ev->id)->where('patient_id',auth()->user()->id)->count();
        }
        return view('dashboard_patient.therapy.upcoming_sessions',compact('events'));
    }

    public function therapy_event_payment($session_id)
    {
        $count = DB::table('therapy_patients')->where('session_id',$session_id)->count();
        $pat = DB::table('therapy_patients')->where('session_id',$session_id)->where('patient_id',auth()->user()->id)->first();
        if($pat==null || $pat=='')
        {
            if($count<15)
            {
                $session_data = DB::table('therapy_session')->where('id', $session_id)->first();
                $state_id = auth()->user()->state_id;
                $doc_license = DB::table('doctor_licenses')
                    ->where('doctor_id',$session_data->doctor_id)
                    ->where('is_verified',1)
                    ->select('doctor_licenses.state_id')
                    ->get()->toArray();
                $temp = [];
                foreach($doc_license as $license){
                    array_push($temp,$license->state_id);
                }
                if(in_array($state_id,$temp))
                {
                    $years = [];
                    $user_id = auth()->user()->id;
                    $current_year = Carbon::now()->format('Y');
                    array_push($years, $current_year);
                    $j = 15;
                    for ($i = 0; $i < $j; $i++) {
                        $get_year = $current_year += 1;
                        array_push($years, $get_year);
                    }
                    $states = State::where('country_code', 'US')->get();
                    $session_data = DB::table('therapy_session')->where('id', $session_id)->first();
                    $price = $session_data->price;
                    // dd($price);
                    $cards = DB::table('card_details')->where('user_id', $user_id)->get();
                    return view('dashboard_patient.therapy.payment', compact('session_id', 'states', 'years', 'session_data', 'cards', 'price'));
                }
                else
                {
                    return redirect()->route('therapy_events')->with('msg','This session is not available in your registered state...!!!');
                }
            }
            else
            {
                return redirect()->route('therapy_events')->with('error','Limit of patients exceed');
            }
        }
        else
        {
            return redirect()->route('therapy_events')->with('message','You are already enrolled');
        }

    }

    public function api_payment_therapy(Request $request)
    {
        //$request->session_id = \Crypt::decrypt($request->session_id);
        // dd($request);
        // dd(substr($request->card_num, 0,1));
        $id = auth()->user()->id;
        if ((isset($request->old_card))) {
            $query = DB::table('card_details')
                ->where('id', $request->card_no)
                ->get();
            // dd($query);
            $pay = new PaymentController();
            $profile = $query[0]->customerProfileId;
            $payment = $query[0]->customerPaymentProfileId;
            $amount = $request->amount_charge;
            // dd($profile,$payment,$amount);
            $response = ($pay->new_createPaymentwithCustomerProfile($amount, $profile, $payment));
            $flag = false;
        } else {
            $this->validate($request, [
                'card_holder_name' => 'required',
                'card_holder_last_name' => 'required',
                'card_num' => 'required|min:16',
                'email' => 'required',
                'phoneNumber' => 'required',
                'month' => 'required|',
                'year' => 'required|',
                'cvc' => 'required|integer',
                'state' => 'required',
                'city' => 'required',
                'zipcode' => 'required',
                'address' => 'required',
                'session_id' => 'required',
                'amount_charge' => 'required',
                'subject' => 'required',
            ]);
            $name = $request->card_holder_name . $request->card_holder_name_middle;
            $city = City::find($request->city)->name;
            $state = State::find($request->state)->name;
            $request->card_num = str_replace('-', '', $request->card_num);
            $input = [
                'user' => [
                    'description' => $request->card_holder_name . " " . $request->card_holder_last_name,
                    'email' => $request->email,
                    'firstname' => $request->card_holder_name,
                    'lastname' => $request->card_holder_last_name,
                    'phoneNumber' => $request->phoneNumber,
                ],
                'info' => [
                    'subject' => $request->subject,
                    'user_id' => auth()->user()->id,
                    'description' => $request->session_id,
                    'amount' => $request->amount_charge,
                ],
                'billing_info' => [
                    'amount' => $request->amount_charge,
                    'credit_card' => [
                        'number' => $request->card_num,
                        'expiration_month' => $request->month,
                        'expiration_year' => $request->year,
                    ],
                    'integrator_id' => $request->subject . '-' . $request->session_id,
                    'csc' => $request->cvc,
                    'billing_address' => [
                        'name' => $name,
                        'street_address' => $request->address,
                        'city' => $city,
                        'state' => $state,
                        'zip' => $request->zipcode,
                    ]
                ]
            ];
            // dd($input);
            // $request_data=new Request($input);
            // dd($request_data);
            $pay = new PaymentController();
            $response = ($pay->new_createCustomerProfile($input));
            $flag = true;
        }


        if ($response['messages']['message'][0]['text'] == 'Successful.') {
            if ($flag) {
                $profileId = $response['transactionResponse']['profile']['customerProfileId'];
                $paymentId = $response['transactionResponse']['profile']['customerPaymentProfileId'];
                $billing = array(
                    'number' => 'xxxx-xxxx-xxxx-' . substr($request->card_num, -4),
                    'expiration_month' => $request->month,
                    'expiration_year' => $request->year,
                    "csc" => $request->cvc,
                    "name" => $name,
                    "last_name" => $request->card_holder_last_name,
                    "email" => $request->email,
                    "street_address" => $request->address,
                    "city" => $city,
                    "state" => $state,
                    "zip" => $request->zipcode,
                    'phoneNumber' => $request->phoneNumber,
                );
                DB::table('card_details')->insert([
                    'user_id' => Auth::user()->id,
                    'customerProfileId' => $profileId,
                    'customerPaymentProfileId' => $paymentId,
                    'card_number' => substr($request->card_num,-4),
                    'billing' => serialize($billing),
                    'shipping' => '',
                    'card_type' =>substr($request->card_num, 0,1),
                ]);
            }
            $getSession = DB::table('therapy_session')->where('id', $request->session_id)->first();
            DB::table('therapy_patients')->insert([
                'session_id'=>$getSession->id,
                'patient_id'=>auth()->user()->id,
                'nick_name'=>$request->nick_name,
                'status'=>'paid',
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s'),
            ]);
            //DB::table('therapy_session')->where('id', $request->session_id)->update(['status' => 'paid']);
            // try{
            $phyc_info = DB::table('psychiatrist_info')->where('doctor_id',$getSession->doctor_id)
            ->where('event_id',$getSession->event_id)->first();
            $concerns = explode(',',$phyc_info->concerns);
            unset($concerns[count($concerns)-1]);
            $total = DB::table('therapy_patients')->where('session_id',$getSession->id)->count();
            $doctor_data = DB::table('users')->where('id', $getSession->doctor_id)->first();
            $patient_data = DB::table('users')->where('id', auth()->user()->id)->first();
            $state = DB::table('states')->where('id',$patient_data->state_id)->first();
            $session_date_time = User::convert_utc_to_user_timezone($doctor_data->id,$getSession->start_time);
            $markDownData = [
                'doc_name' => ucwords($doctor_data->name),
                'pat_name' => ucwords($patient_data->name),
                'pat_email' => $patient_data->email,
                'doc_mail' => $doctor_data->email,
                'amount' => $request->amount_charge,
            ];

            $markDownData1 = [
                'doc_name' => ucwords($doctor_data->name).' '.ucwords($doctor_data->last_name),
                'pat_name' => ucwords($patient_data->name).' '.ucwords($patient_data->last_name),
                'concerns' => $concerns,
                'date' => $session_date_time['date'],
                'start_time' => $session_date_time['time'],
                'pat_state' => $state->name,
                'total' => $total,
            ];
            Mail::to($patient_data->email)->send(new EvisitBookMail($markDownData));
            Mail::to($doctor_data->email)->send(new TherapyPatientEnrolled($markDownData1));

            $text = "New Patient " . $patient_data->name . " " . $patient_data->last_name." Enrolled";
            Notification::create([
                'user_id' =>  $getSession->doctor_id,
                'type' => '/add/doctor/schedule',
                'text' => $text,
                'appoint_id' => $request->session_id,
            ]);
            event(new RealTimeMessage($getSession->doctor_id));
            //event(new updateQuePatient('update patient que'));

            // }
            // catch(Exception $e){
            //     Log::error($e);
            // }
            return redirect()->route('therapy_events');
        } else {
            // dd($response['messages']['message'][0]['text']);
            $code = $response['messages']['message'][0]['code'];
            $message = TblTransaction::errorCode($code);
            DB::table('error_log')->insert([
                'user_id' => $id,
                'Error_code' => $code,
                'Error_text' => $response['messages']['message'][0]['text'],
            ]);
            return redirect()->route('therapy_event_payment', ['id' => $request->session_id])->with('error_message', $message);
        }
    }

    public function patient_therapy_video($id)
    {
        if(auth()->user()->user_type == 'patient'){
            $therapy_session = DB::table('therapy_session')->where('id',$id)->first();
            if($therapy_session->status == 'started')
            {
                $therapy_patient = DB::table('therapy_patients')->where('session_id',$id)->where('patient_id',auth()->user()->id)->first();
                if($therapy_patient!=null || $therapy_patient!='')
                {
                    $therapy_patients = DB::table('therapy_patients')->where('session_id',$id)->get();
                    $doc = DB::table('users')->where('id',$therapy_session->doctor_id)->first();
                    $therapy_session->doc_name =  'Dr.'.$doc->name.' '.$doc->last_name;
                    $therapy_session->pat_name = $therapy_patient->nick_name;
                    $therapy_patients = DB::table('therapy_patients')->where('session_id',$id)->get()->toArray();
                    $therapy_patients = json_encode($therapy_patients);
                    $time = strtotime(date('Y-m-d H:i:s'))-strtotime($therapy_session->end_time);
                    return view('dashboard_patient.Video.conference_video',compact('therapy_session','therapy_patients','time'));
                }
                else
                {
                    return redirect()->route('therapy_event_payment',['id' => $id])->with('msg','first pay for this event');
                }
            }
            elseif($therapy_session->status == 'ended')
            {
                return redirect()->back()->with('msg','This Session is finished');
            }
            else
            {
                $therapy_patient = DB::table('therapy_patients')->where('session_id',$id)->where('patient_id',auth()->user()->id)->count();
                if($therapy_patient < 1)
                {
                    $state_id = auth()->user()->state_id;
                    $doc_license = DB::table('doctor_licenses')
                        ->where('doctor_id',$session_data->doctor_id)
                        ->where('is_verified',1)
                        ->select('doctor_licenses.state_id')
                        ->get()->toArray();
                    $temp = [];
                    foreach($doc_license as $license){
                        array_push($temp,$license->state_id);
                    }
                    if(in_array($state_id,$temp)){
                        return redirect()->route('therapy_event_payment', ['id' => $id]);
                    }
                    else
                    {
                        return redirect()->route('therapy_events')->with('msg','This session is not available in your registered state...!!!');
                    }
                }
                else
                {
                    return redirect()->route('therapy_events')->with('msg','You are joining too early... doctor have not joined yet');
                }
            }
        }else{
            return redirect()->route('wrong_address');
        }
    }

    public function raise_hand(Request $request)
    {
        if($request->session_id != null && $request->pat_id != null)
        {
            event(new HandRaise($request->session_id,$request->pat_id));
            return "ok";
        }
        else{
            return "no";
        }
    }

    public function end_conference_call($id)
    {
        if($id!=0)
        {
            event(new PatientCallEnd($id,auth()->user()->id));
            return redirect()->route('therapy_events')->with('message','You have ended the call successfully...!!!');
        }
        else
        {
            return redirect()->route('therapy_events')->with('message','Session is over, Doctor ended the call...!!!');
        }
    }

    public function doctor_in_clinic(Request $request){
        if($request->ajax()){
            $patients = InClinics::with(['user','med_profile'])->where('id',$request->id)->where('status','paid')->first();
            if($patients){
                $patients->user->age = User::get_age($patients->user->id);
                $patient->med_profile->immunization_history = json_decode($patient->med_profile->immunization_history);
            }
            return response()->json($patients, 200);
        }else{
            $patients = InClinics::with(['user','med_profile'])->where('status','paid')->orderBy('id','asc')->get();
            $med = DB::table('products_sub_categories')->where('parent_id', '38')
                ->join('tbl_products','products_sub_categories.id','tbl_products.sub_category')
                ->select('products_sub_categories.*')
                ->groupBy('tbl_products.sub_category')
                ->get();
            $img = DB::table('product_categories')->where('category_type', 'imaging')->get();
            foreach($patients as $patient){
                $patient->med_profile->immunization_history = json_decode($patient->med_profile->immunization_history);
            }
            return view('dashboard_doctor.in_clinic.index',compact('patients','med','img'));
        }
    }
}
