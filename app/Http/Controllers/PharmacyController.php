<?php

namespace App\Http\Controllers;

use App\ActivityLog;
use App\City;
use App\Models\ProductsSubCategory;
use App\Events\CountCartItem;
use App\Events\RealTimeMessage;
use App\Http\Controllers\PaymentController;
use Flash;
use App\ImagingOrder;
use App\ImagingPrices;
use App\LabOrder;
use App\Mail\AdviyatOrderEmail;
use App\Mail\OrderConfirmationEmail;
use App\Models\AllProducts;
use App\Models\TblTransaction;
use App\Notification;
use App\Pharmacy;
use App\Prescription;
use App\RefillRequest;
use App\QuestDataAOE;
use App\QuestLab;
use App\Repositories\AllProductsRepository;
use App\Session as SessionModel;
use App\State;
use App\TblCart as AppTblCart;
use App\User;
use App\VendorAccount;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PharmacyController extends Controller
{
    private $Pharmacy;
    private $allProductsRepository;

    public function __construct(Pharmacy $Pharmacy, AllProductsRepository $allProductsRepo)
    {
        $this->Pharmacy = $Pharmacy;
        $this->allProductsRepository = $allProductsRepo;
    }
    public function healthTopic()
    {
        $data = DB::table('mental_conditions')->get();
        $content = DB::table('mental_conditions')->limit(3)->get();

        return view('website_pages.substance.health_topic', compact('data', 'content'));
    }

    public function healthTopicSingle($name)
    {
        // $data = DB::table('mental_conditions')->get();
        $content = DB::table('mental_conditions')
            ->where('name', $name)
            ->first();

        return view('website_pages.substance.single_view', compact('content'));
    }

    public function therapy_single($slug){
        if($slug == "view"){
            $data = DB::table('product_categories')
            ->where('name', '=', 'therapy')
            ->first();
        }else{
            $data = DB::table('product_categories')
            ->where('name', '=', 'therapy')
            ->first();

            $data->sub = DB::table('products_sub_categories')
            ->where('slug', '=', $slug)
            ->orderby('id','desc')
            ->first();
        }
        $data->therapy = ProductsSubCategory::select('id', 'slug', 'title', 'thumbnail')
        ->where('parent_id', '58')
        ->orderBy('title', 'asc')
        ->get();
        $url = url()->current();
        $tags = DB::table('meta_tags')->where('url',$url)->get();
        $title = DB::table('meta_tags')->where('url',$url)->where('name','title')->first();
        return view('website_pages.therapy-session.single_view',compact('data','tags','title'));
    }

    // PRODUCTS & E-COMMERCE

    public function index(Request $request, $slug = '')
    {
        $vendor_id = $request->query('id');

        $pageName = $request->segment(1);

        $slug_name = "";
        // For Categories
        if ($pageName == 'pharmacy') {
            $modeType = 'medicine';
            $sideMenus = $this->Pharmacy->getMedPrescribeSubCategories();
            $viewName = "website_pages.pharmacy.new_pakistan_index";
        } elseif ($pageName == 'labtests') {
            $modeType = 'lab-test';
            $sideMenus = $this->Pharmacy->getMainCategory($modeType);
            $viewName = 'website_pages.lab-test.new_pakistan_index';
        } elseif ($pageName == "imaging") {
            $modeType = "imaging";
            $sideMenus = $this->Pharmacy->getMainCategory($modeType);
            $viewName = 'website_pages.imaging.new_pakistan_index';
        } elseif ($pageName == "substance-abuse") {
            $modeType = 'substance-abuse';
            $sideMenus = $this->getSideMenuByType($modeType);
            $viewName = 'website_pages.substance.new_pakistan_index';
        } elseif ($pageName == "pain-management") {

            $modeType = $pageName;
            $sideMenus = $this->getSideMenuByType($modeType);

            $viewName = 'website_pages.pain-management.new_pakistan_index';
        }elseif ($pageName == "psychiatry") {
            $modeType = $pageName;
            $sideMenus = $this->getSideMenuByType($modeType);
            $viewName = 'website_pages.psychiatrist.new_pakistan_index';
        }elseif ($pageName == "primary-care"){
            $sideMenus ='';
            $viewName = 'website_pages.primary-care.index';
        } else {
            dd('Method not found.');
        }

        // For Data or with Category
        if (!empty($slug) && $pageName == 'pharmacy') {
            $products = $this->Pharmacy->getProductBySlugCommaIds($slug, $modeType);
        } elseif (!empty($slug) && $pageName == 'labtests') {
            $products = $this->Pharmacy->getProductBySlugCommaIds($slug, $modeType);
            if ($slug == 'general-health') {
                $slug_name = 'General Health';
            } elseif ($slug == "women-s-health") {
                $slug_name = "Women's Health";
            } elseif ($slug == "digestive-health") {
                $slug_name = "Digestive Health";
            } elseif ($slug == "heart-health") {
                $slug_name = "Heart Health";
            } elseif ($slug == "std") {
                $slug_name = "STD";
            } elseif ($slug == "men-s-health") {
                $slug_name = "Men's Health";
            } elseif ($slug == "infectious-disease") {
                $slug_name = "Infectious Disease";
            } elseif ($slug == "others-labtest") {
                $slug_name = "Others";
            }
        } elseif (!empty($slug) && $pageName == 'imaging') {
            $products = $this->Pharmacy->getProductBySlugCommaIds($slug, $modeType);
            //dd($products,$slug);
            if ($slug == 'others-imaging') {
                $slug_name = 'Others';
            } elseif ($slug == "mri") {
                $slug_name = "MRI";
            } elseif ($slug == "mra") {
                $slug_name = "MRA";
            } elseif ($slug == "ct-scan") {
                $slug_name = "CT Scan";
            } elseif ($slug == "ultrasound") {
                $slug_name = "Ultrasound";
            } elseif ($slug == "mr") {
                $slug_name = "MR";
            }
        } elseif (!empty($slug) && $pageName == 'substance-abuse') {
            $products = $this->Pharmacy->getProductBySlugCommaIds($slug="", $modeType);
        } elseif (!empty($slug) && $pageName == 'pain-management') {
            $products = $this->Pharmacy->getProductBySlugCommaIds($slug, $modeType);
        }elseif (!empty($slug) && $pageName == 'psychiatry') {
            $products = $this->Pharmacy->getProductBySlugCommaIds($slug="", $modeType);
        }elseif ($pageName == 'primary-care'){
            $products = '';
        } else {
            // Main Page Products
            $products = $this->Pharmacy->getProductOrderByDesc($modeType, $vendor_id);
        }

        // Passing the Data to Page
        $data['sidebar'] = $sideMenus;
        $data['products'] = $products;

        $url = url()->current();
        $meta_tags = DB::table('meta_tags')->where('url',$url)->get();
        $title = DB::table('meta_tags')->where('url',$url)->where('name','title')->first();
        $vendor = DB::table('vendor_accounts')->where('id',$vendor_id)->first();
        return view($viewName, compact('data', 'slug', 'slug_name','title','meta_tags','vendor'));
    }

    public function single_product($slug)
    {
        $url = request()->segment(1);
        if($url=='labtest'){
            $type = 'labtests';
        }elseif($url=='medicines'){
            $type = 'pharmacy';
        }elseif($url=='imagings'){
            $type = 'imaging';
        }
        if (!empty($slug)) {
            $products = $this->Pharmacy->getSingleProduct($type, $slug);
        } else {
            $products = [];
        }

        foreach ($products as $product) {
            if ($product->mode == "medicine") {
                $meta_tags = DB::table('meta_tags')->where('product_id',$product->id)->get();
                $title = DB::table('meta_tags')->where('name','title')->where('product_id',$product->id)->first();
                $product->featured_image = \App\Helper::check_bucket_files_url($product->featured_image);
                if($product->featured_image == env('APP_URL')."/assets/images/user.png"){
                    $product->featured_image = asset('assets/new_frontend/panadol2.png');
                }

                return view('website_pages.pharmacy.new_pakistan_single_view', compact('products','meta_tags','title'));
                // return view('single_product_details', compact('products'));
            } else if ($product->mode == 'lab-test') {
                $meta_tags = DB::table('meta_tags')->where('product_id',$product->id)->get();
                $product->related_products = DB::table('related_products')
                    ->join('quest_data_test_codes','quest_data_test_codes.TEST_CD','related_products.related_product_ids')
                    ->where('related_products.product_id',$product->id)
                    ->select('quest_data_test_codes.*')
                    ->get();
                $title = DB::table('meta_tags')->where('name','title')->where('product_id',$product->id)->first();
                return view('website_pages.lab-test.new_pakistan_single_view', compact('products','meta_tags','title'));
            } else if ($product->mode == 'imaging') {
                $meta_tags = DB::table('meta_tags')->where('product_id',$product->id)->get();
                $product->related_products = DB::table('related_products')
                    ->join('quest_data_test_codes','quest_data_test_codes.TEST_CD','related_products.related_product_ids')
                    ->where('related_products.product_id',$product->id)
                    ->select('quest_data_test_codes.*')
                    ->get();
                $title = DB::table('meta_tags')->where('name','title')->where('product_id',$product->id)->first();
                return view('website_pages.imaging.new_pakistan_single_view', compact('products','meta_tags','title'));
            }
        }
    }

    public function single_product_oldroute($type,$slug)
    {
        if($type=='labtests'){
            return redirect()->route("single_product_view_labtest", ['slug' => $slug]);
        }elseif($type=='pharmacy'){
            return redirect()->route("single_product_view_medicines", ['slug' => $slug]);
        }elseif($type=='imaging'){
            return redirect()->route("single_product_view_imagings", ['slug' => $slug]);
        }
        return redirect()->back();
    }

    public function searchProducts($keyword = "", $pageName = "", $categoryID = "")
    {
        if ($pageName == 'pharmacy') {
            $modeType = 'medicine';
        } elseif ($pageName == 'labtests') {
            $modeType = 'lab-test';
        } elseif ($pageName = "medical-imaging-services") {
            $modeType = "imaging";
        }

        if ($pageName == 'labtests') {
            $data = $this->Pharmacy->searchProductLabtest($keyword, $modeType, (int) $categoryID);
        } else {
            $data = $this->Pharmacy->searchProducts($keyword, $modeType, (int) $categoryID);
        }

        $data = [
            'total_count' => count($data),
            'items' => $data,
            'pageName' => $pageName,
        ];
        echo json_encode($data);
    }

    public function getSideMenuByType($type)
    {
        // Get Product Parent Category Names
        $getNames = $this->Pharmacy->getMainCategory($type);
        // Get Product Sub Category Names
        // dd($getNames)
        $sideMenus = $this->Pharmacy->getSubCategories($getNames);
        return $sideMenus;
    }

    // PRODUCTS & E-COMMERCE

    // CART & CHECKOUTS v2

    public function cart()
    {
        if (Auth::check()) {
            return view('cart');
        } else {
            return redirect('/login');
        }
    }
    public function my_cart()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $countItem = 0;
            $itemSum = 0;
            $providerFee = 0;
            // Get User Cart Items
            $cards = DB::table('card_details')->where('user_id', $user->id)->get();
            $user_cart_items = DB::table('tbl_cart')->where('user_id', Auth::user()->id)->where('status', 'recommended')->get();
            foreach ($user_cart_items as $item) {

                if ($item->item_type == 'prescribed') {
                    $pres = DB::table('prescriptions')->where('id', $item->pres_id)->first();
                    $item->prescription_date = $pres->created_at;
                    $item->medicine_usage = $pres->usage;
                    $datetime = date('Y-m-d h:i A', strtotime($item->prescription_date));
                    $item->prescription_date = User::convert_utc_to_user_timezone($user->id, $datetime)['datetime'];
                    $item->prescription_date = date("m-d-Y h:iA", strtotime($item->prescription_date));
                }
                if ($item->item_type == 'counter' && $item->product_mode == 'lab-test' && $item->show_product == '1') {
                    $providerFee = 6;
                    $datetime = date('Y-m-d h:i A', strtotime($item->created_at));
                    $item->prescription_date = User::convert_utc_to_user_timezone($user->id, $datetime)['datetime'];
                    $item->prescription_date = date("m-d-Y h:iA", strtotime($item->created_at));
                } else {
                    $providerFee = 0;
                }
                if ($item->show_product == 1) {
                    $countItem += 1;
                    $itemSum += $item->update_price;
                }
                if ($item->doc_session_id != '0') {
                    $doctorDetails = User::find($item->doc_id);
                    $item->prescribed_by = 'Dr.' . $doctorDetails->name . ' ' . $doctorDetails->last_name;
                } else {
                    $item->prescribed_by = '';
                }
            }
            // dd($user_cart_items);

            $totalPrice = $itemSum + $providerFee;
            return view('website_pages.cart', compact('user_cart_items', 'countItem', 'itemSum', 'totalPrice', 'providerFee', 'cards'));
        } else {
            return redirect('/login');
        }
    }

    public function new_my_cart()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $countItem = 0;
            $itemSum = 0;
            $providerFee = 0;
            // Get User Cart Items
            $cards = DB::table('card_details')->where('user_id', $user->id)->get();
            $user_cart_items = DB::table('tbl_cart')->where('user_id', Auth::user()->id)->where('status', 'recommended')->get();
            foreach ($user_cart_items as $item) {
                if ($item->item_type == 'prescribed') {
                    // dd(Auth::user()->id,$item->pres_id);
                    $pres = DB::table('prescriptions')->where('id', $item->pres_id)->first();
                    $item->prescription_date = $pres->created_at;
                    $item->medicine_usage = $pres->usage;
                    $datetime = date('Y-m-d h:i A', strtotime($item->prescription_date));
                    $item->prescription_date = User::convert_utc_to_user_timezone($user->id, $datetime)['datetime'];
                    $item->prescription_date = date("m-d-Y h:iA", strtotime($item->prescription_date));
                }
                if ($item->item_type == 'counter' && $item->product_mode == 'lab-test' && $item->show_product == '1') {
                    $providerFee = 6;
                    $datetime = date('Y-m-d h:i A', strtotime($item->created_at));
                    $item->prescription_date = User::convert_utc_to_user_timezone($user->id, $datetime)['datetime'];
                    $item->prescription_date = date("m-d-Y h:iA", strtotime($item->created_at));
                } else {
                    $providerFee = 0;
                }
                if ($item->show_product == 1) {
                    $countItem += 1;
                    $itemSum += $item->update_price;
                }
                if ($item->doc_session_id != '0') {
                    $doctorDetails = User::find($item->doc_id);
                    $item->prescribed_by = 'Dr.' . $doctorDetails->name . ' ' . $doctorDetails->last_name;
                } else {
                    $item->prescribed_by = '';
                }
                $item->unit = \App\MedicineUOM::find($item->prescription);
            }

            $totalPrice = $itemSum;
            return view('website_pages.new_api_cart', compact('user_cart_items', 'countItem', 'itemSum', 'totalPrice', 'providerFee', 'cards'));
        } else {
            return redirect('/login');
        }
    }

    public function checkout()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $items = $this->Pharmacy->get_data_cart_page_by_user_id($user['id'], 'all', 'checkout');
            if (count($items) > 0) {
                $itemTotal = $this->Pharmacy->getProductsTotalForCheckout($user['id']);
                $checkOutItems = $this->converToObjToArray($items);

                // Get AOE's
                $allTestAOEs = $this->getAllAOEs($checkOutItems);
                // dd($allTestAOEs);

                $data = [
                    'checkoutItems' => $checkOutItems,
                    'itemTotal' => $itemTotal,
                    'prescribedCount' => count($this->Pharmacy->get_data_cart_page_by_user_id($user['id'], 'prescribed', 'all')),
                    'countries' => $this->Pharmacy->get_country_states(233), // for dependant dropdowns
                    'AOEs' => $allTestAOEs,
                    'AOEsCount' => count($allTestAOEs['TestsName']),
                ];
                //dd($data);
                return view('checkout', compact('data'));
            } else {
                return redirect('/cart');
            }
        } else {
            return redirect('/login');
        }
    }

    public function getUserCartData()
    {
        // Get User
        $user = Auth::user();
        // Get User Cart Items
        $items = $this->Pharmacy->get_data_cart_page_by_user_id($user['id'], 'all', 'all');
        // Get User Cart Total
        $itemTotal = $this->Pharmacy->getCartItemsTotal($user['id']);

        $forCart = [
            'cartItems' => $this->converToObjToArray($items),
            'itemTotal' => $itemTotal,
            'prescribed_count' => count($this->Pharmacy->get_data_cart_page_by_user_id($user['id'], 'prescribed', 'all')),
        ];

        return view('cart_list', compact('forCart'));
    }

    public function get_cart_content(Request $request)
    {
        $user = Auth::user();
        $cart = [];

        if (!empty($request->quantity)) {
            $data = $this->Pharmacy->get_product_detail_for_cart($request->id, $request->quantity, $request->mode);
        } else {
            $data = $this->Pharmacy->get_product_detail_for_cart($request->id, 0, $request->mode);
        }
        // cart session data
        $cart_session = $request->session()->get('cart');
        // check quantity available or not
        if (count($data) < 1) {
            $cart['cart_message'] = 'Quantity not available';
            $cart['cart_counter'] = $this->Pharmacy->get_cart_counter_by_user($user->id);
            $cart['status'] = false;
        } else {
            // Product ID
            $product_id = $data['product_id'];
            // already added
            $already_added = $this->check_cart_product_already_added_or_not($product_id, $cart_session);
            if ($already_added != 1) {
                // Insert TBL Cart
                $data['item_type'] = 'counter';
                $data['status'] = 'recommended';
                $data['doc_id'] = 0;
                $data['map_market_id'] = 0;
                $data['location_id'] = 0;
                $data['user_id'] = $user->id;
                $tbl_Cart = AppTblCart::Create($data);
                // Insert TBL Cart
                $cart['cart_message'] = 'Your Product added to the Cart.';
                $cart['cart_counter'] = $this->Pharmacy->get_cart_counter_by_user($user->id);
                $cart['status'] = true;
            } else {
                $cart['cart_message'] = 'Already added into cart';
                $cart['cart_counter'] = $this->Pharmacy->get_cart_counter_by_user($user->id);
                $cart['status'] = false;
            }
        }
        echo json_encode($cart);
    }

    public function remove_single_cart_item(Request $request, $product_id = "", $cart_row_id = "")
    {
        $user = Auth::user();
        $remove = AppTblCart::where('product_id', $product_id)->where('user_id', $user['id'])->where('id', $cart_row_id)->update(['status' => "deleted", 'purchase_status' => 99, 'checkout_status' => 99]);
        $items = $this->Pharmacy->get_data_cart_page_by_user_id($user['id'], 'all', 'all');
        $data = ['data' => $items, 'cart_count' => count($items), 'status' => 1];
        event(new CountCartItem($user['id']));
        echo json_encode($data);
    }

    public function update_cart_item_quantity(Request $request, $product_id = "", $quantity = "")
    {
        $user = Auth::user();
        $cart_row_id = $request->input('cart_row_id');
        $checkQty = AllProducts::select('name as product_name', 'quantity')->where('id', $product_id)->get()->toArray();
        if ($checkQty[0]['quantity'] < $quantity) {
            $arr = [
                'product_id' => $product_id,
                'product_name' => $checkQty[0]['product_name'],
                'remaining' => $checkQty[0]['quantity'],
            ];
            echo json_encode($arr);
        } else {
            $price = AppTblCart::select('price')->where('id', $cart_row_id)->where('product_id', $product_id)->where('user_id', $user['id'])->first();
            $updatePrice = $price->price * $quantity;
            $update = AppTblCart::where('product_id', $request->product_id)->where('id', $cart_row_id)->where('user_id', $user['id'])->update(
                ['update_price' => $updatePrice, 'quantity' => $quantity]
            );
            $items = $this->Pharmacy->get_data_cart_page_by_user_id($user['id'], 'all', 'all');
            $data = ['data' => $items, 'cart_count' => count($items), 'status' => 1];
            echo json_encode($data);
        }
    }

    public function check_cart_product_already_added_or_not($product_id, $cart_session)
    {
        $user = Auth::user();
        $data = AppTblCart::where('product_id', $product_id)
            ->where('user_id', $user['id'])
            ->where('purchase_status', 1)
            ->get()->toArray();
        if (count($data) > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function get_cart_counter(Request $request)
    {
        $user = Auth::user();
        if (isset($user->id)) {
            $data = $this->Pharmacy->get_cart_counter_by_user($user->id);
        } else {
            $data = 0;
        }
        echo json_encode($data);
    }

    public function getPrescribedProducts()
    {
        $user = Auth::user();
        $data['items'] = $this->Pharmacy->get_data_cart_page_by_user_id($user['id'], 'all', 'all');
        $data['totalAmount'] = number_format($this->Pharmacy->getProductsTotalForCheckout($user['id']), 2);
        return $data;
    }

    public function updateCheckoutStatus($product_id = "", $status = "", $cart_row_id = "")
    {
        $user = Auth::user();
        $update = AppTblCart::where('product_id', $product_id)->where('user_id', $user['id'])->where('id', $cart_row_id)->update(['checkout_status' => $status]);
        return $update;
    }

    public function getAllAOEs($items)
    {
        $AOECollection = [];
        $TestsName = [];
        $FieldName = [];
        foreach ($items as $item) {
            if ($item['product_mode'] == 'lab-test') {
                $getTestAOE = QuestDataAOE::select("TEST_CD AS TestCode", "AOE_QUESTION AS QuestionShort", "AOE_QUESTION_DESC AS QuestionLong")
                    ->where('TEST_CD', $item['product_id'])
                    ->groupBy('AOE_QUESTION_DESC')
                    ->get()
                    ->toArray();
                if (count($getTestAOE) > 0) {
                    $counter = count($getTestAOE);

                    $getAoesAnswers = DB::table('patient_lab_recomend_aoe')->where('testCode', $item['product_id'])->where('session_id', $item['doc_session_id'])->first();
                    $data = unserialize($getAoesAnswers->aoes);

                    for ($i = 0; $i < $counter; $i++) {
                        if ($data[$i]['question'] == $getTestAOE[$i]['QuestionLong']) {
                            $getTestAOE[$i]['answer'] = $data[$i]['answer'];
                        }
                    }
                    $AOECollection[] = $getTestAOE;
                    $FieldName[] = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $item['name']), '_'));
                    $TestsName[] = $item['name'];
                }
            }
        }
        $data = [
            'AOECollection' => $AOECollection,
            'TestsName' => $TestsName,
            'FieldName' => $FieldName,
        ];
        return $data;
    }

    // CART & CHECKOUTS v2

    // CREATE ORDER FOR WEB / APP
    public function orderIDs($state, $orderTypes, $user_id, $last_id)
    {
        // State - Type - OrderDate - User ID - -Increament
        // NY 122320 U6 1000
        // NY PHAR 122320 U6 1000
        // NY LT 122320U6 1000
        // $dateOrder =  date('mdy');
        $ordersID = [];
        foreach ($orderTypes as $key => $val) {
            $str = $val . $user_id . $last_id;
            $ordersID[$val] = $str;
        }
        return $ordersID;
    }

    public function create_new_order(Request $request)
    {
        $user = Auth::user();

        $cartPreLab = [];
        $cartCntLab = [];
        $cartPreMed = [];
        $cartCntMed = [];
        $cartPreImg = [];
        $cartCntImg = [];
        $orderAllIds = [];


        //get medicine items from tbl_product table
        $getAllCartProducts = DB::table('tbl_cart')->where('user_id', $user->id)->where('show_product', '1')->where('status', 'recommended')->get();

        $orderId = '';
        $dateString = Carbon::now()->format('yHis');
        $getLastOrderId = DB::table('tbl_orders')->orderBy('id', 'desc')->first();

        if ($getLastOrderId != null) {
            $orderId = $getLastOrderId->order_id + 1;
        } else {
            $orderId = $dateString;
        }

        foreach ($getAllCartProducts as $item) {

            if ($item->item_type == 'counter' && $item->product_mode == 'lab-test') {
                $item->orderSubId = $orderId . $item->product_id;
                $item->orderSystemId = $orderId;
                array_push($orderAllIds, $orderId . $item->product_id);
                array_push($cartCntLab, $item);
            } else if ($item->item_type == 'prescribed' && $item->product_mode == 'lab-test') {
                $item->orderSubId = $orderId . $item->product_id;
                $item->orderSystemId = $orderId;
                array_push($orderAllIds, $orderId . $item->product_id);
                array_push($cartPreLab, $item);
            } else if ($item->item_type == 'counter' && $item->product_mode == 'medicine') {
                $item->orderSubId = $orderId . $item->product_id;
                $item->orderSystemId = $orderId;
                array_push($orderAllIds, $orderId . $item->product_id);
                array_push($cartCntMed, $item);
            } else if ($item->item_type == 'prescribed' && $item->product_mode == 'medicine') {
                $item->orderSubId = $orderId . $item->product_id;
                $item->orderSystemId = $orderId;
                array_push($orderAllIds, $orderId . $item->product_id);
                array_push($cartPreMed, $item);
            } else if ($item->item_type == 'counter' && $item->product_mode == 'imaging') {
                $item->orderSubId = $orderId . $item->product_id;
                $item->orderSystemId = $orderId;
                array_push($orderAllIds, $orderId . $item->product_id);
                array_push($cartCntImg, $item);
            } else if ($item->item_type == 'prescribed' && $item->product_mode == 'imaging') {
                $item->orderSubId = $orderId . $item->product_id;
                $item->orderSystemId = $orderId;
                array_push($orderAllIds, $orderId . $item->product_id);
                array_push($cartPreImg, $item);
            }
        }


        $totalPaymentToCharge = $request->payAble;
        // create object for payment
        $forPayment = array(
            'amount' => $request->payAble,
            'credit_card' => [
                'number' => $request->card_number,
                'expiration_month' => $request->exp_month,
                'expiration_year' => $request->exp_year,
            ],
            'integrator_id' => 'Order-' . $orderId,
            "csc" => $request->cvc,
            "billing_address" => [
                "name" => $request->card_holder_name,
                "street_address" => $request->address,
                "city" => $request->city,
                "state" => $request->state_code,
                "zip" => $request->zipcode,
            ],
        );
        //charge payment
        $pay = new PaymentController;
        $getTokenForPayment = $pay->getTokenForPayment();
        if ($getTokenForPayment['success']) {
            $createTransaction = $pay->paymentToPayTrace($getTokenForPayment, $forPayment);
            $paymentResult = (array) $createTransaction;
        } else {
            $paymentResult = $getTokenForPayment;
        }


        if ($paymentResult['success']) {

            $payment_request = [];
            $payment_request['transaction_id'] = $paymentResult['transaction_id'];
            $payment_request['approval_code'] = $paymentResult['approval_code'];
            $payment_request['avs_response'] = $paymentResult['avs_response'];
            $payment_request['csc_response'] = $paymentResult['csc_response'];
            $payment_request['payment_status'] = true;
            $agent = 'web';

            // ADD IN TABLE TRANSACTION
            $transactionArr = [
                'transaction_id' => $paymentResult['transaction_id'],
                'subject' => 'Order',
                'description' => $orderId,
                'total_amount' => $totalPaymentToCharge,
                'user_id' => $user->id,
                'approval_code' => $paymentResult['approval_code'],
                'approval_message' => $paymentResult['approval_message'],
                'avs_response' => $paymentResult['avs_response'],
                'csc_response' => $paymentResult['csc_response'],
                'external_transaction_id' => $paymentResult['external_transaction_id'],
                'masked_card_number' => $paymentResult['masked_card_number'],
            ];
            TblTransaction::create($transactionArr);

            $billing = array(
                'number' => 'xxxx-xxxx-xxxx-' . substr($request->card_number, -4),
                'expiration_month' => $request->exp_month,
                'expiration_year' => $request->exp_year,
                "csc" => $request->cvc,
                "name" => $request->card_holder_name,
                "street_address" => $request->address,
                "city" => $request->city,
                "state" => $request->state_code,
                "zip" => $request->zipcode,
                'phoneNumber' => $request->phoneNumber,
            );
            $shipping = '';
            if (isset($request->shipping_customer_name)) {
                $shipping = array(
                    "name" => $request->shipping_customer_name,
                    "email" => $request->shipping_customer_email,
                    "phone" => $request->shipping_customer_phone,
                    "street_address" => $request->shipping_customer_address,
                    "city" => $request->shipping_customer_city,
                    "state" => $request->shipping_customer_state,
                    "zip" => $request->shipping_customer_zip,
                );
            } else {
                $shipping = array(
                    "name" => $request->card_holder_name,
                    "email" => $user->email,
                    "phone" => $user->phone_number,
                    "street_address" => $request->address,
                    "city" => $request->city,
                    "state" => $request->state_code,
                    "zip" => $request->zipcode,
                );
            }


            $this->seprate_order_create($cartCntLab, $cartPreLab, $cartPreMed, $cartCntMed, $cartPreImg, $cartCntImg, $shipping);

            DB::table('tbl_orders')->insert([
                'order_state' => $user->state_id,
                'order_id' => $orderId,
                'order_sub_id' => serialize($orderAllIds),
                'transaction_id' => $payment_request['transaction_id'],
                'customer_id' => $user->id,
                'total' => $totalPaymentToCharge,
                'total_tax' => 0,
                'billing' => serialize($billing),
                'shipping' => serialize($shipping),
                'payment' => serialize($payment_request),
                'payment_title' => 'Direct Bank Transfer',
                'payment_method' => 'via authorize payment',
                'cart_items' => '',
                "lab_order_approvals" => '',
                'currency' => 'US',
                'order_status' => 'paid',
                'agent' => $agent,
                'created_at' => Carbon::now(),
            ]);

            $this->orderNotify($orderId, $getAllCartProducts->toArray(), $totalPaymentToCharge, $billing['number'], $billing['name'], $paymentResult['transaction_id']);

            foreach ($getAllCartProducts as $ci) {
                if ($ci->refill_flag != '0') {
                    RefillRequest::where('id', $ci->refill_flag)->update(['granted' => null]);
                }
                DB::table('tbl_cart')->where('id', $ci->id)->update([
                    'status' => 'purchased',
                    'purchase_status' => '0',
                    'checkout_status' => '0',
                ]);
            }
            return redirect()->route('order.complete', ['id' => $orderId]);
        } else {
            dd($paymentResult);
            $response = [
                'orderID' => '',
                'success' => false,
                'paymentStatus' => $paymentResult,
            ];
        }
    }

    /////////////////////////////////////////////////
    /////////////////////////////////////////////////

    public function authorize_create_new_order(Request $request)
    {
        $request->payAble = filter_var($request->payAble, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $request->payAble = $request->payAble*100;
        $user = Auth::user();
        $orderId = '';
        $dateString = Carbon::now()->format('yHis');
        $getLastOrderId = DB::table('tbl_orders')->orderBy('id', 'desc')->first();
        $randNumber=rand(1,100);
        if ($getLastOrderId != null) {
            $orderId = $getLastOrderId->order_id + 1+$randNumber;
        } else {
            $orderId = $dateString+$randNumber;
        }

        if (isset($request->shipping_customer_name)) {
            $shipping = array(
                "name" => $request->shipping_customer_name,
                "email" => $request->shipping_customer_email,
                "phone" => $request->shipping_customer_phone,
                "street_address" => $request->shipping_customer_address,
                "city" => $request->shipping_customer_city
            );
            session()->put('shipping_details', $shipping);
        }
        if($request->payment_method == "credit-card"){
            $data = "Order-" .$orderId."-". now()->format('Ymd');
            $pay = new \App\Http\Controllers\MeezanPaymentController();
            $res = $pay->payment($data, $request->payAble);
            if ($res->errorCode == 0) {
                return redirect($res->formUrl);
            }else{
                return redirect()->back()->with('msg','Sorry, we are currently facing server issues. Please try again later.');
            }
        }else{
            return redirect()->back()->with('msg','Sorry, Can\'t process with this payment method right now.Kindly try different method.');
        }
    }

    public function order_payment_return(){
        $pay = new \App\Http\Controllers\MeezanPaymentController();
        $response = $pay->payment_return();
        $description = explode("-",$response->orderDescription);
        if($response->orderStatus == 2){
            $orderId = $description[1];
            $user = Auth::user();
            $cartPreLab = [];
            $cartCntLab = [];
            $cartPreMed = [];
            $cartCntMed = [];
            $cartPreImg = [];
            $cartCntImg = [];
            $orderAllIds = [];

            $getAllCartProducts = DB::table('tbl_cart')->where('user_id', $user->id)->where('show_product', '1')->where('status', 'recommended')->get();

            foreach ($getAllCartProducts as $item) {
                if ($item->item_type == 'counter' && $item->product_mode == 'lab-test') {
                    $item->orderSubId = $orderId . $item->product_id;
                    $item->orderSystemId = $orderId;
                    array_push($orderAllIds, $orderId . $item->product_id);
                    array_push($cartCntLab, $item);
                } else if ($item->item_type == 'prescribed' && $item->product_mode == 'lab-test') {
                    $item->orderSubId = $orderId . $item->product_id;
                    $item->orderSystemId = $orderId;
                    array_push($orderAllIds, $orderId . $item->product_id);
                    array_push($cartPreLab, $item);
                } else if ($item->item_type == 'counter' && $item->product_mode == 'medicine') {
                    $item->orderSubId = $orderId . $item->product_id;
                    $item->orderSystemId = $orderId;
                    array_push($orderAllIds, $orderId . $item->product_id);
                    array_push($cartCntMed, $item);
                } else if ($item->item_type == 'prescribed' && $item->product_mode == 'medicine') {
                    $item->orderSubId = $orderId . $item->product_id;
                    $item->orderSystemId = $orderId;
                    array_push($orderAllIds, $orderId . $item->product_id);
                    array_push($cartPreMed, $item);
                } else if ($item->item_type == 'counter' && $item->product_mode == 'imaging') {
                    $item->orderSubId = $orderId . $item->product_id;
                    $item->orderSystemId = $orderId;
                    array_push($orderAllIds, $orderId . $item->product_id);
                    array_push($cartCntLab, $item);
                } else if ($item->item_type == 'prescribed' && $item->product_mode == 'imaging') {
                    $item->orderSubId = $orderId . $item->product_id;
                    $item->orderSystemId = $orderId;
                    array_push($orderAllIds, $orderId . $item->product_id);
                    array_push($cartPreLab, $item);
                }
            }

            $agent = 'web';
            $transactionArr = [
                'subject' => 'Order',
                'description' => $description[1],
                'currency' => 'PKR',
                'total_amount' => ($response->amount/100),
                'user_id' => $user->id,
                'status' => 1,
            ];
            TblTransaction::where('description',$description[1])->update($transactionArr);

            $this->seprate_order_create($cartCntLab, $cartPreLab, $cartPreMed, $cartCntMed, $cartPreImg, $cartCntImg);
            $shipping = session()->get('shipping_details');
            DB::table('tbl_orders')->insert([
                'order_id' => $description[1],
                'order_sub_id' => serialize($orderAllIds),
                // 'transaction_id' => $payment_request['transaction_id'],
                'customer_id' => $user->id,
                'total' => ($response->amount/100),
                'total_tax' => 0,
                'billing' => serialize($shipping),
                'shipping' => serialize($shipping),
                'payment' => serialize($response),
                'payment_title' => 'Direct Bank Transfer',
                'payment_method' => 'via Meezan Bank',
                'cart_items' => '',
                "lab_order_approvals" => '',
                'currency' => 'PKR',
                'order_status' => 'paid',
                'agent' => $agent,
                'created_at' => now(),
            ]);

            $this->orderNotify($description[1], $getAllCartProducts->toArray(), ($response->amount/100));
            foreach ($getAllCartProducts as $ci) {
                DB::table('tbl_cart')->where('id', $ci->id)->update([
                    'status' => 'purchased',
                    'purchase_status' => '0',
                    'checkout_status' => '0',

                ]);
            }
            return redirect()->route('order.complete', ['id' => $description[1]]);
        }else{
            return redirect()->route('user_cart')->with('msg', $response->actionCodeDescription);
        }
    }
    //////////////////////////////////////////
    //////////////////////////////////////////
    public function get_card_details(Request $request){
        // dd($request);
        $billingDetails = DB::table('card_details')->where('id',$request->id)->select('billing')->first();
        $billing = unserialize($billingDetails->billing);

        $shippingDetails = DB::table('card_details')->where('id',$request->id)->select('shipping')->first();
        $shipping = unserialize($shippingDetails->shipping);


        return array('billing'=> $billing,
                    'shipping'=> $shipping,);
    }

    public function orderComplete($id)
    {
        return view('website_pages.order_thankyou', compact('id'));
    }
    public function disapprovedLabTest(Request $request)
    {
        DB::table('lab_orders')->where('order_id', $request->order_id)->update(['status' => 'lab-editor-approval']);
        return 'ok';
    }

    public function seprate_order_create($cartCntLab, $cartPreLab, $cartPreMed, $cartCntMed, $cartPreImg, $cartCntImg)
    {
        if ($cartCntLab != null) {
            foreach ($cartCntLab as $order) {
                LabOrder::create([
                    'user_id' => Auth::user()->id,
                    'order_id' => $order->orderSystemId,
                    'product_id' => $order->product_id,
                    'session_id' => $order->doc_session_id,
                    'pres_id' => $order->pres_id,
                    'status' => 'essa-forwarded',
                    'date' => date('Y-m-d'),
                    'time' => 0,
                    'type' => 'Counter',
                    'map_marker_id' => 0,
                    'price' => $order->update_price,
                    'sub_order_id' => $order->orderSubId,
                ]);
            }
            $text = "New Online Lab Order Place By " . Auth::user()->name;
            $lab_admins = DB::table('users')->where('user_type','admin_lab')->get();
            foreach($lab_admins as $admin)
            {
                Notification::create([
                    'user_id' => $admin->id,
                    'type' => '/unassigned/lab/orders',
                    'text' => $text,
                ]);
            }
        }
        if ($cartPreLab != null) {
            $account = VendorAccount::where('vendor', 'quest')->first();
            $testsData = [];

            foreach ($cartPreLab as $item) {
                LabOrder::create([
                    'user_id' => Auth::user()->id,
                    'order_id' => $item->orderSystemId,
                    'product_id' => $item->product_id,
                    'session_id' => $item->doc_session_id,
                    'pres_id' => $item->pres_id,
                    'status' => 'essa-forwarded',
                    'type' => 'Prescribed',
                    'date' => date('Y-m-d'),
                    'time' => 0,
                    'map_marker_id' => 0,
                    'price' => $item->update_price,
                    'sub_order_id' => $item->orderSubId,
                ]);

                $data = DB::table('patient_lab_recomend_aoe')->where('session_id', $item->doc_session_id)->where('testCode', $item->product_id)->first();
                array_push($testsData, ['testCode' => $item->product_id, 'testName' => $item->name, 'aoes' => '']);
            }

            $doctor = User::find($item->doc_id);
            $patient = User::find($item->user_id);
        }
        if ($cartCntImg != null) {
            foreach ($cartCntLab as $order) {
                LabOrder::create([
                    'user_id' => Auth::user()->id,
                    'order_id' => $order->orderSystemId,
                    'product_id' => $order->product_id,
                    'session_id' => $order->doc_session_id,
                    'pres_id' => $order->pres_id,
                    'status' => 'essa-forwarded',
                    'date' => date('Y-m-d'),
                    'time' => 0,
                    'type' => 'Counter',
                    'map_marker_id' => 0,
                    'price' => $order->update_price,
                    'sub_order_id' => $order->orderSubId,
                ]);
            }
            $text = "New Online Lab Order Place By " . Auth::user()->name;
            $lab_admins = DB::table('users')->where('user_type','admin_lab')->get();
            foreach($lab_admins as $admin)
            {
                Notification::create([
                    'user_id' => $admin->id,
                    'type' => '/unassigned/lab/orders',
                    'text' => $text,
                ]);
            }
        }
        if ($cartPreImg != null) {
            $account = VendorAccount::where('vendor', 'quest')->first();
            $testsData = [];

            foreach ($cartPreImg as $item) {
                LabOrder::create([
                    'user_id' => Auth::user()->id,
                    'order_id' => $item->orderSystemId,
                    'product_id' => $item->product_id,
                    'session_id' => $item->doc_session_id,
                    'pres_id' => $item->pres_id,
                    'status' => 'essa-forwarded',
                    'type' => 'Prescribed',
                    'date' => date('Y-m-d'),
                    'time' => 0,
                    'map_marker_id' => 0,
                    'price' => $item->update_price,
                    'sub_order_id' => $item->orderSubId,
                ]);

                $data = DB::table('patient_lab_recomend_aoe')->where('session_id', $item->doc_session_id)->where('testCode', $item->product_id)->first();
                array_push($testsData, ['testCode' => $item->product_id, 'testName' => $item->name, 'aoes' => '']);
            }

            $doctor = User::find($item->doc_id);
            $patient = User::find($item->user_id);
        }
        if ($cartPreMed != null) {
            $presc_meds = array();
            foreach ($cartPreMed as $key => $item) {
                DB::table('medicine_order')->insert([
                    'user_id' => $item->user_id,
                    'order_main_id' => $item->orderSystemId,
                    'order_sub_id' => $item->orderSubId,
                    'order_product_id' => $item->product_id,
                    'pro_mode' => 'Prescribed',
                    'pro_mode' => 'Prescribed',
                    'update_price' => $item->update_price,
                    'session_id' => $item->doc_session_id,
                    'status' => 'order-placed',
                ]);
                $doctor = User::find($item->doc_id);
                $patient = User::find($item->user_id);
                // $state = State::find($doctor->state_id);
                // $city = City::find($doctor->city_id);
                $orderDate = User::convert_utc_to_user_timezone($patient->id, Carbon::now()->format('Y-m-d H:i:s'));
                $date = str_replace('-', '/',  $patient->date_of_birth);
                $patient->date_of_birth = date('m/d/Y', strtotime($date));

                $pres = Prescription::find($item->pres_id);
                $item->med_days = $pres->med_days;
                $item->med_unit = $pres->med_unit;
                $item->med_time = $pres->med_time;
                $item->medicine_usage = $item->med_unit . " " . $item->med_time . " " . $item->med_days;
            }
        }
        if ($cartCntMed != null) {
            foreach ($cartCntMed as $item) {
                DB::table('medicine_order')->insert([
                    'user_id' => $item->user_id,
                    'order_main_id' => $item->orderSystemId,
                    'order_sub_id' => $item->orderSubId,
                    'order_product_id' => $item->product_id,
                    'pro_mode' => 'Counter',
                    'update_price' => $item->update_price,
                    'session_id' => $item->doc_session_id,
                    'status' => 'order-placed',
                ]);
            }
        }
    }

    public function orderNotify($order_main_id, $order_cart_items, $orderAmount)
    {
        $orderDate=DB::table('tbl_orders')->where('order_id',$order_main_id)->first();
        try {
            $users = User::where('id', Auth::user()->id)->get();
            $get_order_total = $orderAmount;

            foreach ($users as $u) {
                $time = User::convert_user_timezone_to_utc($u->id, $orderDate->created_at);

                $userDetails = array(
                    'cardNumber' => "",
                    'cardHolder' => $u->name." ".$u->last_name,
                    'transaction_id' => "",
                    'order_total' => $get_order_total,
                    'order_date' => $time['datetime'],
                    'order_id' => $order_main_id,
                    'pat_email' => Auth::user()->email
                );


                if($u->id==Auth::user()->id)
                {
                     Mail::to($u->email)->send(new OrderConfirmationEmail($order_cart_items, $userDetails));
                     Mail::to(env('ADVIYAT_EMAIL'))->send(new AdviyatOrderEmail($order_cart_items, $userDetails));
                }


                $text = "New Order Place By " . Auth::user()->name;
                if($u->id==Auth::user()->id)
                {
                    ActivityLog::create([
                        'user_id' => $u->id,
                        'activity' => 'purchased',
                        'identity' => 'xx',
                        'type' => '/orders',
                        'user_type' => 'patient',
                        'text' => $text,
                    ]);
                }
                $text = "New Order Place By " . $order_main_id;
                event(new RealTimeMessage('Hello World'));
            }
        } catch (\Exception $e) {
            Log::error($e);
        }
    }

    public function create_order(Request $request)
    {

        // REQUEST VALUES
        $user = Auth::user();

        // USER
        $user_id = $user->id;
        $user_state = $user->state_id;
        $input['user_id'] = $user_id;

        // GET CHECKOUT BY USER
        $checkoutItems = $this->Pharmacy->get_data_cart_page_by_user_id($user['id'], 'all', 'checkout');


        $input['cart_items'] = $checkoutItems;
        if (isset($request['AOEs'])) {
            $input['labtestAOEs'] = $this->serializeAOEs($request['AOEs']);
        } else {
            $input['labtestAOEs'] = [];
        }
        // GET ITEMS FROM CHECKOUT
        $cart_items = $this->converToObjToArray($checkoutItems);
        $orderTypes = $this->checkOrderTypes($cart_items);
        $last_orderID = DB::table('tbl_orders')->select('id')->latest('id')->first();
        (isset($last_orderID->id)) ? $current_id = $last_orderID->id + 1 : $current_id = 1;
        $orderIDs = $this->orderIDs($user_state, $orderTypes, $user_id, $current_id);

        $totalOfCartList = $this->Pharmacy->getProductsTotalForCheckout($user['id']);
        $orderPayment = $totalOfCartList;
        $orderCartItems = $this->orderCartItems($cart_items);
        $labOrderApprovalsArr = $this->labtestForApprovals($cart_items);
        $labOrderApprovals = serialize($labOrderApprovalsArr);
        //  dd($orderCartItems, $checkoutItems);
        // For Payment Procedure
        $arr_expiry = explode("-", $request->payment['card_expiry']);
        $forPayment = array(
            'amount' => $orderPayment,
            'credit_card' => [
                'number' => $request->payment['card_number'],
                'expiration_month' => $arr_expiry[1],
                'expiration_year' => $arr_expiry[0],
            ],
            'integrator_id' => 'Order-' . $this->findAssocValue($orderIDs, 'UMB'),
            "csc" => $request->payment['cvc'],
            "billing_address" => [
                "name" => $request->billing['first_name'] . " " . $request->billing['last_name'],
                "street_address" => $request->billing['address'],
                "city" => $request->billing['city'],
                "state" => $request->billing['state_code'],
                "zip" => $request->billing['zip_code'],
            ],
        );

        $pay = new PaymentController;
        $getTokenForPayment = $pay->getTokenForPayment();
        if ($getTokenForPayment['success']) {
            $createTransaction = $pay->paymentToPayTrace($getTokenForPayment, $forPayment);
            $paymentResult = (array) $createTransaction;
        } else {
            $paymentResult = $getTokenForPayment;
        }

        if ($paymentResult['success']) {

            $payment_request = $request->payment;
            $payment_request['transaction_id'] = $paymentResult['transaction_id'];
            $payment_request['approval_code'] = $paymentResult['approval_code'];
            $payment_request['avs_response'] = $paymentResult['avs_response'];
            $payment_request['csc_response'] = $paymentResult['csc_response'];
            $payment_request['payment_status'] = true;
            $agent = 'web';
            //dd($checkoutItems);
            // ADD IN TABLE TRANSACTION
            $transactionArr = [
                'transaction_id' => $paymentResult['transaction_id'],
                'subject' => 'Order',
                'description' => $this->findAssocValue($orderIDs, 'UMB'),
                'total_amount' => $orderPayment,
                'user_id' => $user_id,
                'approval_code' => $paymentResult['approval_code'],
                'approval_message' => $paymentResult['approval_message'],
                'avs_response' => $paymentResult['avs_response'],
                'csc_response' => $paymentResult['csc_response'],
                'external_transaction_id' => $paymentResult['external_transaction_id'],
                'masked_card_number' => substr($paymentResult['masked_card_number'], -7),
            ];

            foreach ($checkoutItems as $ci) {
                if ($ci->refill_flag != '0') {
                    $up = RefillRequest::where('id', $ci->refill_flag)->update(['granted' => null]);
                }
            }
            $transaction = TblTransaction::create($transactionArr);

            // ADD IN TABLE ORDER
            $mainOrderID = $this->findAssocValue($orderIDs, 'UMB');

            $order_id = DB::table('tbl_orders')->insertGetId([
                'order_state' => $user_state,
                'order_id' => $mainOrderID,
                'order_sub_id' => serialize($orderIDs),
                'transaction_id' => $payment_request['transaction_id'],
                'customer_id' => $user_id,
                'total' => $orderPayment,
                'total_tax' => 0,
                'billing' => serialize($request->billing),
                'shipping' => serialize($request->shipping),
                'payment' => serialize($payment_request),
                'payment_title' => 'Direct Bank Transfer',
                'payment_method' => 'via PayTrace',
                'cart_items' => serialize($orderCartItems),
                "lab_order_approvals" => $labOrderApprovals,
                'currency' => 'US',
                'order_status' => 'paid',
                'agent' => $agent,
                'created_at' => NOW(),
            ]);
            // dd($mainOrderID, $orderIDs);
            // ADD LABTESTS & IMAGING BY ROWS
            $this->addOrderByRows($orderCartItems, $user_id, $orderIDs);

            // SEND NOTIFICATION TO USER
            $cardHolderName = $request->billing['first_name'] . " " . $request->billing['last_name'];
            $lastCardNumber = substr($request->payment['card_number'], -4);
            $transaction_id = $payment_request['transaction_id'];
            $this->orderNotifyToUser($user_id, $this->findAssocValue($orderIDs, 'UMB'), $orderTypes, $cart_items, $lastCardNumber, $cardHolderName, $transaction_id);

            // // FOR QUEST
            $labTests = \DB::table('tbl_cart')->where([
                'user_id' => $user_id,
                'purchase_status' => '1',
                'checkout_status' => '1',
                'status' => 'recommended',
                'product_mode' => 'lab-test',
            ])->selectRaw('COUNT(*) AS noOfLabTest')
                ->first();

            if ($labTests->noOfLabTest > 0) {
                // dd($input);
                $this->orderNotifyLabEditor($user_id, $this->findAssocValue($orderIDs, 'UMB'), $orderTypes, $cart_items, $lastCardNumber, $cardHolderName, $transaction_id);
                $this->add_lab_order_for_quest($input, $input['labtestAOEs'], $request->billing);
            }
            // dd('$labTests');
            // AFTER COMPLETE ORDER PRODUCT WILL BE REMOVE & MINUS FROM DB
            $this->Pharmacy->product_quantity_update($cart_items);
            $orderID = $this->findAssocValue($orderIDs, 'UMB');
            // E-Fax for medicines
            $recom_obj = new RecommendationController($this->allProductsRepository);
            $recom_obj->eprescription($cart_items, $request->billing, $orderID);
            // dd($cart_items);
            $response = [
                'orderID' => $orderID,
                'success' => true,
                'paymentStatus' => $paymentResult,
            ];
        } else {
            $response = [
                'orderID' => '',
                'success' => false,
                'paymentStatus' => $paymentResult,
            ];
        }

        echo json_encode($response);
    }
    public function orderNotifyLabEditor($user_id, $order_main_id, $orderTypes, $order_cart_items, $cardNumber, $cardHolder, $transaction_id)
    {
        try {
            $user_time_zone = Auth::user()->timeZone;
            $date = Carbon::now();
            $date->setTimezone(new DateTimeZone($user_time_zone));
            $order_date = $date->format('h:i:s A');
            $get_order_total = 0;
            foreach ($order_cart_items as $order_cart_item) {
                $get_order_total += $order_cart_item['update_price'];
            }
            $order_total = number_format($get_order_total, 2);
            $userDetails = array('cardNumber' => $cardNumber, 'cardHolder' => $cardHolder, 'transaction_id' => $transaction_id, 'order_total' => $order_total, 'order_date' => $order_date, 'order_id' => $order_main_id, 'pat_email' => Auth::user()->email);
            $lab_editors = User::where('user_type', 'editor_lab')->get();

            foreach ($lab_editors as $lab_editor) {
                Mail::to($lab_editor->email)->send(new OrderConfirmationEmail($order_cart_items, $userDetails));
                if ($user_id == "GUEST") {
                    $text = "New Order Place By " . Auth::user()->name;
                    $notification_id = Notification::create([
                        'user_id' => $lab_editor->id,
                        'type' => '/orders',
                        'text' => $text,
                    ]);
                    ActivityLog::create([
                        'user_id' => $lab_editor->id,
                        'type' => '/orders',
                        'text' => $text,
                    ]);
                    $data = [
                        'user_id' => $lab_editor->id,
                        'type' => '/orders',
                        'text' => $text,
                        'session_id' => "null",
                        'received' => 'false',
                        'appoint_id' => 'null',
                        'refill_id' => 'null',
                    ];
                    try {
                        // \App\Helper::firebase($user->id,'notification',$notification_id->id,$data);

                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                    event(new RealTimeMessage('Hello World'));
                } else {

                    $text = "New Order Place By " . Auth::user()->name;
                    Notification::create([
                        'user_id' => $lab_editor->id,
                        'type' => '/orders',
                        'text' => $text,
                    ]);
                    ActivityLog::create([
                        'user_id' => $lab_editor->id,
                        'activity' => 'purchased',
                        'identity' => 'xx',
                        'type' => '/orders',
                        'user_type' => 'patient',
                        'text' => $text,
                    ]);
                    $text = "New Order Place By " . $order_main_id;
                }
            }
        } catch (\Exception $e) {
            Log::error($e);
        }
    }

    public function orderNotifyToUser($user_id, $order_main_id, $orderTypes, $order_cart_items, $cardNumber, $cardHolder, $transaction_id)
    {
        // Notification Comments
        try {
            $user_time_zone = Auth::user()->timeZone;
            $date = Carbon::now();
            $date->setTimezone(new DateTimeZone($user_time_zone));
            $order_date = $date->format('h:i:s A');
            $get_order_total = 0;
            foreach ($order_cart_items as $order_cart_item) {
                $get_order_total += $order_cart_item['update_price'];
            }
            $order_total = number_format($get_order_total, 2);
            $userDetails = array('cardNumber' => $cardNumber, 'cardHolder' => $cardHolder, 'transaction_id' => $transaction_id, 'order_total' => $order_total, 'order_date' => $order_date, 'order_id' => $order_main_id, 'pat_email' => Auth::user()->email);
            Mail::to(Auth::user()->email)->send(new OrderConfirmationEmail($order_cart_items, $userDetails));
            $admin_data = User::where('user_type', 'admin')->first();
            if ($user_id == "GUEST") {
                $text = "New Order Place By " . Auth::user()->name;
                $notification_id = Notification::create([
                    'user_id' => $admin_data->id,
                    'type' => '/orders',
                    'text' => $text,
                ]);
                ActivityLog::create([
                    'user_id' => $admin_data->id,
                    'type' => '/orders',
                    'text' => $text,
                ]);
                $data = [
                    'user_id' => $admin_data->id,
                    'type' => '/orders',
                    'text' => $text,
                    'session_id' => "null",
                    'received' => 'false',
                    'appoint_id' => 'null',
                    'refill_id' => 'null',
                ];
                try {

                    // \App\Helper::firebase($admin_data->id,'notification',$notification_id->id,$data);
                } catch (\Throwable $th) {
                    //throw $th;
                }
                event(new RealTimeMessage($admin_data->id));
            } else {

                $text = "Order Complete Thank You For Choosing Us " . Auth::user()->name;
                $notification_id = Notification::create([
                    'user_id' => $user_id,
                    'type' => '/patient/all/orders',
                    'text' => $text,
                ]);
                $text = "New Order Place By " . Auth::user()->name;
                Notification::create([
                    'user_id' => $admin_data->id,
                    'type' => '/orders',
                    'text' => $text,
                ]);
                ActivityLog::create([
                    'user_id' => $admin_data->id,
                    'activity' => 'purchased',
                    'identity' => 'xx',
                    'type' => '/orders',
                    'user_type' => 'patient',
                    'text' => $text,
                ]);
                $text = "New Order Place By " . $order_main_id;
                $data = [
                    'user_id' => $user_id,
                    'type' => '/patient/all/orders',
                    'text' => $text,
                    'session_id' => "null",
                    'received' => 'false',
                    'appoint_id' => 'null',
                    'refill_id' => 'null',
                ];
                try {

                    // \App\Helper::firebase($user_id,'notification',$notification_id->id,$data);
                } catch (\Throwable $th) {
                    //throw $th;
                }
                event(new RealTimeMessage($user_id));
                event(new RealTimeMessage($admin_data->id));
            }
        } catch (\Exception $e) {
            Log::error($e);
        }
    }

    public function labtestForApprovals($items)
    {
        $data = [];
        foreach ($items as $item) {
            if ($item['item_type'] == "counter" && $item['product_mode'] == 'lab-test') {
                $data[] = [
                    'cart_row_id' => $item['cart_row_id'],
                    'product_id' => $item['product_id'],
                    'test_code' => $item['TEST_CD'],
                    'flag' => 0,
                ];
            }
        }
        if (count($data) > 0) {
            return $data;
        } else {
            return $data = [];
        }
    }

    public function addOrderByRows($orderCartItems, $user_id, $orderIDs)
    {
        foreach ($orderCartItems as $item) {
            if ($item['product_mode'] == 'lab-test') {
                $orderIDPrefix = $item['item_type'] == 'prescribed' ? 'PLBT' : 'LBT';
                $laborder = LabOrder::create([
                    'user_id' => $user_id,
                    'order_id' => $this->findAssocValue($orderIDs, 'UMB'),
                    'product_id' => $item['product_id'],
                    'session_id' => $item['doc_session_id'],
                    'pres_id' => $item['pres_id'],
                    'status' => 'pending',
                    'date' => date('Y-m-d'),
                    'time' => 0,
                    'map_marker_id' => 0,
                    'sub_order_id' => $this->findAssocValue($orderIDs, $orderIDPrefix),
                ]);
                // dd($laborder);
            } else if ($item['product_mode'] == 'imaging') {
                if ($item['item_type'] == 'prescribed') {
                    $price_obj = ImagingPrices::where('location_id', $item['location_id'])
                        ->where('product_id', $item['product_id'])->first();
                    if (isset($price_obj->price)) {
                        $price = $price_obj->price;
                    } else {
                        $price = 0;
                    }

                    if ($item['location_id'] == null) {
                        $item['location_id'] = '1';
                    }

                    ImagingOrder::create([
                        'user_id' => $user_id,
                        'order_id' => $this->findAssocValue($orderIDs, 'UMB'),
                        'product_id' => $item['product_id'],
                        'session_id' => $item['doc_session_id'],
                        'pres_id' => $item['pres_id'],
                        'location_id' => $item['location_id'],
                        'status' => 'pending',
                        'price' => $price,
                        // 'date' => $date,
                        // 'time' => $request->billing['lab_appointment_time'],
                        'sub_order_id' => $this->findAssocValue($orderIDs, 'PLBT'),
                    ]);
                }
            }
        }
    }

    public function checkOrderTypes($cart_items)
    {
        $str = [];
        // dd($cart_items);
        foreach ($cart_items as $item) {
            if ($item['product_mode'] == 'medicine' && $item['item_type'] == 'prescribed') {
                array_push($str, 'PPHAR');
            } else if ($item['product_mode'] == 'lab-test' && $item['item_type'] == 'prescribed') {
                array_push($str, 'PLBT');
            } else if ($item['product_mode'] == 'imaging' && $item['item_type'] == 'prescribed') {
                array_push($str, 'PIMG');
            } else if ($item['product_mode'] == 'medicine' && $item['item_type'] == 'counter') {
                array_push($str, 'PHAR');
            } else if ($item['product_mode'] == 'lab-test' && $item['item_type'] == 'counter') {
                array_push($str, 'LBT');
            } else if ($item['product_mode'] == 'imaging' && $item['item_type'] == 'counter') {
                array_push($str, 'IMG');
            }
            array_push($str, 'UMB');
        }
        // dd($str);
        return array_unique($str);
    }

    public function complete_order(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            if (!empty($request->order_id)) {
                $order_id = $request->order_id;
                $data = $this->Pharmacy->get_data_cart_page_by_user_id($user['id'], 'all', 'checkout');
                if (!empty($data)) {
                    foreach ($data as $item) {
                        $update = AppTblCart::where('product_id', $item->product_id)
                            ->where('user_id', $user['id'])
                            ->update(
                                ['purchase_status' => 0, 'checkout_status' => 0, 'status' => 'purchased']
                            );
                    }
                }
                // dd($order_id);
                return view('complete_order', compact('order_id'));
            } else {
                return redirect('/cart');
            }
        } else {
            return redirect('/');
        }
    }

    public function orderCartItems($cart_items)
    {
        $cart = [];
        foreach ($cart_items as $key => $val) {
            $a['product_id'] = $val['product_id'];
            $a['product_qty'] = $val['quantity'];
            $a['pres_id'] = $val['pres_id'];
            $a['doc_session_id'] = $val['doc_session_id'];
            $a['product_mode'] = $val['product_mode'];
            $a['item_type'] = $val['item_type'];
            $a['quantity'] = $val['quantity'];
            $a['product_name'] = $val['name'];
            $a['update_price'] = $val['update_price'];
            $a['price'] = $val['price'];
            if ($val['product_mode'] == 'imaging') {
                $a['location_id'] = $val['location_id'];
            } else {
                $a['location_id'] = 0;
            }
            array_push($cart, $a);
        }
        return $cart;
    }

    // CREATE ORDER FOR WEB / APP

    // LOCATIONS

    public function add_pharmacy_location()
    {
        return view("add_pharmacy_location");
    }

    public function add_location(Request $request)
    {
        $validateData = $request->validate([
            'l_name' => ['required'],
            'l_address' => ['required'],
            'l_lat' => ['required'],
            'l_long' => ['required'],
            'l_zipcode' => ['required'],
        ]);
        $insert = DB::insert('insert into pharmacy_location (l_name, l_address, l_lat, l_long, l_zipcode) values (?, ?, ?, ?, ?)', [$validateData['l_name'], $validateData['l_address'], $validateData['l_lat'], $validateData['l_long'], $validateData['l_zipcode']]);

        if ($insert == 1) {

            return redirect('add_pharmacy_location')->with('message', "Add Location Sucessfully");
        }
    }

    public function view_pharmacy_location()
    {
        $data = DB::select('select * from pharmacy_location');
        return view("view_pharmacy_location", compact("data"));
    }

    public function delete_pharmacy_location($id)
    {
        $data = DB::delete('delete from pharmacy_location where id = ?', [$id]);
        return redirect('view_pharmacy_location')->with('message', "Delete Record Successfully");
    }

    public function edit_pharmacy_location($id)
    {
        $data = DB::select('select * from pharmacy_location where id = ?', [$id]);
        return view("edit_pharmacy_location", compact("data"));
    }

    public function update_pharmacy_location(Request $request)
    {
        $id = $request->input('id');
        $l_name = $request->input('l_name');
        $l_address = $request->input('l_address');
        $l_lat = $request->input('l_lat');
        $l_long = $request->input('l_long');
        $l_zipcode = $request->input('l_zipcode');
        $update = DB::update('update pharmacy_location set l_name = ?,l_address=?,l_lat=?,l_long=?,l_zipcode=? where id = ?', [$l_name, $l_address, $l_lat, $l_long, $l_zipcode, $id]);
        if ($update == 1) {
            return redirect('view_pharmacy_location')->with('message', "Update Record Successfully");
        }
    }

    public function get_cities_by_state($state_code = "")
    {
        $data = $this->Pharmacy->get_states_cities($state_code);
        echo json_encode($data);
    }

    public function get_lang_long($zipCode = '')
    {

        $data['data'] = $this->Pharmacy->get_lat_long_of_zipcode($zipCode);
        if (count($data['data']) === 0) {
            echo json_encode(0);
        } else {
            $data['locations'] = $this->Pharmacy->get_nearby_places($data['data'][0]->lat, $data['data'][0]->long);
            echo json_encode($data);
        }
    }

    public function get_near_location($lat = '', $long = '')
    {

        $data = $this->Pharmacy->get_nearby_places($lat, $long);

        // Start XML file,   parent nodeg

        $dom = new \DOMDocument("1.0");
        $node = $dom->createElement("markers");
        $parnode = $dom->appendChild($node);

        header("Content-type: text/xml");

        // Iterate through the rows, adding XML nodes for each

        foreach ($data as $key => $val) {
            // Add to XML document node
            $node = $dom->createElement("marker");
            $newnode = $parnode->appendChild($node);
            $newnode->setAttribute("id", $val->id);
            $newnode->setAttribute("name", $val->name);
            $newnode->setAttribute("address", $val->address);
            $newnode->setAttribute("lat", $val->lat);
            $newnode->setAttribute("lng", $val->long);
            $newnode->setAttribute("type", 'pharmacy');
        }

        $myfile = fopen("test.xml", "w") or die("Unable to open file!");
        $txt = "";
        fwrite($myfile, $txt);
        $txt = $dom->saveXML();
        fwrite($myfile, $txt);
        fclose($myfile);

        echo json_encode(1);
    }

    public function get_neareast_pharmacy(Request $request)
    {
        echo $zip = $request["zipCode"];
        $json = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyCw242E8X5zxLneor4jRIFUsQA7BsXFEPA&components=postal_code:' . $zip);
        $obj = json_decode($json);
        foreach ($obj->results as $idx => $results) {
            $data[3] = $lat = $results->geometry->location->lat;
            $data[4] = $lng = $results->geometry->location->lng;
        }
        echo $lat = $data[3];
        echo $long = $data[4];
    }

    public function parseToXML($htmlStr)
    {
        $xmlStr = str_replace('<', '&lt;', $htmlStr);
        $xmlStr = str_replace('>', '&gt;', $xmlStr);
        $xmlStr = str_replace('"', '&quot;', $xmlStr);
        $xmlStr = str_replace("'", '&#39;', $xmlStr);
        $xmlStr = str_replace("&", '&amp;', $xmlStr);
        return $xmlStr;
    }

    // LOCATIONS

    // UTILITIES

    public function converToObjToArray($data)
    {
        return json_decode(json_encode($data), true);
    }

    public function findAssocValue($orderIDs, $col)
    {
        foreach ($orderIDs as $key => $val) {
            if ($key == $col) {
                return $val;
            }
        }
    }
    public function substance($slug = "")
    {

        $type = explode("/", $_SERVER['REQUEST_URI']);
        $sideMenus = $this->getSideMenuByType($type[1]);

        if (!empty($slug)) {
            // Get Products Slug Wise
            $products = $this->Pharmacy->getProductBySlug($slug);
        } else {
            // Get Products Order By Desc
            $products = [];
        }

        $data['sidebar'] = $sideMenus;
        $data['url_type'] = $type;
        $data['data'] = $products[0];
        // dd($data);
        return view('substance', compact('data', 'slug'));
    }
    public function getLabTestAoesDuringSession(Request $request)
    {
        $getTestAOE = QuestDataAOE::select("TEST_CD AS TestCode", "AOE_QUESTION AS QuestionShort", "AOE_QUESTION_DESC AS QuestionLong", 'ANALYTE_CD as ques_id')
            ->where('TEST_CD', $request->id)
            ->groupBy('AOE_QUESTION_DESC')
            ->get();
        $res = DB::table('patient_lab_recomend_aoe')->where('session_id', $request->session_id)->where('testCode', $request->id)->first();
        if ($res != null) {
            // dd(unserialize($res->aoes));
            return Response(['aoes' => unserialize($res->aoes), 'answer' => 1]);
        } else {
            $count = count($getTestAOE);
            if ($count > 0) {

                return Response(['aoes' => $getTestAOE, 'answer' => 0]);
            } else {
                return "nothing";
            }
        }
    }
    public function add_labtest_aoes_into_db(Request $request)
    {
        $aoes = serialize($request->inputValue);
        $record = DB::table('patient_lab_recomend_aoe')
            ->where('testCode', $request->getTestCode)
            ->where('session_id', $request->session_id)
            ->count();
        if ($record > 0) {
            DB::table('patient_lab_recomend_aoe')
                ->where('testCode', $request->getTestCode)
                ->where('session_id', $request->session_id)
                ->update([
                    'aoes' => $aoes
                ]);
            return "ok";
        } else {
            DB::table('patient_lab_recomend_aoe')
                ->insert([
                    'aoes' => $aoes,
                    'testCode' => $request->getTestCode,
                    'session_id' => $request->session_id,
                ]);
            return "ok";
        }
    }
}
