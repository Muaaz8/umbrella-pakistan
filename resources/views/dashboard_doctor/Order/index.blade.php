@extends('layouts.dashboard_doctor')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>CHCC - All Orders</title>
@endsection

@section('top_import_file')
    <link href="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/datedropper.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/datedropper.js"></script>
@endsection


@section('bottom_import_file')
<script src="{{asset('assets\js\searching.js')}}"></script>
@endsection

@section('content')
{{-- {{ dd($user,$tblOrders) }} --}}

<div class="dashboard-content">
    <div class="container-fluid">
      <div class="row m-auto">
        <div class="col-md-12">
          <div class="row m-auto">
            <div class="d-flex align-items-baseline justify-content-between flex-wrap p-0">
              <h3>Orders</h3>
              <div class="col-md-4 col-12 p-0">
                <div class="input-group">
                  <input
                    type="text"
                    id="search"
                    class="form-control"
                    placeholder="Search what you are looking for"
                    aria-label="Username"
                    aria-describedby="basic-addon1"
                  />
                </div>
              </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12">
                @include('flash::message')
            </div>
            <div class="wallet-table table-responsive">
              <table class="table" id="table">
                <thead>
                    <!-- <th scope="col">S.No</th> -->
                    <th scope="col">Order ID</th>
                    <th scope="col">Payment</th>
                    <th scope="col">Date</th>
                    <th scope="col">Time</th>
                    <th scope="col">Action</th>
                </thead>
                <tbody>
                    @php
                        $counter = 1;
                    @endphp
                    @forelse ($tblOrders as $order)
                  <tr>
                    <!-- <td data-label="S.No" scope="row">{{ $counter }}</td> -->
                    <td data-label="Order ID">{{ $order->order_id }}</td>
                    @if ($order->order_status == "paid")
                        <td data-label="Order State"><label class="order-paid">Paid</label></td>
                    @elseif ($order->order_status == "Pending")
                        <td data-label="Order State"><label class="order-pending">Paid</label></td>
                    @else
                        <td data-label="Order State"><label class="order-progress">Paid</label></td>
                    @endif
                    <td data-label="Date">{{ $order->created_at['date'] }}</td>
                    <td data-label="Time">{{ $order->created_at['time'] }}</td>
                    <td data-label="Action"><a href="/doctor/order/{{ $order->id }}"><button class="orders-view-btn">View</button></a></td>
                  </tr>
                  @php
                      $counter++;
                  @endphp
                  @empty

                  <tr>
                    <td colspan="5" >
                        <div class="m-auto text-center for-empty-div">
                            <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                            <h6>No Orders</h6>
                        </div>
                        </div>
                    </td>
                </tr>
                  @endforelse
                </tbody>
            </table>
            {{ $tblOrders->links('pagination::bootstrap-4') }}
        </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
{{-- </div>
    <div class="dashboard-content">
        <div class="container">
            <div class="row m-auto">
                <div class="col-md-12">



                    <div class="row m-auto">
                        <h3>Orders</h3>
                        <div class="wallet-table">
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Order ID</th>
                                        <th>Order State</th>
                                        <th>Payment</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $counter = 1;
                                    @endphp
                                    @foreach ($tblOrders as $order)
                                    <tr>
                                        <td>{{ $counter }}</td>
                                        <td>{{ $order->order_id }}</td>
                                        <td>{{ $order->order_state }}</td>
                                        @if ($order->order_status == "paid")
                                            <td><label class="badge badge-success">{{ $order->order_status }}</label></td>
                                        @elseif ($order->order_status == "Pending")
                                            <td><label class="badge badge-danger">{{ $order->order_status }}</label></td>
                                        @else
                                            <td><label class="badge badge-warning">{{ $order->order_status }}</label></td>
                                        @endif
                                        <td>{{ $order->created_at }}</td>
                                        <td><i class="fa-solid fa-eye"></i></td>
                                    </tr>
                                    @php
                                        $counter++;
                                    @endphp
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Order ID</th>
                                        <th>Order State</th>
                                        <th>Payment</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>



                </div>
            </div>
        </div>
    </div>
    </div> --}}
@endsection
