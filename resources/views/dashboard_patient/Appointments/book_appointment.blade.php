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
    <style>
        /* -----symptoms-Checker-Css-- */
        #heading {
            text-transform: uppercase;
            color: #673AB7;
            font-weight: normal
        }

        #msform {
            text-align: center;
            position: relative;
            margin-top: 20px
        }

        #msform fieldset {
            background: white;
            border: 0 none;
            border-radius: 0.5rem;
            box-sizing: border-box;
            width: 100%;
            margin: 0;
            padding-bottom: 20px;
            position: relative
        }

        .form-card {
            text-align: left
        }

        #msform fieldset:not(:first-of-type) {
            display: none
        }

        #msform .custom_input {
            padding: 8px 15px 8px 15px;
            border: 1px solid #ccc;
            border-radius: 0px;
            margin-bottom: 14px;
            margin-top: 2px;
            width: 100%;
            box-sizing: border-box;
            font-family: montserrat;
            color: #2C3E50;
            background-color: #ECEFF1;
            font-size: 16px;
            letter-spacing: 1px
        }

        #msform .custom_input:focus {
            -moz-box-shadow: none !important;
            -webkit-box-shadow: none !important;
            box-shadow: none !important;
            border: 1px solid #673AB7;
            outline-width: 0
        }

        #msform .action-button {
            width: 100px;
            background: #673AB7;
            font-weight: bold;
            color: white;
            border: 0 none;
            border-radius: 0px;
            cursor: pointer;
            padding: 10px 5px;
            margin: 10px 0px 10px 5px;
            float: right
        }

        #msform .action-button:hover,
        #msform .action-button:focus {
            background-color: #311B92
        }

        #msform .action-button-previous {
            width: 100px;
            background: #616161;
            font-weight: bold;
            color: white;
            border: 0 none;
            border-radius: 0px;
            cursor: pointer;
            padding: 10px 5px;
            margin: 10px 5px 10px 0px;
            float: right
        }

        #msform .action-button-previous:hover,
        #msform .action-button-previous:focus {
            background-color: #000000
        }

        .card {
            z-index: 0;
            border: none;
            position: relative
        }

        .fs-title {
            font-size: 25px;
            color: #673AB7;
            margin-bottom: 15px;
            font-weight: normal;
            text-align: left
        }

        .purple-text {
            color: #673AB7;
            font-weight: normal
        }

        .steps {
            font-size: 25px;
            color: gray;
            margin-bottom: 10px;
            font-weight: normal;
            text-align: right
        }

        .fieldlabels {
            color: gray;
            text-align: left
        }

        #progressbar {
            margin-bottom: 30px;
            overflow: hidden;
            color: lightgrey
        }

        #progressbar .active {
            color: #673AB7
        }

        #progressbar li {
            list-style-type: none;
            font-size: 15px;
            width: 25%;
            float: left;
            position: relative;
            font-weight: 400
        }

        #progressbar #account:before {
            font-family: FontAwesome;
            content: "\f13e"
        }

        #progressbar #personal:before {
            font-family: FontAwesome;
            content: "\f007"
        }

        #progressbar #payment:before {
            font-family: FontAwesome;
            content: "\f030"
        }

        #progressbar #confirm:before {
            font-family: FontAwesome;
            content: "\f00c"
        }

        #progressbar li:before {
            width: 50px;
            height: 50px;
            line-height: 45px;
            display: block;
            font-size: 20px;
            color: #ffffff;
            background: lightgray;
            border-radius: 50%;
            margin: 0 auto 10px auto;
            padding: 2px
        }

        #progressbar li:after {
            content: '';
            width: 100%;
            height: 2px;
            background: lightgray;
            position: absolute;
            left: 0;
            top: 25px;
            z-index: -1
        }

        #progressbar li.active:before,
        #progressbar li.active:after {
            background: #673AB7
        }

        .progress {
            height: 20px
        }

        .progress-bar {
            background-color: #673AB7
        }

        .fit-image {
            width: 100%;
            object-fit: cover
        }

        .right__user {
            display: flex;
            justify-content: end;
            gap: 14px;
            align-items: center;
        }

        .right__user_img {
            border-radius: 15px;
            width: 30px;
            height: 30px;
        }

        .chat__main__ {
            max-height: 180px;
            overflow-y: auto;
        }

        .message__div {
            display: flex;
            align-items: center;
            gap: 7px;
            margin-top: 10px;
        }

        .send_icon:hover {
            transform: scale(1.3);
            transition: 150ms ease-in;
            color: #08295a;
            font-weight: 600;
            cursor: pointer;
        }

        .left_p {
            background-color: #cecece;
            padding: 10px 19px;
            border-radius: 10px 10px 10px 0px;
            color: #000;
            text-align: left;
            max-width: 300px;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .right_p {
            background-color: #08295a;
            padding: 10px 19px;
            border-radius: 10px 10px 0px 10px;
            color: #fff;
            max-width: 300px;
        }

        .btn_finish {
            float: right;
            margin-top: 10px;
            padding: 10px;
            background: #08295a;
            border: 0px;
            border-radius: 5px;
            color: #ffff;
            width: 100px;
        }

        .is-invalid {
            border: 1px solid red !important;
        }

        .CEva {
            text-align: center;
            font-size: 17px;
            font-weight: 600;
            margin-bottom: 55px;
        }

        .btn-go {
            margin-top: 10px !important;
            color: #fff !important;
            background-color: #0d3a7e !important;
        }

        #send_button {
            border: none;
            padding: 6px 8px;
            outline: none;
            background: #08295a;
            color: #fff;
            border-radius: 5px;
        }
    </style>
    <script>
        $(document).ready(function() {
            var current_fs, next_fs, previous_fs; //fieldsets
            var opacity;
            var current = 1;
            var steps = $("fieldset").length;
            var msg;
            var questions = 1;
            var session_id = '';
            var flag = false;

            setProgressBar(current);
            $('.symptomsOpen').click(function(e) {
                e.preventDefault();
                $('#symptomsOpen').modal('show');
                var user = $(this).data('user');
                var doc_id = $(this).data('doctor');
                var sp_id = $(this).data('specialization');
                $('#symptomsOpen').find('.modal-body #doctorId').val(doc_id);
                $('#symptomsOpen').find('.modal-body #specializationId').val(sp_id);
            });
            $(".next").click(async function() {
                if (current == 1) {
                    var name = $('.symptom_checker_name').val();
                    var syemail = $('.symptom_checker_email').val();
                    var age = $('.symptom_checker_age').val();
                    var gender = $('.symptom_checker_gender').val();
                    var evisit = '2';
                    var price = $('#price').val();
                    var doctorId = $('#doctorId').val();
                    var specializationId = $('#specializationId').val();
                    $.ajax({
                        type: "POST",
                        url: "/symptom_checker_cookie_store",
                        async: false,
                        data: {
                            email: syemail,
                            name: name,
                            age: age,
                            gender: gender,
                            evisit: evisit,
                            price: price,
                            doctor_id: doctorId,
                            specialization_id: specializationId
                        },
                        success: function(response) {
                            if (response.errors) {
                                $.each(response.errors, function(key, value) {
                                    var element = $('.' + key);
                                    element.addClass('is-invalid');
                                    element.closest('.col-md-6').find(
                                        '.invalid-feedback').text(value);
                                });
                            } else {
                                flag = true;
                            }

                        }
                    });
                } else if (current == 2) {
                    if ($('.agreeCheckbox').is(':checked')) {
                        $('.symptom_checker_check_error').text('');
                        flag = true;
                    } else {
                        flag = false;
                        $('.symptom_checker_check_error').text('Please read and check to proceed.');
                    }
                } else if (current == 3) {
                    flag = true;
                    var evisit = '2';
                    var price = $('#price').val();
                    var doctorId = $('#doctorId').val();
                    var specializationId = $('#specializationId').val();
                    $.ajax({
                        type: "POST",
                        url: "/chat_done",
                        data: {
                            session_id: session_id,
                            evisit: evisit,
                            price: price,
                            doctorId: doctorId,
                            specializationId: specializationId,
                        },
                        beforeSend: function() {
                            $(".CEva_heading").addClass('d-none');
                            $(".HRep_heading").addClass('d-none');
                            $(".INote_heading").addClass('d-none');
                            $(".RAT_heading").addClass('d-none');
                            $(".conclusion_loader").removeClass('d-none');
                        },
                        success: function(response) {
                            if (response.auth == 0) {
                                var fullUrl = window.location.href;
                                html = ' <div class="modal-login-reg-btn my-3">' +
                                    '<a href="' + fullUrl +
                                    'patient_register"> REGISTER AS A PATIENT</a>' +
                                    '<a href="' + fullUrl +
                                    'doctor_register">REGISTER AS A DOCTOR </a>' +
                                    '</div>' +
                                    '<div class="login-or-sec">' +
                                    '<hr>' +
                                    'OR' +
                                    '<hr>' +
                                    '</div>' +
                                    '<div style="text-align: center;">' +
                                    '<p>Already have account?</p>' +
                                    '<a href="' + fullUrl + 'login">Login</a>' +
                                    '</div>';
                                $('.conclusions').html(html);
                                $('.next').addClass('d-none');
                                $('.previous').addClass('d-none');
                            } else {
                                $('.action-button').addClass('d-none');
                                $('.previous').addClass('d-none');
                                $(".conclusion_loader").addClass('d-none');
                                $(".CEva").html(
                                    '<center> Thank you for providing us information. Please proceed ahead.</center> <br> <center> <a href="#" class="btn btn-primary btn-go" onclick="bookAppointmentModal(' +
                                    response.doc_id + ')">Click Here</a> </center>');

                            }
                        },
                    });
                }
                if (flag) {
                    current_fs = $(this).parent();
                    next_fs = $(this).parent().next();
                    $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
                    next_fs.show();
                    current_fs.animate({
                        opacity: 0
                    }, {
                        step: function(now) {
                            opacity = 1 - now;
                            current_fs.css({
                                'display': 'none',
                                'position': 'relative'
                            });
                            next_fs.css({
                                'opacity': opacity
                            });
                        },
                        duration: 500
                    });
                    setProgressBar(++current);
                    flag = false;
                }
            });

            $(".previous").click(function() {

                current_fs = $(this).parent();
                previous_fs = $(this).parent().prev();

                //Remove class active
                $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

                //show the previous fieldset
                previous_fs.show();

                //hide the current fieldset with style
                current_fs.animate({
                    opacity: 0
                }, {
                    step: function(now) {
                        // for making fielset appear animation
                        opacity = 1 - now;

                        current_fs.css({
                            'display': 'none',
                            'position': 'relative'
                        });
                        previous_fs.css({
                            'opacity': opacity
                        });
                    },
                    duration: 500
                });
                setProgressBar(--current);
            });
            $("#send_button").click(function(e) {
                e.preventDefault();
                var answer = $('.chat_answer').val();
                var userImage =
                    '{{ auth()->check() ? \App\Helper::check_bucket_files_url(auth()->user()->user_image) : '../../assets/images/no_image.png' }}';
                $('.chat__main__').append('<div class="text-end justify-content-lg-start right__user">' +
                    '<img class="right__user_img" height="30" width="30" src="' + userImage +
                    '" alt="">' +
                    '<p class="left_p">' + answer + '</p></div>');
                $('.chat_answer').val('');
                $.ajax({
                    type: "POST",
                    url: "/symptom_chat",
                    data: {
                        message: answer,
                        session_id: session_id,
                    },
                    beforeSend: function() {
                        $(".loader").removeClass('d-none');
                        // $('#acceptIcon_'+order_id).html('<i class="fa fa-spinner fa-spin"></i>');
                    },
                    success: function(response) {
                        $(".loader").addClass('d-none');
                        $('.chat__main__').append('<div class="text-start right__user">' +
                            '<p class="right_p">' + response.response + '</p>' +
                            '<img class="right__user_img" height="30" width="30" src="{{ asset('') }}assets/images/doc__.jpg" alt=""></div>'
                        );
                        answer = $('.chat_answer').val('');
                        questions++;
                        session_id = response.session_id;
                        $('.chat__main__').animate({
                            scrollTop: $('.chat__main__')[0].scrollHeight
                        }, 'slow');
                    },
                    error: function(response) {}
                });
                if (questions >= 3) {
                    $('.skip').removeClass('d-none');
                }
                if (questions >= 8) {
                    $(".message__div").html('');
                    $(".message__div").html(
                        'Your Questions Limit has Completed!! Please click Next to view Conclusion.');
                    $('.chat_next_button').removeClass('d-none');
                }
            })

            function setProgressBar(curStep) {
                var percent = parseFloat(100 / steps) * curStep;
                percent = percent.toFixed();
                $(".progress-bar")
                    .css("width", percent + "%")
            }

            $(".submit").click(function() {
                return false;
            })
        });
    </script>
    <script>
        <?php header('Access-Control-Allow-Origin: *'); ?>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            var url = window.location.pathname;
            var spec_id = url.split('/')[3];
            $("#search_spec").val(spec_id);
        });

        function change_state() {
            var spec_id = "{{ $id }}";
            var loc_id = $('#location_id').val();
            var name = $('#opt_' + loc_id).text();
            $('#change_state_btn').attr('disabled', true);
            $('#change_state_btn').html('<i class="fa fa-spinner fa-spin"></i>Processing...');
            $.ajax({
                type: "POST",
                url: "/get/book/appointment/" + spec_id + "/" + loc_id + "",
                data: {
                    spec_id: spec_id,
                    loc_id: loc_id,
                },
                success: function(data) {
                    if (data == '1') {
                        $('#load_docs').html(
                            '<div class="No__SpeC_avai"><p>No Doctors Available in Selected State.</p></div>'
                        );
                        $('.spec__loCation').html(
                            '<div class="d-flex align-items-center state___sty spec__loCation">' +
                            '<p id="selected_state">' + name +
                            '</p><i class="fa-solid fa-sort-down pb-1"></i></div>');
                        $('#change_state_btn').attr('disabled', false);
                        $('#change_state_btn').html('UPDATE');
                        $('.btn-close').click();
                        //alert("Sorry This specialization is not availble in your desired state");
                    } else {
                        $('#loc_id').val(loc_id);
                        $('.spec__loCation').html(
                            '<div class="d-flex align-items-center state___sty spec__loCation">' +
                            '<p id="selected_state">' + name +
                            '</p><i class="fa-solid fa-sort-down pb-1"></i></div>');
                        $('#load_docs').html(data);
                        $('#change_state_btn').attr('disabled', false);
                        $('#change_state_btn').html('UPDATE');
                        $('.btn-close').click();
                    }
                }
            });
        }

        $('.spec__loCation').on('click', function() {
            $('#staticBackdrop').modal('show');
        });

        $('#search_form').on('submit', function() {
            var spec_id = "{{ $id }}";
            var loc_id = $('#location_id').val();
            $('#search_spec').val(spec_id);
            $('#search_loc').val(loc_id);
        });
    </script>

    <script src="{{ asset('assets\js\doctor_dashboard_script\book_appointment.js?n=1') }}"></script>
