<?php

namespace App;

use App\Models\AllProducts;
use App\Models\ProductCategory;
use App\Models\ProductsSubCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Pharmacy extends Model
{

    protected $tbl_Categories = 'product_categories';
    protected $tbl_SubCategories = 'products_sub_categories';
    protected $tbl_Products = 'tbl_products';
    protected $tbl_cart = 'tbl_cart';
    protected $tbl_maps = 'tbl_map_markers';
    protected $tbl_prescriptions = 'prescriptions';
    protected $tbl_faq = 'tbl_faq';
    protected $tbl_users = 'users';
    protected $tbl_orders = 'tbl_orders';
    protected $tbl_zip_codes_cities = 'tbl_zip_codes_cities';

    public function get_data_cart_page_by_user_id($user_id, $text, $forCheckout)
    {
        DB::table('tbl_cart')
            ->leftJoin('prescriptions', 'tbl_cart.pres_id', '=', 'prescriptions.id')
            ->leftJoin('tbl_products', 'tbl_cart.product_id', '=', 'tbl_products.id')
            ->leftJoin('tbl_map_markers', 'tbl_cart.map_marker_id', '=', 'tbl_map_markers.id')
            ->leftJoin('users', 'tbl_cart.doc_id', '=', 'users.id')
            ->select(
                "tbl_cart.id as cart_row_id",
                "tbl_products.tags as TEST_CD",
                "tbl_cart.product_id",
                "tbl_cart.show_product",
                "tbl_cart.name",
                "tbl_cart.quantity",
                "tbl_cart.price",
                "tbl_cart.update_price",
                "tbl_cart.product_mode",
                DB::raw("CASE WHEN (tbl_products.featured_image IS NULL ) THEN 'default-labtest.jpg' ELSE tbl_products.featured_image END AS product_image"),
                "tbl_cart.item_type",
                "tbl_cart.pres_id",
                "tbl_cart.doc_session_id",
                "tbl_cart.location_id",
                "tbl_cart.status",
                "tbl_cart.purchase_status",
                "tbl_cart.refill_flag",
                "tbl_cart.checkout_status",
                "prescriptions.created_at as prescription_date",
                DB::raw("CONCAT(prescriptions.usage,' (', med_unit,')') AS medicine_usage"),
                DB::raw("CONCAT(users.name,' ', users.last_name) AS prescribed_by"),
                DB::raw("CONCAT(tbl_map_markers.name,',',tbl_map_markers.address,'|', tbl_map_markers.zip_code) AS zip_code_location")
            )
            ->where('tbl_cart.user_id', $user_id)
            ->where('tbl_cart.status', '!=', 'purchased')
            ->when($text == 'prescribed', function ($query) {
                return $query->where('tbl_cart.status', 'recommended');
            })
            ->when($forCheckout == 'checkout', function ($query) {
                return $query->where('tbl_cart.checkout_status', 1);
            })
            ->get();

        $cartPage = DB::table($this->tbl_cart)
            ->leftJoin($this->tbl_prescriptions, $this->tbl_cart . '.pres_id', '=', $this->tbl_prescriptions . '.id')
            ->leftJoin($this->tbl_Products, $this->tbl_cart . '.product_id', '=', $this->tbl_Products . '.id')
            ->leftJoin($this->tbl_maps, $this->tbl_cart . '.map_marker_id', '=', $this->tbl_maps . '.id')
            ->leftJoin($this->tbl_users, $this->tbl_cart . '.doc_id', '=', $this->tbl_users . '.id')
            ->select(
                $this->tbl_cart . ".id as cart_row_id",
                $this->tbl_Products . ".tags as TEST_CD",
                $this->tbl_cart . ".product_id",
                $this->tbl_cart . ".show_product",
                $this->tbl_cart . ".name",
                $this->tbl_cart . ".quantity",
                $this->tbl_cart . ".price",
                $this->tbl_cart . ".update_price",
                $this->tbl_cart . ".product_mode",
                DB::raw("CASE WHEN (tbl_products.featured_image IS NULL ) THEN 'default-labtest.jpg' ELSE tbl_products.featured_image END AS product_image"),
                $this->tbl_cart . ".item_type",
                $this->tbl_cart . ".pres_id",
                $this->tbl_cart . ".doc_session_id",
                $this->tbl_cart . ".location_id",
                $this->tbl_cart . ".status",
                $this->tbl_cart . ".purchase_status",
                $this->tbl_cart . ".refill_flag",
                $this->tbl_cart . ".checkout_status",
                "prescriptions.created_at as prescription_date",
                DB::raw("CONCAT(prescriptions.usage,' (', med_unit,')') AS medicine_usage"),
                DB::raw("CONCAT(" . $this->tbl_users . ".name,' ', " . $this->tbl_users . ".last_name) AS prescribed_by"),
                DB::raw("CONCAT(" . $this->tbl_maps . ".name,',', " . $this->tbl_maps . ".address,'|', " . $this->tbl_maps . ".zip_code) AS zip_code_location")
            )
            ->where($this->tbl_cart . '.user_id', $user_id)
            ->where($this->tbl_cart . '.purchase_status', '1')
            ->when($text == 'prescribed', function ($query) {
                return $query->where($this->tbl_cart . '.status', 'recommended');
            })
            ->when($forCheckout == 'checkout', function ($query) {
                return $query->where($this->tbl_cart . '.checkout_status', 1);
            })->get();
        return $cartPage;
    }

    public function getMainCategory($type)
    {
        $data = DB::table($this->tbl_Categories)
            ->select('id', 'name AS product_parent_category', 'slug', 'thumbnail')
            ->where('category_type', $type)
            ->when($type == 'medicine', function ($query) {
                return $query->where('id', '38');
            })
            ->orderBy('id', 'asc')
            ->whereNotIn('id', ['27', '29', '43'])
            ->get();

        return $data;
    }
    public function getSubCategories($getNames)
    {
        $data['sideMenus'] = array();
        foreach ($getNames as &$val) {
            $sub = DB::table($this->tbl_SubCategories)->select('id', 'title', 'slug', 'thumbnail')
                ->where('parent_id', $val->id)
                ->orderby('title', 'asc')
                ->get();
            foreach ($sub as &$val2) {
                $data['sideMenus'][$val->product_parent_category . '|' . $val->slug][] = $val2->id . '|' . $val2->title . '|' . $val2->slug . '|' . $val2->thumbnail;
            }
        }
        return $data;
    }
    public function getMedPrescribeSubCategories()
    {
        $cat = DB::table('products_sub_categories')->where('parent_id', 38)->orderBy('title', 'asc')->get();
        return $cat;
    }

    public function getMainCategoryHomePage($type)
    {
        $data = DB::table($this->tbl_Categories)
            ->select('id', 'name AS product_parent_category', 'slug', 'thumbnail')
            ->where('category_type', $type)
            ->when($type == 'medicine', function ($query) {
                return $query->where('id', '38');
            })
            ->orderBy('name', 'desc')
            ->whereNotIn('id', ['27', '29', '43'])
            ->limit(5)
            ->get();
        return $data;
    }

    public function searchDropdownSubCategory($modeType)
    {
        $subCategories = ProductsSubCategory::select('*', 'title as product_parent_category')
            ->where('parent_id', '38')
            ->orderBy('title', 'asc')->get();
        return $subCategories;
    }

    public function getMainCategoryHome()
    {
        $data = DB::table($this->tbl_Categories)->select('id', 'name AS product_parent_category', 'slug', 'thumbnail')->get();
        return $data;
    }


    public function getProductBySlugCommaIds($slug, $mode)
    {
        // dd($slug);
        if ($mode == 'medicine') {
            $get_cat_id = DB::table($this->tbl_SubCategories)->select('id')
                ->where('slug', '=', $slug)
                ->get();
            $cat_id = $get_cat_id[0]->id;
            // $data = DB::table($this->tbl_Products)
            //     ->leftJoin('product_categories', 'tbl_products.parent_category', '=', 'product_categories.id')
            //     ->leftJoin('products_sub_categories', 'tbl_products.sub_category', '=', 'products_sub_categories.id')
            //     ->select(
            //         'tbl_products.*',
            //         'product_categories.name as main_category_name',
            //         'product_categories.slug as main_category_slug',
            //         'products_sub_categories.title as sub_category_name',
            //         'products_sub_categories.slug as sub_category_slug'
            //     )
            //     ->whereRaw("find_in_set('$cat_id',`sub_category`)")
            //     ->where('product_status', 1)
            //     ->where('is_approved', 1)
            //     ->orderBy('name', 'asc')
            //     ->paginate(12);
            $data = DB::table('tbl_products')
                ->join('products_sub_categories', 'products_sub_categories.id', 'tbl_products.sub_category')
                ->join('medicine_pricings', 'medicine_pricings.product_id', 'tbl_products.id')
                ->select(
                    'tbl_products.*',
                    'products_sub_categories.title as sub_category_name',
                    'products_sub_categories.slug as sub_category_slug',
                    DB::raw('MIN(medicine_pricings.sale_price) as sale_prices')
                )
                ->whereRaw("find_in_set('$cat_id',`sub_category`)")
                ->groupBy('tbl_products.id', 'products_sub_categories.title', 'products_sub_categories.slug') // group by product and category fields
                ->paginate(12);
            foreach ($data as $key => $product) {
                $product->featured_image = \App\Helper::check_bucket_files_url($product->featured_image);
                if ($product->featured_image == env('APP_URL') . "/assets/images/user.png") {
                    $product->featured_image = asset('assets/new_frontend/panadol2.png');
                }
            }
            return $data;
        } elseif ($mode == 'substance-abuse') {
            $get_cat_id = DB::table($this->tbl_SubCategories)->select('id')
                ->get();
            $data = DB::table($this->tbl_Products)
                ->leftJoin('product_categories', 'tbl_products.parent_category', '=', 'product_categories.id')
                ->leftJoin('products_sub_categories', 'tbl_products.sub_category', '=', 'products_sub_categories.id')
                ->select(
                    'tbl_products.*',
                    'product_categories.name as main_category_name',
                    'product_categories.slug as main_category_slug',
                    'products_sub_categories.title as sub_category_name',
                    'products_sub_categories.slug as sub_category_slug'
                )
                ->whereRaw("find_in_set('18',`parent_category`)")
                ->where('product_status', 1)
                ->where('is_approved', 1)
                ->orderBy('name', 'asc')
                ->get(12);
            return $data;
        } elseif ($mode == 'pain-management') {
            $get_cat_id = DB::table($this->tbl_SubCategories)->select('id')
                ->where('slug', '=', $slug)
                ->get();
            $cat_id = $get_cat_id[0]->id;
            $data = DB::table($this->tbl_Products)
                ->leftJoin('product_categories', 'tbl_products.parent_category', '=', 'product_categories.id')
                ->leftJoin('products_sub_categories', 'tbl_products.sub_category', '=', 'products_sub_categories.id')
                ->select(
                    'tbl_products.*',
                    'product_categories.name as main_category_name',
                    'product_categories.slug as main_category_slug',
                    'products_sub_categories.title as sub_category_name',
                    'products_sub_categories.slug as sub_category_slug'
                )
                ->whereRaw("find_in_set('$cat_id',`sub_category`)")
                ->where('product_status', 1)
                ->where('is_approved', 1)
                ->orderBy('name', 'asc')
                ->get(12);
            return $data;
        } elseif ($mode == 'psychiatry') {
            $get_cat_id = DB::table($this->tbl_SubCategories)->select('id')
                ->get();
            $data = DB::table($this->tbl_SubCategories)->where('parent_id', 44)->get();
            return $data;
        }
        $get_cat_id = DB::table($this->tbl_Categories)->select('id')
            ->where('slug', '=', $slug)
            ->where('category_type', $mode)
            ->get();
        $cat_id = $get_cat_id[0]->id;
        if ($mode == 'lab-test') {

            $data = DB::table('quest_data_test_codes')
                ->select(
                    'TEST_CD AS id',
                    'TEST_NAME AS name',
                    'SALE_PRICE AS sale_price',
                    'DETAILS AS short_description',
                    'DETAILS AS description',
                    'actual_price AS actual_price',
                    'discount_percentage AS discount_percentage',
                    DB::raw('SLUG as slug'),
                    DB::raw('"quest_data_test_codes" as tbl_name')
                )->where([
                        ['PARENT_CATEGORY', '!=', ""],
                        ['AOES_exist', null],
                        ['DETAILS', '!=', ""],
                        ['SALE_PRICE', '!=', ""],
                    ])->whereRaw("find_in_set('$cat_id',`PARENT_CATEGORY`)")
                // ->union($first)
                ->orderBy('name', 'ASC')
                ->paginate(10);
        } elseif ($mode == 'imaging') {
            $data = DB::table('quest_data_test_codes')
                ->select(
                    'TEST_CD AS id',
                    'TEST_NAME AS name',
                    'SALE_PRICE AS sale_price',
                    'DETAILS AS short_description',
                    'DETAILS AS description',
                    'actual_price AS actual_price',
                    'discount_percentage AS discount_percentage',
                    DB::raw('SLUG as slug'),
                    DB::raw('"quest_data_test_codes" as tbl_name')
                )->where([
                        ['PARENT_CATEGORY', '!=', ""],
                        ['AOES_exist', null],
                        ['DETAILS', '!=', ""],
                        ['SALE_PRICE', '!=', ""],
                    ])->whereRaw("find_in_set('$cat_id',`PARENT_CATEGORY`)")
                // ->union($first)
                ->orderBy('name', 'ASC')
                ->paginate(10);
        }
        return $data;
    }

    public function getProductOrderByDesc($modeType, $vendor_id)
    {

        if ($modeType == 'lab-test' || $modeType == 'imaging') {
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
                ->where('mode', $modeType)
                ->where('vendor_products.vendor_id', $vendor_id)
                ->where('vendor_products.is_active', '1')
                ->where('vendor_accounts.is_active', '1')
                ->orderBy('name', 'ASC')
                ->paginate(12);
                foreach ($data as $product) {
                    if($product->discount_percentage != null){
                        $product->actual_price = $product->sale_price;
                        $product->sale_price = $product->sale_price - ($product->sale_price * $product->discount_percentage / 100);
                    }
                }
        // } elseif ($modeType == 'imaging') {
        //     $data = DB::table('quest_data_test_codes')
        //         ->select(
        //             'TEST_CD AS id',
        //             'TEST_NAME AS name',
        //             'SALE_PRICE AS sale_price',
        //             'DETAILS AS short_description',
        //             'DETAILS AS description',
        //             'actual_price AS actual_price',
        //             'discount_percentage AS discount_percentage',
        //             DB::raw('SLUG as slug'),
        //             DB::raw('"quest_data_test_codes" as tbl_name')
        //         )
        //         ->where([
        //             ['PARENT_CATEGORY', '!=', ""],
        //             ['AOES_exist', null],
        //             ['DETAILS', '!=', ""], /* WILL REMOVE */
        //             ['SALE_PRICE', '!=', ""], /* WILL REMOVE */
        //         ])
        //         ->where('mode', $modeType)
        //         ->orderBy('name', 'ASC')
        //         ->paginate(12);
        //     //dd($data);
        } else {
            $data = DB::table('tbl_products')
                ->join('products_sub_categories', 'products_sub_categories.id', '=', 'tbl_products.sub_category')
                ->join('medicine_pricings', 'medicine_pricings.product_id', '=', 'tbl_products.id')
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

                    'vendor_products.vendor_id',
                    'vendor_products.id as vendor_product_id',
                    'vendor_products.selling_price as sale_price',
                    'vendor_products.actual_price',
                    'vendor_products.discount',
                    'vendor_products.available_stock',

                    'products_sub_categories.title as sub_category_name',
                    'products_sub_categories.slug as sub_category_slug',

                    DB::raw('MIN(medicine_pricings.sale_price) as sale_prices')
                )
                ->where('tbl_products.product_status', 1)
                ->where('tbl_products.is_approved', 1)
                ->where('vendor_products.vendor_id', $vendor_id)
                ->where('vendor_products.is_active', 1)
                ->where('vendor_accounts.is_active', 1)
                ->groupBy(
                    'tbl_products.id',
                    'vendor_products.id',
                    'products_sub_categories.id'
                )
                ->orderBy('tbl_products.name', 'asc')
                ->paginate(12);

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

    public function getSingleProduct($type, $slug)
    {

        if ($type == 'labtests') {

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
                    'quest_data_test_codes.mode',
                    DB::raw('SLUG as slug'),
                    DB::raw('"quest_data_test_codes" as tbl_name')
                )
                ->where([
                    ['quest_data_test_codes.PARENT_CATEGORY', '!=', ""],
                    // ['quest_data_test_codes.DETAILS', '!=', ""], /* WILL REMOVE */
                ])
                // ->union($first)
                ->where([['quest_data_test_codes.slug', $slug]])
                ->get();
                foreach ($data as $product) {
                    if($product->discount_percentage != null){
                        $product->actual_price = $product->sale_price;
                        $product->sale_price = $product->sale_price - ($product->sale_price * $product->discount_percentage / 100);
                    }
                }
            // dd($data);
        } else {
            $data = DB::table('tbl_products')
                ->join('products_sub_categories', 'products_sub_categories.id', '=', 'tbl_products.sub_category')
                ->join('medicine_pricings', 'medicine_pricings.product_id', '=', 'tbl_products.id')
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

                    'vendor_products.vendor_id',
                    'vendor_products.id as vendor_product_id',
                    'vendor_products.selling_price as sale_price',
                    'vendor_products.actual_price',
                    'vendor_products.discount',
                    'vendor_products.available_stock',

                    'products_sub_categories.title as sub_category_name',
                    'products_sub_categories.slug as sub_category_slug',
                )
                ->where('tbl_products.slug', '=', $slug)
                ->where('product_status', 1)
                ->where('is_approved', 1)
                ->get();
        }

        return $data;
    }

    public function get_product_detail_for_cart($product_id, $quantity = '', $mode)
    {
        if ($mode == 'quest_data_test_codes') {
            $getProductMetaData = DB::table('quest_data_test_codes')
                ->select(
                    'TEST_CD AS product_id',
                    'mode',
                    'TEST_NAME AS name',
                    'SALE_PRICE AS sale_price',
                    'actual_price AS actual_price',
                    'discount_percentage AS discount_percentage',
                    'featured_image',
                    DB::raw('"quest_data_test_codes" as tbl_name')
                )
                ->where([['TEST_CD', $product_id]])
                ->get();
        } else {
            $getProductMetaData = DB::table($this->tbl_Products)->select('id as product_id', 'name', 'sale_price', 'mode', 'featured_image')
                ->where('id', $product_id)
                ->where('quantity', '>=', (int) $quantity)
                ->get();
        }

        if (count($getProductMetaData) > 0) {
            $getProductMeta = $getProductMetaData[0];

            $product_id = $getProductMeta->product_id;
            $product_name = $getProductMeta->name;
            $product_quantity = empty($quantity) ? 1 : $quantity;
            $product_sale_price = $getProductMeta->sale_price;
            $product_mode = $getProductMeta->mode;
            $product_image = $getProductMeta->featured_image;
            $item_type = 'session';
            $medicine_usage = '';
            $prescribed_by = '';
            $pres_id = '';
            $doc_session_id = '';
            $zip_code_location = '';

            $data = [
                'cart_row_id' => rand(),
                'product_id' => $product_id,
                'name' => $product_name,
                'quantity' => $product_quantity,
                'price' => $product_sale_price,
                'update_price' => $product_sale_price,
                'product_mode' => $product_mode,
                'product_image' => $product_image,
                'item_type' => $item_type,
                'medicine_usage' => $medicine_usage,
                'pres_id' => $pres_id,
                'doc_session_id' => $doc_session_id,
                'prescribed_by' => $prescribed_by,
                'zip_code_location' => $zip_code_location,
            ];
            return $data;
        } else {
            $data = [];
            return $data;
        }

        //dd($data);
    }

    public function get_cart_counter_by_user($user_id)
    {
        $cartCount = DB::table($this->tbl_cart)
            ->select(DB::raw('count(*) as cartTotalItems'))
            ->where('user_id', '=', $user_id)
            ->where('purchase_status', 1)
            ->get();
        return $cartCount[0]->cartTotalItems;
    }

    public function update_product_quantity_in_tbl_cart($session_id, $product_id, $product_quantity)
    {
        $updateQty = DB::table($this->tbl_cart)
            ->where('product_id', $product_id)
            ->where('session_id', $session_id)
            ->update(['quantity' => $product_quantity]);

        return $updateQty;
    }

    public function get_data_cart_page($session_id)
    {
        $cartPage = DB::table($this->tbl_cart)
            ->where('session_id', $session_id)
            ->get();
        return $cartPage;
    }

    public function get_lat_long_of_zipcode($zipCode)
    {
        $var = '%' . $zipCode . '%';
        $query = DB::table($this->tbl_maps)->where('zip_code', '=', $zipCode)->get();
        return $query;
    }
    public function get_lat_long_of_zipcode_imaging($zipCode)
    {
        $var = '%' . $zipCode . '%';
        $query = DB::table('imaging_locations')->where('zip_code', '=', $zipCode)->get();
        return $query;
    }

    public function get_nearby_places($lat, $long)
    {
        // Km
        // $q = 'SELECT * FROM ( SELECT *, ( ( ( ACOS( SIN(( '.$lat.' * PI() / 180)) * SIN(( lat * PI() / 180)) + COS(( '.$lat.' * PI() /180 )) * COS(( lat * PI() / 180)) * COS((( '.$long.' - `long`) * PI()/180))) ) * 180/PI() ) * 60 * 1.1515 ) AS distance FROM `tbl_map_markers` ) `tbl_map_markers` WHERE distance <= 150 LIMIT 10;';

        // Miles
        $q = 'SELECT * FROM ( SELECT *, ( ( ( ACOS( SIN(( ' . $lat . ' * PI() / 180)) * SIN(( lat * PI() / 180)) + COS(( ' . $lat . ' * PI() /180 )) * COS(( lat * PI() / 180)) * COS((( ' . $long . ' - `long`) * PI()/180))) ) * 180/PI() ) * 60 * 1.1515 ) AS distance FROM `tbl_map_markers` ) `tbl_map_markers` WHERE distance <= 150 LIMIT 10;';
        $data = DB::select($q);

        return $data;
    }
    public function get_nearby_places_imaging($lat, $long)
    {
        // Km
        //  $q = 'SELECT * FROM ( SELECT *, ( ( ( ACOS( SIN(( '.$lat.' * PI() / 180)) * SIN(( lat * PI() / 180)) + COS(( '.$lat.' * PI() /180 )) * COS(( lat * PI() / 180)) * COS((( '.$long.' - `long`) * PI()/180))) ) * 180/PI() ) * 60 * 1.1515 ) AS distance FROM `imaging_locations` ) `imaging_locations` WHERE distance <= 10000 LIMIT 10;';

        // Miles
        $q = 'SELECT * FROM ( SELECT *, ( ( ( ACOS( SIN(( ' . $lat . ' * PI() / 180)) * SIN(( lat * PI() / 180)) + COS(( ' . $lat . ' * PI() /180 )) * COS(( lat * PI() / 180)) * COS((( ' . $long . ' - `long`) * PI()/180))) ) * 180/PI() ) * 60 * 1.1515 ) AS distance FROM `imaging_locations` ) `imaging_locations` WHERE distance <= 100;';
        $data = DB::select($q);
        return $data;
    }



    public function getCartItemsTotal($user_id)
    {
        $total = DB::table('tbl_cart')
            ->where('user_id', $user_id)
            ->where('purchase_status', 1)
            ->sum('update_price');
        return $total;
    }
    public function new_product_quantity_update($data)
    {
        foreach ($data as $item) {
            if ($item->product_mode != 'lab-test') {
                $get_qty = DB::table($this->tbl_Products)->select('id', 'quantity')->where('id', $item->product_id)->first();
                $old_qty = $get_qty->quantity;
                $new_qty = $old_qty - $item->quantity;
                DB::table($this->tbl_Products)->where('id', $item->product_id)->update(['quantity' => $new_qty]);
            }
        }
        return 1;
    }
    public function product_quantity_update($data)
    {
        foreach ($data as $item) {
            $product_id = $item['product_id'];
            $product_mode = $item['product_mode'];
            if ($product_mode != 'lab-test') {
                $get_qty = DB::table($this->tbl_Products)
                    ->select('id', 'quantity')
                    ->where('id', '=', $product_id)
                    ->get();
                $old_qty = $get_qty[0]->quantity;
                $new_qty = $old_qty - $item['quantity'];
                DB::table($this->tbl_Products)
                    ->where('id', '=', $product_id)
                    ->update(['quantity' => $new_qty]);
            }
        }
        return 1;
    }

    public function searchProducts($keyword, $modeType, $categoryID)
    {
        $length = strlen($keyword);
        if ($length > 3) {
            $var = '%' . $keyword . '%';
        } else {
            $var = $keyword . '%';
        }

        $data = DB::table($this->tbl_Products)
            ->leftJoin('product_categories', 'tbl_products.parent_category', '=', 'product_categories.id')
            ->leftJoin('products_sub_categories', 'tbl_products.sub_category', '=', 'products_sub_categories.id')
            ->select(
                'tbl_products.*',
                'product_categories.name as main_category_name',
                'product_categories.slug as main_category_slug',
                'products_sub_categories.title as sub_category_name',
                'products_sub_categories.slug as sub_category_slug',
                DB::raw('CONCAT("$",FORMAT(tbl_products.sale_price, 2)) AS sale_price')
            )
            ->where('tbl_products.mode', $modeType)
            ->where('tbl_products.name', 'LIKE', $var)
            ->where('tbl_products.product_status', 1)
            ->where('tbl_products.is_approved', 1)
            ->when($modeType == 'medicine' && $categoryID > 0, function ($query) {
                return $query->where('tbl_products.sub_category', request()->categoryID);
            })
            ->when($modeType == 'lab-test', function ($query) {
                return $query->where([['tbl_products.sale_price', '!=', '0'], ['tbl_products.tags', '!=', ""]]);
            })
            ->when($modeType == 'lab-test' && $categoryID > 0, function ($query) {
                $categoryID = request()->categoryID;
                return $query->whereRaw("find_in_set('$categoryID',`parent_category`)");
            })
            ->when($modeType == 'imaging' && $categoryID > 0, function ($query) {
                $categoryID = request()->categoryID;
                return $query->whereRaw("find_in_set('$categoryID',`parent_category`)");
            })
            ->whereNotIn('tbl_products.mode', ['substance-abuse'])
            ->get();

        return $data;
    }

    public function getLabtestbyCategoryID($category_id, $paginate)
    {
        $data = DB::table('quest_data_test_codes')
            ->select(
                'TEST_CD AS id',
                'TEST_NAME AS name',
                'SALE_PRICE AS sale_price',
                'DETAILS AS short_description',
                'DETAILS AS description',
                'actual_price AS actual_price',
                'discount_percentage AS discount_percentage',
                DB::raw('SLUG as slug'),
                DB::raw('"quest_data_test_codes" as tbl_name')
            )
            ->where([
                ['PARENT_CATEGORY', '!=', ""],
                ['DETAILS', '!=', ""], /* WILL REMOVE */
                ['SALE_PRICE', '!=', ""], /* WILL REMOVE */
            ])
            ->whereRaw("find_in_set('$category_id',`PARENT_CATEGORY`)")
            ->orderBy('name', 'ASC')
            ->paginate($paginate);
        return $data;
    }

    public function searchProductLabtest($keyword, $modeType, $categoryID)
    {

        $length = strlen($keyword);
        if ($length > 3) {
            $var = '%' . $keyword . '%';
        } else {
            $var = $keyword . '%';
        }


        // $first = DB::table('tbl_products')
        //     ->leftJoin('product_categories', 'tbl_products.parent_category', '=', 'product_categories.id')
        //     ->select(
        //         'tbl_products.id',
        //         'tbl_products.mode',
        //         'tbl_products.name',
        //         'tbl_products.short_description',
        //         'tbl_products.description',
        //         'tbl_products.slug',
        //         DB::raw('"tbl_products" as tbl_name'),
        //         'product_categories.name as main_category_name',
        //         'product_categories.slug as main_category_slug',
        //         DB::raw('CONCAT("$",FORMAT(tbl_products.sale_price, 2)) AS sale_price'),
        //         'tbl_products.featured_image'
        //     )
        //     ->where('tbl_products.mode', $modeType)
        //     ->where('tbl_products.name', 'LIKE', $var)
        //     ->where('tbl_products.product_status', 1)
        //     ->where('tbl_products.is_approved', 1)
        //     ->when($categoryID > 0, function ($query) {
        //         $categoryID = request()->categoryID;
        //         return $query->whereRaw("find_in_set('$categoryID',`parent_category`)");
        //     });

        $data = DB::table('quest_data_test_codes')
            ->leftJoin('product_categories', 'quest_data_test_codes.PARENT_CATEGORY', '=', 'product_categories.id')
            ->select(
                'quest_data_test_codes.TEST_CD AS id',
                'quest_data_test_codes.mode',
                'quest_data_test_codes.TEST_NAME AS name',
                'quest_data_test_codes.DETAILS AS short_description',
                'quest_data_test_codes.DETAILS AS description',
                'quest_data_test_codes.actual_price AS actual_price',
                'quest_data_test_codes.discount_percentage AS discount_percentage',
                DB::raw('quest_data_test_codes.SLUG as slug'),
                DB::raw('"quest_data_test_codes" as tbl_name'),
                'product_categories.name as main_category_name',
                'product_categories.slug as main_category_slug',
                DB::raw('CONCAT("$",FORMAT(quest_data_test_codes.sale_price, 2)) AS sale_price'),
                'quest_data_test_codes.featured_image'
            )
            // ->union($first)
            ->where('AOES_exist', null)
            ->where('quest_data_test_codes.TEST_NAME', 'LIKE', $var)
            ->where([
                ['quest_data_test_codes.PARENT_CATEGORY', '!=', ""],
                ['quest_data_test_codes.DETAILS', '!=', ""], /* WILL REMOVE */
                ['quest_data_test_codes.SALE_PRICE', '!=', ""],
            ])
            ->when($categoryID > 0, function ($query) {
                $categoryID = request()->categoryID;
                return $query->whereRaw("find_in_set('$categoryID',`PARENT_CATEGORY`)");
            })->get();
        return $data;
    }

    public function get_country_states($country_id)
    {
        $query = DB::table($this->tbl_zip_codes_cities)->select('*')
            //->where('state_code', '=', $state_code)
            ->groupBy('state')
            ->orderBy('state', 'asc')
            ->get();
        return $query;
    }

    public function get_states_cities($state_code)
    {
        $query = DB::table('cities')->select('*')
            ->where('state_id', $state_code)
            // ->groupBy('city')
            ->orderBy('name', 'asc')
            ->get();
        return $query;
    }

    public function get_order_for_app($arr = "")
    {
        $query = DB::table($this->tbl_orders)->select('*')
            ->where($arr)->orderByDesc('id')
            ->get();

        return $query;
    }

    public static function get_created_products_by_id($user_id)
    {
        $allProducts = AllProducts::where('user_id', $user_id)->paginate(10);
        if ($allProducts != null) {
            foreach ($allProducts as $allProduct) {
                $cat_name = [];
                $allCategries = explode(',', $allProduct['parent_category']);
                foreach ($allCategries as $allCategrie) {

                    $get_cat_name = ProductCategory::where('id', $allCategrie)->select('name')->first()->toArray();
                    array_push($cat_name, $get_cat_name);
                }
                $allProduct->cat_names = $cat_name;
            }
        }

        return $allProducts;
    }

    public static function get_created_products($user_id)
    {
        $allProducts = AllProducts::where('user_id', $user_id)->get();
        if ($allProducts != null) {
            foreach ($allProducts as $allProduct) {
                $cat_name = [];
                $allCategries = explode(',', $allProduct['parent_category']);
                foreach ($allCategries as $allCategrie) {

                    $get_cat_name = ProductCategory::where('id', $allCategrie)->select('name')->first()->toArray();
                    array_push($cat_name, $get_cat_name);
                }
                $allProduct->cat_names = $cat_name;
            }
        }

        return $allProducts;
    }
    public static function get_created_product_categories($user_id)
    {
        return ProductCategory::where('created_by', $user_id)->get();
    }

    public static function get_created_product_categories_by_id($user_id)
    {
        return ProductCategory::where('created_by', $user_id)->paginate(10);
    }

    public static function get_created_product_sub_categories($user_id)
    {
        return ProductsSubCategory::where('created_by', $user_id)->get();
    }

    public static function get_created_product_sub_categories_by_id($user_id)
    {
        return ProductsSubCategory::where('created_by', $user_id)->paginate(10);
    }

    public function getProductsTotalForCheckout($userID)
    {
        $checkoutCount = DB::table($this->tbl_cart)
            ->select(DB::raw('sum(update_price) as cartTotalItems'))
            ->where('user_id', '=', $userID)
            ->where('purchase_status', 1)
            ->where('checkout_status', 1)
            ->get();
        return $checkoutCount[0]->cartTotalItems;
    }
    public function getProductBySlug($slug)
    {

        $check_slug = $this->checkSlugCategory($slug);

        $data = '';

        if ($check_slug == 2) {

            $get_sub_cat_id = DB::table($this->tbl_SubCategories)->select('id')->where('slug', '=', $slug)->get();
            $sub_cat_id = $get_sub_cat_id[0]->id;
            $data = DB::table($this->tbl_Products)
                ->join($this->tbl_SubCategories, $this->tbl_Products . '.sub_category', '=', $this->tbl_SubCategories . '.id')
                ->select($this->tbl_Products . '.*', $this->tbl_SubCategories . '.title as product_category')
                ->where($this->tbl_Products . '.sub_category', '=', $sub_cat_id)
                ->get();
        } elseif ($check_slug == 1) {
            $get_sub_cat_id = DB::table($this->tbl_Categories)->select('id')->where('slug', '=', $slug)->get();
            $sub_cat_id = $get_sub_cat_id[0]->id;
            $data = DB::table($this->tbl_Products)
                ->join($this->tbl_Categories, $this->tbl_Products . '.parent_category', '=', $this->tbl_Categories . '.id')
                ->select($this->tbl_Products . '.*', $this->tbl_Categories . '.name as product_category')
                ->where($this->tbl_Products . '.parent_category', '=', $sub_cat_id)
                ->get();
        }

        return $data;
    }
    public function checkSlugCategory($slug)
    {

        // check first in parent category
        $parent_category = DB::table($this->tbl_Categories)->select('id')->where('slug', '=', $slug)->get();

        if ($parent_category->count() > 0) {
            return 1;
        } else {
            $parent_sub_category = DB::table($this->tbl_SubCategories)->select('id')->where('slug', '=', $slug)->get();
            if ($parent_sub_category->count() > 0) {
                return 2;
            } else {
                return 0;
            }
        }
    }
}
//add new change
