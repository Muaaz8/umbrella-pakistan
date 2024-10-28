@extends('layouts.dashboard_Pharm_admin')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>UHCS - Pharmacy Admin Dashboard</title>
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
                                <h1>Umbrella Health Care Systems</h1>
                                <p>Welcome to Umbrella Health Care Systems</p>
                            </div>
                            <div class="first-card-img-div">
                                <img src="assets/images/logo.png" alt=""  height="100" width="150">
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
