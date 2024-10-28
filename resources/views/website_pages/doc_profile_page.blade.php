@extends('layouts.new_web_layout')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>Doctor {{ $doctor->name." ".$doctor->last_name }}</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
@endsection

@section('content')
<section class="container pt-6 pb-8">
    <div class="row">
        <div class="col-lg-8">
            <div class="profile-header mb-5">
                <h1 class="display-4 color font-weight-bold">Dr. {{ $doctor->name." ".$doctor->last_name }}</h1>
                <p class="text-muted mb-4">{{ $doctor->specializations->name }} - {{ $doctor->nip_number }}</p>
            </div>
            <div class="profile-personal-details mb-4">
                <h4 class="color font-weight-bold"><u>Personal Details </u></h4>
                <ul class="list-unstyled">
                    <li><i class="fa-solid fa-venus-mars color"></i><strong>Gender:</strong> {{ Str::ucfirst($doctor->gender) }}</li>
                    <li><i class="fa-solid fa-globe color"></i> <strong>Language:</strong> English</li>
                    <li><i class="fa-solid fa-map-marker-alt color"></i> <strong>Residency:</strong> {{ \App\City::find($doctor->city_id)->name }}, {{ \App\State::find($doctor->state_id)->name }}</li>
                    <li><i class="fa-solid fa-user-tie color"></i> <strong>Provider Type: </strong>{{ $doctor->specializations->name }}</li>
                </ul>
            </div>

            <hr>
            <div class="profile-about mb-4">
                <h5 class="text-secondary font-weight-bold">About</h5>
                <p class="text-justify">{{ $doctor->details!=null?$doctor->details->about:"No Data Available" }}</p>
            </div>
            <div class="profile-approach mb-4">
                <h5 class="text-secondary font-weight-bold">Medical Education</h5>

                    <ul class="text-justify">
                        <li class="border border-1 p-3">{{$doctor->details!=null?$doctor->details->education:"No Data Available" }}</li>
                    </ul>

            </div>
            <div class="profile-approach mb-4">
                <h5 class="text-secondary font-weight-bold">Approach to Helping</h5>
                <p class="text-justify">{{ $doctor->details!=null?$doctor->details->helping:"No Data Available" }}</p>
            </div>
            <div class="profile-specialization mb-4">
                <h5 class="text-secondary font-weight-bold">Specific Issues Skilled at Helping With</h5>
                <p class="text-justify">{{ $doctor->details!=null?$doctor->details->issue:"No Data Available" }}</p>
            </div>
            <div class="profile-specialization mb-4">
                <h5 class="text-secondary font-weight-bold">Specialties</h5>
                <p class="text-justify">{{ $doctor->details!=null?$doctor->details->specialties:"No Data Available" }}</p>
            </div>
        </div>
        <!-- Sidebar Section -->
        <div class="col-lg-4">
            <div class="position-relative d-flex align-items-center justify-content-center mb-4">
                <img src="{{ asset('assets/images/brush_color2.png')}}" alt="Profile Image" style="height: 300px; width: 100%;">
                <div class="doc__img position-absolute">
                    <img src="{{ $doctor->user_image }}" alt="Dr. Zayan Ahmed">
                </div>
            </div>

            <div class="profile-services mb-4">
                <h4 class="color font-weight-bold">Certifications and Licensing</h4>
                <ul class="list-unstyled">
                    @if($doctor->details != null)
                        @forelse ($doctor->details->certificates as $item)
                            <li><i class="fa-solid fa-check text-success"></i>{{ $item }}</li>
                        @empty
                            <li><i class="fa-solid fa-check text-success"></i>No Data</li>
                        @endforelse
                    @endif
                </ul>
            </div>
            <hr>

            <div class="profile-services mb-4">
                <h4 class="color font-weight-bold">Condition Treated</h4>
                <ul class="list-unstyled">
                    @if($doctor->details != null)
                        @forelse ($doctor->details->conditions as $item)
                            <li><i class="fa-solid fa-check text-success"></i>{{ $item }}</li>
                        @empty
                            <li><i class="fa-solid fa-check text-success"></i>No Data</li>
                        @endforelse
                    @endif

                </ul>
            </div>
            <hr>
            <div class="profile-concerns mb-4">
                <h4 class="color font-weight-bold">Procedures Performed</h4>
                <ul class="list-unstyled">
                    @if($doctor->details != null)
                        @forelse ($doctor->details->procedures as $item)
                            <li><i class="fa-solid fa-check text-success"></i>{{ $item }}</li>
                        @empty
                            <li><i class="fa-solid fa-check text-success"></i>No Data</li>
                        @endforelse
                    @endif
                </ul>
            </div>
            <hr>
            <div class="profile-location mb-4">
                <h4 class="color font-weight-bold">My Location</h4>
                <p><i class="fa-solid fa-map-marker-alt color"></i>{{ $doctor->details != null ?$doctor->details->location:"No Data Available" }}<br></p>
                <hr>
                @if($doctor->details != null)
                    <iframe src='https://www.google.com/maps/?q={{ $doctor->details->latitude }},{{ $doctor->details->longitude }}&hl=es;z=14&amp;output=embed' width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
