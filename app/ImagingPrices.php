<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImagingPrices extends Model
{

    protected $fillable = [
        'location_id',
        'product_id',
        'price',
        'actual_price',
        'created_by',
        'created_at',
        'updated_at'
    ];
}
