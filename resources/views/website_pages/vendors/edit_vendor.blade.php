@extends('layouts.dashboard_admin')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
    <style>
        .required-field::after {
            content: " *";
            color: red;
        }

        .form-section {
            box-shadow: rgba(17, 17, 26, 0.05) 0px 1px 0px, rgba(17, 17, 26, 0.1) 0px 0px 8px;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .form-section-title {
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .vendor-logo-preview {
            max-width: 100px;
            max-height: 100px;
            margin-top: 10px;
            border-radius: 5px;
        }
    </style>
@endsection

@section('page_title')
    <title>CHCC - Edit Vendor</title>
@endsection

@section('top_import_file')
@endsection

@section('content')
    <div class="dashboard-content">
        <div class="container-fluid">
            <div class="row m-auto">
                <div class="col-md-12">
                    <div class="row m-auto">
                        <div class="d-flex align-items-baseline justify-content-between flex-wrap p-0">
                            <div>
                                <h3>Edit Vendor</h3>
                            </div>
                        </div>
                        <div class="wallet-table" style="border-radius: 18px;">
                            <form action="{{ route('update_vendor', $vendor->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="p-3">
                                    <!-- Personal Information Section -->
                                    <div class="form-section">
                                        <h5 class="form-section-title">Personal Information</h5>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="fw-bolder mb-2 required-field" for="vendor_name">First
                                                    Name</label>
                                                <input type="text" name="vendor_name" id="vendor_name" class="form-control"
                                                    required placeholder="Enter First Name..." value="{{ $user->name }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="fw-bolder mb-2 required-field" for="last_name">Last
                                                    Name</label>
                                                <input type="text" name="last_name" id="last_name" class="form-control"
                                                    required placeholder="Enter Last Name..." value="{{ $user->last_name }}">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <label class="fw-bolder mb-2 required-field" for="email">Email</label>
                                                <input type="email" name="email" id="email" class="form-control" required
                                                    placeholder="Enter Email Address..." value="{{ $user->email }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="fw-bolder mb-2 required-field" for="phone_number">Phone
                                                    Number</label>
                                                <input type="text" name="phone_number" id="phone_number"
                                                    class="form-control" required placeholder="Enter Phone Number..."
                                                    pattern="\d{11}" title="Phone number must be 11 digits" value="{{ $user->phone_number }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="fw-bolder mb-2" for="password">Password (Leave blank to keep current)</label>
                                                <input type="password" name="password" id="password" class="form-control"
                                                    placeholder="Enter New Password...">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Vendor Details Section -->
                                    <div class="form-section">
                                        <h5 class="form-section-title">Vendor Details</h5>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="fw-bolder mb-2 required-field" for="vendor_number">Vendor Account
                                                    Number</label>
                                                <input type="text" name="vendor_number" id="vendor_number"
                                                    class="form-control" required placeholder="Enter Vendor Account Number..." value="{{ $vendor->vendor_number }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="fw-bolder mb-2 required-field"
                                                    for="vendor_account_name">Vendor Name</label>
                                                <input type="text" name="name" id="vendor_account_name" class="form-control"
                                                    required placeholder="Enter Vendor Name..." value="{{ $vendor->name }}">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="fw-bolder mb-2 required-field" for="vendor">Vendor
                                                    Type</label>
                                                <select name="vendor" id="vendor" class="form-control" required>
                                                    <option value="" disabled>Select Vendor Type</option>
                                                    <option value="pharmacy" {{ $vendor->vendor == 'pharmacy' ? 'selected' : '' }}>Pharmacy</option>
                                                    <option value="labs" {{ $vendor->vendor == 'labs' ? 'selected' : '' }}>Labs</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="fw-bolder mb-2" for="vendor_logo">Vendor Logo</label>
                                                <input type="file" name="image" id="vendor_logo" class="form-control"
                                                    accept="image/*">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="fw-bolder mb-2 required-field"
                                                    for="location_id">Location</label>
                                                <select name="location_id" id="location_id" class="form-control" required>
                                                    <option value="" disabled>Select Location</option>
                                                    @foreach($locations ?? [] as $location)
                                                        <option value="{{ $location['id'] }}">{{ $location['name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="fw-bolder mb-2" for="check_image">Checkbook Image</label>
                                                <input type="file" name="check_image" id="check_image" class="form-control"
                                                    accept="image/*">
                                                @if($vendor->check_image)
                                                    <div class="mt-2">
                                                        <img src="{{ Storage::disk('s3')->url($vendor->check_image) }}" alt="Current Checkbook Image" class="vendor-logo-preview">
                                                        <p class="small text-muted mt-1">Current checkbook image</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <label class="fw-bolder mb-2 required-field" for="address">Vendor
                                                    Address</label>
                                                <textarea name="address" id="address" class="form-control" rows="3" required
                                                    placeholder="Enter Office Address...">{{ $vendor->address }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Hidden field for user_id -->
                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                    <input type="hidden" name="user_type" value="vendor">

                                    @if(session('error'))
                                        <div class="alert alert-danger">
                                            {{ session('error') }}
                                        </div>
                                    @endif

                                    @if($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <div class="row mt-3">
                                        <div class="text-end">
                                            <a href="{{ route('all_vendors_page') }}" class="btn btn-secondary me-2">Cancel</a>
                                            <button type="submit" class="btn process-pay">Update Vendor</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('bottom_import_file')
    <script>
        document.getElementById('phone_number').addEventListener('input', function (e) {
            if (this.value.length > 11) {
                this.value = this.value.slice(0, 11);
            }
        });
    </script>
@endsection