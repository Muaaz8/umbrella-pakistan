<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Symptom extends Model
{
    protected $fillable=[
    	'patient_id',
    	'doctor_id',
    	'headache',
    	'flu',
    	'fever',
    	'nausea',
    	'others',
		'description',
		'status',
        'symptoms_text'
    ];
}
