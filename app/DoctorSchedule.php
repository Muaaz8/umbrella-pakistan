<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DoctorSchedule extends Model
{
    protected $fillable = [
        'title',
        'color',
        'start',
        'end',
        'doctorID',
        'slotStartTime',
        'slotEndTime',
        'date',       
    ];
}
