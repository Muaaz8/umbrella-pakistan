<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestGetResultRequest extends Model
{
    protected $fillable=[
        'name',
        'resultServiceType',
        'json_response',
        'json_ack'
    ];
}
