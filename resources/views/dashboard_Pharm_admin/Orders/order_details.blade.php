@extends('layouts.dashboard_Pharm_admin')
@section('meta_tags')
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
@endsection

@section('page_title')
    <title>Order Details</title>
@endsection

@section('top_import_file')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="./assets/js/custom.js"></script>
@endsection

@section('bottom_import_file')
@endsection
@section('content')
    <div class="dashboard-content">
        <div class="container-fluid">
            <div class="row m-auto">

                <div class="col-md-6">
                    <div class="card" style="width: 100%">
                        <ul class="list-group list-group-flush">
                            @if ($img_order->order_status == 'pending')
                                <li class="list-group-item d-flex justify-content-between">Payment : <label
                                        class="order-progress">{{ $img_order->pay_status }}</label></li>
                            @else
                                <li class="list-group-item d-flex justify-content-between">Payment : <label
                                        class="order-paid">{{ $img_order->pay_status }}</label></li>
                            @endif
                            @if ($img_order->status == 'pending')
                                <li class="list-group-item d-flex justify-content-between">Status : <label
                                        class="order-progress">{{ $img_order->status }}</label></li>
                            @else
                                <li class="list-group-item d-flex justify-content-between">Status : <label
                                        class="order-paid">{{ $img_order->status }}</label></li>
                            @endif
                        </ul>
                    </div>
                </div>

            </div>
            <div class="row m-auto">
                <div class="col-12">
                    <div class="card mt-3">
                        <h5 class="card-header">Pharmacy Order</h5>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card" style="width: 100%">
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item"><b>Tracking ID : </b> {{ $img_order->order_main_id }}
                                                </li>
                                                <li class="list-group-item"><b>Service Name :</b> {{ $img_order->name }} </li>
                                                <li class="list-group-item"><b>Name :</b>
                                                    {{ $img_order->first_name . ' ' . $img_order->last_name }}</li>
                                                <li class="list-group-item"><b>Product Price :</b> Rs. {{ $img_order->update_price }}</li>
                                                {{-- <li class="list-group-item"><b>Username :</b> {{ $order->name }}</li> --}}
                                                <li class="list-group-item"><b>Order State :</b> {{ $img_order->order_state }}
                                                </li>
                                                <li class="list-group-item"><b>Payment Title :</b>
                                                    {{ $img_order->payment_title }}</li>
                                                <li class="list-group-item"><b>Payment Method :</b>
                                                    {{ $img_order->payment_method }}</li>
                                                <li class="list-group-item"><b>Currency :</b> {{ $img_order->currency }}</li>
                                                <li class="list-group-item"><b>E-Prescription :</b><a class="btn process-pay m-3" href="{{ $file->filename }}" target="_blank"> View </a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                </div>
                            </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