@endsection
@section('content')
    <div class="dashboard-content">
        <div class="container-fluid">
            <div class="col-md-12">
                <div class="row m-auto all-doc-wrap">
                    <div class="row align-items-center mb-3">
                        <div class="col-md-4">
                            <div>
                                <button class="location__back__BTN"
                                    onclick="window.location.href='{{ url()->previous() }}'"><i
                                        class="fa-solid fa-arrow-left"></i> Back</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <h3>All Doctors</h3>
                        </div>
                        <div class="col-md-4">
                            @if ($id == 21)
                                <form id="search_form" action="/psych/book/appointment/{{ $id }}" method="POST">
                                    @csrf
                                    <div class="input-group">
                                        <input type="hidden" name="spec_id" id="search_spec">
                                        <input type="hidden" name="loc_id" id="search_loc">
                                        <input type="text" id="search" name="name" class="form-control"
                                            placeholder="Search" aria-label="Username" aria-describedby="basic-addon1" />
                                        <button type="submit" class="btn process-pay"><i
                                                class="fa-solid fa-search"></i></button>
                                    </div>
                                </form>
                            @else
                                <form id="search_form" action="/book/appointment/{{ $id }}" method="POST">
                                    @csrf
                                    <div class="input-group">
                                        <input type="hidden" name="spec_id" id="search_spec">
                                        <input type="hidden" name="loc_id" id="search_loc">
                                        <input type="text" id="search" name="name" class="form-control"
                                            placeholder="Search" aria-label="Username" aria-describedby="basic-addon1" />
                                        <button type="submit" class="btn process-pay"><i
                                                class="fa-solid fa-search"></i></button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        @if (session()->get('error'))
                            <div id="errorDiv1" class="alert alert-danger col-12 col-md-6 offset-md-3">
                                @php
                                    $es = session()->get('error');
                                @endphp
                                <span role="alert"> <strong>{{ $es }}</strong></span>
                            </div>
                        @endif
                    </div>

                    <hr>
                    <div class="row clearfix" id="load_docs">
                        @forelse ($doctors as $doc)
                        @if ($doc->consultation_fee != null)
                            <div class="col-md-4 col-lg-3 col-sm-6 mb-3">
                                <div class="card">
                                    @if ($doc->title == 'Availability')
                                        <div class="shedule_tick">
                                            <span>
                                                <p>Schedule</p>
                                                <p>Available</p>
                                            </span>
                                        </div>
                                    @endif
                                    @if ($doc->flag != '')
                                        <div class="visited-doc-flag">
                                            {{ $doc->flag }}
                                        </div>
                                    @endif
                                    <div class="additional">
                                        <div class="user-card">
                                            <img src="{{ $doc->user_image }}" alt="" />
                                        </div>
                                    </div>

                                    <div class="general">
                                        <h4 class="fs-5">Dr. {{ $doc->name }} {{ $doc->last_name }}</h4>
                                        <h6 class="m-0">{{ $doc->sp_name }}</h6>
                                        <h6 class="m-0 all__doc__ini_pr pt-2"><span>Initial Price:</span>
                                            Rs. {{ $doc->consultation_fee }}</h6>
                                        @if ($doc->followup_fee != null)
                                            <h6 class="m-0 all__doc__ini_pr"><span>Follow-up Price:</span>
                                                Rs. {{ $doc->followup_fee }}</h6>
                                        @else
                                            <h6 class="m-0 all__doc__ini_pr"><span>Follow-up Price:</span>
                                                Rs. {{ $doc->consultation_fee }}</h6>
                                        @endif
                                        @if ($doc->rating > 0)
                                            <div class="star-ratings">
                                                <div class="fill-ratings" style="width: {{ $doc->rating }}%;">
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
                                                onclick="window.location.href='/view/doctor/{{ \Crypt::encrypt($doc->id) }}'">
                                                View Profile </button>
                                            {{-- <button type="button" class="btn btn-primary symptomsOpen"
                                                id="{{ $doc->id }}" data-user="{{ $user }}"
                                                data-doctor="{{ $doc->id }}"
                                                data-specialization="{{ $doc->specialization }}">
                                                Book Appointment
                                            </button> --}}
                                            <button type="button" class="btn btn-primary"
                                                onclick="bookAppointmentModal({{ $doc->id }})" data-price="{{ $doc->consultation_fee }}">
                                                Book Appointment
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
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

    </div>
    </div>
    </div>

    <!-- Modal-Select-Location-Start -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="p-4 text-center update_location_main">
                        <h4 class="upd_my_head">CHOOSE YOUR CURRENT STATE</h4>
                        <select id="location_id" class="form-select update_select" aria-label="Default select example">

                        </select>
                        <button type="button" id="change_state_btn" class="state_upd_btn"
                            onclick="change_state()">UPDATE</button>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal-Select-Location-End -->


    <div class="modal fade" id="symptomsOpen" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Automated Symptoms Checker</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding:20px !important;">
                    <div>
                        <div class="">
                            <div class="row justify-content-center p-0 m-0">
                                <div class=" text-center p-0">
                                    <div class="card px-0 ">
                                        <form id="msform">
                                            <fieldset>
                                                <div class="form-card">
                                                    <div class="row">
                                                        <div class="col-7">
                                                            <h2 class="fs-title">Patient Information:</h2>
                                                        </div>
                                                        <div class="col-5">
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label class="fieldlabels">Name: *</label>
                                                            <input class="custom_input symptom_checker_name name"
                                                                type="text" name="name" placeholder="Name"
                                                                value="{{ Auth::check() ? auth()->user()->name : '' }}"
                                                                required />
                                                            <small
                                                                class="text-danger symptom_checker_name_error invalid-feedback "></small>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="fieldlabels">Email: *</label>
                                                            <input class="custom_input symptom_checker_email email"
                                                                type="email" name="email" placeholder="Email"
                                                                value="{{ Auth::check() ? auth()->user()->email : '' }}"
                                                                required />
                                                            <small
                                                                class="text-danger symptom_checker_email_error invalid-feedback"></small>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="fieldlabels">Age: *</label>
                                                            @php
                                                                $dob = auth()->user()->date_of_birth;
                                                                if ($dob) {
                                                                    $formattedDob = Carbon\Carbon::parse($dob)->format(
                                                                        'Y-m-d',
                                                                    );
                                                                    $age = Carbon\Carbon::parse($formattedDob)->age;
                                                                } else {
                                                                    // Handle the case where the date of birth is not set
                                                                    $age = '';
                                                                }
                                                            @endphp
                                                            <input class="custom_input symptom_checker_age age"
                                                                type="age" name="text"
                                                                placeholder="Please enter your age"
                                                                value="{{ $age }}" required />
                                                            <small
                                                                class="text-danger symptom_checker_age_error invalid-feedback"></small>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="fieldlabels">Gender: *</label>
                                                            <select name="gender"
                                                                class="custom_input symptom_checker_gender gender">
                                                                <option selected disabled> Select Gender </option>
                                                                <option value="male"
                                                                    {{ auth()->user()->gender == 'male' ? 'selected' : '' }}>
                                                                    Male </option>
                                                                <option value="female"
                                                                    {{ auth()->user()->gender == 'female' ? 'selected' : '' }}>
                                                                    Female </option>
                                                                <option value="other"
                                                                    {{ auth()->user()->gender == 'other' ? 'selected' : '' }}>
                                                                    Other </option>
                                                            </select>
                                                            <small
                                                                class="text-danger symptom_checker_gender_error invalid-feedback"></small>
                                                        </div>
                                                        {{--<input type="hidden" id="price" name="price"
                                                            value="">--}}
                                                        <input type="hidden" id="doctorId" name="doctorId">
                                                        <input type="hidden" id="specializationId"
                                                            name="specializationId">

                                                    </div>

                                                </div> <input type="button" name="next" class="next action-button"
                                                    value="Next" />
                                            </fieldset>
                                            <fieldset>
                                                <div class="form-card">
                                                    <div class="row">
                                                        <div class="col-7">
                                                            <h2 class="fs-title">Disclaimer</h2>
                                                        </div>
                                                        <div class="col-5">
                                                        </div>
                                                    </div>

                                                    <div class="accordion-body border rounded-2">
                                                        <p style="text-align: justify;">
                                                            Kindly be aware that this tool is not designed to offer medical
                                                            advice.
                                                        </p><br>
                                                        <p style="text-align: justify;">
                                                            Our tool is not a substitute for professional medical advice,
                                                            diagnosis, or treatment. It is crucial to thoroughly review the
                                                            label of any over-the-counter (OTC) medications you may be
                                                            considering. The label provides information about active
                                                            ingredients and includes critical details such as potential drug
                                                            interactions and side effects. Always consult with your
                                                            physician or a qualified healthcare provider for any questions
                                                            regarding a medical condition. Never disregard professional
                                                            medical advice or delay seeking it due to information found on
                                                            our website. If you suspect a medical emergency, please contact
                                                            your doctor or call 911 without delay. Umbrella Health Care
                                                            Systems does not endorse or recommend specific products or
                                                            services. Any reliance on information provided by Umbrella
                                                            Health Care Systems is solely at your discretion and risk.
                                                        </p>
                                                    </div>
                                                    <input type="checkbox" id="agree" class="agreeCheckbox" required>
                                                    <label for="agree"> By checking this box, It is considered you have
                                                        read and agreed to the disclaimer.</label>
                                                    <small class="text-danger symptom_checker_check_error"></small>
                                                </div> <input type="button" name="next" class="next action-button"
                                                    value="Next" />
                                            </fieldset>
                                            <fieldset>
                                                <div>
                                                    <div class="chat__main__">
                                                        <div class="text-start right__user">
                                                            <p class="right_p">Hello, How may i help you today??</p>
                                                            <img class="right__user_img" height="30" width="30"
                                                                src="{{ asset('') }}assets/images/doc__.jpg"
                                                                alt="">
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <i class="loader fa fa-spinner fa-spin d-none"
                                                            style="font-size:35px;"></i>
                                                        <div class="message__div">
                                                            <input type="text" class="form-control chat_answer"
                                                                placeholder="Type symptoms...." name="answer">
                                                            <div>
                                                                <button type="submit" class="send_button"
                                                                    id="send_button"><i
                                                                        class="fa-regular fa-paper-plane me-0 send_icon send_button"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="button" name="next"
                                                    class="next action-button chat_next_button skip d-none"> Skip </button>
                                                <input type="button" name="next"
                                                    class="next action-button chat_next_button d-none" value="Next" />
                                            </fieldset>
                                            <fieldset>
                                                <div>
                                                    <div class="text-start conclusions">
                                                        <i class="conclusion_loader fa fa-spinner fa-spin d-none d-flex justify-content-center"
                                                            style="font-size:35px;"></i>
                                                        <h3 class="CEva_heading">Clinical Evaluation</h3>
                                                        <p class="CEva" style="text-align: justify;"></p>
                                                        <h3 class="HRep_heading">Hypothesis Report</h3>
                                                        <p class="HRep" style="text-align: justify;"></p>
                                                        <h3 class="INote_heading">Intake Notes</h3>
                                                        <p class="INote" style="text-align: justify;"></p>
                                                        <h3 class="RAT_heading">Referrals And Tests</h3>
                                                        <p class="RAT" style="text-align: justify;"></p>
                                                    </div>

                                                </div>
                                            </fieldset>
                                            <fieldset>
                                                <div class="form-card">
                                                    <div class="row">
                                                        <div class="col-7">
                                                            <h2 class="fs-title">Finish:</h2>
                                                        </div>
                                                        <div class="col-5">
                                                            <h2 class="steps">Step 4 - 4</h2>
                                                        </div>
                                                    </div> <br><br>
                                                    <h2 class="purple-text text-center"><strong>SUCCESS !</strong></h2>
                                                    <br>
                                                    <div class="row justify-content-center">
                                                        <div class="col-3"> <img src="https://i.imgur.com/GwStPmg.png"
                                                                class="fit-image"> </div>
                                                    </div> <br><br>
                                                    <div class="row justify-content-center">
                                                        <div class="col-7 text-center">
                                                            {{-- <h5 class="purple-text text-center">You Have Successfully Signed Up</h5> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </form>
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
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Appointment Form
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
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
                                            <span class="subheadings"><select class="js-select2"
                                                    aria-label="Default select example">
                                                    <option selected>Open this select menu</option>
                                                    <option value="1">One</option>
                                                    <option value="2">Two</option>
                                                    <option value="3">Three</option>
                                                </select></span>
                                        </div>
                                        <input type="hidden" id="price" name="price"    value="">
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
    </form>
@endsection
