<?php

namespace App\Http\Controllers\Api\DoctorsApi;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use App\Repositories\TblOrdersRepository;
use Auth;
use DB;
use App\User;

class OrderController extends BaseController
{
    private $tblOrdersRepository;

    public function __construct(TblOrdersRepository $tblOrdersRepo)
    {

        $this->tblOrdersRepository = $tblOrdersRepo;
    }
    public function doctor_order(){
        $user = auth()->user();
        $tblOrders = $this->tblOrdersRepository->getOrdersByUserID($user->id);
        if ($user->user_type == 'admin') {
            $tblOrders = $this->tblOrdersRepository->all();
        } else if ($user->user_type == 'admin_lab' || $user->user_type == 'editor_lab') {
            $tblOrders = DB::table('lab_orders')
                ->join('quest_data_test_codes', 'lab_orders.product_id', '=', 'quest_data_test_codes.TEST_CD')
                ->join('users', 'users.id', '=', 'lab_orders.user_id')
                ->join('states', 'states.id', '=', 'users.state_id')
                ->join('cities', 'cities.id', '=', 'users.city_id')
                ->join('tbl_orders', 'tbl_orders.order_id', '=', 'lab_orders.order_id')
                ->select(
                    'quest_data_test_codes.TEST_NAME as name',
                    'quest_data_test_codes.SALE_PRICE as total',
                    'lab_orders.*',
                    'users.name as fname',
                    'users.last_name as lname',
                    'users.office_address as address',
                    'cities.name as order_city',
                    'states.name as order_state',
                    'lab_orders.status as order_status',
                    'tbl_orders.payment_title',
                    'tbl_orders.payment_method',
                    'tbl_orders.currency',
                    'lab_orders.sub_order_id as order_id'
                )
                ->groupBy('lab_orders.order_id')
                ->orderBy('lab_orders.created_at', 'desc')
                ->get();
        } else if ($user->user_type == 'admin_imaging' || $user->user_type == 'editor_imaging') {
            $tblOrders = DB::table('imaging_orders')
                ->join('tbl_products', 'imaging_orders.product_id', '=', 'tbl_products.id')
                ->join('users', 'users.id', '=', 'imaging_orders.user_id')
                ->join('states', 'states.id', '=', 'users.state_id')
                ->join('cities', 'cities.id', '=', 'users.city_id')
                ->join('tbl_orders', 'tbl_orders.order_id', '=', 'imaging_orders.order_id')
                ->select(
                    'tbl_products.name as name',
                    'tbl_products.sale_price as total',
                    'imaging_orders.*',
                    'users.name as fname',
                    'users.last_name as lname',
                    'users.office_address as address',
                    'cities.name as order_city',
                    'states.name as order_state',
                    'imaging_orders.status as order_status',
                    'tbl_orders.payment_title',
                    'tbl_orders.payment_method',
                    'tbl_orders.currency',
                )
                ->orderBy('imaging_orders.created_at', 'desc')
                ->orderBy('order_status')
                ->get();
        }
        foreach ($tblOrders as $tblOrder) {
            $tblOrder->created_at = User::convert_utc_to_user_timezone($user->id, $tblOrder->created_at);
        }
        $doctor_order['code'] = 200;
        $doctor_order['tblOrders'] =$tblOrders;
        $doctor_order['user'] =$user;
       return $this->sendResponse($doctor_order,"Doctor Orders");
    }
    public function doctor_order_detail($id){
        $tblOrders = $this->tblOrdersRepository->getsOrderByID($id);
        $user = auth()->user();
        if (empty($tblOrders)) {
            $doctor_orderError['code'] = 200;
            return $this->sendError($doctor_orderError,"Somthing Went Wrong!");
        } else {
            $data['order_data'] = $tblOrders;
            $data['billing'] = $this->tblOrdersRepository->forOrderListViewApiBilling($tblOrders->billing);
            $data['shipping'] = $this->tblOrdersRepository->forOrderListViewApishipping($tblOrders->shipping);
            $data['payment'] = unserialize($tblOrders->payment);
            $data['payment_method'] = $tblOrders->payment_title;
            $data['order_sub_id'] = $this->tblOrdersRepository->forOrderListView($tblOrders->order_sub_id);

           $orderId = $tblOrders->order_id;
           if (Auth::user()->user_type == 'doctor') {
                $ordercntLabs = DB::table('lab_orders')->where('order_id', $orderId)
                    ->join('quest_data_test_codes', 'quest_data_test_codes.TEST_CD', 'lab_orders.product_id')
                    ->select('lab_orders.*', 'quest_data_test_codes.DESCRIPTION', 'quest_data_test_codes.SALE_PRICE')->get();
            }
            $data['order_data']->created_at = User::convert_utc_to_user_timezone($user->id, $data['order_data']->created_at);
            $order_detail['code'] = 200;
            $order_detail['data'] = $data;
            $order_detail['order_counterLabs'] = $ordercntLabs;
            return $this->sendResponse($order_detail,"Order Detail");
        }
    }
    public function doctor_search_order(Request $request){
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
            return $this->sendResponse($orderData,"Order found");
    }
}
