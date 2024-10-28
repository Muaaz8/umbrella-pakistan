<?php

namespace App\Http\Middleware;

use Closure;
use DB;
use App\User;

class IsDoctorActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user=auth()->user();
        if($user->user_type=='doctor'){
            if($user->active=='1'){
                $date = User::convert_utc_to_user_timezone($user->id,date('Y-m-d H:i:s'))['datetime'];
                $date = explode(" ",$date)[0];
                $contract = DB::table('contracts')->where('provider_id',$user->id)->first();
                if($date >= $contract->date)
                {
                    $timestamp = date("d-m-Y h:i:s a");
                    $date = \Carbon\Carbon::createFromFormat('d-m-Y h:i:s a',$timestamp,'UTC')->setTimezone('UTC');
                    $user->last_activity=$date;
                    $user->save();
                    return $next($request);
                }
                else
                {
                    $user = DB::table('users')
                    ->join('users_email_verification','users.id','users_email_verification.user_id')
                    ->where('users.id',auth()->user()->id)
                    ->select('users.*','users_email_verification.status as email_status')
                    ->first();
                    if ($user->id_card_front == '' && $user->id_card_back == '') {
                        $user->card_status = 0;
                    }elseif($user->id_card_front == '' || $user->id_card_back == ''){
                        $user->card_status = 0;
                    }else{
                        $user->card_status = 1;
                    }
                    $user->contract_date = DB::table('contracts')->where('provider_id',Auth()->user()->id)->orderby('id','desc')->first();
                    // $user->contract_date = DB::table('contracts')->where('provider_id',Auth()->user()->id)->first();
                    if(isset($user->contract_date)){
                        $user->contract_date->date = date('m-d-Y', strtotime($user->contract_date->date));
                    }
                    return response()->view('dashboard_doctor.EmailVerify',compact('user'));
                }

            }elseif($user->active=='0' && $user->status=='ban'){
                return response()->view('dashboard_doctor.blocked_doctor');
                //return response()->view('errors.inactiveUser');
            }else{
                $user = DB::table('users')
                ->join('users_email_verification','users.id','users_email_verification.user_id')
                ->where('users.id',auth()->user()->id)
                ->select('users.*','users_email_verification.status as email_status')
                ->first();
                if ($user->id_card_front == '' && $user->id_card_back == '') {
                    $user->card_status = 0;
                }elseif($user->id_card_front == '' || $user->id_card_back == ''){
                    $user->card_status = 0;
                }else{
                    $user->card_status = 1;
                }
                $user->contract_date = DB::table('contracts')->where('provider_id',Auth()->user()->id)->orderby('id','desc')->first();
                // $user->contract_date = DB::table('contracts')->where('provider_id',Auth()->user()->id)->first();
                if(isset($user->contract_date)){
                    $user->contract_date->date = date('m-d-Y', strtotime($user->contract_date->date));
                }
                return response()->view('dashboard_doctor.EmailVerify',compact('user'));
                //return response()->view('errors.inactiveUser');
            }
        }else{
            return $next($request);
        }
    }
}
