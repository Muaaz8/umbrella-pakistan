@extends('layouts.dashboard_doctor')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>UHCS - Doctor Approved Labs</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
<script src="{{asset('assets\js\searching.js')}}"></script>
@endsection

@section('content')
    {{-- {{ dd($orders) }} --}}
    <div class="dashboard-content">
        <div class="container-fluid">
            <div class="row m-auto">
                <div class="col-md-12">
                    <div class="row m-auto">
                        <div class="d-flex align-items-baseline justify-content-between flex-wrap p-0">
                            <h3>Approved Labs</h3>
                            <div class=" p-0">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="search" placeholder="Search"
                                        aria-label="Username" aria-describedby="basic-addon1" />
                                </div>
                            </div>
                        </div>
                        <div class="wallet-table table-responsive">
                            <table class="table">
                                <thead>
                                        <th scope="col">Lab Product</th>
                                        <th scope="col">Order ID</th>
                                        <th scope="col">Order Date</th>
                                        <th scope="col">Order Time</th>
                                </thead>
                                <tbody id="table">
                                    @forelse ($orders as $order)
                                    @php $index = 1; @endphp
                                        <tr>
                                            <td class="text-start ps-3">
                                                @foreach($orders_test_name as $data)
                                                @if($data->order_id==$order->order_id)
                                                <strong>{{$index}}) </strong>{{ $data->TEST_NAME }}<br>
                                                @php $index++; @endphp
                                                @endif
                                                @endforeach
                                            </td>
                                            <td data-label="Order ID" scope="row">{{ $order->order_id }}</td>
                                            <td data-label="Order Date">{{ explode(" ", $order->created_at)[0] }}</td>
                                            <td data-label="Order Time">{{ explode(" ", $order->created_at)[1] }}</td>
                                        </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4">
                                                <div class="m-auto text-center for-empty-div">
                                                    <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                                    <h6> No Approved Labs</h6>
                                                </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            {{ $orders->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
@endsection
