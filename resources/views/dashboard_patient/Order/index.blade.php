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
<script src="{{asset('assets\js\searching.js')}}"></script>
@endsection

@section('content')
<div class="dashboard-content">
    <div class="container-fluid">
      <div class="row m-auto">
        <div class="col-md-12">
          <div class="row m-auto">
            <div class="d-flex align-items-baseline justify-content-between flex-wrap p-0">
              <h3>Orders</h3>
              <div class="p-0">
                <div class="input-group">
                  <input
                    type="text"
                    id="search"
                    class="form-control"
                    placeholder="Search"
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
                    {{-- <th scope="col">Order State</th> --}}
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
                    <td data-label="Order ID" type="hidden">{{ $order->order_id }}</td>
                    {{-- <td data-label="Order State">{{ $order->order_state }}</td> --}}
                    @if ($order->order_status == "paid")
                        <td data-label="Payment"><label class="order-paid">Paid</label></td>
                    @elseif ($order->order_status == "Pending")
                        <td data-label="Payment"><label class="order-pending">Pending</label></td>
                    @else
                        <td data-label="Payment"><label class="order-progress">In Progress</label></td>
                    @endif
                    <td data-label="Date">{{ explode(" ", $order->created_at)[0] }}</td>
                    <td data-label="Time">{{ explode(" ", $order->created_at)[1] }}</td>
                    <td data-label="Action"><a href="{{ route('patient_order_detail', $order->id) }}"><button class="orders-view-btn">View</button></a></td>
                </tr>
                @php
                    $counter++;
                @endphp
                @empty
                <tr>
                    <td colspan='6'>
                    <div class="m-auto text-center for-empty-div">
                        <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                        <h6> No Orders</h6>
                    </div>
                    </td>
                </tr>
                @endforelse
                </tbody>
              </table>

            </div>
            {{ $tblOrders->links('pagination::bootstrap-4') }}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
