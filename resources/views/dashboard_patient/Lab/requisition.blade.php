@extends('layouts.dashboard_patient')

@section('meta_tags')
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
<title>UHCS - Lab Requisition</title>
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
                        <h3>Lab Requisitions</h3>
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
                                <th scope="col">Order ID</th>
                                <th scope="col">Date</th>
                                <th scope="col">Refered Physician</th>
                                <th scope="col">Test Names</th>
                                <th scope="col">Action</th>
                            </thead>
                            <tbody>
                                @php
                                $counter = 1;
                                @endphp
                                @forelse ($requisitions as $requisition)
                                @php $index = 1; @endphp
                                <tr>
                                    <!-- <td data-label="S.No" scope="row">{{ $counter }}</td> -->
                                    <td data-label="Order ID">{{ $requisition->order_id }}</td>
                                    <td data-label="Date">{{ $requisition->created_at }}</td>
                                    <td data-label="Refered Physician ID">{{ $requisition->ref_physician_id }}</td>
                                    <td class="text-start ps-3" data-label="Test Names">
                                        @foreach($requisition->names as $names)
                                        <strong>{{$index}}) </strong>{{$names->testName }}</br>
                                        @php $index++; @endphp
                                        @endforeach
                                    </td>
                                    <td data-label="Action"><a target="_blank"
                                            href="{{\App\Helper::get_files_url($requisition->requisition_file) }}">
                                            <button class="orders-view-btn">REQUISITION</button>
                                        </a>
                                    </td>
                                </tr>
                                @php
                                $counter++;
                                @endphp
                                @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="m-auto text-center for-empty-div">
                                            <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                            <h6>No Requisitions To Show</h6>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $requisitions->links('pagination::bootstrap-4') }}

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
