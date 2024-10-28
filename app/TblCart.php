<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TblCart extends Model
{
    public $table = 'tbl_cart';

    public $fillable = [
        'session_id',
        'cart_row_id',
        'product_id',
        'name',
        'product_image',
        'prescription',
        'design_view',
        'strip_per_pack',
        'quantity',
        'price',
        'discount',
        'created_at',
        'updated_at',
        'user_id',
        'doc_session_id',
        'doc_id',
        'pres_id',
        'update_price',
        'product_mode',
        'show_product',
        'item_type',
        'status',
        'purchase_status',
        'checkout_status',
        'map_marker_id',
        'refill_flag',
        'location_id',
        'coupon_code_id'
    ];
}
