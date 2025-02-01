@extends('layouts.dashboard_patient')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>CHCC - Online Doctors</title>
@endsection

@section('top_import_file')
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
@endsection


@section('bottom_import_file')
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
                    var evisit = '1';
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
                    var evisit = '1';
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
                                    '<center> Thank you for providing us information. Please proceed ahead.</center> <br> <center> <a href="' +
                                    response.route +
                                    '" class="btn btn-primary btn-go">Click Here</a> </center>'
                                    );

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
        $(".js-select2").select2({
            closeOnSelect: false,
            scrollAfterSelect: false,
            placeholder: "Enter Symptoms",
            allowHtml: true,
            allowClear: true,
            tags: true,
        }).on('select2:selecting', function(e) {
            var cur = e.params.args.data.id;
            var old = (e.target.value == '') ? [cur] : $(e.target).val().concat([cur]);
            $(e.target).val(old).trigger('change');
            $(e.params.args.originalEvent.currentTarget).attr('aria-selected', 'true');
            return false;
        });
    </script>
    <script src="{{ asset('/js/app.js') }}"></script>
    <script>
        <?php header('Access-Control-Allow-Origin: *'); ?>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            let doc_count = "{{ count($doctors) }}";
            let spec_id = "{{ $id }}";
            if (doc_count == 0) {
                $.ajax({
                    type: "POST",
                    url: "/send/doctors/online/alert",
                    data: {
                        spec_id: spec_id,
                        loc_id: loc_id,
                    },
                    success: function(data) {

                    }
                });
            }
        });

        function inquiryform(doc_id) {
            var pricetemp = $(doc_id).data('price');
            var doc_id = $(doc_id).attr('id');
            var sp_id = $("#sp_id" + doc_id).val();

            $("#doc_id").val(doc_id);
            $("#doc_sp_id").val(sp_id);
            $('#price').val(pricetemp);
            $('#inquiryModal').modal('show');
        }

        function newinquiryform(doc_id) {
            // alert('ok');
            var doc_id = $(doc_id).attr('id');
            var sp_id = $("#sp_id" + doc_id).val();

            $("#new_doc_id").val(doc_id);
            $("#new_doc_sp_id").val(sp_id);
            $('#exampleModal').modal('show');
        }

        $('#submit_btn').click(function() {
            var temp = "";
            // alert($('.select2-selection__rendered'));
            // console.log($('.select2-selection__rendered'));
            // if ($('#s1').is(":checked") || $('#s2').is(":checked") || $('#s3').is(":checked") || $('#s4').is(
            //         ":checked") || $('#s5').is(":checked")) {
            //     return true;
            // } else {
            //     $('#submit_btn').type = '';
            //     alert("Error: Please select atleast one of these symptoms");
            //     return false;
            // }
            $('#sympt').val(temp);
        });

        $('#inqury_form').submit(function() {
            $('#buton').attr('disabled', true);
            var element = $(".butn");
            // element.addClass("buttonload");
            element.html('<i class="fa fa-spinner fa-spin"></i>Processing...');
        });

        Echo.channel('load-online-doctor')
            .listen('loadOnlineDoctor', (e) => {
                var spec_id = "{{ $id }}";
                var loc_id = $('#location_id').val();
                //var url = "/get/patient/online/doctors/"+spec_id+"/"+loc_id+"";
                $.ajax({
                    type: "POST",
                    url: "/get/patient/online/doctors/" + spec_id,
                    data: {
                        spec_id: spec_id,
                        loc_id: loc_id,
                    },
                    success: function(data) {
                        if (data == '1') {
                            $('#load_OnlineDoctors').html(
                                '<div class="No__SpeC_avai"><p>No Doctors Available in Selected State.</p></div>'
                                );
                        } else {
                            $('#load_OnlineDoctors').html(data);
                        }
                    }
                });
                //$('#load_OnlineDoctors').load(url);
            });

        function change_state() {
            var spec_id = "{{ $id }}";
            var loc_id = $('#location_id').val();
            var name = $('#opt_' + loc_id).text();
            $('#change_state_btn').attr('disabled', true);
            $('#change_state_btn').html('<i class="fa fa-spinner fa-spin"></i>Processing...');
            $.ajax({
                type: "POST",
                url: "/get/patient/online/doctors/" + spec_id + "/" + loc_id + "",
                data: {
                    spec_id: spec_id,
                    loc_id: loc_id,
                },
                success: function(data) {
                    if (data == '1') {
                        $('#load_OnlineDoctors').html(
                            '<div class="No__SpeC_avai"><p>No Doctors Available in Selected State.</p></div>'
                            );
                        // $('.spec__loCation').html(name+' <i class="fa-solid fa-sort-down"></i>');
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
                        // $('.spec__loCation').html('<div class="spec__loCation meet__new d-flex justify-content-center meet_select_loca d-flex">'
                        // +'<p id="selected_state">'+name+'</p><i class="fa-solid fa-sort-down pt-1"></i></div>');
                        $('.spec__loCation').html(
                            '<div class="d-flex align-items-center state___sty spec__loCation">' +
                            '<p id="selected_state">' + name +
                            '</p><i class="fa-solid fa-sort-down pb-1"></i></div>');
                        $('#load_OnlineDoctors').html(data);
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
    </script>
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
                    @if (session()->get('error'))
                        <div id="errorDiv1" class="alert alert-danger col-12 col-md-6 offset-md-3">
                            @php
                                $es = session()->get('error');
                            @endphp
                            <span role="alert"> <strong>{{ $es }}</strong></span>
                        </div>
                    @endif
                    <h3 class="pb-2">Online Doctors</h3>
                    <input type="hidden" id="load_online_doctors"
                        value="{{ route('load_online_doctors', ['id' => $id]) }}">
                    <div class="row clearfix" id="load_OnlineDoctors">
                        @forelse ($doctors as $doctor)
                            <div class="col-md-4 col-lg-3 col-sm-6 mb-3">
                                <div class="card">
                                    <div class="additional">
                                        <div class="user-card">
                                            <img src="{{ $doctor->user_image }}" alt="" />
                                        </div>
                                    </div>

                                    <div class="general">
                                        <h4 class="fs-5">Dr.
                                            {{ ucfirst($doctor->name) . ' ' . ucfirst($doctor->last_name) }}
                                        </h4>
                                        <h6 class="m-0">{{ $doctor->sp_name }}</h6>
                                        <h6 class="m-0 all__doc__ini_pr pt-2"><span>Initial Price:</span>
                                            Rs. {{ $doctor->consultation_fee }}</h6>
                                        @if ($doctor->followup_fee != null)
                                            <h6 class="m-0 all__doc__ini_pr"><span>Follow-up Price:</span>
                                                Rs. {{ $doctor->followup_fee }}</h6>
                                        @endif
                                        <input type="hidden" id="sp_id{{ $doctor->id }}"
                                            value="{{ $doctor->specialization }}">
                                        @if ($doctor->rating > 0)
                                            <div class="star-ratings">
                                                <div class="fill-ratings" style="width: {{ $doctor->rating }}%;">
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
                                        <div class="appoint-btn">
                                            <button type="button" class="btn btn-primary"
                                                onclick="window.location.href='/view/doctor/{{ \Crypt::encrypt($doctor->id) }}'">
                                                View
                                                Profile </button>
                                            <button id="{{ $doctor->id }}" class="btn btn-primary"
                                                onclick="inquiryform(this)" data-price="{{ $doctor->consultation_fee }}">
                                                TALK TO DOCTOR
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            @if ($id == 21)
                                <p class="mx-5" style="margin-left: 1rem!important; font-size: 22px;">No Doctor Available.
                                    You
                                    can set an appointment <a href="/psych/book/appointment/{{ $id }}">here</a>
                                </p>
                            @else
                                <p class="mx-5" style="margin-left: 1rem!important; font-size: 22px;">No Doctor Available.
                                    You
                                    can set an appointment <a href="/book/appointment/{{ $id }}">here</a></p>
                            @endif
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

        <div class="modal fade" id="inquiryModal" data-bs-backdrop="static" data-bs-keyboard="false" style="font-weight: normal; " tabindex="-1" role="dialog">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="symp">Inquiry Form<br>
                        <small>Please fill this form to continue</small></h5>
                    </div>
                    <form action="{{route('patient_inquiry_store')}}" method="POST" class="p-3">
                        @csrf
                        <div class="modal-body" style="height: 150px;">
                            <input type="hidden" id="price" name="price" value="">
                            <div class="">
                                <input type="hidden" id="doc_sp_id" name="doc_sp_id">
                                <input type="hidden" name="doc_id" id="doc_id">
                                <input type='hidden' value="0" id='sympt' name='sympt'>
                            </div>
                            <div>
                                <h6>Symptoms</h6>
                                <textarea required="" rows="6" id="symp_text" name="problem" class="form-control no-resize" placeholder="Add Symptoms..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer mt-5">
                                <button type="submit" name="submit_btn" id="submit_btn" class="btn btn-link waves-effect location__back__BTN" style="border:none; padding:10px;">SUBMIT</button> &nbsp;
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Modal -->
        {{--        <div class="modal fade" id="symptomsOpen" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
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
                                                                        $formattedDob = Carbon\Carbon::parse(
                                                                            $dob,
                                                                        )->format('Y-m-d');
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
                                                            @if (isset($session))
                                                                <input type="hidden" id="price" name="price"
                                                                    value="{{ $doctor->consultation_fee }}">
                                                            @else
                                                                <input type="hidden" id="price" name="price"
                                                                    value="{{ $doctor->consultation_fee }}">
                                                            @endif
                                                            <input type="hidden" id="doctorId" name="doctorId">
                                                            <input type="hidden" id="specializationId"
                                                                name="specializationId">

                                                        </div>

                                                    </div> <input type="button" name="next"
                                                        class="next action-button" value="Next" />
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
                                                                Kindly be aware that this tool is not designed to offer
                                                                medical advice.
                                                            </p><br>
                                                            <p style="text-align: justify;">
                                                                Our tool is not a substitute for professional medical
                                                                advice, diagnosis, or treatment. It is crucial to thoroughly
                                                                review the label of any over-the-counter (OTC) medications
                                                                you may be considering. The label provides information about
                                                                active ingredients and includes critical details such as
                                                                potential drug interactions and side effects. Always consult
                                                                with your physician or a qualified healthcare provider for
                                                                any questions regarding a medical condition. Never disregard
                                                                professional medical advice or delay seeking it due to
                                                                information found on our website. If you suspect a medical
                                                                emergency, please contact your doctor or call 911 without
                                                                delay. Community Healthcare Clinics does not endorse or
                                                                recommend specific products or services. Any reliance on
                                                                information provided by Community Healthcare Clinics is
                                                                solely at your discretion and risk.
                                                            </p>
                                                        </div>
                                                        <input type="checkbox" id="agree" class="agreeCheckbox"
                                                            required>
                                                        <label for="agree"> By checking this box, It is considered you
                                                            have read and agreed to the disclaimer.</label>
                                                        <small class="text-danger symptom_checker_check_error"></small>
                                                    </div> <input type="button" name="next"
                                                        class="next action-button" value="Next" />
                                                </fieldset>
                                                <fieldset>
                                                    <div>
                                                        <div class="chat__main__">
                                                            <div class="text-start right__user">
                                                                <p class="right_p">Hello, How may i help you today??</p>
                                                                <img class="right__user_img" height="30"
                                                                    width="30"
                                                                    src="{{ asset('') }}assets/images/doc__.jpg"
                                                                    alt="">
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <i class="loader fa fa-spinner fa-spin d-none"
                                                                style="font-size: 60px;"></i>
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
                                                        class="next action-button chat_next_button skip d-none"> Skip
                                                    </button>
                                                    <input type="button" name="next"
                                                        class="next action-button chat_next_button d-none"
                                                        value="Next" />
                                                </fieldset>
                                                <fieldset>
                                                    <div>
                                                        <div class="text-start conclusions">
                                                            <i class="conclusion_loader fa fa-spinner fa-spin d-none d-flex justify-content-center"
                                                                style="font-size: 60px;"></i>
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
                                                            <div class="col-3"> <img
                                                                    src="https://i.imgur.com/GwStPmg.png"
                                                                    class="fit-image"> </div>
                                                        </div> <br><br>
                                                        <div class="row justify-content-center">
                                                            <div class="col-7 text-center">
                                                                <h5 class="purple-text text-center">You Have Successfully Signed Up</h5>
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
        </div>--}}
        <!--del_model_inqueryform.blade.php -->
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
                            {{--@foreach ($states as $loc)
                                @if ($loc->id == $loc_id)
                                    <option id="opt_{{ $loc->id }}" value="{{ $loc->id }}" selected>
                                        {{ $loc->name }}</option>
                                @else
                                    <option id="opt_{{ $loc->id }}" value="{{ $loc->id }}">
                                        {{ $loc->name }}</option>
                                @endif
                            @endforeach--}}
                        </select>
                        <button type="button" id="change_state_btn" class="state_upd_btn"
                            onclick="change_state()">UPDATE</button>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal-Select-Location-End -->
@endsection
