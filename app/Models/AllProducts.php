<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class AllProducts
 * @package App\Models
 * @version September 16, 2020, 12:02 pm UTC
 *
 * @property string $name
 * @property string $panel_name
 * @property string $slug
 * @property string $parent_category
 * @property string $sub_category
 * @property string $featured_image
 * @property string $gallery
 * @property string $tags
 * @property string $sale_price
 * @property string $regular_price
 * @property string $quantity
 * @property string $keyword
 * @property string $mode
 * @property string $medicine_type
 * @property string $status
 * @property string $short_description
 * @property string $description
 * @property string $cpt_code
 * @property string $test_details
 * @property string $including_test
 * @property string $stock_quantity
 * @property string $stock_status
 * @property string medicine_ingredients
 * @property string $medicine_warnings
 * @property string $medicine_directions
 */
class AllProducts extends Model
{
    use SoftDeletes;

    public $table = 'tbl_products';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'name',
        'panel_name',
        'slug',
        'parent_category',
        'sub_category',
        'featured_image',
        'gallery',
        'tags',
        'sale_price',
        'regular_price',
        'quantity',
        'keyword',
        'mode',
        'medicine_type',
        'is_featured',
        'short_description',
        'description',
        'cpt_code',
        'test_details',
        'including_test',
        'faq_for_test',
        'medicine_ingredients',
        'stock_status',
        'medicine_warnings',
        'medicine_directions',
        'user_id',
        'del_req',
        'product_status',
        'is_approved',
        'is_approved_by'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'panel_name' => 'string',
        'slug' => 'string',
        'parent_category' => 'string',
        'sub_category' => 'string',
        'featured_image' => 'string',
        'gallery' => 'string',
        'tags' => 'string',
        'sale_price' => 'string',
        'regular_price' => 'string',
        'quantity' => 'string',
        'keyword' => 'string',
        'mode' => 'string',
        'medicine_type' => 'string',
        'status' => 'string',
        'short_description' => 'string',
        'description' => 'string',
        'cpt_code' => 'string',
        'test_details' => 'string',
        'including_test' => 'string',
        'medicine_ingredients' => 'string',
        'faq_for_test' => 'string',
        'stock_status' => 'string',
        'medicine_warnings' => 'string',
        'medicine_directions' => 'string',
        'user_id' => 'string',
        'del_req' => 'string',
        'product_status' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];
    public function refillRequest()
    {
        return $this->hasOne(RefillRequest::class);
    }

}
