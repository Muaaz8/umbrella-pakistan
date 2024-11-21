<?php

namespace App\Http\Controllers;

use App\ActivityLog;
use App\MedicineDays;
use App\MedicinePricing;
use App\MedicineUOM;
use App\Models\AllProducts;
use App\User;
use App\Models\ProductCategory;
use App\Models\ProductsSubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laracasts\Flash\Flash;

class MedicineImportController extends Controller
{
    public function index()
    {
        return view('all_products.rxOutreach.upload_medicines');
    }

    public function dash_index()
    {
        $user_type = Auth::user()->user_type;
        if($user_type == 'admin_pharm'){
            return view('dashboard_Pharm_admin.Medicine.upload_med');
        }elseif($user_type == 'editor_pharmacy'){
            return view('dashboard_Pharm_editor.Medicine.upload_med');
        }
    }

    public function viewMedicine(Request $request)
    {
        // Active Inactive Products
        $param = $request->query();
        // Add Form Of Medicine Variation
        $form_type = '';
        if (isset($_GET['form_type'])) {$form_type = $_GET['form_type'];}
         // End Form Of Medicine Variation
        if (isset($param['status']) && $param['status'] == 'deactive') {
            $delete1 = AllProducts::where([
                'id' => $param['product_id'],
            ])->update([
                'product_status' => 0,
                'is_approved' => 0
            ]);
        } elseif (isset($param['status']) && $param['status'] == 'active') {
            $delete1 = AllProducts::where([
                'id' => $param['product_id'],
            ])->update([
                'product_status' => 1,
                'is_approved' => 1
            ]);
        }

        $sub_category = ProductsSubCategory::select('id', 'title as sub_category')->where('parent_id', 38)->get()->toJson();
        $medicineUOM = MedicineUOM::select('unit as unit_id', 'unit as unit_name','id')->get()->toArray();
        $medicineDays = MedicineDays::select('days as days_id', 'days','id')->get()->toArray();
        $allProducts = AllProducts::select('name as product_id', 'name','id')->where([['parent_category', 38], ['mode', 'medicine']])->get()->toArray();
        $product_category = json_decode($sub_category);
        // dd($medicineDays);

        return view('all_products.rxOutreach.viewMedicines', compact('sub_category','form_type', 'product_category','allProducts', 'medicineUOM', 'medicineDays'));
    }

    public function pe_view_Medicine(Request $request)
    {

        // Active Inactive Products
        $param = $request->query();
        // Add Form Of Medicine Variation
        $form_type = '';
        if (isset($_GET['form_type'])) {$form_type = $_GET['form_type'];}
         // End Form Of Medicine Variation
        if (isset($param['status']) && $param['status'] == 'deactive') {
            $delete = DB::table('medicine_pricings')->where('id',$param['product_id'])->delete();
            // $delete1 = AllProducts::where('id',$param['product_id'])->update([
            //     'product_status' => 0,
            //     'is_approved' => 0
            // ]);
            // dd($delete1);
        } elseif (isset($param['status']) && $param['status'] == 'active') {
            $delete1 = AllProducts::where([
                'id' => $param['product_id'],
            ])->update([
                'product_status' => 1,
                'is_approved' => 1
            ]);
        }
        $data = DB::table('medicine_pricings')
            ->leftJoin('tbl_products', 'tbl_products.id', '=', 'medicine_pricings.product_id')
            ->leftJoin('medicine_units', 'medicine_units.id', '=', 'medicine_pricings.unit_id')
            ->leftJoin('medicine_days', 'medicine_days.id', '=', 'medicine_pricings.days_id')
            ->leftJoin('products_sub_categories', 'tbl_products.sub_category', '=', 'products_sub_categories.id')
            ->select(
                'medicine_pricings.id',
                'medicine_pricings.product_id',
                'tbl_products.name',
                'tbl_products.short_description',
                'tbl_products.updated_at',
                'medicine_pricings.unit_id',
                'medicine_units.unit',
                'medicine_pricings.days_id',
                'medicine_days.days',
                'medicine_pricings.price',
                'medicine_pricings.sale_price',
                'products_sub_categories.title as sub_category',
                'percentage',
                DB::raw("CASE WHEN (product_status = '0' && is_approved = '0' ) THEN 'Deactive' ELSE 'Active' END AS statusProduct"),
            )
            ->orderBy('tbl_products.name', 'asc')
            ->paginate(20);
        $data1 = DB::table('medicine_pricings')
            ->leftJoin('tbl_products', 'tbl_products.id', '=', 'medicine_pricings.product_id')
            ->leftJoin('medicine_units', 'medicine_units.id', '=', 'medicine_pricings.unit_id')
            ->leftJoin('medicine_days', 'medicine_days.id', '=', 'medicine_pricings.days_id')
            ->leftJoin('products_sub_categories', 'tbl_products.sub_category', '=', 'products_sub_categories.id')
            ->select(
                'medicine_pricings.id',
                'medicine_pricings.product_id',
                'tbl_products.name',
                'tbl_products.short_description',
                'tbl_products.updated_at',
                'medicine_pricings.unit_id',
                'medicine_units.unit',
                'medicine_pricings.days_id',
                'medicine_days.days',
                'medicine_pricings.price',
                'medicine_pricings.sale_price',
                'products_sub_categories.title as sub_category',
                'percentage',
                DB::raw("CASE WHEN (product_status = '0' && is_approved = '0' ) THEN 'Deactive' ELSE 'Active' END AS statusProduct"),
            )
            ->orderBy('tbl_products.name', 'asc')
            ->get()->toArray();
        // dd($data);
        foreach($data as $dt){
            $dt->updated_at = User::convert_utc_to_user_timezone(Auth::user()->id,$dt->updated_at);
            $dt->updated_at = $dt->updated_at['date']." ".$dt->updated_at['time'];
        }
        $days = DB::table('medicine_days')->get();
        $units = DB::table('medicine_units')->get();
        $allProducts = AllProducts::select('name as product_id', 'name','id')->where([['parent_category', 38], ['mode', 'medicine']])->get();
        $user_type = Auth::user()->user_type;
        if($user_type == 'admin_pharm'){
            return view('dashboard_Pharm_admin.Medicine.view_medicine', compact('data','days','units','allProducts','data1'));
        }elseif($user_type == 'editor_pharmacy'){
            return view('dashboard_Pharm_editor.Medicine.view_medicine', compact('data','days','units','allProducts','data1'));
        }
    }

    // Store function of Medicine Variations
    public function storeMedicineVariation(Request $request)
    {
        $user_id = Auth::user()->id;
        $input = $request->all();
        // dd($input);
        $medSalePrice = $input['price'];
        $medPercentage = $input['percentage'] / 100;
        $input['sale_price'] = floor(($medPercentage * $medSalePrice) +  $medSalePrice);

        $data = MedicinePricing::Create([
            'product_id' => $input['product_id'],
            'unit_id' => $input['unit_id'],
            'days_id' => $input['days_id'],
            'price' => $input['price'],
            'sale_price' => $input['sale_price'],
            'percentage' => $input['percentage'],
            'created_by' => $user_id,
        ]);

    Flash::success('Data saved successfully.');
    return redirect()->back();

    }

