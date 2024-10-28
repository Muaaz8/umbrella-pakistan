@extends('layouts.dashboard_Pharm_admin')

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
    <script>
        var input = document.getElementById("search");
        input.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                document.getElementById("search_btn").click();
            }
        });

        function search(array) {
            var val = $('#search').val();
            console.log(val,array);
            if (val == '') {
                window.location.href = '/pharmacy/all/orders';
            } else {
                $('#bodies').empty();
                $('#pag').html('');
                $.each(array, function(key, arr) {
                    if ((arr.order_id != null && arr.order_id.toString().match(val)) || (arr.order_state != null &&
                            arr.order_state.toString().match(val)) ||
                        (arr.order_status != null && arr.order_status.toString().match(val)) || (arr.created_at
                            .date != null &&
                            arr.created_at.date.toString().match(val))) {
                        $('#bodies').append('<tr id="body_' + arr.order_id + '">'+
                            '<td data-label="Order ID">' + arr.order_id + '</td>' +
                            '<td data-label="Order State">' + arr.order_state + '</td>' +
                            '<td data-label="Order Status">' + arr.order_status + '</td>' +
                            '<td data-label="Date">' + arr.created_at.split(' ')[0] + '</td>' +
                            '<td data-label="Time">' + arr.created_at.split(' ')[1] + '</td>' +
                            '<td data-label="Action">' +
                            '<a href="/pharmacy/order/'+arr.id+'">' +
                            '<button class="orders-view-btn">View</button></a></td></tr>'
                        );
                    }
                });
            }
        }
    </script>
@endsection

@section('content')
    <div class="dashboard-content">
        <div class="container-fluid">
            <div class="row m-auto">
                <div class="col-md-12">
                    <div class="row m-auto">
                        <div class="d-flex justify-content-between flex-wrap align-items-baseline p-0">
                            <h3>Orders</h3>
                            <div class="col-md-4 p-0">
                                <div class="input-group">
                                    <div class="d-flex">
                                        <input type="text" class="form-control mb-1" id="search"
                                            placeholder="Search editor">
                                        <button type="button" id="search_btn"
                                            onclick="search({{ json_encode($data) }})" class="btn process-pay"><i
                                                class="fa-solid fa-search"></i></button>
                                    </div>
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
                                <tbody id="bodies">
                                    @forelse ($tblOrders as $order)
                                        <tr>
                                            {{-- <th scope="row">1</th> --}}
                                            <td data-label="Order ID">{{ $order->order_main_id }}</td>
                                            <td data-label="Order State">{{ $order->order_state }}</td>
                                            <td data-label="Order Status">{{ $order->order_status }}</td>
                                            <td data-label="Date">{{ explode(' ', $order->created_at)[0] }}</td>
                                            <td data-label="Time">{{ explode(' ', $order->created_at)[1] }}
                                                {{ explode(' ', $order->created_at)[2] }}</td>
                                            <td data-label="Action">
                                                <a href="{{ route('dash_pharmacy_order', ['id' => $order->id]) }}">
                                                    <button class="orders-view-btn">View</button>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6">
                                                <div class="m-auto text-center for-empty-div">
                                                    <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                                    <h6>No Orders To Show</h6>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse

                                </tbody>
                            </table>
                            <div id="pag">
                            {{ $tblOrders->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
