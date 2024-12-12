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
                        <h2 class="doctor_name lh-1 fw-bolder">Dr. {{  }}<br class="line_break d-none"> Chowdhury</h2>
                        <h5 class="doctor_designation lh-1 fw-normal">
                            Cardiology Specialist
                        </h5>
                        <h5 class="doctor_degree doctor_designation lh-1 fw-normal fs-6">
                            M.B.B.S, M.C.P.S.
                        </h5>
                        <div class="ratings d-flex gap-2 align-items-center">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
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
                        <div class="col-md-6 col-12">
                            <div class="d-flex align-items-center gap-3 rounded-5 py-2 px-3">
                                <i class="fa-solid fa-check text-primary"></i>
                                <p>Orthopedic Consultation</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="d-flex align-items-center gap-3 rounded-5 py-2 px-3">
                                <i class="fa-solid fa-check text-primary"></i>
                                <p>Delivery Blocks</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="d-flex align-items-center gap-3 rounded-5 py-2 px-3">
                                <i class="fa-solid fa-check text-primary"></i>
                                <p>Ultrasound Injections</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="d-flex align-items-center gap-3 rounded-5 py-2 px-3">
                                <i class="fa-solid fa-check text-primary"></i>
                                <p>Something</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="d-flex align-items-center gap-3 rounded-5 py-2 px-3">
                                <i class="fa-solid fa-check text-primary"></i>
                                <p>Something</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="d-flex align-items-center gap-3 rounded-5 py-2 px-3">
                                <i class="fa-solid fa-check text-primary"></i>
                                <p>Something</p>
                            </div>
                        </div>
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
                        <div class="col-md-6 col-12">
                            <div class="d-flex align-items-center gap-3 rounded-5 py-2 px-3">
                                <i class="fa-solid fa-check text-primary"></i>
                                <p>Lorem, ipsum dolor.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-3 mt-3 mt-md-0 gap-md-5 col-12 col-md-4 flex-md-column flex-column-reverse">
                <div class="doctor_info rounded-4 d-flex flex-column gap-2">
                    <h3 class="ps-4 pt-4 pr-4"><u>About the Doctor</u></h3>
                    <div class="doctor_experience d-flex flex-column gap-3">
                        <div class="d-flex gap-2 align-items-baseline ps-4 pe-4">
                            <i class="fa-solid fa-location-dot"></i>
                            <div class="ps-2">
                                <h6>10 Years of Experience</h6>
                                <p class="doctor_exp_text">
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                    Sed, earum.
                                </p>
                            </div>
                        </div>
                        <div class="d-flex gap-2 my-3 align-items-baseline ps-4 pe-4">
                            <i class="fa-regular fa-comment-dots"></i>
                            <div class="ps-1">
                                <h6>85% Recommended</h6>
                                <p class="doctor_exp_text">
                                    358 patients would recommend this doctor to their friends
                                    and family
                                </p>
                            </div>
                        </div>
                        <div class="d-flex gap-2 align-items-baseline ps-4 pe-4">
                            <i class="fa-solid fa-user-plus"></i>
                            <div class="">
                                <h6>Online Consultations</h6>
                                <p class="doctor_exp_text">
                                    The consultation is possible both onsite and online.
                                </p>
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
                        <div class="col-12">
                            <div class="d-flex align-items-center gap-3 rounded-5 py-2 px-3">
                                <i class="fa-solid fa-check text-primary"></i>
                                <p>Orthopedic Consultation</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center gap-3 rounded-5 py-2 px-3">
                                <i class="fa-solid fa-check text-primary"></i>
                                <p>Delivery Blocks</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center gap-3 rounded-5 py-2 px-3">
                                <i class="fa-solid fa-check text-primary"></i>
                                <p>Ultrasound Injections</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center gap-3 rounded-5 py-2 px-3">
                                <i class="fa-solid fa-check text-primary"></i>
                                <p>Something</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center gap-3 rounded-5 py-2 px-3">
                                <i class="fa-solid fa-check text-primary"></i>
                                <p>Something</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center gap-3 rounded-5 py-2 px-3">
                                <i class="fa-solid fa-check text-primary"></i>
                                <p>Something</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
