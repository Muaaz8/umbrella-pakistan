<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index($name)
    {
        if (!empty($name)) {
            if ($name == 'pharmacy') {
                $products = DB::table('tbl_products')
                    ->select(
                        'tbl_products.id',
                        'tbl_products.name',
                        'tbl_products.short_description',
                        'tbl_products.is_single',
                        'tbl_products.is_otc',
                        'tbl_products.featured_image',
                        'medicine_pricings.unit_id as unit_id',
                        'medicine_pricings.sale_price as sale_price',
                        'medicine_pricings.percentage as percentage',
                        'products_sub_categories.title as category_name',
                    )
                    ->join('products_sub_categories', 'products_sub_categories.id', 'tbl_products.sub_category')
                    ->join('medicine_pricings', 'medicine_pricings.product_id', 'tbl_products.id')
                    ->where('tbl_products.mode', 'medicine')
                    ->paginate(10);
                return response()->json(['products' => $products]);
            }
            if ($name == 'imaging') {
                $products = DB::table('quest_data_test_codes')
                    ->select(
                        'quest_data_test_codes.id',
                        'quest_data_test_codes.TEST_NAME',
                        'quest_data_test_codes.DESCRIPTION',
                        'quest_data_test_codes.actual_price',
                        'quest_data_test_codes.SALE_PRICE',
                        'quest_data_test_codes.PRICE',
                        'quest_data_test_codes.discount_percentage',
                    )
                    ->where('mode', 'imaging')
                    ->paginate(10);
                return response()->json(['products' => $products]);
            }
            if ($name == 'lab-test') {
                $products = DB::table('quest_data_test_codes')
                    ->select(
                        'quest_data_test_codes.id',
                        'quest_data_test_codes.TEST_NAME',
                        'quest_data_test_codes.DESCRIPTION',
                        'quest_data_test_codes.actual_price',
                        'quest_data_test_codes.SALE_PRICE',
                        'quest_data_test_codes.PRICE',
                        'quest_data_test_codes.discount_percentage',
                    )
                    ->where('mode', 'lab-test')
                    ->paginate(10);
                return response()->json(['products' => $products]);
            }
        } else {
            return response()->json(['message' => 'Category name is required. e.g: /pharmacy , /lab-test , /imaging'], 404);
        }
    }

    public function singleProduct($name, $id)
    {
        if (empty($name) || empty($id)) {
            return response()->json(['message' => 'category name and product ID are required'], 404);
        } else {
            if ($name == 'pharmacy') {
                $product = DB::table('tbl_products')
                    ->select(
                        'tbl_products.id',
                        'tbl_products.name',
                        'tbl_products.short_description',
                        'tbl_products.is_single',
                        'tbl_products.is_otc',
                        'tbl_products.featured_image',
                        'medicine_pricings.unit_id as unit_id',
                        'medicine_pricings.sale_price as sale_price',
                        'medicine_pricings.percentage as percentage',
                        'products_sub_categories.title as category_name',
                    )
                    ->join('products_sub_categories', 'products_sub_categories.id', 'tbl_products.sub_category')
                    ->join('medicine_pricings', 'medicine_pricings.product_id', 'tbl_products.id')
                    ->where('tbl_products.mode', 'medicine')
                    ->where('id', $id)
                    ->paginate(10);
                return response()->json(['product' => $product]);
            }
            if ($name == 'imaging' || $name == 'lab-test') {
                $product = DB::table('quest_data_test_codes')
                    ->select(
                        'quest_data_test_codes.id',
                        'quest_data_test_codes.TEST_NAME',
                        'quest_data_test_codes.DESCRIPTION',
                        'quest_data_test_codes.actual_price',
                        'quest_data_test_codes.SALE_PRICE',
                        'quest_data_test_codes.PRICE',
                        'quest_data_test_codes.discount_percentage',
                    )
                    ->where('id', $id)
                    ->paginate(10);
                return response()->json(['product' => $product]);
            }
        }
    }

    public function getCategories($name)
    {
        if (empty($name)) {
            return response()->json(['message' => 'category name required'], 404);
        } else {
            if ($name == 'pharmacy') {
                $categories = DB::table('product_categories')
                    ->select(
                        'product_categories.id',
                        'product_categories.name',
                    )
                    ->where('category_type', 'medicine')
                    ->paginate(20);
                return response()->json(['categories' => $categories]);
            }
            if ($name == 'imaging') {
                $categories = DB::table('product_categories')
                    ->select(
                        'product_categories.id',
                        'product_categories.name',
                    )
                    ->where('category_type', 'imaging')
                    ->paginate(20);
                return response()->json(['categories' => $categories]);
            }
            if ($name == 'lab-test') {
                $categories = DB::table('product_categories')
                    ->select(
                        'product_categories.id',
                        'product_categories.name',
                    )
                    ->where('category_type', 'lab-test')
                    ->paginate(20);
                return response()->json(['categories' => $categories]);
            }
        }
    }

    public function getProductsByCategory($name , $id){
        if (empty($name)) {
            return response()->json(['message'=> 'category name and id reqiured'],404);
        }else{
            if($name == 'pharmacy'){
                $products = DB::table('tbl_products')
                ->select(
                    'tbl_products.id',
                    'tbl_products.name',
                    'tbl_products.short_description',
                    'tbl_products.is_single',
                    'tbl_products.is_otc',
                    'tbl_products.featured_image',
                    'medicine_pricings.unit_id as unit_id',
                    'medicine_pricings.sale_price as sale_price',
                    'medicine_pricings.percentage as percentage',
                    'products_sub_categories.title as category_name',
                )
                ->join('products_sub_categories','products_sub_categories.id','tbl_products.sub_category')
                ->join('medicine_pricings','medicine_pricings.product_id','tbl_products.id')
                ->where('tbl_products.mode','medicine')
                ->where('tbl_products.sub_category',$id)
                ->paginate(10);
                return response()->json(['products' => $products]);
            }
            if($name == 'imaging'){
                $products = DB::table('quest_data_test_codes')
                ->select(
                    'quest_data_test_codes.id',
                    'quest_data_test_codes.TEST_NAME',
                    'quest_data_test_codes.DESCRIPTION',
                    'quest_data_test_codes.actual_price',
                    'quest_data_test_codes.SALE_PRICE',
                    'quest_data_test_codes.PRICE',
                    'quest_data_test_codes.discount_percentage',
                )
                ->where('mode','imaging')
                ->where('category_id',$id)
                ->paginate(10);
                return response()->json(['products' => $products]);
            }
            if($name == 'lab-test'){
                $products = DB::table('quest_data_test_codes')
                ->select(
                    'quest_data_test_codes.id',
                    'quest_data_test_codes.TEST_NAME',
                    'quest_data_test_codes.DESCRIPTION',
                    'quest_data_test_codes.actual_price',
                    'quest_data_test_codes.SALE_PRICE',
                    'quest_data_test_codes.PRICE',
                    'quest_data_test_codes.discount_percentage',
                )
                ->where('mode','lab-test')
                ->where('category_id',$id)
                ->paginate(10);
                return response()->json(['products' => $products]);
            }
        }
    }
}
