@extends('layouts.dashboard_Lab_admin')
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
                            @if ($lab_order[0]->order_status == 'pending')
                                <li class="list-group-item d-flex justify-content-between">Payment : <label
                                        class="order-progress">{{ $lab_order[0]->pay_status }}</label></li>
                            @else
                                <li class="list-group-item d-flex justify-content-between">Payment : <label
                                        class="order-paid">{{ $lab_order[0]->pay_status }}</label></li>
                            @endif
                            @if ($lab_order[0]->status == 'pending')
                                <li class="list-group-item d-flex justify-content-between px-2">Status : <label
                                        class="order-progress">{{ $lab_order[0]->status }}</label></li>
                            @else
                                <li class="list-group-item d-flex justify-content-between px-2">Status : <label
                                        class="order-paid">{{ $lab_order[0]->status }}</label></li>
                            @endif
                            {{-- <li class="list-group-item d-flex justify-content-between"> Status :  <label class="order-">In progress</label></li> --}}
                        </ul>
                    </div>
                </div>

            </div>
            <div class="row m-auto">
                <div class="col-12">
                    <div class="card mt-3">
                        <h5 class="card-header">Lab Order</h5>
                        @foreach ($lab_order as $order)
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card" style="width: 100%">
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item"><b>Tracking ID : </b> {{ $order->order_id }}
                                                </li>
                                                <li class="list-group-item"><b>Service Name :</b> {{ $order->name }} </li>
                                                <li class="list-group-item"><b>Name :</b>
                                                    {{ $order->first_name . ' ' . $order->last_name }}</li>
                                                <li class="list-group-item"><b>Product Price :</b>Rs. {{ $order->total }}</li>
                                                {{-- <li class="list-group-item"><b>Username :</b> {{ $order->name }}</li> --}}
                                                {{-- <li class="list-group-item"><b>Order State :</b> {{ $order->order_state }} --}}
                                                </li>
                                                <li class="list-group-item"><b>Payment Title :</b>
                                                    {{ $order->payment_title }}</li>
                                                <li class="list-group-item"><b>Payment Method :</b>
                                                    {{ $order->payment_method }}</li>
                                                <li class="list-group-item"><b>Currency :</b> {{ $order->currency }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
