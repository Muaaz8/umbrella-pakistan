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
            $pres = DB::table('prescriptions')->where('session_id', $request['session_id'])->where('medicine_id', $id)->get();
            if (count($pres) == 0) {
                Prescription::insert([
                    'session_id' => $request['session_id'],
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
        $pres = DB::table('prescriptions')->where('session_id', $request['session_id'])->where('test_id', $request['id'])->get();
        $test = DB::table('quest_data_test_codes')->where('TEST_CD', $request['id'])->first();
        if (count($pres) == 0) {
            Prescription::insert([
                'session_id' => $request['session_id'],
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
        $pres = DB::table('prescriptions')->where('session_id', $request['session_id'])->where('imaging_id', $request['id'])->get();
        $test = DB::table('quest_data_test_codes')->where('TEST_CD', $request['id'])->first();
        if (count($pres) == 0) {
            Prescription::insert([
                'session_id' => $request['session_id'],
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
        $res = Prescription::where('session_id', $request['session_id'])->where('medicine_id', $request['pro_id'])->update([
            'med_days' => $request['days'],
            // 'med_unit' => $request['units'],
            'med_time' => $request['med_time'],
            // 'price' => $request['price'],
            'comment' => $request['instructions'],
            'usage' => 'Dosage: ' . $request['med_time'] . ' Times a day for ' . $request['days'] . ' days',
        ]);
        if ($res) {
            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'error']);
        }
    }

    public function getSessionPrescription($id)
    {
        $prescriptions = Prescription::with(['medicine','test','imaging'])->where('session_id', $id)->get();
        return response()->json($prescriptions);
    }


}
