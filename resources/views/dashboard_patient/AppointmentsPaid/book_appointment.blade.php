@extends('layouts.dashboard_patient')
@section('meta_tags')
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('top_import_file')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />

@endsection

@section('page_title')
    <title>CHCC - Book Appointment</title>
@endsection
@section('bottom_import_file')

    <script>
    <?php header("Access-Control-Allow-Origin: *"); ?>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

    $(document).ready(function () {
        var url = window.location.pathname;
        var spec_id = url.split('/')[3];
        $("#search_spec").val(spec_id);
    });

    //     function doctor_search(){
    //         var url = window.location.pathname;
    //         var spec_id = url.split('/')[3];
    //     }
    // $("#search").submit(function() {
    //    var input = $("#search").val();
    //    var url = window.location.pathname;
    //    var spec_id = url.split('/')[3];
    //    $.ajax({
    //     type: "POST",
    //     url: "/all/doctor/search",
    //     data: {
    //         id: spec_id,
    //         name: input,
    //     },
    //     success: function (response) {
    //         console.log(response);
    //         $("#doctor_cards").html('');
    //         $.each(response, function (index, value) {
    //             $("#doctor_cards").append('<div class="col-md-4 col-lg-3 col-sm-6 mb-3">'
    //             +'<div class="card"><div class="additional"><div class="user-card">'
    //             +'<img src="'+value.user_image+'" alt=""/>'
    //             +'</div></div><div class="general">'
    //             +'<h4 class="fs-5">Dr.'+  value.name+' ' + value.last_name +'</h4>'
    //             +'<h6 class="m-0">'+value.sp_name+'</h6><div class="appoint-btn">'
    //             +'<button type="button" class="btn btn-primary" onclick="window.location.href="/view/doctor/'+value.id+'"" > View Profile </button>'
    //             +'<button type="button" class="btn btn-primary" onclick="bookAppointmentModal('+value.id+','+value+'">'
    //             +'Book Appointment</button></div></div></div></div>');
    //         });
    //     }
    //    });
    // //    $.each(cards, function (index, value) {

    // //    });
    // });
    </script>

    <script src="{{asset('assets\js\doctor_dashboard_script\book_appointment.js?n=1')}}"></script>
@endsection
@section('content')
        <div class="dashboard-content">
          <div class="container-fluid">
            <div class="col-md-12">
              <div class="row m-auto all-doc-wrap">
              <div class="spec__loCation meet__new d-flex justify-content-center meet_select_loca d-flex">
                        <p id="selected_state">{{$state->name}}</p>
                        <i class="fa-solid fa-sort-down pt-1"></i>
                    </div>
                <div class="row">
                    <div class="col-md-8">
                        <h3 >All Doctors</h3>
                    </div>
                    <div class="col-md-4">
                        <form action="/paid/book/appointment/{{$id}}/{{$ses_id}}" method="GET">
                            @csrf
                            <input type="hidden" name="id" id="search_spec">
                            <input
                            type="text"
                            id="search"
                            name="name"
                            class="form-control"
                            placeholder="Search"
                            aria-label="Username"
                            aria-describedby="basic-addon1"/>
                        </form>
                    </div>
                </div>

                  <hr>
                @forelse ($doctors as $doc)
                    <div class="col-md-4 col-lg-3 col-sm-6 mb-3">
                    <div class="card">
                    @if($doc->title=='Availability')
                      <div class="shedule_tick">
                      <!-- <i class="fa-solid fa-clipboard-check"></i> -->
                      <span><p>Schedule</p><p>Available</p></span>
                      </div>
                      @endif
                        @if($doc->flag != '')
                        <!-- <div class="tdhhead">
                        <h2>{{ $doc->flag }}</h2>
                    </div> -->
                    <div class="visited-doc-flag">
                    {{ $doc->flag }}
                    </div>
                        <!-- <div class="red-doc">
                            <h6 class="m-0">{{ $doc->flag }}</h6>
                        </div> -->
                        @endif
                        <div class="additional">
                        <div class="user-card">
                            <img
                            src="{{ $doc->user_image }}"
                            alt=""
                            />
                        </div>
                        </div>

                        <div class="general">
                        <h4 class="fs-5">Dr. {{ $doc->name }} {{ $doc->last_name }}</h4>
                        <h6 class="m-0">{{ $doc->sp_name }}</h6>
                        <h6 class="m-0 all__doc__ini_pr pt-2"><span>Initial Price:</span> Rs. {{$price->initial_price}}</h6>
                        @if($price->follow_up_price != null)
                        <h6 class="m-0 all__doc__ini_pr"><span>Follow-up Price:</span> Rs. {{$price->follow_up_price}}</h6>
                        @else
                        <h6 class="m-0 all__doc__ini_pr"><span>Follow-up Price:</span> Rs. {{$price->initial_price}}</h6>
                        @endif
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
                        <div class="appoint-btn"
                            ><button type="button" class="btn btn-primary" onclick="window.location.href='/view/doctor/{{\Crypt::encrypt($doc->id)}}'" > View Profile </button>
                            <button
                            type="button"
                            class="btn btn-primary"
                            onclick="bookAppointmentModal({{ $doc->id }},{{ $user }})"
                            >
                            Book Appointment
                            </button>
                            </div
                        >
                        </div>
                    </div>
                    </div>
                @empty
                <h6 class="pb-2">No Available Doctor</h6>
                @endforelse
                <div class="row d-flex justify-content-center">
                    <div class="paginateCounter">
                        {{ $doctors->links('pagination::bootstrap-4') }}
                    </div>
                </div>
              </div>
            </div>
          </div>


