@extends('layouts.new_video_calling') @section('meta_tags')
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon" />
@endsection @section('page_title')
<title>CHCC - Doctor Video Calling</title>
@endsection @section('top_import_file')
<link rel="stylesheet" href="{{ asset('assets\css\doctor_video_calling.css') }}" />
@endsection @section('bottom_import_file') @php
header("Access-Control-Allow-Origin: *"); @endphp

<script src="{{ asset('/js/app.js') }}"></script>
<script src="https://download.agora.io/sdk/release/AgoraRTC_N-4.12.1.js"></script>
<script>
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    $(document).ready(function () {
        var session_id = "{{ $session->id }}";
        var doctor_id = "{{ $session->doctor_id }}";
        var patient_id = "{{ $session->patient_id }}";
        var remaining_time = "{{ $session->remaining_time }}";
        var channel = "{{ $session->channel }}";
        var appID = "{{ env('AGORA_APP_ID') }}";

        var sec = 0;
        if (remaining_time == "full") {
            sec = 15 * 60;
        } else {
            time = remaining_time;
            time_sp = time.split("m");
            min = time_sp[0];
            min = min * 60;
            sec_sp = time_sp[1].split(": ");
            sec_split = sec_sp[1].split("s");
            sec = parseInt(sec_split[0]) + parseInt(min);
        }
        $("div.spannerx").addClass("showx");
        $("div.overlayx").addClass("showx");
        CountDown(sec, session_id);
        // loadSymtoms(session_id);
        loadIsabelDiagnosis(session_id);
        loadPsychQuestion(session_id);
        currentMedication(patient_id);
        getSessionRecord(patient_id, session_id);
        getMedicalHistory(patient_id);
        getFamilyHistory(patient_id);
        onPageLoadPrescribeItemLoad(session_id);
        loadLabItems(session_id);
    });

    Echo.channel('load-prescribe-item-list')
        .listen('LoadPrescribeItemList', (e) => {
            var session_id = "{{ $session->id }}";
            var user_id = "{{ $session->patient_id }}";

            if (session_id == e.session_id && user_id == e.user_id) {

                $(".prescribed_items").html("");
                $.ajax({
                    type: "POST",
                    url: "{{URL('/get_prescribe_item_list')}}",
                    data: {
                        session_id: session_id,
                    },
                    success: function (products) {
                        $(".prescribed_items").html("");
                        if (products.length > 0) {
                            $.each(products, function (key, product) {

                                if (product.mode == "medicine") {
                                    $(".prescribed_items").append(
                                        '<span class="selected-value-bydoc">' +
                                        product.name +
                                        '<a onclick="med_remove(' + product.id + ')">' +
                                        '<i class="fa-solid fa-circle-xmark"></i>' +
                                        "</a>" +
                                        "</span>"
                                    );
                                } else if (product.mode == "lab-test") {
                                    $(".prescribed_items").append(
                                        '<span class="selected-value-bydoc">' +
                                        product.TEST_NAME +
                                        '<a onclick="lab_remove(' + product.TEST_CD + ')">' +
                                        '<i class="fa-solid fa-circle-xmark"></i>' +
                                        "</a>" +
                                        "</span>"
                                    );
                                } else if (product.mode == "imaging") {
                                    $(".prescribed_items").append(
                                        '<span class="selected-value-bydoc">' +
                                        product.TEST_NAME +
                                        '<a onclick="lab_remove(' + product.TEST_CD + ')">' +
                                        '<i class="fa-solid fa-circle-xmark"></i>' +
                                        "</a>" +
                                        "</span>"
                                    );
                                }
                            });
                        } else {
                            $(".prescribed_items").append(
                                '<span class="selected-value-bydoc">Not Found Any Prescribed Item !!</span>'
                            );
                        }
                    },
                });
            }
        });
    function med_remove(pro_id) {
        var session_id = "{{ $session->id }}";
        var user_id = "{{ $session->patient_id }}";
        $.ajax({
            type: 'POST',
            url: "{{URL('/delete_prescribe_item_from_session')}}",
            data: {
                pro_id: pro_id,
                type: 'medicine',
                session_id: session_id,
                user_id: user_id
            },
            success: function (result) {
                $('#med_' + pro_id).removeClass('selected_medi');
            }
        });
    }
    function img_remove(pro_id) {
        var session_id = "{{ $session->id }}";
        var user_id = "{{ $session->patient_id }}";
        $.ajax({
            type: 'POST',
            url: "{{URL('/delete_prescribe_item_from_session')}}",
            data: {
                pro_id: pro_id,
                type: 'imaging',
                session_id: session_id,
                user_id: user_id
            },
            success: function (result) {
                $('#img_' + pro_id).removeClass('selected_medi');
            }
        });
    }
    function lab_remove(pro_id) {
        var session_id = "{{ $session->id }}";
        var user_id = "{{ $session->patient_id }}";
        $.ajax({
            type: 'POST',
            url: "{{URL('/delete_prescribe_item_from_session')}}",
            data: {
                pro_id: pro_id,
                type: 'lab-test',
                session_id: session_id,
                user_id: user_id
            },
            success: function (result) {
                $("#" + pro_id).removeClass('selected_medi');
            }
        });
    }

    function CountDown(duration, session_id) {
        if (!isNaN(duration)) {
            var timer = duration,
                minutes,
                seconds;
            var interVal = setInterval(function () {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);
                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;
                $("#time").text(minutes + " minutes : " + seconds + " seconds");
                --timer;
                if (timer < 0) {
                    clearInterval(interVal);
                    $("#time").text("00 minutes : 00 seconds");
                    //open when go to production
                    $.ajax({
                        type: "POST",
                        url: "{{url('/doctor_end_session')}}",
                        data: {
                            id: session_id,
                        },
                        beforeSend: function () {
                            $(".videoLoaderDiv").css('display','flex');
                        },
                        success: function (msg) {
                            endCall();
                            leave();
                            $("#recommendationForm").submit();
                        },
                    });
                }
            }, 1000);
        }
    }
    // function loadSymtoms(session_id) {
    //     $("#loadSymtems").html("");
    //     $.ajax({
    //         type: "POST",
    //         url: "{{url('/load_symtems_video_page')}}",
    //         data: {
    //             id: session_id,
    //         },
    //         success: function (data) {
    //             console.log(data);
    //             $.each(data.symptoms_text, function (key, value) {
    //                 $("#loadSymtems").append("<li>" + value + "</li>");
    //             });
    //             if (data.description != "NaN") {
    //                 $("#loadSymtems").append(
    //                     "<li><b> Symptoms Description: </b>" + data.description + "</li>"
    //                 );
    //             }
    //         },
    //     });
    // }
    function loadIsabelDiagnosis(session_id) {
        $("#loadIsabelDiagnosis").html("");
        $.ajax({
            type: "POST",
            url: "/load_session_diagnosis",
            data: {
                id: session_id,
            },
            success: function (response) {
                if(response != 'null'){
                    console.log(response)
                    // $.each(response, function (key, value) {
                            $("#loadIsabelDiagnosis").append("<li><strong>Clinical Evaluation:</strong> " + response.clinical_evaluation +"</li>");
                            $("#loadIsabelDiagnosis").append("<li><strong>Hypothesis Report:</strong> " + response.hypothesis_report +"</li>");
                            $("#loadIsabelDiagnosis").append("<li><strong>Intake Notes:</strong> " + response.intake_notes+"</li>");
                            $("#loadIsabelDiagnosis").append("<li><strong>Referrals And Tests:</strong> " + response.referrals_and_tests +"</li>");
                    // });
                }else{
                    $("#flush-collapseSix").hide();
                    $("#flush-headingSix").hide();
                }
            }
        });

    }
    function loadPsychQuestion(session_id) {
        $("#loadPsychQuestion").html("");
        $.ajax({
            type: "POST",
            url: "/load_psych_question",
            data: {
                id: session_id,
            },
            success: function (response) {
                console.log(response);
                $.each(response.patient_health, function (key, value) {
                    console.log(value);
                    if(key != "question10"){
                        if (value == '0') {
                            $("#"+key).append("Answer: Not at all");
                        }else if(value == '1'){
                            $("#"+key).append("Answer: Several");
                        }else if(value == '2'){
                            $("#"+key).append("Answer: More than half the days");
                        }else if(value == '3'){
                            $("#"+key).append("Answer: Nearly every day");
                        }
                    }else{
                        var abc = key;
                        $("#"+abc).append("Answer: "+value+" difficult");
                    }
                });
                $.each(response.mood_disorder, function (key, value) {
                    if (value == 'y') {
                        $("#MD"+key).append("Answer: Yes");
                    }else if(value == 'n'){
                        $("#MD"+key).append("Answer: No");
                    }else{
                        $("#MD"+key).append("Answer: "+value);
                    }
                });
                $.each(response.anxiety_scale, function (key, value) {
                    if (value == '0') {
                        $("#"+key).append("Answer: Not present");
                    }else if(value == '1'){
                        $("#"+key).append("Answer: Mild");
                    }else if(value == '2'){
                        $("#"+key).append("Answer: Moderate");
                    }else if(value == '3'){
                        $("#"+key).append("Answer: Severe");
                    }else if(value == '4'){
                        $("#"+key).append("Answer: Very Severe");
                    }
                });
            }
        });
    }
    function currentMedication(patient_id) {
        $("#loadCurrentMedication").html("");
        $.ajax({
            type: "POST",
            url: "{{url('/load_current_medication_video_page')}}",
            data: {
                id: patient_id,
            },
            success: function (data) {
                temp = false;
                $.each(data, function (key, value) {
                    var myHtml = "";
                    $.each(value.mediciens, function (i, name) {
                        myHtml +=
                            '<div class="card-body"><p class="card-text">Name: <b>' +
                            name.pro_name +
                            '</b></p><p class="card-text">Status:<b class="text-capitalize">' +
                            name.status +
                            "</b></p></div>";
                    });
                    $("#loadCurrentMedication").append(
                        '<div class="card">' +
                        '<h5 class="card-header">Prescribed by: Dr.' +
                        value.prescrib_by +
                        "<span>Date: <b>" +
                        value.date +
                        "</b></span></h5>" +
                        myHtml +
                        "</div>"
                    );
                    temp = true;
                });
                if (temp == false){
                    $("#loadCurrentMedication").append(
                    "<tr'>No Medication History</tr>"
                );
                }
            },
        });
    }
    function getSessionRecord(patient_id, session_id) {
        $("#loadSessionRecords").html("");
        $.ajax({
            type: "POST",
            url: "{{url('/load_session_record_video_page')}}",
            data: {
                id: patient_id,
                session_id: session_id,
            },
            success: function (data) {
                console.log(data);
                if(data.length != 0){
                    var counter = 0;
                    $.each(data, function (key, value) {
                        var myHtml = "";
                        if (value.prescriptions != null) {
                            $.each(value.prescriptions, function (i, name) {
                                myHtml +=
                                    "<li class='pres_li'><span>" +
                                    name.pro_name +
                                    " </span>" +
                                    "<span><b>" +
                                    name.status +
                                    "</b></span></li>";
                            });
                        } else {
                            myHtml += "<li>not any item prescribed</li>";
                        }

                        $("#loadSessionRecords").append(
                            '<div class="visit-history-div-wrapper"><div class="visit-history-div">' +
                            "<div><p>Provider:<b>" +
                            value.provider +
                            "</b></p>" +
                            "<p>Date:<b>" +
                            value.date +
                            "</b></p></div>" +
                            "<div>" +
                            '<button onclick="detailToggle(' +
                            counter +
                            ')">View Details</button>' +
                            "</div>" +
                            "</div>" +
                            '<div class="col-12" id="myDIV' +
                            counter +
                            '" style="display: none">' +
                            '<div class="card mt-2" style="width: 100%" >' +
                            '<div class="card-header d-flex justify-content-between align-items-baseline">Session Details' +
                            '<i class="fa-regular fa-circle-xmark fs-4 text-danger ghh" onclick="detailToggle(' +
                            counter +
                            ')"></i>' +
                            "</div>" +
                            '<ul class="list-group list-group-flush">' +
                            // '<li class="list-group-item">' +
                            // "<b>Symptoms : </b>" +
                            // value.symtems +
                            // "</li>" +
                            // '<li class="list-group-item">' +
                            // "<b>Diagnosis : </b> " +
                            // value.diagnois +
                            // "</li>" +
                            '<li class="list-group-item">' +
                            "<b>Prescriptions :</b>" +
                            "<ul class='pres-ul'>" +
                            myHtml +
                            "</ul>" +
                            "</li>" +
                            '<li class="list-group-item">' +
                            " <b>Provider Notes : </b>" +
                            value.note +
                            "</li>" +
                            "</ul>" +
                            "</div>" +
                            "</div></div>"
                        );
                        counter++;
                    });
                }else{
                    $('#loadSessionRecords').append(
                        "<tr'>No Visit History</tr>"
                    );
                }
            },
        });
    }
    function getMedicalHistory(patient_id) {
        $("#loadMedicalRecord").html("");
        $.ajax({
            type: "POST",
            url: "{{url('/load_medical_history_video_page')}}",
            data: {
                id: patient_id,
            },
            success: function (data) {
                if (data.prev_symp != "" || data.prev_symp != null) {
                    temp = false;
                    $.each(data.prev_symp, function (key, value) {
                        if (value != null || value != " ") {
                            $("#loadMedicalRecord").append(
                                "<button>" + value + "</button>"
                            );
                            temp = true;
                        }
                    });
                    if (data.comment != null) {
                        $("#loadMedicalRecord").append(
                            "<p><b> Comment: </b>" + data.comment + "</p>"
                        );
                    }
                }
                if (temp == false){
                    $("#loadMedicalRecord").append(
                    "<tr'>No Medical Record</tr>"
                );
                }
            },
            error: function(jqXHR, textStatus, errorThrown){
                $("#loadMedicalRecord").append(
                    "<tr'>No Medical Record</tr>"
                );
            }
        });
    }
    function getFamilyHistory(patient_id) {
        $("#loadFamilyRecord").html("");
        $.ajax({
            type: "POST",
            url: "{{url('/load_family_history_video_page')}}",
            data: {
                id: patient_id,
            },
            success: function (data) {
                if (data != "" || data != null) {
                    temp = false;
                    $.each(data, function (key, value) {
                        $("#loadFamilyRecord").append(
                            "<tr>" +
                            "<td>" +
                            value.disease +
                            "</td>" +
                            "<td>" +
                            value.family +
                            "</td>" +
                            "<td>" +
                            value.age +
                            "</td>" +
                            "</tr>"
                        );
                        temp = true;
                    });
                }
                if (temp == false){
                        $("#loadFamilyRecord").append(
                        "<tr><td>No Family Record</td></tr>"
                    );
                }
            },
            error: function(jqXHR, textStatus, errorThrown){
                $("#loadFamilyRecord").append(
                    "<tr><td>No Family Record</td></tr>"
                );
            }
        });
    }
    function onPageLoadPrescribeItemLoad(session_id) {
        $(".prescribed_items").html("");
        $.ajax({
            type: "POST",
            url: "{{URL('/get_prescribe_item_list')}}",
            data: {
                session_id: session_id,
            },
            success: function (products) {
                if (products.length > 0) {
                    $.each(products, function (key, product) {

                        if (product.mode == "medicine") {
                            $(".prescribed_items").append(
                                '<span class="selected-value-bydoc">' +
                                product.name +
                                '<a onclick="med_remove(' + product.id + ')">' +
                                '<i class="fa-solid fa-circle-xmark"></i>' +
                                "</a>" +
                                "</span>"
                            );
                        } else if (product.mode == "lab-test") {
                            $(".prescribed_items").append(
                                '<span class="selected-value-bydoc">' +
                                product.TEST_NAME +
                                '<a onclick="lab_remove(' + product.TEST_CD + ')">' +
                                '<i class="fa-solid fa-circle-xmark"></i>' +
                                "</a>" +
                                "</span>"
                            );
                        } else if (product.mode == "imaging") {
                            $(".prescribed_items").append(
                                '<span class="selected-value-bydoc">' +
                                product.TEST_NAME +
                                '<a onclick="lab_remove(' + product.TEST_CD + ')">' +
                                '<i class="fa-solid fa-circle-xmark"></i>' +
                                "</a>" +
                                "</span>"
                            );
                        }
                    });
                } else {
                    $(".prescribed_items").append(
                        '<span class="selected-value-bydoc">Not Found Any Prescribed Item !!</span>'
                    );
                }
            },
        });
    }

    $("#search_cat").keyup(function () {
        var title = $(this).val();
        $('#results').html('');
        $.ajax({
            type: 'POST',
            url: "{{URL('/get_med_filtered_category')}}",
            data: {
                title: title,
            },
            success: function (response) {
                $('#results').html('');
                if (response.length != 0) {
                    $.each(response, function (key, value) {
                        $('#results').append('<div class="col-md-4">'
                            + '<button  title="' + value.title + '" '
                            + 'onclick="getMedicienByCategory(' + value.id + ')">' + value.title + '</button>'
                            + '</div>'
                        );
                    });
                } else {
                    $('#results').append('<div class="col-md-4">'
                        + '<button  title="">No Category found</button></div>'
                    );
                }
            }
        });
    });

    function getMedicienByCategory(med_id) {
        $('.loadMedicienProduct').html('');
        $('#selected_med_cat').val(med_id);
        var name = '';
        $.ajax({
            type: 'POST',
            url: "{{URL('/new_get_products_by_category')}}",
            data: {
                med_id: med_id,
                session_id: "{{ $session->id }}",
                name: name,
                type: 'medicine'
            },
            success: function (response) {
                if (response.length != 0) {
                    var type = 'med';
                    $.each(response, function (key, value) {
                        if (value.added == 'yes') {
                            $('.loadMedicienProduct').append(
                                '<div class="col-md-4 col-sm-6">' +
                                '<button title="' + value.name + '" class="selected_medi" onclick="javascript:void(0)">' + value.name + '</button>' +
                                '</div>'
                            );
                        } else {
                            $('.loadMedicienProduct').append(
                                '<div class="col-md-4 col-sm-6">' +
                                '<button title="' + value.name + '" id="med_' + value.id + '" onclick="add_med(' + value.id + ')">' + value.name + '</button>' +
                                '</div>'
                            );
                        }
                    });
                } else {

                    $('.loadMedicienProduct').append(
                        '<div class="col-md-4">' +
                        '<button onclick="javascript:void(0)">No products found in this category</button>' +
                        '</div>'
                    );
                }
            }
        });
        $(".medicine_category").stop().slideUp(300);
        $(".medicine_product").stop().slideToggle(300);

    }

    $("#search_med").keyup(function () {
        var med_id = $('#selected_med_cat').val();
        var name = $("#search_med").val();
        $('#loadMedicienProduct').html('');
        $.ajax({
            type: 'POST',
            url: "{{URL('/new_get_products_by_category')}}",
            data: {
                session_id: "{{ $session->id }}",
                med_id: med_id,
                name: name,
                type: 'medicine'
            },
            success: function (response) {
                $('#loadMedicienProduct').html('');
                if (response.length != 0) {
                    var type = 'med';
                    $.each(response, function (key, value) {
                        if (value.added == 'yes') {
                            $('#loadMedicienProduct').append(
                                '<div class="col-md-4 col-sm-6">' +
                                '<button title="' + value.name + '" class="selected_medi" onclick="javascript:void(0)">' + value.name + '</button>' +
                                '</div>'
                            );
                        } else {
                            $('#loadMedicienProduct').append(
                                '<div class="col-md-4 col-sm-6">' +
                                '<button title="' + value.name + '" id="med_' + value.id + '" onclick="add_med(' + value.id + ')">' + value.name + '</button>' +
                                '</div>'
                            );
                        }
                    });
                } else {

                    $('#loadMedicienProduct').append(
                        '<div class="col-md-4">' +
                        '<button onclick="javascript:void(0)">No products found in this category</button>' +
                        '</div>'
                    );
                }
            }
        });
        $(".medicine_category").stop().slideUp(300);

    });

    function loadLabItems() {
        var session_id = "{{ $session->id }}";
        var name = $('#Lab_search').val();
        $('#loadLabItems').html('');
        if (name == null || name == '') {
            name = '';
        }
        $.ajax({
            type: 'POST',
            url: "{{URL('/new_get_lab_products_video_page')}}",
            data: {
                id: session_id,
                name: name,
            },
            success: function (response) {
                $('#loadLabItems').html('');
                if (response.length != 0) {

                    $.each(response, function (key, value) {


                        if (value.added == 'yes') {
                            $('#loadLabItems').append(
                                '<div class="col-md-4 col-sm-6">' +
                                '<button title="' + value.TEST_NAME + '" class="selected_medi" onclick="javascript:void(0)">' + value.TEST_NAME + '</button>' +
                                '</div>'
                            );
                        } else {
                            $('#loadLabItems').append(
                                '<div class="col-md-4 col-sm-6">' +
                                '<button title="' + value.TEST_NAME + '" id="' + value.TEST_CD + '" onclick="checkAoes(' + value.TEST_CD + ')">' + value.TEST_NAME + '</button>' +
                                '</div>'
                            );
                        }



                    });
                } else {

                    $('#loadLabItems').append(
                        '<div class="col-md-4">' +
                        '<button onclick="javascript:void(0)">No products found </button>' +
                        '</div>'
                    );
                }
            }
        });
    }

    function referDoctor() {
        var spec = $('#specializations').val();
        var name = $('#refer_search').val();
        $('#load_specialization').html('<div class="text-center"><i class="fa fa-spinner fa-spin"></i>Loading...</div>');
        if (name == null || name == '') {
            name = '';
        }
        $.ajax({
            type: 'POST',
            url: "{{URL('/refer_doc_search')}}",
            data: {
                spec:spec,
                name: name,
                doctor_id: "{{ $session->doctor_id }}",
                session: "{{ $session->id }}",
            },
            success: function (doctors) {
                $('#load_specialization').text('');
                if (doctors[0].length != 0) {
                    $.each(doctors[0], function (key, value) {
                        if (doctors[1] == 1) {
                            if (value.refered) {
                                $('#load_specialization').append('<div class= "col-md-12 mb-3" >' +
                                    '<div class="refer-card-container">' +
                                    '<div class="image-wrapper">' +
                                    '<img src="' + value.user_image + '" alt="">' +
                                    '</div>' +
                                    '<div class="text-wrapper">' +
                                    '<h3 class="fs-6">Dr.' + value.name + ' ' + value.last_name + '</h3>' +
                                    '<p class="fs-6"><b>NPI: </b> ' + value.nip_number + '</p>' +
                                    '<button type="button" id="'+value.refer_id+'" class="bg-danger referbutn" onclick="cancelReferal(' + value.refer_id + ')">Cancel Refer</button>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div >'
                                );
                            }
                            else {
                                $('#load_specialization').append('<div class= "col-md-12 mb-3" >' +
                                    '<div class="refer-card-container">' +
                                    '<div class="image-wrapper">' +
                                    '<img src="' + value.user_image + '" alt="">' +
                                    '</div>' +
                                    '<div class="text-wrapper">' +
                                    '<h3 class="fs-6">Dr.' + value.name + ' ' + value.last_name + '</h3>' +
                                    '<p class="fs-6"><b>NPI: </b> ' + value.nip_number + '</p>' +
                                    '<textarea class="form-control" id="commit_' + value.id + '" placeholder="Add Comment" style="line-height:1 ; height: 32px;"></textarea>' +
                                    '<button type="button" id="'+value.id+'" class="referbutn" onclick="sendReferal(' + value.id + ')">Refer</button>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div >'
                                );
                            }

                        }
                        else {
                            $('#load_specialization').append('<div class= "col-md-12 mb-3" >' +
                                '<div class="refer-card-container">' +
                                '<div class="image-wrapper">' +
                                '<img src="' + value.user_image + '" alt="">' +
                                '</div>' +
                                '<div class="text-wrapper">' +
                                '<h3 class="fs-6">Dr.' + value.name + ' ' + value.last_name + '</h3>' +
                                '<p class="fs-6"><b>NPI: </b> ' + value.nip_number + '</p>' +
                                '<textarea class="form-control" placeholder="Add Comment" id="commit_' + value.id + '" style="line-height:1 ; height: 32px;"></textarea>' +
                                '<button type="button" id="'+value.id+'" class="referbutn" onclick="sendReferal(' + value.id + ')">Refer</button>' +
                                '</div>' +
                                '</div>' +
                                '</div >'
                            );
                        }

                    })
                } else {
                    $('#load_specialization').append('<a href="javascript:void(0);"' + 'class="list-group-item sp_doc">No doctors of this specialization available</a>');
                }
            }
        });
    }

    function getImagingProduct(cat_id) {
        var selected_location_id = $('#selected_location_id').val();
        $('#selected_img_cat').val(cat_id);
        var name = '';
        $('#load_imaging_product').html('');
        $.ajax({
            type: 'POST',
            url: "{{URL('/new_get_imaging_products_by_category')}}",
            data: {
                cat_id: cat_id,
                location_id: selected_location_id,
                name: name,
                session_id: "{{ $session->id }}"
            },
            success: function (response) {
                if (response == 'notfound') {
                    $('#load_imaging_product').append(
                        '<div class="col-md-4">' +
                        '<button onclick="javascript:void(0)">No products found </button>' +
                        '</div>'
                    );
                } else {
                    if (response.length != 0) {
                        console.log(response[0].TEST_NAME);
                        $.each(response, function (key, value) {
                            if (value.added == 'yes') {
                                $('#load_imaging_product').append(
                                    '<div class="col-md-4 col-sm-6">' +
                                    '<button title="' + value.TEST_NAME + '" class="selected_medi" onclick="javascript:void(0)">' + value.TEST_NAME + '</button>' +
                                    '</div>'
                                );
                            } else {
                                $('#load_imaging_product').append(
                                    '<div class="col-md-4 col-sm-6">' +
                                    '<button title="' + value.TEST_NAME + '" id="' + value.TEST_CD + '" onclick="checkAoes(' + value.TEST_CD + ')">' + value.TEST_NAME + '</button>' +
                                    '</div>'
                                );
                            }
                        });
                    } else {
                        $('#load_imaging_product').append(
                            '<div class="col-md-4">' +
                            '<button onclick="javascript:void(0)">No products found </button>' +
                            '</div>'
                        );
                    }
                }
                $(".medicine_product").stop().slideUp(300);
                $(".lab_oaes").stop().slideUp(300);
                $(".imaging_category").stop().slideUp(300);
                $(".img_zipcode").stop().slideUp(300);
                $(".lab_product").stop().slideUp(300);
                $(".medicine_category").stop().slideUp(300);
                $(".imaging_product").stop().slideToggle(300);
            }
        });
    }

    $("#search_img").keyup(function () {
        var cat_id = $('#selected_img_cat').val();
        var selected_location_id = $('#selected_location_id').val();
        var name = $('#search_img').val();
        $('#load_imaging_product').html('');
        $.ajax({
            type: 'POST',
            url: "{{URL('/new_get_imaging_products_by_category')}}",
            data: {
                cat_id: cat_id,
                location_id: selected_location_id,
                name: name,
                session_id: "{{ $session->id }}"
            },
            success: function (response) {
                $('#load_imaging_product').html('');
                if (response == 'notfound') {
                    $('#load_imaging_product').append(
                        '<div class="col-md-4">' +
                        '<button onclick="javascript:void(0)">No products found </button>' +
                        '</div>'
                    );
                } else {
                    if (response.length != 0) {
                        $.each(response, function (key, value) {
                            if (value.added == 'yes') {
                                $('#load_imaging_product').append(
                                    '<div class="col-md-4">' +
                                    '<button title="' + value.TEST_NAME + '" class="selected_medi" onclick="javascript:void(0)">' + value.TEST_NAME + '</button>' +
                                    '</div>'
                                );
                            } else {
                                $('#load_imaging_product').append(
                                    '<div class="col-md-4">' +
                                    '<button title="' + value.TEST_NAME + '" id="' + value.TEST_CD + '" onclick="checkAoes(' + value.TEST_CD + ')">' + value.TEST_NAME + '</button>' +
                                    '</div>'
                                );
                            }
                        });
                    } else {
                        $('#load_imaging_product').append(
                            '<div class="col-md-4">' +
                            '<button onclick="javascript:void(0)">No products found </button>' +
                            '</div>'
                        );
                    }
                }
                $(".medicine_product").stop().slideUp(300);
                $(".lab_oaes").stop().slideUp(300);
                $(".imaging_category").stop().slideUp(300);
                $(".img_zipcode").stop().slideUp(300);
                $(".lab_product").stop().slideUp(300);
                $(".medicine_category").stop().slideUp(300);
            }
        });
    });


    // ================= Toggle Category Starts================


    function checkAoes(test_code) {
        $('.load_aoes').html('');
        $.ajax({
            type: 'POST',
            url: "{{URL('/check_lab_aoes')}}",
            data: {
                test_code: test_code,
                session_id: "{{ $session->id }}"
            },
            success: function (response) {
                if (response != 'not found') {
                    $.each(response, function (key, value) {
                        $('.load_aoes').append(
                            '<li class="list-group-item d-flex justify-content-between flex-wrap">' +
                            '<span>' + value.QuestionLong + '</span>' +
                            '<input class="' + test_code + ' ' + value.question_id + '" id="' + value.QuestionLong + '" type="text" name="array[]" placeholder="Answer*" required>' +
                            '</li>'
                        );
                    });
                    $(".medicine_category").stop().slideUp(300);
                    $(".medicine_product").stop().slideUp(300);
                    $(".img_zipcode").stop().slideUp(300);
                    $(".lab_product").stop().slideUp(300);
                    $(".imaging_product").stop().slideUp(300);
                    $(".lab_oaes").stop().slideToggle(300);
                } else {
                    //add lab into prescribe table

                    add_lab(test_code);
                    $("#" + test_code).addClass('selected_medi');

                }
            }
        });


    }

    $("#mic_mute").click(function () {
        if ($("#mic_icon").hasClass('fa-microphone-slash')) {
            localTracks.audioTrack.setEnabled(true);
            $('#mic_mute').prop('title', 'mute');
            $("#mic_icon").toggleClass('fa-microphone-slash');
        }
        else {
            localTracks.audioTrack.setEnabled(false);
            $('#mic_mute').prop('title', 'unmute');
            $("#mic_icon").toggleClass('fa-microphone-slash');
        }
    });

    function add_med(product_id) {
        var session_id = "{{ $session->id }}";
        var user_id = "{{ $session->patient_id }}";
        // console.log(user_id);
        $.ajax({
            type: 'POST',
            url: "{{URL('/get_product_details')}}",
            data: {
                id: product_id,
                type: 'med',
                session_id: session_id,
                user_id: user_id,
            },
            success: function (product) {
                $('#med_' + product_id).addClass('selected_medi');
            }
        });
    }
    function add_img(product_id, location_id) {

        var session_id = "{{ $session->id }}";
        var user_id = "{{ $session->patient_id }}";

        $.ajax({
            type: 'POST',
            url: "{{URL('/add_imging_pro')}}",
            data: {
                id: product_id,
                type: 'img',
                session_id: session_id,
                user_id: user_id,
                location_id: location_id,
            },
            success: function (product) {
                $('#img_' + product_id).addClass('selected_medi');
            }
        });
    }
    function add_lab(product_id) {
        var session_id = "{{ $session->id }}";
        var user_id = "{{ $session->patient_id }}";

        $.ajax({
            type: 'POST',
            url: "{{URL('/get_lab_details')}}",
            data: {
                id: product_id,
                session_id: session_id,
                user_id: user_id,
            },
            success: function (product) {

            }
        });
    }
    function submitAoesDetailsForm() {
        var session_id_aoe = "{{ $session->id }}";
        var getTestCode = '';

        var input = document.getElementsByName('array[]');

        var inputValue = [];
        var geterror = 0;
        for (var i = 0; i < input.length; i++) {
            var a = input[i];

            if (a.value == "") {
                geterror = 1;
                $('#aoes_error').css('display', 'block');
            }
            else {
                var allClassesName = a.className;
                var classesName = allClassesName.split(" ");
                $('#aoes_error').css('display', 'none');
                getTestCode = classesName[0];
                var newItems = [{ 'test_cd': classesName[0], 'ques_id': classesName[1], 'ques': a.id, 'ans': a.value }];
                inputValue.push(...newItems);
            }
        }

        if (geterror == 0) {
            $.ajax({
                type: 'POST',
                url: "{{URL('/new_add_labtest_aoes_into_db')}}",
                data: {
                    session_id: session_id_aoe,
                    inputValue: inputValue,
                    getTestCode: getTestCode,
                },
                success: function (res) {
                    add_lab(getTestCode);
                    $("#" + getTestCode).addClass('selected_medi');
                    $(".medicine_product").stop().slideUp(300);
                    $(".lab_oaes").stop().slideUp(300);
                    $(".img_zipcode").stop().slideUp(300);
                    $(".medicine_category").stop().slideUp(300);
                    $(".imaging_product").stop().slideUp(300);
                    $(".lab_product").stop().slideToggle(300);

                }
            });
        }
    }

    function forZipCode() {
        var zipcode = $("#get-user-zipcode").val();
        $('#load_imaging_locations').html('');
        $('#selected_location_id').val('');
        $.ajax({
            type: 'POST',
            url: "{{URL('/fetch_user_state_by_zipcode')}}",
            data: {
                zipcode: zipcode
            },
            success: function (res) {
                if (res == 'no found') {
                    $('#load_imaging_locations').append('<li class="list-group-item d-flex justify-content-between zip-loc-li">' +
                        '<span><b>Not found any location against (' + zipcode + ') that zipcode.</b></span>' +
                        '</li>');
                } else {

                    $.each(res, function (key, value) {

                        if (value.address == null || value.address == '') {
                            $('#load_imaging_locations').append('<li onclick="openImagingCat(' + value.id + ')" class="list-group-item d-flex justify-content-between zip-loc-li">' +
                                '<span><b>' + value.clinic_name + '</b></span>' +
                                '<span>' + value.city + ',' + value.zip_code + '</span>' +
                                '</li>');

                        }
                        else {
                            $('#load_imaging_locations').append('<li onclick="openImagingCat(' + value.id + ')" class="list-group-item d-flex justify-content-between zip-loc-li">' +
                                '<span><b>' + value.clinic_name + '</b></span>' +
                                '<span>' + value.city + '</span>' +
                                '</li>');

                        }
                    });
                }
            }
        });
    }
    function backMedCat() {
        $(".medicine_product").stop().slideUp(300);
        $(".lab_oaes").stop().slideUp(300);
        $(".lab_product").stop().slideUp(300);
        $(".img_zipcode").stop().slideUp(300);
        $(".imaging_product").stop().slideUp(300);
        $(".medicine_category").stop().slideToggle(300);

    }
    $(".but-med").click(function () {
        $(".medicine_product").stop().slideUp(300);
        $(".lab_oaes").stop().slideUp(300);
        $(".imaging_category").stop().slideUp(300);
        $(".img_zipcode").stop().slideUp(300);
        $(".lab_product").stop().slideUp(300);
        $(".imaging_product").stop().slideUp(300);
        $(".medicine_category").stop().slideToggle(300);
    });
    $(".but-img").click(function () {
        $(".medicine_product").stop().slideUp(300);
        $(".lab_oaes").stop().slideUp(300);
        $(".imaging_category").stop().slideUp(300);
        $(".lab_product").stop().slideUp(300);
        $(".medicine_category").stop().slideUp(300);
        $(".imaging_product").stop().slideUp(300);
        $(".img_zipcode").stop().slideToggle(300);
    });
    $(".but-lab").click(function () {
        $(".medicine_product").stop().slideUp(300);
        $(".lab_oaes").stop().slideUp(300);
        $(".imaging_category").stop().slideUp(300);
        $(".medicine_category").stop().slideUp(300);
        $(".img_zipcode").stop().slideUp(300);
        $(".imaging_product").stop().slideUp(300);
        $(".lab_product").stop().slideToggle(300);
    });
    $(".but_close_cat_med").click(function () {
        $(".medicine_product").stop().slideUp(300);
        $(".lab_oaes").stop().slideUp(300);
        $(".imaging_category").stop().slideUp(300);
        $(".medicine_category").stop().slideUp(300);
        $(".img_zipcode").stop().slideUp(300);
        $(".imaging_product").stop().slideUp(300);
        $(".lab_product").stop().slideUp(300);
    });
    $(".close_lab_aoes").click(function () {
        $(".medicine_product").stop().slideUp(300);
        $(".lab_oaes").stop().slideUp(300);
        $(".imaging_category").stop().slideUp(300);
        $(".medicine_category").stop().slideUp(300);
        $(".img_zipcode").stop().slideUp(300);
        $(".imaging_product").stop().slideUp(300);
        $(".lab_product").stop().slideToggle(300);
    });
    $(".but_close_cat_lab").click(function () {
        $(".medicine_product").stop().slideUp(300);
        $(".lab_oaes").stop().slideUp(300);
        $(".imaging_category").stop().slideUp(300);
        $(".medicine_category").stop().slideUp(300);
        $(".img_zipcode").stop().slideUp(300);
        $(".lab_product").stop().slideUp(300);
    });
    $(".but_close_cat_img").click(function () {
        $(".medicine_product").stop().slideUp(300);
        $(".lab_oaes").stop().slideUp(300);
        $(".imaging_category").stop().slideUp(300);
        $(".medicine_category").stop().slideUp(300);
        $(".img_zipcode").stop().slideUp(300);
        $(".lab_product").stop().slideUp(300);
    });
    $('.toggleSubCategory').click(function () {
        $(".medicine_product").stop().slideUp(300);
        $(".imaging_category").stop().slideUp(300);
        $(".lab_oaes").stop().slideUp(300);
        $(".imaging_product").stop().slideUp(300);
        $(".img_zipcode").stop().slideUp(300);
        $(".lab_product").stop().slideUp(300);
        $(".medicine_category").stop().slideToggle(300);
    });
    $('.but_close_pro_img').click(function () {
        $(".medicine_product").stop().slideUp(300);
        $(".imaging_category").stop().slideUp(300);
        $(".lab_oaes").stop().slideUp(300);
        $(".imaging_product").stop().slideUp(300);
        $(".img_zipcode").stop().slideUp(300);
        $(".lab_product").stop().slideUp(300);
        $(".medicine_category").stop().slideUp(300);
        $(".imaging_category").stop().slideToggle(300);
    });
    function openImagingCat(location_id) {
        $('#selected_location_id').val(location_id);
        $(".medicine_product").stop().slideUp(300);
        $(".lab_oaes").stop().slideUp(300);
        $(".medicine_category").stop().slideUp(300);
        $(".img_zipcode").stop().slideUp(300);
        $(".imaging_product").stop().slideUp(300);
        $(".lab_product").stop().slideUp(300);
        $(".imaging_category").stop().slideToggle(300);
    }


    // ================= For Refer Select Doctor Starts================
    $(document).on('click', '.pagination a', function(event){
        event.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        fetch_data(page);
        });

        function fetch_data(page)
        {
            var spec = $('#specializations').val();
            $.ajax({
                type: 'POST',
                url:"/get_specializations_doctors?page="+page,
                data: {
                    spec: spec,
                    session_id: "{{ $session->id }}",
                    patient_id: "{{ $session->patient_id }}",
                    doctor_id: "{{ $session->doctor_id }}"
                },
            success:function(doctors)
            {
                $('#load_specialization').text('');
                $('#load_specialization').append(doctors);
            }
        });
    }
    function cancelReferal(refer_id) {
        var spec = $('#specializations').val();
        $('#'+refer_id+'').html('')
        $('.referbutn').attr('disabled',true);
        $('#'+refer_id+'').html('<i class="fa fa-spinner fa-spin"></i>Loading...')
        $.ajax({
            type: 'POST',
            url: "{{URL('/newCancelReferal')}}",
            data: {
                spec: spec,
                refer_id: refer_id,
                doctor_id: "{{ $session->doctor_id }}",
                session: "{{ $session->id }}",
            },
            success: function (doctors) {
                $('#load_specialization').text('');
                if (doctors) {
                    $('#load_specialization').append(doctors);
                } else {
                    $('#load_specialization').append('<a href="javascript:void(0);"' + 'class="list-group-item sp_doc">No doctors of this specialization available</a>');
                }
            }
        });
    }
    function showHide() {
        var spec = $('#specializations').val();
        $('#load_specialization').html('<div class="text-center"><i class="fa fa-spinner fa-spin"></i>Loading...</div>');
        $.ajax({
            type: 'POST',
            url: "{{URL('/get_specializations_doctors')}}",
            data: {
                spec: spec,
                session_id: "{{ $session->id }}",
                patient_id: "{{ $session->patient_id }}",
                doctor_id: "{{ $session->doctor_id }}"
            },
            success: function (doctors) {
                console.log(doctors);
                $('#load_specialization').text('');
                if (doctors) {
                    $('#load_specialization').append(doctors);
                } else {
                    $('#load_specialization').append('<a href="javascript:void(0);"' + 'class="list-group-item sp_doc">No doctors of this specialization available</a>');
                }
            }
        });
    }

    function sendReferal(a) {
        var spec = $('#specializations').val();
        var comment = $('#commit_' + a).val();
        var id = a;
        $('#'+a+'').html('')
        $('.referbutn').attr('disabled',true);
        $('#'+a+'').html('<i class="fa fa-spinner fa-spin"></i>Loading...')
        $.ajax({
            type: "POST",
            url: "{{URL('/newSendReferal')}}",
            data: {
                spec: spec,
                refer_doc_id: id,
                doctor_id: "{{ $session->doctor_id }}",
                patient_id: "{{ $session->patient_id }}",
                session: "{{ $session->id }}",
                comment: comment
            },
            success: function (doctors) {
                $('#load_specialization').text('');
                if (doctors) {
                    $('#load_specialization').append(doctors);
                } else {
                    $('#load_specialization').append('<a href="javascript:void(0);"' + 'class="list-group-item sp_doc">No doctors of this specialization available</a>');
                }
            }
        });
    }


    window.onload = function () {
        //get the divs to show/hide
        divsO = document
            .getElementById("specialization")
            .getElementsByClassName("show-hide");
    };
    // ================= For Refer Select Doctor Ends================

    // ================= For Notepad Starts================

    function myNotePad() {
        var x = document.getElementById("myNoteDiv");
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    }
    // ================= For Notepad Ends================

    // ================= For Visit History Details Starts================

    function detailToggle(counter) {
        var name = "myDIV" + counter;
        var x = document.getElementById(name);
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    }
    // ================= For Visit History Details Ends================

    // ================= For AOEs Div Starts================

    function forAOESFunction() {
        var x = document.getElementById("myAoesDiv");
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    }
    // ================= For AOEs Div Ends================

    // ================= For ZipCodeDiv Starts================



    // ================= For ZipCodeDiv Ends================
    function endCall() {
        @php
        if (env('APP_TYPE') == 'local') {
            @endphp
                var stopUrl = 'http://127.0.0.1:8000/api/stop';
            @php
        }
        if (env('APP_TYPE') == 'testing') {
            @endphp
                var stopUrl = 'https://demo.umbrellamd-video.com/api/stop';
            @php
        }
        if (env('APP_TYPE') == 'staging') {
            @endphp
                var stopUrl = 'https://www.umbrellamd-video.com/api/stop';
            @php
        }
        if (env('APP_TYPE') == 'production') {
            @endphp
                var stopUrl = 'https://www.umbrellamd.com/api/stop';
            @php
        }
        @endphp

            var _token = $('meta[name="csrf-token"]').attr('content');
        var channel = "{{ $session->channel }}";

        $.ajax({
            type: 'POST',
            dataType: "text",
            data: {
                _token: _token,
                channel: channel
            },
            url: stopUrl,
            success: function (data) {
                console.log('video recording stop');
            }
        });

    }
    function aquire_start() {

        @php
        if (env('APP_TYPE') == 'local') {
            @endphp
                var aynalyticsUrl = 'http://127.0.0.1:8000/api/aynalatics';
            @php
        }
        if (env('APP_TYPE') == 'testing') {
            @endphp
                var aynalyticsUrl = 'https://demo.umbrellamd-video.com/api/aynalatics';
            @php
        }
        if (env('APP_TYPE') == 'staging') {
            @endphp
                var aynalyticsUrl = 'https://www.umbrellamd-video.com/api/aynalatics';
            @php
        }
        if (env('APP_TYPE') == 'production') {
            @endphp
                var aynalyticsUrl = 'https://www.umbrellamd.com/api/aynalatics';
            @php
        }
        @endphp

            var _token = $('meta[name="csrf-token"]').attr('content');
        var channel = "{{ $session->channel }}";
        $.ajax({
            type: 'POST',
            dataType: "text",
            data: {
                _token: _token,
                channel: channel
            },
            url: aynalyticsUrl,
            success: function (data) {
                if (data == 0) {
                    @php
                    if (env('APP_TYPE') == 'local') {
                        @endphp
                            var aquireUrl = 'http://127.0.0.1:8000/api/aquire';
                        @php
                    }
                    if (env('APP_TYPE') == 'testing') {
                        @endphp
                            var aquireUrl = 'https://demo.umbrellamd-video.com/api/aquire';
                        @php
                    }
                    if (env('APP_TYPE') == 'staging') {
                        @endphp
                            var aquireUrl = 'https://www.umbrellamd-video.com/api/aquire';
                        @php
                    }
                    if (env('APP_TYPE') == 'production') {
                        @endphp
                            var aquireUrl = 'https://www.umbrellamd.com/api/aquire';
                        @php
                    }
                    @endphp

                        var _token = $('meta[name="csrf-token"]').attr('content');
                    var userid = "{{ $session->doctor_id }}";
                    var channel = "{{ $session->channel }}";
                    $.ajax({
                        type: 'POST',
                        dataType: "text",
                        data: {
                            _token: _token,
                            channel: channel,
                            userid: userid,
                        },
                        url: aquireUrl,
                        success: function (data) {
                            console.log('video recording start');
                        }
                    });
                }
            }
        });

    }

    // ================= video code start================

    var client = AgoraRTC.createClient({
        mode: "rtc",
        codec: "vp8"
    });
    var localTracks = {
        videoTrack: null,
        audioTrack: null
    };
    var remoteUsers = {};
    const joincall = async function join() {
        aquire_start();
        // Add an event listener to play remote tracks when remote user publishes.
        client.on("user-published", handleUserPublished);
        client.on("user-unpublished", handleUserUnpublished);
        // Join a channel and create local tracks. Best practice is to use Promise.all and run them concurrently.
        [uid, localTracks.audioTrack, localTracks.videoTrack] = await Promise.all([
            // Join the channel.
            client.join(appid, channel, token || null, uid || null),
            // Create tracks to the local microphone and camera.
            AgoraRTC.createMicrophoneAudioTrack(),
            AgoraRTC.createCameraVideoTrack()
        ]);
        // Play the local video track to the local browser and update the UI with the user ID.
        localTracks.videoTrack.play("local-player");
        // Publish the local video and audio tracks to the channel.
        await client.publish(Object.values(localTracks));
        //    console.log("publish success");
    }

    var appid = "{{ env('AGORA_APP_ID') }}";
    var channel = "{{ $session->channel }}";
    var uid = "{{ $session->doctor_id }}";
    var token = '';

    if (appid != null && channel != null) {
        joincall();
    }

    function leave() {
        for (trackName in localTracks) {
            var track = localTracks[trackName];
            if (track) {
                track.stop();
                track.close();
                localTracks[trackName] = undefined;
            }
        }
        // Remove remote users and player views.
        remoteUsers = {};
        $("#remote-playerlist").html("");
        // leave the channel
        client.leave();
        $("#local-player-name").text("");
        // $("#recommendationForm").submit();
    }

    async function subscribe(user, mediaType) {
        const uid = user.uid;
        // subscribe to a remote user
        await client.subscribe(user, mediaType);
        if (mediaType === 'video') {
            const player = $(`
          <div id="player-wrapper-${uid}" style="height:100%;">
          <div id="player-${uid}" class="player" style="height:100%;"></div>
          </div>
       `);
            $("#remote-playerlist").append(player);
            user.videoTrack.play(`player-${uid}`);
        }
        if (mediaType === 'audio') {
            user.audioTrack.play();
        }

    }

    function handleUserPublished(user, mediaType) {
        const id = user.uid;
        remoteUsers[id] = user;
        subscribe(user, mediaType);
        $('#video_pat').hide();
        $('#video_doc').hide();
    }

    function handleUserUnpublished(user, mediaType) {

        if (mediaType === 'video') {
            const id = user.uid;
            delete remoteUsers[id];
            $(`#player-wrapper-${id}`).remove();
            $('#video_pat').show();
            $('#video_doc').show();
        }
    }

    function runSpeechRecognition() {

        try {
            // get action element reference
            var action = document.getElementById("start-record-btn");
            // new speech recognition object
            var SpeechRecognition = SpeechRecognition || webkitSpeechRecognition;
            var recognition = new SpeechRecognition();

            // This runs when the speech recognition service starts
            recognition.onstart = function () {
                $('#start-record-btn').css("background-color", "#2185d0");
                if (!$("#mic_icon").hasClass('fa-microphone-slash')) {
                    localTracks.audioTrack.setEnabled(false);
                    $('#mic_mute').prop('title', 'unmute');
                    $("#mic_icon").toggleClass('fa-microphone-slash');
                }
                action.innerHTML = "listening, please speak...";
            };
            recognition.onspeechend = function () {
                action.innerHTML = "stopped listening, hope you are done...";
                recognition.stop();
                if ($("#mic_icon").hasClass('fa-microphone-slash')) {
                    localTracks.audioTrack.setEnabled(true);
                    $('#mic_mute').prop('title', 'mute');
                    $("#mic_icon").toggleClass('fa-microphone-slash');
                }
            }
            // This runs when the speech recognition service returns result
            recognition.onresult = function (event) {
                var previousData = $('#note-textarea').val();
                var transcript = event.results[0][0].transcript;
                $('#start-record-btn').css("background-color", "red");
                $('#note-textarea').val(previousData + " " + transcript);

            };
            // start recognition
            recognition.start();
        }catch (err) {
            action.innerHTML = "Sorry!, Your Browser did not translate speech to text...";
            $('#start-record-btn').css("background-color", "red");
        }
    }
    // ================= video code end================



    $('#end_session').click(function () {
        var id = "{{ $session->id }}";
        $.ajax({
            type: 'POST',
            url: '/check_prescription_completed',
            data: {
                id: id,
            },
            success: function (status) {
                console.log(status);
                $.ajax({
                    type: 'POST',
                    url: '/doctor_end_session',
                    data: {
                        id: id,
                    },
                    beforeSend: function () {
                        $(".videoLoaderDiv").css('display','flex');
                    },
                    success: function (status) {
                        console.log(status);
                        endCall();
                        leave();
                        $("#recommendationForm").submit();
                    }
                });
            }
        });
    });
