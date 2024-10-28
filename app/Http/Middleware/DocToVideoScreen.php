<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use App\Session;
use DB;
use Illuminate\Support\Facades\Auth;

class DocToVideoScreen extends BaseController
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
        if(Auth::check())
        {
            $user=auth()->user();
            if($user->user_type == 'doctor'){
                $session_check = DB::table('sessions')
                    ->join('users','users.id','sessions.doctor_id')
                    ->where('sessions.patient_id',$user->id)
                    ->where('sessions.status','started')->first();
                if($session_check!=null){
                    $sessionDetails['code'] = 200;
                    $sessionDetails['doctor_name'] = $session_check->name.' '.$session_check->last_name;
                    $sessionDetails['session_id'] = $session_check->id;
                    return $this->sendResponse($sessionDetails,'session available');
                }
                else{
                    $session_check = DB::table('sessions')
                        ->join('users','users.id','sessions.doctor_id')
                        ->where('sessions.patient_id',$user->id)
                        ->where('sessions.status','doctor joined')->first();
                    if($session_check!=null){
                        $sessionDetails['code'] = 200;
                        $sessionDetails['doctor_name'] = $session_check->name.' '.$session_check->last_name;
                        $sessionDetails['session_id'] = $session_check->id;
                        return $this->sendResponse($sessionDetails,'doctor queuee');
                    }
                    else{
                        return $next($request);
                    }
                }
            }
        }
        return $next($request);
    }
}
