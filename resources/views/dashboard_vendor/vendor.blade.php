@extends('layouts.dashboard_vendor')
@section('meta_tags')
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('page_title')
    <title>Dashboard Vendor</title>
@endsection

@section('top_import_file')
@endsection

@section('bottom_import_file')
@endsection

@section('content')
    <div class="dashboard-content">
        <div class="container-fluid">
            <div class="row m-auto">
                <div class="col-md-12">
                    <div class="row m-auto">
                        <div class="col-md-12">
                            <div class="card first-card-wrap">
                                <div class="card-body">
                                    <div class="first-card-content">
                                        <h1>Community Healthcare Clinics</h1>
                                        <p>Welcome to Community Healthcare Clinics</p>
                                    </div>
                                    <div class="first-card-img-div">
                                        {{-- <img src="assets/images/logo.png" alt=""  height="auto" width="200"> --}}
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


    </div>
@endsection
