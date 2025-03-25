<?php

namespace App\Http\Controllers\Api;

use App\Events\LoadPrescribeItemList;
use App\Http\Controllers\Controller;
use App\Models\AllProducts;
use App\Prescription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;

class PrescriptionController extends Controller
{
    public function addMedicine(Request $request)
    {
        $id = $request['id'];
        if ($request['type'] == 'med') {
            $pres = DB::table('prescriptions')->where('session_id',"0")->where('parent_id',$request['session_id'])->where('medicine_id',$id)->get();
            if(count($pres)==0){
                Prescription::insert([
                    'session_id' => "inclinic",
                    'medicine_id' => $id,
                    'type' => 'medicine',
                    'title' => 'pending',
                    'quantity' => 1,
                    'parent_id' => $request['session_id'],
                    'created_at' => Carbon::now(),
                ]);
                event(new LoadPrescribeItemList($request['session_id'], $request['user_id']));
            }
            return response()->json(['status' => 'success']);
        }
    }
    public function addLab(Request $request)
    {
        $pres = DB::table('prescriptions')->where('session_id','0')->where('test_id',$request['id'])->where('parent_id',$request['session_id'])->get();
        $test = DB::table('quest_data_test_codes')->where('TEST_CD',$request['id'])->first();
        if(count($pres)==0){
            Prescription::insert([
                'session_id' => "0",
                'test_id' => $request['id'],
                'type' => $test->mode,
                'title' => 'pending',
                'price' => $test->SALE_PRICE,
                'quantity' => 1,
                'parent_id' => $request['session_id'],
                'created_at' => Carbon::now(),
            ]);
            event(new LoadPrescribeItemList($request['session_id'], $request['user_id']));
            
            return response()->json(['status' => 'success']);
        }
    }

    public function addImaging(Request $request)
    {
        $pres = DB::table('prescriptions')->where('session_id','0')->where('imaging_id',$request['id'])->where('parent_id',$request['session_id'])->get();
        $test = DB::table('quest_data_test_codes')->where('TEST_CD',$request['id'])->first();
        if(count($pres)==0){
            Prescription::insert([
                'session_id' => '0',
                'imaging_id' => $request['id'],
                'type' => 'imaging',
                'quantity' => 1,
                'price' => $test->SALE_PRICE,
                'title' => 'pending',
                'parent_id' => $request['session_id'],
                'created_at' => Carbon::now(),
            ]);

            event(new LoadPrescribeItemList($request['session_id'], $request['user_id']));
            return response()->json(['status' => 'success']);
        }
    }

    public function removeItem(Request $request)
    {
        if ($request['type'] == "lab-test") {
            Prescription::where('session_id', $request['session_id'])->where('test_id', $request['pro_id'])->delete();
        } else if ($request['type'] == "imaging") {
            Prescription::where('session_id', $request['session_id'])->where('imaging_id', $request['pro_id'])->delete();
        } else if ($request['type'] == "medicine") {
            Prescription::where('session_id', $request['session_id'])->where('medicine_id', $request['pro_id'])->delete();
        }
        event(new LoadPrescribeItemList($request->session_id, $request->user_id));
        return response()->json(['status' => 'success']);
    }

    public function addMedicineDose(Request $request)
    {
        $product = AllProducts::find($request['pro_id']);
        $med_unit = DB::table('medicine_units')->where('unit',$request['units'])->first();
        $quantity = 0;
        $price = DB::table('medicine_pricings')
            ->where('product_id', $request['pro_id'])
            ->where('unit_id',$med_unit->id)
            ->first();
        if($product->is_single == 1){
            $totalprice = $price->sale_price * ($request['days']*$request['med_time']);
            $quantity = $request['days']*$request['med_time'];
        }else{
            $totalprice = $price->sale_price;
            $quantity = 1;
        }
        $res = Prescription::where('session_id', '0')->where('medicine_id', $request['pro_id'])->where('parent_id', $request['session_id'])->update([
            'med_days' => $request['days'],
            'med_unit' => $request['units'],
            'med_time' => $request['med_time'],
            'quantity' => $quantity,
            'price' => $totalprice,
            'comment' => $request['instructions'],
            'usage' => $request['med_time'] . ' Times a day for ' . $request['days'] . ' days',
        ]);
        if ($res) {
            return response()->json(['status' => 'success']);
        }
    }

}
