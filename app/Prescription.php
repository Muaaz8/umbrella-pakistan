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

    public function medicine()
    {
        return $this->belongsTo(\App\Models\AllProducts::class, 'medicine_id');
    }

    public function test()
    {
        return $this->belongsTo(\App\QuestDataTestCode::class, 'test_id', 'TEST_CD');
    }

    public function imaging()
    {
        return $this->belongsTo(\App\QuestDataTestCode::class, 'imaging_id', 'TEST_CD');
    }
}
