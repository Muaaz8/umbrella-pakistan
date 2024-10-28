<?php

namespace App\Repositories;

use App\Models\AllProducts;
use App\Repositories\BaseRepository;
use DB;

/**
 * Class AllProductsRepository
 * @package App\Repositories
 * @version September 16, 2020, 12:02 pm UTC
 */

class AllProductsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
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
        return AllProducts::class;
    }

    public function getMainCategories($type)
    {
        $main_category_data = DB::table('product_categories')->select('id', 'name as title')->where('category_type', '=', $type)->get();
        $data = [];
        foreach ($main_category_data as $key => $item) {
            $data[] = $item->id . '|' . $item->title;
            // $sub_cat = DB::table('products_sub_categories')->select('id', 'title')->where('parent_id', '=', $item->id)->get();
            // foreach ($sub_cat as $key => $item2) {
            //     $sub_category[] = $item2->id . '|' . $item2->title;
            // }
        }

        return $data;
    }

    // public function getCategories($type)
    // {
    //     $main_category = DB::table('product_categories')->select('id', 'name as title')->where('category_type', '=', $type)->get();
    //     $sub_category = [];
    //     foreach ($main_category as $key => $item) {
    //         $sub_cat = DB::table('products_sub_categories')->select('id', 'title')->where('parent_id', '=', $item->id)->get();
    //         foreach ($sub_cat as $key => $item2) {
    //             //echo '<p>'. $item2->id.'</p>';
    //             //array_push($sub_category, $item2->id);
    //             $sub_category[] = $item2->id . '|' . $item2->title;
    //         }
    //     }
    //     return $sub_category;
    // }

    public function getCategories2()
    {
        $data['main_category'] = DB::table('product_categories')->select('id', 'name as title')->get();
        $data['sub_category'] = DB::table('products_sub_categories')->select('id', 'title')->get();
        return $data;
    }

    public function getParentChildName($id)
    {
        return $data = DB::table('tbl_products')
            ->join('product_categories', 'tbl_products.parent_category', '=', 'product_categories.id')
            ->join('products_sub_categories', 'tbl_products.sub_category', '=', 'products_sub_categories.id')
            ->select('tbl_products.*', 'product_categories.name as parent_name', 'products_sub_categories.title as child_name')
            ->where('tbl_products.id', '=', $id)
            ->get();
    }

    public function getProductsData($type)
    {
        return $data = DB::table('tbl_products')
        //->join('product_categories', 'tbl_products.parent_category', '=', 'product_categories.id')
        // ->join('products_sub_categories', 'tbl_products.sub_category', '=', 'products_sub_categories.id')
        //->select('tbl_products.*')
            ->where('mode', '=', $type['type'])
        //  ->where('sale_price', '!=', '0')
            ->where('product_status', '=', 1)
            ->where('is_approved', '=', 1)
            ->orderByDesc('tbl_products.id')
            ->get();
    }

    public function getParentCategoryNames($ids, $type)
    {
        $id = explode(",", $ids);
        $data = [];
        foreach ($id as $key => $i) {
            $query = DB::table('product_categories')->select('id', 'name as category_name')->where('category_type', '=', $type)->where('id', '=', $i)->get();
            array_push($data, $query[0]);
        }
        return json_encode($data);
    }

    public function getDeletedProductsData($type)
    {

        return $data = DB::table('tbl_products')
        // ->join('product_categories', 'tbl_products.parent_category', '=', 'product_categories.id')
        // ->join('products_sub_categories', 'tbl_products.sub_category', '=', 'products_sub_categories.id')
        // ->select('tbl_products.*', 'product_categories.name as parent_name', 'products_sub_categories.title as child_name')
            ->whereNotIn('mode', ['substance-abuse'])
            ->where('del_req', '1')
            ->orderByDesc('id')
            ->get();
    }

    public function getNewAddedProduct()
    {

        return $data = DB::table('tbl_products')
        //->join('product_categories', 'tbl_products.parent_category', '=', 'product_categories.id')
        // ->join('products_sub_categories', 'tbl_products.sub_category', '=', 'products_sub_categories.id')
        // ->select('tbl_products.*', 'product_categories.name as parent_name', 'products_sub_categories.title as child_name')
            ->whereNotIn('mode', ['substance-abuse'])
            ->where('is_approved', '0')
            ->orderByDesc('id')
            ->get();
    }

    public function getParentCatName($id)
    {
        $data = DB::table('products_sub_categories')
            ->join('product_categories', 'products_sub_categories.parent_id', '=', 'product_categories.id')
            ->select('product_categories.name as parent_name', 'product_categories.id as parent_id')
            ->where('products_sub_categories.id', '=', $id)
            ->get();
        return $data[0];
    }

    public function getProductNameID($keyword)
    {
        $data = DB::table('tbl_products')
            ->select('id', 'name as text')
            ->where('name', 'like', '%' . $keyword . '%')
            ->where('tags', '!=', '')
            ->where('mode', 'lab-test')
            ->get();

        return $data;
    }

    public function getFAQNameID($keyword)
    {
        $data = DB::table('tbl_faq')
            ->select('id', 'question as text')
            ->get();

        return $data;
    }

    public function getParentCategoryNameID($keyword)
    {
        $data = DB::table('product_categories')
            ->select('id', 'name as text')
            ->where('name', 'like', '%' . $keyword . '%')->where('category_type', 'lab-test')
            ->get();

        return $data;
    }

    public function getImagingServicesSelect($keyword)
    {
        $data = DB::table('tbl_products')
            ->select('id', 'name as text')
            ->where('name', 'like', '%' . $keyword . '%')->where('mode', 'imaging')
            ->get();

        return $data;
    }

    public function getImagingLocationSelect($keyword)
    {

        $data = DB::table('imaging_locations')
            ->select('id', DB::raw("CONCAT(`clinic_name`, ' ', `city`, ' (', `zip_code`, ')') AS `text`"))
            ->where('city', 'LIKE', '%' . $keyword . '%')
            ->orWhere('clinic_name', 'LIKE', '%' . $keyword . '%')
            ->orWhere('address', 'LIKE', '%' . $keyword . '%')
            ->get();

        return $data;
    }

    public function getTestNameAndPrice($ids)
    {

        $data = [];

        foreach ($ids as $key => $id) {
            $query = DB::table('tbl_products')
                ->select('id', 'name as test_name', 'sale_price as price', 'slug', 'cpt_code', 'tags as test_code')
                ->where('name', '=', $id)->where('mode', 'lab-test')
                ->get();
            array_push($data, $query[0]);
        }

        // echo '<pre>';
        return json_encode($data);
        // echo '</pre>';
    }

    public function getFAQByID($ids)
    {

        $data = [];

        foreach ($ids as $key => $question) {
            $query = DB::table('tbl_faq')
                ->select('id', 'question', 'answer')
                ->where('question', '=', $question)
                ->get();
            array_push($data, $query[0]);
        }
        return json_encode($data);
    }
}
