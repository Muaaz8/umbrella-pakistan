@extends('layouts.dashboard_Pharm_admin')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>Pharmacy Admin Dashboard</title>
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
                    <div class="card first-card-wrap">
                        <div class="card-body">
                            <div class="first-card-content">
                                <h1>Community Health Care Clinics</h1>
                                <p>Welcome to Community Health Care Clinics</p>
                            </div>
                            <div class="first-card-img-div">
                                <img src="assets/images/logo.png" alt=""  height="auto" width="200">
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
