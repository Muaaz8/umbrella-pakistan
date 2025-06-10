@extends('layouts.dashboard_patient')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>CHCC - Patient Orders</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
@endsection

@section('content')
    @php
        $med = '0';
    @endphp
    <div class="dashboard-content">
        <div class="container-fluid">
            <div class="row m-auto">
                <div class="col-md-6">
                    <div class="card" style="width: 100%">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><b>Order ID </b> : {{ $data['order_data']->order_id }}</li>
                            <li class="list-group-item"><b> Date</b> : {{ $data['order_data']->created_at['date'] }}
                            <li class="list-group-item"><b> Time</b> : {{ $data['order_data']->created_at['time'] }}
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card" style="width: 100%">
                        <ul class="list-group list-group-flush">
                            @if ($data['order_data']->order_status == 'paid')
                                <li class="list-group-item d-flex justify-content-between"><b>Payment Status : </b><label
                                        class="order-paid">{{ $data['order_data']->order_status }}</label></li>
                                {{-- <li class="list-group-item d-flex justify-content-between"><b>Order Status : </b><label
                                        class="order-paid">{{ $data['order_data']->status }}</label></li> --}}
                            @else
                                <li class="list-group-item d-flex justify-content-between"><b>Payment Status :</b> <label
                                        class="order-progess">{{ $data['order_data']->order_status }}</label></li>
                                {{-- <li class="list-group-item d-flex justify-content-between"><b>Order Status : </b> <label
                                        class="order-progress">{{ $data['order_data']->status }}</label></li> --}}
                            @endif
                            <li class="list-group-item d-flex justify-content-between"><b>Payment Type
                                    :</b>{{ $data['order_data']->payment_title }} </li>
                        </ul>
                    </div>
                </div>

            </div>
            <div class="row m-auto mt-3">
                <div class="col-md-8">
                    <div class="wallet-table m-0 table-responsive">
                        <table class="table table-bordered checkout-table-das">
                            <thead>
                                <tr>
                                    <!-- <th scope="col"></th> -->
                                    <th scope="col">Product Name</th>
                                    <th scope="col">Dosage and Imaging Location</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $priceTotal = 0;
                                    $itemCount = 0;
                                    $providerfee = 0;
                                @endphp
                                @foreach ($orderMeds as $med)
                                    <tr>
                                        <!-- <th><i class="fa-solid fa fa-flask fs-4"></i></th> -->
                                        <td data-label="Product Name">{{ $med->name }}</td>
                                        <td data-label="Quantity">{{ isset($med->usage) ? $med->usage : "-" }}</td>
                                        <td data-label="Price">Rs. {{ $med->update_price }}</td>
                                        <td data-label="Status">{{ $med->status }}</td>
                                        @php
                                            $priceTotal += $med->update_price;
                                            $itemCount += 1;
                                            $med = '1';
                                        @endphp
                                        {{-- <td data-label=""><a href="{{ url('/viewQuestTestReport/1') }}">View Report</a>
                                        </td> --}}
                                    </tr>
                                @endforeach
                                @foreach ($orderLabs as $labs)
                                    <tr>
                                        <!-- <th><i class="fa-solid fa fa-flask fs-4"></i></th> -->
                                        <td data-label="Product Name">{{ $labs->TEST_NAME }}</td>
                                        <td data-label="Quantity"></td>
                                        <td data-label="Price">Rs. {{ $labs->price }}</td>
                                        <td data-label="Status">{{ $labs->status }}</td>
                                        @php
                                            $priceTotal = $priceTotal + $labs->price;
                                            $itemCount += 1;
                                        @endphp
                                        {{-- <td data-label=""><a href="{{ url('/viewQuestTestReport/1') }}">View Report</a>
                                        </td> --}}
                                    </tr>
                                @endforeach
                                @foreach ($ordercntLabs as $labs)
                                    <tr>
                                        <!-- <th><i class="fa-solid fa fa-flask fs-4"></i></th> -->
                                        <td data-label="Product Name">{{ $labs->TEST_NAME }}</td>
                                        <td data-label="Quantity"></td>
                                        <td data-label="Price">Rs. {{ $labs->price }}</td>
                                        <td data-label="Status">{{ $labs->status }}</td>
                                        @php
                                            $priceTotal = (int) $priceTotal + (int) $labs->price;
                                            $itemCount += 1;
                                            $providerfee = 0;
                                        @endphp
                                        {{-- <td data-label=""><a href="{{ url('/viewQuestTestReport/1') }}">View Report</a>
                                        </td> --}}
                                    </tr>
                                @endforeach
                                @foreach ($orderImagings as $image)
                                    <tr>
                                        <!-- <th><i class="fa-solid fa fa-flask fs-4"></i></th> -->
                                        <td data-label="Product Name">{{ $image->name }}</td>
                                        <td data-label="Quantity">{{ $image->location }}</td>
                                        <td data-label="Price">Rs. {{ $image->price }}</td>
                                        <td data-label="Status">{{ $image->status }}</td>
                                        @php
                                            $priceTotal = $priceTotal + $image->price;
                                            $itemCount += 1;
                                        @endphp
                                        {{-- <td data-label=""><a href="{{ url('/viewQuestTestReport/1') }}">View Report</a>
                                        </td> --}}
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row">
                        <div class="payment-order-summary-wrap">
                            <div class="card">
                                <div class="card-header">Order Summary</div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        Total Item <span>{{ $itemCount }}</span>
                                    </li>
                                    <li class="list-group-item">
                                        Total Cost <span>Rs. {{ $priceTotal }}</span>
                                    </li>
                                    <li class="list-group-item">
                                        Tax <span>Rs. {{ $data['order_data']->total_tax }}</span>
                                    </li>
                                    {{-- <li class="list-group-item">
                                        Provider Fee <span>Rs. {{ $providerfee }}</span>
                                    </li> --}}
                                    <li class="list-group-item">
                                        To be Paid <span>Rs.
                                            {{ (float) $priceTotal + (float) $data['order_data']->total_tax + (float) $providerfee }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row m-auto">
                    @if ($med == '1')
                    <div class="col-6">
                        <div class="card mt-3">
                            <h5 class="card-header">Shipping Details</h5>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 mb-2">
                                        <div class="card" style="width: 100%">
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item"><b>Name : </b>
                                                    {{ explode('|', $data['shipping'][0])[1] }}</li>
                                                <li class="list-group-item"><b>Phone :</b>
                                                    {{ explode('|', $data['shipping'][2])[1] }}</li>
                                                <li class="list-group-item"><b>Email :</b>
                                                    {{ explode('|', $data['shipping'][1])[1] }}</li>
                                                <li class="list-group-item"><b>Address :</b>
                                                    {{ explode('|', $data['shipping'][3])[1] }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="col-6">
                        <div class="card mt-3">
                            <h5 class="card-header">Vendor Details</h5>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 mb-2">
                                        <div class="card" style="width: 100%">
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item"><b>Name : </b>
                                                    {{ $vendor_details['name'] }}</li>
                                                <li class="list-group-item"><b>Phone :</b>
                                                    {{ $vendor_details['vendor_number'] }}</li>
                                                <li class="list-group-item"><b>Address :</b>
                                                    {{$vendor_details['address'] }}</li>
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