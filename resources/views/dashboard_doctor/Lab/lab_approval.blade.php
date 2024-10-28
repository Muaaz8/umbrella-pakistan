@extends('layouts.dashboard_doctor')

@section('meta_tags')
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
<title>UHCS - Doctor Labs Approval</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
<script src="{{asset('assets\js\searching.js')}}"></script>
<script>
    @php header("Access-Control-Allow-Origin: *"); @endphp
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    function approvedLabTest(order_id) {
        $.ajax({
            type: "POST",
            url: "{{URL('/submit_pending_approvals')}}",
            beforeSend: function(){
                $('#acceptIcon_'+order_id).html('');
                $('#acceptIcon_'+order_id).html('<i class="fa fa-spinner fa-spin"></i>');
                // $('#acceptIcon').addClass('fa fa-spinner fa-spin');
            },
            data: {
                order_id: order_id
            },
            success: function (res) {
                if (res == 'ok') {
                    location.href = '/doctor/online/lab/approval/requests';
                }
            }
        });
    }

    function disapprovedLabTest(order_id) {
        $.ajax({
            type: "POST",
            url: "{{URL('/disapprovedLabTest')}}",
            data: {
                order_id: order_id
            },
            success: function (res) {
                if (res == 'ok') {
                    location.href = '/doctor/online/lab/approval/requests';
                }
            }
        });

    }
</script>
@endsection

@section('content')
<div class="dashboard-content">
    <div class="container-fluid">
        <div class="row m-auto">
            <div class="col-md-12">
                <div class="row m-auto">
                    <div class="d-flex align-items-baseline justify-content-between flex-wrap p-0">
                        <h3>Pending Online Lab</h3>
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
                                <th scope="col">Order ID</th>
                                <th scope="col">Order State</th>
                                <th scope="col">Order Date</th>
                                <th scope="col">Lab Product</th>
                                <th scope="col">Action</th>
                            </thead>
                            <tbody id="table">
                                @forelse($pending_requisitions as $order)
                                @php $index = 1; @endphp
                                <tr>
                                    {{-- submit_pending_approvals --}}
                                    <td data-label="Order ID" scope="row">{{ $order->order_id }}</td>
                                    <td data-label="Order State">{{ $order->user_state }}</td>
                                    <td data-label="Order Date">{{ $order->date }}</td>
                                    <td class="text-start ps-3" data-label="Lab Product">
                                        @foreach($pending_requisitions_test_name as $data)
                                        @if($data->order_id==$order->order_id)
                                        <strong>{{$index}}) </strong>{{ $data->TEST_NAME }}<br>
                                        @php $index++; @endphp
                                        @endif
                                        @endforeach
                                    </td>
                                    <td data-label="Action" class="lab-app-td-icon">
                                        <a href="{{url('patient/detail/'.$order->user_id)}}"
                                            class="orders-view-btn">View Patient</a>
                                            <div id="acceptIcon_{{ $order->order_id }}">
                                                <button type="button" onclick="approvedLabTest({{ $order->order_id }})"
                                                    value="Approve"><i id="acceptIcon" class="fa-solid fa-circle-check"></i></button>
                                            </div>
                                        <button type="button" onclick="disapprovedLabTest({{ $order->order_id }})"
                                            value="Decline"><i class="fa-solid fa-circle-xmark"></i></button>
                                    </td>

                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="m-auto text-center for-empty-div">
                                            <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                            <h6> No Pending Online Labs</h6>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $pending_requisitions->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
@endsection
