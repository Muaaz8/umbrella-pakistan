<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
            'patient_id',
            'doctor_id',
            'patient_name',
            'doctor_name',
            'email',
            'phone',
            'date',
            'day',
            'time',
            'status',
            'created_at',
            'updated_at',
            'problem',
            'reminder_one',
            'reminder_two',
            'reminder_two_status',
            'reminder_one_status',
            'appointment_id',

    ];
}
