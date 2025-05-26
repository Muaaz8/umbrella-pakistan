<?php

namespace App\Http\Controllers;

use App\Mail\UserVerificationEmail;
use App\Models\Location;
use App\User;
use App\VendorAccount;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Image;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;

class VendorsController extends Controller
{
    public function index($shop_type)
    {
        $vendors = DB::table('vendor_accounts')->where('vendor', $shop_type)->paginate(12);

        foreach ($vendors as $key => $vendor) {
            $vendor->image = \App\Helper::check_bucket_files_url($vendor->image);
            $vendor->products_count = DB::table('vendor_products')
                ->where('vendor_id', $vendor->id)
                ->where('is_active', 1)
                ->count();
        }
        return view('website_pages.vendors.index', compact('vendors'));
    }

    public function create_vendor_page()
    {
        $locations = Location::latest()->get();

        return view('website_pages.vendors.create', compact('locations'));
    }


    public function showVendors(Request $request)
    {
        $search = $request->input('search', '');

        $query = DB::table('vendor_accounts')
            ->join('users', 'vendor_accounts.user_id', '=', 'users.id')
            ->select('vendor_accounts.*', 'users.name as user_name', 'users.last_name as user_last_name');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'LIKE', "%{$search}%")
                    ->orWhere('vendor_accounts.vendor_number', 'LIKE', "%{$search}%")
                    ->orWhere('vendor_accounts.name', 'LIKE', "%{$search}%");
            });
        }

        $vendors = $query->paginate(12);

        if ($request->ajax()) {
            return view('website_pages.vendors.all_vendors', compact('vendors'))->render();
        }

        return view('website_pages.vendors.all_vendors', compact('vendors'));
    }


    public function create(Request $request)
    {
        try {

            $validated = $request->validate([
                'vendor_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
                'phone_number' => 'required|string',
                'vendor_number' => 'required|string',
                'name' => 'required|string',
                'vendor' => 'required|in:pharmacy,labs',
                'address' => 'required|string',
                'user_type' => 'required|in:vendor',
                'image' => 'nullable|image|max:2048',
            ]);

            DB::beginTransaction();

            $timeZone = 'Asia/Karachi';

            $user = User::create([
                'user_type' => $request->user_type,
                'name' => $request->vendor_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'username' => $request->vendor_name . '_' . $request->last_name,
                'country_id' => $request->country ?? null,
                'city_id' => $request->city ?? null,
                'state_id' => '',
                'password' => Hash::make($request->password),
                'date_of_birth' => null,
                'phone_number' => $request->phone_number,
                'office_address' => "",
                'zip_code' => '',
                'gender' => null,
                'terms_and_cond' => $request->terms_and_cond ?? false,
                'timeZone' => $timeZone,
            ]);

            if (!$user) {
                throw new \Exception('Failed to create user record');
            }

            $imagePath = null;
            $checkImagePath = null;

            if ($request->hasFile('image')) {
                try {
                    $file = $request->file('image');
                    $imageName = Storage::disk('s3')->put('vendors', $file);
                    $imagePath = $imageName;
                } catch (\Exception $e) {
                    Log::error('Image upload failed: ' . $e->getMessage());
                }
            }

            if ($request->hasFile('check_image')) {
                try {
                    $file = $request->file('check_image');
                    $imageName = Storage::disk('s3')->put('vendors_check', $file);
                    $checkImagePath = $imageName;
                } catch (\Exception $e) {
                    Log::error('Image upload failed: ' . $e->getMessage());
                }
            }

            $x = rand(10e12, 10e16);
            $otp = rand(100000, 999999);
            $hash_to_verify = base_convert($x, 10, 36);
            $data1 = [
                'verification_hash_code' => $hash_to_verify,
                'user_id' => $user->id,
                'otp' => $otp,
            ];

            DB::table('users_email_verification')->insert($data1);

            $vendorAccount = VendorAccount::create([
                'number' => $request->vendor_number,
                'name' => $request->name,
                'address' => $request->address,
                'vendor' => $request->vendor,
                'vendor_number' => $request->phone_number,
                'image' => $imagePath,
                'user_id' => $user->id,
                'checkbook_image' => $checkImagePath,
                'location_id' => $request->location_id ?? null,
            ]);

            if (!$vendorAccount) {
                throw new \Exception('Failed to create vendor account');
            }

            DB::commit();

            try {
                $data1 = [
                    'hash' => $hash_to_verify,
                    'user_id' => $user->id,
                    'to_mail' => $user->email,
                    'otp' => $otp,
                ];
                Mail::to($user->email)->send(new UserVerificationEmail($data1));
            } catch (\Exception $e) {
                Log::error('Email sending failed: ' . $e->getMessage());
            }

            try {
                $whatsapp = new \App\Http\Controllers\WhatsAppController();
                $res = $whatsapp->send_otp_message($request->phone_number, $otp);
                if (!$res) {
                    Log::warning('WhatsApp message might not have been sent');
                }
            } catch (\Exception $e) {
                Log::error('WhatsApp sending failed: ' . $e->getMessage());
            }

            return redirect()->route('all_vendors_page')->with('success', 'Vendor created successfully.');

        } catch (\Illuminate\Validation\ValidationException $e) {

            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Please check the form for errors.');

        } catch (\Illuminate\Database\QueryException $e) {

            DB::rollBack();
            Log::error('Database error: ' . $e->getMessage());


            if ($e->errorInfo[1] == 1062) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'A vendor with this information already exists. Please use different details.');
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'A database error occurred. Please try again later.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating vendor: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while creating the vendor. Please try again later.');
        }
    }

    public function toggle_status(Request $request)
    {
        $vendor = VendorAccount::findOrFail($request->vendor_id);
        $vendor->is_active = !$vendor->is_active;
        $vendor->save();

        return response()->json([
            'success' => true,
            'message' => 'Vendor status updated successfully.',
            'new_status' => $vendor->is_active,
        ]);

    }

    public function edit($id)
    {
        $locations = [
            [
                'id' => '1',
                'name' => 'shah faisal colony'
            ],
            [
                'id' => '2',
                'name' => 'johor'
            ]
        ];
        try {
            $vendor = VendorAccount::findOrFail($id);
            $user = User::findOrFail($vendor->user_id);
            return view('website_pages.vendors.edit_vendor', compact('vendor', 'user', 'locations'));
        } catch (\Exception $e) {
            Log::error('Error fetching vendor for editing: ' . $e->getMessage());
            return redirect()->route('all_vendors_page')
                ->with('error', 'Vendor not found or could not be edited.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Validate the request data
            $rules = [
                'vendor_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email',
                'phone_number' => 'required|string',
                'vendor_number' => 'required|string',
                'name' => 'required|string',
                'vendor' => 'required|in:pharmacy,labs',
                'address' => 'required|string',
                'image' => 'nullable|image|max:2048',
            ];

            // Only validate password if it's provided
            if ($request->filled('password')) {
                $rules['password'] = 'string|min:6';
            }

            $validated = $request->validate($rules);

            $vendor = VendorAccount::findOrFail($id);

            $user = User::findOrFail($vendor->user_id);

            DB::beginTransaction();

            $user->name = $request->vendor_name;
            $user->last_name = $request->last_name;

            if ($user->email !== $request->email) {
                if (User::where('email', $request->email)->where('id', '!=', $user->id)->exists()) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'The email address is already in use by another account.');
                }
                $user->email = $request->email;
            }

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            ;
            $user->phone_number = $request->phone_number;

            if (!$user->save()) {
                throw new \Exception('Failed to update user record');
            }

            // Update vendor account details
            $vendor->number = $request->phone_number;
            $vendor->name = $request->name;
            $vendor->address = $request->address;
            $vendor->vendor = $request->vendor;
            $vendor->vendor_number = $request->vendor_number;
            $vendor->location_id = $request->location_id ?? null;

            // Handle image upload if provided
            if ($request->hasFile('image')) {
                try {
                    $file = $request->file('image');
                    $imageName = Storage::disk('s3')->put('vendors', $file);

                    // Delete old image if exists
                    if ($vendor->image) {
                        try {
                            Storage::disk('s3')->delete($vendor->image);
                        } catch (\Exception $e) {
                            Log::error('Failed to delete old image: ' . $e->getMessage());
                        }
                    }

                    $vendor->image = $imageName;
                } catch (\Exception $e) {
                    Log::error('Image upload failed: ' . $e->getMessage());
                }
            }

            // Handle checkbook image upload if provided
            if ($request->hasFile('check_image')) {
                try {
                    $file = $request->file('check_image');
                    $imageName = Storage::disk('s3')->put('vendors_check', $file);

                    if ($vendor->checkbook_image) {
                        try {
                            Storage::disk('s3')->delete($vendor->checkbook_image);
                        } catch (\Exception $e) {
                            Log::error('Failed to delete old checkbook image: ' . $e->getMessage());
                        }
                    }

                    $vendor->checkbook_image = $imageName;
                } catch (\Exception $e) {
                    Log::error('Checkbook image upload failed: ' . $e->getMessage());
                }
            }

            if (!$vendor->save()) {
                throw new \Exception('Failed to update vendor account');
            }

            DB::commit();

            return redirect()->route('all_vendors_page')
                ->with('success', 'Vendor updated successfully.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Please check the form for errors.');

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            Log::error('Database error during vendor update: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'A database error occurred. Please try again later.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating vendor: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while updating the vendor. Please try again later.');
        }
    }

    public function vendor_dash()
    {
        $user = auth()->user();
        $vendor = VendorAccount::where('user_id', $user->id)->first();
        return view('dashboard_vendor.vendor');
    }

    public function add_product_page()
    {
        $vendor = VendorAccount::where('user_id', auth()->user()->id)->first();
        $products = [];
        if ($vendor->vendor == 'pharmacy') { {
                $products = DB::table('tbl_products')
                    ->select('id', 'name')
                    ->where('mode', 'medicine')
                    ->get();
                return view('dashboard_vendor.add_product', compact('products'));
            }
        } else {
            $products = DB::table('quest_data_test_codes')
                ->select('TEST_CD AS id', 'TEST_NAME AS name')
                ->where('mode', 'lab-test')
                ->orWhere('mode', 'imaging')
                ->get();
            return view('dashboard_vendor.add_product', compact('products'));
        }
    }

    public function store_vendor_product(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'selling_price' => 'required',
        ]);


        $vendorId = auth()->user()->id;
        $vendorAccount = VendorAccount::where('user_id', $vendorId)->first();
        $vendor_type = '';

        if ($vendorAccount->vendor == 'pharmacy') {
            $vendor_type = 'pharmacy';
        } else {
            $vendor_type = 'lab';
        }

        DB::table('vendor_products')->insert([
            'vendor_id' => $vendorAccount->id,
            'product_id' => $request->product_id,
            'available_stock' => $request->available_stock,
            'actual_price' => $request->actual_price,
            'selling_price' => $request->selling_price,
            'SKU' => $request->SKU,
            'discount' => $request->discount_percentage,
            'product_type' => $vendor_type,
        ]);

        return redirect()->back()
            ->with('success', 'Product added successfully.');
    }

    public function vendor_products()
    {
        $vendor = VendorAccount::where('user_id', auth()->user()->id)->first();
        $vendor_type = $vendor->vendor == 'pharmacy' ? 'pharmacy' : 'lab';
        if ($vendor->vendor == 'pharmacy') {
            $products = DB::table('vendor_products')
                ->join('tbl_products', 'vendor_products.product_id', '=', 'tbl_products.id')
                ->select('vendor_products.*', 'tbl_products.name')
                ->where('vendor_products.vendor_id', $vendor->id)
                ->paginate(10);
            return view('dashboard_vendor.vendor_products', compact('products', 'vendor_type'));
        } else {
            $products = DB::table('vendor_products')
                ->join('quest_data_test_codes', 'vendor_products.product_id', '=', 'quest_data_test_codes.TEST_CD')
                ->select('vendor_products.*', 'quest_data_test_codes.TEST_NAME AS name')
                ->where('vendor_products.vendor_id', $vendor->id)
                ->paginate(10);
            return view('dashboard_vendor.vendor_products', compact('products', 'vendor_type'));
        }
    }

    public function toggle_product_status(Request $request)
    {
        $product = DB::table('vendor_products')->where('id', $request->product_id)->first();
        if ($product) {
            DB::table('vendor_products')->where('id', $request->product_id)->update(['is_active' => !$product->is_active]);
            return response()->json([
                'success' => true,
                'message' => 'Product status updated successfully.',
                'new_status' => !$product->is_active,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.',
            ]);
        }
    }

    public function edit_product($id)
    {
        $vendorId = auth()->user()->id;
        $vendorProduct = DB::table('vendor_products')
            ->where('id', $id)
            ->first();

        if (!$vendorProduct) {
            return redirect()->route('vendor_products')
                ->with('error', 'Product not found or you do not have permission to edit this product.');
        }

        if ($vendorProduct->product_type == 'pharmacy') {
            $product = DB::table('tbl_products')
                ->where('id', $vendorProduct->product_id)
                ->first();
            $productName = $product->name;
        } else {
            $product = DB::table('quest_data_test_codes')
                ->where('TEST_CD', $vendorProduct->product_id)
                ->first();
            $productName = $product->TEST_NAME;
        }


        return view('dashboard_vendor.edit_product', compact('vendorProduct', 'productName'));
    }

    public function update_vendor_product(Request $request, $id)
    {
        $request->validate([
            'selling_price' => 'required|min:0',
        ]);

        $vendorProduct = DB::table('vendor_products')
            ->where('id', $id)
            ->first();

        if (!$vendorProduct) {
            return redirect()->route('vendor_products')
                ->with('error', 'Product not found or you do not have permission to update this product.');
        }

        DB::table('vendor_products')
            ->where('id', $id)
            ->update([
                'actual_price' => $request->actual_price,
                'selling_price' => $request->selling_price,
                'discount' => $request->discount_percentage,
                'available_stock' => $request->available_stock,
                'SKU' => $request->SKU,
                'is_active' => $request->is_active,
                'updated_at' => now(),
            ]);

        return redirect()->route('vendor_products')
            ->with('success', 'Product updated successfully.');
    }


    public function upload_page()
    {
        return view('dashboard_vendor.upload_products');
    }

