<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $fillable=[
        'id',
        'patient_id',
        'doctor_id',
        'appointment_id',
        'date',
        'status',
        'symptom_id',
        'queue',
        'remaining_time',
        'sequence',
        'less_min_flag',
        'invite_time',
        'response_time',
        'created_at',
        'updated_at',
        'que_message',
        'channel',
        'price',
        'specialization_id',
        'session_id',
        'isabel_diagnosis_id',
        'location_id',
        'validation_status'
    ];
    public function refillRequest()
    {
        return $this->hasMany(RefillRequest::class);
    }
}
