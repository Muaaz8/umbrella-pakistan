<?php

namespace App\Http\Controllers;

use App\MedicineUOM;
use Illuminate\Http\Request;
use DB;
use Auth;


class MedicineUOMController extends Controller
{

    public function index()
    {
        return view('all_products.rxOutreach.medicineUOM');
    }

    public function store(Request $request)
    {
        $input = $request->input();
        $check = MedicineUOM::where('unit', $input['unit'])->count();
        if ($check == 0 && $input['unit'] != "") {
            $save = MedicineUOM::create([
                'unit' => $input['unit'],
                'created_at' => NOW(),
                'update_at' => NOW()
            ]);
            $data = MedicineUOM::where('id', $save->id)->first()->toJson();
            echo $data;
        } else {
            echo 0;
        }
    }

    public function dash_store(Request $request)
    {
        $input = $request->input();
        $check = MedicineUOM::where('unit', $input['unit'])->count();
        if ($check == 0 && $input['unit'] != "") {
            $save = MedicineUOM::create([
                'unit' => $input['unit'],
                'created_at' => NOW(),
                'update_at' => NOW()
            ]);
        }
        return redirect()->back();
    }

    public function show()
    {
        if (isset($_GET['unit']) && !empty($_GET['unit'])) {
            $dataGrid = MedicineUOM::where([['status', 1], ['unit', 'like', '%' . $_GET['unit'] . '%']])->get()->toJson();
        } else {
            $dataGrid = MedicineUOM::where('status', 1)->get()->toJson();
        }

        return $dataGrid;
    }

    public function dash_show()
    {
        if (isset($_GET['unit']) && !empty($_GET['unit'])) {
            $dataGrid = MedicineUOM::where(['unit', 'like', '%' . $_GET['unit'] . '%'])->paginate(10);
        } else {
            $dataGrid = DB::table('medicine_units')->paginate(10);
        }
        $data = DB::table('medicine_units')->get();
        $user_type = Auth::user()->user_type;
        if($user_type == 'admin_pharm'){
            return view('dashboard_Pharm_admin.Medicine.medicine_UOM', compact('dataGrid','data'));
        }elseif($user_type == 'editor_pharmacy'){
            return view('dashboard_Pharm_editor.Medicine.medicine_UOM', compact('dataGrid','data'));
        }
    }

    public function update(Request $request)
    {
        $input = $request->input();
        MedicineUOM::where('id', $input['id'])->update([
            'unit' => $input['unit'],
            'created_at' => NOW(),
            'update_at' => NOW()
        ]);
    }

    public function dash_update(Request $request)
    {
        $input = $request->input();
        MedicineUOM::where('id', $input['edit_id'])->update([
            'unit' => $input['unit'],
            'created_at' => NOW(),
            'updated_at' => NOW()
        ]);
        return redirect()->back();
    }

    public function destroy(Request $request)
    {
        $input = $request->input();
        MedicineUOM::where([
            'id' => $input['id']
        ])->delete();
    }

    public function dash_deactive(Request $request)
    {
        $input = $request->input();
        dd($input);
        MedicineUOM::where([
            'id' => $input['delete_id']
        ])->delete();
        return redirect()->back();
    }
}