    public function dash_storeMedicineVariation(Request $request)
    {
        $user_id = Auth::user()->id;
        $input = $request->all();
        // dd($input);
        $medSalePrice = $input['price'];
        $medPercentage = $input['percentage'] / 100;
        $input['sale_price'] = floor(($medPercentage * $medSalePrice) +  $medSalePrice);

        $data = MedicinePricing::Create([
            'product_id' => $input['product_id'],
            'unit_id' => $input['unit_id'],
            'days_id' => $input['days_id'],
            'price' => $input['price'],
            'sale_price' => $input['sale_price'],
            'percentage' => $input['percentage'],
            'created_by' => $user_id,
        ]);

    Flash::success('Data saved successfully.');
    return redirect()->back();

    }
    public function getRxMedicine()
    {

        $name = empty($_GET['name']) ? '' : $_GET['name'];
        $short_description = empty($_GET['short_description']) ? '' : $_GET['short_description'];
        $unit = empty($_GET['unit']) ? '' : $_GET['unit'];
        $days = empty($_GET['days']) ? '' : $_GET['days'];
        $price = empty($_GET['price']) ? '' : $_GET['price'];

        $data = DB::table('medicine_pricings')
            ->leftJoin('tbl_products', 'tbl_products.id', '=', 'medicine_pricings.product_id')
            ->leftJoin('medicine_units', 'medicine_units.id', '=', 'medicine_pricings.unit_id')
            ->leftJoin('medicine_days', 'medicine_days.id', '=', 'medicine_pricings.days_id')
            ->leftJoin('products_sub_categories', 'tbl_products.sub_category', '=', 'products_sub_categories.id')
            ->select(
                'medicine_pricings.id',
                'medicine_pricings.product_id',
                'tbl_products.name',
                'tbl_products.short_description',
                'medicine_pricings.unit_id',
                'medicine_units.unit',
                'medicine_pricings.days_id',
                'medicine_days.days',
                'medicine_pricings.price',
                'medicine_pricings.sale_price',
                'products_sub_categories.title as sub_category',
                'percentage',
                DB::raw("CASE WHEN (product_status = '0' && is_approved = '0' ) THEN 'Deactive' ELSE 'Active' END AS statusProduct"),
            )
            ->where('tbl_products.name', 'like', '%' . $name . '%')
            ->where('tbl_products.short_description', 'like', '%' . $short_description . '%')
            ->where('medicine_units.unit', 'like', '%' . $unit . '%')
            ->where('medicine_days.days', 'like', '%' . $days . '%')
            ->where('medicine_pricings.price', 'like', '%' . $price . '%')
            ->orderBy('tbl_products.name', 'asc')
            ->get()->toJson();

        return $data;
    }

    public function editRxMedicine(Request $request)
    {
        $input = $request->input();
        $medicineDay = MedicineDays::where('days', $input['days'])->first();
        $medUnit = MedicineUOM::where('unit', $input['unit'])->first();
        $subCategoryID = ProductsSubCategory::select('id')->where('title', $input['sub_category'])->first();
        $updateCategoryID = AllProducts::where('id', $input['product_id'])->update(['sub_category' => $subCategoryID->id]);

            // Calculating Percentage
            $medSalePrice = $input['price'];
            $medPercentage = $input['percentage'] / 100;
            $input['sale_price'] = floor(($medPercentage * $medSalePrice) +  $medSalePrice);
            // dd($input);
            MedicinePricing::where('id', $input['id'])->update([
            'price' => $input['price'],
             'sale_price' => $input['sale_price'],
            'unit_id' => $medUnit->id,
            'days_id' => $medicineDay->id,
            'percentage' => $input['percentage'],
            'updated_at' => NOW(),
        ]);

        $create = ActivityLog::create([
            'activity' => 'Medicine id '.$input['id'].' updated by ' . auth()->user()->name,
            'type' => 'Medicine update',
            'user_id' => auth()->user()->id,
            'user_type' => 'editor_pharmacy'
        ]);

        return $input;

    }

