<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LabOrder extends Model
{
    protected $fillable=[
        'user_id',
        'order_id',
        'product_id',
        'session_id',
        'pres_id',
        'map_marker_id',
        'date',
        'time',
        'type',
        'report',
        'uploaded_by',
        'status',
        'price',
        'sub_order_id'
    ];
}
