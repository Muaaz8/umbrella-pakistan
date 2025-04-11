<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Helper;
use App\Notification;
use App\ActivityLog;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateTblOrdersRequest;
use App\Http\Requests\UpdateTblOrdersRequest;
use App\ImagingOrder;
use App\LabOrder;
use App\Mail\imagingResultDoctorMail;
use App\Mail\imagingResultImagingAdminMail;
use App\Mail\imagingResultPatientMail;
use App\Mail\LabOrderDeclinedMail;
use App\Mail\LabApproval;
use App\Models\LabOrderApproval;
use App\Repositories\TblOrdersRepository;
use Carbon\Carbon;
use Auth;
use DB;
use Exception;
use Flash;
use Storage;
use Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Response;
use App\User;
use App\Models\TblOrders;
use App\Models\InClinics;
use App\State;

class OrdersController extends BaseController
{

    /** @var  TblOrdersRepository */
    private $tblOrdersRepository;

    public function __construct(TblOrdersRepository $tblOrdersRepo)
    {

        $this->tblOrdersRepository = $tblOrdersRepo;
    }
    public function patient_orders(Request $request)
    {
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
                    // 'imaging_orders.sub_order_id as order_id'
                )
                ->orderBy('imaging_orders.created_at', 'desc')
                ->orderBy('order_status')
                ->get();
        }
        //dd($tblOrders);
        foreach ($tblOrders as $tblOrder) {
            $datetime = date('Y-m-d h:i A', strtotime($tblOrder->created_at));
            $tblOrder->created_at = User::convert_utc_to_user_timezone($user->id, $datetime)['datetime'];
            $tblOrder->created_at = date("m-d-Y h:iA", strtotime($tblOrder->created_at));
        }

        return $this->sendResponse([
            'orders' => $tblOrders
        ], 'orders fetch successfully');
    }
    public function order_details($id)
    {
        $tblOrders = $this->tblOrdersRepository->getsOrderByID($id);
        $user = auth()->user();
        if (empty($tblOrders)) {
            Flash::error('OrderID ' . $id . ' not found.');
            return redirect(route('patient_all_order'));
        } else {
            $data['order_data'] = $tblOrders;
            $data['billing'] = $this->tblOrdersRepository->forOrderListView($tblOrders->billing);
            $data['shipping'] = $this->tblOrdersRepository->forOrderListView($tblOrders->shipping);
            $data['payment'] = unserialize($tblOrders->payment);
            $data['payment_method'] = $tblOrders->payment_title;
            $data['order_sub_id'] = $this->tblOrdersRepository->forOrderListView($tblOrders->order_sub_id);

            $orderId = $tblOrders->order_id;
            $orderMeds = DB::table('medicine_order')
                ->where('order_main_id', $orderId)
                ->join('tbl_products', 'tbl_products.id', '=', 'medicine_order.order_product_id')
                ->leftJoin('prescriptions', function ($join) {
                    $join->on('prescriptions.medicine_id', '=', 'medicine_order.order_product_id')
                        ->where('medicine_order.pro_mode', 'Prescribed');
                })
                ->groupBy('medicine_order.id')
                ->select('tbl_products.name', 'medicine_order.update_price', 'medicine_order.status', 'prescriptions.usage')
                ->get();

            if (Auth::user()->user_type == 'patient') {

                $orderLabs = DB::table('lab_orders')
                    ->join('quest_data_test_codes', 'quest_data_test_codes.TEST_CD', 'lab_orders.product_id')
                    ->join('prescriptions', 'prescriptions.test_id', 'lab_orders.product_id')
                    ->where('lab_orders.order_id', $orderId)
                    ->where('lab_orders.type', 'Prescribed')
                    ->groupBy('lab_orders.id')
                    ->select('lab_orders.*', 'quest_data_test_codes.DESCRIPTION', 'quest_data_test_codes.TEST_NAME', 'quest_data_test_codes.SALE_PRICE', 'prescriptions.quantity',)
                    ->get();
                    $ordercntLabs = DB::table('lab_orders')
                    ->join('quest_data_test_codes', 'quest_data_test_codes.TEST_CD', 'lab_orders.product_id')
                    ->where('lab_orders.order_id', $orderId)
                    ->where('lab_orders.type', 'Counter')
                    ->select('lab_orders.*', 'quest_data_test_codes.DESCRIPTION', 'quest_data_test_codes.TEST_NAME', 'quest_data_test_codes.SALE_PRICE')->get();
            } elseif (Auth::user()->user_type == 'doctor') {
                $ordercntLabs = DB::table('lab_orders')->where('order_id', $orderId)
                    ->join('quest_data_test_codes', 'quest_data_test_codes.TEST_CD', 'lab_orders.product_id')
                    ->select('lab_orders.*', 'quest_data_test_codes.DESCRIPTION', 'quest_data_test_codes.TEST_NAME', 'quest_data_test_codes.SALE_PRICE')->get();
            }

            $orderImagings = DB::table('imaging_orders')->where('order_id', $orderId)
                ->join('tbl_products', 'tbl_products.id', 'imaging_orders.product_id')
                ->select('tbl_products.name', 'imaging_orders.price','imaging_orders.status','imaging_orders.session_id','imaging_orders.product_id')->get();
            foreach($orderImagings as $img)
            {
                $location = DB::table('imaging_selected_location')
                ->join('imaging_locations', 'imaging_selected_location.imaging_location_id', 'imaging_locations.id')
                ->where('imaging_selected_location.session_id', $img->session_id)
                ->where('imaging_selected_location.product_id', $img->product_id)
                ->select('imaging_locations.city as location')
                ->first();
                $img->location = $location->location;
            }

            $data['order_data']->created_at = User::convert_utc_to_user_timezone($user->id, $data['order_data']->created_at);
            if ($user->user_type == 'patient') {
                return $this->sendResponse([
                    'order' => $data,
                    'orderMeds' => $orderMeds,
                    'orderLabs' => $orderLabs,
                    'orderImagings' => $orderImagings,
                    'ordercntLabs' => $ordercntLabs
                ], 'Order details fetched successfully');
            } else if ($user->user_type == 'doctor') {
                return $this->sendResponse([
                    'order' => $data,
                    'orderMeds' => $orderMeds,
                    'orderLabs' => $ordercntLabs,
                    'orderImagings' => $orderImagings
                ], 'Order details fetched successfully');
            }
        }
    }
    public function order_confirm(){
        $id = auth()->user()->id;
        $order = TblOrders::where('customer_id', $id)->orderBy('created_at', 'desc')->first();
        $user = User::where('id', $id)->select('name', 'last_name', 'gender', 'user_type', 'email')->first();
        if ($order) {
            return $this->sendResponse([
                'order' => $order,
                'user' => $user
            ], 'Order details fetched successfully');
        }else{
            return $this->sendError('Order not found', ['error' => 'Order with the given ID does not exist.']);
        }
    }
}
