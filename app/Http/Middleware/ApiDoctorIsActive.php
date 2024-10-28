<?php

namespace App\Http\Middleware;
use App\Http\Controllers\Api\BaseController;
use Closure;
use Illuminate\Http\Request;
use DB;
use App\User;

class ApiDoctorIsActive extends BaseController
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
        $user=auth()->user();
        if($user->user_type=='doctor'){
            if($user->active=='1'){
                $date = User::convert_utc_to_user_timezone($user->id,date('Y-m-d H:i:s'))['datetime'];
                $date = explode(" ",$date)[0];
                $contract = DB::table('contracts')->where('provider_id',$user->id)->first();
                if($date >= $contract->date){
                    $timestamp = date("d-m-Y h:i:s a");
                    $date = \Carbon\Carbon::createFromFormat('d-m-Y h:i:s a',$timestamp,'UTC')->setTimezone('UTC');
                    $user->last_activity=$date;
                    $user->save();
                    return $next($request);
                } else {
                    $user = DB::table('users')
                    ->join('users_email_verification','users.id','users_email_verification.user_id')
                    ->where('users.id',auth()->user()->id)
                    ->select('users.*','users_email_verification.status as email_status')
                    ->first();
                    if ($user->id_card_front == '' && $user->id_card_back == '') {
                        $id_card_status = 0;
                    }elseif($user->id_card_front == '' || $user->id_card_back == ''){
                        $id_card_status = 0;
                    }else{
                        $id_card_status = 1;
                    }
                    $user->contract_date = DB::table('contracts')->where('provider_id',Auth()->user()->id)->orderby('id','desc')->first();
                    if(isset($user->contract_date)){
                        $user->contract_date->date = date('m-d-Y', strtotime($user->contract_date->date));
                        $contract = 1;
                    } else{
                        $contract = 0;
                    }
                }
            } elseif($user->active=='0' && $user->status=='ban'){
                $doctor_status = 0;
            } else{
                $user = DB::table('users')
                ->join('users_email_verification','users.id','users_email_verification.user_id')
                ->where('users.id',auth()->user()->id)
                ->select('users.*','users_email_verification.status as email_status')
                ->first();
                if(isset($user->email_status)){
                    $email_verification_status = $user->email_status;
                }
                if ($user->id_card_front == '' && $user->id_card_back == '') {
                    $id_card_status = 0;
                }elseif($user->id_card_front == '' || $user->id_card_back == ''){
                    $id_card_status = 0;
                } else{
                    $id_card_status = 1;
                }
                if($user->id_card_front !=''){
                    $id_card_status = 1;
                }
                $user->contract_date = DB::table('contracts')->where('provider_id',Auth()->user()->id)->orderby('id','desc')->first();
                if(isset($user->contract_date)){
                    $user->contract_date->date = date('m-d-Y', strtotime($user->contract_date->date));
                    $contract = 1;
                } else{
                   $contract = 0;
                }
                if($email_verification_status ==1 && $id_card_status == 1 && $contract ==1){
                    DB::table('users')->where('id',$user->id)->update(['active'=>1]);
                    return $next($request);
                } else{
                    $userData['code'] = 200;
                    $userData['email_verification_status'] = $email_verification_status;
                    $userData['id_card_status'] = $id_card_status;
                    $userData['contract'] = $contract;
                    return $this->sendResponse($userData,'doctor info');
                }
            }
        } else{
            return $next($request);
        }
    }
}
