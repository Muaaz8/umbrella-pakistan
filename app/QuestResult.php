<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestResult extends Model
{
    protected $fillable=[
        'get_request_id',
        'pat_id',
        'pat_first_name',
        'pat_last_name',
        'pat_gender',
        'doc_id',
        'doc_npi',
        'doc_name',
        'get_quest_request_id',
        'control_id',
        'base64_message',
        'hl7_message',
        'file',
        'status',
        'patient_matching',
        'provider_matching',
        'order_matching',
        'flag'
    ];
}
