<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use App\User;
class UserTimeZoneController extends BaseController
{
    public function timezone(Request $request){
        $user_id = $request->id;
        $user_timezone =$request->timezone;

        $user = User::find($user_id);
        $user->update([
             'timeZone'  => $user_timezone
        ]);
        $timezone['code'] = 200;
        $timezone['user'] = $user;
        return $this->sendResponse($timezone,"TimeZone Updated!");
    } //Asia/Karachi
}
