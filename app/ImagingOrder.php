<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImagingOrder extends Model
{
    protected $fillable=[
        'user_id',
        'order_id',
        'sub_order_id',
        'location_id',
        'price',
        'product_id',
        'session_id',
        'pres_id',
        'date',
        'time',
        'report',
        'uploaded_by',
        'status'
    ];
}
