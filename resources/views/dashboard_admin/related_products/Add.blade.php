@extends('layouts.dashboard_admin')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>CHCC - Admin Dashboard</title>
@endsection

@section('top_import_file')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"
        integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection


@section('bottom_import_file')
    <script>
        $(".js-select2").select2({
            closeOnSelect: false,
            placeholder: "Click to View Options",
            allowHtml: true,
            allowClear: true,
            tags: true,
        });
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
                                <h3>Add Related Products</h3>
                            </div>
                        </div>
                        <div class="wallet-table " style="border-radius: 18px;">
                            <form action="{{ route('related_products.store') }}" method="POST">
                                @csrf
                                <div class="p-3">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="fw-bolder mb-2" for="selectmedicine">Product</label>
                                            <select id="cat_select" class="form-control " name="product_id" required>
                                                <option value="">Select Product</option>
                                                @foreach ($labs as $item)
                                                    <option value="{{ $item->TEST_CD }}">{{ $item->TEST_NAME }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                        <div class="col-md-6 sub_cat">
                                            <label class="fw-bolder mb-2" for="selectmedicine">Related Products</label>
                                            <select id="sub_category" class="js-select2" name="related_product_ids[]" multiple="multiple" required>
                                                <option value="">Select Related Product</option>
                                                @foreach ($labs as $item)
                                                    <option value="{{ $item->TEST_CD }}">{{ $item->TEST_NAME }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
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
