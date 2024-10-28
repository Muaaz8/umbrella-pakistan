<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Referal extends Model
{
    protected $fillable=['session_id','doctor_id','sp_doctor_id','patient_id','comment','status'];
}
