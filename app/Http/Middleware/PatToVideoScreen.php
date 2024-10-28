<?php

namespace App\Http\Middleware;
use App\Http\Controllers\Api\BaseController;
use Closure;
use Illuminate\Http\Request;
use App\Session;
use DB;
use Illuminate\Support\Facades\Auth;

class PatToVideoScreen extends BaseController
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
        if(Auth::check()){
            $user=auth()->user();
            if($user->user_type == 'patient'){
                $session_check = DB::table('sessions')
                ->join('users','users.id','sessions.doctor_id')
                ->where('sessions.patient_id',$user->id)
                ->where('sessions.status','started')
                ->select('sessions.*','users.id as patient_id','users.name as doctor_name','users.last_name as doctor_last_name')
                ->first();
                if($session_check!=null){
                    $sessionDetails['code'] = 200;
                    $sessionDetails['doctor_name'] = $session_check->doctor_name.' '.$session_check->doctor_last_name;
                    $sessionDetails['session_id'] = $session_check->id;
                    return $this->sendResponse($sessionDetails,'session available');
                }
                else{
                    $session_check = DB::table('sessions')
                        ->join('users','users.id','sessions.doctor_id')
                        ->where('sessions.patient_id',$user->id)
                        ->where('sessions.status','doctor joined')
                        ->select('sessions.*','users.id as patient_id','users.name as doctor_name','users.last_name as doctor_last_name')
                        ->first();
                    if($session_check != null){
                        $sessionDetails['code'] = 200;
                        $sessionDetails['doctor_name'] = $session_check->doctor_name.' '.$session_check->doctor_last_name;
                        $sessionDetails['session_id'] = $session_check->id;
                        return $this->sendResponse($sessionDetails,'session available, redirect to waiting room');
                    }
                    else {
                        $session_check = DB::table('sessions')
                            ->join('users','users.id','sessions.doctor_id')
                            ->where('sessions.patient_id',$user->id)
                            ->where('sessions.status','invitation sent')
                            ->select('sessions.*','users.id as patient_id','users.name as doctor_name','users.last_name as doctor_last_name')
                            ->first();
                        if($session_check!=null){
                            $sessionDetails['code'] = 200;
                            $sessionDetails['doctor_name'] = $session_check->doctor_name.' '.$session_check->doctor_last_name;
                            $sessionDetails['session_id'] = $session_check->id;
                            return $this->sendResponse($sessionDetails,'invitation sent to doctor');
                        }
                        else{
                            return $next($request);
                        }
                    }
                }
            }
        }

        return $next($request);
    }
}