    public function dash_editRxMedicine(Request $request)
    {
        $input = $request->input();
        $medicineDay = MedicineDays::where('days', $input['days'])->first();
        $medUnit = MedicineUOM::where('unit', $input['unit'])->first();
        $subCategoryID = ProductsSubCategory::select('id')->where('title', $input['sub_category'])->first();
        $updateCategoryID = AllProducts::where('id', $input['product_id'])->update(['sub_category' => $subCategoryID->id]);

            // Calculating Percentage
            $medSalePrice = $input['price'];
            $medPercentage = $input['percentage'] / 100;
            $input['sale_price'] = floor(($medPercentage * $medSalePrice) +  $medSalePrice);
            // dd($input);
            MedicinePricing::where('id', $input['id'])->update([
            'price' => $input['price'],
             'sale_price' => $input['sale_price'],
            'unit_id' => $medUnit->id,
            'days_id' => $medicineDay->id,
            'percentage' => $input['percentage'],
            'updated_at' => NOW(),
        ]);

        $create = ActivityLog::create([
            'activity' => 'Medicine id '.$input['id'].' updated by ' . auth()->user()->name,
            'type' => 'Medicine update',
            'user_id' => auth()->user()->id,
            'user_type' => 'editor_pharmacy'
        ]);

        return redirect()->back();

    }

    public function dash_get_medicine_details(){
        $days = DB::table('medicine_days')->get();
        $units = DB::table('medicine_units')->get();
        $allProducts = AllProducts::select('name as product_id', 'name','id')->where([['parent_category', 38], ['mode', 'medicine']])->get()->toArray();
        $data = array([
            'day' => $days,
            'unit' => $units,
            'allProducts' => $allProducts,
        ]);
        $data = json_encode($data);
        return $data;
    }

    public function deleteRxMedicine(Request $request)
    {
        $input = $request->input();
        $delete1 = AllProducts::where([
            'id' => $input['product_id'],
        ])->delete();
        $delete2 = MedicinePricing::where([
            'product_id' => $input['product_id'],
        ])->delete();
    }

    public function dash_deleteRxMedicine(Request $request)
    {
        $input = $request->input();
        // AllProducts::find($input['del_id'])->forcedelete();
        $delete2 = MedicinePricing::where([
            'product_id' => $input['del_id'],
        ])->forcedelete();
        return redirect()->back();
        // $delete1 = AllProducts::where([
        //     'id' => $input['del_id'],
        // ])->delete();
        // return redirect()->back();
    }

    public function uploadFile(Request $request)
    {
        $user_id = Auth::user()->id;

        $fileData = $this->readCSV($request->file('file'), ',');

        $errorValidation = $this->errorValidation($fileData);

        if (count($errorValidation) == 0) {

            $pricingData = [];

            foreach ($fileData as $item) {

                // echo $item['ProductName'];
                $product_id = $this->payloadProducts($item, $user_id);

                // $string = $product_id . '|';

                $units = explode(",", $item['Units']);

                foreach ($units as $unitName) {

                    $unit = MedicineUOM::where('unit', $unitName)->first();
                    $unit_id = $unit->id;
                    $prices = explode(",", $item['Price']);
                    $days = explode(",", $item['Days']);

                    for ($i = 0; $i < count($prices); $i++) {

                        $price = $prices[$i];
                        $day = $days[$i];
                        $dayss = MedicineDays::where('days', 'like', '%' . $day . '%')->first();
                        $days_id = $dayss->id;

                        $pricingData[] = [
                            'product_id' => $product_id,
                            'unit_id' => $unit_id,
                            'days_id' => $days_id,
                            'price' => $price,
                            'sale_price' => $price,
                            'created_by' => $user_id,
                        ];
                    }
                }
            }

            foreach ($pricingData as $pricing) {
                $addPricing = MedicinePricing::create($pricing);
            }

            Flash::success("Successfully Uploaded.");
            return redirect('uploadMedicineByCSV');
        } else {
            foreach ($errorValidation as $message) {
                Flash::error($message['message']);
            }
            return redirect('uploadMedicineByCSV');
        }
    }

