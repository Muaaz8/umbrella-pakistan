<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DoctorCertificate extends Model
{
    protected $table='doctor_certificates';
    protected $fillable=[
        'doc_id',
        'certificate_file',
    ];
}
