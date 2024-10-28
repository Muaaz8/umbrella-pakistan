<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class ErrorController extends Controller
{
    /*
        code 101:: not available in your state error
    */
    public function errorPage($code)
    {
        if($code=='101'){
            $type = Auth::user()->user_type;
            if($type == 'patient'){
                return view('errors.101');
            }else{
                return view('errors.101D');
            }
        }
    }
}
