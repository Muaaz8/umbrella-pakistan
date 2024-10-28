<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\User;

class ImagingController extends BaseController
{
    public function imaging(){
        $user = auth()->user();
        $tblOrders = DB::table('imaging_orders')
            ->join('tbl_products', 'imaging_orders.product_id', '=', 'tbl_products.id')
            ->join('users', 'users.id', '=', 'imaging_orders.user_id')
            ->join('states', 'states.id', '=', 'users.state_id')
            ->join('cities', 'cities.id', '=', 'users.city_id')
            ->join('tbl_orders', 'tbl_orders.order_id', '=', 'imaging_orders.order_id')
            ->join('sessions', 'sessions.id', '=', 'imaging_orders.session_id')
            ->join('users as doc', 'doc.id', '=', 'sessions.doctor_id')
            ->where('imaging_orders.user_id','=',Auth::user()->id)
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
           if(!$tblOrders->isEmpty()){
            $imaging_data['code'] = 200;
            $imaging_data['tblOrders'] = $tblOrders;
                return $this->sendResponse($imaging_data,"Imaging list");
           } else{
                $imaging_data['code'] = 200;
                return $this->sendResponse($imaging_data,"No Image found");
           }
    }
    public function dash_imaging_file(){
        $imagingDetails = DB::table('imaging_file')->where('patient_id',auth()->user()->id)->orderby('id','desc')->paginate(10);
        foreach ($imagingDetails as $m) {
            $m->names = DB::table('imaging_orders')
            ->join('tbl_products','imaging_orders.product_id','tbl_products.id')
            ->where('imaging_orders.order_id',$m->order_id)
            ->select('tbl_products.name')->get();
            $doc = DB::table('users')->where('id',$m->doctor_id)->first();
            $m->doc = $doc->name.' '.$doc->last_name;
            $m->created_at = User::convert_utc_to_user_timezone(auth()->user()->id, $m->created_at);
            $m->created_at = $m->created_at['date'] . ' ' . $m->created_at['time'];
            $m->fileURL =  \App\Helper::get_files_url($m->filename);
        }
        $imagingData['code'] = 200;
        $imagingData['imagingDetails'] = $imagingDetails;
        return $this->sendResponse($imagingData,'Imaging Files');
    }
    public function imaging_products(){
        $data = DB::table('tbl_products')->where('mode','imaging')->where('product_status',1)->paginate(10);
        if(!$data->isEmpty()){
            $status =200;
            $imaging_products['code'] = $status;
            $imaging_products['imaging_products'] = $data;
            return $this->sendResponse($imaging_products,"Imaging Products");
        } else{
            $status =200;
            $imaging_products['code'] = $status;
            return $this->sendError($imaging_products,"Error in imaging product");
        }
    }
    public function imaging_result_search(Request $request)
    {   
        $search = $request->imaging_name;
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
            ->where('tbl_products.name','LIKE','%'.$search.'%')
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
                'sessions.id as session_id',
                'doc.name as doc_fname',
                'doc.last_name as doc_lname',
                'doc.nip_number',
                'doc.upin'
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
        $imagingData['code'] = 200;
        $imagingData['tblOrders'] = $tblOrders;
        return $this->sendResponse($imagingData,'Imaging report list');
    }
}
