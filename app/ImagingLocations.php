<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImagingLocations extends Model
{
    protected $fillable = [
        'city',
        'zip_code',
        'clinic_name',
        'lat',
        'long',
        'address',
        'created_by',
        'created_at',
        'updated_at'
    ];
  
}
