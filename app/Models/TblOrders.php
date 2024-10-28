<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TblOrders
 * @package App\Models
 * @version December 23, 2020, 2:54 pm UTC
 *
 * @property string $order_state
 * @property string $order_id
 * @property string $order_sub_id
 * @property string $customer_id
 * @property string $total
 * @property string $shipping_total
 * @property string $total_tax
 * @property string $billing
 * @property string $shipping
 * @property string $payment
 * @property string $payment_title
 * @property string $payment_method
 * @property string $cart_items
 * @property string $tax_lines
 * @property string $shipping_lines
 * @property string $coupon_lines
 * @property string $currency
 * @property string $order_status
 * @property string $agent

 */
class TblOrders extends Model
{
    use SoftDeletes;

    public $table = 'tbl_orders';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'order_state',
        'order_id',
        'order_sub_id',
        'customer_id',
        'total',
        'shipping_total',
        'total_tax',
        'billing',
        'shipping',
        'payment',
        'payment_title',
        'payment_method',
        'cart_items',
        'tax_lines',
        'shipping_lines',
        'coupon_lines',
        'lab_order_approvals',
        'currency',
        'order_status',
        'agent',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'order_state' => 'string',
        'order_id' => 'string',
        'order_sub_id' => 'string',
        'customer_id' => 'string',
        'total' => 'string',
        'shipping_total' => 'string',
        'total_tax' => 'string',
        'billing' => 'string',
        'shipping' => 'string',
        'payment' => 'string',
        'payment_title' => 'string',
        'payment_method' => 'string',
        'cart_items' => 'string',
        'tax_lines' => 'string',
        'shipping_lines' => 'string',
        'coupon_lines' => 'string',
        'lab_order_approvals' => "string",
        'currency' => 'string',
        'order_status' => 'string',
        'agent' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'order_state' => 'nullable|string|max:191',
        'order_id' => 'nullable|string|max:191',
        'order_sub_id' => 'nullable|string',
        'customer_id' => 'nullable|string|max:191',
        'total' => 'nullable|string|max:191',
        'shipping_total' => 'nullable|string|max:191',
        'total_tax' => 'nullable|string|max:191',
        'billing' => 'nullable|string',
        'shipping' => 'nullable|string',
        'payment' => 'nullable|string',
        'payment_title' => 'nullable|string|max:191',
        'payment_method' => 'nullable|string|max:191',
        'cart_items' => 'nullable|string',
        'tax_lines' => 'nullable|string',
        'shipping_lines' => 'nullable|string',
        'coupon_lines' => 'nullable|string',
        'currency' => 'nullable|string|max:191',
        'order_status' => 'nullable|string|max:191',
        'agent' => 'nullable|string|max:191',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
    ];

}