</script>
@endsection @section('content')
<section>
    <div class="videoLoaderDiv" style="display: none;
        width: 100%;
        height: 100vh;
        position: fixed;
        z-index: 999999;
        background-color: black;
        opacity: 0.8;
        align-items: center;
        justify-content: center;">
        <i class="fas fa-spinner fa-spin" style="color: white;font-size: 150px;"></i>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="call-screen-header">
                    <div class="d-flex align-items-center">
                        <i class="fa-solid fa-user-doctor"></i>
                        <h6>
                            Dr.{{ Auth::user()->name.' '.Auth::user()->last_name }}
                        </h6>
                    </div>
                    <div class="call-time">
                        <h5 id="time">15 Minutes : 00 Seconds</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-7">
                <div class="video-call-screen">
                    <div class="big-video-screen" id="remote-playerlist">
                        <div class="spannerx" id="video_pat">
                            <div class="loaderx"></div>
                            <p>Connecting...</p>
                        </div>
                    </div>

                    <div class="after-big-screen-div">
                        <div class="small-video-screen" id="local-player" style="position: relative">
                            <div class="spannerx" id="video_doc" style="position: absolute">
                                <div class="loaderx"></div>
                                <p>Connecting...</p>
                            </div>
                        </div>
                        <div class="call-disconnect">
                            <button class="end-call" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                <i class="fa-solid fa-phone-slash"></i> END
                            </button>

                            <button class="mic-call" id="mic_mute">
                                <i class="fa-solid fa-microphone-lines" id="mic_icon"></i>MIC
                            </button>
                        </div>
                        <div class="patient-name">
                            <div>
                                <h6>Patient Name:</h6>
                                <p>
                                    {{ $patient->name.' '.$patient->last_name }}
                                </p>
                            </div>
                            <div>
                                <h6>Patient Age:</h6>
                                <p>
                                    {{ $patient->date_of_birth }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="call-disclaimer mb-3">
                    <h6>DISCLAIMER</h6>
                    <p class="fs-6">
                        Never disregard professional medical advice or delay
                        seeking medical treatment because of something you have
                        read on or accessed through this web site. community healthcare clinics not responsible nor liable for any
                        advice, course of treatment, diagnosis or any other
                        information, services or products that you obtain
                        through this web site.
                    </p>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="right-detail-call-screen">
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item col-3" role="presentation">
                            <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home"
                                aria-selected="true">
                                Patient History
                            </button>
                        </li>
                        <li class="nav-item col-3" role="presentation">
                            <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile"
                                aria-selected="false">
                                Prescription
                            </button>
                        </li>
                        <li class="nav-item col-3" role="presentation">
                            <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact"
                                aria-selected="false">
                                Refer
                            </button>
                        </li>

                        <li class="nav-item col-2 ms-auto offset-1" role="presentation">
                            <button onclick="myNotePad()" class="notes-btn">
                                <i class="fa-solid fa-notes-medical"></i> Notes
                            </button>
                        </li>
                    </ul>

                    <!-- ================ NOTEDPAD STARTS============ -->
                    <div class="container">
                        <div class="row">
                            <div id="myNoteDiv" style="display: none">
                                <div class="row m-auto">
                                    <div class="home faqs_text_editor">
                                        <div>
                                            <!-- <h6>Notes </h6> -->
                                            <i class="fa-regular fa-circle-xmark fs-3 text-danger"
                                                onclick="myNotePad()"></i>
                                        </div>
                                        <form id="recommendationForm" action="{{route('recommendations.store.pres')}}"
                                            method="post">
                                            @csrf
                                            <div class="mb-3">
                                                <input hidden name="imaging_id" id="hid_imaging_id">
                                                <input id="products" hidden="" type="text" name="product_list"
                                                    value="0" />
                                                <input id="lab_products" hidden="" type="text" name="lab_product_list"
                                                    value="0" />
                                                <input hidden="" id="pat_id" type="text" name="patient_id"
                                                    value="{{$session->patient_id}}" />
                                                <input hidden="" id="doc_id" type="text" name="doc_id"
                                                    value="{{$session->doctor_id}}" />
                                                <input hidden="" id="session_id" type="text" name="session_id"
                                                    value="{{$session->id}}" />
                                                <label class="form-label">Notes</label>
                                                <textarea type="text" class="form-control" id="note-textarea"
                                                    name="note"
                                                    placeholder="Create a new notes by typing or using voice recording."
                                                    style="height: 130px"></textarea>
                                            </div>

                                            <div class="d-flex justify-content-between mt-3">
                                                <button id="start-record-btn" class="btn ui blue button"
                                                    onclick="runSpeechRecognition()" type="button"
                                                    title="Start Recording">
                                                    <i class="fa-solid fa-microphone"></i>
                                                    Start Recording
                                                </button>
                                            </div>
                                            <div class="d-flex justify-content-between mt-3">
                                                <p class="m-0 px-2">
                                                    Press the Start Recording
                                                    button and allow access
                                                </p>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ================ NOTEDPAD ENDS============ -->

                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                            aria-labelledby="pills-home-tab">
                            <div>
                                <div class="accordion accordion-flush" id="accordionFlushExample">
                                    {{-- <div class="accordion-item">
                                        <h2 class="accordion-header" id="flush-headingSix">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#flush-collapseSix"
                                                aria-expanded="false" aria-controls="flush-collapseSix">
                                                Diagnosis
                                            </button>
                                        </h2>
                                        <div id="flush-collapseSix" class="accordion-collapse collapse"
                                            aria-labelledby="flush-headingSix" data-bs-parent="#accordionFlushExample">
                                            <div class="accordion-body accordion-first">
                                                <div class="screen-symtoms">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <ul class="ps-0" id="loadIsabelDiagnosis"></ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="flush-headingTwo">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo"
                                                aria-expanded="false" aria-controls="flush-collapseTwo">
                                                Current Medication
                                            </button>
                                        </h2>
                                        <div id="flush-collapseTwo"
                                            class="accordion-collapse collapse visit-history-data"
                                            aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                                            <div class="accordion-body">
                                                <div class="my-2">
                                                    <div class="screen-medication" id="loadCurrentMedication"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="flush-headingThree">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#flush-collapseThree"
                                                aria-expanded="false" aria-controls="flush-collapseThree">
                                                Visit History
                                            </button>
                                        </h2>
                                        <div id="flush-collapseThree"
                                            class="accordion-collapse collapse visit-history-data"
                                            aria-labelledby="flush-headingThree"
                                            data-bs-parent="#accordionFlushExample">
                                            <div class="accordion-body mb-2" id="loadSessionRecords"></div>
                                        </div>
                                    </div>
                                    @if ($session->specialization_id == '21')
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="flush-headingSeven">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#flush-collapseSeven"
                                                aria-expanded="false" aria-controls="flush-collapseSeven">
                                                Psychiatric Evaluation
                                            </button>
                                        </h2>
                                        <div id="flush-collapseSeven"
                                            class="accordion-collapse collapse visit-history-data"
                                            aria-labelledby="flush-headingSeven"
                                            data-bs-parent="#accordionFlushExample">
                                            <nav>
                                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                                  <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Patient Health</button>
                                                  <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Mood Disorder</button>
                                                  <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Anxiety Scale</button>
                                                </div>
                                              </nav>
                                              <div class="tab-content" id="nav-tabContent">
                                                <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                                                    <p class="med-head">Patient Health</p>
                                                    <div>
                                                        <p><b>1. Little interest or pleasure in doing things</b></p>
                                                        <p id="Question1"></p> <br>
                                                    </div>
                                                    <div>
                                                        <p><b>2. Feeling down, depressed, or hopeless</b></p>
                                                        <p id="Question2"></p> <br>
                                                    </div>
                                                    <div>
                                                        <p><b>3. Trouble falling or staying asleep, or sleeping too much</b></p>
                                                        <p id="Question3"></p> <br>
                                                    </div>
                                                    <div>
                                                        <p><b>4. Feeling tired or having little energy</b></p>
                                                        <p id="Question4"></p> <br>
                                                    </div>
                                                    <div>
                                                        <p><b>5. Poor appetite or overeating</b></p>
                                                        <p id="Question5"></p> <br>
                                                    </div>
                                                    <div>
                                                        <p><b>6. Feeling bad about yourself or that you are a failure or have let yourself or your
                                                            family down</b></p>
                                                        <p id="Question6"></p> <br>
                                                    </div>
                                                    <div>
                                                        <p><b>7. Trouble concentrating on things, such as reading the newspaper or watching
                                                            television</b></p>
                                                        <p id="Question7"></p> <br>
                                                    </div>
                                                    <div>
                                                        <p><b>8. Moving or speaking so slowly that other people could have noticed. Or the
                                                            opposite
                                                            being so figety or restless that you have been moving around a lot more than usual</b></p>
                                                        <p id="Question8"></p> <br>
                                                    </div>
                                                    <div>
                                                        <p><b>9. Thoughts that you would be better off dead, or of hurting yourself</b></p>
                                                        <p id="Question9"></p> <br>
                                                    </div>
                                                    <div>
                                                        <p><b>10. If you checked off any problems, how difficult Not difficult at all have these
                                                            problems
                                                            made it for you to do your work, take care of things at home, or get along with other
                                                            people?</b></p>
                                                        <p id="question10"></p> <br>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                                                    <p class="med-head">Mood Disorder</p>
                                                    <div>
                                                        <p><b>1. ...you felt so good or so hyper that other people thought you were not your normal self
                                                            or you were so hyper that you got into trouble?</b></p>
                                                        <p id="MDQuestion1a"></p> <br>
                                                    </div>
                                                    <div>
                                                        <p><b>2. ...you were so irritable that you shouted at people or started fights or arguments?</b></p>
                                                        <p id="MDQuestion1b"></p> <br>
                                                    </div>
                                                    <div>
                                                        <p><b>3. ...you felt much more self-confident than usual?</b></p>
                                                        <p id="MDQuestion1c"></p> <br>
                                                    </div>
                                                    <div>
                                                        <p><b>4. ...you got much less sleep than usual and found you didn’t really miss it? </b></p>
                                                        <p id="MDQuestion1d"></p> <br>
                                                    </div>
                                                    <div>
                                                        <p><b>5. ...you were much more talkative or spoke much faster than usual?</b></p>
                                                        <p id="MDQuestion1e"></p> <br>
                                                    </div>
                                                    <div>
                                                        <p><b>6. ...thoughts raced through your head or you couldn’t slow your mind down?</b></p>
                                                        <p id="MDQuestion1f"></p> <br>
                                                    </div>
                                                    <div>
                                                        <p><b>7. ...you were so easily distracted by things around you that you had trouble
                                                            concentrating or staying on track?</b></p>
                                                        <p id="MDQuestion1g"></p> <br>
                                                    </div>
                                                    <div>
                                                        <p><b>8. ...you had much more energy than usual?</b></p>
                                                        <p id="MDQuestion1h"></p> <br>
                                                    </div>
                                                    <div>
                                                        <p><b>9. ...you were much more active or did many more things than usual?</b></p>
                                                        <p id="MDQuestion1i"></p> <br>
                                                    </div>
                                                    <div>
                                                        <p><b>10. ...you were much more social or outgoing than usual, for example, you telephoned
                                                            friends in the middle of the night?</b></p>
                                                        <p id="MDQuestion1j"></p> <br>
                                                    </div>
                                                    <div>
                                                        <p><b>11. ...you were much more interested in sex than usual?</b></p>
                                                        <p id="MDQuestion1k"></p> <br>
                                                    </div>
                                                    <div>
                                                        <p><b>12. ...you did things that were unusual for you or that other people might have thought
                                                            were excessive, foolish, or risky?</b></p>
                                                        <p id="MDQuestion1l"></p> <br>
                                                    </div>
                                                    <div>
                                                        <p><b>13. ...spending money got you or your family into trouble?</b></p>
                                                        <p id="MDQuestion1m"></p> <br>
                                                    </div>
                                                    <div>
                                                        <p><b>2. If you checked YES to more than one of the above, have several of these
                                                            ever happened during the same period of time?</b></p>
                                                        <p id="MDQuestion2"></p> <br>
                                                    </div>
                                                    <div>
                                                        <p><b>3. How much of a problem did any of these cause you – like being unable to work; having
                                                            family,money or legal troubles; getting into arguments or fights? Please circle one response only *</b></p>
                                                        <p id="MDQuestion3"></p> <br>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                                                    <p class="med-head">Anxiety Scale</p>
                                                    <div>
                                                        <p><b>1. ANXIOUS MOOD</b></p>
                                                        <p id="anxiety"></p> <br>
                                                    </div>
                                                    <div>
                                                        <p><b>2. TENSION</b></p>
                                                        <p id="tension"></p> <br>
                                                    </div>
                                                    <div>
                                                        <p><b>3. FEARS</b></p>
                                                        <p id="fears"></p> <br>
                                                    </div>
                                                    <div>
                                                        <p><b>4. INSOMNIA</b></p>
                                                        <p id="insomnia"></p> <br>
                                                    </div>
                                                    <div>
                                                        <p><b>5. INTELLECTUAL</b></p>
                                                        <p id="intellectual"></p> <br>
                                                    </div>
                                                    <div>
                                                        <p><b>6. DEPRESSED MOOD</b></p>
                                                        <p id="depressed"></p> <br>
                                                    </div>
                                                    <div>
                                                        <p><b>7. SOMATIC COMPLAINTS: MUSCULAR</b></p>
                                                        <p id="muscular"></p> <br>
                                                    </div>
                                                    <div>
                                                        <p><b>8. SOMATIC COMPLAINTS: SENSORY </b></p>
                                                        <p id="sensory"></p> <br>
                                                    </div>
                                                    <div>
                                                        <p><b>9. CARDIOVASCULAR SYMPTOMS</b></p>
                                                        <p id="cardio"></p> <br>
                                                    </div>
                                                    <div>
                                                        <p><b>10. RESPIRATORY SYMPTOMS</b></p>
                                                        <p id="respiratory"></p> <br>
                                                    </div>
                                                    <div>
                                                        <p><b>11. GASTROINTESTINAL SYMPTOMS</b></p>
                                                        <p id="gastro"></p> <br>
                                                    </div>
                                                    <div>
                                                        <p><b>12. GENITOURINARY SYMPTOMS </b></p>
                                                        <p id="genitourinary"></p> <br>
                                                    </div>
                                                    <div>
                                                        <p><b>13.  AUTONOMIC SYMPTOMS </b></p>
                                                        <p id="autonomic"></p> <br>
                                                    </div>
                                                    <div>
                                                        <p><b>14. BEHAVIOR AT INTERVIEW  </b></p>
                                                        <p id="behavior"></p> <br>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="flush-headingFour">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#flush-collapseFour"
                                                aria-expanded="false" aria-controls="flush-collapseFour">
                                                Medical History
                                            </button>
                                        </h2>
                                        <div id="flush-collapseFour" class="accordion-collapse collapse"
                                            aria-labelledby="flush-headingFour" data-bs-parent="#accordionFlushExample">
                                            <div class="accordion-body">
                                                <div class="visit-history-div">
                                                    <div id="loadMedicalRecord" class="medical-history-btn"></div>
                                                    @php $count = 1; @endphp
                                                    @foreach($files as $file)
                                                        <div><a href="{{ $file->record_file }}" target="_blank">View Medical Record {{$count}}</a></div>
                                                        @php $count = $count+1; @endphp
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="flush-headingFive">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#flush-collapseFive"
                                                aria-expanded="false" aria-controls="flush-collapseFive">
                                                Family History
                                            </button>
                                        </h2>
                                        <div id="flush-collapseFive" class="accordion-collapse collapse"
                                            aria-labelledby="flush-headingFive" data-bs-parent="#accordionFlushExample">
                                            <div class="accordion-body table-family">
                                                <div>
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">
                                                                    Disease
                                                                </th>
                                                                <th scope="col">
                                                                    Family
                                                                    Member
                                                                </th>
                                                                <th scope="col">
                                                                    Apprx.age
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="loadFamilyRecord"></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                            aria-labelledby="pills-profile-tab">
                            <div class="row">
                                <div class="assad prescription-button-wrap">
                                    <div class="btn-wrapper-div">
                                        <button class="but-med btn-1">
                                            Medicines
                                        </button>


                                        <div class="content medicine_category">
                                            <div class="prescriptio-card-wrapper">
                                                <div class="card">
                                                    <div class="card-header fw-bold"><i
                                                            class="fa-solid fa-capsules"></i> Medicines Categories <i
                                                            class="fa-solid fa-circle-xmark but_close_cat_med"></i>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="wrap">
                                                            <div class="search">
                                                                <input id="search_cat" type="text" class="searchTerm"
                                                                    placeholder="What are you looking for?" />
                                                                <button id="search_cat_button" type="submit"
                                                                    class="searchButton">
                                                                    <i class="fa fa-search"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" id="selected_med_cat">
                                                        <div class="medical-col-scroll">
                                                            <div class="row" id="results">
                                                                @foreach($med as $cat)
                                                                <div class="col-md-4 col-sm-6">
                                                                    <button title="{{$cat->title}}"
                                                                        onclick="getMedicienByCategory('{{ $cat->id }}')">{{$cat->title}}</button>
                                                                </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="content medicine_product">
                                            <div class="prescriptio-card-wrapper">
                                                <div class="card">
                                                    <div class="card-header fw-bold">
                                                        <i class="fa-solid fa-circle-arrow-left toggleSubCategory"></i>
                                                        <h6 class="m-auto"></h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="wrap">
                                                            <div class="search">
                                                                <input type="text" id="search_med" class="searchTerm"
                                                                    placeholder="What are you looking for?" />
                                                                <button type="submit" class="searchButton">
                                                                    <i class="fa fa-search"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="medical-col-scroll">
                                                            <div class="row loadMedicienProduct"
                                                                id="loadMedicienProduct"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="btn-wrapper-div">
                                        <button class="but-lab btn-2">
                                            Lab-Tests
                                        </button>
                                        <div class="content lab_product">
                                            <div class="prescriptio-card-wrapper">
                                                <div class="card">
                                                    <div class="card-header fw-bold"><i
                                                            class="fa-solid fa-capsules"></i> Tests Categories <i
                                                            class="fa-solid fa-circle-xmark but_close_cat_lab"></i>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="wrap">
                                                            <div class="search">
                                                                <input id="Lab_search"
                                                                    onkeyup="loadLabItems({{ $session->id }})"
                                                                    type="text" class="searchTerm"
                                                                    placeholder="What are you looking for?" />
                                                                <button id="Lab_search_button" type="button"
                                                                    class="searchButton">
                                                                    <i class="fa fa-search"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="medical-col-scroll">
                                                            <div class="row" id="loadLabItems">



                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="content lab_oaes">
                                            <div class="prescriptio-card-wrapper">
                                                <div class="card">
                                                    <div class="card-header fw-bold">
                                                        <i class="fa-solid fa-circle-arrow-left close_lab_aoes"></i>
                                                        <span class="m-auto">Digestive Health</span>
                                                        <!-- <i class="fa-solid fa-circle-xmark but1"></i> -->
                                                    </div>
                                                    <div class="card-body">
                                                        <div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="card m-0 aoes-question-card"
                                                                        style="width: 100%;">
                                                                        <div class="card-header">AOEs for SARS <span
                                                                                class="float-end">Question/Answers</span>
                                                                        </div>
                                                                        <ul
                                                                            class="list-group list-group-flush aoes-ul load_aoes">

                                                                        </ul>
                                                                        <div class="d-flex aoes-btn-div ms-auto mt-1">
                                                                            <p id="aoes_error" style="display: none;">
                                                                                All AOE's Answer Are
                                                                                Required </p>
                                                                            <button class="bg-danger"
                                                                                onclick="close_aoes_modal()">Cancel</button>
                                                                            <button onClick="submitAoesDetailsForm()"
                                                                                class="bg-success">Submit</button>
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

                                    <div class="btn-wrapper-div">
                                        <button class="but-img btn-3">
                                            Imagings
                                        </button>
                                        <div class="content img_zipcode">
                                            <div class="prescriptio-card-wrapper">
                                                <div class="card">
                                                    <div class="card-header fw-bold"><i
                                                            class="fa-solid fa-capsules"></i> Imaging Categories <i
                                                            class="fa-solid fa-circle-xmark but_close_cat_img"></i>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="wrap">
                                                            <div class="search">
                                                                <input type="text" class="searchTerm"
                                                                    placeholder="What are you looking for?" />
                                                                <button type="submit" class="searchButton">
                                                                    <i class="fa fa-search"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="medical-col-scroll">
                                                            <div class="row">
                                                                <input type="hidden" id="selected_img_cat">
                                                                <input type="hidden" id="selected_location_id">
                                                                @foreach($img as $cat)
                                                                <div class="col-md-4 col-sm-6">
                                                                    <button title="{{$cat->name}}"
                                                                        onclick="getImagingProduct('{{$cat->id}}')">{{$cat->name}}</button>
                                                                </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="content imaging_product">
                                            <div class="prescriptio-card-wrapper">
                                                <div class="card">
                                                    <div class="card-header fw-bold"><i
                                                            class="fa-solid fa-capsules"></i> Imaging Product
                                                        <!-- <i class="fa-solid fa-circle-xmark but_close_pro_img"></i> -->
                                                        <i class="fa-solid fa-circle-arrow-left but_close_pro_img"></i>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="wrap">
                                                            <div class="search">
                                                                <input type="text" id="search_img" class="searchTerm"
                                                                    placeholder="What are you looking for?" />
                                                                <button type="submit" class="searchButton">
                                                                    <i class="fa fa-search"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="medical-col-scroll">
                                                            <div class="row" id="load_imaging_product">



                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="selected-value-div-wrap">
                                    <h5>Selected items:</h5>
                                    <div class="prescribed_items_main">
                                        <div class="pt-3 d-flex flex-wrap prescribed_items">

                                            <span class="selected-value-bydoc">Not Found Any Prescribed Item
                                                !!</span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="pills-contact" role="tabpanel"
                            aria-labelledby="pills-contact-tab">
                            <div>
                                <h5 class="pb-1">Select Specialization</h5>
                                <div class="row refer-button-wrap">
                                    <form action="#" method="post" id="specialization">
                                        <div class="dropdown-form">
                                            <select onchange="showHide(this)" id="specializations">
                                                <option value="">select</option>

                                                @foreach($specializations as $specialization)

                                                <option value="{{ $specialization->id }}">{{ $specialization->name }}
                                                </option>
                                                @endforeach


                                            </select>
                                        </div>
                                    </form>
                                    <div class="search d-flex">
                                        <input id="refer_search"
                                            onkeyup="referDoctor({{ $session->id }})"
                                            type="text" class="p-2 w-100 rounded"
                                            placeholder="What are you looking for?" />
                                    </div>
                                    <div id="load_specialization">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ================== MODAl START=================== -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Are You Sure?
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-5">
                    <div class="text-center">
                        <h3>End Video Session</h3>
                        <div class="mt-3">
                            <button id="end_session" type="button" onclick="" class="btn btn-danger"
                                data-bs-dismiss="modal">
                                End Call
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ================== MODAl ENDS=================== -->
</section>

@endsection
