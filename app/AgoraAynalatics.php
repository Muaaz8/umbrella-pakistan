<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AgoraAynalatics extends Model
{
    protected $table='table_agora_aynalatics';
    protected $fillable=[
        'resID',
        'sID',
        'channel',
        'video_link',
        'userID',
        'secUserID'
    ];
}
