<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ProductCategory
 * @package App\Models
 * @version September 16, 2020, 8:29 am UTC
 *
 * @property string $name
 * @property string $slug
 * @property string $category_type
 * @property string $description
 * @property string $thumbnail
 */
class ProductCategory extends Model
{
    use SoftDeletes;

    public $table = 'product_categories';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'name',
        'created_by',
        'slug',
        'category_type',
        'description',
        'thumbnail'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'slug' => 'string',
        'category_type' => 'string',
        'description' => 'string',
        'thumbnail' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required'
    ];

    
}
