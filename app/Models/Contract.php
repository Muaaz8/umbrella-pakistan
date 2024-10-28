<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;
    
    protected $fillable=[
        'slug',
        'provider_id',
        'provider_name',
        'provider_address',
        'provider_email_address',
        'provider_speciality',
        'date',
        'session_percentage',
        'signature',
        'status'
    ];
}
