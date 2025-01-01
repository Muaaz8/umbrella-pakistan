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
        'doctor_note',
        'reason',
        'status'
    ];

    public function user(){
        return $this->belongsTo(\App\User::class);
    }

    public function prescriptions(){
        return $this->hasMany(\App\Prescription::class, 'parent_id', 'id');
    }

}
