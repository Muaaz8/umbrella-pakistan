<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorController extends BaseController
{
    public function getVendors($type)
    {
        $vendors = DB::table('vendor_accounts')
            ->where('vendor', $type)
            ->select(
                'id',
                'name',
                'image',
                'address',
                'vendor_number',
                'vendor',
                'is_active'
            )
            ->where('is_active', 1)
            ->paginate(12);

        foreach ($vendors as $key => $vendor) {
            $vendor->image = \App\Helper::check_bucket_files_url($vendor->image);
            $vendor->products_count = DB::table('vendor_products')
                ->where('vendor_id', $vendor->id)
                ->where('is_active', 1)
                ->count();
        }

        return $this->sendResponse([
            'vendors' => $vendors,
        ], 'Vendors retrieved successfully.');
    }

    public function getLoaction()
    {
        $locations = DB::table('locations')->get();
        return $this->sendResponse([
            'locations' => $locations,
        ], 'Locations retrieved successfully.');
    }

    public function getVendorProducts(Request $request, $modeType)
    {
        $vendor_id = $request->query('id');
        if (!$vendor_id) {
            return $this->sendError('Vendor ID is required.', []);
        }

        if ($modeType == 'labs' || $modeType == 'imaging') {
            $data = DB::table('quest_data_test_codes')
                ->Join('vendor_products', 'quest_data_test_codes.TEST_CD', '=', 'vendor_products.product_id')
                ->join('vendor_accounts', 'vendor_accounts.id', '=', 'vendor_products.vendor_id')
                ->select(
                    'vendor_products.vendor_id',
                    'vendor_products.id AS vendor_product_id',
                    'vendor_products.selling_price AS sale_price',
                    'vendor_products.actual_price  AS actual_price',
                    'vendor_products.discount AS discount_percentage',
                    'vendor_products.available_stock',
                    'quest_data_test_codes.TEST_CD AS id',
                    'quest_data_test_codes.TEST_NAME AS name',
                    'quest_data_test_codes.DETAILS AS short_description',
                    'quest_data_test_codes.DETAILS AS description',
                    DB::raw('SLUG as slug'),
                    DB::raw('"quest_data_test_codes" as tbl_name')
                )
                ->where([
                    ['PARENT_CATEGORY', '!=', ""],
                    ['AOES_exist', null],
                ])
                ->where('vendor_products.vendor_id', $vendor_id)
                ->where('vendor_products.is_active', '1')
                ->where('vendor_accounts.is_active', '1')
                ->orderBy('name', 'ASC')
                ->paginate(12)->appends(
                    [
                        'id' => $vendor_id
                    ]
                );
            foreach ($data as $product) {
                if ($product->discount_percentage != null) {
                    $product->actual_price = $product->sale_price;
                    $product->sale_price = $product->sale_price - ($product->sale_price * $product->discount_percentage / 100);
                }
            }
        } else {
            $data = DB::table('tbl_products')
                ->join('products_sub_categories', 'products_sub_categories.id', '=', 'tbl_products.sub_category')
                ->join('vendor_products', 'vendor_products.product_id', '=', 'tbl_products.id')
                ->join('vendor_accounts', 'vendor_accounts.id', '=', 'vendor_products.vendor_id')
                ->select(
                    'tbl_products.id',
                    'tbl_products.name',
                    'tbl_products.panel_name',
                    'tbl_products.slug',
                    'tbl_products.parent_category',
                    'tbl_products.sub_category',
                    'tbl_products.featured_image',
                    'tbl_products.gallery',
                    'tbl_products.tags',
                    'tbl_products.quantity',
                    'tbl_products.keyword',
                    'tbl_products.mode',
                    'tbl_products.medicine_type',
                    'tbl_products.is_featured',
                    'tbl_products.short_description',
                    'tbl_products.description',
                    'tbl_products.cpt_code',
                    'tbl_products.test_details',
                    'tbl_products.including_test',
                    'tbl_products.faq_for_test',
                    'tbl_products.medicine_ingredients',
                    'tbl_products.stock_status',
                    'tbl_products.medicine_warnings',
                    'tbl_products.medicine_directions',
                    'tbl_products.is_otc',
                    'vendor_products.vendor_id',
                    'vendor_products.id as vendor_product_id',
                    'vendor_products.selling_price as sale_prices',
                    'vendor_products.actual_price',
                    'vendor_products.discount',
                    'vendor_products.available_stock',
                    'products_sub_categories.title as sub_category_name',
                    'products_sub_categories.slug as sub_category_slug',
                )
                ->where('tbl_products.is_approved', 1)
                ->where('vendor_products.vendor_id', $vendor_id)
                ->where('vendor_products.is_active', 1)
                ->groupBy(
                    'tbl_products.id',
                    'vendor_products.id',
                    'products_sub_categories.id'
                )
                ->orderBy('tbl_products.name', 'asc')
                ->paginate(12)->appends(
                    [
                        'id' => $vendor_id
                    ]
                );

            foreach ($data as $product) {
                $product->short_description = strip_tags($product->short_description);
                $product->featured_image = \App\Helper::check_bucket_files_url($product->featured_image);

                if ($product->featured_image == env('APP_URL') . "/assets/images/user.png") {
                    $product->featured_image = asset('assets/new_frontend/panadol2.png');
                }
            }
        }

        return $data;
    }

    public function getSingleProduct($name, $vendor_id)
    {
        if (!$vendor_id) {
            return $this->sendError('Vendor ID is required.', []);
        }
        $data = [];
        $vendorType = DB::table('vendor_accounts')
            ->where('id', $vendor_id)
            ->value('vendor');
        if ($vendorType == 'labs') {
            $data = DB::table('quest_data_test_codes')
                ->Join('vendor_products', 'quest_data_test_codes.TEST_CD', '=', 'vendor_products.product_id')
                ->join('vendor_accounts', 'vendor_accounts.id', '=', 'vendor_products.vendor_id')
                ->select(
                    'vendor_products.vendor_id',
                    'vendor_accounts.name AS vendor_name',
                    'vendor_products.id AS id',
                    'vendor_products.selling_price AS sale_price',
                    'vendor_products.actual_price  AS actual_price',
                    'vendor_products.discount AS discount_percentage',
                    'vendor_products.available_stock',
                    'quest_data_test_codes.TEST_CD AS MPK',
                    'quest_data_test_codes.TEST_NAME AS name',
                    'quest_data_test_codes.DETAILS AS short_description',
                    'quest_data_test_codes.DETAILS AS description',
                    'quest_data_test_codes.mode',
                    DB::raw('SLUG as slug'),
                    DB::raw('"quest_data_test_codes" as tbl_name')
                )
                ->where([
                    ['quest_data_test_codes.PARENT_CATEGORY', '!=', ""],
                ])
                ->where([['quest_data_test_codes.slug', $name]])
                ->where('vendor_products.vendor_id', $vendor_id)
                ->get();
            foreach ($data as $product) {
                if ($product->discount_percentage != null) {
                    $product->actual_price = $product->sale_price;
                    $product->sale_price = $product->sale_price - ($product->sale_price * $product->discount_percentage / 100);
                }
            }
        } else {
            $data = DB::table('tbl_products')
                ->join('products_sub_categories', 'products_sub_categories.id', '=', 'tbl_products.sub_category')
                ->join('vendor_products', 'vendor_products.product_id', '=', 'tbl_products.id')
                ->join('vendor_accounts', 'vendor_accounts.id', '=', 'vendor_products.vendor_id')
                ->select(
                    'vendor_products.id AS id',
                    'tbl_products.name',
                    'tbl_products.panel_name',
                    'tbl_products.slug',
                    'tbl_products.parent_category',
                    'tbl_products.sub_category',
                    'tbl_products.featured_image',
                    'tbl_products.gallery',
                    'tbl_products.tags',
                    'tbl_products.quantity',
                    'tbl_products.keyword',
                    'tbl_products.mode',
                    'tbl_products.is_otc',
                    'tbl_products.medicine_type',
                    'tbl_products.is_featured',
                    'tbl_products.short_description',
                    'tbl_products.description',
                    'tbl_products.cpt_code',
                    'tbl_products.test_details',
                    'tbl_products.including_test',
                    'tbl_products.faq_for_test',
                    'tbl_products.medicine_ingredients',
                    'tbl_products.id AS MPK',
                    'tbl_products.stock_status',
                    'tbl_products.medicine_warnings',
                    'tbl_products.medicine_directions',
                    'tbl_products.generic',
                    'tbl_products.is_single',
                    'vendor_products.vendor_id',
                    'vendor_accounts.name AS vendor_name',
                    'vendor_products.selling_price AS sale_prices',
                    'vendor_products.actual_price',
                    'vendor_products.discount AS discount',
                    'vendor_products.available_stock',
                    'vendor_products.is_active',
                    'products_sub_categories.title as sub_category_name',
                    'products_sub_categories.slug as sub_category_slug',
                )
                ->where('tbl_products.slug', '=', $name)
                ->where('vendor_products.vendor_id', $vendor_id)
                ->where('vendor_products.is_active', 1)
                ->get();
        
        foreach ($data as $product) {
        $product->featured_image = \App\Helper::check_bucket_files_url($product->featured_image);
            if($product->featured_image == env('APP_URL')."/assets/images/user.png"){
                $product->featured_image = asset('assets/new_frontend/panadol2.png');
                }
            }
        }

        return $this->sendResponse([
            'product' => $data,
        ], 'Product retrieved successfully.');
    }

public function findVendorbyLocation(Request $request)
{
    $locationId = $request->locationId;
    $vendorType = $request->shop_type;
    $searchText = $request->searchText;

    $validVendorTypes = ['labs', 'pharmacy'];
    if ($vendorType && !in_array($vendorType, $validVendorTypes)) {
        return response()->json([
            'error' => 'Invalid vendor type'
        ], 400);
    }

    $query = DB::table('vendor_accounts');

    if ($vendorType) {
        $query->where('vendor', $vendorType);
    }

    if ($locationId && $locationId !== 'all') {
        $query->where('location_id', $locationId);
    }

    if ($searchText && trim($searchText) !== '') {
        $searchTerm = '%' . trim($searchText) . '%';
        $query->where(function($q) use ($searchTerm) {
            $q->where('name', 'LIKE', $searchTerm)
              ->orWhere('address', 'LIKE', $searchTerm);
        });
    }

    $query->where('is_active', 1);

    $vendors = $query->paginate(12);

    foreach ($vendors as $vendor) {
        $vendor->image = \App\Helper::check_bucket_files_url($vendor->image);
        $vendor->products_count = DB::table('vendor_products')
            ->where('vendor_id', $vendor->id)
            ->where('is_active', 1)
            ->count();
    }

    if ($vendors->count() === 0) {
        return response()->json([
            'vendors' => [],
            'pagination' => [
                'current_page' => 1,
                'last_page' => 1,
                'per_page' => 12,
                'total' => 0,
                'from' => null,
                'to' => null,
                'has_more_pages' => false
            ],
            'message' => 'No vendors found for the specified criteria'
        ], 200);
    }

    return response()->json([
        'vendors' => $vendors->items(),
        'pagination' => [
            'current_page' => $vendors->currentPage(),
            'last_page' => $vendors->lastPage(),
            'per_page' => $vendors->perPage(),
            'total' => $vendors->total(),
            'from' => $vendors->firstItem(),
            'to' => $vendors->lastItem(),
            'has_more_pages' => $vendors->hasMorePages()
        ],
        'message' => 'Vendors retrieved successfully'
    ]);
}

    public function searchPharmacyItemByCategory(Request $request, $vendor_id)
    {
        if ($request->cat_id == 'all' && strlen($request->text) < 4) {
            $products = DB::table('tbl_products')
                ->join('products_sub_categories', 'products_sub_categories.id', '=', 'tbl_products.sub_category')
                ->join('vendor_products', 'vendor_products.product_id', '=', 'tbl_products.id')
                ->join('vendor_accounts', 'vendor_accounts.id', '=', 'vendor_products.vendor_id')
                ->select(
                    'tbl_products.id',
                    'tbl_products.name',
                    'tbl_products.panel_name',
                    'tbl_products.slug',
                    'tbl_products.parent_category',
                    'tbl_products.sub_category',
                    'tbl_products.featured_image',
                    'tbl_products.gallery',
                    'tbl_products.tags',
                    'tbl_products.quantity',
                    'tbl_products.keyword',
                    'tbl_products.mode',
                    'tbl_products.medicine_type',
                    'tbl_products.is_featured',
                    'tbl_products.short_description',
                    'tbl_products.description',
                    'tbl_products.cpt_code',
                    'tbl_products.test_details',
                    'tbl_products.including_test',
                    'tbl_products.faq_for_test',
                    'tbl_products.medicine_ingredients',
                    'tbl_products.stock_status',
                    'tbl_products.medicine_warnings',
                    'tbl_products.medicine_directions',
                    'tbl_products.is_otc',

                    'vendor_products.vendor_id',
                    'vendor_products.id as vendor_product_id',
                    'vendor_products.selling_price as sale_prices',
                    'vendor_products.actual_price',
                    'vendor_products.discount',
                    'vendor_products.available_stock',

                    'products_sub_categories.title as sub_category_name',
                    'products_sub_categories.slug as sub_category_slug',
                )
                ->where('tbl_products.product_status', 1)
                ->where('tbl_products.is_approved', 1)
                ->where('vendor_products.vendor_id', $vendor_id)
                ->where('vendor_products.is_active', 1)
                ->where('tbl_products.name', 'LIKE', $request->text . '%')
                ->groupBy(
                    'tbl_products.id',
                    'vendor_products.id',
                    'products_sub_categories.id'
                )
                ->paginate(10)->appends(
                    [
                        'id' => $vendor_id
                    ]
                );
            foreach ($products as $product) {
                $product->short_description = strip_tags($product->short_description);
                $product->featured_image = \App\Helper::check_bucket_files_url($product->featured_image);
                if($product->featured_image == env('APP_URL')."/assets/images/user.png"){
                    $product->featured_image = asset('assets/new_frontend/panadol2.png');
                }
            }
        } else if ($request->cat_id != 'all' && strlen($request->text) < 4) {
            $products = DB::table('tbl_products')
                ->join('products_sub_categories', 'products_sub_categories.id', '=', 'tbl_products.sub_category')
                ->join('vendor_products', 'vendor_products.product_id', '=', 'tbl_products.id')
                ->join('vendor_accounts', 'vendor_accounts.id', '=', 'vendor_products.vendor_id')
                ->select(
                    'tbl_products.id',
                    'tbl_products.name',
                    'tbl_products.panel_name',
                    'tbl_products.slug',
                    'tbl_products.parent_category',
                    'tbl_products.sub_category',
                    'tbl_products.featured_image',
                    'tbl_products.gallery',
                    'tbl_products.tags',
                    'tbl_products.quantity',
                    'tbl_products.keyword',
                    'tbl_products.mode',
                    'tbl_products.medicine_type',
                    'tbl_products.is_featured',
                    'tbl_products.short_description',
                    'tbl_products.description',
                    'tbl_products.cpt_code',
                    'tbl_products.test_details',
                    'tbl_products.including_test',
                    'tbl_products.faq_for_test',
                    'tbl_products.medicine_ingredients',
                    'tbl_products.stock_status',
                    'tbl_products.medicine_warnings',
                    'tbl_products.medicine_directions',
                    'tbl_products.is_otc',

                    'vendor_products.vendor_id',
                    'vendor_products.id as vendor_product_id',
                    'vendor_products.selling_price as sale_prices',
                    'vendor_products.actual_price',
                    'vendor_products.discount',
                    'vendor_products.available_stock',

                    'products_sub_categories.title as sub_category_name',
                    'products_sub_categories.slug as sub_category_slug',
                )
                ->where('tbl_products.product_status', 1)
                ->where('tbl_products.is_approved', 1)
                ->where('vendor_products.vendor_id', $vendor_id)
                ->where('vendor_products.is_active', 1)
                ->where('tbl_products.name', 'LIKE', $request->text . '%')
                ->groupBy(
                    'tbl_products.id',
                    'vendor_products.id',
                    'products_sub_categories.id'
                )
                ->paginate(10)->appends(
                    [
                        'id' => $vendor_id
                    ]
                );
            foreach ($products as $product) {
                $product->short_description = strip_tags($product->short_description);
                $product->featured_image = \App\Helper::check_bucket_files_url($product->featured_image);
                if($product->featured_image == env('APP_URL')."/assets/images/user.png"){
                    $product->featured_image = asset('assets/new_frontend/panadol2.png');
                }
            }
        } else if ($request->cat_id == 'all' && strlen($request->text) >= 4) {
            $products = DB::table('tbl_products')
                ->join('products_sub_categories', 'products_sub_categories.id', '=', 'tbl_products.sub_category')
                ->join('vendor_products', 'vendor_products.product_id', '=', 'tbl_products.id')
                ->join('vendor_accounts', 'vendor_accounts.id', '=', 'vendor_products.vendor_id')
                ->select(
                    'tbl_products.id',
                    'tbl_products.name',
                    'tbl_products.panel_name',
                    'tbl_products.slug',
                    'tbl_products.parent_category',
                    'tbl_products.sub_category',
                    'tbl_products.featured_image',
                    'tbl_products.gallery',
                    'tbl_products.tags',
                    'tbl_products.quantity',
                    'tbl_products.keyword',
                    'tbl_products.mode',
                    'tbl_products.medicine_type',
                    'tbl_products.is_featured',
                    'tbl_products.short_description',
                    'tbl_products.description',
                    'tbl_products.cpt_code',
                    'tbl_products.test_details',
                    'tbl_products.including_test',
                    'tbl_products.faq_for_test',
                    'tbl_products.medicine_ingredients',
                    'tbl_products.stock_status',
                    'tbl_products.medicine_warnings',
                    'tbl_products.medicine_directions',
                    'tbl_products.is_otc',

                    'vendor_products.vendor_id',
                    'vendor_products.id as vendor_product_id',
                    'vendor_products.selling_price as sale_prices',
                    'vendor_products.actual_price',
                    'vendor_products.discount',
                    'vendor_products.available_stock',

                    'products_sub_categories.title as sub_category_name',
                    'products_sub_categories.slug as sub_category_slug',
                )
                ->where('tbl_products.product_status', 1)
                ->where('tbl_products.is_approved', 1)
                ->where('vendor_products.vendor_id', $vendor_id)
                ->where('vendor_products.is_active', 1)
                ->where('tbl_products.name', 'LIKE', $request->text . '%')
                ->groupBy(
                    'tbl_products.id',
                    'vendor_products.id',
                    'products_sub_categories.id'
                )
                ->paginate(10)->appends(
                    [
                        'id' => $vendor_id
                    ]
                );
            foreach ($products as $product) {
                $product->short_description = strip_tags($product->short_description);
                $product->featured_image = \App\Helper::check_bucket_files_url($product->featured_image);
                if($product->featured_image == env('APP_URL')."/assets/images/user.png"){
                    $product->featured_image = asset('assets/new_frontend/panadol2.png');
                }
            }
        } else if ($request->cat_id != 'all' && strlen($request->text) >= 4) {
            $products = DB::table('tbl_products')
                ->join('products_sub_categories', 'products_sub_categories.id', '=', 'tbl_products.sub_category')
                ->join('vendor_products', 'vendor_products.product_id', '=', 'tbl_products.id')
                ->join('vendor_accounts', 'vendor_accounts.id', '=', 'vendor_products.vendor_id')
                ->select(
                    'tbl_products.id',
                    'tbl_products.name',
                    'tbl_products.panel_name',
                    'tbl_products.slug',
                    'tbl_products.parent_category',
                    'tbl_products.sub_category',
                    'tbl_products.featured_image',
                    'tbl_products.gallery',
                    'tbl_products.tags',
                    'tbl_products.quantity',
                    'tbl_products.keyword',
                    'tbl_products.mode',
                    'tbl_products.medicine_type',
                    'tbl_products.is_featured',
                    'tbl_products.short_description',
                    'tbl_products.description',
                    'tbl_products.cpt_code',
                    'tbl_products.test_details',
                    'tbl_products.including_test',
                    'tbl_products.faq_for_test',
                    'tbl_products.medicine_ingredients',
                    'tbl_products.stock_status',
                    'tbl_products.medicine_warnings',
                    'tbl_products.medicine_directions',
                    'tbl_products.is_otc',

                    'vendor_products.vendor_id',
                    'vendor_products.id as vendor_product_id',
                    'vendor_products.selling_price as sale_prices',
                    'vendor_products.actual_price',
                    'vendor_products.discount',
                    'vendor_products.available_stock',

                    'products_sub_categories.title as sub_category_name',
                    'products_sub_categories.slug as sub_category_slug',
                )
                ->where('tbl_products.product_status', 1)
                ->where('tbl_products.is_approved', 1)
                ->where('vendor_products.vendor_id', $vendor_id)
                ->where('vendor_products.is_active', 1)
                ->where('tbl_products.name', 'LIKE', $request->text . '%')
                ->groupBy(
                    'tbl_products.id',
                    'vendor_products.id',
                    'products_sub_categories.id'
                )
               ->paginate(10)->appends(
                    [
                        'id' => $vendor_id
                    ]
                );
            foreach ($products as $product) {
                $product->short_description = strip_tags($product->short_description);
                $product->featured_image = \App\Helper::check_bucket_files_url($product->featured_image);
                if($product->featured_image == env('APP_URL')."/assets/images/user.png"){
                    $product->featured_image = asset('assets/new_frontend/panadol2.png');
                }
            }
        }


        return $this->sendResponse([
            'products' => $products,
        ], 'Products retrieved successfully.');
    }

    public function searchLabItemByCategory(Request $request, $vendor_id)
    {
        if ($request->cat_id == 'all' && strlen($request->text) < 4) {
            $products = DB::table('quest_data_test_codes')
                ->Join('vendor_products', 'quest_data_test_codes.TEST_CD', '=', 'vendor_products.product_id')
                ->join('vendor_accounts', 'vendor_accounts.id', '=', 'vendor_products.vendor_id')
                ->select(
                    'vendor_products.vendor_id',
                    'vendor_products.id AS vendor_product_id',
                    'vendor_products.selling_price AS SALE_PRICE',
                    'vendor_products.actual_price  AS actual_price',
                    'vendor_products.discount AS discount_percentage',
                    'vendor_products.available_stock',
                    'quest_data_test_codes.TEST_CD AS id',
                    'quest_data_test_codes.TEST_NAME',
                    'quest_data_test_codes.DETAILS AS short_description',
                    'quest_data_test_codes.DETAILS AS description',
                    'quest_data_test_codes.DETAILS',
                    DB::raw('SLUG as slug'),
                    DB::raw('"quest_data_test_codes" as tbl_name')
                )
                ->where([
                    ['PARENT_CATEGORY', '!=', ""],
                    ['AOES_exist', null],
                ])
                ->where('vendor_products.vendor_id', $vendor_id)
                ->where('vendor_products.is_active', '1')
                ->where('TEST_NAME', 'LIKE', $request->text . '%')
                ->where('SALE_PRICE', '!=', null)
                ->where('AOES_exist', null)
                ->paginate(10)->appends(
                    [
                        'id' => $vendor_id
                    ]
                );
            foreach ($products as $product) {
                if ($product->discount_percentage != null) {
                    $product->actual_price = $product->SALE_PRICE;
                    $product->SALE_PRICE = $product->SALE_PRICE - ($product->SALE_PRICE * $product->discount_percentage / 100);
                }
            }
        } else if ($request->cat_id != 'all' && strlen($request->text) < 4) {

            $products = DB::table('quest_data_test_codes')
                ->Join('vendor_products', 'quest_data_test_codes.TEST_CD', '=', 'vendor_products.product_id')
                ->join('vendor_accounts', 'vendor_accounts.id', '=', 'vendor_products.vendor_id')
                ->select(
                    'vendor_products.vendor_id',
                    'vendor_products.id AS vendor_product_id',
                    'vendor_products.selling_price AS SALE_PRICE',
                    'vendor_products.actual_price  AS actual_price',
                    'vendor_products.discount AS discount_percentage',
                    'vendor_products.available_stock',
                    'quest_data_test_codes.TEST_CD AS id',
                    'quest_data_test_codes.TEST_NAME',
                    'quest_data_test_codes.DETAILS AS short_description',
                    'quest_data_test_codes.DETAILS AS description',
                    'quest_data_test_codes.DETAILS',
                    DB::raw('SLUG as slug'),
                    DB::raw('"quest_data_test_codes" as tbl_name')
                )
                ->where([
                    ['PARENT_CATEGORY', '!=', ""],
                    ['AOES_exist', null],
                ])
                ->where('vendor_products.vendor_id', $vendor_id)
                ->where('vendor_products.is_active', '1')
                ->where('TEST_NAME', 'LIKE', $request->text . '%')
                ->where('SALE_PRICE', '!=', null)
                ->where('AOES_exist', null)
                ->paginate(10)->appends(
                    [
                        'id' => $vendor_id
                    ]
                );
            foreach ($products as $product) {
                if ($product->discount_percentage != null) {
                    $product->actual_price = $product->SALE_PRICE;
                    $product->SALE_PRICE = $product->SALE_PRICE - ($product->SALE_PRICE * $product->discount_percentage / 100);
                }
            }
        } else if ($request->cat_id == 'all' && strlen($request->text) >= 4) {
            $products = DB::table('quest_data_test_codes')
                ->Join('vendor_products', 'quest_data_test_codes.TEST_CD', '=', 'vendor_products.product_id')
                ->join('vendor_accounts', 'vendor_accounts.id', '=', 'vendor_products.vendor_id')
                ->select(
                    'vendor_products.vendor_id',
                    'vendor_products.id AS vendor_product_id',
                    'vendor_products.selling_price AS SALE_PRICE',
                    'vendor_products.actual_price  AS actual_price',
                    'vendor_products.discount AS discount_percentage',
                    'vendor_products.available_stock',
                    'quest_data_test_codes.TEST_CD AS id',
                    'quest_data_test_codes.TEST_NAME',
                    'quest_data_test_codes.DETAILS',
                    'quest_data_test_codes.DETAILS AS short_description',
                    'quest_data_test_codes.DETAILS AS description',
                    DB::raw('SLUG as slug'),
                    DB::raw('"quest_data_test_codes" as tbl_name')
                )
                ->where([
                    ['PARENT_CATEGORY', '!=', ""],
                    ['AOES_exist', null],
                ])
                ->where('vendor_products.vendor_id', $vendor_id)
                ->where('vendor_products.is_active', '1')
                ->where('TEST_NAME', 'LIKE', $request->text . '%')
                ->where('SALE_PRICE', '!=', null)
                ->where('AOES_exist', null)
                ->limit(10)
                ->paginate(10)->appends(
                    [
                        'id' => $vendor_id
                    ]
                );
            foreach ($products as $product) {
                if ($product->discount_percentage != null) {
                    $product->actual_price = $product->SALE_PRICE;
                    $product->SALE_PRICE = $product->SALE_PRICE - ($product->SALE_PRICE * $product->discount_percentage / 100);
                }
            }
        } else if ($request->cat_id != 'all' && strlen($request->text) >= 4) {
            $products = DB::table('quest_data_test_codes')
                ->Join('vendor_products', 'quest_data_test_codes.TEST_CD', '=', 'vendor_products.product_id')
                ->join('vendor_accounts', 'vendor_accounts.id', '=', 'vendor_products.vendor_id')
                ->select(
                    'vendor_products.vendor_id',
                    'vendor_products.id AS vendor_product_id',
                    'vendor_products.selling_price AS SALE_PRICE',
                    'vendor_products.actual_price  AS actual_price',
                    'vendor_products.discount AS discount_percentage',
                    'vendor_products.available_stock',
                    'quest_data_test_codes.TEST_CD AS id',
                    'quest_data_test_codes.TEST_NAME',
                    'quest_data_test_codes.DETAILS AS short_description',
                    'quest_data_test_codes.DETAILS AS description',
                    'quest_data_test_codes.DETAILS',
                    DB::raw('SLUG as slug'),
                    DB::raw('"quest_data_test_codes" as tbl_name')
                )
                ->where([
                    ['PARENT_CATEGORY', '!=', ""],
                    ['AOES_exist', null],
                ])
                ->where('vendor_products.vendor_id', $vendor_id)
                ->where('vendor_products.is_active', '1')
                ->where('TEST_NAME', 'LIKE', $request->text . '%')
                ->where('SALE_PRICE', '!=', null)
                ->where('AOES_exist', null)
                ->paginate(10)->appends(
                    [
                        'id' => $vendor_id
                    ]
                );
            foreach ($products as $product) {
                if ($product->discount_percentage != null) {
                    $product->actual_price = $product->SALE_PRICE;
                    $product->SALE_PRICE = $product->SALE_PRICE - ($product->SALE_PRICE * $product->discount_percentage / 100);
                }
            }
        }
        if (Auth::check()) {
            $user_id = Auth::user()->id;
        } else {
            $user_id = '';
        }

        if (empty($products)) {
            return $this->sendError('No products found for the specified criteria.', []);
        }else{
            return $this->sendResponse([
                'products' => $products,
                'user_id' => $user_id
            ], 'Products retrieved successfully.');
        }
    }

}
