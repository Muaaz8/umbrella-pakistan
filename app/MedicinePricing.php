<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedicinePricing extends Model
{
    protected $table = 'medicine_pricings';

    protected $fillable = [
        'id',
        'product_id',
        'unit_id',
        'days_id',
        'price',
        'sale_price',
        'percentage',
        'created_by',
        'created_at',
        'updated_at'
    ];
}
