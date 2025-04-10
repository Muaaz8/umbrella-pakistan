<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getUserNotification(){
        if (Auth::user()) {
            $countNote = Notification::where('user_id', Auth::user()->id)->where('status', 'new')->orderby('id', 'desc')->count();
            return response()->json($countNote);
        }
    }
}
