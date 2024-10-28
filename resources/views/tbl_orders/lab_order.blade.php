@php
    use Carbon\Carbon;
@endphp

@extends('layouts.admin')
<link rel="stylesheet" href="{{ asset('asset_admin/css/lab-order.css') }}">

@section('content')

    <section class="content home">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Dashboard</h2>
                <small class="text-muted">Welcome to Umbrellamd</small>
            </div>

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <h4>Payment: <strong>Paid</strong> </h4>
                    <h4>Status: <strong>Pending</strong> </h4>
                    <div class="card">
                        <div class="row" style="padding-left: 20px">
                            <div class="row col-md-12">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header text-center"
                                            style=" font-size: 1.5rem; margin-top: 30px; background-color:#5387d4; color:#fff;">
                                            Lab Order
                                        </div>

                                        <div class="card-body">
                                            <div class="row heading_row">
                                                <table class="col-md-12 table-bordered table-hover">
                                        @foreach ($lab_order as $lab_order)
                                                    <tr class="p-2 mb-1">
                                                        <th width="150">Tracking ID</th>
                                                        <td width="200">{{ $lab_order->sub_order_id }}</td>
                                                    </tr>
                                                    <tr class="p-2 mb-1">
                                                        <th width="150">Lab Test</th>
                                                        <td width="200">{{ $lab_order->name }}</td>
                                                    </tr>
                                                    <tr class="p-2 mb-1">
                                                        <th width="150">Name</th>
                                                        <td width="200">
                                                            {{ $lab_order->first_name . ' ' . $lab_order->last_name }}
                                                        </td>
                                                    </tr>
                                                    <tr class="p-2 mb-1">
                                                        <th width="150">Username</th>
                                                        <td width="200">{{ $lab_order->username }}</td>
                                                    </tr>
                                                    <tr class="p-2 mb-1">
                                                        <th width="150">Booking Date</th>
                                                        <td width="200">{{$lab_order->date}}</td>
                                                    </tr>
                                                    <tr class="p-2 mb-1">
                                                        <th width="150">Booking Time</th>
                                                        <td width="200">{{ $lab_order->time }}</td>
                                                    </tr>
                                                    <tr class="p-2 mb-1">
                                                        <th width="150">Order State</th>
                                                        <td width="200">{{ $lab_order->order_state }}</td>
                                                    </tr>
                                                    <tr class="p-2 mb-1">
                                                        <th width="150">Payment Title</th>
                                                        <td width="200">{{ $lab_order->payment_title }}</td>
                                                    </tr>
                                                    <tr class="p-2 mb-1">
                                                        <th width="150">Payment Method</th>
                                                        <td width="200">{{ $lab_order->payment_method }}</td>
                                                    </tr>
                                                    <tr class="p-2 mb-1">
                                                        <th width="150">Currency</th>
                                                        <td width="200">{{ $lab_order->currency }}</td>
                                                    </tr>
                                                    <tr class="p-2 mb-1">
                                                        <th width="150">Report</th>
                                                        <td width="200">
                                                            @if ($lab_order->order_status != 'reported')
                                                                <form method="post"
                                                                    action="{{ route('upload_lab_report', $lab_order->id) }}"
                                                                    enctype="multipart/form-data">
                                                                    @csrf
                                                                    <div class="cross-input">
                                                                        <div
                                                                            class="custom-file col-lg-12 col-md-7 p-0 pt-2">
                                                                            <input type="file" hidden
                                                                                class="custom-file-input customFile"
                                                                                name="lab_report" id="customFile">
                                                                            <label class="custom-file-label col-12 mb-0"
                                                                                for="customFile"
                                                                                style="padding:8px;
                                                                                border-radius:13px; border:grey 1px solid;top:9px">
                                                                                Select File</label>

                                                                        </div>
                                                                        <div class=" p-0 cross">
                                                                            <button id="cancel_file" class="cancel">
                                                                                <i class="fa fa-times"></i>
                                                                            </button>
                                                                        </div>
                                                                        <br>
                                                                        <button type="submit" style="top:9px"
                                                                            class="btn btn-raised btn-primary p-2 mt-3
                                                                                    waves-effect btn-raised waves-float m-0"><i
                                                                                class="fa fa-upload"
                                                                                aria-hidden="true"></i>Upload
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            @else
                                                                <a href="{{ asset('asset_admin/images/lab_reports/' . $lab_order->report) }}"
                                                                    target="_blank"><i class="fa fa-eye"></i> View</a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script>
        // Add the following code if you want the name of the file appear on select
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
        $('.cancel').click(function() {
            console.log('ghjkl');
            $('.customFile').val('');
            $('.customFile').siblings(".custom-file-label").removeClass("selected").html(
                'No File Added');
        })
    </script>
@endsection
