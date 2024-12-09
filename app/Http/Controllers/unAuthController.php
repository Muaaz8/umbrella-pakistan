<?php

namespace App\Http\Controllers;

use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cookie;
use App\Models\Symptoms_Checker;
use Illuminate\Support\Facades\Crypt;
use App\Session;
use Response;

class unAuthController extends Controller
{
    public function getDataByZipCode(Request $request)
    {
        $zip = $request['zip'];
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . $zip . "&sensor=true&key=" . env('GOOGLE_APIKEY');
        $json = file_get_contents($url);
        $data = json_decode($json);
        $status = $data->status;
        $country_id = "";
        $state_id = "";
        $city_id = "";
        // return $status;
        if ($status == "OK") {

            $complete_address = $data->results[0]->formatted_address;
            $address = explode(",", $complete_address);
            $countryName = str_replace(' ', '', $address[2]);
            if ($countryName == "USA") {
                $countryID = DB::table("countries")->where('iso3', $countryName)->first();
                if ($countryID != null) {
                    $country_name = $countryID->name;
                    $country_id = $countryID->id;
                    $stateID = DB::table("states")->where('state_code', substr($address[1], 1, 2))->where('country_id', $countryID->id)->first();
                    if ($stateID != null) {
                        $state_name = $stateID->name;
                        $state_id = $stateID->id;
                        $cityID = DB::table("cities")->where('name', $address[0])->where('state_id', $stateID->id)->where('country_id', $countryID->id)->first();
                        if ($cityID == null) {
                            $res = DB::table("cities")->insert([
                                'name' => $address[0],
                                'state_id' => $stateID->id,
                                'state_code' => $stateID->state_code,
                                'country_id' => $countryID->id,
                                'country_code' => $countryID->iso2,
                                'latitude' => $data->results[0]->geometry->location->lat,
                                'longitude' => $data->results[0]->geometry->location->lng,
                                'flag' => 1,
                            ]);
                            if ($res == 1) {
                                $all_citys = DB::table("cities")
                                    ->where('state_id', $stateID->id)
                                    ->where('country_id', $countryID->id)
                                    ->get();
                            }
                        } else {
                            $all_citys = DB::table("cities")
                                ->where('state_id', $stateID->id)
                                ->where('country_id', $countryID->id)
                                ->get();
                        }
                    }
                }
            }
            return response()->json(array('country_id' => $country_id, 'country_name' => $country_name, 'state_id' => $state_id, 'state_name' => $state_name, 'all_citys' => $all_citys), 200);
        } else {
            return response()->json(array('country_id' => $country_id), 200);
        }
    }
    public function fetchPharmacyItemByCategory(Request $request)
    {
        if ($request->sub_cat_id != 'all') {
            $products = DB::table('tbl_products')
                ->join('medicine_pricings', 'medicine_pricings.product_id', 'tbl_products.id')
                ->join('products_sub_categories', 'products_sub_categories.id', 'tbl_products.sub_category')
                ->where('tbl_products.sub_category', $request->sub_cat_id)
                ->select(
                    'tbl_products.*',
                    'products_sub_categories.title as category_name',
                    'products_sub_categories.slug as category_slug',
                    DB::raw('MIN(medicine_pricings.sale_price) as sale_price')
                )
                ->groupBy('tbl_products.id', 'products_sub_categories.title', 'products_sub_categories.slug') // group by product and category fields
                ->inRandomOrder()
                ->limit($request->limit)
                ->get();

            foreach ($products as $product) {
                $product->short_description = strip_tags($product->short_description);
                $product->featured_image = \App\Helper::check_bucket_files_url($product->featured_image);
                if($product->featured_image == env('APP_URL')."/assets/images/user.png"){
                    $product->featured_image = asset('assets/new_frontend/panadol2.png');
                }
            }
        } else {
            $products = DB::table('tbl_products')
                ->join('medicine_pricings', 'medicine_pricings.product_id', 'tbl_products.id')
                ->join('products_sub_categories', 'products_sub_categories.id', 'tbl_products.sub_category')
                ->select(
                    'tbl_products.*',
                    'products_sub_categories.title as category_name',
                    'products_sub_categories.slug as category_slug',
                    DB::raw('MIN(medicine_pricings.sale_price) as sale_price')
                )
                ->where('tbl_products.featured_image','!=','dummy_medicine.png')
                ->groupBy('tbl_products.id', 'products_sub_categories.title', 'products_sub_categories.slug') // group by product and category fields
                ->inRandomOrder()
                ->limit($request->limit)
                ->get();

            foreach ($products as $product) {
                $product->short_description = strip_tags($product->short_description);
                $product->featured_image = \App\Helper::check_bucket_files_url($product->featured_image);
                if($product->featured_image == env('APP_URL')."/assets/images/user.png"){
                    $product->featured_image = asset('assets/new_frontend/panadol2.png');
                }
            }
        }
        return $products;
    }

