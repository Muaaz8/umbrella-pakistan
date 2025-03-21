<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use App\UserDetails;
use App\Country;
use App\Models\Contract;
use App\State;
use App\DoctorLicense;
use App\Mail\UserVerificationEmail;
use App\Specialization;
use \Auth;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use Exception;
use App\Models\Document;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Image;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    // if()
    // protected $redirectTo = RouteServiceProvider::HOME;
    protected $type;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
    public function showRegistrationForm()
    {
        $countries = Country::all();
        $states = State::where('country_code', 'US')->get();
        $specs = Specialization::all();
        $date=Carbon::now()->format('m-d-y');
        return view('auth.register', compact('countries', 'specs', 'states','date'));
    }
    public function doctor_register()
    {
        $states = State::where('country_code', 'US')->get();
        $specs = Specialization::where('status', '1')->get(); // Specialization::all();
        $term = Document::where('name', 'term of use')->orderByDesc('id')->first();
        $date=date('m-d-Y',strtotime($term->updated_at));
        // return view("auth.doctor_register", compact('states', 'specs', 'term'));
        return view("auth.new_doc_register", compact('states', 'specs', 'term','date'));
    }
    public function nurse_register()
    {
        $states = State::where('country_code', 'US')->get();
        $specs = Specialization::where('status', '1')->get(); // Specialization::all();
        $term = Document::where('name', 'term of use')->orderByDesc('id')->first();
        $date=date('m-d-Y',strtotime($term->updated_at));
        // return view("auth.doctor_register", compact('states', 'specs', 'term'));
        return view("auth.new_nurse_register", compact('states', 'specs', 'term','date'));
    }
    public function patient_register()
    {
        $states = State::where('country_code', 'US')->get();
        $term = Document::where('name', 'term of use')->orderByDesc('id')->first();
        $date=Carbon::now()->format('m-d-y');
        // return view("auth.patient_register", compact('states', 'term'));
        return view("auth.new_pat_register", compact('states', 'term','date'));
    }
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        // dd($data);
        if($data['user_type'] == 'patient'){
            // if($data['rep_radio'] == 'representative'){
            //     return Validator::make($data, [
            //         'rep_fullname' => ['required'],
            //         'name' => ['required'],
            //         'last_name' => ['required'],
            //         'email' => ['required'],
            //         'username' => ['required'],
            //         'password' => ['required'],
            //     ]);
            // } else {
                return Validator::make($data, [
                'name' => ['required'],
                'last_name' => ['required'],
                'email' => ['required'],
                'password' => ['required'],
            ]);
            // }
         }else {
            return Validator::make($data, [
            'name' => ['required'],
            'last_name' => ['required'],
            'email' => ['required'],
            'password' => ['required'],
        ]);
        }
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */

    protected function create(array $data)
    {

        $this->type= $data['url_type'];
        // $information = geoip($_SERVER['REMOTE_ADDR']);
        // $geo_info = $information->toArray();
        // $timeZone = $geo_info['timezone'];
        // $digits = 5;
        // $rand = str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
        // $date = str_replace('/', '-', $data['date_of_birth']);
        // $data['date_of_birth'] = date('d-m-Y', strtotime($date));
        $captcha='';
        if(isset($_POST['g-recaptcha-response']))
        {
            $captcha = $_POST['g-recaptcha-response'];
        }
        else
        {
            return redirect()->back()->with('You Are A Robot');
        }
        $secretKey = "6LctFXkqAAAAAIMmlIukFW8I-pb_-iUeAhB-LQ7O";
        $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secretKey.'&response='.$captcha);
        $response = json_decode($response,true);

        if($response['success'] === true)
        {
            $user_type = $data['user_type'];
            if ($user_type == 'patient')
            {
                    $datecheck = $data['date_of_birth'];
                    // dd($datecheck);
                    $date = str_replace('-', '/', $datecheck);
                    $newd_o_b = date("Y-m-d", strtotime($date));
                    if (str_contains($datecheck, "/")) {
                        $newd_o_b;
                    }
                    $user = User::create([
                        'user_type' => $data['user_type'],
                        'name' => $data['name'],
                        'last_name' => $data['last_name'],
                        'email' => $data['email'],
                        'username' => $data['name'].'_'.$data['last_name'],
                        'country_id' => $data['country'],
                        'city_id' => $data['city'],
                        'state_id' => '',
                        'password' => Hash::make($data['password']),
                        'date_of_birth' => $newd_o_b,
                        'phone_number' => $data['phone_number'],
                        'office_address' => "",
                        'zip_code' => '',
                        'gender' => $data['gender'],
                        'terms_and_cond' => $data['terms_and_cond'],
                        'timeZone' => $data['timezone'],
                    ]);
                    $x = rand(10e12, 10e16);
                    $hash_to_verify = base_convert($x, 10, 36);
                    $otp = rand(100000, 999999);
                    $data1 = [
                        'hash' => $hash_to_verify,
                        'user_id' => $user->id,
                        'otp' => $otp,
                        'to_mail' => $user->email,
                    ];
                    try {
                        Mail::to($user->email)->send(new UserVerificationEmail($data1));
                    } catch (Exception $e) {
                        Log::error($e);
                    }
                    try {
                        $whatsapp = new \App\Http\Controllers\WhatsAppController();
                        $res = $whatsapp->send_otp_message($data['phone_number'],$otp);
                        Log::error($res);
                    } catch (Exception $e) {
                        Log::error($e);
                    }
                    DB::table('users_email_verification')->insert([
                        'verification_hash_code' => $hash_to_verify,
                        'user_id' => $user->id,
                        'otp' => $otp,
                    ]);
                    return $user;
            }
            //doctor registration
            else {
                $datecheck = $data['date_of_birth'];
                $date = str_replace('-', '/', $datecheck);
                $newd_o_b = date("Y-m-d", strtotime($date));
                if (str_contains($datecheck, "/")) {
                    $newd_o_b;
                }

                if(request()->hasFile('id_front_side')){
                    $file = request()->file('id_front_side');
                    $frontimageName = "doctors/" .date('YmdHis').''.$file->getClientOriginalName();
                    $img = Image::make($file);
                    $img->resize(1000, 1000, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    //detach method is the key! Hours to find it... :/
                    $resource = $img->stream()->detach();
                    $filename = \Storage::disk('s3')->put(
                        $frontimageName,
                        $resource
                    );
                }else{
                    $frontimageName = 'doctors/'.date('YmdHis');
                }
                if(request()->hasFile('id_back_side')){
                    $file = request()->file('id_back_side');
                    $backimageName = "doctors/" .date('YmdHis').''.$file->getClientOriginalName();
                    $img = Image::make($file);
                    $img->resize(1000, 1000, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    //detach method is the key! Hours to find it... :/
                    $resource = $img->stream()->detach();
                    $backfilename = \Storage::disk('s3')->put(
                        $backimageName,
                        $resource
                    );
                }else{
                    $backimageName = 'doctors/'.date('YmdHis');
                }
                if(request()->hasFile('profile_pic')){
                    $file = request()->file('profile_pic');
                    $profile_pic = "user_profile_images/" .date('YmdHis').''.$file->getClientOriginalName();
                    $img = Image::make($file);
                    $img->resize(1000, 1000, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    //detach method is the key! Hours to find it... :/
                    $resource = $img->stream()->detach();
                    $backfilename = \Storage::disk('s3')->put(
                        $profile_pic,
                        $resource
                    );
                }else{
                    $profile_pic = 'user.png';
                }

                $user = User::create([
                    'user_type' => $data['user_type'],
                    'name' => $data['name'],
                    'last_name' => $data['last_name'],
                    'email' => $data['email'],
                    'username' => $data['name'].'_'.$data['last_name'],
                    'country_id' => $data['country'],
                    'city_id' => $data['city'],
                    'state_id' => '',
                    'password' => Hash::make($data['password']),
                    'date_of_birth' => $newd_o_b,
                    'phone_number' => $data['phone_number'],
                    'office_address' => $data['address'],
                    'zip_code' => null,
                    'nip_number' => $data['npi'],
                    'consultation_fee' => $data['consultation_fee'],
                    'followup_fee' => $data['follow_up_fee'],
                    'active' => '1',
                    'upin' => '',
                    'specialization' => $data['specializations'],
                    'gender' => $data['gender'],
                    'id_card_front' => $frontimageName,
                    'id_card_back' => $backimageName,
                    'user_image' => $profile_pic,
                    'terms_and_cond' => $data['terms_and_cond'],
                    'signature' => $data['signature'],
                    'timeZone' => $data['timezone'],
                ]);
                $x = rand(10e12, 10e16);
                $otp = rand(100000, 999999);
                $hash_to_verify = base_convert($x, 10, 36);
                $data1 = [
                    'hash' => $hash_to_verify,
                    'user_id' => $user->id,
                    'to_mail' => $user->email,
                    'otp' => $otp,
                ];
                try {
                    Mail::to($user->email)->send(new UserVerificationEmail($data1));
                } catch (Exception $e) {
                    Log::error($e);
                }
                try {
                    $whatsapp = new \App\Http\Controllers\WhatsAppController();
                    $res = $whatsapp->send_otp_message($data['phone_number'],$otp);
                    Log::error($res);
                } catch (Exception $e) {
                    Log::error($e);
                }

                DB::table('users_email_verification')->insert([
                    'verification_hash_code' => $hash_to_verify,
                    'user_id' => $user->id,
                    'otp' => $otp,
                ]);
                DB::table('doctor_percentage')->insert([
                    'doc_id'=>$user->id,
                    'percentage'=>70,
                ]);
                Contract::create([
                    'slug' => 'UMB'.time(),
                    'provider_id' => $user->id,
                    'provider_name'  => $data['name'].' '.$data['last_name'],
                    'provider_address' => $data['address'],
                    'provider_email_address' => $data['email'],
                    'provider_speciality' => $data['specializations'],
                    'date' => date('Y-m-d'),
                    'session_percentage' => 70,
                    'signature' => $data['signature'],
                    'status' => 'signed',
                ]);
                return $user;
            }
        }
        else
        {
            return redirect()->back()->with('You Are A Robot');
        }

    }
    public function verify_email_unique(Request $request)
    {
        $user = User::where('email', $request['email'])->first();
        return (isset($user->id)) ? 1 : 0;
    }
    public function verify_username_unique(Request $request)
    {
        $user = User::where('username', $request['username'])->first();
        return (isset($user->id)) ? 1 : 0;
    }
    public function verify_nip_unique(Request $request)
    {
        $user = User::where('nip_number', $request['nip_number'])->first();
        return (isset($user->id)) ? 1 : 0;
    }
    public function redirectPath()
    {
        // if (method_exists($this, 'redirectTo')) {
        //     return $this->redirectTo();
        // }
        return $this->registerRouting($this->type);
        // return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
    }
    public function registerRouting($url_type)
    {

        if (!empty($url_type)) {
            $routeName = $url_type;
            switch ($routeName) {
                case "pharmacy":
                    return '/pharmacy';
                    break;
                case "labtests":
                    return '/labtests';
                case "medical_profile":
                    return '/home';
                    break;
                default:
                    return '/home';
            }
        }
        else {
            return '/home';
        }
    }
}
