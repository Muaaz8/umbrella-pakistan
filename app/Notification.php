<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
    	'user_id',
    	'text',
    	'type',
    	'status',    	
		'appoint_id',
		'session_id',
		'refill_id'
    ];
}
