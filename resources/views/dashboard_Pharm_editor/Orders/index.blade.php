@extends('layouts.dashboard_Pharm_editor')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>UHCS - Orders</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
@endsection

@section('content')
    <div class="dashboard-content">
        <div class="container">
            <div class="row m-auto">
                <div class="col-md-12">
                    <div class="row m-auto">
                        <div class="d-flex align-items-baseline p-0">
                            <h3>Orders</h3>
                            <div class="col-md-4 ms-auto p-0">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search what you are looking for"
                                        aria-label="Username" aria-describedby="basic-addon1" />
                                </div>
                            </div>
                        </div>
                        <div class="wallet-table">
                            <table class="table">
                                <thead>
                                    <tr>
                                        {{-- <th scope="col">S.No</th> --}}
                                        <th scope="col">Order ID</th>
                                        <th scope="col">Order State</th>
                                        <th scope="col">Order Status</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Time</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($tblOrders as $order)
                                        <tr>
                                            {{-- <th scope="row">1</th> --}}
                                            <td>{{ $order->order_main_id }}</td>
                                            <td>{{ $order->order_state }}</td>
                                            <td>{{ $order->order_status }}</td>
                                            <td>{{ explode(" ",$order->created_at)[0] }}</td>
                                            <td>{{ explode(" ",$order->created_at)[1]}} {{ explode(" ",$order->created_at)[2] }}</td>
                                            <td><button class="orders-view-btn" >View</button></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3">
                                                <div class="m-auto text-center for-empty-div">
                                                    <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                                    <h6>No Orders To Show</h6>
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
@endsection