public function processBulkUpload(Request $request)
{
    $request->validate([
        'excel_file' => 'required|file|max:30048',
    ]);

    $file = $request->file('excel_file');
    $extension = $file->getClientOriginalExtension();
    
    if (!in_array(strtolower($extension), ['xlsx', 'xls', 'csv'])) {
        return redirect()->back()->withErrors(['excel_file' => 'The excel file must be a file of type: xlsx, xls, csv.']);
    }

    $vendorId = auth()->user()->id;
    $vendorAccount = VendorAccount::where('user_id', $vendorId)->first();

    $vendor_type = $vendorAccount->vendor == 'pharmacy' ? 'pharmacy' : 'lab';

    try {
        $spreadsheet = IOFactory::load($file->getPathname());
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        $header = array_shift($rows);

        $added = 0;
        $updated = 0;
        $errors = 0;
        $errorMessages = [];

        foreach ($rows as $index => $row) {

            if (empty(array_filter($row))) {
                continue;
            }

            $productData = [
                'product_id' => $row[0],
                'available_stock' => $row[2],
                'actual_price' => $row[3],
                'selling_price' => $row[4],
                'SKU' => $row[5],
                'discount' => $row[6],
                'is_active' => $row[7] ?? 0,
            ];

            $validator = Validator::make($productData, [
                'product_id' => 'required',
                'selling_price' => 'required',
            ]);

            if ($validator->fails()) {
                $errors++;
                $errorMessages[] = "Row " . ($index + 2) . ": " . implode(', ', $validator->errors()->all());
                continue;
            }

            try {
                $existingProduct = DB::table('vendor_products')
                    ->where('vendor_id', $vendorAccount->id)
                    ->where('SKU', $productData['SKU'])
                    ->where('product_id', $productData['product_id'])
                    ->first();

                if ($existingProduct) {
                    DB::table('vendor_products')
                        ->where('vendor_id', $vendorAccount->id)
                        ->where('SKU', $productData['SKU'])
                        ->where('product_id', $productData['product_id'])
                        ->update([
                            'product_id' => $productData['product_id'],
                            'available_stock' => $productData['available_stock'],
                            'actual_price' => $productData['actual_price'],
                            'selling_price' => $productData['selling_price'],
                            'discount' => $productData['discount'],
                            'is_active' => $productData['is_active'],
                            'updated_at' => now()
                        ]);
                    $updated++;
                } else {
                    DB::table('vendor_products')->insert([
                        'vendor_id' => $vendorAccount->id,
                        'product_id' => $productData['product_id'],
                        'available_stock' => $productData['available_stock'],
                        'actual_price' => $productData['actual_price'],
                        'selling_price' => $productData['selling_price'],
                        'SKU' => $productData['SKU'],
                        'discount' => $productData['discount'],
                        'is_active' => $productData['is_active'],
                        'product_type' => $vendor_type,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    $added++;
                }
            } catch (\Exception $e) {
                $errors++;
                $errorMessages[] = "Row " . ($index + 2) . ": " . $e->getMessage();
            }
        }

        $successMessage = "Products processed successfully. Added: $added, Updated: $updated";
        if ($errors > 0) {
            $successMessage .= ", Errors: $errors";
        }

        return redirect()->route('upload_page')->with('success', $successMessage);
    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['file_error' => 'Error processing file: ' . $e->getMessage()]);
    }
}

    public function getVendorProducts()
    {
        $vendor = VendorAccount::where('user_id', auth()->user()->id)->first();
        $products = [];

        if ($vendor->vendor == 'pharmacy') {
            $products = DB::table('tbl_products')
                ->select('id', 'name', 'sele_price')
                ->where('mode', 'medicine')
                ->get();
        } else {
            $products = DB::table('quest_data_test_codes')
                ->select(
                    'TEST_CD AS id',
                    'TEST_NAME AS name',
                    'PRICE AS price',
                    'SALE_PRICE AS selling_price',
                    'discount_percentage AS discount'
                )
                ->where(function ($query) {
                    $query->where('mode', 'lab-test')
                        ->orWhere('mode', 'imaging');
                })
                ->get();
        }

        return $products;
    }

    public function downloadTemplate()
    {
        $products = $this->getVendorProducts();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $sheet->setCellValue('A1', 'Product ID');
        $sheet->setCellValue('B1', 'Product Name');
        $sheet->setCellValue('C1', 'Available Stock');
        $sheet->setCellValue('D1', 'Actual Price');
        $sheet->setCellValue('E1', 'Selling Price');
        $sheet->setCellValue('F1', 'SKU');
        $sheet->setCellValue('G1', 'Discount (%)');
        $sheet->setCellValue('H1', 'Is Active (1/0)');

        // Make headers bold
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);

        // Fill product data
        $row = 2;
        foreach ($products as $product) {
            $sheet->setCellValue('A' . $row, $product->id);
            $sheet->setCellValue('B' . $row, $product->name);
            $sheet->setCellValue('C' . $row, $product->stock ?? 0);
            $sheet->setCellValue('D' . $row, $product->price ?? 0);
            $sheet->setCellValue('E' . $row, $product->selling_price ?? 0);
            $sheet->setCellValue('F' . $row, $product->sku ?? '');
            $sheet->setCellValue('G' . $row, $product->discount ?? 0);
            $sheet->setCellValue('H' . $row, $product->is_active ?? 1);

            $row++;
        }

        $protection = $sheet->getProtection();
        $protection->setSheet(true);
        $protection->setPassword('vendorTemplate123');


        foreach (range('A', 'H') as $col) {
            if (in_array($col, ['A', 'B'])) {
                $sheet->getStyle($col . '2:' . $col . $row)->getProtection()
                    ->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_PROTECTED);
            } else {
                $sheet->getStyle($col . '2:' . $col . $row)->getProtection()
                    ->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);
            }
        }

        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'vendor_products_template.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($tempFile);

        return response()->download($tempFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

}
