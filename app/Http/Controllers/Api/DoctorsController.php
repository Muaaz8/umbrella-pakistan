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
                'users.zip_code',
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
    
    public function getSpeciallization(){
        $specialization = DB::table('specializations')
        ->join('users', 'users.specialization', 'specializations.id')
        ->groupBy('specializations.id')
        ->select('specializations.*',)
        ->get();

        return response()->json($specialization);
    }

    public function getDoctorsBySpeciallization($id){
        $doctors = DB::table('users')
        ->join('specializations', 'specializations.id', 'users.specialization')
        ->where('users.specialization', $id)
        ->where('users.active', '1')
        ->select(
        'users.name',
        'users.last_name',
        'users.id',
        'users.rating',
        'users.zip_code',
        'users.status',
        'users.consultation_fee',
        'users.followup_fee',
        'users.user_image', 
        'specializations.name as sp_name',
        'specializations.id as sp_id'
        )
        ->paginate(10);
    foreach ($doctors as $doctor) {
        $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);
    }
    return response()->json($doctors);
    }
    public function getOnlineDoctorsBySpeciallization($id){
        $doctors = DB::table('users')
        ->join('specializations', 'specializations.id', 'users.specialization')
        ->where('users.specialization', $id)
        ->where('users.status', 'online')
        ->where('users.active', '1')
        ->select(
        'users.name',
        'users.last_name',
        'users.id',
        'users.rating',
        'users.zip_code',
        'users.status',
        'users.consultation_fee',
        'users.followup_fee',
        'users.user_image', 
        'specializations.name as sp_name',
        'specializations.id as sp_id'
        )
        ->paginate(10);
    foreach ($doctors as $doctor) {
        $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);
    }
    return response()->json($doctors);
    }

    public function getOnlineDoctors(){
        $doctors = DB::table('users')
        ->join('specializations', 'specializations.id', 'users.specialization')
        ->where('users.status', 'online')
        ->where('users.active', '1')
        ->select(
            'users.name',
            'users.last_name',
            'users.id',
            'users.rating',
            'users.status',
            'users.zip_code',
            'users.consultation_fee',
            'users.followup_fee',
            'users.user_image', 
            'specializations.name as sp_name',
            'specializations.id as sp_id')
        ->paginate(10);

        foreach ($doctors as $doctor) {
            $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);
        }

        return response()->json($doctors);
    }

}
