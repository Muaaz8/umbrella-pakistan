<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DoctorLicense extends Model
{
    protected $fillable=['doctor_id','state_id','is_verified'];
}
