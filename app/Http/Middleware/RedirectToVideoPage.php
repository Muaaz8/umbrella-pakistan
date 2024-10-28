<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Session;
use Illuminate\Support\Facades\Auth;

class RedirectToVideoPage
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
            if($user->user_type == 'doctor')
            {
                $session_check = Session::where('doctor_id',$user->id)->where('status','started')->first();
                if($session_check!=null){
                    return redirect()->route('doc_video_page', ['id' => \Crypt::encrypt($session_check->id)]);
                    //return redirect()->route('doctor.video.session', ['id' => $session_check->id]);
                }
                else{
                    $session_check = Session::where('doctor_id',$user->id)->where('status','doctor joined')->first();
                    if($session_check!=null){
                        //return redirect()->route('doc_video_page', ['id' => \Crypt::encrypt($session_check->id)]);
                        return redirect()->route('doctor_queue');
                        //return redirect()->route('doctor.video.session', ['id' => $session_check->id]);
                    }
                    else{
                        return $next($request);
                    }
                }
            }
            else if($user->user_type == 'patient')
            {
                $session_check = Session::where('patient_id',$user->id)->where('status','started')->first();
                if($session_check!=null){
                    return redirect()->route('pat_video_page', ['id' => \Crypt::encrypt($session_check->id)]);
                    //return redirect()->route('patient.video.session', ['id' => $session_check->id]);
                }
                else{
                    //return $next($request);
                    $session_check = Session::where('patient_id',$user->id)->where('status','doctor joined')->first();
                    if($session_check!=null){
                        return redirect()->route('waiting_room_pat', ['id' => \Crypt::encrypt($session_check->id)]);
                        //return redirect()->route('patient.video.session', ['id' => $session_check->id]);
                    }
                    else
                    {
                        $session_check = Session::where('patient_id',$user->id)->where('status','invitation sent')->first();
                        if($session_check!=null)
                        {
                            return redirect()->route('waiting_room_pat', ['id' => \Crypt::encrypt($session_check->id)]);
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
