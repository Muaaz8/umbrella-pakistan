
@extends('layouts.admin')
<link rel="stylesheet" href="{{ asset('asset_admin/css/allproduct.css')}}">

@section('content')

    <section class="content home">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Dashboard</h2>
                <small class="text-muted">Welcome to Umbrellamd</small>
            </div>

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="header">
                            <h2>Product Categories<small>All recent categories</small> </h2>
                        </div>
                        <div class="row" style="padding-left: 20px">
                            @include('product_categories.show_fields')
                        </div>
                        <div class="row clearfix" style="padding-left: 20px">
                           <div class="col-md-3">
                            <a href="{{ route('productCategories.index') }}" class="btn close-btn">Back</a>
                           </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

@endsection

@section('script')
    <script src="asset_admin/js/pages/index.js"></script>
    <script src="asset_admin/js/pages/charts/sparkline.min.js"></script>
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.21/b-1.6.3/b-flash-1.6.3/b-html5-1.6.3/b-print-1.6.3/datatables.min.css" />
@endsection
