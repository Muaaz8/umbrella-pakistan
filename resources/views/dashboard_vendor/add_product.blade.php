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
    <script>
        document.getElementById('actual_price').addEventListener('input', function () {
            calculateDiscount();
        });

        document.getElementById('selling_price').addEventListener('input', function () {
            calculateDiscount();
        });
        
        document.getElementById('discount_percentage').addEventListener('input', function () {
            calculateDiscountedPrice();
        });

        function calculateDiscount() {
            const actualPrice = parseFloat(document.getElementById('actual_price').value) || 0;
            const sellingPrice = parseFloat(document.getElementById('selling_price').value) || 0;

            if (actualPrice > 0 && sellingPrice > 0 && actualPrice >= sellingPrice) {
                const discountAmount = actualPrice - sellingPrice;
                const discountPercentage = (discountAmount / actualPrice) * 100;
                document.getElementById('discount').value = discountPercentage.toFixed(2);
            } else {
                document.getElementById('discount').value = '';
            }
        }
        
        function calculateDiscountedPrice() {
            const sellingPrice = parseFloat(document.getElementById('selling_price').value) || 0;
            const discountPercentage = parseFloat(document.getElementById('discount_percentage').value) || 0;
            
            if (sellingPrice > 0 && discountPercentage >= 0 && discountPercentage <= 100) {
                const discountAmount = (sellingPrice * discountPercentage) / 100;
                const discountedPrice = sellingPrice - discountAmount;
                document.getElementById('discounted_price').value = discountedPrice.toFixed(2);
            } else {
                document.getElementById('discounted_price').value = '';
            }
        }
    </script>
@endsection

@section('content')
    <div class="dashboard-content">
        <div class="container-fluid">
            <div class="row m-auto">
                <div class="col-md-12">
                    <div class="row m-auto">
                        <div class="d-flex align-items-baseline justify-content-between flex-wrap p-0">
                            <div>
                                <h3>Add Vendor Product</h3>
                            </div>
                        </div>
                        <div class="wallet-table" style="border-radius: 18px;">
                            <form action="{{ route('store_vendor_product') }}" method="POST">
                                @csrf
                                <div class="p-3">
                                    <!-- Product Information Section -->
                                    <div class="form-section">
                                        <h5 class="form-section-title">Product Information</h5>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="fw-bolder mb-2 required-field" for="product_id">Select
                                                    Product</label>
                                                <select id="product_id"
                                                    class="form-control"
                                                    name="product_id" required>
                                                    <option value="" selected disabled>-- Select Product --</option>
                                                    @foreach($products as $product)
                                                        <option value="{{ $product->id }}">{{ \Illuminate\Support\Str::limit($product->name, 30) }}</option>
                                                    @endforeach
                                                </select>
                                                @error('product_id')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label class="fw-bolder mb-2 required-field" for="available_stock">Available
                                                    Stock</label>
                                                <input id="available_stock" type="number" min="0"
                                                    class="form-control @error('available_stock') is-invalid @enderror"
                                                    name="available_stock" value="{{ old('available_stock', 0) }}" required
                                                    placeholder="Enter Stock Quantity">
                                                @error('available_stock')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="fw-bolder mb-2 required-field" for="actual_price">Actual
                                                    Price</label>
                                                <input id="actual_price" type="number" step="0.01" min="0"
                                                    class="form-control @error('actual_price') is-invalid @enderror"
                                                    name="actual_price" value="{{ old('actual_price') }}" required
                                                    placeholder="Enter Actual Price">
                                                @error('actual_price')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label class="fw-bolder mb-2 required-field" for="selling_price">Selling
                                                    Price</label>
                                                <input id="selling_price" type="number" step="0.01" min="0"
                                                    class="form-control @error('selling_price') is-invalid @enderror"
                                                    name="selling_price" value="{{ old('selling_price') }}" required
                                                    placeholder="Enter Selling Price">
                                                @error('selling_price')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="fw-bolder mb-2" for="discount_percentage">Discount Percentage (%)</label>
                                                <input id="discount_percentage" type="number" step="0.01" min="0" max="100"
                                                    class="form-control @error('discount_percentage') is-invalid @enderror"
                                                    name="discount_percentage" value="{{ old('discount_percentage') }}"
                                                    placeholder="Enter Discount Percentage">
                                                @error('discount_percentage')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label class="fw-bolder mb-2" for="discounted_price">Discounted Price</label>
                                                <input id="discounted_price" type="number" step="0.01" min="0"
                                                    class="form-control @error('discounted_price') is-invalid @enderror"
                                                    name="discounted_price" value="{{ old('discounted_price') }}"
                                                    placeholder="Automatically Calculated" readonly>
                                                @error('discounted_price')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    @if (session('success'))
                                        <div class="alert alert-success" role="alert">
                                            {{ session('success') }}
                                        </div>
                                    @endif

                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
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