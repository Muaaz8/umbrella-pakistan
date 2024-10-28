<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedicineUOM extends Model
{
    protected $table = 'medicine_units';

    protected $fillable = [
        'unit',
        'status'
    ];
}
