<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ProductsSubCategory
 * @package App\Models
 * @version September 16, 2020, 8:54 am UTC
 *
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property integer $parent_id
 * @property string $thumbnail
 */
class ProductsSubCategory extends Model
{
    // use SoftDeletes;

    public $table = 'products_sub_categories';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'title',
        'slug',
        'created_by',
        'description',
        'parent_id',
        'thumbnail'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'slug' => 'string',
        'description' => 'string',
        'parent_id' => 'integer',
        'thumbnail' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];
}
