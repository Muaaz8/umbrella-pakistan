<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Notification;
use DB;
use Illuminate\Support\Facades\Auth;

class NotificationController extends BaseController
{
    public function getUserNotification(){
        if (Auth::user()) {
            $countNote = Notification::where('user_id', Auth::user()->id)->where('status', 'new')->orderby('id', 'desc')->count();
            return response()->json($countNote);
        }
    }
    public function GetUnreadNotifications()
    {
        $user_id = auth()->user()->id;
        if($user_id!=null){
            $data = DB::table('notifications')->where('user_id',$user_id)->where('status','new')->get();
            return $this->sendResponse($data, 'Notifications retrieved successfully.');
        }
    }

    public function ReadNotification($id)
    {
        $notId=$id;
        $id=auth()->user()->id;
        DB::table('notifications')->where('user_id',$id)->where('id',$notId)->update(['status' => 'old']);
        $notifs=Notification::where('id',$notId)->get();
        foreach($notifs as $note)
        {
            $type=$note['type'];
            return $this->sendResponse(['notes'=>$note , 'type'=> $type], 'Notification retrieved successfully.');
        }
    }

    public function ReadAllNotification()
    {
        $user_id = Auth()->user()->id;
        $user = auth()->user();
        if($user_id!=null){
            DB::table('notifications')->where('user_id',$user_id)->update(['status' => 'old']);
            return $this->sendResponse([], 'All notifications marked as read successfully.');

        }
    }

}
