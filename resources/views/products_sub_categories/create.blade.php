
@extends('layouts.admin')
<link rel="stylesheet" href="{{ asset('asset_admin/css/allproduct.css')}}">

@section('content')

    <section class="content home">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Dashboard</h2>
                <small class="text-muted">Welcome to Umbrellamd</small>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>Product Category<small>Fill the form below</small> </h2>
                        </div>
                        <div class="body">
                            {!! Form::open(['route' => 'productsSubCategories.store', 'files' => true]) !!}

                            @include('products_sub_categories.fields')

                            {!! Form::close() !!}
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
@endsection
