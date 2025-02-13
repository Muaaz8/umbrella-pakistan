<?php

namespace App\Http\Controllers\Auth;

use App\ActivityLog;
use App\Session;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Events\loadOnlineDoctor;
use Illuminate\Validation\ValidationException;
use App\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
     */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */

    // protected $redirectTo = RouteServiceProvider::HOME;

    protected $username;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->username = $this->findUsername1();
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function findUsername1()
    {
        $login = request()->input('login');
        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_number';

        request()->merge([$fieldType => $login]);

        return $fieldType;
    }

    /**
     * Get username property.
     *
     * @return string
     */
    // public function username()
    // {
    //     return $this->username;
    // }
    public function logout(Request $request)
    {
        $user = auth()->user();
        $type = $user->user_type;
        if ($type == 'doctor') {
            if($user->status != 'ban'){
                $user->status = 'offline';
                event(new loadOnlineDoctor('run'));
            }
            $user->save();

            ActivityLog::create([
                'activity' => 'logged out',
                'type' => 'logout',
                'user_id' => $user->id,
                'user_type' => 'doctor',
            ]);
            Session::where('doctor_id', $user->id)->where('remaining_time','!=','full')->update(['status' => 'ended', 'queue' => 0]);
            Session::where('doctor_id', $user->id)->where('status','invitation sent')->orwhere('status','doctor joined')->update(['status' => 'paid']);
            $data = User::where('id',$user->id)->select('id','status')->first();
            if($data->status == "online"){
                $data->status = "offline";
            }else{
                $data->status = "online";
            }
            $data->save();
        }
        if ($type == 'patient') {
            ActivityLog::create([
                'activity' => 'logged out',
                'type' => 'logout',
                'user_id' => $user->id,
                'user_type' => 'patient',
            ]);
            Session::where('patient_id', $user->id)->where('remaining_time','!=','full')->update(['status' => 'ended', 'queue' => 0]);
        }
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/');
    }
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();
        $this->clearLoginAttempts($request);
        $user = auth()->user();

        //$information = geoip($_SERVER['REMOTE_ADDR']);
        //$data = $information->toArray();
        //$timeZone=$data['timezone'];
        $timeZone="Asia/Karachi";

        \DB::table('users')->where('id',$user->id)->update(['timeZone'=>$timeZone]);



        if ($user->user_type == 'doctor') {
            ActivityLog::create([
                'activity' => 'logged in',
                'type' => 'login',
                'user_id' => $user->id,
                'user_type' => 'doctor',
            ]);
            Session::where('doctor_id', $user->id)->where('remaining_time','!=','full')->update(['status' => 'ended', 'queue' => 0]);
        }
        if ($user->user_type == 'patient') {

            ActivityLog::create([
                'activity' => 'logged in',
                'type' => 'login',
                'user_id' => $user->id,
                'user_type' => 'patient',
            ]);
            Session::where('patient_id', $user->id)->where('remaining_time','!=','full')->update(['status' => 'ended', 'queue' => 0]);

        }
        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        $url = session()->get('url');

        if ($url == '/pharmacy' || '/about_us' || '/labtests' || '/imaging' || '/pain-management' || '/substance-abuse/first-visit' || '/e-visit' || '/our_doctors' || '/contact_us') {
            return $request->wantsJson()
                ? new JsonResponse([], 204)
                : redirect()->intended($url);
        } else {
            return $request->wantsJson()
                ? new JsonResponse([], 204)
                : redirect()->intended($this->redirectPath());
        }
    }
    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function findUsername()
    {
        $login = request()->input('login');

        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        request()->merge([$fieldType => $login]);

        return $fieldType;
    }

    /**
     * Get username property.
     *
     * @return string
     */
    public function username()
    {
        return $this->username;
    }
}
