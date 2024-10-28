<?php

namespace App\Http\Controllers;

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
use App\State;

class TblOrdersController extends AppBaseController
{
    /** @var  TblOrdersRepository */
    private $tblOrdersRepository;

    public function __construct(TblOrdersRepository $tblOrdersRepo)
    {

        $this->tblOrdersRepository = $tblOrdersRepo;
    }

    /**
     * Display a listing of the TblOrders.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        //  $tblOrders = $this->tblOrdersRepository->all();
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
                    'lab_orders.order_id as order_id'
                )
                ->groupBy('lab_orders.order_id')
                ->orderBy('lab_orders.created_at', 'desc')
                ->get();
            // return route('lab_order',$tblOrders->id);
            // ->with('tblOrders', $tblOrders)
            // ->with('user', $user);

        } else if ($user->user_type == 'admin_imaging' || $user->user_type == 'editor_imaging') {

            $tblOrders = DB::table('imaging_orders')
                ->join('tbl_products', 'imaging_orders.product_id', '=', 'tbl_products.id')
                ->join('users', 'users.id', '=', 'imaging_orders.user_id')
                ->join('states', 'states.id', '=', 'users.state_id')
                ->join('cities', 'cities.id', '=', 'users.city_id')
                ->join('tbl_orders', 'tbl_orders.order_id', '=', 'imaging_orders.order_id')
                //->join('imaging_selected_location', '.id', '=', 'imaging_selected_location.')
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
                    'imaging_orders.session_id as session_id',
                    'imaging_orders.product_id as product_id',
                    'imaging_orders.price as price',
                    // 'imaging_locations.name as lab_name',
                    // 'imaging_locations.address as lab_address',
                    'tbl_orders.payment_title',
                    'tbl_orders.payment_method',
                    'tbl_orders.currency',
                    // 'imaging_orders.sub_order_id as order_id'
                )
                ->orderBy('imaging_orders.created_at', 'desc')
                ->orderBy('order_status')
                ->get();

                foreach ($tblOrders as $tblOrder) {
                    $datetime = date('Y-m-d h:i A', strtotime($tblOrder->created_at));
                    $tblOrder->created_at = User::convert_utc_to_user_timezone($user->id, $datetime)['datetime'];
                    $location = DB::table('imaging_selected_location')
                    ->join('imaging_locations', 'imaging_selected_location.imaging_location_id', 'imaging_locations.id')
                    ->where('imaging_selected_location.session_id', $tblOrder->session_id)
                    ->where('imaging_selected_location.product_id', $tblOrder->product_id)
                    ->select('imaging_locations.address as location')
                    ->first();
                    $tblOrder->location = $location->location;
                    //$tblOrder->created_at = User::convert_utc_to_user_timezone($user->id,$tblOrder->created_at);
                }
        }else if ($user->user_type == 'admin_pharmacy' || $user->user_type == 'editor_pharmacy') {

            $tblOrders = DB::table('medicine_order')
                ->join('tbl_products', 'medicine_order.order_product_id', '=', 'tbl_products.id')
                ->join('users', 'users.id', '=', 'medicine_order.user_id')
                ->join('states', 'states.id', '=', 'users.state_id')
                ->join('cities', 'cities.id', '=', 'users.city_id')
                ->join('tbl_orders', 'tbl_orders.order_id', '=', 'medicine_order.order_main_id')
                //->join('imaging_selected_location', '.id', '=', 'imaging_selected_location.')
                ->select(
                    'tbl_products.name as name',
                    'tbl_products.sale_price as total',
                    'medicine_order.*',
                    'users.name as fname',
                    'users.last_name as lname',
                    'users.office_address as address',
                    'cities.name as order_city',
                    'states.name as order_state',
                    'medicine_order.status as order_status',
                    'medicine_order.session_id as session_id',
                    'medicine_order.order_product_id as product_id',
                    'medicine_order.update_price as price',
                    'medicine_order.order_main_id as order_id',
                    // 'imaging_locations.name as lab_name',
                    // 'imaging_locations.address as lab_address',
                    'tbl_orders.payment_title',
                    'tbl_orders.payment_method',
                    'tbl_orders.currency',
                    'tbl_orders.created_at as created_at',
                    // 'imaging_orders.sub_order_id as order_id'
                )
                ->orderBy('medicine_order.id', 'desc')
                ->orderBy('order_status')
                ->get();
        }
        foreach ($tblOrders as $tblOrder) {
            $datetime = date('Y-m-d h:i A', strtotime($tblOrder->created_at));
            $tblOrder->created_at = User::convert_utc_to_user_timezone($user->id, $datetime)['datetime'];
            //$tblOrder->created_at = User::convert_utc_to_user_timezone($user->id,$tblOrder->created_at);
        }
        //dd($tblOrders);
        //dd($tblOrders);

        return view('tbl_orders.index')
            ->with('tblOrders', $tblOrders)
            ->with('user', $user);
    }

    public function lab_admin_orders()
    {
        $user = auth()->user();
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
            // 'lab_orders.order_id as order_id'
        )
        ->groupBy('lab_orders.order_id')
        ->orderBy('lab_orders.created_at', 'desc')
        ->paginate(10);
        $data = DB::table('lab_orders')
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
            // 'lab_orders.order_id as order_id'
        )
        ->groupBy('lab_orders.order_id')
        ->orderBy('lab_orders.created_at', 'desc')
        ->get()->toArray();
        foreach ($tblOrders as $tblOrder) {
            $datetime = date('Y-m-d h:i A', strtotime($tblOrder->created_at));
            $tblOrder->created_at = User::convert_utc_to_user_timezone($user->id, $datetime);
            //$tblOrder->created_at = User::convert_utc_to_user_timezone($user->id,$tblOrder->created_at);
        }
        foreach ($data as $tblOrder) {
            $datetime = date('Y-m-d h:i A', strtotime($tblOrder->created_at));
            $tblOrder->created_at = User::convert_utc_to_user_timezone($user->id, $datetime);
        }
        $data = json_encode($data);
        //dd($tblOrders);
        //dd($tblOrders);

        return view('dashboard_Lab_admin.Orders.index')
            ->with('tblOrders', $tblOrders)
            ->with('user', $user)
            ->with('data', $data);
    }

    public function imaging_admin_orders()
    {
        $user = auth()->user();
        $tblOrders = DB::table('imaging_orders')
        ->join('tbl_products', 'imaging_orders.product_id', '=', 'tbl_products.id')
        ->join('users', 'users.id', '=', 'imaging_orders.user_id')
        ->join('states', 'states.id', '=', 'users.state_id')
        ->join('cities', 'cities.id', '=', 'users.city_id')
        ->join('tbl_orders', 'tbl_orders.order_id', '=', 'imaging_orders.order_id')
        ->select(
            'tbl_products.name as name',
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
            // 'imaging_orders.order_id as order_id'
        )
        // ->groupBy('imaging_orders.order_id')
        ->orderBy('imaging_orders.created_at', 'desc')
        ->paginate(10);
        $data = DB::table('imaging_orders')
        ->join('tbl_products', 'imaging_orders.product_id', '=', 'tbl_products.id')
        ->join('users', 'users.id', '=', 'imaging_orders.user_id')
        ->join('states', 'states.id', '=', 'users.state_id')
        ->join('cities', 'cities.id', '=', 'users.city_id')
        ->join('tbl_orders', 'tbl_orders.order_id', '=', 'imaging_orders.order_id')
        ->select(
            'tbl_products.name as name',
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
            // 'imaging_orders.order_id as order_id'
        )
        // ->groupBy('imaging_orders.order_id')
        ->orderBy('imaging_orders.created_at', 'desc')
        ->get()->toArray();
        foreach ($tblOrders as $tblOrder) {
            $datetime = date('Y-m-d h:i A', strtotime($tblOrder->created_at));
            $tblOrder->created_at = User::convert_utc_to_user_timezone($user->id, $datetime);
            $tblOrder->created_at = $tblOrder->created_at['date']." ".$tblOrder->created_at['time'];
            //$tblOrder->created_at = User::convert_utc_to_user_timezone($user->id,$tblOrder->created_at);
        }

        foreach ($data as $tblOrder) {
            $datetime = date('Y-m-d h:i A', strtotime($tblOrder->created_at));
            $tblOrder->created_at = User::convert_utc_to_user_timezone($user->id, $datetime);
            //$tblOrder->created_at = User::convert_utc_to_user_timezone($user->id,$tblOrder->created_at);
        }
        $data = json_encode($data);
        //dd($tblOrders);
        //dd($tblOrders);

        return view('dashboard_imaging_admin.orders.orders')
            ->with('tblOrders', $tblOrders)
            ->with('data', $data)
            ->with('user', $user);
    }

    public function imaging_admin_order_details($id)
    {
        $user = auth()->user();
        $tblOrders = DB::table('imaging_orders')
        ->join('tbl_products', 'imaging_orders.product_id', '=', 'tbl_products.id')
        ->join('users', 'users.id', '=', 'imaging_orders.user_id')
        ->join('states', 'states.id', '=', 'users.state_id')
        ->join('cities', 'cities.id', '=', 'users.city_id')
        ->join('tbl_orders', 'tbl_orders.order_id', '=', 'imaging_orders.order_id')
        ->select(
            'tbl_products.name as name',
            'imaging_orders.*',
            'users.name as fname',
            'users.last_name as lname',
            'users.office_address as address',
            'cities.name as order_city',
            'states.name as order_state',
            // 'imaging_orders.status as order_status',
            'tbl_orders.payment_title',
            'tbl_orders.payment_method',
            'tbl_orders.currency',
            'tbl_orders.order_status',
            'imaging_orders.order_id as order_id'
        )
        ->where('imaging_orders.id',$id)
        ->groupBy('imaging_orders.order_id')
        ->orderBy('imaging_orders.created_at', 'desc')
        ->get();
        foreach ($tblOrders as $tblOrder) {
            $datetime = date('Y-m-d h:i A', strtotime($tblOrder->created_at));
            $tblOrder->created_at = User::convert_utc_to_user_timezone($user->id, $datetime);
            //$tblOrder->created_at = User::convert_utc_to_user_timezone($user->id,$tblOrder->created_at);
        }
        $tblOrders = $tblOrders[0];
        //dd($tblOrders);

        return view('dashboard_imaging_admin.orders.order_details')
            ->with('tblOrders', $tblOrders)
            ->with('user', $user);
    }

    public function admin_orders(Request $request)
    {
        $user = auth()->user();
        if ($user->user_type == "admin") {
            if(isset($request->name)){
                $tblOrders = DB::table('tbl_orders')
                    ->join('states', 'tbl_orders.order_state', 'states.id')
                    ->select('tbl_orders.*', 'states.name as state_name')
                    ->where('tbl_orders.order_id', 'like', '%'.$request->name.'%')
                    ->orderby('tbl_orders.created_at', 'desc')
                    ->paginate(10);
            }else{
                $tblOrders = DB::table('tbl_orders')
                    ->join('states', 'tbl_orders.order_state', 'states.id')
                    ->select('tbl_orders.*', 'states.name as state_name')
                    ->orderby('tbl_orders.created_at', 'desc')
                    ->paginate(10);
            }

            foreach ($tblOrders as $orders) {
                $orders->created_at = User::convert_utc_to_user_timezone($user->id, $orders->created_at);
            }

            return view('dashboard_admin.Orders.index')->with('tblOrders', $tblOrders)->with('user', $user);
        } else {
            return redirect(url()->previous());
        }
    }

    public function patient_order(Request $request)
    {
        //  $tblOrders = $this->tblOrdersRepository->all();
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
            // return route('lab_order',$tblOrders->id);
            // ->with('tblOrders', $tblOrders)
            // ->with('user', $user);

        } else if ($user->user_type == 'admin_imaging' || $user->user_type == 'editor_imaging') {

            $tblOrders = DB::table('imaging_orders')
                ->join('tbl_products', 'imaging_orders.product_id', '=', 'tbl_products.id')
                ->join('users', 'users.id', '=', 'imaging_orders.user_id')
                ->join('states', 'states.id', '=', 'users.state_id')
                ->join('cities', 'cities.id', '=', 'users.city_id')
                ->join('tbl_orders', 'tbl_orders.order_id', '=', 'imaging_orders.order_id')
                // ->join('imaging_locations', 'imaging_locations.id', '=', 'imaging_orders.location_id')
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
                    // 'imaging_locations.name as lab_name',
                    // 'imaging_locations.address as lab_address',
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
            //$tblOrder->created_at = User::convert_utc_to_user_timezone($user->id,$tblOrder->created_at);
            $tblOrder->created_at = date("m-d-Y h:iA", strtotime($tblOrder->created_at));
        }
        //  dd($newDate);
        return view('dashboard_patient.Order.index')
            ->with('tblOrders', $tblOrders)
            ->with('user', $user);
    }

    public function order_details($id)
    {
        $user = auth()->user();
        $tblOrders = $this->tblOrdersRepository->getsOrderByID($id);
        if (empty($tblOrders)) {
            Flash::error('OrderID ' . $id . ' not found.');
            return redirect(route('orders.index'));
        } elseif (auth()->user()->user_type == 'admin') {
            $data['order_data'] = $tblOrders;
            $orderId = $data['order_data']->order_id;
            // $data['cart_items'] = $this->tblOrdersRepository->productDetails($tblOrders->cart_items);
            $data['billing'] = $this->tblOrdersRepository->forOrderView($tblOrders->billing);
            $data['shipping'] = $this->tblOrdersRepository->forOrderListView($tblOrders->shipping);
            $data['payment'] = unserialize($tblOrders->payment);
            $data['payment_method'] = $tblOrders->payment_title;
            $data['order_sub_id'] = $this->tblOrdersRepository->forOrderListView($tblOrders->order_sub_id);
            $datetime = User::convert_utc_to_user_timezone($user->id, $data['order_data']->created_at);
            $data['order_data']->created_at = $datetime['date']." ".$datetime['time'];

            $orderMeds = DB::table('medicine_order')->where('order_main_id', $orderId)
            ->join('tbl_products', 'tbl_products.id', 'medicine_order.order_product_id')
            ->join('prescriptions', 'prescriptions.medicine_id', 'medicine_order.order_product_id')
            ->groupBy('medicine_order.id')
            ->select('tbl_products.name', 'medicine_order.update_price', 'medicine_order.status', 'prescriptions.usage',)->get();

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
                $loc = DB::table('imaging_selected_location')
                ->join('imaging_locations', 'imaging_selected_location.imaging_location_id', 'imaging_locations.id')
                ->where('imaging_selected_location.session_id', $img->session_id)
                ->where('imaging_selected_location.product_id',$img->product_id)
                ->select('imaging_locations.address as location')
                ->first();
                $img->location = $loc->location;
            }
            return view('dashboard_admin.Orders.order_details', compact('data', 'orderMeds', 'orderLabs', 'ordercntLabs', 'orderImagings'));
            }
        }

    public function dash_index(Request $request)
    {
        //  $tblOrders = $this->tblOrdersRepository->all();
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
            // return route('lab_order',$tblOrders->id);
            // ->with('tblOrders', $tblOrders)
            // ->with('user', $user);

        } else if ($user->user_type == 'admin_imaging' || $user->user_type == 'editor_imaging') {

            $tblOrders = DB::table('imaging_orders')
                ->join('tbl_products', 'imaging_orders.product_id', '=', 'tbl_products.id')
                ->join('users', 'users.id', '=', 'imaging_orders.user_id')
                ->join('states', 'states.id', '=', 'users.state_id')
                ->join('cities', 'cities.id', '=', 'users.city_id')
                ->join('tbl_orders', 'tbl_orders.order_id', '=', 'imaging_orders.order_id')
                // ->join('imaging_locations', 'imaging_locations.id', '=', 'imaging_orders.location_id')
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
                    // 'imaging_locations.name as lab_name',
                    // 'imaging_locations.address as lab_address',
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
            $tblOrder->created_at = User::convert_utc_to_user_timezone($user->id, $tblOrder->created_at);
            //$tblOrder->created_at = User::convert_utc_to_user_timezone($user->id,$tblOrder->created_at);
        }
        //  dd($tblOrders);

        return view('dashboard_doctor.Order.index')
            ->with('tblOrders', $tblOrders)
            ->with('user', $user);
    }

    /**
     * Show the form for creating a new TblOrders.
     *
     * @return Response
     */
    public function create()
    {
        return view('tbl_orders.create');
    }

