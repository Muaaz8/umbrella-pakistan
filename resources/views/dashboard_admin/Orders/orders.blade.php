@extends('layouts.dashboard_admin')

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
            var val = $('#search').val().toLowerCase();
            if (val == '') {
                window.location.href = '/inclinic/pharmacy/all/orders';
            } else {
                $('#bodies').empty();
                $('#pag').html('');
                $.each(array, function(key, arr) {
                        fullname = arr.user.name+" "+arr.user.last_name
                        if ((arr.id != null && arr.id.toString().match(val))
                            || (arr.user.name != null && arr.user.name.toLowerCase().toString().match(val))
                            || (fullname != null && fullname.toLowerCase().toString().match(val))
                            || (arr.user.phone_number != null && arr.user.phone_number.toString().match(val))) {
                        $('#bodies').append(
                            `<tr>
                                <th scope="S.No">Inclinic_${arr.id}</th>
                                <td data-label="User Name">${arr.user.name} ${arr.user.last_name}</td>
                                <td data-label="User Number">${arr.user.phone_number}</td>
                                <td data-label="Date">${(arr.created_at).split('T')[0]}</td>
                                <td data-label="Action">
                                    <a href="/inclinic/pharmacy/order/${arr.id}">
                                        <button class="orders-view-btn">View</button>
                                    </a>
                                </td>
                            </tr>`
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
                                        <th scope="col">S.No</th>
                                        <th scope="col">User Name</th>
                                        <th scope="col">User Phone Number</th>
                                        {{-- <th scope="col">Order Status</th> --}}
                                        <th scope="col">Date</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="bodies">
                                    @forelse ($tblOrders as $key => $order)
                                        <tr>
                                            <th scope="S.No">Inclinic_{{ $order->id }}</th>
                                            <td data-label="User Name">{{ $order->user->name." ".$order->user->last_name}}</td>
                                            <td data-label="User Number">{{ $order->user->phone_number }}</td>
                                            <td data-label="Date">{{ explode(' ', $order->created_at)[0] }}</td>

                                            <td data-label="Action">
                                                <a href="{{ route('dash_inclinic_pharmacy_order', ['id' => $order->id]) }}">
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
