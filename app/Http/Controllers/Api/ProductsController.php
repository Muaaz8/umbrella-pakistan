<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\QuestDataTestCode;
use DB;
use Illuminate\Http\Request;
use function Aws\map;

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
                    foreach ($products as $key => $product) {
                        $product->featured_image = \App\Helper::check_bucket_files_url($product->featured_image);
                        if($product->featured_image == env('APP_URL')."/assets/images/user.png"){
                            $product->featured_image = asset('assets/new_frontend/panadol2.png');
                        }
                    }
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
            return response()->json(['message' => 'Category name and product ID are required'], 400);
        }
    
        if ($name === 'pharmacy') {
            $product = DB::table('tbl_products')
                ->select(
                    'tbl_products.id',
                    'tbl_products.name',
                    'tbl_products.short_description',
                    'tbl_products.description',
                    'tbl_products.is_single',
                    'tbl_products.is_otc',
                    'tbl_products.featured_image',
                    'medicine_pricings.unit_id',
                    'medicine_pricings.sale_price',
                    'medicine_pricings.percentage',
                    'products_sub_categories.title as category_name'
                )
                ->join('products_sub_categories', 'products_sub_categories.id', '=', 'tbl_products.sub_category')
                ->join('medicine_pricings', 'medicine_pricings.product_id', '=', 'tbl_products.id')
                ->where('tbl_products.mode', 'medicine')
                ->where('tbl_products.id', $id)
                ->first();
    
            if (!$product) {
                return response()->json(['message' => 'Product not found'], 404);
            }

            $product->featured_image = \App\Helper::check_bucket_files_url($product->featured_image);
            if ($product->featured_image === env('APP_URL') . "/assets/images/user.png") {
                $product->featured_image = asset('assets/new_frontend/panadol2.png');
            }
    
            return response()->json(['product' => $product]);
        }
    
        if (in_array($name, ['imaging', 'lab-test'])) {
            $product = DB::table('quest_data_test_codes')
                ->select(
                    'id',
                    'TEST_NAME',
                    'DESCRIPTION',
                    'actual_price',
                    'SALE_PRICE',
                    'PRICE',
                    'discount_percentage'
                )
                ->where('id', $id)
                ->first();
    
            if (!$product) {
                return response()->json(['message' => 'Product not found'], 404);
            }
    
            return response()->json(['product' => $product]);
        }
    
        return response()->json(['message' => 'Invalid category name'], 400);
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
                foreach ($products as $key => $product) {
                    $product->featured_image = \App\Helper::check_bucket_files_url($product->featured_image);
                    if($product->featured_image == env('APP_URL')."/assets/images/user.png"){
                        $product->featured_image = asset('assets/new_frontend/panadol2.png');
                    }
                }
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
                ->where('PARENT_CATEGORY',$id)
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
                ->where('PARENT_CATEGORY',$id)
                ->paginate(10);
                return response()->json(['products' => $products]);
            }
        }
    }

    public function getProductsCategories(){
        $med = DB::table('products_sub_categories')->where('parent_id', '38')
            ->join('tbl_products','products_sub_categories.id','tbl_products.sub_category')
            ->select('products_sub_categories.*')
            ->groupBy('tbl_products.sub_category')
            ->get();
        $img = DB::table('product_categories')->where('category_type', 'imaging')->get();   
        $labs = QuestDataTestCode::where('mode', 'lab-test')
        ->where('TEST_NAME','!=', null)
        ->where('PRICE', '!=', null)
        ->get();     
        return response()->json(['pharmacy-categories'=> $med,'imaging-categories'=> $img, 'labtest-products'=> $labs]);
    }

    public function getPharmacyByCategory(Request $request){
        if($request->name=='')
        {
            $products = DB::table('tbl_products')
            ->where('sub_category', $request->med_id)
            ->where('mode', 'medicine')
            ->where('product_status', 1)
            ->where('is_approved', 1)
            ->get();
            return response()->json(['products' => $products]);
        }
        else
        {
            $products = DB::table('tbl_products')
            ->where('name','LIKE', "%{$request->name}%")
            ->where('mode', 'medicine')
            ->where('product_status', 1)
            ->where('is_approved', 1)
            ->get();
            return response()->json(['products' => $products]);
        }
    }

    public function getImagingByCategory(Request $request){
        if($request->name=='')
        {
            $products = DB::table('quest_data_test_codes')
            ->where('PARENT_CATEGORY', $request->img_id)
            ->where('mode', 'imaging')
            ->get();
            return response()->json(['products' => $products]);
        }
        else
        {
            $products = DB::table('quest_data_test_codes')
            ->where('TEST_NAME','LIKE', "%{$request->name}%")
            ->where('mode', 'imaging')
            ->get();
            return response()->json(['products' => $products]);
        }
    }

    public function getLabtestByCategory(Request $request){
        if($request->name== '')
        {
            $products = DB::table('quest_data_test_codes')
            ->where('PARENT_CATEGORY', '38')
            ->where('mode', 'lab-test')
            ->get();
            return response()->json(['products' => $products]);
        }
        else
        {
            $products = DB::table('quest_data_test_codes')
            ->where('TEST_NAME','LIKE', "%{$request->name}%")
            ->where('mode', 'lab-test')
            ->get();
            return response()->json(['products' => $products]);
        }
    }



}
