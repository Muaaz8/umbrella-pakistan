<?php

namespace App\Repositories;

use App\Models\ProductCategory;
use App\Repositories\BaseRepository;
use DB;

/**
 * Class ProductCategoryRepository
 * @package App\Repositories
 * @version September 16, 2020, 8:29 am UTC
*/

class ProductCategoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'category_type',
        'slug',
        'description',
        'thumbnail'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ProductCategory::class;
    }

    public function getProductCategories($type){
       return $data['productCategories'] = DB::table('product_categories')->select('*')->where('category_type', '=', $type)->get();
    }
}
