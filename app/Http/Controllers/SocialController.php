<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Validator,Redirect,Response,File;
use Socialite;
use App\User;

class SocialController extends Controller
{
  public function redirect($provider)
 {
   return Socialite::driver($provider)->redirect();
 }
 public function callback($provider)
 {
   $getInfo = Socialite::driver($provider)->user(); 
  //  dd($getInfo );
   $user = $this->createUser($getInfo,$provider); 
   auth()->login($user); 
   return redirect()->to('/home');
 }
 function createUser($getInfo,$provider){
  //  dd($getInfo);
  $digits = 5;
  $rand= str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);
  $uid='UMS'.$rand;
        
 $user = User::where('provider_id', $getInfo->id)->first();
 if (!$user) {
    //   $validateData=$getInfo->validate([
    //     'email' =>  ['required', 'email','unique:users'],            
    // ]);

      $user = User::create([
         'name'     => $getInfo->name,
         'email'    => $getInfo->email,
         'provider' => $provider,
         'provider_id' => $getInfo->id,
         'user_image'=>$getInfo->avatar,
         'username'=>$uid,
         'user_type'=>'patient'
     ]);
   }
   return $user;
 }
}