<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'tbl_cart';
    protected $fillable=[
        'product_id',
        'name',
        'quantity',
        'price',
        'user_id',
        'doc_id',
        'doc_session_id',
        'pres_id',
        'update_price',
        'product_mode',
        'item_type',
        'show_product',
        'status',
        'map_marker_id',
        'refill_flag',
        'location_id',
        'product_image'
    ];
}
