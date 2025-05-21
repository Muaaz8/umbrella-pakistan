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
                    <div class="card first-card-wrap">
                        <div class="card-body align-items-end">
                            <div class="first-card-content">
                                <p>Welcome to</p>
                                <h1>Community Healthcare Clinics</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row m-auto">
                <div class="col-md-12">
                    <div class="row my-4">
                        <div class="col-sm-6 col-lg-4 mb-2">
                            <div class="dashboard-small-card-wrap">
                                <a href="/all/patients">
                                    <div class="d-flex dashboard-small-card-inner">
                                        <i class="fa-solid fa-hospital-user"></i>
                                        <div>
                                            <h6>All Orders</h6>
                                            <p>100</p>
                                        </div>

                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4 mb-2">
                            <div class="dashboard-small-card-wrap">
                                <a href="/doctor/appointments">
                                    <div class="d-flex dashboard-small-card-inner">
                                        <i class="fa-regular fa-calendar-check"></i>
                                        <div>
                                            <h6>Active Products</h6>
                                            <p>1000</p>
                                        </div>

                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4 mb-2">
                            <div class="dashboard-small-card-wrap">
                                <a href="/all/sessions">
                                    <div class="d-flex dashboard-small-card-inner">
                                        <i class="fa-solid fa-laptop-medical"></i>
                                        <div>
                                            <h6>Pendding Orders</h6>
                                            <p>12</p>
                                        </div>

                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row m-auto">
                <div class="col-md-12">
                    <h5>Pending Orders</h5>
                    <div class="dashboard-table table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Time</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- @forelse($appoints as $app) --}}
                                <tr>
                                    <td data-label="Name" scope="row">zayan</td>
                                    <td data-label="Date">12-05-2025</td>
                                    <td data-label="Time">13:30</td>
                                    <td data-label="Status">Pending</td>
                                    <td data-label="Action">
                                        <button id="view_"
                                            class="view_btn btn btn-raised btn-primary btn-sm waves-effect">View</button>
                                    </td>
                                </tr>
                                {{-- @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="m-auto text-center for-empty-div">
                                            <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                            <h6> No Appointments Pending</h6>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse --}}
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection