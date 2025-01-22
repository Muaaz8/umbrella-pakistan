<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\AllProducts;
use App\LabReport;
use App\User;
use Carbon\Carbon;
use DB;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Support\Facades\Cache;
// use Laravel\Sanctum\HasApiTokens;
use App\QuestResult;
use App\Http\Controllers\HL7Controller;
use App\Notifications\CustomResetPasswordNotification;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
// use Laravel\Passport\HasApiTokens;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasRoles, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_type', 'name', 'last_name', 'status', 'temp_password',
        'email', 'password', 'date_of_birth', 'phone_number',
        'office_address', 'zip_code',
        'nip_number', 'upin', 'specialization', 'id_card_front', 'id_card_back', 'terms_and_cond', 'signature',
        'time_from', 'time_to', 'user_image', 'bio', 'state_id',
        'created_by', 'gender',
        // 'cnic','uid',
        'med_record_file',
        'username', 'representative_name', 'representative_relation',
        'city_id', 'country_id', 'provider', 'provider_id', 'rating', 'timeZone',
        'created_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function refillRequest()
    {
        return $this->hasOne(RefillRequest::class);
    }
    public function get_current_medicines($user_id)
    {
        // $id=auth()->user()->id;

        $sessions = Session::where('patient_id', $user_id)->orderByDesc('id')->get();
        // dd($sessions);
        $pres = [];
        if (!empty($sessions)) {
            foreach ($sessions as $session) {
                $sess_pres = Prescription::where('session_id', $session['id'])->where('type', 'medicine')->groupBy('medicine_id')->get();
                $doc = User::where('id', $session['doctor_id'])->first();
                // dd($doc);
                if ($sess_pres->count() > 0) {
                    foreach ($sess_pres as $sess_p) {
                        $cart = Cart::where('pres_id', $sess_p['id'])->first();
                        $ref = RefillRequest::where('pres_id', $sess_p['id'])->orderByDesc('id')->first();
                        //refill requests
                        if ($cart != null) {
                            $prod = AllProducts::where('id', $sess_p['medicine_id'])->first();
                            $sess_p->status = $cart['status'];
                            $sess_p->prod = $prod;
                            if ($ref != null)
                                $sess_p->granted = $ref['granted'];
                            else
                                $sess_p->granted = null;
                            $sess_p->date = Helper::get_date_with_format($session['date']);
                            $sess_p->doc = $doc['name'] . ' ' . $doc['last_name'];
                            array_push($pres, $sess_p);
                        }
                    }
                }
            }
        }
        foreach($pres as $pre)
        {
            $pre->ndate = User::convert_utc_to_user_timezone(auth()->user()->id, $pre->created_at)['date'];
        }
        return $pres;
    }

    public function get_current_med($user_id)
    {
        // $id=auth()->user()->id;

        $sessions = Session::where('patient_id', $user_id)->orderByDesc('id')->get();

        $pres = [];
        if (!empty($sessions)) {
            foreach ($sessions as $session) {
                $doc = User::where('id', $session['doctor_id'])->first();


                $sess_pres = DB::table('prescriptions')
                    ->join('tbl_products', 'tbl_products.id', 'prescriptions.medicine_id')
                    ->where('prescriptions.session_id', $session['id'])
                    ->where('prescriptions.type', 'medicine')
                    ->groupBy('medicine_id')
                    ->select('prescriptions.created_at as session_date', 'prescriptions.medicine_id','prescriptions.usage' , 'prescriptions.session_id', 'tbl_products.name as pro_name')
                    ->get();

                if ($sess_pres->count() > 0) {
                    $date = $this->convert_utc_to_user_timezone($session['doctor_id'], $sess_pres[0]->session_date);
                    $name = array('prescrib_by' => $doc->name . ' ' . $doc->last_name, 'date' => $date['date']);
                    $prod['mediciens'] = [];
                    foreach ($sess_pres as $sess_p) {
                        $buyItem = DB::table('medicine_order')->where('order_product_id', $sess_p->medicine_id)->where('session_id', $sess_p->session_id)->first();
                        if ($buyItem != null) {
                            $data = ['pro_name' => $sess_p->pro_name, 'status' => 'Purchased', 'usage' => $sess_p->usage ];
                            array_push($prod['mediciens'], $data);
                        } else {
                            $data = ['pro_name' => $sess_p->pro_name, 'status' => 'Recommend' , 'usage' => $sess_p->usage];
                            array_push($prod['mediciens'], $data);
                        }
                    }
                    $marge_array = array_merge($name, $prod);

                    array_push($pres, $marge_array);
                }
            }
        }
        return $pres;
    }
    public function get_sessions($user_id)
    {
        $sessions = Session::where(['patient_id' => $user_id, 'status' => 'ended'])->orderByDesc('id')->get();
        if (!empty($sessions)) {
            foreach ($sessions as $session) {
                $doc = User::where('id', $session['doctor_id'])->first();
                $session->doc = $doc['name'] . ' ' . $doc['last_name'];
                $user_time_zone = Auth::user()->timeZone;
                $date = new DateTime($session['date']);
                $date->setTimezone(new DateTimeZone($user_time_zone));
                $session->date = Helper::get_date_with_format($date->format('Y-m-d'));
            }
        }
        return $sessions;
    }
    public function get_recent_sessions($user_id)
    {
        $sessions = Session::where(['patient_id' => $user_id, 'status' => 'ended'])->orderByDesc('id')->paginate(6);
        if (!empty($sessions)) {
            foreach ($sessions as $session) {
                $doc = User::where('id', $session['doctor_id'])->first();
                $session->doc = $doc['name'] . ' ' . $doc['last_name'];
                $user_time_zone = Auth::user()->timeZone;
                $date = new DateTime($session['date']);
                $date->setTimezone(new DateTimeZone($user_time_zone));
                $session->date = Helper::get_date_with_format($date->format('Y-m-d'));
            }
        }
        return $sessions;
    }
    public static function get_full_session_details($user_id)
    {
        $sessionss = Session::where('patient_id', $user_id)
            ->orderByDesc('id')
            ->paginate(4,['*'],'ses');
            foreach ($sessionss as $session) {
                $session->date = Helper::get_date_with_format($session->date);
                if ($session->start_time == null) {
                    $session->start_time = Helper::get_time_with_format($session->created_at);
                } else {
                    $session->start_time = Helper::get_time_with_format($session->start_time);
                }
                if ($session->end_time == null) {
                    $session->end_time = Helper::get_time_with_format($session->updated_at);
                } else {
                    $session->end_time = Helper::get_time_with_format($session->end_time);
                }
                // dd($session->end_time);
                $doc = User::where('id', $session['doctor_id'])->first();
                $session->doc_name = $doc['name'] . " " . $doc['last_name'];
                $pres = Prescription::where('session_id', $session['id'])->get();
                $pres_arr = [];
                foreach ($pres as $prod) {
                    $cart = Cart::where('pres_id', $prod['id'])->first();
                    $product = '';
                    if ($cart != null) {
                        if ($prod['test_id'] == '0') {
                            if ($prod['medicine_id'] == '0') {
                                $product = AllProducts::where('id', $prod['imaging_id'])->first();
                            } else {
                                $product = AllProducts::where('id', $prod['medicine_id'])->first();
                            }
                        } else {
                            $product = QuestDataTestCode::where('TEST_CD', $prod['test_id'])
                                ->first();
                        }
                        $prod->status = $cart['status'];
                    }
                    // $product=AllProducts::where('id',$prod['medicine_id'])->first();
                    // dd($product);
                    $prod->prod_detail = $product;
                    // dd($prod);
                    array_push($pres_arr, $prod);
                }
                $session->pres = $pres_arr;
                // array_push($sessions,$session);
            }
        return $sessionss;
    }

    public function get_current_labs($user_id)
    {
        $sessions = Session::where('patient_id', $user_id)->orderByDesc('id')->get();
        // dd($sessions);
        $pres = [];
        if (!empty($sessions)) {
            foreach ($sessions as $session) {
                $sess_pres = Prescription::where('session_id', $session['id'])->where('type', 'lab-test')->get();
                $doc = User::where('id', $session['doctor_id'])->first();
                // dd($doc);
                if ($sess_pres->count() > 0) {
                    foreach ($sess_pres as $sess_p) {
                        $cart = Cart::where('pres_id', $sess_p['id'])->first();
                        $ref = RefillRequest::where('pres_id', $sess_p['id'])->orderByDesc('id')->first();
                        if ($cart != null) {
                            $prod = AllProducts::where('id', $sess_p['medicine_id'])->first();
                            $sess_p->status = $cart['status'];
                            $sess_p->prod = $prod;
                            if ($ref != null)
                                $sess_p->granted = $ref['granted'];
                            else
                                $sess_p->granted = null;
                            $sess_p->date = Helper::get_date_with_format($session['date']);
                            $sess_p->doc = $doc['name'] . ' ' . $doc['last_name'];
                            array_push($pres, $sess_p);
                        }
                    }
                }
            }
        }
        return $pres;
    }

    public function get_lab_reports($user_id)
    {
        $labs = QuestResult::where('pat_id', $user_id)
            ->latest()
            ->where('status', 'success')
            ->orderByDesc('id')
            ->get();
        $hl7_obj = new HL7Controller();
        foreach ($labs as $report) {
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
            if ($report->file != null) {
                $report->file = \App\Helper::get_files_url($report->file);
            }
            // dd($report);
        }
        return $labs;
    }
    public function get_pending_labs($user_id)
    {
        $labs = DB::table('sessions')
        ->join('tbl_cart','sessions.id','tbl_cart.doc_session_id')
        ->where('tbl_cart.user_id',$user_id)
        ->where('tbl_cart.product_mode','lab-test')
        ->where('tbl_cart.item_type','prescribed')
        ->where('tbl_cart.status','purchased')
        ->select('tbl_cart.*','sessions.date as session_date')
        ->latest()
        ->paginate(3);
        return $labs;
    }
    public function get_pending_imagings($user_id)
    {
        $labs = DB::table('sessions')
        ->join('tbl_cart','sessions.id','tbl_cart.doc_session_id')
        ->where('tbl_cart.user_id',$user_id)
        ->where('tbl_cart.product_mode','imaging')
        ->where('tbl_cart.item_type','prescribed')
        ->where('tbl_cart.status','purchased')
        ->select('tbl_cart.*','sessions.date as session_date')
        ->paginate(3);
        return $labs;
    }
    public function get_imaging_reports($patient_id)
    {
        $reports = \DB::table('imaging_orders')
            ->join('tbl_products', 'tbl_products.id', '=', 'imaging_orders.product_id')
            ->where('imaging_orders.user_id', $patient_id)
            ->where('imaging_orders.status', 'reported')
            ->select(
                'imaging_orders.id',
                'imaging_orders.created_at',
                'imaging_orders.updated_at',
                'tbl_products.name',
                'imaging_orders.report'
            )
            ->latest()
            ->get();
        // dd($reports);
        foreach ($reports as $report) {
            if ($report->report != null) {
                $report->report = \App\Helper::get_files_url($report->report);
            }
        }

        return $reports;
    }
    public static function get_age($id)
    {
        // $birthDate = "12/17/1983";
        $user = User::find($id);

        $dob = $user->date_of_birth;
        $birthDate = explode("-", $dob);
        $age = (date("md", date("U", mktime(
            0,
            0,
            0,
            $birthDate[1],
            $birthDate[2],
            $birthDate[0]
        ))) > date("md")
            ? ((date("Y") - $birthDate[0]) - 1)
            : (date("Y") - $birthDate[0]));
        return $age;
    }

    public static function getName($id)
    {

        $user = User::find($id);
        if ($user->user_type == 'doctor')
            return 'Dr. ' . ucfirst($user->name) . ' ' . ucfirst($user->last_name);
        else
            return ucfirst($user->name) . ' ' . ucfirst($user->last_name);
    }
    public function isOnline()
    {
        $value = Cache::get('userid');
        return $value;
    }
    public static function dob_month($dob, $returnType)
    {
        if ($returnType == 'int') {
            $birthDate = explode("-", $dob);
            return $birthDate[1];
        }
    }
    public static function dob_year($dob, $returnType)
    {
        if ($returnType == 'int') {
            $birthDate = explode("-", $dob);
            return $birthDate[2];
        }
    }
    public static function dob_date($dob, $returnType)
    {
        if ($returnType == 'int') {
            $birthDate = explode("-", $dob);
            return $birthDate[0];
        }
    }
    public static function patient_info($id)
    {
        $user = User::find($id);
        $user->user_image = \App\Helper::check_bucket_files_url($user->user_image);
        return $user;
    }
    public static function getEmail($id)
    {
        $user = User::find($id);
        return $user->email;
    }
    // public function sendPasswordResetNotification($token)
    // {
    //     $this->notify(new CustomResetPasswordNotification($token));
    // }
    public function sendPasswordResetNotification($token)
    {
        $data = [
            $this->email
        ];
        Mail::send('emails.resetTesting', [
            'fullname'      => $this->name,
            'reset_url'     => route('password.reset', ['token' => $token, 'email' => $this->email]),
            'email' => $data[0],
        ], function ($message) use ($data) {
            $message->subject('Reset Password Request');
            $message->to($data[0]);
        });
    }
    public function get_user_all_orders_items($id)
    {
        $getOrders = DB::table('tbl_orders')->where('customer_id', $id)->get();
        foreach ($getOrders as $order) {
            $getCartItems = unserialize($order->cart_items);
            $lab = [];
            $med = [];
            $img = [];
            foreach ($getCartItems as $item) {
                if ($item['product_mode'] == 'medicine') {
                    $med_pro = ['product_name' => $item['product_name'], 'quantity' => $item['quantity'], 'price' => $item['price'], 'created_at' => $order->created_at];
                    array_push($med, $med_pro);
                }
                if ($item['product_mode'] == 'lab-test') {
                    $labs_pro = ['product_name' => $item['product_name'], 'quantity' => $item['quantity'], 'price' => $item['price'], 'created_at' => $order->created_at];
                    array_push($lab, $labs_pro);
                }
                if ($item['product_mode'] == 'imaging') {
                    $imaging_pro = ['product_name' => $item['product_name'], 'quantity' => $item['quantity'], 'price' => $item['price'], 'created_at' => $order->created_at];
                    array_push($img, $imaging_pro);
                }
            }
        }
        return ['lab' => $lab, 'medicine' => $med, 'imaging' => $img];
    }
    public static function convert_utc_to_user_timezone($uid, $dateAndTime)
    {
        $user = User::find($uid);

        $date = new DateTime($dateAndTime);

        $date->setTimezone(new DateTimeZone($user->timeZone));

        $format = array('date' => $date->format('m-d-Y'), 'time' => $date->format('h:i A'), 'datetime' => $date->format('Y-m-d h:i A'));
        return $format;
    }
    public static function convert_user_timezone_to_utc($uid, $dateAndTime)
    {
        $user = User::find($uid);
        $newDateTime = new DateTime($dateAndTime, new DateTimeZone($user->timeZone));
        // dd($newDateTime);
        $newDateTime->setTimezone(new DateTimeZone("UTC"));
        $format = array('date' => $newDateTime->format('Y-m-d'), 'time' => $newDateTime->format('h:i A'), 'datetime' => $newDateTime->format('Y-m-d h:i A'));
        return $format;
    }
    public static function dob_month_dashes($dob, $returnType)
    {
        if ($returnType == 'int') {
            $birthDate = explode("-", $dob);
            return $birthDate[1];
        }
    }
    public static function dob_year_dashes($dob, $returnType)
    {
        if ($returnType == 'int') {
            $birthDate = explode("-", $dob);
            return $birthDate[2];
        }
    }
    public static function dob_date_dashes($dob, $returnType)
    {
        if ($returnType == 'int') {
            $birthDate = explode("-", $dob);
            return $birthDate[0];
        }
    }
}
