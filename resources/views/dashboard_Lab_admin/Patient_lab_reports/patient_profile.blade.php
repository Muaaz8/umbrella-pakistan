@extends('layouts.dashboard_Lab_admin')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>CHCC - Doctor Lab Reports</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
@endsection

@section('content')
    <div class="dashboard-content">
        <div class="container-fluid">
            <div class="row profile-row-wrapper m-auto">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="profile-box bg-white">
                                <div class="d-flex flex-column align-items-center">
                                    <img src="{{ $patient->user_image }}" class="img-fluid rounded profile_picture"
                                        alt="">

                                    <span class="profile_name"></span>
                                    <p class="fw-bold h4 mt-3">{{ $patient->name . ' ' . $patient->last_name }}</p>
                                </div>

                                <div class="">
                                    <div class="px-3 py-2">
                                        <div class="d-flex align-items-center justify-content-between border-bottom">
                                            <p class="py-2">Email</p>
                                            <p class="py-2 text-muted">{{ $patient->email }}</p>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between border-bottom">
                                            <p class="py-2">Phone</p>
                                            <p class="py-2 text-muted">{{ $patient->phone_number }}</p>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between border-bottom">
                                            <p class="py-2">Date Of Birth</p>
                                            @php
                                            $date = str_replace('-', '/', $patient->date_of_birth);
                                            $newd_o_b = date('m/d/Y', strtotime($date));
                                            //    dd($newd_o_b);
                                            @endphp
                                            <p class="py-2 text-muted">{{ $newd_o_b }}</p>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between border-bottom">
                                            <p class="py-2">Country</p>
                                            <p class="py-2 text-muted">{{ $patient->country }}</p>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between border-bottom">
                                            <p class="py-2">Zip Code</p>
                                            <p class="py-2 text-muted">{{ $patient->zip_code }}</p>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between border-bottom">
                                            <p class="py-2">State</p>
                                            <p class="py-2 text-muted">{{ $patient->state }}</p>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between border-bottom">
                                            <p class="py-2">City</p>
                                            <p class="py-2 text-muted">{{ $patient->city }}</p>
                                        </div>
                                        <div class="d-flex justify-content-between border-bottom">
                                            <p class="py-2">Address</p>
                                            <p class="py-2 ps-2 text-muted text-break text-end">{{ $patient->office_address }}</p>
                                        </div>
                                        <div class="d-flex justify-content-between border-bottom">
                                            <p class="py-2">Timezone</p>
                                            <p class="py-2 text-muted text-break text-end"> {{ $patient->timeZone }}</p>
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
