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
    <title>CHCC - Request New Product</title>
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
                            <h4>Request New Product</h4>
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
                                            @if ($vendor_type === 'pharmacy')
                                                <p>Please use the form below to request the addition of a new medicine to the
                                                    inventory.</p>
                                                <ul>
                                                    <li><strong>Important:</strong> Do not request medicines known to cause
                                                        severe side effects like suicidal thoughts or life-threatening
                                                        conditions.</li>
                                                    <li>Ensure that the requested medicine is approved and safe for general use.
                                                    </li>
                                                    <li>Our team will review your request within <strong>2 working days</strong>
                                                        and notify you upon approval or rejection.</li>
                                                </ul>
                                            @elseif ($vendor_type === 'lab')
                                                <p>Please use the form below to request the addition of a new lab test to the
                                                    system.</p>
                                                <ul>
                                                    <li><strong>Important:</strong> Ensure the test complies with national
                                                        healthcare guidelines.</li>
                                                    <li>Do not request tests that are not approved or could mislead patients.
                                                    </li>
                                                    <li>Our team will evaluate your request within <strong>2 working
                                                            days</strong> and notify you accordingly.</li>
                                                </ul>
                                            @else
                                                <p class="text-danger">Invalid vendor type specified.</p>
                                            @endif

                                            <form action="/submit-request" method="POST" class="mt-4">
                                                @csrf
                                                <div class="form-group">
                                                    <label for="productName">
                                                        {{ $vendor_type === 'pharmacy' ? 'Medicine Name' : 'Lab Test Name' }}
                                                    </label>
                                                    <input type="text" class="form-control" id="productName"
                                                        name="product_name" placeholder="{{ $vendor_type === 'pharmacy' ? 'Enter Medicine / product Name with mg ' : 'Enter Imaging/Lab Test Name' }}" required>
                                                </div>
                                                <input type="hidden" name="vendor_type" value="{{ $vendor_type }}">
                                                <button type="submit" class="btn btn-primary mt-3">Submit Request</button>
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