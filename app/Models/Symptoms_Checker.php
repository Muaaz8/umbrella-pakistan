<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Symptoms_Checker extends Model
{
    use HasFactory;
    protected $table = 'symptom_checker';
    protected $guarded = ['id','created_at','updated_at'];
}
