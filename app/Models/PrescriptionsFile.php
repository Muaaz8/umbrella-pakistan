<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrescriptionsFile extends Model
{
    use HasFactory;
    public $fillable = [
        'session_id',
        'doctor_id',
        'patient_id',
        'order_id',
        'filename',
        'status',
        'response'
    ];
}
