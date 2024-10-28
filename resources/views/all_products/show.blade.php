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
                    {{-- <div class="header">
                        <h2>Products<small>All recent products</small> </h2>
                    </div> --}}
                    <div class="row product_show" style="padding-left: 20px">
                        @include('all_products.show_fields')
                    </div>
                    <div class="row d-flex justify-content-center" style="padding-left: 20px">
                        <a href="{{ route('allProducts.index') }}" class="btn callbtn">Back</a>
                    </div>
                    </div>
                </div>
            </div>

        </div>
</section>
<style>
.product_show h4{
    margin-top:16px !important;
    font-size:20px !important;
    font-weight:700 !important;
}
.product_show p{
    font-size:14px !important;
    color:grey !important;
    font-weight:300 !important;
}

</style>

@endsection

@section('script')
<script src="asset_admin/js/pages/index.js"></script>
<script src="asset_admin/js/pages/charts/sparkline.min.js"></script>
@endsection

