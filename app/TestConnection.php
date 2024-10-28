<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestConnection extends Model
{
    protected $connection='suunnoo_db';
    public $timestamps = false;
    protected $table='lsv_rooms';
    // protected $fillable=['name','phone','address'];
    protected $fillable=[
        'agent',
        'visitor',
        'agenturl',
        'visitorurl',
        'password',
        'roomId',
        'datetime',
        'duration',
        'shortagenturl',
        'shortvisitorurl',
        'is_active',
    ];
}
