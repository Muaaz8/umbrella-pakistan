<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $fillable = [
        'session_id',
        'medicine_id',
        'test_id',
        'imaging_id',
        'title',
        'type',
        'comment',
        'usage',
        'quantity',
        'parent_id',
        'med_time',
        'med_days',
        'med_unit',
        'price',
        'created_at'
    ];
    public function refillRequest()
    {
        return $this->hasOne(RefillRequest::class);
    }
}
