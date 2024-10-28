<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnsureUserEmailVerify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        $user_email_verify_status=DB::table('users_email_verification')->where('user_id',auth()->user()->id)->first();
        if($user_email_verify_status!=null)
        {
            if($user_email_verify_status->status==0)
            {
                if(auth()->user()->user_type == 'doctor'){
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
                    }return response()->view('dashboard_doctor.EmailVerify',compact('user'));
                }
                else{
                    return response()->view('website_pages.email_verify_new');
                }

            }else{
                return $next($request);
            }
        }else{
            if(auth()->user()->user_type == 'doctor'){
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
            else{
                return response()->view('website_pages.email_verify_new');
            }
        }


    }
}