<!-- ////////////////// MODAL ///////////// -->

          <div
            class="modal fade"
            id="exampleModal"
            tabindex="-1"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true"
          >
            <div class="modal-dialog modal-lg modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">
                    Appointment Form
                  </h5>
                  <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                  >
                    <!-- <span aria-hidden="true"><i class="fa fa-close"></i></span> -->
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
                                  <span id="pat_name" class="subheadings"
                                    >haris</span
                                  >
                                </div>

                                <div class="d-flex flex-column mb-3">
                                  <span class="heading d-block">Last Name</span>
                                  <span id="pat_lastname" class="subheadings"
                                    >umar</span
                                  >
                                </div>
                                <div class="d-flex flex-column mb-3">
                                  <span class="heading d-block"
                                    >Email</span
                                  >
                                  <span class="subheadings">haris@gmail.com</span>
                                </div>

                                <div class="d-flex flex-column mb-3">
                                  <span class="heading d-block"
                                    >Phone</span
                                  >
                                  <span class="subheadings"
                                    >09876543111</span
                                  >
                                </div>
                      </div>

                      <div class="col-md-6 p-3">
                        <h6>Appointment Information</h6>
                                <div class="d-flex flex-column mb-3">
                                  <span class="heading d-block">Service Provider</span>
                                  <span class="subheadings"
                                    ><select class="form-select" aria-label="Default select example">
                                      <option selected>Open this select menu</option>
                                      <option value="1">One</option>
                                      <option value="2">Two</option>
                                      <option value="3">Three</option>
                                    </select></span
                                  >
                                </div>


                                <div class="d-flex flex-column mb-3">
                                  <span class="heading d-block">Symptoms</span>
                                  <span class="subheadings"
                                    ><select class="js-select2" aria-label="Default select example">
                                      <option selected>Open this select menu</option>
                                      <option value="1">One</option>
                                      <option value="2">Two</option>
                                      <option value="3">Three</option>
                                    </select></span
                                  >
                                </div>

                                <div class="d-flex flex-column mb-3">
                                  <span class="heading d-block"
                                    >Choose Appointment Date</span
                                  >
                                  <span class="subheadings"><input type="date" name="" id=""></span>
                                </div>

                                <div class="d-flex flex-column mb-3">
                                  <span class="heading d-block"
                                    >Choose Appointment Time</span
                                  >
                                  <span class="subheadings"
                                    ><select class="form-select" aria-label="Default select example">
                                      <option selected>Open this select menu</option>
                                      <option value="1">One</option>
                                      <option value="2">Two</option>
                                      <option value="3">Three</option>
                                    </select></span
                                  >
                                </div>

                      </div>
                    </div>
                    </div>
                    <div class="col-md-4">
                      <div class="p-2 text-center">
                        <div class="profile">
                          <img
                            src="https://sehatghar-doctor-images.s3.amazonaws.com/image-1650700380419"
                            width="100"
                            class="rounded-circle img-thumbnail"
                          />

                          <span class="d-block mt-3 font-weight-bold"
                            >DR. ABDUL HADII</span
                          >
                        </div>

                        <div class="about-doctor">
                                  <div class="d-flex flex-column mb-2">
                                    <span class="heading d-block"
                                      >Primary Care</span
                                    >
                                  </div>

                                  <div class="d-flex flex-column mb-2">
                                    <span class="heading d-block"
                                      >
                                        3.93<i class="fa-solid fa-star" style="color:orange ;"></i> <sub>Rating</sub>
                                      </span
                                    >
                                  </div>
                                  <div class="d-flex flex-column mb-2">
                                    <span class="heading d-block">Status</span>
                                    <span class="subheadings"
                                      ><i class="dots"></i> Online</span
                                    >
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
