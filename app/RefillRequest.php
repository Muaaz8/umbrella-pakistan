<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RefillRequest extends Model
{
    protected $fillable=[
        'pres_id',
        'prod_id',
        'patient_id',
        'doctor_id',
        'session_id',
        'granted',
        'comment',
        'session_req'
    ];
    public function prescription()
    {
        return $this->belongsTo(Prescription::class,'pres_id');
    }
    public function session()
    {
        // dd($this->belongsTo(Session::class,'session_id'));
        return $this->belongsTo(Session::class,'session_id');
    }
    public function patient()
    {
        return $this->belongsTo(User::class,'patient_id');
    }
    public function doctor()
    {
        return $this->belongsTo(User::class,'doctor_id');
    }
    public function product()
    {
        return $this->belongsTo(Models\AllProducts::class,'prod_id');
    }
}
