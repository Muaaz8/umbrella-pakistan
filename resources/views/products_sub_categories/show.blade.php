
@extends('layouts.admin')

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
                        <h2>Product Sub Categories<small>All recent categories</small> </h2>
                      
                    </div>
                    <div class="row" style="padding-left: 20px">
                        @include('products_sub_categories.show_fields')
                    </div>

                    <div class="row clearfix" style="padding-left: 20px">
                        <div class="col-md-3">
                            <a href="{{ route('productsSubCategories.index') }}" class="btn close-btn">Back</a>
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
@endsection
