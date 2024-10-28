<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LsvRoom extends Model
{
    protected $connection= 'suunnoo_db';
    protected $table='lsv_rooms';
    public $timestamps = false;

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
        'session_id'
    ];
}
