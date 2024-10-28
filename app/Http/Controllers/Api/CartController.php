<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\User;
use App\Events\CountCartItem;
use Carbon\Carbon;
use App\TblCart as AppTblCart;

class CartController extends BaseController
{
    public function add_to_cart(Request $request){
        $user_id=Auth::user()->id;
        $getProductMetaData='';
            $count=DB::table('tbl_cart')
                ->where('user_id',$user_id)
                ->where('product_id',$request->product_id)
                ->where('product_mode','lab-test')
                ->where('item_type','counter')
                ->where('status','recommended')
                ->first();
            if($count!=null)
            {
                $cartExisit['code'] = 200;
                return $this->sendResponse($cartExisit,'Lab test already added to cart!');
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
                ->where('TEST_CD', $request->product_id)
                ->first();
                $data['session_id'] = '';
                $data['cart_row_id'] = rand();
                $data['product_id'] = $getProductMetaData->product_id;
                $data['name'] = $getProductMetaData->name;
                $data['product_image'] = $getProductMetaData->featured_image;
                $data['prescription'] = '';
                $data['design_view'] = '';
                $data['strip_per_pack'] = 0;
                $data['quantity'] = 1;
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
                $appTblCart =AppTblCart::Create($data);
                try {
                    \App\Helper::firebase($user_id,'CountCartItem',$appTblCart->id,$appTblCart);
                } catch (\Throwable $th) {
                    //throw $th;
                }
                event(new CountCartItem($user_id));
                $addCart['code'] = 200;
                return $this->sendResponse($addCart,'Lab test successfully added to cart!');
            }
    }
    public function view_cart(){
        if (Auth::check()) {
            $user = Auth::user();
            $countItem = 0;
            $itemSum = 0;
            $providerFee = 0;
            // Get User Cart Items
            $cards = DB::table('card_details')->where('user_id', $user->id)->get();
            $user_cart_items = DB::table('tbl_cart')->where('user_id', Auth::user()->id)->where('status', 'recommended')->get();
            // dd($billingDetails,$shippingDetails);
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

            $totalPrice = $itemSum + $providerFee;
            $cartData['code'] = 200;
            $cartData['user_cart_items'] = $user_cart_items;
            $cartData['countItem'] = $countItem;
            $cartData['itemSum'] = $itemSum;
            $cartData['totalPrice'] = $totalPrice;
            $cartData['providerFee'] = $providerFee;
            $cartData['cards'] = $cards;
            return $this->sendResponse($cartData,'cart items');
        } else {
            $cartData['code'] = 200;
            return $this->sendError($cartData,'Login required');
        }
    }
    public function selected_CartItems(Request $request){
        $item_id = $request->item_id;
        $showproducts = DB::table('tbl_cart')->whereIn('id', $item_id)->where('user_id', Auth::user()->id)->get();
        if(isset($showproducts)){
            foreach ($showproducts as $product) {
                if($product->show_product == 1){
                    DB::table('tbl_cart')
                    ->where('id', $product->id)
                    ->update(['show_product' => 0]);
                } else{
                    DB::table('tbl_cart')
                    ->where('id', $product->id)
                    ->update(['show_product' => 1]);
                }
            }
        }
        $yourItemList =DB::table('tbl_cart')->whereIn('id', $item_id)->get();
        $countItem = 0;
        $itemSum = 0;
        $providerFee = 0;
        $user_cart_items = DB::table('tbl_cart')->where('user_id', Auth::user()->id)->where('status', 'recommended')->get();
            foreach ($user_cart_items as $item) {
                if ($item->item_type == 'prescribed') {
                    $pres = DB::table('prescriptions')->where('id', $item->pres_id)->first();
                    $item->prescription_date = $pres->created_at;
                    $item->medicine_usage = $pres->usage;
                } if ($item->item_type == 'counter' && $item->product_mode == 'lab-test' && $item->show_product == '1') {
                    $providerFee = 6;
                } if ($item->show_product == 1) {
                    $countItem += 1;
                    $itemSum += $item->update_price;
                } if ($item->doc_session_id != '0') {
                    $doctorDetails = User::find($item->doc_id);
                    $item->prescribed_by = 'Dr.' . $doctorDetails->name . ' ' . $doctorDetails->last_name;
                } else {
                    $item->prescribed_by = '';
                }
            }
            $totalPrice = $itemSum + $providerFee;
            $res = ['code' => 200,'countItem' => $countItem, 'itemSum' => number_format($itemSum, 2), 'providerFee' => number_format($providerFee, 2), 'totalPrice' => number_format($totalPrice, 2),'yourItemList' => $yourItemList];
            return $this->sendResponse($res,'Selected Cart Items list');
    }
    public function remove_item_from_cart($id){
        DB::table('tbl_cart')->where('id', $id)->delete();
        $cartData['code'] = 200;
        return $this->sendResponse($cartData,'Item removed from cart');
    }
    public function checkout(){
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
                $checkoutData['code'] = 200;
                $checkoutData['data'] = $data;
                return $this->sendResponse($checkoutData,'checkout');
            } else {
                $checkoutData['code'] = 200;
                return $this->sendError($checkoutData,'no items');
            }
        } else {
            $checkoutData['code'] = 200;
            return $this->sendError($checkoutData,'Login required');
        }
    }
    public function your_item(Request $request){
        $user =Auth::user();
        $showproducts = DB::table('tbl_cart')->where('user_id', $user->id)
                            ->where('show_product',1)
                            ->where('status','recommended')
                            ->get();
        $countItem = 0;
        $itemSum = 0;
        $providerFee = 0;
        // $yourItemList =DB::table('tbl_cart')->whereIn('id', $item_id)->get();
        foreach($showproducts as $item){
            if ($item->item_type == 'prescribed') {
                $pres = DB::table('prescriptions')->where('id', $item->pres_id)->first();
                $item->prescription_date = $pres->created_at;
                $item->medicine_usage = $pres->usage;
            } if ($item->item_type == 'counter' && $item->product_mode == 'lab-test' && $item->show_product == '1') {
                $providerFee = 6;
            } if ($item->show_product == 1) {
                $countItem += 1;
                $itemSum += $item->update_price;
            } if ($item->doc_session_id != '0') {
                $doctorDetails = User::find($item->doc_id);
                $item->prescribed_by = 'Dr.' . $doctorDetails->name . ' ' . $doctorDetails->last_name;
            } else {
                $item->prescribed_by = '';
            }
        }
        $totalPrice = $itemSum + $providerFee;
        $res = ['code' => 200,'countItem' => $countItem, 'itemSum' => number_format($itemSum, 2), 'providerFee' => number_format($providerFee, 2), 'totalPrice' => number_format($totalPrice, 2),'yourItemList' => $showproducts];
        return $this->sendResponse($res,'Your Cart Selected Items list');
    }
}
