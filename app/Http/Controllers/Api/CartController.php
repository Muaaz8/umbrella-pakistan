<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\TblCart as AppTblCart;
use App\Events\CountCartItem;

class CartController extends BaseController
{
    public function my_cart()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $countItem = 0;
            $itemSum = 0;
            $providerFee = 0;
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
                $item->unit = \App\MedicineUOM::find($item->prescription);
            }

            $totalPrice = $itemSum;
            return $this->sendResponse([
                'user_cart_items' => $user_cart_items,
                'countItem' => $countItem,
                'itemSum' => $itemSum,
                'totalPrice' => $totalPrice,
                'providerFee' => $providerFee
            ], 'Cart retrieved successfully.');
        } else {
            return $this->sendError('Unauthorized', ['error' => 'Unauthorized']);
        }
    }

    public function select_cart_product($id)
    {
        $item_id = $id;
        $result = DB::table('tbl_cart')->where('id', $item_id)->update(['show_product' => '1']);
        $countItem = 0;
        $itemSum = 0;
        $providerFee = 0;
        if ($result) {

            $user_cart_items = DB::table('tbl_cart')->where('user_id', Auth::user()->id)->where('status', 'recommended')->get();
            foreach ($user_cart_items as $item) {

                if ($item->item_type == 'prescribed') {
                    $pres = DB::table('prescriptions')->where('id', $item->pres_id)->first();
                    $item->prescription_date = $pres->created_at;
                    $item->medicine_usage = $pres->usage;
                }
                if ($item->item_type == 'counter' && $item->product_mode == 'lab-test' && $item->show_product == '1') {
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

            $totalPrice = $itemSum + $providerFee;
        }

        return $this->sendResponse([
            'user_cart_items' => $user_cart_items,
            'countItem' => $countItem,
            'itemSum' => number_format($itemSum, 2),
            'totalPrice' => number_format($totalPrice, 2),
            'providerFee' => number_format($providerFee, 2)
        ], 'Cart retrieved successfully.');
    }

    public function remove_item_from_cart($id)
    {
        DB::table('tbl_cart')->where('id', $id)->delete();
        return $this->sendResponse([], 'Item removed from cart successfully.');
    }

    public function show_checkout_products()
    {
        $count = 0;
        $providerFee = 0;
        $allProducts = DB::table('tbl_cart')->where('show_product', '1')->where('status', 'recommended')->where('user_id', Auth::user()->id)->get();
        $itemSum = DB::table('tbl_cart')->where('show_product', '1')->where('status', 'recommended')->where('user_id', Auth::user()->id)->sum('update_price');
        $totalPrice = $itemSum;
        foreach ($allProducts as $allProduct) {
            if ($allProduct->product_image != 'dummy_medicine.png' && $allProduct->product_image != 'default-labtest.jpg' && $allProduct->product_image != 'default-imaging.png'){
                $allProduct->product_image = \App\Helper::check_bucket_files_url($allProduct->product_image);
            }else{
                $allProduct->product_image = asset('assets/images/' . $allProduct->product_image);
            }
            $count += 1;
            if ($allProduct->product_mode == 'lab-test' && $allProduct->item_type == "counter") {
                $providerFee = 0;
            }
            $item_type = $allProduct->item_type;
            if ($item_type == 'prescribed') {
                $doctor = User::find($allProduct->doc_id);
                $allProduct->prescribed = $doctor->name . ' ' . $doctor->last_name;
            }
            if($allProduct->coupon_code_id != ""){
                $percentage = DB::table('coupon_code')
                ->where('id',$allProduct->coupon_code_id)
                ->where('status','1')->select('discount_percentage')->first();
                $percentage = $percentage->discount_percentage;
                if($allProduct->product_mode == "medicine"){
                    $productA = DB::table('tbl_products')->where('id',$allProduct->product_id)->first();
                }else{
                    $productA = DB::table('quest_data_test_codes')->where('TEST_CD',$allProduct->product_id)->first();
                }
                if($productA->discount_percentage != null ){
                    $new_price = (int)$productA->actual_price - ((int)$productA->actual_price*((int)$percentage/100));
                }else{
                    $new_price = (int)$allProduct->price - ((int)$allProduct->price*((int)$percentage/100));
                }
                $discount_item = DB::table('tbl_cart')
                ->where('id',$allProduct->id)
                ->update([
                    'update_price' => $new_price,
                ]);
                $allProduct->update_price = $new_price;
            }
        }
        $itemSum = DB::table('tbl_cart')->where('show_product', '1')->where('status', 'recommended')->where('user_id', Auth::user()->id)->sum('update_price');
        $totalPrice = $itemSum;
        return $this->sendResponse([
            'countItem' => $count,
            'itemSum' => number_format($itemSum, 2),
            'totalPrice' => number_format($totalPrice + $providerFee, 2),
            'allProducts' => $allProducts,
            'providerFee' => number_format($providerFee, 2)
        ], 'Cart retrieved successfully.');
    }
    public function remove_product_on_checkout($id)
    {
        $item_id = $id;
        $result = DB::table('tbl_cart')->where('id', $item_id)->update(['show_product' => '0']);
        $user_cart_items = DB::table('tbl_cart')->where('user_id', Auth::user()->id)->where('status', 'recommended')->get();
        $countItem = 0;
        $itemSum = 0;
        $providerFee = 0;
        if ($result) {

            $user_cart_items = DB::table('tbl_cart')->where('user_id', Auth::user()->id)->where('status', 'recommended')->get();
            foreach ($user_cart_items as $item) {
                $temp = 0;
                if ($item->item_type == 'prescribed' && $item->product_mode == 'pharmacy') {
                    $temp = 1;
                }
                if ($item->item_type == 'prescribed') {
                    $pres = DB::table('prescriptions')->where('id', $item->pres_id)->first();
                    $item->prescription_date = $pres->created_at;
                    $item->medicine_usage = $pres->usage;
                }
                if ($item->item_type == 'counter' && $item->product_mode == 'lab-test' && $item->show_product == '1') {
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

            $totalPrice = $itemSum + $providerFee;
        }

        return $this->sendResponse([
            'user_cart_items' => $user_cart_items,
            'countItem' => $countItem,
            'itemSum' => number_format($itemSum, 2),
            'totalPrice' => number_format($totalPrice, 2),
            'providerFee' => number_format($providerFee, 2)
        ], 'Cart retrieved successfully.');
    }

    public function create_new_order(Request $request)
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
            $res = $pay->payment_app($data, $request->payAble);
            if (isset($res) && $res->errorCode == 0) {
                return $this->sendResponse(['method'=> 'credit-card', 'url'=> $res->formUrl], 'Payment link generated successfully');

            }else{
                return $this->sendError([], 'Payment link not generated');
            }
        }else{
            return $this->sendError([], 'Sorry, Can\'t process with this payment method right now.Kindly try different method.');
        }
    }


    public function add_to_cart(Request $request)
    {
        $user_id=Auth::user()->id;

        $getProductMetaData='';
        if ($request->pro_mode=="lab-test" || $request->pro_mode=="imaging")
        {
            $count=DB::table('tbl_cart')
                ->where('user_id',$user_id)
                ->where('product_id',$request->pro_id)
                ->where('product_mode',$request->pro_mode)
                ->where('item_type','counter')
                ->where('status','recommended')
                ->first();
            if($count!=null)
            {
                return response()->json(array('check' => '1'), 200);
            }
            else
            {
                $getProductMetaData = DB::table('quest_data_test_codes')
                ->select(
                    'TEST_CD AS product_id',
                    'mode',
                    'TEST_NAME AS name',
                    'SALE_PRICE AS sale_price',
                    'featured_image',
                    DB::raw('"quest_data_test_codes" as tbl_name')
                )
                ->where('TEST_CD', $request->pro_id)
                ->first();
                $data['session_id'] = '';
                $data['cart_row_id'] = rand();
                $data['product_id']=$getProductMetaData->product_id;
                $data['name'] = $getProductMetaData->name;
                $data['product_image'] = $getProductMetaData->featured_image;
                $data['prescription'] = '';
                $data['design_view'] = '';
                $data['strip_per_pack'] = 0;
                $data['quantity'] = $request->pro_qty;
                $data['price'] = $getProductMetaData->sale_price;
                $data['discount'] = 0;
                $data['created_at'] = Carbon::now();
                $data['updated_at'] = Carbon::now();
                $data['user_id'] = $user_id;
                $data['doc_session_id'] = 0;
                $data['doc_id'] = 0;
                $data['pres_id'] = 0;
                $data['update_price'] = $getProductMetaData->sale_price;
                $data['product_mode'] = $getProductMetaData->mode;
                $data['item_type'] = 'counter';
                $data['status'] = 'recommended';
                $data['map_marker_id'] = '';
                $data['location_id'] = '';
                $cart = AppTblCart::Create($data);
                event(new CountCartItem($user_id));
                return $this->sendResponse([], 'add to cart successfully.');
            }

        }
        else
        {
            $count=DB::table('tbl_cart')
                ->where('user_id',$user_id)
                ->where('product_id',$request->pro_id)
                ->where('product_mode',$request->pro_mode)
                ->where('prescription',$request->unit)
                ->where('item_type','counter')
                ->where('status','recommended')
                ->first();
            if($count!=null)
            {
                $qty=$count->quantity+$request->quantity;
                $pricing = DB::table('medicine_pricings')->where('id',$request->unit)->first();
                $count=DB::table('tbl_cart')
                    ->where('user_id',$user_id)
                    ->where('product_id',$request->pro_id)
                    ->where('product_mode',$request->pro_mode)
                    ->where('item_type','counter')
                    ->where('status','recommended')
                    ->update(['quantity'=>$qty,'price'=>$qty*$pricing->sale_price]);
                    event(new CountCartItem($user_id));
                    return $this->sendResponse([], 'add to cart successfully.');
            }
            else
            {
                $getProductMetaData = DB::table('tbl_products')
                    ->select(
                        'id as product_id',
                        'name',
                        'sale_price',
                        'mode',
                        'featured_image'
                    )
                    ->where('id', $request->pro_id)
                    ->first();
                $pricing = DB::table('medicine_pricings')->where('id',$request->unit)->first();

                $data['session_id'] = '';
                $data['cart_row_id'] = rand();
                $data['product_id']=$getProductMetaData->product_id;
                $data['name'] = $getProductMetaData->name;
                $data['product_image'] = $getProductMetaData->featured_image;
                $data['prescription'] = $request->unit;
                $data['design_view'] = '';
                $data['strip_per_pack'] = 0;
                $data['quantity'] = $request->quantity;
                $data['price'] = $pricing->sale_price*$request->quantity;
                $data['discount'] = 0;
                $data['created_at'] = Carbon::now();
                $data['updated_at'] = Carbon::now();
                $data['user_id'] = $user_id;
                $data['doc_session_id'] = 0;
                $data['doc_id'] = 0;
                $data['pres_id'] = 0;
                $data['update_price'] = $pricing->sale_price*$request->quantity;
                $data['product_mode'] = $getProductMetaData->mode;
                $data['item_type'] = 'counter';
                $data['status'] = 'recommended';
                $data['map_marker_id'] = '';
                $data['location_id'] = '';

                $cart = AppTblCart::Create($data);
                event(new CountCartItem($user_id));
                return $this->sendResponse([], 'add to cart successfully.');
            }
        }
    }



}