    public function searchPharmacyItemByCategory(Request $request)
    {
        // dd($request->all());
        if ($request->cat_id == 'all' && strlen($request->text) < 4) {
            $products = DB::table('tbl_products')
                ->join('medicine_pricings', 'medicine_pricings.product_id', 'tbl_products.id')
                ->join('products_sub_categories', 'products_sub_categories.id', 'tbl_products.sub_category')
                ->where('tbl_products.mode', 'medicine')
                ->where('tbl_products.medicine_type', 'prescribed')
                ->where('tbl_products.sub_category', $request->sub_cat_id)
                ->where('tbl_products.name', 'LIKE', $request->text . '%')
                ->select(
                    'tbl_products.*',
                    'products_sub_categories.title as category_name',
                    'products_sub_categories.slug as category_slug',
                    DB::raw('MIN(medicine_pricings.sale_price) as sale_price')
                )
                ->groupBy('tbl_products.id', 'products_sub_categories.title', 'products_sub_categories.slug') // group by product and category fields
                ->get();
            foreach ($products as $product) {
                $product->short_description = strip_tags($product->short_description);
                $product->featured_image = \App\Helper::check_bucket_files_url($product->featured_image);
                if($product->featured_image == env('APP_URL')."/assets/images/user.png"){
                    $product->featured_image = asset('assets/new_frontend/panadol2.png');
                }
            }
        } else if ($request->cat_id != 'all' && strlen($request->text) < 4) {
            $products = DB::table('tbl_products')
                ->join('medicine_pricings', 'medicine_pricings.product_id', 'tbl_products.id')
                ->join('products_sub_categories', 'products_sub_categories.id', 'tbl_products.sub_category')
                ->where('tbl_products.mode', 'medicine')
                ->where('tbl_products.medicine_type', 'prescribed')
                ->where('tbl_products.sub_category', $request->sub_cat_id)
                ->where('tbl_products.name', 'LIKE', $request->text . '%')
                ->select(
                    'tbl_products.*',
                    'products_sub_categories.title as category_name',
                    'products_sub_categories.slug as category_slug',
                    DB::raw('MIN(medicine_pricings.sale_price) as sale_price')
                )
                ->groupBy('tbl_products.id', 'products_sub_categories.title', 'products_sub_categories.slug') // group by product and category fields
                ->get();
            foreach ($products as $product) {
                $product->short_description = strip_tags($product->short_description);
                $product->featured_image = \App\Helper::check_bucket_files_url($product->featured_image);
                if($product->featured_image == env('APP_URL')."/assets/images/user.png"){
                    $product->featured_image = asset('assets/new_frontend/panadol2.png');
                }
            }
        } else if ($request->cat_id == 'all' && strlen($request->text) >= 4) {
            $products = DB::table('tbl_products')
                ->join('medicine_pricings', 'medicine_pricings.product_id', 'tbl_products.id')
                ->join('products_sub_categories', 'products_sub_categories.id', 'tbl_products.sub_category')
                ->where('tbl_products.mode', 'medicine')
                ->where('tbl_products.medicine_type', 'prescribed')
                ->where('tbl_products.name', 'LIKE', $request->text . '%')
                ->select(
                    'tbl_products.*',
                    'products_sub_categories.title as category_name',
                    'products_sub_categories.slug as category_slug',
                    DB::raw('MIN(medicine_pricings.sale_price) as sale_price')
                )
                ->groupBy('tbl_products.id', 'products_sub_categories.title', 'products_sub_categories.slug') // group by product and category fields
                ->get();
            foreach ($products as $product) {
                $product->short_description = strip_tags($product->short_description);
                $product->featured_image = \App\Helper::check_bucket_files_url($product->featured_image);
                if($product->featured_image == env('APP_URL')."/assets/images/user.png"){
                    $product->featured_image = asset('assets/new_frontend/panadol2.png');
                }
            }
        } else if ($request->cat_id != 'all' && strlen($request->text) >= 4) {
            $products = DB::table('tbl_products')
                ->join('medicine_pricings', 'medicine_pricings.product_id', 'tbl_products.id')
                ->join('products_sub_categories', 'products_sub_categories.id', 'tbl_products.sub_category')
                ->where('tbl_products.mode', 'medicine')
                ->where('tbl_products.medicine_type', 'prescribed')
                ->where('tbl_products.name', 'LIKE', $request->text . '%')
                ->select(
                    'tbl_products.*',
                    'products_sub_categories.title as category_name',
                    'products_sub_categories.slug as category_slug',
                    DB::raw('MIN(medicine_pricings.sale_price) as sale_price')
                )
                ->groupBy('tbl_products.id', 'products_sub_categories.title', 'products_sub_categories.slug') // group by product and category fields
                ->get();
            foreach ($products as $product) {
                $product->short_description = strip_tags($product->short_description);
                $product->featured_image = \App\Helper::check_bucket_files_url($product->featured_image);
                if($product->featured_image == env('APP_URL')."/assets/images/user.png"){
                    $product->featured_image = asset('assets/new_frontend/panadol2.png');
                }
            }
        }


        return $products;
    }

