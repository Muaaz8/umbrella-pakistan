<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController;
use App\Notification;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Dotenv\Result\Success;
use App\Events\RealTimeMessage;

class NotificationController extends BaseController
{
    public function toaster(Request $request)
    {
        $toastShow=Notification::where('user_id',$request->user_id)->where('status','new')->where('toast',0)->orderby('id','desc')->get();
        Notification::where('user_id',$request->user_id)->update(['toast'=>1]);
        $notification['code'] = 200;
        $notification['code'] = $toastShow;
        return $this->sendResponse($notification,"toster");
    }
    public function all_notifictions(){
        $id= auth()->user()->id;
        $notifications=Notification::where('user_id',$id)->latest()->orderBy('status','asc')->orderBy('id','ASC')->get();
        $noti['code'] = 200;
        $noti['notifications'] = $notifications;
        return $this->sendResponse($noti,"All Notifications");
    }
    public function read_all_notification(){
        $user_id = Auth()->user()->id;
        if($user_id!=null){
            DB::table('notifications')->where('user_id',$user_id)->update(['status' => 'old']);
        //    $firebase_noti= DB::table('notifications')->where('user_id',$user_id)->get();
        //     try {
        //         \App\Helper::firebase($user_id,'notification',$firebase_noti->id,$firebase_noti);
        //     } catch (\Throwable $th) {
        //         //throw $th;
        //     }
            event(new RealTimeMessage($user_id));
            $noti['code'] = 200;
            return $this->sendResponse($noti,"all notification readed successfully");
        }
    }
    public function view_unread_notifictions(){
        $id= auth()->user()->id;
        $notifications=Notification::where('user_id',$id)->where('status','new')->orderBy('status','asc')->orderBy('id','ASC')->get();
        $noti['code'] = 200;
        $noti['notifications'] = $notifications;
        return $this->sendResponse($noti,"All unread notifications");
    }
    public function read_notification(Request $request){
        $notId=$request->id;
        $id=auth()->user()->id;
        DB::table('notifications')->where('user_id',$id)->where('id',$notId)->update(['status' => 'old']);
        $notifs=Notification::where('id',$notId)->get();
        $url = [];
        foreach($notifs as $note)
        {
            $type=$note['type'];
            $url = $type;
        }
        $notification['code'] = 200;
        $notification['url'] = url($url);
        return $this->sendResponse($notification,"notification url");
    }
    public function remove_reminder($id){
        $session = DB::table('sessions')->where('id',$id)->first();
        if($session->status == 'pending'){
            DB::table('sessions')->where('id',$id)->update([
                'reminder' => 0,
            ]);
            $reminderInfo['code'] = 200;
            $reminderInfo['session_id'] = $session->id;
            $reminderInfo['redirect_to'] = 'patient payment screen';
            return $this->sendResponse($reminderInfo,$reminderInfo['redirect_to']);
        }
        elseif($session->status == 'paid' || $session->status == 'invitation sent')
        {
            DB::table('sessions')->where('id',$id)->update([
                'reminder' => 0,
            ]);
            $reminderInfo['code'] = 200;
            $reminderInfo['session_id'] = $session->id;
            $reminderInfo['redirect_to'] = 'waiting room';
            return $this->sendResponse($reminderInfo,$reminderInfo['redirect_to']);
        }
        elseif($session->status == 'cancel')
        {
            DB::table('sessions')->where('id',$id)->update([
                'reminder' => 0,
            ]);
            $reminderInfo['code'] = 200;
            $reminderInfo['session_id'] = '';
            $reminderInfo['redirect_to'] = 'back to same screen';
            return $this->sendResponse($reminderInfo,$reminderInfo['redirect_to']);
        }
        else{
            $reminderInfo['code'] = 200;
            $reminderInfo['session_id'] = '';
            $reminderInfo['redirect_to'] = 'back to same screen';
            return $this->sendResponse($reminderInfo,$reminderInfo['redirect_to']);
        }
    }
}
