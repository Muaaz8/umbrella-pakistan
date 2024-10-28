<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\User;

class MydoctorController extends BaseController
{
    public function mydoctor(){
        $user = Auth()->user();
        $doctors = DB::table('sessions')
        ->join('users', 'users.id', 'doctor_id')
        ->join('specializations', 'sessions.specialization_id', 'specializations.id')
        ->where('patient_id',$user->id)
        ->where('sessions.status', '!=', 'pending')
        ->groupBy('doctor_id')
        ->select('users.*','specializations.name as sp_name')
        ->get();
        foreach($doctors as $doctor){
            $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);
        }
        if($doctors != '[]'){
            $mydoc['code'] = 200;
            $mydoc['doctors'] = $doctors;
            return $this->sendResponse($mydoc,"My Doctors Found!");
        } else{
            $mydoc['code'] = 200;
            return $this->sendError($mydoc,"No Doctor Found");
        }
    }
    public function view_docProfile($id){
        $doc = DB::table('users')->where('user_type','doctor')->where('id',$id)->first();
        $doc_sp = $doc->specialization;
        $specialization = DB::table('specializations')->where('id',$doc_sp)->first();
        $docData['code'] =200;
        $docData['doc'] =$doc;
        $docData['specialization_name'] =$specialization->name;
        return $this->sendResponse($docData,'doctor profile');
    }
    public function search_mydoctor(Request $request){
        $user = Auth()->user();
        $doctors = DB::table('sessions')
        ->join('users', 'users.id', 'doctor_id')
        ->join('specializations', 'sessions.specialization_id', 'specializations.id')
        ->where('patient_id',$user->id)
        ->where('users.name','LIKE','%'.$request->doctor_name.'%')
        ->orWhere('users.last_name','LIKE','%'.$request->doctor_name.'%')
        ->where('sessions.status', '!=', 'pending')
        ->groupBy('doctor_id')
        ->select('users.*','specializations.name as sp_name')
        ->get();
        foreach($doctors as $doctor){
            $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);
        }
        if($doctors != '[]'){
            $mydoc['code'] = 200;
            $mydoc['doctors'] = $doctors;
            return $this->sendResponse($mydoc,"My Doctors Found!");
        } else{
            $mydoc['code'] = 200;
            return $this->sendError($mydoc,"No Doctor Found");
        }
   }
}
