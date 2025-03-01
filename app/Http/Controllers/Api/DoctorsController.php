<?php

namespace App\Http\Controllers\Api;
use DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DoctorsController extends Controller
{
    public function index()
    {
        $doctors = DB::table('users')
            ->select(
                'users.name',
                'users.last_name',
                'users.id',
                'users.rating',
                'users.gender',
                'users.consultation_fee',
                'users.followup_fee',
                'users.user_image',
                'users.status',
                'users.specialization'
            )
            ->where('user_type', 'doctor')
            ->where('active', '1')
            ->where('status', '!=', 'ban')
            ->orderBy('id', 'desc')
            ->paginate(8);
    
        $doctors->getCollection()->transform(function($doctor) {

            $doctorDetails = DB::table('doctor_details')->where('doctor_id', $doctor->id)->first();
            $specialization = DB::table('specializations')->where('id', $doctor->specialization)->first();
            $doctor->details = $doctorDetails;
            $doctor->specializations = $specialization;
            $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);
    
            return $doctor;
        });
    
        return response()->json($doctors);
    }
    
    public function singleDoctor($id)
    {
        $doctor = DB::table('users')
            ->select(
                'users.name',
                'users.last_name',
                'users.id',
                'users.rating',
                'users.gender',
                'users.consultation_fee',
                'users.followup_fee',
                'users.user_image',
                'users.status',
                'users.specialization'
            )
            ->where('user_type', 'doctor')
            ->where('active', '1')
            ->where('status', '!=', 'ban')
            ->where('id', $id)
            ->first();

        if ($doctor) {
            $doctorDetails = DB::table('doctor_details')->where('doctor_id', $doctor->id)->first();
            $specialization = DB::table('specializations')->where('id', $doctor->specialization)->first();
            $doctor->details = $doctorDetails;
            $doctor->specializations = $specialization;
            $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);
        }
    
        return response()->json($doctor);
    }
    
}
