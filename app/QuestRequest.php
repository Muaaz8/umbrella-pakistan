<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestRequest extends Model
{
    protected $fillable=[
        'order_id',
        'patient_id',
        'documentTypes',
        'orderHL7',
        'ackhl7',
        'requestStatus',
        'responseMessage',
        'status',
        'orderSupportDocuments',
        'hl7_payload',
        'quest_lab_id', 
        'requisition_file'
    ];
}
