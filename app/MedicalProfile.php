<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedicalProfile extends Model
{
    protected $fillable=[
        'user_id','allergies','previous_symp','immunization_history','family_history','comment','surgeries','medication'
    ];
}
