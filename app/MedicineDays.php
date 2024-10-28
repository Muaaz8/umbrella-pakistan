<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedicineDays extends Model
{
    protected $table = 'medicine_days';

    protected $fillable = [
        'days'
    ];
}
