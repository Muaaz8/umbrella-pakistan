@extends('layouts.dashboard_patient')
@section('meta_tags')
<!-- Required meta tags -->
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
<style>
    h5{
        text-decoration: underline;
    }
</style>

@endsection

@section('page_title')
<title>CHCC - Doctor Profile</title>
@endsection

@section('top_import_file')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
</script>
@endsection


@section('bottom_import_file')
<script>
    <?php header("Access-Control-Allow-Origin: *"); ?>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
</script>
<script src="{{asset('assets\js\doctor_dashboard_script\book_appointment.js?n=1')}}"></script>
<script>

 $(document).ready(function () {
  const mobileScreen = window.matchMedia("(max-width: 990px )");
  const urlParams = new URLSearchParams(window.location.search);
  const appointmentDate = urlParams.get('date');

  if (appointmentDate) {
    bookAppointmentModal({{ $doc->id }}, {{ auth()->user()->id }});
    $('#d2').val(appointmentDate).trigger('change');
  }

  $(".dashboard-nav-dropdown-toggle").click(function () {
    $(this)
      .closest(".dashboard-nav-dropdown")
      .toggleClass("show")
      .find(".dashboard-nav-dropdown")
      .removeClass("show");
    $(this).parent().siblings().removeClass("show");
  });

  $(".menu-toggle").click(function () {
    if (mobileScreen.matches) {
      $(".dashboard-nav").toggleClass("mobile-show");
    } else {
      $(".dashboard").toggleClass("dashboard-compact");
    }
  });
});



</script>
@endsection

@section('content')

<div class="dashboard-content">
    <div class="container">
        <div class="row align-items-center mb-3 mx-0">
            <div class="col-md-4">
                <div>
                    <button class="location__back__BTN" onclick="window.location.href='{{url()->previous()}}'"><i
                            class="fa-solid fa-arrow-left"></i> Back</button>
                </div>
            </div>
            <div class="col-md-8">
            </div>
        </div>
        <div class="row profile-row-wrapper m-auto">
            <div class="col-md-5">
                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="profile-box bg-white">
                            <div class="d-flex flex-column align-items-center">
                                <img class="photo" src="{{$doc->user_image}}" alt="" />
                                <p class="fw-bold h4 mt-3">Dr.{{$doc->name.' '.$doc->last_name}}</p>
                            </div>
                            <div class="">
                                <div class="p-2 text-center">
                                    <div class="about-doctor">
                                        <div class="d-flex flex-column mb-2">
                                            <span class="heading d-block">{{$doc->specializations->name}}</span>
                                        </div>
                                        <div class="d-flex flex-column mb-2">
                                            @if($doc->bio == null)
                                            <p>No Bio Data</p>
                                            @else
                                            <p>{{$doc->bio}}</p>
                                            @endif
                                        </div>
                                        <div class="general d-flex justify-content-center">
                                            @if($doc->rating > 0)
                                            <div class="star-ratings">
                                                <div class="fill-ratings" style="width: {{$doc->rating}}%;">
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
                                        </div>
                                        <div class="d-flex align-items-center justify-content-center mb-2">
                                            <span class="heading d-block px-3">Status: </span>
                                            @if($doc->status=='online')
                                            <span class="subheadings">
                                                <i class="fa fa-circle" style="color:green;"></i>
                                                {{ $doc->status}}</span>
                                            @else
                                            <span class="subheadings">
                                                <i class="fa fa-circle" style="color:red;"></i> {{$doc->status}}</span>
                                            @endif
                                        </div>
                                        <div class="appoint-btn"><button type="button" class="btn btn-primary"
                                                onclick="bookAppointmentModal({{ $doc->id }},{{ auth()->user() }})">
                                                Book Appointment </button>
                                        </div>

                                        <!-- <div class="modal-doc-book">
                                    <button>BOOK</button>
                                    </div> -->

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="row">
                    <div class="col-12 mb-3 bg-white profile-box">
                        <div class="py-2">
                            <div>
                                <h5>Education:</h5>
                                @if($doc->details->education == null)
                                <p>No Education Data</p>
                                @else
                                <p>{!! nl2br(isset($doc->details->education)?$doc->details->education:"No data
                                    available")
                                    !!}</p>
                                @endif
                            </div>
                        </div>
                        <div class="py-2">
                            <div>
                                <h5>Certifications and Licensing:</h5>
                                <div class="row gy-1 gx-2 m-3 profile_service">
                                    @if (isset($doc->details->certificates))
                                    @foreach ($doc->details->certificates as $item)
                                    <div class="col-md-6 col-12">
                                        <div class="d-flex align-items-center gap-1 rounded-5">
                                            <i class="fa-solid fa-check text-primary"></i>
                                            <p>{{$item}}</p>
                                        </div>
                                    </div>
                                    @endforeach
                                    @else
                                    <div class="col-md-6 col-12">
                                        <div class="d-flex align-items-center gap-1 rounded-5 py-2 px-3">
                                            <i class="fa-solid fa-check text-primary"></i>
                                            <p>No Data Available</p>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="py-2">
                            <h5>Conditions:</h5>
                            <div class="row gy-1 gx-2 m-3 profile_service">
                                @if (isset($doc->details->conditions))
                                @foreach ($doc->details->conditions as $item)
                                <div class="col-md-6 col-12">
                                    <div class="d-flex align-items-center gap-1 rounded-5">
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
            </div>
        </div>
    </div>
</div>
</div>
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
                                        <span class="subheadings"><input type="date" name="" id="app-date"></span>
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
@endsection
