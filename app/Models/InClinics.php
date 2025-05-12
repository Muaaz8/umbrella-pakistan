<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class InClinics extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'doctor_id',
        'doctor_note',
        'reason',
        'status',
        'follow_up'
    ];

    public function user(){
        return $this->belongsTo(\App\User::class);
    }

    public function doctor(){
        return $this->belongsTo(\App\User::class, 'doctor_id', 'id');
    }

    public function prescriptions(){
        return $this->hasMany(\App\Prescription::class, 'parent_id', 'id');
    }

    public function med_profile(){
        return $this->belongsTo(\App\MedicalProfile::class, 'user_id', 'user_id');
    }

}
