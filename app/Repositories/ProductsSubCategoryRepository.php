<?php

namespace App\Repositories;

use App\Models\ProductsSubCategory;
use App\Repositories\BaseRepository;
use DB;

/**
 * Class ProductsSubCategoryRepository
 * @package App\Repositories
 * @version September 16, 2020, 8:54 am UTC
 */

class ProductsSubCategoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'title',
        'slug',
        'description',
        'parent_id',
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
        return ProductsSubCategory::class;
    }

    public function getDataWithName($type)
    {
        $data = DB::table('products_sub_categories')
            ->join('product_categories', 'products_sub_categories.parent_id', '=', 'product_categories.id')
            ->select('products_sub_categories.*', 'product_categories.name as parent_name', 'product_categories.id as parent_id')
            ->where('product_categories.category_type', '=', $type['type'])
            ->get();
        return $data;
    }

    public function getDataWithNameWithId($id)
    {
        $data = DB::table('products_sub_categories')
            ->join('product_categories', 'products_sub_categories.parent_id', '=', 'product_categories.id')
            ->select('products_sub_categories.*', 'product_categories.name as parent_name', 'product_categories.id as parent_id')
            ->where('products_sub_categories.id', '=', $id)
            ->get();
        return $data;
    }
}