    public function fetchLabtestItemByCategory(Request $request)
    {
        if ($request->cat_id != 'all') {
            $cat_name = DB::table('product_categories')->where('id', $request->cat_id)->first();

            $products = DB::table('quest_data_test_codes')
                ->where('SALE_PRICE', '!=', null)
                ->where('AOES_exist', null)
                ->where('PARENT_CATEGORY', 'LIKE', '%' . $request->cat_id . '%')
                ->limit($request->limit)
                ->get();
            foreach ($products as $product) {
                $product->cat_name = $cat_name->slug;
                $product->DETAILS = strip_tags($product->DETAILS);
                $product->SALE_PRICE = number_format($product->SALE_PRICE, 2);
            }
        } else {
            $products = DB::table('quest_data_test_codes')
                ->where('SALE_PRICE', '!=', null)
                ->where('AOES_exist', null)
                // ->where('PARENT_CATEGORY', [21, 25])
                // ->orderBy('TEST_NAME', 'ASC')
                ->inRandomOrder()
                ->limit($request->limit)
                ->get();
            foreach ($products as $product) {
                $product->DETAILS = strip_tags($product->DETAILS);
                $product->SALE_PRICE = number_format($product->SALE_PRICE, 2);
            }
        }
        if (Auth::check()) {
            $user_id = Auth::user()->id;
        } else {
            $user_id = '';
        }
        return array('products' => $products, 'user_id' => $user_id);
        // dd($products);
        // return $products;
    }
    public function fetchImagingItemByCategory(Request $request)
    {
        // if ($request->cat_id != 'all') {
        //     $products = DB::table('tbl_products')
        //         ->join('product_categories', 'product_categories.id', 'tbl_products.parent_category')
        //         ->where('tbl_products.parent_category', $request->cat_id)
        //         ->where('tbl_products.short_description', '!=', null)
        //         ->select('tbl_products.*', 'product_categories.slug as cat_name')
        //         ->limit($request->limit)
        //         ->get();
        //     foreach ($products as $product) {
        //         $product->short_description = strip_tags($product->short_description);
        //     }
        // } else {
        //     $products = DB::table('tbl_products')
        //         ->where('tbl_products.mode', 'imaging')
        //         ->where('tbl_products.short_description', '!=', null)
        //         ->inRandomOrder()
        //         ->limit($request->limit)
        //         ->get();
        //     foreach ($products as $product) {
        //         $product->short_description = strip_tags($product->short_description);
        //     }
        // }
        // return $products;
        if ($request->cat_id != 'all') {
            $cat_name = DB::table('product_categories')->where('id', $request->cat_id)->first();

            $products = DB::table('quest_data_test_codes')
                ->where('SALE_PRICE', '!=', null)
                ->where('AOES_exist', null)
                ->where('PARENT_CATEGORY', 'LIKE', '%' . $request->cat_id . '%')
                ->limit($request->limit)
                ->get();
            foreach ($products as $product) {
                $product->cat_name = $cat_name->slug;
                $product->DETAILS = strip_tags($product->DETAILS);
                $product->SALE_PRICE = number_format($product->SALE_PRICE, 2);
            }
        } else {
            $products = DB::table('quest_data_test_codes')
                ->where('SALE_PRICE', '!=', null)
                ->where('AOES_exist', null)
                ->where('mode','imaging')
                // ->where('PARENT_CATEGORY', [21, 25])
                // ->orderBy('TEST_NAME', 'ASC')
                ->inRandomOrder()
                ->limit($request->limit)
                ->get();
            foreach ($products as $product) {
                $product->DETAILS = strip_tags($product->DETAILS);
                $product->SALE_PRICE = number_format($product->SALE_PRICE, 2);
            }
        }
        if (Auth::check()) {
            $user_id = Auth::user()->id;
        } else {
            $user_id = '';
        }
        return array('products' => $products, 'user_id' => $user_id);
    }
    public function searchPharmacyItem(Request $request)
    {
        if (strlen($request->text) < 4) {
            $products = DB::table('tbl_products')
                ->join('products_sub_categories', 'products_sub_categories.id', 'tbl_products.sub_category')
                ->where('tbl_products.name', 'LIKE', $request->text . '%')
                ->where('tbl_products.mode', 'medicine')
                ->where('tbl_products.medicine_type', 'prescribed')
                ->select('tbl_products.*', 'products_sub_categories.title as category_name', 'products_sub_categories.slug as category_slug')
                ->limit($request->limit)
                ->get();
        } else {
            $products = DB::table('tbl_products')
                ->join('products_sub_categories', 'products_sub_categories.id', 'tbl_products.sub_category')
                ->where('tbl_products.name', 'LIKE', '%' . $request->text . '%')
                ->where('tbl_products.mode', 'medicine')
                ->where('tbl_products.medicine_type', 'prescribed')
                ->select('tbl_products.*', 'products_sub_categories.title as category_name', 'products_sub_categories.slug as category_slug')
                ->limit($request->limit)
                ->get();
        }

        foreach ($products as $product) {
            $product->short_description = strip_tags($product->short_description);
        }
        return $products;
    }
    public function searchLabItem(Request $request)
    {
        if (strlen($request->text) < 4) {
            $products = DB::table('quest_data_test_codes')
                ->where('TEST_NAME', 'LIKE', $request->text . '%')
                ->where('SALE_PRICE', '!=', null)
                ->where('AOES_exist', null)
                ->limit($request->limit)
                ->get();
            foreach ($products as $product) {
                $product->DETAILS = strip_tags($product->DETAILS);
                $product->SALE_PRICE = number_format($product->SALE_PRICE, 2);
            }
        } else {
            $products = DB::table('quest_data_test_codes')
                ->where('TEST_NAME', 'LIKE', '%' . $request->text . '%')
                ->where('SALE_PRICE', '!=', null)
                ->where('AOES_exist', null)
                ->limit($request->limit)
                ->get();
            foreach ($products as $product) {
                $product->DETAILS = strip_tags($product->DETAILS);
                $product->SALE_PRICE = number_format($product->SALE_PRICE, 2);
            }
        }
        if (Auth::check()) {
            $user_id = Auth::user()->id;
        } else {
            $user_id = '';
        }
        return array('products' => $products, 'user_id' => $user_id);
        // return $products;
    }
    public function searchImagingItem(Request $request)
    {
        if (strlen($request->text) < 4) {
            $products = DB::table('tbl_products')
                ->where('tbl_products.short_description', '!=', null)
                ->where('tbl_products.name', 'LIKE', $request->text . '%')
                ->where('tbl_products.mode', 'imaging')
                ->where('tbl_products.medicine_type', 'prescribed')
                ->limit($request->limit)
                ->get();
            foreach ($products as $product) {
                $product->short_description = strip_tags($product->short_description);
            }
        } else {
            $products = DB::table('tbl_products')
                ->where('tbl_products.short_description', '!=', null)
                ->where('tbl_products.name', 'LIKE', '%' . $request->text . '%')
                ->where('tbl_products.mode', 'imaging')
                ->where('tbl_products.medicine_type', 'prescribed')
                ->limit($request->limit)
                ->get();
            foreach ($products as $product) {
                $product->short_description = strip_tags($product->short_description);
            }
        }
        return $products;
    }
    public function searchLabItemByCategory(Request $request)
    {
        if ($request->cat_id == 'all' && strlen($request->text) < 4) {
            $products = DB::table('quest_data_test_codes')
                ->where('TEST_NAME', 'LIKE', $request->text . '%')
                ->where('SALE_PRICE', '!=', null)
                ->where('AOES_exist', null)
                ->limit(10)
                ->get();
            foreach ($products as $product) {
                $product->DETAILS = strip_tags($product->DETAILS);
                $product->SALE_PRICE = number_format($product->SALE_PRICE, 2);
            }
        } else if ($request->cat_id != 'all' && strlen($request->text) < 4) {

            $products = DB::table('quest_data_test_codes')
                ->where('TEST_NAME', 'LIKE', $request->text . '%')
                ->where('SALE_PRICE', '!=', null)
                ->where('AOES_exist', null)
                ->whereRaw("find_in_set('$request->cat_id',`PARENT_CATEGORY`)")
                // ->where('PARENT_CATEGORY', $request->cat_id)
                ->limit(10)
                ->get();
            // dd($products);
            foreach ($products as $product) {
                $product->DETAILS = strip_tags($product->DETAILS);
                $product->SALE_PRICE = number_format($product->SALE_PRICE, 2);
            }
        } else if ($request->cat_id == 'all' && strlen($request->text) >= 4) {
            $products = DB::table('quest_data_test_codes')
                ->where('TEST_NAME', 'LIKE', '%' . $request->text . '%')
                ->where('SALE_PRICE', '!=', null)
                ->where('AOES_exist', null)
                ->limit(10)
                ->get();
            foreach ($products as $product) {
                $product->DETAILS = strip_tags($product->DETAILS);
                $product->SALE_PRICE = number_format($product->SALE_PRICE, 2);
            }
        } else if ($request->cat_id != 'all' && strlen($request->text) >= 4) {
            $products = DB::table('quest_data_test_codes')
                ->where('TEST_NAME', 'LIKE', '%' . $request->text . '%')
                ->where('SALE_PRICE', '!=', null)
                // ->where('PARENT_CATEGORY', $request->cat_id)
                ->whereRaw("find_in_set('$request->cat_id',`PARENT_CATEGORY`)")
                ->where('AOES_exist', null)
                ->limit(10)
                ->get();
            foreach ($products as $product) {
                $product->DETAILS = strip_tags($product->DETAILS);
                $product->SALE_PRICE = number_format($product->SALE_PRICE, 2);
            }
        }
        if (Auth::check()) {
            $user_id = Auth::user()->id;
        } else {
            $user_id = '';
        }
        return array('products' => $products, 'user_id' => $user_id);
        // return $products;
    }
    public function searchImagingItemByCategory(Request $request)
    {
        if ($request->cat_id == 'all' && strlen($request->text) < 4) {
            $products = DB::table('tbl_products')
                ->where('tbl_products.short_description', '!=', null)
                ->where('tbl_products.name', 'LIKE', $request->text . '%')
                ->where('tbl_products.mode', 'imaging')
                ->where('tbl_products.medicine_type', 'prescribed')
                ->limit(10)
                ->get();
            foreach ($products as $product) {
                $product->short_description = strip_tags($product->short_description);
            }
        } else if ($request->cat_id != 'all' && strlen($request->text) < 4) {
            $products = DB::table('tbl_products')
                ->where('tbl_products.short_description', '!=', null)
                ->where('tbl_products.name', 'LIKE', $request->text . '%')
                ->where('tbl_products.parent_category', 'LIKE', $request->cat_id)
                ->where('tbl_products.mode', 'imaging')
                ->where('tbl_products.medicine_type', 'prescribed')
                ->limit(10)
                ->get();
            foreach ($products as $product) {
                $product->short_description = strip_tags($product->short_description);
            }
        } else if ($request->cat_id == 'all' && strlen($request->text) >= 4) {
            $products = DB::table('tbl_products')
                ->where('tbl_products.short_description', '!=', null)
                ->where('tbl_products.name', 'LIKE', '%' . $request->text . '%')
                ->where('tbl_products.mode', 'imaging')
                ->where('tbl_products.medicine_type', 'prescribed')
                ->limit(10)
                ->get();
            foreach ($products as $product) {
                $product->short_description = strip_tags($product->short_description);
            }
        } else if ($request->cat_id != 'all' && strlen($request->text) >= 4) {
            $products = DB::table('tbl_products')
                ->where('tbl_products.short_description', '!=', null)
                ->where('tbl_products.name', 'LIKE', '%' . $request->text . '%')
                ->where('tbl_products.parent_category', 'LIKE', $request->cat_id)
                ->where('tbl_products.mode', 'imaging')
                ->where('tbl_products.medicine_type', 'prescribed')
                ->limit(10)
                ->get();
            foreach ($products as $product) {
                $product->short_description = strip_tags($product->short_description);
            }
        }


        return $products;
    }
    public function payment()
    {




        define("AUTHORIZENET_LOG_FILE", "phplog");

        // Common setup for API credentials
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName("YOUR_API_LOGIN_ID");
        $merchantAuthentication->setTransactionKey("YOUR_TRANSACTION_KEY");
        $refId = 'ref' . time();

        // Create the payment data for a credit card
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber("4111111111111111");
        $creditCard->setExpirationDate("2038-12");
        $paymentOne = new AnetAPI\PaymentType();
        $paymentOne->setCreditCard($creditCard);

        // Create a transaction
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("authCaptureTransaction");
        $transactionRequestType->setAmount(151.51);
        $transactionRequestType->setPayment($paymentOne);
        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setTransactionRequest($transactionRequestType);
        $controller = new AnetController\CreateTransactionController($request);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);

        if ($response != null) {
            $tresponse = $response->getTransactionResponse();
            if (($tresponse != null) && ($tresponse->getResponseCode() == "1")) {
                echo "Charge Credit Card AUTH CODE : " . $tresponse->getAuthCode() . "\n";
                echo "Charge Credit Card TRANS ID  : " . $tresponse->getTransId() . "\n";
            } else {
                echo "Charge Credit Card ERROR :  Invalid response\n";
            }
        } else {
            echo  "Charge Credit Card Null response returned";
        }
    }

    public function symptom_checker_cookie_store(Request $request){
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'age' => 'required',
            'gender' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        if($request->evisit == 1){

        } else{
            $user_info = ['email' => $request->email, 'name' => $request->name ,'age' => $request->age, 'gender' => $request->gender];
            $user_info_json = json_encode($user_info);
            Cookie::queue(Cookie::make('symptom_user', $user_info_json, 180)); //180 = validate cookie for 180mins only
            return response()->json("Cookie Added Successfully.",200);
        }
    }
    public function chat(Request $request){
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'chatbot.umbrellamd-video.com/chat',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => json_encode(array('message' => $request->message,'session_id'=>$request->session_id)),
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response,true);
    }

    public function done(Request $request){
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'chatbot.umbrellamd-video.com/done',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => json_encode(array('session_id'=>$request->session_id)),
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
          ),
        ));
        $response = curl_exec($curl);
        $dcode_response = json_decode($response,true);
        if($request->evisit == 1){
            curl_close($curl);
            if(Auth::check()){
               $syschecker_id=  Symptoms_Checker::create([
                    'user_id' => auth()->user()->id,
                    'clinical_evaluation' => $dcode_response['clinical_evaluation'],
                    'hypothesis_report' => $dcode_response['hypothesis_report'],
                    'intake_notes' => $dcode_response['intake_notes'],
                    'referrals_and_tests' => $dcode_response['referrals_and_tests'],
                ]);
                $doc_id = $request->doctorId;
                $check_session_already_have = DB::table('sessions')
                    ->where('doctor_id', $doc_id)
                    ->where('patient_id', auth()->user()->id)
                    ->where('specialization_id', $request->specializationId)
                    ->count();


                $session_price = "";
                if ($check_session_already_have > 0) {
                    $session_price_get = DB::table('specalization_price')->where('spec_id', $request->specializationId)->first();
                    if ($session_price_get->follow_up_price != null) {
                        $session_price = $session_price_get->follow_up_price;
                    } else {
                        $session_price = $session_price_get->initial_price;
                    }
                } else {
                    $session_price_get = DB::table('specalization_price')->where('spec_id', $request->specializationId)->first();
                    $session_price = $session_price_get->initial_price;
                }

                $timestamp = time();
                $date = date('Y-m-d', $timestamp);
                $permitted_chars = 'abcdefghijklmnopqrstuvwxyz';
                $channelName = substr(str_shuffle($permitted_chars), 0, 8);
                $get_last_session = DB::table('sessions')->where('doctor_id', $doc_id)->where('status', 'invitation sent')->orderBy('id', 'desc')->first();
                $queue = 0;
                if ($get_last_session != null) {
                    $queue = $get_last_session->queue + 1;
                } else {
                    $queue = 1;
                }
                $new_session_id;
                $randNumber=rand(11,99);
                $getLastSessionId = DB::table('sessions')->orderBy('id', 'desc')->first();
                if ($getLastSessionId != null) {
                    $new_session_id = $getLastSessionId->session_id + 1+$randNumber;
                } else {
                    $new_session_id = rand(311111,399999);
                }
                $session_id = Session::create([
                    'patient_id' =>  auth()->user()->id,
                    'doctor_id' =>  $doc_id,
                    'date' =>  $date,
                    'status' => 'pending',
                    'queue' => $queue,
                    'symptom_id' => $syschecker_id->id,
                    'remaining_time' => 'full',
                    'channel' => $channelName,
                    'price' => $session_price,
                    'specialization_id' => $request->specializationId,
                    'session_id' => $new_session_id,
                    'validation_status' => "valid",
                ])->id;
                $encryptedSessionId = Crypt::encrypt($session_id);
                // $sessionData['session_id'] = $session_id;
                $sessionData['message'] = 'session_payment_page';
                $sessionData['route'] = $url = route('patient_session_payment_page', ['id' => $encryptedSessionId]);
                return Response::json($sessionData);
                // return redirect()->route('session_payment_page', ['id' => $session_id]);
            }
        } else if($request->evisit == 2){
            curl_close($curl);
            $user = auth()->user();
            $responseData['user'] = $user;
            $responseData['doc_id'] = $request->doctorId;
            return Response::json($responseData);
        } else{
            Cookie::queue(Cookie::make('chat_done_cookie', $response, 180));
            curl_close($curl);
            if(Auth::check()){
                $requestData['auth'] = 1;
                $requestData['response_api'] = json_decode($response,true);
                return Response::json($requestData);
            } else{
                $requestData['auth'] = 0;
                return Response::json($requestData);
            }
        }
    }
    public function check_cookie(){
       $cookieData = request()->cookie('chat_done_cookie');
        if($cookieData !=null){
            $data = json_decode($cookieData);
            if(Auth::check()){
                if(auth()->user()->user_type == 'doctor' && auth()->user()->active == 1){
                    Symptoms_Checker::create([
                        'user_id' => auth()->user()->id,
                        'clinical_evaluation' => $data->clinical_evaluation,
                        'hypothesis_report' => $data->hypothesis_report,
                        'intake_notes' => $data->intake_notes,
                        'referrals_and_tests' => $data->referrals_and_tests,
                    ]);
                } else if(auth()->user()->user_type == 'patient'){
                    Symptoms_Checker::create([
                        'user_id' => auth()->user()->id,
                        'clinical_evaluation' => $data->clinical_evaluation,
                        'hypothesis_report' => $data->hypothesis_report,
                        'intake_notes' => $data->intake_notes,
                        'referrals_and_tests' => $data->referrals_and_tests,
                    ]);
                }
            } else{
                $data = 'UnAuth';
            }
        } else{
            $data = 0;
        }
       return Response::json($data);
    }
    public function forget_cookie(){
        Cookie::queue(Cookie::forget('chat_done_cookie'));
        return 1;
    }
    public function symptom_checker(){
        $user = Auth::user()->id;
        $symptomsChecker = Symptoms_Checker::where('user_id',$user)->latest()->paginate(10);
        return view('dashboard_patient.symptom_checker',compact('symptomsChecker'));
    }
    public function get_symptom(Request $request){
        $user = Auth::user()->id;
        $symptomsChecker = Symptoms_Checker::where('user_id',$user)->where('id',$request->id)->first();
        return Response::json($symptomsChecker);
    }

}

