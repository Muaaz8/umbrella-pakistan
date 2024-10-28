<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestLab extends Model
{
    protected $fillable=[
        'order_id',
        'quest_patient_id',
        'umd_patient_id',
        'abn',
        'billing_type',
        'diagnosis_code',
        'vendor_account_id',
        'orderedtestcode',
        'names',
        'aoe',
        'collect_date',
        'collect_time',
        'lab_reference_num',
        'npi',
        'ssn',
        'room',
        'result_notification',
        'insurance_num',
        'group_num',
        'relation',
        'upin',
        'ref_physician_id',
        'temp',
        'icd_diagnosis_code',
        'psc_hold',
        'barcode',
        'specimen_labels',
        'comment',
        'client_bill',
        'patient_bill',
        'third_party_bill'
    ];
}
