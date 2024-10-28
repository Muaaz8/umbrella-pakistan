@extends('layouts.admin')
<link rel="stylesheet" href="{{ asset('asset_admin/css/table.css') }}">

@section('content')
<section class="content ovrflw">
    <div class="container-fluid">
        <div class="block-header">
            <h2>All States</h2>
            <ul class="breadcrumb mb-0 pb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0);">States</a></li>
            </ul>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <table
                    class="table table-bordered table-responsive table-striped table-hover js-basic-example dataTable">
                    <thead>
                        <th>Item Name</th>
                        <th>Item Price</th>
                        <th>Quantity</th>
                        <th>Order Date</th>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                        <tr>
                            <td>{{ $item['product_name']  }}</td>
                            <td>$ {{ number_format($item['price'],2)  }}</td>
                            <td>{{ $item['quantity']  }}</td>
                            <td>{{ $item['created_at']  }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</section>
@endsection
