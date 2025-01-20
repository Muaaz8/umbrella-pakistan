@extends('layouts.dashboard_doctor')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>CHCC - Doctor Pending Labs</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
<script src="{{asset('assets\js\searching.js')}}"></script>
@endsection

@section('content')
{{-- {{ dd($requisitions) }} --}}
<div class="dashboard-content">
    <div class="container-fluid">
        <div class="row m-auto">
            <div class="col-md-12">
                <div class="row m-auto">
                    <div class="d-flex align-items-baseline justify-content-between flex-wrap p-0">
                        <h3>Lab Pending Requisitions</h3>
                        <div class="p-0">
                            <div class="input-group">
                                <input type="text" id="search" class="form-control" placeholder="Search"
                                    aria-label="Username" aria-describedby="basic-addon1" />
                            </div>
                        </div>
                    </div>
                    <div class="wallet-table table-responsive">
                        <table class="table" id="table">
                            <thead>
                                <!-- <th scope="col">S.No</th> -->
                                <th scope="col">Test Names</th>
                                <th scope="col">Order ID</th>
                                <th scope="col">Date</th>
                                <th scope="col">Requisition</th>
                            </thead>
                            <tbody>
                                @php
                                $counter = 1;

                                @endphp
                                @forelse ($pending_requisitions as $pending_requisition)
                                @php $index = 1; @endphp
                                <tr>
                                    <td class="text-start ps-3" data-label="Test Names">
                                        @foreach($pending_requisitions_test_name as $data)
                                        @if($data->order_id==$pending_requisition->order_id)
                                        <strong>{{$index}}) </strong>{{ $data->TEST_NAME }}<br>
                                        @php $index++; @endphp
                                        @endif
                                        @endforeach
                                    </td>
                                    <td data-label="Order ID">{{ $pending_requisition->order_id }}</td>
                                    <td data-label="Date">{{ $pending_requisition->date }}</td>
                                    <td data-label="Price">In Progress</td>
                                </tr>
                                @php
                                $counter++;
                                @endphp
                                @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="m-auto text-center for-empty-div">
                                            <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                            <h6>No Requisitions To Show</h6>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
