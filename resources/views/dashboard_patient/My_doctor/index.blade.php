@extends('layouts.dashboard_patient')

@section('meta_tags')
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
<title>CHCC - My Doctors</title>
@endsection

@section('top_import_file')
<style>
    .payment-method {
        cursor: pointer;
        border: 2px solid #ddd;
        border-radius: 10px;
        padding: 15px;
        text-align: center;
        transition: 0.3s;
    }

    .payment-method:hover,
    .payment-method.active {
        border-color: #007bff;
        background-color: #f8f9fa;
    }

    .payment-method h5 {
        font-size: 14px;
        font-weight: 700;
        margin: 0;
    }

    .icon {
        height: 35px;
        object-fit: cover;
    }

    #submit_btn:disabled {
        background-color: #82a2e7;
        color: #000;
    }
</style>
@endsection


@section('bottom_import_file')
<script>
    <?php header("Access-Control-Allow-Origin: *"); ?>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

        $(".payment-method").click(function () {
            $(".payment-method").removeClass("active");
            $(this).addClass("active");

            let selectedMethod = $(this).data("method");

            $("#submit_btn").attr("disabled", false);
            $("#payment_method").val(selectedMethod);
        });


</script>
<script src="{{asset('assets\js\doctor_dashboard_script\book_appointment.js?n=1')}}"></script>
@endsection

@section('content')
{{-- {{ dd($doctors) }} --}}
<div class="dashboard-content">
    <div class="container-fluid">
        <div class="col-md-12">
            <div class="row m-auto all-doc-wrap">
                <h3 class="pb-2">My Doctors</h3>
                @forelse ($doctors as $doctor)
                <div class="col-md-4 col-lg-3 col-sm-6 mb-3">
                    <div class="card">
                        <div class="additional">
                            <div class="user-card">
                                <img src="{{ $doctor->user_image }}" alt=""
                                    onclick="window.location.href='/view/doctor/{{$doctor->id}}'" />
                            </div>
                        </div>

                        <div class="general">
                            <h4 onclick="window.location.href='/view/doctor/{{$doctor->id}}'">Dr. {{ucfirst(
                                $doctor->name)." ".ucfirst( $doctor->last_name) }}</h4>
                            <h6>{{ $doctor->sp_name }}</h6>
                            @if($doctor->rating > 0)
                            <div class="star-ratings">
                                <div class="fill-ratings" style="width: {{$doctor->rating}}%;">
                                    <span>★★★★★</span>
                                </div>
                                <div class="empty-ratings">
                                    <span>★★★★★</span>
                                </div>
                            </div>
                            @else
                            <div class="star-ratings">
                                <div class="fill-ratings" style="width: 0%;">
                                    <span>★★★★★</span>
                                </div>
                                <div class="empty-ratings">
                                    <span>★★★★★</span>
                                </div>
                            </div>
                            @endif
                            <div class="appoint-btn"><button type="button" class="btn btn-primary"
                                    onclick="window.location.href='/view/doctor/{{\Crypt::encrypt($doctor->id)}}'"> View
                                    Profile </button>
                                <button type="button" class="btn btn-primary"
                                    onclick="bookAppointmentModal({{ $doctor->id }},{{ auth()->user() }})"> Book
                                    Appointment </button>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center for-empty-div">
                    <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                    <h6> No Doctors</h6>
                </div>
                @endforelse
                <div class="row d-flex justify-content-center">
                    <div class="paginateCounter">
                        {{ $doctors->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="no_date" tabindex="-1" aria-labelledby="no_dateLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="no_dateLabel">No Date Available <i class="fas fa-sad-cry"></i></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="ask_change_status-modal-body text-dark p-5">
                        Oops... Sorry, There is No available date of this doctor...!!!
                        You can select other doctor...!!!
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">Ok</button>
                </div>
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/create_appointment" method="POST">
                    @csrf
                    <div class="modal-body doc-book-modal" id="load_bookappointment">
                        <div class="row g-0">
                            <div class="col-md-8 border-right">
                                <div class="row m-auto">
                                    <div class="col-md-6 p-3">
                                        <h6>Patient Information</h6>
                                        <div class="d-flex flex-column mb-3">
                                            <span class="heading d-block">First Name</span>
                                            <span id="pat_name" class="subheadings">haris</span>
                                        </div>

                                        <div class="d-flex flex-column mb-3">
                                            <span class="heading d-block">Last Name</span>
                                            <span id="pat_lastname" class="subheadings">umar</span>
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
                                            <span class="subheadings"><input type="date" name="" id=""></span>
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
    </form>
</div>
</div>
</div>
@endsection
