<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VideoLinks extends Model
{
    protected $fillable=[
        'session_id',
        'patient_link',
        'doctor_link',
        'room_id'
    ];
}
