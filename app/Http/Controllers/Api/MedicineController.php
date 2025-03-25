<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Prescription;

class MedicineController extends Controller
{
    public function get_medicine_detail(Request $request){
        {
            $response['product'] = \DB::table('tbl_products')
                ->join('medicine_pricings', 'medicine_pricings.product_id', '=', 'tbl_products.id')
                ->where('tbl_products.id', $request['id'])
                ->select('tbl_products.id', 'tbl_products.name', 'medicine_pricings.price', 'medicine_pricings.sale_price')
                ->first();
            $response['units'] = \DB::table('tbl_products')
                ->join('medicine_pricings', 'medicine_pricings.product_id', '=', 'tbl_products.id')
                ->join('medicine_units', 'medicine_units.id', '=', 'medicine_pricings.unit_id')
                ->groupBy('medicine_units.id')
                ->where('tbl_products.id', $request['id'])
                ->select('medicine_units.id', 'medicine_units.unit', 'medicine_pricings.price', 'medicine_pricings.sale_price')
                ->get();
            $res = Prescription::where('session_id', $request['session_id'])->where('medicine_id', $request['id'])->first();
            $inclinic_res = Prescription::where('session_id', '0')->where('medicine_id', $request['id'])->where('parent_id', $request['session_id'])->first();
            if($res){
                if ($res->med_unit != null && $res->med_time != null) {
                    $response['update'] = ['units' => $res->med_unit, 'time' => $res->med_time, 'comment' => $res->comment];
                }
            }
            if($inclinic_res){
                if ($inclinic_res->med_unit != null && $inclinic_res->med_time != null) {
                    $response['update'] = ['units' => $inclinic_res->med_unit, 'time' => $inclinic_res->med_time, 'comment' => $inclinic_res->comment, 'days' => $inclinic_res->med_days];
                }
            }
            return response()->json($response);
        }
    }
    
}
