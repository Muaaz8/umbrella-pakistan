<?php

namespace App\Http\Controllers;

use App\Mail\UserVerificationEmail;
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

class VendorsController extends Controller
{
    public function index()
    {
        $vendors = DB::table('vendor_accounts')->paginate(12);
        foreach ($vendors as $key => $vendor) {
            $vendor->image = \App\Helper::check_bucket_files_url($vendor->image);
        }
        return view('website_pages.vendors.index', compact('vendors'));
    }

    public function create_vendor_page()
    {
        return view('website_pages.vendors.create');
    }

public function showVendors(Request $request)
{
    $search = $request->input('search', '');

    $query = DB::table('vendor_accounts')
        ->join('users', 'vendor_accounts.user_id', '=', 'users.id')
        ->select('vendor_accounts.*', 'users.name as user_name', 'users.last_name as user_last_name');

    if ($search) {
        $query->where(function($q) use ($search) {
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
                'date_of_birth' => 'required|date',
                'gender' => 'required|in:male,female,other',
                'vendor_number' => 'required|string',
                'name' => 'required|string',
                'vendor' => 'required|in:pharmacy,labs',
                'address' => 'required|string',
                'user_type' => 'required|in:vendor',
                'image' => 'nullable|image|max:2048',
            ]);

            DB::beginTransaction();

            $newd_o_b = date('Y-m-d', strtotime($request->date_of_birth));
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
                'date_of_birth' => $newd_o_b,
                'phone_number' => $request->phone_number,
                'office_address' => "",
                'zip_code' => '',
                'gender' => $request->gender,
                'terms_and_cond' => $request->terms_and_cond ?? false,
                'timeZone' => $timeZone,
            ]);

            if (!$user) {
                throw new \Exception('Failed to create user record');
            }

            $imagePath = null;

            if ($request->hasFile('image')) {
                try {
                    $file = $request->file('image');
                    $imageName = Storage::disk('s3')->put('vendors', $file);
                    $imagePath = $imageName;
                } catch (\Exception $e) {
                    Log::error('Image upload failed: ' . $e->getMessage());
                }
            }

            $x = rand(10e12, 10e16);
            $otp = rand(100000, 999999);
            $hash_to_verify = base_convert($x, 10, 36);
            $data1 = [
                'hash' => $hash_to_verify,
                'user_id' => $user->id,
                'to_mail' => $user->email,
                'otp' => $otp,
            ];

            $vendorAccount = VendorAccount::create([
                'number' => $request->phone_number,
                'name' => $request->name,
                'address' => $request->address,
                'vendor' => $request->vendor,
                'vendor_number' => $request->vendor_number,
                'image' => $imagePath,
                'user_id' => $user->id,
            ]);

            if (!$vendorAccount) {
                throw new \Exception('Failed to create vendor account');
            }

            DB::commit();

            try {
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
    try {
        $vendor = VendorAccount::findOrFail($id);
        $user = User::findOrFail($vendor->user_id);
        return view('website_pages.vendors.edit_vendor', compact('vendor', 'user'));
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
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
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

        // Find the vendor account
        $vendor = VendorAccount::findOrFail($id);

        // Find the associated user
        $user = User::findOrFail($vendor->user_id);

        DB::beginTransaction();

        // Update user details
        $user->name = $request->vendor_name;
        $user->last_name = $request->last_name;

        // Check if email has changed
        if ($user->email !== $request->email) {
            // Verify if the new email is available
            if (User::where('email', $request->email)->where('id', '!=', $user->id)->exists()) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'The email address is already in use by another account.');
            }
            $user->email = $request->email;
        }

        // Update password if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->date_of_birth = date('Y-m-d', strtotime($request->date_of_birth));
        $user->phone_number = $request->phone_number;
        $user->gender = $request->gender;

        if (!$user->save()) {
            throw new \Exception('Failed to update user record');
        }

        // Update vendor account details
        $vendor->number = $request->phone_number;
        $vendor->name = $request->name;
        $vendor->address = $request->address;
        $vendor->vendor = $request->vendor;
        $vendor->vendor_number = $request->vendor_number;

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
}
