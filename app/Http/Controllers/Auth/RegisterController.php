<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use App\UserDetails;
use App\Country;
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
            if($data['rep_radio'] == 'representative'){
                return Validator::make($data, [
                    'rep_fullname' => ['required'],
                    'name' => ['required'],
                    'last_name' => ['required'],
                    'email' => ['required'],
                    'username' => ['required'],
                    'password' => ['required'],
                ]);
            } else {
                return Validator::make($data, [
                'name' => ['required'],
                'last_name' => ['required'],
                'email' => ['required'],
                'username' => ['required'],
                'password' => ['required'],
            ]);
            }
         }else {
            return Validator::make($data, [
            'name' => ['required'],
            'last_name' => ['required'],
            'email' => ['required'],
            'username' => ['required'],
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
        $secretKey = "6Lfx12gjAAAAABKWoz1v0TkSkjDxPmy4yfU84n7m";
        $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secretKey.'&response='.$captcha);
        $response = json_decode($response,true);

        if($response['success'] === true)
        {
            $user_type = $data['user_type'];
            if ($user_type == 'patient')
            {
                if ($data['rep_radio'] == 'representative')
                {
                    $datecheck = $data['date_of_birth'];
                    //  dd($datecheck);
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
                        'username' => $data['username'],
                        'country_id' => $data['country'],
                        'city_id' => $data['city'],
                        'state_id' => $data['state'],
                        'password' => Hash::make($data['password']),
                        'date_of_birth' => $newd_o_b,
                        'phone_number' => $data['phone_number'],
                        'office_address' => $data['address'],
                        'zip_code' => $data['zip_code'],
                        'gender' => $data['gender'],
                        'terms_and_cond' => $data['terms_and_cond'],
                        'timeZone' => $data['timezone'],
                        'representative_name' => $data['rep_fullname'],
                        'representative_relation' => $data['rep_relation'],
                    ]);

                    $x = rand(10e12, 10e16);
                    $hash_to_verify = base_convert($x, 10, 36);
                    $data1 = [
                        'hash' => $hash_to_verify,
                        'user_id' => $user->id,
                        'to_mail' => $user->email,
                    ];
                    try {
                        Mail::to($user->email)->send(new UserVerificationEmail($data1));
                    } catch (Exception $e) {
                        Log::error($e);
                    }
                    DB::table('users_email_verification')->insert([
                        'verification_hash_code' => $hash_to_verify,
                        'user_id' => $user->id,
                    ]);

                    $data_email["email"] = $user->email;
                    $data_email["title"] = "Terms And Conditions";
                    $time = DB::table('documents')->where('name','term of use')->select('updated_at')->first();
                    $data_email["revised"] = date('m-d-Y',strtotime($time->updated_at));
                    $pdf = app()->make(PDF::class);
                    $pdf = $pdf->loadView('terms.index', $data_email);
                    \Storage::disk('s3')->put('term_and_conditions/' . $user->name . '_term_and_conditions.pdf', $pdf->output());
                    DB::table('user_term_and_condition_status')->insert([
                        'term_and_condition_file' => 'term_and_conditions/' . $user->name . '_term_and_conditions.pdf',
                        'user_id' => $user->id,
                        'status' => 1,
                    ]);
                    try {
                        $adminUsers = DB::table('users')->where('user_type', 'admin')->get();
                        foreach ($adminUsers as $adminUser) {
                            $admin_data_email["email"] =  $adminUser->email;
                            $admin_data_email["title"] = "Terms And Conditions";
                            Mail::send('emails.termAndConditionDoctorEmail', $admin_data_email, function ($message1) use ($admin_data_email, $pdf) {
                                $message1->to($admin_data_email["email"])->subject($admin_data_email["title"])->attachData($pdf->output(), "TermsAndConditions.pdf");
                            });
                        }
                        Mail::send('emails.termAndConditionDoctorEmail', $data_email, function ($message) use ($data_email, $pdf) {
                            $message->to($data_email["email"])->subject($data_email["title"])->attachData($pdf->output(), "TermsAndConditions.pdf");
                        });
                    } catch (Exception $e) {
                        Log::info($e);
                    }
                    return $user;
                }
                else
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
                        'username' => $data['username'],
                        'country_id' => $data['country'],
                        'city_id' => $data['city'],
                        'state_id' => $data['state'],
                        'password' => Hash::make($data['password']),
                        'date_of_birth' => $newd_o_b,
                        'phone_number' => $data['phone_number'],
                        'office_address' => $data['address'],
                        'zip_code' => $data['zip_code'],
                        'gender' => $data['gender'],
                        'terms_and_cond' => $data['terms_and_cond'],
                        'timeZone' => $data['timezone'],
                    ]);
                    $x = rand(10e12, 10e16);
                    $hash_to_verify = base_convert($x, 10, 36);
                    $data1 = [
                        'hash' => $hash_to_verify,
                        'user_id' => $user->id,
                        'to_mail' => $user->email,
                    ];
                    try {
                        Mail::to($user->email)->send(new UserVerificationEmail($data1));
                    } catch (Exception $e) {
                        Log::error($e);
                    }
                    DB::table('users_email_verification')->insert([
                        'verification_hash_code' => $hash_to_verify,
                        'user_id' => $user->id,
                    ]);

                    $data_email["email"] = $user->email;
                    $data_email["title"] = "Terms And Conditions";
                    $time = DB::table('documents')->where('name','term of use')->select('updated_at')->first();
                    $data_email["revised"] = date('m-d-Y',strtotime($time->updated_at));
                    $pdf = app()->make(PDF::class);
                    $pdf = $pdf->loadView('terms.index', $data_email);
                    \Storage::disk('s3')->put('term_and_conditions/' . $user->name . '_term_and_conditions.pdf', $pdf->output());
                    DB::table('user_term_and_condition_status')->insert([
                        'term_and_condition_file' => 'term_and_conditions/' . $user->name . '_term_and_conditions.pdf',
                        'user_id' => $user->id,
                        'status' => 1,
                    ]);
                    try {
                        $adminUsers = DB::table('users')->where('user_type', 'admin')->get();
                        foreach ($adminUsers as $adminUser) {
                            $admin_data_email["email"] =  $adminUser->email;
                            $admin_data_email["title"] = "Terms And Conditions";
                            Mail::send('emails.termAndConditionDoctorEmail', $admin_data_email, function ($message1) use ($admin_data_email, $pdf) {
                                $message1->to($admin_data_email["email"])->subject($admin_data_email["title"])->attachData($pdf->output(), "TermsAndConditions.pdf");
                            });
                        }
                        Mail::send('emails.termAndConditionDoctorEmail', $data_email, function ($message) use ($data_email, $pdf) {
                            $message->to($data_email["email"])->subject($data_email["title"])->attachData($pdf->output(), "TermsAndConditions.pdf");
                        });
                    } catch (Exception $e) {
                        Log::info($e);
                    }
                    return $user;
                }
            }
            //doctor registration
            else {
                $datecheck = $data['date_of_birth'];
                // dd($datecheck);
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
                    $frontimageName = '';
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
                    $backimageName = '';
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
                    'username' => $data['username'],
                    'country_id' => $data['country'],
                    'city_id' => $data['city'],
                    'state_id' => $data['state'],
                    'password' => Hash::make($data['password']),
                    'date_of_birth' => $newd_o_b,
                    'phone_number' => $data['phone_number'],
                    'office_address' => $data['address'],
                    'zip_code' => $data['zip_code'],
                    'nip_number' => $data['npi'],
                    'upin' => $data['upin'],
                    'specialization' => $data['specializations'],
                    'gender' => $data['gender'],
                    'id_card_front' => $frontimageName,
                    'id_card_back' => $backimageName,
                    'user_image' => $profile_pic,
                    'terms_and_cond' => $data['terms_and_cond'],
                    'signature' => $data['signature'],
                    'timeZone' => $data['timezone'],
                ]);
                foreach ($data['licensed_states'] as $li_state) {
                    DoctorLicense::create([
                        'doctor_id' => $user->id,
                        'state_id' => $li_state
                    ]);
                }
                // dd($user);
                $x = rand(10e12, 10e16);
                $hash_to_verify = base_convert($x, 10, 36);
                $data1 = [
                    'hash' => $hash_to_verify,
                    'user_id' => $user->id,
                    'to_mail' => $user->email,
                ];
                try {
                    Mail::to($user->email)->send(new UserVerificationEmail($data1));
                } catch (Exception $e) {
                    Log::error($e);
                }
                DB::table('users_email_verification')->insert([
                    'verification_hash_code' => $hash_to_verify,
                    'user_id' => $user->id,
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