    /**
     * Store a newly created TblOrders in storage.
     *
     * @param CreateTblOrdersRequest $request
     *
     * @return Response
     */
    public function store(CreateTblOrdersRequest $request)
    {
        $input = $request->all();

        $tblOrders = $this->tblOrdersRepository->create($input);

        Flash::success('Tbl Orders saved successfully.');

        return redirect(route('orders.index'));
    }

    /**
     * Display the specified TblOrders.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $tblOrders = $this->tblOrdersRepository->getOrderByID($id);

        if (empty($tblOrders)) {
            Flash::error('OrderID ' . $id . ' not found.');
            return redirect(route('all_orders_admin'));
        } else {
            $data['order_data'] = $tblOrders;
            // dd($data['order_data']);
            $orderId = $data['order_data']->order_id;
            // $data['cart_items'] = $this->tblOrdersRepository->productDetails($tblOrders->cart_items);
            $data['billing'] = $this->tblOrdersRepository->forOrderListView($tblOrders->billing);
            $data['shipping'] = $this->tblOrdersRepository->forOrderListView($tblOrders->shipping);
            $data['payment'] = unserialize($tblOrders->payment);
            $data['payment_method'] = $tblOrders->payment_title;
            // $data['payment'] = $this->tblOrdersRepository->forOrderListView($tblOrders->payment);
            $data['order_sub_id'] = $this->tblOrdersRepository->forOrderListView($tblOrders->order_sub_id);

            $orderMeds = DB::table('medicine_order')->where('order_main_id', $orderId)
                ->join('tbl_products', 'tbl_products.id', 'medicine_order.order_product_id')
                ->join('prescriptions', 'prescriptions.medicine_id', 'medicine_order.order_product_id')
                ->select('tbl_products.name', 'medicine_order.update_price', 'prescriptions.usage',)->get();

            if (Auth::user()->user_type == 'patient') {
                $orderLabs = DB::table('lab_orders')->where('order_id', $orderId)
                    ->join('quest_data_test_codes', 'quest_data_test_codes.TEST_CD', 'lab_orders.product_id')
                    ->join('prescriptions', 'prescriptions.test_id', 'lab_orders.product_id')
                    ->select('quest_data_test_codes.DESCRIPTION', 'quest_data_test_codes.SALE_PRICE', 'prescriptions.quantity',)->get();
                $ordercntLabs = DB::table('lab_orders')->where('order_id', $orderId)
                    ->join('quest_data_test_codes', 'quest_data_test_codes.TEST_CD', 'lab_orders.product_id')
                    ->where('lab_orders.type','Counter')
                    ->select('quest_data_test_codes.DESCRIPTION', 'quest_data_test_codes.SALE_PRICE')->get();
            } elseif (Auth::user()->user_type == 'doctor') {
                $ordercntLabs = DB::table('lab_orders')->where('order_id', $orderId)
                    ->join('quest_data_test_codes', 'quest_data_test_codes.TEST_CD', 'lab_orders.product_id')
                    ->where('lab_orders.type','Counter')
                    ->select('quest_data_test_codes.DESCRIPTION', 'quest_data_test_codes.SALE_PRICE')->get();
            } elseif (Auth::user()->user_type == 'admin') {
                $orderLabs = DB::table('lab_orders')
                ->join('quest_data_test_codes', 'quest_data_test_codes.TEST_CD', 'lab_orders.product_id')
                ->join('prescriptions', 'prescriptions.test_id', 'lab_orders.product_id')
                ->where('lab_orders.order_id', $orderId)
                ->where('lab_orders.type', 'Prescribed')
                ->groupBy('lab_orders.id')
                ->select('lab_orders.*', 'quest_data_test_codes.DESCRIPTION', 'quest_data_test_codes.SALE_PRICE', 'prescriptions.quantity',)
                ->get();
                // dd($orderLabs);
                $ordercntLabs = DB::table('lab_orders')
                ->join('quest_data_test_codes', 'quest_data_test_codes.TEST_CD', 'lab_orders.product_id')
                ->where('lab_orders.order_id', $orderId)
                ->where('lab_orders.type', 'Counter')
                ->select('lab_orders.*', 'quest_data_test_codes.DESCRIPTION', 'quest_data_test_codes.SALE_PRICE')->get();
                // $orderLabs = DB::table('lab_orders')->where('order_id', $orderId)
                //     ->join('quest_data_test_codes', 'quest_data_test_codes.TEST_CD', 'lab_orders.product_id')
                //     ->join('prescriptions', 'prescriptions.test_id', 'lab_orders.product_id')
                //     ->select('quest_data_test_codes.DESCRIPTION', 'quest_data_test_codes.SALE_PRICE', 'prescriptions.quantity',)->get();
                // $ordercntLabs = DB::table('lab_orders')->where('order_id', $orderId)
                //     ->join('quest_data_test_codes', 'quest_data_test_codes.TEST_CD', 'lab_orders.product_id')
                //     ->where('lab_orders.type','Counter')
                //     ->select('quest_data_test_codes.DESCRIPTION', 'quest_data_test_codes.SALE_PRICE')->get();
            }

            $orderImagings = DB::table('imaging_orders')->where('order_id', $orderId)
                ->join('tbl_products', 'tbl_products.id', 'imaging_orders.product_id')
                ->select('tbl_products.name', 'imaging_orders.price','imaging_orders.session_id','imaging_orders.product_id')->get();
            foreach($orderImagings as $img)
            {
                $loc = DB::table('imaging_selected_location')
                ->join('imaging_locations', 'imaging_selected_location.imaging_location_id', 'imaging_locations.id')
                ->where('imaging_selected_location.session_id', $img->session_id)
                ->where('imaging_selected_location.product_id',$img->product_id)
                ->select('imaging_locations.address as location')
                ->first();
                $img->location = $loc->location;
            }


            $data['order_data']->created_at = date("m-d-Y h:iA", strtotime($data['order_data']->created_at));
            // dd($orderLabs);
            return view('dashboard_admin.Orders.order_details', compact('data', 'orderMeds', 'orderLabs', 'ordercntLabs', 'orderImagings'));
        }
    }

    public function dash_show($id)
    {
        $tblOrders = $this->tblOrdersRepository->getsOrderByID($id);
        $user = auth()->user();
        if (empty($tblOrders)) {
            Flash::error('OrderID ' . $id . ' not found.');
            return redirect(route('patient_all_order'));
        } else {
            $data['order_data'] = $tblOrders;
            // $data['cart_items'] = $this->tblOrdersRepository->productDetails($tblOrders->cart_items);
            $data['billing'] = $this->tblOrdersRepository->forOrderListView($tblOrders->billing);
            $data['shipping'] = $this->tblOrdersRepository->forOrderListView($tblOrders->shipping);
            $data['payment'] = unserialize($tblOrders->payment);
            $data['payment_method'] = $tblOrders->payment_title;
            // $data['payment'] = $this->tblOrdersRepository->forOrderListView($tblOrders->payment);
            $data['order_sub_id'] = $this->tblOrdersRepository->forOrderListView($tblOrders->order_sub_id);

            $orderId = $tblOrders->order_id;
            $orderMeds = DB::table('medicine_order')->where('order_main_id', $orderId)
                ->join('tbl_products', 'tbl_products.id', 'medicine_order.order_product_id')
                ->join('prescriptions', 'prescriptions.medicine_id', 'medicine_order.order_product_id')
                ->groupBy('medicine_order.id')
                ->select('tbl_products.name', 'medicine_order.update_price', 'medicine_order.status', 'prescriptions.usage',)->get();
            if (Auth::user()->user_type == 'patient') {
                // $data=DB::table('prescriptions')->where('test_id','10285')->get();
                // dd($data);

                $orderLabs = DB::table('lab_orders')
                    ->join('quest_data_test_codes', 'quest_data_test_codes.TEST_CD', 'lab_orders.product_id')
                    ->join('prescriptions', 'prescriptions.test_id', 'lab_orders.product_id')
                    ->where('lab_orders.order_id', $orderId)
                    ->where('lab_orders.type', 'Prescribed')
                    ->groupBy('lab_orders.id')
                    ->select('lab_orders.*', 'quest_data_test_codes.DESCRIPTION', 'quest_data_test_codes.SALE_PRICE', 'prescriptions.quantity',)
                    ->get();
                    // dd($orderLabs);
                    $ordercntLabs = DB::table('lab_orders')
                    ->join('quest_data_test_codes', 'quest_data_test_codes.TEST_CD', 'lab_orders.product_id')
                    ->where('lab_orders.order_id', $orderId)
                    ->where('lab_orders.type', 'Counter')
                    ->select('lab_orders.*', 'quest_data_test_codes.DESCRIPTION', 'quest_data_test_codes.SALE_PRICE')->get();
            } elseif (Auth::user()->user_type == 'doctor') {
                $ordercntLabs = DB::table('lab_orders')->where('order_id', $orderId)
                    ->join('quest_data_test_codes', 'quest_data_test_codes.TEST_CD', 'lab_orders.product_id')
                    ->select('lab_orders.*', 'quest_data_test_codes.DESCRIPTION', 'quest_data_test_codes.SALE_PRICE')->get();
            }

            $orderImagings = DB::table('imaging_orders')->where('order_id', $orderId)
                ->join('tbl_products', 'tbl_products.id', 'imaging_orders.product_id')
                // ->join('prescriptions','prescriptions.imaging_id','imaging_orders.product_id')
                ->select('tbl_products.name', 'imaging_orders.price','imaging_orders.status','imaging_orders.session_id','imaging_orders.product_id')->get();
            // dd($orderImagings);
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
                return view('dashboard_patient.Order.details', compact('data', 'orderMeds', 'orderLabs', 'orderImagings', 'ordercntLabs'));
            } else if ($user->user_type == 'doctor') {
                return view('dashboard_doctor.Order.order_details', compact('data', 'orderMeds', 'ordercntLabs', 'orderImagings'));
            }
        }
    }

    /**
     * Show the form for editing the specified TblOrders.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $tblOrders = $this->tblOrdersRepository->find($id);

        if (empty($tblOrders)) {
            Flash::error('Tbl Orders not found');

            return redirect(route('orders.index'));
        }

        return view('tbl_orders.edit')->with('tblOrders', $tblOrders);
    }

    /**
     * Update the specified TblOrders in storage.
     *
     * @param int $id
     * @param UpdateTblOrdersRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTblOrdersRequest $request)
    {
        $tblOrders = $this->tblOrdersRepository->find($id);

        if (empty($tblOrders)) {
            Flash::error('Tbl Orders not found');

            return redirect(route('orders.index'));
        }

        $tblOrders = $this->tblOrdersRepository->update($request->all(), $id);

        Flash::success('Tbl Orders updated successfully.');

        return redirect(route('orders.index'));
    }

    /**
     * Remove the specified TblOrders from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $tblOrders = $this->tblOrdersRepository->find($id);

        if (empty($tblOrders)) {
            Flash::error('Tbl Orders not found');

            return redirect(route('orders.index'));
        }

        $this->tblOrdersRepository->delete($id);

        Flash::success('Tbl Orders deleted successfully.');

        return redirect(route('orders.index'));
    }

    public function lab_order(Request $request)
    {
        // $lab_order = LabOrder::where('id', $request['id'])->first();
        // $tblOrders = $this->tblOrdersRepository->getOrderByOrderID($lab_order['order_id']);
        $lab_order_id = LabOrder::where('id', $request['id'])->select('lab_orders.order_id')->first();
        $lab_order = DB::table('lab_orders')
            ->where('lab_orders.order_id', $lab_order_id->order_id)
            ->join('quest_data_test_codes', 'lab_orders.product_id', '=', 'quest_data_test_codes.TEST_CD')
            ->join('users', 'users.id', '=', 'lab_orders.user_id')
            ->join('states', 'states.id', '=', 'users.state_id')
            ->join('tbl_orders', 'tbl_orders.order_id', '=', 'lab_orders.order_id')
            ->select(
                'quest_data_test_codes.TEST_NAME as name',
                'quest_data_test_codes.SALE_PRICE as total',
                'lab_orders.*',
                'states.name as order_state',
                'lab_orders.status as order_status',
                'tbl_orders.payment_title',
                'tbl_orders.payment_method',
                'tbl_orders.currency',
                'users.username',
                'users.name as first_name',
                'users.last_name'
            )->get();
        foreach ($lab_order as $labs) {
            $labs->date = User::convert_utc_to_user_timezone($labs->user_id, $labs->created_at)['date'];
            $labs->time = User::convert_utc_to_user_timezone($labs->user_id, $labs->created_at)['time'];
        }

        // $lab_order->date = Helper::get_date_with_format($lab_order->date);
        // $lab_order->time = Helper::get_time_with_format($lab_order->time);


        // $data['order_data'] = $tblOrders[0];
        // $data['cart_items'] = $this->tblOrdersRepository->productDetails($tblOrders[0]->cart_items);
        // $data['billing'] = $this->tblOrdersRepository->forOrderListView($tblOrders[0]->billing);
        // $data['shipping'] = $this->tblOrdersRepository->forOrderListView($tblOrders[0]->shipping);
        // $data['payment'] = $this->tblOrdersRepository->forOrderListView($tblOrders[0]->payment);
        // $data['order_sub_id'] = $this->tblOrdersRepository->forOrderListView($tblOrders[0]->order_sub_id);
        // dd($data);
        return view('tbl_orders.lab_order', compact('lab_order'));
    }
    public function dash_lab_order(Request $request)
    {
        // $lab_order = LabOrder::where('id', $request['id'])->first();
        // $tblOrders = $this->tblOrdersRepository->getOrderByOrderID($lab_order['order_id']);
        $lab_order_id = LabOrder::where('id', $request['id'])->select('lab_orders.order_id')->first();
        $lab_order = DB::table('lab_orders')
            ->where('lab_orders.order_id', $lab_order_id->order_id)
            ->join('quest_data_test_codes', 'lab_orders.product_id', '=', 'quest_data_test_codes.TEST_CD')
            ->join('users', 'users.id', '=', 'lab_orders.user_id')
            ->join('states', 'states.id', '=', 'users.state_id')
            ->join('tbl_orders', 'tbl_orders.order_id', '=', 'lab_orders.order_id')
            ->select(
                'quest_data_test_codes.TEST_NAME as name',
                'quest_data_test_codes.SALE_PRICE as total',
                'lab_orders.*',
                'states.name as order_state',
                'lab_orders.status as order_status',
                'tbl_orders.payment_title',
                'tbl_orders.payment_method',
                'tbl_orders.currency',
                'tbl_orders.order_status as pay_status',
                'users.username',
                'users.name as first_name',
                'users.last_name'
            )->get();
        foreach ($lab_order as $labs) {
            $labs->date = User::convert_utc_to_user_timezone($labs->user_id, $labs->created_at)['date'];
            $labs->time = User::convert_utc_to_user_timezone($labs->user_id, $labs->created_at)['time'];
        }

        // $lab_order->date = Helper::get_date_with_format($lab_order->date);
        // $lab_order->time = Helper::get_time_with_format($lab_order->time);


        // $data['order_data'] = $tblOrders[0];
        // $data['cart_items'] = $this->tblOrdersRepository->productDetails($tblOrders[0]->cart_items);
        // $data['billing'] = $this->tblOrdersRepository->forOrderListView($tblOrders[0]->billing);
        // $data['shipping'] = $this->tblOrdersRepository->forOrderListView($tblOrders[0]->shipping);
        // $data['payment'] = $this->tblOrdersRepository->forOrderListView($tblOrders[0]->payment);
        // $data['order_sub_id'] = $this->tblOrdersRepository->forOrderListView($tblOrders[0]->order_sub_id);
        // dd($lab_order);
        return view('dashboard_Lab_admin.Orders.order_details', compact('lab_order'));
    }

    public function labOrders_by_patient(Request $request, $patient_id)
    {
        $lab_order = DB::table('lab_orders')
            ->join('quest_data_test_codes', 'lab_orders.product_id', '=', 'quest_data_test_codes.TEST_CD')
            ->join('users', 'users.id', '=', 'lab_orders.user_id')
            ->join('states', 'states.id', '=', 'users.state_id')
            ->join('tbl_orders', 'tbl_orders.order_id', '=', 'lab_orders.order_id')
            # ->join('tbl_map_markers', 'tbl_map_markers.id', '=', 'lab_orders.map_marker_id')
            ->select(
                'quest_data_test_codes.DESCRIPTION as description',
                'quest_data_test_codes.TEST_NAME as name',
                'quest_data_test_codes.SALE_PRICE as sale_price',
                'lab_orders.*',
                'states.name as order_state',
                'lab_orders.status as order_status',
                'tbl_orders.payment_title',
                'tbl_orders.payment_method',
                'tbl_orders.currency',
                # 'tbl_map_markers.name as lab_name',
                # 'tbl_map_markers.address as lab_address',
                'users.username',
                'users.name as first_name',
                'users.last_name'
            )->where('lab_orders.user_id', $patient_id)
            ->get();

        foreach ($lab_order as $labs) {
            $labs->date = User::convert_utc_to_user_timezone($user->id, $labs->created_at)['date'];
            $labs->time = User::convert_utc_to_user_timezone($user->id, $labs->created_at)['time'];
        }

        return $lab_order;
    }

    public function upload_lab_report(Request $request)
    {
        // dd($request);
        if (request()->hasFile('lab_report')) {
            $digits = 5;
            $rand = str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
            $image = request()->file('lab_report');
            $filename = $rand . '_' . $image->getClientOriginalName();
            $image->move(public_path('asset_admin/images/lab_reports'), $filename);
            $imageName = $rand . '_' . request()->file('lab_report')->getClientOriginalName();
            LabOrder::where('id', $request['id'])->update([
                'report' => $imageName,
                'uploaded_by' => auth()->user()->id,
                'status' => 'reported',
            ]);
        }
        return redirect()->route('orders.index');
    }

    public function imaging_order(Request $request)
    {
        $user = Auth()->user();
        $img_order = ImagingOrder::where('id', $request['id'])->first();
        $tblOrders = $this->tblOrdersRepository->getOrderByOrderID($img_order['order_id']);
        $img_order = DB::table('imaging_orders')
            ->where('imaging_orders.id', $request['id'])
            ->join('tbl_products', 'imaging_orders.product_id', '=', 'tbl_products.id')
            ->join('users', 'users.id', '=', 'imaging_orders.user_id')
            ->join('states', 'states.id', '=', 'users.state_id')
            ->join('tbl_orders', 'tbl_orders.order_id', '=', 'imaging_orders.order_id')
            // ->join('imaging_locations', 'imaging_locations.id', '=', 'imaging_orders.location_id')
            ->select(
                'tbl_products.name as name',
                'tbl_products.sale_price as total',
                'imaging_orders.*',
                'states.name as order_state',
                'imaging_orders.status as order_status',
                'imaging_orders.session_id',
                'imaging_orders.product_id',
                'imaging_orders.price',
                'tbl_orders.payment_title',
                'tbl_orders.payment_method',
                'tbl_orders.currency',
                // 'imaging_locations.name as lab_name',
                // 'imaging_locations.address as lab_address',
                'users.username',
                'users.name as first_name',
                'users.last_name'
            )->first();

        $img_order->date = User::convert_utc_to_user_timezone($user->id, $img_order->created_at)['date'];
        $img_order->time = User::convert_utc_to_user_timezone($user->id, $img_order->created_at)['time'];
        // $img_order->date = Helper::get_date_with_format($img_order->date);
        // $img_order->time =  User::convert_utc_to_user_timezone Helper::get_time_with_format($img_order->time);
        // // dd($img_order);
        // $data['order_data'] = $tblOrders[0];
        // $data['cart_items'] = $this->tblOrdersRepository->productDetails($tblOrders[0]->cart_items);
        // $data['billing'] = $this->tblOrdersRepository->forOrderListView($tblOrders[0]->billing);
        // $data['shipping'] = $this->tblOrdersRepository->forOrderListView($tblOrders[0]->shipping);
        // $data['payment'] = $this->tblOrdersRepository->forOrderListView($tblOrders[0]->payment);
        // $data['order_sub_id'] = $this->tblOrdersRepository->forOrderListView($tblOrders[0]->order_sub_id);
        // dd($data);
        $location = DB::table('imaging_selected_location')
        ->join('imaging_locations', 'imaging_selected_location.imaging_location_id', 'imaging_locations.id')
        ->where('imaging_selected_location.session_id', $img_order->session_id)
        ->where('imaging_selected_location.product_id', $img_order->product_id)
        ->select('imaging_locations.address as location')
        ->first();
        $img_order->location = $location->location;
        return view('tbl_orders.imaging_order', compact('img_order'));
    }

    public function pharmacy_order(Request $request)
    {
        $user = Auth()->user();
        // $phar_order = DB::table('medicine_order')->where('id', $request['id'])->first();
        // $tblOrders = $this->tblOrdersRepository->getOrderByOrderID($phar_order['order_main_id']);
        $img_order = DB::table('medicine_order')
            ->where('medicine_order.id', $request['id'])
            ->join('tbl_products', 'medicine_order.order_product_id', '=', 'tbl_products.id')
            ->join('users', 'users.id', '=', 'medicine_order.user_id')
            ->join('states', 'states.id', '=', 'users.state_id')
            ->join('tbl_orders', 'tbl_orders.order_id', '=', 'medicine_order.order_main_id')
            // ->join('imaging_locations', 'imaging_locations.id', '=', 'imaging_orders.location_id')
            ->select(
                'tbl_products.name as name',
                'tbl_products.sale_price as total',
                'medicine_order.*',
                'states.name as order_state',
                'medicine_order.status as order_status',
                'medicine_order.session_id',
                'medicine_order.order_product_id as product_id',
                'medicine_order.update_price as price',
                'medicine_order.order_main_id as order_id',
                'tbl_orders.payment_title',
                'tbl_orders.payment_method',
                'tbl_orders.currency',
                'tbl_orders.created_at as created_at',
                // 'imaging_locations.name as lab_name',
                // 'imaging_locations.address as lab_address',
                'users.username',
                'users.name as first_name',
                'users.last_name'
            )->first();

        $img_order->date = User::convert_utc_to_user_timezone($user->id, $img_order->created_at)['date'];
        $img_order->time = User::convert_utc_to_user_timezone($user->id, $img_order->created_at)['time'];
        // $img_order->date = Helper::get_date_with_format($img_order->date);
        // $img_order->time =  User::convert_utc_to_user_timezone Helper::get_time_with_format($img_order->time);
        // // dd($img_order);
        // $data['order_data'] = $tblOrders[0];
        // $data['cart_items'] = $this->tblOrdersRepository->productDetails($tblOrders[0]->cart_items);
        // $data['billing'] = $this->tblOrdersRepository->forOrderListView($tblOrders[0]->billing);
        // $data['shipping'] = $this->tblOrdersRepository->forOrderListView($tblOrders[0]->shipping);
        // $data['payment'] = $this->tblOrdersRepository->forOrderListView($tblOrders[0]->payment);
        // $data['order_sub_id'] = $this->tblOrdersRepository->forOrderListView($tblOrders[0]->order_sub_id);
        // dd($data);
        return view('tbl_orders.imaging_order', compact('img_order'));
    }
    public function dash_pharmacy_order(Request $request)
    {
        $user = Auth()->user();
        // $phar_order = DB::table('medicine_order')->where('id', $request['id'])->first();
        // $tblOrders = $this->tblOrdersRepository->getOrderByOrderID($phar_order['order_main_id']);
        $img_order = DB::table('medicine_order')
            ->where('medicine_order.id', $request['id'])
            ->join('tbl_products', 'medicine_order.order_product_id', '=', 'tbl_products.id')
            ->join('users', 'users.id', '=', 'medicine_order.user_id')
            ->join('states', 'states.id', '=', 'users.state_id')
            ->join('tbl_orders', 'tbl_orders.order_id', '=', 'medicine_order.order_main_id')
            // ->join('imaging_locations', 'imaging_locations.id', '=', 'imaging_orders.location_id')
            ->select(
                'tbl_products.name as name',
                'tbl_products.sale_price as total',
                'medicine_order.*',
                'states.name as order_state',
                'medicine_order.status as order_status',
                'medicine_order.session_id',
                'medicine_order.order_product_id as product_id',
                'medicine_order.update_price as price',
                'medicine_order.order_main_id as order_id',
                'tbl_orders.payment_title',
                'tbl_orders.order_status as pay_status',
                'tbl_orders.payment_method',
                'tbl_orders.currency',
                'tbl_orders.created_at as created_at',
                // 'imaging_locations.name as lab_name',
                // 'imaging_locations.address as lab_address',
                'users.username',
                'users.name as first_name',
                'users.last_name'
            )->first();

        $img_order->date = User::convert_utc_to_user_timezone($user->id, $img_order->created_at)['date'];
        $img_order->time = User::convert_utc_to_user_timezone($user->id, $img_order->created_at)['time'];
        $file = DB::table('prescriptions_files')->where('order_id',$img_order->order_sub_id)->first();
        $file->filename =\App\Helper::get_files_url($file->filename);
        return view('dashboard_Pharm_admin.Orders.order_details', compact('img_order','file'));
    }

    public function upload_imaging_report(Request $request)
    {
        // dd(request());
        if (request()->hasFile('img_report')) {
            $file = request()->file('img_report');
            $file_path = Storage::disk('s3')->put('imaging_reports', $file);
            ImagingOrder::where('id', $request['id'])->update([
                'report' => $file_path,
                'uploaded_by' => auth()->user()->id,
                'status' => 'reported',
            ]);
            $imaging = ImagingOrder::where('id', $request['id'])->first();
            $session_id = $imaging->session_id;
            $sessionRecord = DB::table('sessions')->where('id', $session_id)->first();
            $patient_data = DB::table('users')->where('id', $sessionRecord->patient_id)->first();
            $doctor_data = DB::table('users')->where('id', $sessionRecord->patient_id)->first();
            $admin_data = DB::table('users')->where('user_type', '')->first();

            try {
                $markDownPateint = [
                    'pat_name' => ucwords($patient_data->name),
                    'pat_email' => $patient_data->email,
                ];
                $markDownDoctor = [
                    'doc_name' => ucwords($doctor_data->name),
                    'doc_email' => $doctor_data->email,
                ];
                $markDownAdmin = [
                    'admin_name' => ucwords($admin_data->name),
                    'admin_email' => $admin_data->email,
                ];
                // Mail::to('baqir.redecom@gmail.com')->send(new imagingResultPatientMail($markDownPateint));
                // Mail::to('baqir.redecom@gmail.com')->send(new imagingResultDoctorMail($markDownDoctor));
                // Mail::to('baqir.redecom@gmail.com')->send(new imagingResultImagingAdminMail($markDownAdmin));
                Mail::to($patient_data->email)->send(new imagingResultPatientMail($markDownPateint));
                Mail::to($doctor_data->email)->send(new imagingResultDoctorMail($markDownDoctor));
                Mail::to($admin_data->email)->send(new imagingResultImagingAdminMail($markDownAdmin));
            } catch (Exception $e) {
                Log::error($e);
            }
        }
        return redirect()->route('orders.index');
    }

    public function patient_labs(Request $request)
    {
        $user = Auth::user();
        if ($user->user_type == 'patient') {
            $lab_orders = DB::table('lab_orders')->where('lab_orders.user_id', $user->id)
                ->join('users', 'users.id', '=', 'lab_orders.user_id')
                ->join('tbl_products', 'lab_orders.product_id', '=', 'tbl_products.id')
                ->join('tbl_orders', 'tbl_orders.order_id', '=', 'lab_orders.order_id')
                ->join('tbl_map_markers', 'tbl_map_markers.id', '=', 'lab_orders.map_marker_id')
                ->select(
                    'lab_orders.*',
                    'tbl_products.name as lab_name',
                    'users.name as fname',
                    'users.last_name as lname',
                    'tbl_map_markers.name as location_name',
                    'tbl_map_markers.address as location_address'
                )
                ->get();
            foreach ($lab_orders as $labs) {
                $labs->date = User::convert_utc_to_user_timezone($user->id, $labs->created_at)['date'];
                $labs->time = User::convert_utc_to_user_timezone($user->id, $labs->created_at)['time'];
            }
            return view('patient.lab.lab_reports', compact('lab_orders'));
        } else if ($user->user_type == 'doctor') {
            $lab_orders = DB::table('lab_orders')
                ->join('sessions', 'sessions.id', '=', 'lab_orders.session_id')
                ->where('sessions.doctor_id', $user->id)
                ->join('users', 'users.id', '=', 'lab_orders.user_id')
                ->join('tbl_products', 'lab_orders.product_id', '=', 'tbl_products.id')
                ->join('tbl_orders', 'tbl_orders.order_id', '=', 'lab_orders.order_id')
                ->join('tbl_map_markers', 'tbl_map_markers.id', '=', 'lab_orders.map_marker_id')
                ->select(
                    'lab_orders.*',
                    'tbl_products.name as lab_name',
                    'users.name as fname',
                    'users.last_name as lname',
                    'tbl_map_markers.name as location_name',
                    'tbl_map_markers.address as location_address'
                )
                ->get();
            foreach ($lab_orders as $labs) {
                $labs->date = User::convert_utc_to_user_timezone($user->id, $labs->created_at)['date'];
                $labs->time = User::convert_utc_to_user_timezone($user->id, $labs->created_at)['time'];
            }
            return view('doctor.lab.lab_reports', compact('lab_orders'));
        }

        // dd($lab_orders);

    }

    public function labFilter(Request $request)
    {

        $date = date('Y-m-d');
        if ($request->filter == 'all') {
            $tblOrders = DB::table('lab_orders')
                ->join('tbl_products', 'lab_orders.product_id', '=', 'tbl_products.id')
                ->join('users', 'users.id', '=', 'lab_orders.user_id')
                ->join('states', 'states.id', '=', 'users.state_id')
                ->join('cities', 'cities.id', '=', 'users.city_id')
                ->join('tbl_map_markers', 'tbl_map_markers.id', '=', 'lab_orders.map_marker_id')
                ->join('tbl_orders', 'tbl_orders.order_id', '=', 'lab_orders.order_id')
                ->select(
                    'tbl_products.name as name',
                    'tbl_products.sale_price as total',
                    'lab_orders.*',
                    'users.name as fname',
                    'users.last_name as lname',
                    'users.office_address as address',
                    'cities.name as order_city',
                    'states.name as order_state',
                    'lab_orders.status as order_status',
                    'tbl_map_markers.name as lab_name',
                    'tbl_map_markers.address as lab_address',
                    'tbl_orders.payment_title',
                    'tbl_orders.payment_method',
                    'tbl_orders.currency',
                    'lab_orders.sub_order_id as order_id'
                )
                // ->where('lab_orders.status','pending')
                ->get();
        } else if ($request->filter == 'past') {
            $tblOrders = DB::table('lab_orders')
                ->join('tbl_products', 'lab_orders.product_id', '=', 'tbl_products.id')
                ->join('users', 'users.id', '=', 'lab_orders.user_id')
                ->join('states', 'states.id', '=', 'users.state_id')
                ->join('cities', 'cities.id', '=', 'users.city_id')
                ->join('tbl_map_markers', 'tbl_map_markers.id', '=', 'lab_orders.map_marker_id')
                ->join('tbl_orders', 'tbl_orders.order_id', '=', 'lab_orders.order_id')
                ->select(
                    'tbl_products.name as name',
                    'tbl_products.sale_price as total',
                    'lab_orders.*',
                    'users.name as fname',
                    'users.last_name as lname',
                    'users.office_address as address',
                    'cities.name as order_city',
                    'tbl_map_markers.name as lab_name',
                    'tbl_map_markers.address as lab_address',
                    'states.name as order_state',
                    'lab_orders.status as order_status',
                    'tbl_orders.payment_title',
                    'tbl_orders.payment_method',
                    'tbl_orders.currency',
                    'lab_orders.sub_order_id as order_id'
                )
                ->where('lab_orders.date', '<', $date)
                ->get();
        } else if ($request->filter == 'upcoming') {
            $tblOrders = DB::table('lab_orders')
                ->join('tbl_products', 'lab_orders.product_id', '=', 'tbl_products.id')
                ->join('users', 'users.id', '=', 'lab_orders.user_id')
                ->join('states', 'states.id', '=', 'users.state_id')
                ->join('cities', 'cities.id', '=', 'users.city_id')
                ->join('tbl_map_markers', 'tbl_map_markers.id', '=', 'lab_orders.map_marker_id')
                ->join('tbl_orders', 'tbl_orders.order_id', '=', 'lab_orders.order_id')
                ->select(
                    'tbl_products.name as name',
                    'tbl_products.sale_price as total',
                    'lab_orders.*',
                    'users.name as fname',
                    'users.last_name as lname',
                    'users.office_address as address',
                    'cities.name as order_city',
                    'tbl_map_markers.name as lab_name',
                    'tbl_map_markers.address as lab_address',
                    'states.name as order_state',
                    'lab_orders.status as order_status',
                    'tbl_orders.payment_title',
                    'tbl_orders.payment_method',
                    'tbl_orders.currency',
                    'lab_orders.sub_order_id as order_id'
                )
                ->where('lab_orders.date', '>', $date)
                ->orWhere('lab_orders.date', '=', $date)
                ->get();
        } else if ($request->filter == 'pending') {
            $tblOrders = DB::table('lab_orders')
                ->join('tbl_products', 'lab_orders.product_id', '=', 'tbl_products.id')
                ->join('users', 'users.id', '=', 'lab_orders.user_id')
                ->join('states', 'states.id', '=', 'users.state_id')
                ->join('cities', 'cities.id', '=', 'users.city_id')
                ->join('tbl_map_markers', 'tbl_map_markers.id', '=', 'lab_orders.map_marker_id')
                ->join('tbl_orders', 'tbl_orders.order_id', '=', 'lab_orders.order_id')
                ->select(
                    'tbl_products.name as name',
                    'tbl_products.sale_price as total',
                    'lab_orders.*',
                    'users.name as fname',
                    'users.last_name as lname',
                    'tbl_map_markers.name as lab_name',
                    'tbl_map_markers.address as lab_address',
                    'users.office_address as address',
                    'cities.name as order_city',
                    'states.name as order_state',
                    'lab_orders.status as order_status',
                    'tbl_orders.payment_title',
                    'tbl_orders.payment_method',
                    'tbl_orders.currency',
                    'lab_orders.sub_order_id as order_id'
                )
                ->where('lab_orders.status', 'pending')
                ->get();
        } else if ($request->filter == 'reported') {
            $tblOrders = DB::table('lab_orders')
                ->join('tbl_products', 'lab_orders.product_id', '=', 'tbl_products.id')
                ->join('users', 'users.id', '=', 'lab_orders.user_id')
                ->join('states', 'states.id', '=', 'users.state_id')
                ->join('cities', 'cities.id', '=', 'users.city_id')
                ->join('tbl_map_markers', 'tbl_map_markers.id', '=', 'lab_orders.map_marker_id')
                ->join('tbl_orders', 'tbl_orders.order_id', '=', 'lab_orders.order_id')
                ->select(
                    'tbl_products.name as name',
                    'tbl_products.sale_price as total',
                    'lab_orders.*',
                    'users.name as fname',
                    'users.last_name as lname',
                    'users.office_address as address',
                    'cities.name as order_city',
                    'tbl_map_markers.name as lab_name',
                    'tbl_map_markers.address as lab_address',
                    'states.name as order_state',
                    'lab_orders.status as order_status',
                    'tbl_orders.payment_title',
                    'tbl_orders.payment_method',
                    'tbl_orders.currency',
                    'lab_orders.sub_order_id as order_id'
                )
                ->where('lab_orders.status', 'reported')
                ->get();
        }
        foreach ($tblOrders as $order) {
            $order->date = User::convert_utc_to_user_timezone($user->id, $order->created_at)['date'];
            $order->time = User::convert_utc_to_user_timezone($user->id, $order->created_at)['time'];
        }
        return $tblOrders;
    }

    public function imagingFilter(Request $request)
    {
        if ($request->filter == 'all') {
            $tblOrders = DB::table('imaging_orders')
                ->join('tbl_products', 'imaging_orders.product_id', '=', 'tbl_products.id')
                ->join('users', 'users.id', '=', 'imaging_orders.user_id')
                ->join('states', 'states.id', '=', 'users.state_id')
                ->join('cities', 'cities.id', '=', 'users.city_id')
                ->join('tbl_orders', 'tbl_orders.order_id', '=', 'imaging_orders.order_id')
                ->join('sessions', 'sessions.id', '=', 'imaging_orders.session_id')
                ->join('users as doc', 'doc.id', '=', 'sessions.doctor_id')
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
                    // 'imaging_locations.name as lab_name',
                    // 'imaging_locations.address as lab_address',
                    'tbl_orders.payment_title',
                    'tbl_orders.payment_method',
                    'tbl_orders.currency',
                    'sessions.id as session_id',
                    'doc.name as doc_fname',
                    'doc.last_name as doc_lname',
                    'doc.nip_number',
                    'doc.upin'
                    // 'imaging_orders.sub_order_id as order_id'
                )
                ->orderBy('imaging_orders.status')
                ->get();
        } else if ($request->filter == 'pending' || $request->filter == 'reported') {
            $tblOrders = DB::table('imaging_orders')
                ->join('tbl_products', 'imaging_orders.product_id', '=', 'tbl_products.id')
                ->join('users', 'users.id', '=', 'imaging_orders.user_id')
                ->join('states', 'states.id', '=', 'users.state_id')
                ->join('cities', 'cities.id', '=', 'users.city_id')
                ->join('tbl_orders', 'tbl_orders.order_id', '=', 'imaging_orders.order_id')
                ->join('sessions', 'sessions.id', '=', 'imaging_orders.session_id')
                ->join('users as doc', 'doc.id', '=', 'sessions.doctor_id')
                ->where('imaging_orders.status', $request->filter)
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
                    // 'imaging_locations.name as lab_name',
                    // 'imaging_locations.address as lab_address',
                    'tbl_orders.payment_title',
                    'tbl_orders.payment_method',
                    'tbl_orders.currency',
                    'sessions.id as session_id',
                    'doc.name as doc_fname',
                    'doc.last_name as doc_lname',
                    'doc.nip_number',
                    'doc.upin'
                    // 'imaging_orders.sub_order_id as order_id'
                )
                ->get();
        }
        foreach ($tblOrders as $order) {
            $order->date = User::convert_utc_to_user_timezone($user->id, $order->created_at)['date'];
            $order->time = User::convert_utc_to_user_timezone($user->id, $order->created_at)['time'];
        }
        return $tblOrders;
    }

    public function imaging_orders()
    {
        $user = auth()->user();
        $tblOrders = DB::table('imaging_orders')
            ->join('tbl_products', 'imaging_orders.product_id', '=', 'tbl_products.id')
            ->join('users', 'users.id', '=', 'imaging_orders.user_id')
            ->join('states', 'states.id', '=', 'users.state_id')
            ->join('cities', 'cities.id', '=', 'users.city_id')
            ->join('tbl_orders', 'tbl_orders.order_id', '=', 'imaging_orders.order_id')
            ->join('sessions', 'sessions.id', '=', 'imaging_orders.session_id')
            ->join('users as doc', 'doc.id', '=', 'sessions.doctor_id')
            ->where('users.id', $user->id)
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
                // 'imaging_locations.name as lab_name',
                // 'imaging_locations.address as lab_address',
                'tbl_orders.payment_title',
                'tbl_orders.payment_method',
                'tbl_orders.currency',
                'sessions.id as session_id',
                'doc.name as doc_fname',
                'doc.last_name as doc_lname',
                'doc.nip_number',
                'doc.upin'
                // 'imaging_orders.sub_order_id as order_id'
            )
            ->orderBy('imaging_orders.status')
            ->get();
        foreach ($tblOrders as $img_ord) {
            $img_ord->date = User::convert_utc_to_user_timezone($user->id, $img_ord->created_at)['date'];
            $img_ord->time = User::convert_utc_to_user_timezone($user->id, $img_ord->created_at)['time'];
            if ($img_ord->report != null) {
                $img_ord->report = \App\Helper::get_files_url($img_ord->report);
            }
        }
        // dd($tblOrders);

        return view('imaging.index')
            ->with('tblOrders', $tblOrders)
            ->with('user', $user);
    }
    public function dash_imaging_orders()
    {
        $user = auth()->user();
        $tblOrders = DB::table('imaging_orders')
            ->join('tbl_products', 'imaging_orders.product_id', '=', 'tbl_products.id')
            ->join('users', 'users.id', '=', 'imaging_orders.user_id')
            ->join('states', 'states.id', '=', 'users.state_id')
            ->join('cities', 'cities.id', '=', 'users.city_id')
            ->join('tbl_orders', 'tbl_orders.order_id', '=', 'imaging_orders.order_id')
            ->join('sessions', 'sessions.id', '=', 'imaging_orders.session_id')
            ->join('users as doc', 'doc.id', '=', 'sessions.doctor_id')
            ->where('users.id', $user->id)
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
                // 'imaging_locations.name as lab_name',
                // 'imaging_locations.address as lab_address',
                'tbl_orders.payment_title',
                'tbl_orders.payment_method',
                'tbl_orders.currency',
                'sessions.id as session_id',
                'doc.name as doc_fname',
                'doc.last_name as doc_lname',
                'doc.nip_number',
                'doc.upin'
                // 'imaging_orders.sub_order_id as order_id'
            )
            ->orderBy('imaging_orders.status')
            ->get();
        foreach ($tblOrders as $img_ord) {
            $img_ord->date = User::convert_utc_to_user_timezone($user->id, $img_ord->created_at)['date'];
            $img_ord->time = User::convert_utc_to_user_timezone($user->id, $img_ord->created_at)['time'];
            if ($img_ord->report != null) {
                $img_ord->report = \App\Helper::get_files_url($img_ord->report);
            }
        }
        // dd($tblOrders);

        return view('dashboard_patient.Imaging.index')
            ->with('tblOrders', $tblOrders)
            ->with('user', $user);
    }

    public function dash_imaging_file(){
        $med = DB::table('imaging_file')->where('patient_id',auth()->user()->id)->orderby('id','desc')->paginate(10);
        foreach ($med as $m) {
            $m->names = DB::table('imaging_orders')
            ->join('tbl_products','imaging_orders.product_id','tbl_products.id')
            ->where('imaging_orders.order_id',$m->order_id)
            ->select('tbl_products.name')->get();
            $doc = DB::table('users')->where('id',$m->doctor_id)->first();
            $m->doc = $doc->name.' '.$doc->last_name;
            $m->created_at = User::convert_utc_to_user_timezone(auth()->user()->id, $m->created_at);
            $m->created_at = $m->created_at['date'] . ' ' . $m->created_at['time'];
        }
        return view('dashboard_patient.Imaging.imaging_file', compact('med'));
    }

    public function get_img_report(Request $request)
    {
        $report = ImagingOrder::find($request->id);
        if ($report->report != null && $report->report != 'user.png') {
            $report->report = \App\Helper::get_files_url($report->report);
        }
        return $report;
    }

    public function unassignedLabOrders()
    {
        $pending_requisitions = DB::table('lab_orders')
            ->join('quest_data_test_codes', 'lab_orders.product_id', 'quest_data_test_codes.TEST_CD')
            ->where('lab_orders.status', 'lab-editor-approval')
            ->orderByDesc('lab_orders.order_id')
            ->groupBy('lab_orders.order_id')
            ->paginate(9);
        $pending_requisitions_test_name = DB::table('lab_orders')
            ->join('quest_data_test_codes', 'lab_orders.product_id', 'quest_data_test_codes.TEST_CD')
            ->where('lab_orders.status', 'lab-editor-approval')
            ->orderByDesc('lab_orders.order_id')
            ->select('quest_data_test_codes.TEST_NAME', 'lab_orders.order_id')
            ->get()->toArray();


        foreach ($pending_requisitions as $requisition) {
            $requisition->date = User::convert_utc_to_user_timezone($requisition->user_id, $requisition->created_at);
            $requisition->date = $requisition->date['date'];
            $user = User::find($requisition->user_id);
            $state = State::find($user->state_id);
            $requisition->user_state = $state->name;
            $doctors = DB::table('doctor_licenses')
                ->join('users', 'users.id', 'doctor_licenses.doctor_id')
                ->where('doctor_licenses.state_id', $user->state_id)
                ->select('users.name', 'users.last_name', 'users.id as user_id')
                ->get()->toArray();
            $requisition->doctors = $doctors;

            if ($requisition->doc_id != null) {
                $doc = User::find($requisition->doc_id);
                $requisition->decline_status = "Approval declined by Dr." . $doc->name . ' ' . $doc->last_name;
            } else {
                $requisition->decline_status = "Not assigned to any doctor yet";
            }
        }

        // dd($pending_requisitions);

        // $user = Auth::user();
        // $unassignedOrders = $this->tblOrdersRepository->getApprovalOrders();
        // foreach ($unassignedOrders as $order) {
        //     $order->order_date = User::convert_utc_to_user_timezone($user->id, $order->created_at);
        //     $order->order_time = $order->order_date['time'];
        //     $order->order_date = $order->order_date['date'];
        //     if ($order->doc_id != null) {
        //         $doc = User::find($order->doc_id);
        //         $order->decline_status = "Approval declined by Dr." . $doc->name . ' ' . $doc->last_name;
        //     } else {
        //         $order->decline_status = "Not assigned to any doctor yet";
        //     }
        // }
        //dd($unassignedOrders);
        return view('lab.admin.approval_orders', compact('pending_requisitions', 'pending_requisitions_test_name'));
    }

    public function assignLabForApprovalToDoctor(Request $request)
    {
        DB::table('lab_orders')->where('order_id', $request->order_id)
        ->where('status','lab-editor-approval')
        ->where('type','Counter')
        ->update(['status' => 'forwarded_to_doctor', 'doc_id' => $request->doctor_id]);

        $doctor = User::where('id', $request->doctor_id)->first();
        $orderDetail = [
            'doc_name' => $doctor->name,
            'order_id' => $request->order_id
        ];
        try {
            Mail::to($doctor->email)->send(new LabApproval($orderDetail));
            $text = "Lab Assigned by " . Auth::user()->name;
            Notification::create([
                'user_id' => $doctor->id,
                'type' => '/doctor/online/lab/approval/requests',
                'text' => $text,
            ]);

        } catch (\Throwable $th) {
            return 'ok';
        }

        return 'ok';
    }

    public function assignApprovalDoctor(Request $request)
    {
        //dd($request);
        $input = $request->all();
        $order = DB::table('lab_orders')->where('sub_order_id', $input['sub_order_id'])->update(['status' => 'forwarded_to_doctor', 'doc_id' => $input['doctor_id']],);
        // $status = $this->tblOrdersRepository->assignOrderToDoctor($input);
        $orderDetail = DB::table('lab_orders')
            ->join('tbl_transactions', 'lab_orders.order_id', 'tbl_transactions.description')
            ->where('lab_orders.sub_order_id', $input['sub_order_id'])
            ->select('lab_orders.*', 'tbl_transactions.transaction_id as transaction_id')
            ->first();
        $doctor_id = ($input['doctor_id']);
        if ($orderDetail != null) {
            // dd($doctor->email);
            $doctor = User::where('id', $doctor_id)->first();
            Mail::to($doctor->email)->send(new LabApproval($orderDetail));

            $text = "Lab Assigned by " . Auth::user()->name;
            Notification::create([
                'user_id' => $doctor->id,
                'type' => '/doctor/online/lab/approval/requests',
                'text' => $text,
            ]);
            ActivityLog::create([
                'user_id' => $doctor->id,
                'activity' => 'purchased',
                'identity' => 'xx',
                'type' => '/orders',
                'user_type' => 'patient',
                'text' => $text,
            ]);
            // $text = "New Order Place By " . $order_main_id;
            Flash::success('Order assigned successfully');
        } else {
            Flash::error('Invalid Request');
        }

        return redirect()->back();
    }
    public function doctor_lab_approvals()
    {
        $user = Auth::user();
        $orders = $this->tblOrdersRepository->getDoctorPendingOrders();
        // $user = auth()->user();
        // $pendingOrders = DB::table('lab_order_approvals')
        //         ->join('tbl_products', 'tbl_products.id', '=', 'lab_order_approvals.product_id')
        //         ->where('lab_order_approvals.doctor_id', $user->id)
        //         ->where('lab_order_approvals.status', 'pending')
        //         ->select('lab_order_approvals.*', 'tbl_products.name')
        //         ->get();

        foreach ($orders as $order) {
            $order->created_at = User::convert_utc_to_user_timezone($user->id, $order->created_at)['datetime'];
        }
        // dd($orders);
        return view('lab.doctor.approval_orders', compact('orders'));
    }
    public function doctor_online_lab_approvals()
    {

        $pending_requisitions = DB::table('lab_orders')
            ->join('quest_data_test_codes', 'lab_orders.product_id', 'quest_data_test_codes.TEST_CD')
            ->where('lab_orders.status', 'forwarded_to_doctor')
            ->where('lab_orders.doc_id', Auth::user()->id)
            ->orderByDesc('lab_orders.order_id')
            ->groupBy('lab_orders.order_id')
            ->paginate(9);
        $pending_requisitions_test_name = DB::table('lab_orders')
            ->join('quest_data_test_codes', 'lab_orders.product_id', 'quest_data_test_codes.TEST_CD')
            ->where('lab_orders.status', 'forwarded_to_doctor')
            ->where('lab_orders.doc_id', Auth::user()->id)
            ->orderByDesc('lab_orders.order_id')
            ->select('quest_data_test_codes.TEST_NAME', 'lab_orders.order_id')
            ->get()->toArray();


        foreach ($pending_requisitions as $requisition) {
            $requisition->date = User::convert_utc_to_user_timezone($requisition->user_id, $requisition->created_at);
            $requisition->date = $requisition->date['date'];
            $user = User::find($requisition->user_id);
            $state = State::find($user->state_id);
            $requisition->user_state = $state->name;
            $doctors = DB::table('doctor_licenses')
                ->join('users', 'users.id', 'doctor_licenses.doctor_id')
                ->where('doctor_licenses.state_id', $user->state_id)
                ->select('users.name', 'users.last_name', 'users.id as user_id')
                ->get()->toArray();
            $requisition->doctors = $doctors;

            if ($requisition->doc_id != null) {
                $doc = User::find($requisition->doc_id);
                $requisition->decline_status = "Approval Requested To Dr." . $doc->name . ' ' . $doc->last_name;
            } else {
                $requisition->decline_status = "Not assigned to any doctor yet";
            }
        }

        // $user = Auth::user();
        // $orders = $this->tblOrdersRepository->getDoctorPendingOrders();
        // foreach ($orders as $order) {
        //     $order->created_at = User::convert_utc_to_user_timezone($user->id, $order->created_at)['datetime'];
        //     $order->created_at = date("m-d-Y h:iA", strtotime($order->created_at));
        // }
        return view('dashboard_doctor.Lab.lab_approval', compact('pending_requisitions', 'pending_requisitions_test_name'));
    }

    public function approved_labs()
    {
        $user = Auth::user();

        $orders = DB::table('lab_orders')
            ->join('quest_data_test_codes', 'lab_orders.product_id', 'quest_data_test_codes.TEST_CD')
            ->where('lab_orders.status', 'quest-forwarded')
            ->where('lab_orders.type', 'Counter')
            ->where('lab_orders.doc_id', $user->id)
            ->orderByDesc('lab_orders.order_id')
            ->groupBy('lab_orders.order_id')
            ->paginate(9);
        $orders_test_name = DB::table('lab_orders')
            ->join('quest_data_test_codes', 'lab_orders.product_id', 'quest_data_test_codes.TEST_CD')
            ->where('lab_orders.status', 'quest-forwarded')
            ->where('lab_orders.type', 'Counter')
            ->where('doc_id', $user->id)
            ->orderByDesc('lab_orders.order_id')
            ->select('quest_data_test_codes.TEST_NAME', 'lab_orders.order_id')
            ->get()->toArray();

        foreach ($orders as $order) {
            $order->created_at = User::convert_utc_to_user_timezone($user->id, $order->created_at)['datetime'];
            $order->created_at = date("m-d-Y h:iA", strtotime($order->created_at));
        }
        return view('dashboard_doctor.Lab.approved_labs', compact('orders','orders_test_name'));
    }

    public function submit_pending_approvals(Request $request)
    {
        $input = $request->all();
        // dd($input);

        if (($input['action']) == 'Decline') {
            // dd('declined');
            // $approval = LabOrderApproval::find($input['id']);

            LabOrderApproval::find($input['id'])->update(['status' => 'declined']);
            $userEmail = DB::table('tbl_orders')
                ->join('users', 'users.id', 'tbl_orders.customer_id')
                ->where('tbl_orders.id', '=', $input['id'])
                ->select('users.email')
                ->get();
            // dd($input ,$userEmail);
            try {
                if (isset($userEmail)) {
                    Mail::to($userEmail)->send(new LabOrderDeclinedMail($userEmail));
                } else {
                    Log::channel('errorLogs')->info('Error:: Lab order declined email not sent. lab_order_approval id: ' . $input['id'] . ' | user email ' . $approval->email);
                }
            } catch (Exception $e) {
                Log::error($e);
            }
        } elseif (($input['action']) == 'Approve') {
            $status = $this->tblOrdersRepository->acceptLabOrder($input);
            if ($status == 'done') {
                Flash::success('Order approved successfully');
            } else {
                Flash::error('Invalid Request');
            }
        }
        return redirect()->back();
    }
    public function declineLabOrder(Request $request)
    {
        $input = $request->all();
        // notifcation and email
    }
    public function pendingLabOrders()
    {

        $pending_requisitions = DB::table('lab_orders')
            ->join('quest_data_test_codes', 'lab_orders.product_id', 'quest_data_test_codes.TEST_CD')
            ->where('lab_orders.status', 'forwarded_to_doctor')
            ->orderByDesc('lab_orders.order_id')
            ->groupBy('lab_orders.order_id')
            ->paginate(9);
        $pending_requisitions_test_name = DB::table('lab_orders')
            ->join('quest_data_test_codes', 'lab_orders.product_id', 'quest_data_test_codes.TEST_CD')
            ->where('lab_orders.status', 'forwarded_to_doctor')
            ->orderByDesc('lab_orders.order_id')
            ->select('quest_data_test_codes.TEST_NAME', 'lab_orders.order_id')
            ->get()->toArray();


        foreach ($pending_requisitions as $requisition) {
            $requisition->date = User::convert_utc_to_user_timezone($requisition->user_id, $requisition->created_at);
            $requisition->date = $requisition->date['date'];
            $user = User::find($requisition->user_id);
            $state = State::find($user->state_id);
            $requisition->user_state = $state->name;
            $doctors = DB::table('doctor_licenses')
                ->join('users', 'users.id', 'doctor_licenses.doctor_id')
                ->where('doctor_licenses.state_id', $user->state_id)
                ->select('users.name', 'users.last_name', 'users.id as user_id')
                ->get()->toArray();
            $requisition->doctors = $doctors;

            if ($requisition->doc_id != null) {
                $doc = User::find($requisition->doc_id);
                $requisition->decline_status = "Approval Requested To Dr." . $doc->name . ' ' . $doc->last_name;
            } else {
                $requisition->decline_status = "Not assigned to any doctor yet";
            }
        }



        // $user = Auth::user();
        // $orders = $this->tblOrdersRepository->getAllPendingOrders();
        // foreach ($orders as $order) {
        //     $order->created_at = User::convert_utc_to_user_timezone($user->id, $order->created_at);
        //     $order->created_at = $order->created_at['date'] . ' ' . $order->created_at['time'];
        // }
        // dd($orders);
        return view('lab.admin.pending_orders', compact('pending_requisitions', 'pending_requisitions_test_name'));
    }
    public function pendingRefunds()
    {
        $orders = $this->tblOrdersRepository->getPendingRefunds();
        // dd($orders);
        return view('lab.admin.pending_refunds', compact('orders'));
    }
    public function refundLabOrder(Request $request)
    {
        $input = $request->all();
        // dd($input);
        $transaction = DB::table('tbl_transactions')
            ->where('transaction_id', $input['transaction_id'])
            ->update(['refund_flag' => 1, 'refund_amount' => $input['price']]);
        LabOrderApproval::find($input['lab_approval_order_id'])->update(['status' => 'refunded']);
        return redirect()->back();
    }
}
