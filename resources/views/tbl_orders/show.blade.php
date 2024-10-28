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
                <h4>Payment: <strong>Paid</strong>  </h4>
                <h4>Status: <strong>In Process</strong> </h4>
                <div class="card">
                    <div class="row" style="padding-left: 20px">
                    @include('tbl_orders.show_fields')
                        
                    </div>
                </div>
            </div>
        </div>
</section>


@endsection
