@extends('layouts.dashboard_Lab_admin')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>UHCS - Doctor Lab Reports</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
@endsection

@section('content')
    <style>
        div.ex1 {
            background-color: lightblue;
            height: 40px;
            width: 200px;
            overflow-y: scroll;
        }
    </style>

    <div class="dashboard-content">
        <div class="container-fluid">
            <div class="row profile-row-wrapper m-auto">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-12  mb-3">
                            <div class="profile-box bg-white">
                                <div class="d-flex flex-column align-items-center">
                                    <img src="{{ $doctor->user_image }}" class="img-fluid rounded profile_picture"
                                        alt="">
                                    <p class="fw-bold h4 mt-3">Dr. {{ $doctor->name . ' ' . $doctor->last_name }}</p>
                                </div>

                                <div class="">
                                    <div class=" px-3 py-2">
                                        <div class="d-flex align-items-center justify-content-between border-bottom">

                                            <p class="py-2">Specialization</p>

                                            <p class="py-2 text-muted"> {{ $doctor->spec->name }}</p>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between border-bottom">
                                            <p class="py-2">Email</p>
                                            <p class="py-2 text-muted">{{ $doctor->email }}</p>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between border-bottom">
                                            <p class="py-2">Phone</p>
                                            <p class="py-2 text-muted">{{ $doctor->phone_number }}</p>
                                        </div>
                                        @if (isset($doctor->upin))
                                            <div class="d-flex align-items-center justify-content-between border-bottom">
                                                <p class="py-2">Upin</p>
                                                <p class="py-2 text-muted">{{ $doctor->upin }}</p>
                                            </div>
                                        @endif
                                        <div class="d-flex align-items-center justify-content-between border-bottom">
                                            <p class="py-2">NPI Number</p>
                                            <p class="py-2 text-muted">{{ $doctor->nip_number }}</p>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between border-bottom">
                                            <p class="py-2">Gender</p>
                                            <p class="py-2 text-muted">{{ Str::ucfirst($doctor->gender) }}</p>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between border-bottom">
                                            <p class="py-2">Date of Birth</p>
                                            @php
                                                $date = str_replace('-', '/', $doctor->date_of_birth);
                                                $newd_o_b = date('m/d/Y', strtotime($date));
                                                //    dd($newd_o_b);
                                            @endphp
                                            <p class="py-2 text-muted">{{ $newd_o_b }}</p>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between border-bottom">
                                            <p class="py-2">Country</p>
                                            <p class="py-2 text-muted" value="{{ $doctor->country }}">
                                                {{ $doctor->country->name }}</p>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between border-bottom">
                                            <p class="py-2">State</p>

                                            <p class="py-2 text-muted" value="{{ $doctor->state_id }}">
                                                {{ $doctor->state->name }}</p>

                                        </div>
                                        <div class="d-flex align-items-center justify-content-between border-bottom">
                                            <p class="py-2">City</p>
                                            <p class="py-2 text-muted" value="{{ $doctor->city_id }}">
                                                {{ $doctor->city->name }}</p>
                                        </div>
                                        <div class="d-flex justify-content-between border-bottom">
                                            <p class="py-2">Address</p>
                                            <p class="py-2 text-muted text-break text-end"> {{ $doctor->office_address }}
                                            </p>
                                        </div>
                                        <div class="d-flex justify-content-between border-bottom">
                                            <p class="py-2">Licensed State</p>
                                            <p class="py-2 text-muted text-break text-end">
                                                @foreach ($doctor->license as $li)
                                                    {{ $li->name }}
                                                @endforeach
                                            </p>
                                        </div>
                                        <div class="d-flex justify-content-between border-bottom">
                                            <p class="py-2">Timezone</p>
                                            <p class="py-2 text-muted text-break text-end"> {{ $doctor->timeZone }}</p>
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
