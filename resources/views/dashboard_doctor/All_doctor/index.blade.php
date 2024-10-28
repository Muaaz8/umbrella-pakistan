@extends('layouts.dashboard_doctor')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>UHCS - All Doctors</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
@endsection

@section('content')
    <div class="dashboard-content">
        <div class="container-fluid">
            <div class="col-md-12">
                <div class="row m-auto all-doc-wrap">
                    <h3 class="pb-2">All Doctors</h3>
                    @foreach ($doctors as $doctor)
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="additional">
                                <div class="user-card">
                                    <img src="{{ $doctor->user_image }}" alt="" />
                                </div>
                            </div>

                            <div class="general">
                                <h4>Dr. {{ucfirst( $doctor->name)." ".ucfirst( $doctor->last_name) }}</h4>
                                <h6>{{ $doctor->spec }}</h6>
                                <p>
                                    {{ $doctor->rating }}<i class="fa-solid fa-star"></i> <sub>Rating</sub>
                                </p>

                                <div class="appoint-btn"><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal"> Book Appointment </button></div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>


        <!-- ////////////////// MODAL ///////////// -->

        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            Appointment Form
                        </h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="fa fa-close"></i></span>
                        </button>
                    </div>
                    <div class="modal-body doc-book-modal">
                        <div class="row g-0">
                            <div class="col-md-8 border-right">
                                <div class="row m-auto">
                                    <div class="col-md-6 p-3">
                                        <h6>Patient Information</h6>
                                        <div class="d-flex flex-column mb-3">
                                            <span class="heading d-block">First Name</span>
                                            <span class="subheadings">haris</span>
                                        </div>

                                        <div class="d-flex flex-column mb-3">
                                            <span class="heading d-block">Last Name</span>
                                            <span class="subheadings">umar</span>
                                        </div>
                                        <div class="d-flex flex-column mb-3">
                                            <span class="heading d-block">Email</span>
                                            <span class="subheadings">haris@gmail.com</span>
                                        </div>

                                        <div class="d-flex flex-column mb-3">
                                            <span class="heading d-block">Phone</span>
                                            <span class="subheadings">09876543111</span>
                                        </div>
                                    </div>

                                    <div class="col-md-6 p-3">
                                        <h6>Appointment Information</h6>
                                        <div class="d-flex flex-column mb-3">
                                            <span class="heading d-block">Service Provider</span>
                                            <span class="subheadings"><select class="form-select"
                                                    aria-label="Default select example">
                                                    <option selected>Open this select menu</option>
                                                    <option value="1">One</option>
                                                    <option value="2">Two</option>
                                                    <option value="3">Three</option>
                                                </select></span>
                                        </div>

                                        <div class="d-flex flex-column mb-3">
                                            <span class="heading d-block">Symptoms</span>
                                            <span class="subheadings"><select class="form-select"
                                                    aria-label="Default select example">
                                                    <option selected>Open this select menu</option>
                                                    <option value="1">One</option>
                                                    <option value="2">Two</option>
                                                    <option value="3">Three</option>
                                                </select></span>
                                        </div>

                                        <div class="d-flex flex-column mb-3">
                                            <span class="heading d-block">Choose Appointment Date</span>
                                            <span class="subheadings"><input type="date" name=""
                                                    id=""></span>
                                        </div>

                                        <div class="d-flex flex-column mb-3">
                                            <span class="heading d-block">Choose Appointment Time</span>
                                            <span class="subheadings"><select class="form-select"
                                                    aria-label="Default select example">
                                                    <option selected>Open this select menu</option>
                                                    <option value="1">One</option>
                                                    <option value="2">Two</option>
                                                    <option value="3">Three</option>
                                                </select></span>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-2 text-center">
                                    <div class="profile">
                                        <img src="https://sehatghar-doctor-images.s3.amazonaws.com/image-1650700380419"
                                            width="100" class="rounded-circle img-thumbnail" />

                                        <span class="d-block mt-3 font-weight-bold">DR. ABDUL HADII</span>
                                    </div>

                                    <div class="about-doctor">
                                        <div class="d-flex flex-column mb-2">
                                            <span class="heading d-block">Primary Care</span>
                                        </div>

                                        <div class="d-flex flex-column mb-2">
                                            <span class="heading d-block">
                                                3.93<i class="fa-solid fa-star" style="color:orange ;"></i>
                                                <sub>Rating</sub>
                                            </span>
                                        </div>
                                        <div class="d-flex flex-column mb-2">
                                            <span class="heading d-block">Status</span>
                                            <span class="subheadings"><i class="dots"></i> Online</span>
                                        </div>

                                        <div class="d-flex flex-column modal-doc-book">
                                            <button>BOOK</button>
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
