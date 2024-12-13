@extends('layouts.new_pakistan_layout')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>Doctor {{ $doctor->name . ' ' . $doctor->last_name }}</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
@endsection

@section('content')
    <main class="profile_main d-flex align-items-center justify-content-center w-100 h-100 py-sm-4 py-2">
        <div class="profile_container row px-sm-3 px-1 py-4">
            <div class="col-12 col-md-8 d-flex flex-column gap-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="profile_pic_container rounded-circle">
                        <img class="rounded-circle object-fit-cover w-100 h-100" src="{{$doctor->user_image}}" alt="" />
                    </div>
                    <div class="lh-1">
                        <h2 class="doctor_name lh-1 fw-bolder">Dr. {{ $doctor->name }}<br class="line_break d-none"> {{ $doctor->last_name }}</h2>
                        <h5 class="doctor_designation lh-1 fw-normal">
                            {{ $doctor->specializations->name }}
                        </h5>
                        <h5 class="doctor_degree doctor_designation lh-1 fw-normal fs-6">
                            {!! nl2br(isset($doctor->details->education)?$doctor->details->education:"No data available") !!}
                        </h5>
                        <div class="ratings d-flex gap-2 align-items-center">
                                @php
                                    $fullStars = floor($doctor->rating / 20); // Number of full stars
                                    $halfStar = ($doctor->rating % 20 >= 10) ? 1 : 0; // Check if a half-star is needed
                                    $emptyStars = 5 - ($fullStars + $halfStar); // Remaining stars will be empty
                                @endphp
                                @for ($i = 0; $i < $fullStars; $i++)
                                    <i class="fa-solid fa-star"></i>
                                @endfor
                                @if ($halfStar)
                                    <i class="fa-solid fa-star-half-alt"></i>
                                @endif
                                @for ($i = 0; $i < $emptyStars; $i++)
                                    <i class="fa-regular fa-star"></i>
                                @endfor

                            <p class="profile_comments fw-normal">(356)</p>
                        </div>
                    </div>
                </div>
                <div>
                    <h3>Short Bio</h3>
                    <ul class="bio_points flex flex-column gap-2 align-items-start">
                        {{ $doctor->bio }}
                        {{-- <button class="btn fw-bold text-primary p-0 py-2 border-0">
                            Read More
                        </button> --}}
                    </ul>
                </div>
                <div class="profile_services">
                    <div class="profile_icon d-flex align-items-center gap-2">
                        <div class="icon_container rounded-circle d-flex p-2 x bg-primary">
                            <i class="fa-solid fa-hospital-user fs-4 p-1"></i>
                        </div>
                        <h3>Certifications and Licensing</h3>
                    </div>
                    <div class="row gy-3 gx-4 m-3 profile_service">
                        @if (isset($doctor->details->certificates))
                            @foreach ($doctor->details->certificates as $item)
                                <div class="col-md-6 col-12">
                                    <div class="d-flex align-items-center gap-3 rounded-5 py-2 px-3">
                                        <i class="fa-solid fa-check text-primary"></i>
                                        <p>{{$item}}</p>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-md-6 col-12">
                                <div class="d-flex align-items-center gap-3 rounded-5 py-2 px-3">
                                    <i class="fa-solid fa-check text-primary"></i>
                                    <p>No Data Available</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="licensing">
                    <div class="profile_icon d-flex align-items-center gap-2">
                        <div class="icon_container rounded-circle d-flex p-2 x bg-primary">
                            <i class="fa-solid fa-stamp fs-4 p-1"></i>
                        </div>
                        <h3>Conditions Treated</h3>
                    </div>
                    <div class="row gy-3 gx-4 m-3 profile_service">
                        @if (isset($doctor->details->conditions))
                            @foreach ($doctor->details->conditions as $item)
                                <div class="col-md-6 col-12">
                                    <div class="d-flex align-items-center gap-3 rounded-5 py-2 px-3">
                                        <i class="fa-solid fa-check text-primary"></i>
                                        <p>{{$item}}</p>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-md-6 col-12">
                                <div class="d-flex align-items-center gap-3 rounded-5 py-2 px-3">
                                    <i class="fa-solid fa-check text-primary"></i>
                                    <p>No Data Available</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="d-flex gap-3 mt-3 mt-md-0 gap-md-5 col-12 col-md-4 flex-md-column flex-column-reverse">
                <div class="doctor_info rounded-4 d-flex flex-column gap-2">
                    <h3 class="ps-4 pt-4 pr-4"><u>About the Doctor</u></h3>
                    <div class="doctor_experience d-flex flex-column gap-3">
                        <div class="d-flex gap-2 align-items-baseline ps-4 pe-4">
                            <i class="fa-solid fa-user-plus"></i>
                            <div class="">
                                {{ isset($doctor->details->about)?$doctor->details->about:"No data available" }}
                            </div>
                        </div>
                        <div class="d-flex gap-2 align-items-baseline ps-4 pe-4">
                            <i class="fa-solid fa-location-dot"></i>
                            <div class="ps-2">
                                {{ isset($doctor->details->location)?$doctor->details->location:"No data available"}}
                            </div>
                        </div>
                        <div class="d-flex gap-2 my-3 align-items-baseline ps-4 pe-4">
                            <i class="fa-regular fa-comment-dots"></i>
                            <div class="ps-1">
                                @if ($doctor->rating != null)
                                    <h6>{{$doctor->rating}}% Recommended</h6>
                                @endif
                            </div>
                        </div>
                        <div
                            class="appointment_btn btn btn-primary d-flex align-items-center gap-2 justify-content-center rounded-top-0 w-100 rounded-bottom-4">
                            <button class="py-2 bg-transparent border-0 text-white">
                                Book Appointment Now
                            </button>
                            <i class="fa-solid fa-arrow-right-long"></i>
                        </div>
                    </div>
                </div>
                <div class="doctor_services">
                    <div class="profile_icon d-flex align-items-center gap-2">
                        <div class="icon_container rounded-circle d-flex p-2 x bg-primary">
                            <i class="fa-solid fa-hospital-user fs-4 p-1"></i>
                        </div>
                        <h3>Services</h3>
                    </div>
                    <div class="row gy-3 gx-4 m-3 profile_service">
                        @if (isset($doctor->details->procedures))
                            @foreach ($doctor->details->procedures as $item)
                                <div class="col-12">
                                    <div class="d-flex align-items-center gap-3 rounded-5 py-2 px-3">
                                        <i class="fa-solid fa-check text-primary"></i>
                                        <p>{{$item}}</p>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-md-6 col-12">
                                <div class="d-flex align-items-center gap-3 rounded-5 py-2 px-3">
                                    <i class="fa-solid fa-check text-primary"></i>
                                    <p>No Data Available</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