    public function dash_uploadFile(Request $request)
    {
        $user_id = Auth::user()->id;
        $fileData = $this->readCSV($request->file('file'), ',');
        $errorValidation = $this->errorValidation($fileData);
        if (count($errorValidation) == 0) {
            $pricingData = [];
            foreach ($fileData as $item) {
                $product_id = $this->payloadProducts($item, $user_id);
                $units = explode(",", $item['Units']);
                $prices = explode(",", $item['Price']);
                foreach ($units as $key => $unitName) {
                    $unit = MedicineUOM::where('unit', $unitName)->first();
                    if($unit){
                        $unit_id = $unit->id;
                    }else{
                        $new_unit = MedicineUOM::create([
                            'unit'=> $unitName,
                            'status'=> 1,
                        ])->first();
                        $unit_id = $new_unit->id;

                    }
                    $price = $prices[$key];
                    $pricingData[] = [
                        'product_id' => $product_id,
                        'unit_id' => $unit_id,
                        'price' => $price,
                        'sale_price' => $price,
                        'created_by' => $user_id,
                    ];
                }
            }

            foreach ($pricingData as $pricing) {
                $addPricing = MedicinePricing::create($pricing);
            }

            Flash::success("Successfully Uploaded.");
            return redirect()->back();
        } else {
            foreach ($errorValidation as $message) {
                Flash::error($message['message']);
            }
            return redirect()->back();
        }
    }

    public function payloadProducts($item, $user_id)
    {
        $main = ProductCategory::where('slug',$this->slugify($item['MainCategory']))->first();
        $sub = ProductsSubCategory::where('slug',$this->slugify($item['SubCategory']))->first();
        $prod = AllProducts::where('name',$item['ProductName'])->where('parent_category',$main->id)->where('sub_category',$sub->id)->first();
        if($prod){
            $product_id = $prod->id;
        }else{
            $arr = [
                'name' => $item['ProductName'],
                'slug' => $this->slugify($item['ProductName']),
                'generic' => $item['Generic'],
                'class' => $item['Class'],
                'parent_category' => $main->id,
                'sub_category' => $sub->id,
                'featured_image' => 'dummy_medicine.png',
                'sale_price' => 0,
                'regular_price' => 0,
                'quantity' => 999,
                'mode' => 'medicine',
                'medicine_type' => 'prescribed',
                'is_featured' => 0,
                'short_description' => $item['Description'],
                'description' => $item['Description'],
                'medicine_ingredients' => '0',
                'stock_status' => 'in_stock',
                'medicine_warnings' => '0',
                'medicine_directions' => '0',
                'user_id' => $user_id,
                'product_status' => 1,
                'is_approved' => 1,
                'is_single' => $item['Is_single'],
            ];

            $product_id = AllProducts::create($arr)->id;
        }
        return $product_id;
    }

    public function errorValidation($data)
    {
        $i = 2;
        $result = [];
        foreach ($data as $item) {
            if (empty($item['ProductName']) || empty($item['MainCategory']) || empty($item['Units']) || empty($item['Price'])) {
                $result[] = [
                    'message' => 'Empty Column found at line ' . $i,
                    'status' => 'ERROR',
                ];
            }

            // if (count(explode(",", $item['Price'])) != count(explode(",", $item['Days']))) {
            //     $result[] = [
            //         'message' => 'Check Price and Days at line ' . $i,
            //         'status' => 'ERROR',
            //     ];
            // }


            $units = explode(",", $item['Units']);

            foreach ($units as $unitName) {
                $unit = MedicineUOM::where('unit', $unitName)->count();
                if ($unit == 0) {
                    $newUnit = MedicineUOM::create([
                        'unit' => $unitName,
                        'status' => 1,
                    ]);
                }
            }

            // $days = explode(",", $item['Days']);

            // foreach ($days as $day) {
            //     $day_data = MedicineDays::where('days', 'like', '%' . $day . '%')->count();
            //     if ($day_data == 0) {
            //         $result[] = [
            //             'message' => $day . ' Days  not found in our records at line ' . $i,
            //             'status' => 'ERROR',
            //         ];
            //     }
            // }

            $i++;
        }
        return $result;
    }

    public function readCSV($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename)) {
            return false;
        }

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if (!$header) {
                    $header = $row;
                } else {
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }

        return $data;
    }

    public function slugify($string)
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
    }
}
