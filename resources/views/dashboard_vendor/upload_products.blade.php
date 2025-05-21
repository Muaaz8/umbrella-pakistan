@extends('layouts.dashboard_vendor')

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
            padding-bottom: 10px;
            margin-bottom: 20px;
            font-weight: 600;
        }
    </style>
@endsection

@section('page_title')
    <title>CHCC - Add Vendor Product</title>
@endsection

@section('top_import_file')
@endsection

@section('bottom_import_file')
@endsection

@section('content')
<div class="dashboard-content">
    <div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Bulk Upload Products</h4>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="card-title">How to use Bulk Upload</h5>
                                    <ol>
                                        <li>Download the template Excel file using the button below.</li>
                                        <li>Fill in the product details in the spreadsheet:
                                            <ul>
                                                <li><strong>Product ID:</strong> The ID of the product in the system (required)</li>
                                                <li><strong>Available Stock:</strong> Number of items in stock</li>
                                                <li><strong>Actual Price:</strong> Original product price</li>
                                                <li><strong>Selling Price:</strong> Your selling price (required)</li>
                                                <li><strong>SKU:</strong> Stock Keeping Unit - unique identifier (required)</li>
                                                <li><strong>Discount (%):</strong> Discount percentage if applicable</li>
                                            </ul>
                                        </li>
                                        <li>Save the Excel file.</li>
                                        <li>Upload the file using the form below.</li>
                                        <li><strong>Note:</strong> Products with existing SKUs will be updated automatically.</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Download Template</h5>
                                </div>
                                <div class="card-body text-center">
                                    <p>Download the Excel template to get started.</p>
                                    <a href="{{ route('template') }}" class="btn btn-primary">
                                        <i class="fa fa-download"></i> Download Template
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Upload Products</h5>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('product_process') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <label for="excel_file">Select Excel File</label>
                                            <input type="file" class="form-control-file" id="excel_file" name="excel_file" required>
                                            @error('excel_file')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <button type="submit" class="btn btn-success mt-3">
                                            <i class="fa fa-upload"></i> Upload & Process
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection