@extends('layouts.dashboard_patient')
@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection
@section('top_import_file')
@endsection
@section('page_title')
    <title>UHCS - Patient Dashboard</title>
@endsection
@section('bottom_import_file')
<script>
    <?php header('Access-Control-Allow-Origin: *'); ?>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<script src="{{ asset('assets/js/chat_support.js ')}}"></script>
<!-- <script>
    var btn1 = $('#fixed-chat');

$(window).scroll(function() {
  if ($(window).scrollTop() > 300) {
    btn1.addClass('show');
  } else {
    btn1.removeClass('show');
  }
});
</script> -->
    <script>
        $(document).ready(function() {
            var id = "{{ $ses_id }}";
            if (id == 0) {
                return false;
            } else {
                $('#session_id').val(id);
                var fed = "{{ $ses_feed }}";
                if (fed == '') {
                    $("#rating_modal").modal('show');
                }
            }
        });

        $('#fsa').click(function(event) {
            event.preventDefault();
            var div = $(this).parent().parent();
            var icon = $(this).attr('class');
            id = icon.split(" ");
            id = id[id.length - 1];
            console.log(id, div);
            $.ajax({
                type: "get",
                url: "/session/reminder/" + id,
                beforeSend: function() {
                    $('.cross-icon').html('');
                    $('.cross-icon').html('<i class="fa fa-spinner fa-spin"></i>');
                },
                success: function(response) {
                    div.remove();
                    var num = $('.reminder_span').html();
                    num = num - 1;
                    $('.reminder_span').html(num);
                    if(num == 0){
                        $('#flush-collapseOne').removeClass('show');
                    }
                }
            });
            return false;
        });

        $('.emoji').click(function(e) {
            e.preventDefault();
            $('.emoji').removeClass('emoji-content-rate');
            $(this).addClass('emoji-content-rate');
        });

        $("#st1").click(function() {
            $(".fa-star").css("color", "black");
            $("#st1").css("color", "#ffa500");
            var rate = $(this).attr('id');
            rate = rate.substring(2, 3);
            $('#rate').val(rate);
        });
        $("#st2").click(function() {
            $(".fa-star").css("color", "black");
            $("#st1, #st2").css("color", "#ffa500");
            var rate = $(this).attr('id');
            rate = rate.substring(2, 3);
            $('#rate').val(rate);
        });
        $("#st3").click(function() {
            $(".fa-star").css("color", "black")
            $("#st1, #st2, #st3").css("color", "#ffa500");
            var rate = $(this).attr('id');
            rate = rate.substring(2, 3);
            $('#rate').val(rate);
        });
        $("#st4").click(function() {
            $(".fa-star").css("color", "black");
            $("#st1, #st2, #st3, #st4").css("color", "#ffa500");
            var rate = $(this).attr('id');
            rate = rate.substring(2, 3);
            $('#rate').val(rate);
        });
        $("#st5").click(function() {
            $(".fa-star").css("color", "black");
            $("#st1, #st2, #st3, #st4, #st5").css("color", "#ffa500");
            var rate = $(this).attr('id');
            rate = rate.substring(2, 3);
            $('#rate').val(rate);
        });
    </script>
@endsection
@section('content')
    {{-- {{ dd($user,$doctors,$labOrders,$imagingOrders,$reports) }} --}}
    <div class="dashboard-content">
        <div class="container-fluid">
            <div class="row m-auto">
                <div class="col-md-12">
                    <div class="card first-card-wrap">
                        <div class="card-body">
                            <div class="first-card-content">
                                <p>Welcome to</p>
                                <h1>Community Health Care Clinics</h1>
                            </div>
                            <div class="first-card-img-div">
                                <img src="{{ asset('assets/images/logoAll.png') }}" alt="" height="auto" width="200">
                            </div>
                        </div>


                    </div>
                </div>
            </div>
            <div class="row m-auto">
                <div class="col-md-12 mt-3">
                    @if ($med_profile == 0)
                        <div class="alert alert-warning d-flex align-items-center" role="alert">
                            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img"
                                aria-label="Info:">
                                <use xlink:href="#info-fill" />
                            </svg>
                            <div>
                                Please add your medical history
                            </div>
                            <button onclick="window.location.href='/patient/medical/profile'"
                                class="btn-primary rounded-pill patient-warn-add-btn">Add</button>
                        </div>
                    @endif
                    @if (Session::has('success'))
                        <div class="alert alert-success">
                            <ul>
                                <li>{!! \Session::get('success') !!}</li>
                            </ul>
                        </div>
                    @elseif (Session::has('error'))
                        <div class="alert alert-danger">
                            <ul>
                                <li>{!! \Session::get('error') !!}</li>
                            </ul>
                        </div>
                    @endif
                    <div class="accordion accordion-flush" id="accordionFlushExample">
                        <div class="accordion-item">
                            @if ($total_reminds == 0)
                                <h2 class="accordion-header" id="flush-headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapseOne" aria-expanded="false"
                                        aria-controls="flush-collapseOne">
                                        Reminders
                                        <div class="">
                                            <span style="--i:1" class="reminder_span">{{ $total_reminds }}</span>
                                        </div>
                                    </button>
                                </h2>
                                <div id="flush-collapseOne" class="accordion-collapse collapse"
                                    aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                </div>
                            @else
                                <h2 class="accordion-header" id="flush-headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapseOne" aria-expanded="true"
                                        aria-controls="flush-collapseOne">
                                        Reminder
                                        <div class="waviy">
                                            <span style="--i:1" class="reminder_span">{{ $total_reminds }}</span>
                                        </div>
                                        <!-- <span class="reminder_span divTAReviews">3</span> -->
                                    </button>
                                </h2>
                                <div id="flush-collapseOne" class="accordion-collapse collapse show"
                                    aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                                            <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
                                                <path
                                                    d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                            </symbol>
                                            <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
                                                <path
                                                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
                                            </symbol>
                                            <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
                                                <path
                                                    d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                            </symbol>
                                        </svg>
                                        @if ($pending_appoints != 0)
                                            <div onclick="window.location.href='/patient/appointments'"
                                                class="alert alert-primary d-flex align-items-center reminder-inner-div"
                                                role="alert">
                                                <svg class="bi flex-shrink-0 me-2" width="24" height="24"
                                                    role="img" aria-label="Info:">
                                                    <use xlink:href="#info-fill" />
                                                </svg>
                                                <div>
                                                    You have {{ $pending_appoints }} pending appointments
                                                </div>
                                            </div>
                                        @endif
                                        @if ($unread_reports != 0)
                                            <div onclick="window.location.href='/patient/lab/results'"
                                                class="alert alert-success d-flex align-items-center reminder-inner-div"
                                                role="alert">
                                                <svg class="bi flex-shrink-0 me-2" width="24" height="24"
                                                    role="img" aria-label="Success:">
                                                    <use xlink:href="#check-circle-fill" />
                                                </svg>
                                                <div>
                                                    You have {{ $unread_reports }} Unread Lab Reports
                                                </div>
                                            </div>
                                        @endif
                                        @if ($pending_sessions != null && $pending_sessions->reminder == null)
                                            {{-- {{ dd($pending_sessions) }} --}}
                                            <div onclick="window.location.href='/session/reminder/{{\Crypt::encrypt($pending_sessions->id)}}'"
                                                class="alert alert-danger d-flex align-items-center reminder-inner-div"
                                                role="alert">
                                                <svg class="bi flex-shrink-0 me-2" width="24" height="24"
                                                    role="img" aria-label="Danger:">
                                                    <use xlink:href="#exclamation-triangle-fill" />
                                                </svg>
                                                @if ($pending_sessions->status == 'pending')
                                                    <div>
                                                        You have an unpaid created session... click to continue
                                                    </div>
                                                @elseif($pending_sessions->status == 'paid')
                                                    <div>
                                                        You have a paid created session... click to continue
                                                    </div>
                                                @elseif($pending_sessions->status == 'invitation sent')
                                                    <div>
                                                        You have invited doctor for a session... click to continue
                                                    </div>
                                                @elseif($pending_sessions->status == 'cancel' && $pending_sessions->reminder == null)
                                                    <div>
                                                        Your session has been cancelled because you did not join it (3
                                                        times).
                                                    </div>
                                                @endif
                                                <div class="ms-auto cross-icon">
                                                    <i class="fa-solid fa-xmark fs-4 {{ \Crypt::encrypt($pending_sessions->id) }}"
                                                        id="fsa"></i>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>


                    <!-- <h4 class="pb-1">Reminder</h4> -->

                </div>
            </div>
            <div class="row m-auto">
                <div class="col-md-12">
                    <div class="row my-4">

                        <div class="col-md-6 col-sm-12 mb-3">
                            <a href="{{ route('patient_evisit_specialization') }}" class="text-dark">
                                <div class="dashboard-small-card-wrap dash-evisit-card">
                                    <div class="d-flex dashboard-small-card-inner align-items-center">
                                        <!-- <i class="fa-solid fa-user-doctor"></i> -->
                                        <img src="{{ asset('assets/images/video-call.png') }}" alt="">
                                        <div>
                                            <h5>Start E-Visit </h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-6 col-sm-12 mb-3">
                            <a href="{{ route('select_specialization') }}" class="text-dark">
                                <div class="dashboard-small-card-wrap dash-bookApp-card">
                                    <div class="d-flex dashboard-small-card-inner align-items-center">
                                        <img src="{{ asset('assets/images/booking.png') }}" alt="">
                                        <div>
                                            <h5>Book Appointment</h5>
                                        </div>

                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-6 col-sm-12 mb-3">
                            <a href="{{ route('labs') }}" class="text-dark">
                                <div class="dashboard-small-card-wrap dash-onlineLabs-card">
                                    <div class="d-flex dashboard-small-card-inner align-items-center">
                                        <img src="{{ asset('assets/images/lab.png') }}" alt="">
                                        <div>
                                            <h5>Order Online Labs</h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-6 col-sm-12 mb-3">
                            <a href="{{ route('pharmacy') }}" class="text-dark">
                                <div class="dashboard-small-card-wrap dash-evisit-card">
                                    <div class="d-flex dashboard-small-card-inner align-items-center">
                                        <!-- <i class="fa-solid fa-user-doctor"></i> -->
                                        <img src="{{ asset('assets/images/order-pharmacy.png') }}" alt="">
                                        <div>
                                            <h5>Order Pharmacy</h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-4 col-sm-6 mb-3">
                            <div class="dashboard-small-card-wrap">
                                <a href="{{ route('my_doctors') }}">
                                    <div class="d-flex dashboard-small-card-inner">
                                        <i class="fa-solid fa-user-doctor"></i>
                                        <div>
                                            <h6>My Doctors</h6>
                                            <p>{{ count($doctors) }}</p>
                                        </div>

                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 mb-3">
                            <div class="dashboard-small-card-wrap">
                                <a href="{{ route('patient_all_order') }}">
                                    <div class="d-flex dashboard-small-card-inner">
                                        <i class="fa-solid fa-cart-shopping"></i>
                                        <div>
                                            <h6>My Orders</h6>
                                            <p>{{ count($orders) }}</p>
                                        </div>

                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 mb-3">
                            <div class="dashboard-small-card-wrap">
                                <a href="{{ route('my_reports') }}">
                                    <div class="d-flex dashboard-small-card-inner">
                                        <i class="fa-solid fa-vials"></i>
                                        <div>
                                            <h6>My Reports</h6>
                                            <p>{{ count($reports) }}</p>
                                        </div>

                                    </div>
                                </a>
                            </div>
                        </div>
                        {{-- <div class="col-md-4 col-sm-6 mb-3">
                            <div class="dashboard-small-card-wrap">
                                    <a href="#">
                                    <div class="d-flex dashboard-small-card-inner">
                                        <i class="fa-solid fa-prescription-bottle-medical"></i>
                                        <div>
                                            <h6>My Pharmacy</h6>
                                            <p>0</p>
                                        </div>

                                    </div>
                                </a>
                                </div>
                        </div>
                        <div class="col-md-4 col-sm-6 mb-3">
                            <div class="dashboard-small-card-wrap">
                                    <a href="#">
                                    <div class="d-flex dashboard-small-card-inner">
                                        <i class="fa-solid fa-flask"></i>
                                        <div>
                                            <h6>My Labs</h6>
                                            <p>{{ count($labOrders) }}</p>
                                        </div>

                                    </div>
                                </a>
                                </div>
                        </div>
                        <div class="col-md-4 col-sm-6 mb-3">
                            <div class="dashboard-small-card-wrap">
                                    <a href="#">
                                    <div class="d-flex dashboard-small-card-inner">
                                        <i class="fa-solid fa-x-ray"></i>
                                        <div>
                                            <h6>My Imagings</h6>
                                            <p>{{ count($imagingOrders) }}</p>
                                        </div>

                                    </div>
                                </a>
                                </div>
                        </div> --}}
                    </div>
                </div>
            </div>



<!-- Chatbot -->
<div class="botIcon" id="fixed-chat">
    <i id="pop" class="position-absolute" style="color:red;"></i>
    <div class="botIconContainer">
        <div class="iconInner">
            <i class="fa fa-commenting" aria-hidden="true"></i>
        </div>
    </div>
    <div class="Layout Layout-open Layout-expand Layout-right">
        <div class="Messenger_messenger">
            <div class="Messenger_header">
            <span class="avtr"><figure style="background-image: url(https://demo.umbrellamd-video.com/assets/images/umbrella.png)"></figure></span>
                <h4 class="Messenger_prompt">&nbsp;&nbsp;How can we help you?</h4>
                <span class="chat_close_icon"
                    ><i class="fa fa-window-close" aria-hidden="true"></i
                ></span>
            </div>
            <div class="Messenger_content">
                <div class="Messages">
                    <div class="Messages_list">
                        @foreach($chat as $ch)
                        @if($ch->from == $user->id)
                        <div class="msg user"><span class="avtr">
                            <figure style="background-image: url('{{$user->user_image}}')"></figure></span><span class="responsText">{{$ch->message}}</span></div>
                        @else
                        <div class="msg"><span class="avtr"><figure style="background-image: url(https://demo.umbrellamd-video.com/assets/images/logo.png)"></figure></span><span class="responsText">{{$ch->message}}</span></div>
                        @endif
                        @endforeach
                    </div>
                </div>
                <form id="messenger">
                    <div class="Input Input-blank">
                        <input
                            id="msg"
                            name="msg"
                            class="Input_field"
                            placeholder="Send a message..."
                        />
                        <button
                            type="submit"
                            class="Input_button Input_button-send"
                        >
                            <div class="Icon">
                                <svg
                                    viewBox="1496 193 57 54"
                                    version="1.1"
                                    xmlns="http://www.w3.org/2000/svg"
                                    xmlns:xlink="http://www.w3.org/1999/xlink"
                                >
                                    <g
                                        id="Group-9-Copy-3"
                                        stroke="none"
                                        stroke-width="1"
                                        fill="none"
                                        fill-rule="evenodd"
                                        transform="translate(1523.000000, 220.000000) rotate(-270.000000) translate(-1523.000000, -220.000000) translate(1499.000000, 193.000000)"
                                    >
                                        <path
                                            d="M5.42994667,44.5306122 L16.5955554,44.5306122 L21.049938,20.423658 C21.6518463,17.1661523 26.3121212,17.1441362 26.9447801,20.3958097 L31.6405465,44.5306122 L42.5313185,44.5306122 L23.9806326,7.0871633 L5.42994667,44.5306122 Z M22.0420732,48.0757124 C21.779222,49.4982538 20.5386331,50.5306122 19.0920112,50.5306122 L1.59009899,50.5306122 C-1.20169244,50.5306122 -2.87079654,47.7697069 -1.64625638,45.2980459 L20.8461928,-0.101616237 C22.1967178,-2.8275701 25.7710778,-2.81438868 27.1150723,-0.101616237 L49.6075215,45.2980459 C5.08414042,47.7885641 49.1422456,50.5306122 46.3613062,50.5306122 L29.1679835,50.5306122 C27.7320366,50.5306122 26.4974445,49.5130766 26.2232033,48.1035608 L24.0760553,37.0678766 L22.0420732,48.0757124 Z"
                                            id="sendicon"
                                            fill="#96AAB4"
                                            fill-rule="nonzero"
                                        ></path>
                                    </g>
                                </svg>
                            </div>
                        </button>
                    </div>
                        </form>
            </div>
        </div>
    </div>
</div>
<!-- Chatbot -->



            <!-- <div class="row m-auto">
          <div class="col-md-12">
            <h4 class="pb-1">Upcoming Appointments</h4>
            <div class="wallet-table">
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">Doctor Name</th>
                    <th scope="col">Symptoms</th>
                    <th scope="col">Date</th>
                    <th scope="col">Time</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th scope="row">baqir raza</th>
                    <td>Headache Flu</td>
                    <td>2022-08-11</td>
                    <td>07:00:am</td>
                    <td>
                      <label class="order-progress">Cancelled</label>
                    </td>
                    <td></td>
                  </tr>
                  <tr>
                    <th scope="row">baqir raza</th>
                    <td>Headache Flu</td>
                    <td>2022-08-11</td>
                    <td>07:00:am</td>
                    <td><label class="order-paid">Rechedule</label></td>
                    <td><button class="join-btn">Join</button></td>
                  </tr>
                  <tr>
                    <th scope="row">baqir raza</th>
                    <td>Headache Flu</td>
                    <td>2022-08-11</td>
                    <td>07:00:am</td>
                    <td><label class="order-pending">Pending</label></td>
                    <td>
                      <button class="join-btn btn-danger">Cancel</button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div> -->

        </div>
    </div>

    <!-- ------------------Rating-Modal-Modal-start------------------ -->

    <!-- Modal -->
    <div class="modal fade" id="rating_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Last Session Feedback</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form action="{{ route('new_feedback_submit') }}" method="post">
                        @csrf
                        <div class="p-3">
                            <div class="row">
                                <div class="modal-heading-content">
                                    <h6>How satisfied are you with the quality of service provided</h6>
                                </div>
                                <div class="fs-2 emoji-content">
                                    <a href="#"><i class="fa-solid fa-face-angry emoji"></i></a>
                                    <a href="#"><i class="fa-solid fa-face-frown emoji"></i></a>
                                    <a href="#"><i class="fa-solid fa-face-meh emoji"></i></a>
                                    <a href="#"><i class="fa-sharp fa-solid fa-face-smile emoji"></i></a>
                                    <a href="#"><i class="fa-solid fa-face-laugh-beam emoji"></i></a>

                                </div>
                            </div>
                            <input type="hidden" name="rate" id='rate' value="">
                            <input type="hidden" name="session_id" id='session_id' value="">
                            <div class="row mt-4">
                                <div class="modal-heading-content">
                                    <h6>Rate Your Doctor</h6>
                                </div>
                                <div class="fs-2 emoji-content">
                                    <a href="#"><i class="fa-regular fa-star star" id="st1"></i></a>
                                    <a href="#"><i class="fa-regular fa-star star" id="st2"></i></a>
                                    <a href="#"><i class="fa-regular fa-star star" id="st3"></i></a>
                                    <a href="#"><i class="fa-regular fa-star star" id="st4"></i></a>
                                    <a href="#"><i class="fa-regular fa-star star" id="st5"></i></a>

                                    <p><input type="checkbox" name="sug_box_1" class="mt-3" checked>&nbsp; Doctor
                                        listened to my issue</input></p>
                                    <p><input type="checkbox" name="sug_box_2" class="mt-3" checked>&nbsp; Doctor guide
                                        me about treatment and alternatives</input></p>
                                    <p><input type="checkbox" name="sug_box_3" class="mt-3" checked>&nbsp; Quality of
                                        call was good</input></p>

                                    <input type="text" name="suggestion" class="form-control mt-2"
                                        placeholder="Write Any Suggestion Or Feedback">

                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn process-pay">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- ------------------Rating-Modal-Modal-end------------------ -->
@endsection
