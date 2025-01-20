@extends('layouts.dashboard_doctor')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>CHCC - All Patients</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
@endsection

@section('content')
    {{-- {{ dd($all_patients) }} --}}
    <div class="dashboard-content">
        <div class="container-fluid all-patient-wrapper">
            <div class="row m-auto">
                <h3 class="pb-2">All Patients</h3>
                @forelse ($all_patients as $patient)
                    <div class="col-md-4 col-xxl-3 col-sm-6 mb-3">
                        <div class="card d-flex flex-column align-items-center justify-content-center">
                            <div class="inner-content d-flex flex-column align-items-center justify-content-center">
                                <div class="img-container rounded-circle">
                                    <img src="{{ $patient->user_image }}"
                                        alt="" class="rounded-circle">
                                </div>
                                <div class="h3">{{ $patient->pat_name }}</div>
                                <p class="designation text-muted text-uppercase">Last Visit: {{ $patient->last_visit }}</p>
                                @if ($patient->inclinic)
                                    <p class="designation text-white" style="background: green; border-radius: 20px; padding: 5px; font-size: 12px !important; border:1px solid white;">Inclinic Patient</p>
                                @else
                                    <p class="designation text-white" style="background: rgb(0, 81, 255); border-radius: 20px; padding: 5px; font-size: 12px !important; border:1px solid white;">Online Patient</p>
                                @endif
                            </div>
                            <ul
                                class="social-links d-flex align-items-center justify-content-around list-unstyled w-100 fs-5 m-0 p-0">
                                {{-- <li>
                                    <h6>Last Diagnosis: {{ $patient->last_diagnosis }}</h6>
                                </li> --}}
                                <li><a href="{{route('patient_detailed',$patient->patient_id)}}"><button>view</button></a></li>
                            </ul>
                        </div>
                    </div>
                @empty
                <div class="text-center for-empty-div">
                    <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                    <h6> No Patients</h6>
                </div>
                @endforelse

            </div>
        </div>
    </div>
@endsection
