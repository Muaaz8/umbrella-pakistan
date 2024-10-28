<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\State;
use App\City;

class PhysicalLocations extends Model
{
    use HasFactory;
    protected $table = 'physical_locations';
    protected $fillable = [
        'name',
        'zipcode',
        'state_id',
        'city_id',
        'status',
        'latitude',
        'longitude',
        'phone_number',
        'services',
        'time_from',
        'time_to'
    ];

    public function states(){
        return $this->belongsTo(State::class,'state_id','id');
    }

    public function cities(){
        return $this->belongsTo(City::class,'city_id','id');
    }
}
