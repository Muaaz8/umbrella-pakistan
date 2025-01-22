<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Session;

class EmailVerificationController extends Controller
{
    public function email_verification($user_id, $hash)
    {

        $user = DB::table('users_email_verification')->where('user_id', $user_id)->first();
        if ($hash == $user->verification_hash_code || $hash == $user->otp) {
            DB::table('users_email_verification')->where('user_id', $user_id)->update(['status' => '1']);
            return view('email_verified_message');
        } else {
            return view('website_pages.email_verify_new');
        }
    }

    public function otp_verification()
    {
        $user_id = request()->user_id;
        $otp = request()->otp;


        $user = DB::table('users_email_verification')->where('user_id', $user_id)->first();
        if ($otp == $user->otp) {
            DB::table('users_email_verification')->where('user_id', $user_id)->update(['status' => '1']);
            return redirect()->route('home');
        } else {
            Session::flash('error', 'Invalid OTP');
            if (request()->user_type == "patient") {
                return view('website_pages.email_verify_new');
            } else {
                return redirect()->route('home');
            }
        }
    }
}
