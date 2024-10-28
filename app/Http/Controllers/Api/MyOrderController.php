<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use App\Repositories\TblOrdersRepository;
use DB;
use Auth;
use App\User;

class MyOrderController extends BaseController
{
    /** @var  TblOrdersRepository */
    private $tblOrdersRepository;

    public function __construct(TblOrdersRepository $tblOrdersRepo)
    {

        $this->tblOrdersRepository = $tblOrdersRepo;
    }

    public function all_orders(){
        $user = auth()->user();
        // $orders = DB::table('tbl_orders')->where('customer_id',$user->id)->get();
        $data = DB::table('tbl_orders')
            ->join('states', 'states.id', '=', 'tbl_orders.order_state')
            //->select('name as product_name', 'sale_price')
            ->where('tbl_orders.customer_id', '=', $user->id)
            ->orderBy('tbl_orders.created_at', 'desc')
            ->select('states.name as order_state', 'tbl_orders.order_status as order_status', 'tbl_orders.order_id', 'tbl_orders.id', 'tbl_orders.created_at')
            ->paginate(20);
            foreach ($data as $tblOrder) {
                $tblOrder->created_at = User::convert_utc_to_user_timezone($user->id, $tblOrder->created_at);
            }
        if(!$data->isEmpty()){
            $orderData['code'] = 200;
            $orderData['all_orders'] = $data;
            return $this->sendResponse($orderData,"Orders list");
        } else{
            $orderData['code'] = 200;
            return $this->sendError($orderData,"Somthing Went Wrong!");
        }
    }
    public function search_order(Request $request){
        $search = $request->order_id;
        $user = auth()->user();
        $data = DB::table('tbl_orders')
            ->join('states', 'states.id', '=', 'tbl_orders.order_state')
            ->where('tbl_orders.customer_id', '=', $user->id)
            ->where('tbl_orders.order_id', '=', $search)
            ->orderBy('tbl_orders.created_at', 'desc')
            ->select('states.name as order_state', 'tbl_orders.order_status as order_status', 'tbl_orders.order_id', 'tbl_orders.id', 'tbl_orders.created_at')
            ->get();
        foreach ($data as $tblOrder) {
            $tblOrder->created_at = User::convert_utc_to_user_timezone($user->id, $tblOrder->created_at);
        }    
            $orderData['code'] = 200;
            $orderData['all_orders'] = $data;
            return $this->sendResponse($orderData,"Order Found");
    }
    public function order_details($id){
        $user = auth()->user();
        $orders = DB::table('tbl_orders')->where('tbl_orders.id', $id)->first();
        if($orders ==null) {
            $ordersData['code'] = 200;
            $ordersData['orders'] =$orders;
            return $this->sendError($ordersData,"Order id not found");
        } else{
            $data['order_data'] = $orders;
            // $data['cart_items'] = $this->tblOrdersRepository->productDetails($tblOrders->cart_items);
            $data['billing'] = $this->tblOrdersRepository->forOrderListApiView($orders->billing);
            $data['shipping'] = $this->tblOrdersRepository->forOrderListApiView($orders->shipping);
            $data['payment'] = unserialize($orders->payment);
            $data['payment_method'] = $orders->payment_title;
            // $data['payment'] = $this->tblOrdersRepository->forOrderListApiView($tblOrders->payment);
            $data['order_sub_id'] = $this->tblOrdersRepository->forOrderListApiView($orders->order_sub_id);
            $orderId = $orders->order_id;
            $orderMeds = DB::table('medicine_order')->where('order_main_id', $orderId)
                ->join('tbl_products', 'tbl_products.id', 'medicine_order.order_product_id')
                ->join('prescriptions', 'prescriptions.medicine_id', 'medicine_order.order_product_id')
                ->groupBy('medicine_order.id')
                ->select('tbl_products.name', 'medicine_order.update_price', 'medicine_order.status', 'prescriptions.usage',)->get();
            if (Auth::user()->user_type == 'patient') {
                $orderLabs = DB::table('lab_orders')
                    ->join('quest_data_test_codes', 'quest_data_test_codes.TEST_CD', 'lab_orders.product_id')
                    ->join('prescriptions', 'prescriptions.test_id', 'lab_orders.product_id')
                    ->where('lab_orders.order_id', $orderId)
                    ->where('lab_orders.type', 'Prescribed')
                    ->groupBy('lab_orders.id')
                    ->select('lab_orders.*', 'quest_data_test_codes.DESCRIPTION', 'quest_data_test_codes.SALE_PRICE', 'prescriptions.quantity',)
                    ->get();
                $ordercntLabs = DB::table('lab_orders')
                    ->join('quest_data_test_codes', 'quest_data_test_codes.TEST_CD', 'lab_orders.product_id')
                    ->where('lab_orders.order_id', $orderId)
                    ->where('lab_orders.type', 'Counter')
                    ->select('lab_orders.*', 'quest_data_test_codes.DESCRIPTION', 'quest_data_test_codes.SALE_PRICE')->get();

                $orderImagings = DB::table('imaging_orders')->where('order_id', $orderId)
                    ->join('tbl_products', 'tbl_products.id', 'imaging_orders.product_id')
                    ->select('tbl_products.name', 'imaging_orders.price','imaging_orders.status','imaging_orders.session_id','imaging_orders.product_id')->get();
                foreach($orderImagings as $img)
                {
                    $location = DB::table('imaging_selected_location')
                    ->join('imaging_locations', 'imaging_selected_location.imaging_location_id', 'imaging_locations.id')
                    ->where('imaging_selected_location.session_id', $img->session_id)
                    ->where('imaging_selected_location.product_id', $img->product_id)
                    ->select('imaging_locations.address as location')
                    ->first();
                    $img->location = $location->location;
                }
                $sub_order =0;
                $sub_counterlab =0;
                $sub_pharmacy =0;
                $sub_imaging =0;
                foreach($orderLabs as $order){
                    $sub_order += $order->price;
                }
                foreach($ordercntLabs as $orderCnt){
                    $sub_counterlab +=$orderCnt->price;
                }
                foreach($orderMeds as $med){
                    $sub_pharmacy += $med->update_price;
                }
                foreach($orderImagings as $imaging){
                    $sub_imaging +=$imaging->price;
                }
                if($ordercntLabs != '[]'){
                    $sub_total= $sub_order + $sub_counterlab + $sub_pharmacy + $sub_imaging;
                    $total= $sub_order + $sub_counterlab + $sub_pharmacy + $sub_imaging + 6;
                } else{
                    $sub_total= $sub_order + $sub_counterlab + $sub_pharmacy + $sub_imaging;
                    $total= $sub_total;
                }
                $count_product = count($orderMeds) + count($orderLabs) + count($orderImagings) + count($ordercntLabs);
                $data['order_data']->created_at = User::convert_utc_to_user_timezone($user->id, $data['order_data']->created_at);
                $orderData['code'] = 200;
                $orderData['orders'] = $orders;
                $orderData['data'] = $data;
                $orderData['orderMeds'] = $orderMeds;
                $orderData['orderLabs'] = $orderLabs;
                $orderData['orderImagings'] = $orderImagings; 
                $orderData['ordercntLabs'] = $ordercntLabs;
                $orderData['count'] = $count_product;
                $orderData['provider_fee'] = ($ordercntLabs != '[]')? 6 : 0 ;
                $orderData['sub_total'] = $sub_total;
                $orderData['total'] = $total;
                return $this->sendResponse($orderData, "Order Detail found");
            }    
        }
        
    }
}
