<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmailVerificationController extends Controller
{
    public function email_verification($user_id,$hash)
    {

        $user=DB::table('users_email_verification')->where('user_id',$user_id)->first();
        // dd($user);
        if($hash==$user->verification_hash_code)
        {
            DB::table('users_email_verification')->where('user_id',$user_id)->update(['status'=>'1']);
            return view('email_verified_message');
        }
        else{
            return view('website_pages.email_verify_new');
        }
    }
}
