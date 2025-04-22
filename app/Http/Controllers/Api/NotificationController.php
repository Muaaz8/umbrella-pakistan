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
}
