

<?php 

//  print_r($productCategory);
//  die;

?>


@extends('layouts.admin')

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
                            {!! Form::model($productCategory, ['route' => ['productCategories.update',
                            $productCategory->id], 'method' => 'patch', 'files' => true]) !!}

                            <div class="row clearfix">
                                @include('product_categories.fields') </div>

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
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.21/b-1.6.3/b-flash-1.6.3/b-html5-1.6.3/b-print-1.6.3/datatables.min.css" />
@endsection
