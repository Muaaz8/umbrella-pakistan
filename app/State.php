<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $table='states';
    public $timestamps = false;
    protected $fillable=['name','country_code','country_id','state_code','active'];
}
