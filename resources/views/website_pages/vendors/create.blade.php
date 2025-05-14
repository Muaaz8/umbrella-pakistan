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
    </style>
@endsection

@section('page_title')
    <title>CHCC - Add Vendor</title>
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
                                <h3>Add New Vendor</h3>
                            </div>
                        </div>
                        <div class="wallet-table" style="border-radius: 18px;">
                            <form action="{{ route('add_vender') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="p-3">
                                    <!-- Personal Information Section -->
                                    <div class="form-section">
                                        <h5 class="form-section-title">Personal Information</h5>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="fw-bolder mb-2 required-field" for="vendor_name">First
                                                    Name</label>
                                                <input type="text" name="vendor_name" id="vendor_name" class="form-control"
                                                    required placeholder="Enter First Name...">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="fw-bolder mb-2 required-field" for="last_name">Last
                                                    Name</label>
                                                <input type="text" name="last_name" id="last_name" class="form-control"
                                                    required placeholder="Enter Last Name...">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <label class="fw-bolder mb-2 required-field" for="email">Email</label>
                                                <input type="email" name="email" id="email" class="form-control" required
                                                    placeholder="Enter Email Address...">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="fw-bolder mb-2 required-field" for="phone_number">Phone
                                                    Number</label>
                                                <input type="text" name="phone_number" id="phone_number"
                                                    class="form-control" required placeholder="Enter Phone Number..."
                                                    pattern="\d{11}" title="Phone number must be 11 digits">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="fw-bolder mb-2 required-field" for="password">Password</label>
                                                <input type="password" name="password" id="password" class="form-control"
                                                    required placeholder="Enter Password...">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Vendor Details Section -->
                                    <div class="form-section">
                                        <h5 class="form-section-title">Vendor Details</h5>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="fw-bolder mb-2 required-field" for="vendor_number">Vendor
                                                    Account
                                                    Number</label>
                                                <input type="text" name="vendor_number" id="vendor_number"
                                                    class="form-control" required
                                                    placeholder="Enter Vendor Account Number...">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="fw-bolder mb-2 required-field"
                                                    for="vendor_account_name">Vendor Name</label>
                                                <input type="text" name="name" id="vendor_account_name" class="form-control"
                                                    required placeholder="Enter Vendor Name...">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="fw-bolder mb-2 required-field" for="vendor">Vendor
                                                    Type</label>
                                                <select name="vendor" id="vendor" class="form-control" required>
                                                    <option value="" selected disabled>Select Vendor Type</option>
                                                    <option value="pharmacy">Pharmacy</option>
                                                    <option value="labs">Labs</option>
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
                                                <label class="fw-bolder mb-2" for="check_image">Check-book Image</label>
                                                <input type="file" name="check_image" id="check_image" class="form-control"
                                                    accept="image/*">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="fw-bolder mb-2 required-field"
                                                    for="location_id">Location</label>
                                                <select name="location_id" id="location_id" class="form-control" required>
                                                    <option value="" selected disabled>Select Location</option>
                                                    @foreach($locations ?? [] as $location)
                                                        <option value="{{ $location['id'] }}">{{ $location['name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <label class="fw-bolder mb-2 required-field" for="address">Vendor
                                                    Address</label>
                                                <textarea name="address" id="address" class="form-control" rows="3" required
                                                    placeholder="Enter Office Address..."></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Hidden field for user_type -->
                                    <input type="hidden" name="user_type" value="vendor">

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
                                            <button type="submit" class="btn process-pay">Submit</button>
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