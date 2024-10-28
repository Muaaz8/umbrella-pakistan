<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LsvRecording extends Model
{
    protected $connection= 'suunnoo_db';
    protected $table='lsv_recordings';
    public $timestamps = false;
    protected $fillable=[
        'recording_id',
        'filename',
        'room_id',
        'agent_id',
        'date_created'
    ];
}
