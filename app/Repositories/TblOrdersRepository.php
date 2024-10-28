<?php

namespace App\Repositories;

use App\Http\Controllers\HL7Controller;
use App\Models\LabOrderApproval;
use App\Models\TblOrders;
use App\QuestLab;
use App\TblCart;
use App\Repositories\BaseRepository;
use App\State;
use App\User;
use App\Session;
use DB;
use App\QuestDataTestCode;

/**
 * Class TblOrdersRepository
 * @package App\Repositories
 * @version December 23, 2020, 2:54 pm UTC
 */

class TblOrdersRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'order_state',
        'order_id',
        'order_sub_id',
        'customer_id',
        'total',
        'shipping_total',
        'total_tax',
        'billing',
        'shipping',
        'payment',
        'payment_title',
        'payment_method',
        'cart_items',
        'tax_lines',
        'shipping_lines',
        'coupon_lines',
        'currency',
        'order_status',
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return TblOrders::class;
    }

    public function getOrderByID($id)
    {
        return DB::table('tbl_orders')->select('*')
            ->where('id', $id)
            ->first();
    }

    public function getsOrderByID($id)
    {
        return DB::table('tbl_orders')
            ->where('tbl_orders.id', $id)
            ->first();
    }

    public function getOrderByOrderID($order_id)
    {
        return DB::table('tbl_orders')->select('*')
            ->where('order_id', $order_id)
            ->get();
    }

    public function productDetails($cartItems)
    {
        $data = [];
        $get_var = unserialize($cartItems);
        $basic_info = [];
        $quantities = [];
        //  echo '<pre>';
        //dd($get_var);
        foreach ($get_var as $item) {


            $quantity = $item->quantity;

            if ($item->product_mode == 'medicine') {
                $qt = $item->quantity;
                array_push($quantities, $qt);
                $qry = DB::table('tbl_products')
                    ->join('medicine_pricings', 'tbl_products.id', 'medicine_pricings.product_id')
                    ->join('medicine_units', 'medicine_pricings.unit_id', 'medicine_units.id')
                    ->select('tbl_products.id as product_id', 'tbl_products.name as product_name', 'medicine_pricings.sale_price', DB::raw($quantity . " AS quantity"))
                    ->where('tbl_products.id', $item->product_id)
                    ->get();
                array_push($basic_info, $qry);
            } elseif ($item->product_mode == 'lab-test') {

                $qt = $item->quantity;
                array_push($quantities, $qt);

                if ($item->item_type == 'counter') {
                    $qry = DB::table('tbl_products')
                        ->select('id as product_id', 'name as product_name', 'sale_price', DB::raw($quantity . " AS quantity"))
                        ->where('id', $item->product_id)
                        ->get();

                    if (count($qry) == 0) {
                        $qry = DB::table('quest_data_test_codes')
                            ->select('TEST_CD as product_id', 'DESCRIPTION as product_name', 'PRICE AS sale_price', DB::raw($quantity . " AS quantity"))
                            ->where('TEST_CD', $item->product_id)
                            ->get();
                    }
                    array_push($basic_info, $qry);
                } else {
                    $qry = DB::table('quest_data_test_codes')
                        ->select('TEST_CD as product_id', 'DESCRIPTION as product_name', 'PRICE AS sale_price', DB::raw($quantity . " AS quantity"))
                        ->where('TEST_CD', $item->product_id)
                        ->get();
                    array_push($basic_info, $qry);
                }
            } elseif ($item->product_mode == 'imaging') {

                $qry = DB::table('tbl_products')
                    ->leftJoin('imaging_prices', 'imaging_prices.product_id', '=', 'tbl_products.id')
                    ->select(
                        'tbl_products.id',
                        'tbl_products.name as product_name',
                        'imaging_prices.price AS sale_price',
                        DB::raw($quantity . " AS quantity")
                    )
                    ->where([['tbl_products.id', $item->product_id]])
                    ->get();
                array_push($basic_info, $qry);
            }
        }
        //dd($basic_info);
        $i = 0;
        //dd($basic_info);
        foreach ($basic_info as $key => $val) {
            $data['products'][$i]['product_name'] = $val[0]->product_name;
            $data['products'][$i]['sale_price'] = $val[0]->sale_price;
            $data['products'][$i]['quantity'] = $val[0]->quantity;
            $data['products'][$i]['cost'] = $val[0]->sale_price * $val[0]->quantity;
            $data['total'][] = $val[0]->sale_price * $val[0]->quantity;
            $i++;
        }
        //dd($data);
        // echo '</pre>';
        return $data;
    }

    public function forOrderListView($values)
    {
        $fields_values = unserialize($values) == null ? [] : unserialize($values);
        $data = [];
        $i = 0;
        foreach ($fields_values as $key => $val) {
            $str = $key . '|' . $val;
            $data[] = $str;
            $i++;
        }
        return $data;
    }
    public function forOrderListApiView($values)
    {
        $fields_values = unserialize($values) == null ? [] : unserialize($values);
        // $data = [];
        // $i = 0;
        // foreach ($fields_values as $key => $val) {
        //     $str = $key . '|' . $val;
        //     $data[] = $str;
        //     $i++;
        // }
        return $fields_values;
    }
    public function forOrderListViewApishipping($values)
    {
        $fields_values = unserialize($values) == null ? [] : unserialize($values);
        // $data = [];
        // $i = 0;
        // foreach ($fields_values as $key => $val) {
        //     $str = $key . '|' . $val;
        //     $data[] = $str;
        //     $i++;
        // }
        return $fields_values;
    }
    public function forOrderListViewApiBilling($values)
    {
        $fields_values = unserialize($values) == null ? [] : unserialize($values);
        // $data = [];
        // $i = 0;
        // foreach ($fields_values as $key => $val) {
        //     $str = $key . '|' . $val;
        //     $data[] = $str;
        //     $i++;
        // }
        return $fields_values;
    }
    public function forOrderView($values)
    {
        $fields_values = unserialize($values) == null ? [] : unserialize($values);
        $data = [];
        $i = 0;
        foreach ($fields_values as $key => $val) {
            $str = $val;
            $data[] = $str;
            $i++;
        }
        return $data;
    }

    public function getOrdersByUserID($user_id)
    {

        $data = DB::table('tbl_orders')
            ->join('states', 'states.id', '=', 'tbl_orders.order_state')
            //->select('name as product_name', 'sale_price')
            ->where('tbl_orders.customer_id', '=', $user_id)
            ->orderBy('tbl_orders.created_at', 'desc')
            ->select('states.name as order_state', 'tbl_orders.order_status as order_status', 'tbl_orders.order_id', 'tbl_orders.id', 'tbl_orders.created_at')
            ->paginate(5);

        return $data;
    }

    public function getApprovalOrders()
    {
        $query = DB::table('lab_orders')->where('status','lab-editor-approval')->get();
        //dd($query);
        $pending = collect();
        foreach ($query as $order) {
            $patient = User::find($order->user_id);
            $order->product = QuestDataTestCode::where('TEST_CD', $order->product_id)->first();
            $sessions = Session::where('patient_id', $order->user_id)->groupBy('doctor_id')->get();
            $doctors = [];
            foreach ($sessions as $session) {
                $user = User::find($session->doctor_id);
                if ($user->specialization == 1)
                    array_push($doctors, $user);
            }
            $order->current_doctors = $doctors;
            $order->doctors = User::where(['user_type' => 'doctor', 'active' => '1', 'state_id' => $patient->state_id])->get();
            $order->state = State::find($patient->state_id);
            $push = true;

            $pending->push($order);
        }
        //dd($pending);
        return $pending;
    }
    public function assignOrderToDoctor($input)
    {
        // dd($input);
        $order = $this->model()::find($input['tbl_order_id']);
        $products = unserialize($order->lab_order_approvals);
        // dd($products);
        $up_products = [];
        foreach ($products as $product) {
            if ($product['flag'] == 0) {
                LabOrderApproval::create([
                    'tbl_order_id' => $input['tbl_order_id'],
                    'order_id' => $input['order_id'],
                    'product_id' => $product['product_id'],
                    //'test_cd'=>$product['test_code'],
                    'user_id' => $order->customer_id,
                    'doctor_id' => $input['doctor_id'],
                    'status' => 'pending',
                ]);
                $product['flag'] = 1;
                array_push($up_products, $product);
            }
        }
        $ser_products = serialize($up_products);
        $order->lab_order_approvals = $ser_products;
        $order->save();
        return 'success';
    }
    public function getDoctorPendingOrders()
    {
        $user = auth()->user();
        if ($user->user_type == 'doctor') {
            $pendingOrders = DB::table('lab_orders')
                ->join('quest_data_test_codes', 'quest_data_test_codes.TEST_CD', 'lab_orders.product_id')
                ->where('lab_orders.doc_id', $user->id)
                ->where('lab_orders.status', 'forwarded_to_doctor')
                ->select('lab_orders.*', 'quest_data_test_codes.DESCRIPTION as name')
                ->paginate(5);
            //  dd($pendingOrders);
            return $pendingOrders;
        } else {
            return redirect()->route('home');
        }
    }
    public function acceptLabOrder($input)
    {
        // dd($input);
        $doc = auth()->user();
        $approval = LabOrderApproval::find($input['id']);
        $tbl_order = $this->model()::find($input['tbl_order_id']);
        $sub_ids = unserialize($tbl_order->order_sub_id);
        // dd($sub_ids);
        foreach ($sub_ids as $key => $id) {
            if ($key == 'LBT') {
                // dd($id);
                $lab_order = QuestLab::where('order_id', $id)->first();
                // dd($lab_order);
                if ($lab_order != null) {
                    $lab_order->upin = $doc->upin;
                    $lab_order->npi = $doc->nip_number;
                    $lab_order->ref_physician_id = $doc->last_name . ' ,' . $doc->name;
                    $lab_order->save();
                    // dd($lab_order);
                    $hl7_obj = new HL7Controller();
                    $hl7_obj->hl7Encode($lab_order);
                    $approval->status = 'approved';
                    $approval->save();
                } else {
                    $approval->status = 'invalid';
                    $approval->save();
                    return 'invalid';
                }
            }
        }
        return 'done';
    }
    public function getAllPendingOrders()
    {
        $user = auth()->user();
        if ($user->user_type == 'editor_lab' || $user->user == 'admin_lab') {
            return $pendingOrders =
                DB::table('lab_orders')
                ->join('quest_data_test_codes', 'quest_data_test_codes.TEST_CD', '=', 'lab_orders.product_id')
                ->join('users', 'users.id', '=', 'lab_orders.doc_id')
                ->where('lab_orders.status', 'forwarded_to_doctor')
                ->select(
                    'lab_orders.*',
                    'quest_data_test_codes.TEST_NAME as name',
                    DB::raw("CONCAT(users.name,' ', users.last_name) AS doctor_name"),
                )
                ->paginate(12);
        } else {
            return redirect()->route('home');
        }
    }
    public function getLabOrder($labApprovalId)
    {
        return  DB::table('lab_order_approvals')
            ->join('tbl_products', 'tbl_products.id', '=', 'lab_order_approvals.product_id')
            ->join('users', 'users.id', '=', 'lab_order_approvals.doctor_id')
            ->join('users as patient', 'patient.id', '=', 'lab_order_approvals.user_id')
            ->where('lab_order_approvals.id', $labApprovalId)
            ->select(
                'lab_order_approvals.*',
                'tbl_products.name as product_name',
                'patient.email',
                DB::raw("CONCAT(users.name,' ', users.last_name) AS doctor_name"),
                DB::raw("CONCAT(patient.name,' ', patient.last_name) AS patient_name")
            )->first();
    }
    public function getPendingRefunds()
    {
        $refundOrders = DB::table('lab_order_approvals')
            ->join('tbl_orders', 'tbl_orders.id', '=', 'lab_order_approvals.tbl_order_id')
            ->where('lab_order_approvals.status', 'declined')
            ->select('lab_order_approvals.*', 'tbl_orders.lab_order_approvals', 'tbl_orders.transaction_id')
            ->paginate(6);
        foreach ($refundOrders as $order) {
            $lab_cart_order = unserialize($order->lab_order_approvals);
            foreach ($lab_cart_order as $onlineLabsOfOrder) {
                if ($onlineLabsOfOrder['product_id'] == $order->product_id) {
                    $order->price = TblCart::find($lab_cart_order[0]['cart_row_id'])->update_price;
                }
            }
        }
        // dd($refundOrders);
        return $refundOrders;
    }
}
