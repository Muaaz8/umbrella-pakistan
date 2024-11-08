@extends('layouts.new_video_calling') @section('meta_tags')
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon" />
@endsection @section('page_title')
<title>UHCS - Patient Video Calling</title>
@endsection @section('top_import_file')
<link rel="stylesheet" href="{{ asset('/assets/css/patient_video_calling.css') }}" />
@endsection @section('bottom_import_file')
<script src="{{ asset('/js/app.js') }}"></script>
<script src="https://download.agora.io/sdk/release/AgoraRTC_N-4.12.1.js"></script>
@php header("Access-Control-Allow-Origin: *"); @endphp
<script>
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });


    Echo.channel('patient_end_call')
        .listen('patientEndCall', (e) => {
            if (e.session_id == "{{ $session->id }}") {
                leave();
                window.location.href = '/waiting/page/' + e.session_id;
            }
        });
    function detailToggle(counter) {
        var name = "myDIV" + counter;
        var x = document.getElementById(name);
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    }

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
        loadSymtoms(session_id);
        currentMedication(patient_id);
        getSessionRecord(patient_id, session_id);
        getFamilyHistory(patient_id);
        getMedicalHistory(patient_id);
        imagingReports(patient_id);
        labReports(patient_id);
    });
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
                    $("#time").text("00 minutes : 00 seconds");
                    //open when go to production
                    // $.ajax({
                    //     type: "POST",
                    //     url: "{{url('/doctor_end_session')}}",
                    //     data: {
                    //         id: session_id,
                    //     },
                    //     success: function (msg) {

                    //         window.location = '/waiting/page/' + session_id;
                    //     },
                    // });
                }
            }, 1000);
        }
    }
    function loadSymtoms(session_id) {
        $("#loadSymtems").html("");
        $.ajax({
            type: "POST",
            url: "{{url('/load_symtems_video_page')}}",
            data: {
                id: session_id,
            },
            success: function (data) {
                console.log(data);
                $.each(data.symptoms_text, function (key, value) {
                    $("#loadSymtems").append("<li>" + value + "</li>");
                });
                if (data.description != "NaN") {
                    $("#loadSymtems").append(
                        "<li><b> Symptoms Description: </b>" + data.description + "</li>"
                    );
                }
            },
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
                        "<span>Session Date: <b>" +
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
    function getSessionRecord(patient_id,session_id) {
        $("#loadSessionRecords").html("");
        $.ajax({
            type: "POST",
            url: "{{url('/load_session_record_video_page')}}",
            data: {
                id: patient_id,
                session_id: session_id,
            },
            success: function (data) {
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
                            '<li class="list-group-item">' +
                            "<b>Symptoms : </b>" +
                            value.symtems +
                            "</li>" +
                            '<li class="list-group-item">' +
                            "<b>Diagnosis : </b> " +
                            value.diagnois +
                            "</li>" +
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
                        '<li class="list-group-item">No Visit History</li>'
                    );
                }
            },
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
                    });
                }else{
                    $("#loadFamilyRecord").append(
                        "<tr>No Family Record</tr>"
                    );
                }
            },
            error: function(jqXHR, textStatus, errorThrown){
                $("#loadFamilyRecord").append(
                    "<tr'>No Family Record</tr>"
                );
            }
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
                console.log(data);
                if (data != "" || data != null) {
                    temp = false;
                    $.each(data.prev_symp, function (key, value) {
                        if (value != null || value != " ") {
                            $("#loadMedicalRecord").append(
                                "<button>" + value + "</button>"
                            );
                            temp = true;
                        }
                    });
                }
                if (temp == false){
                    $("#loadMedicalRecord").append(
                    "<tr'>No Medical Record</tr>"
                );
                }

                if (data.comment != null){
                    $("#loadMedicalRecord").append(
                        "<br><tr><b>Comment: </b>"+data.comment+"</tr>"
                    );
                }
            },
            error: function(jqXHR, textStatus, errorThrown){
                console.log('okq22');
                $("#loadMedicalRecord").append(
                    "<tr'>No Medical Record</tr>"
                );
            }
        });
    }
    function imagingReports(patient_id) {
        $("#loadImagingRecord").html("");
        $.ajax({
            type: "POST",
            url: "{{url('/load_imaging_report_video_page')}}",
            data: {
                id: patient_id,
            },
            success: function (data) {
                if(data == 'ok'){
                    $("#loadImagingReports").append(
                        '<li class="list-group-item">No Imaging Reports</li>'
                    );
                }
            },
            error: function(jqXHR, textStatus, errorThrown){
                $("#loadImagingRecord").append(
                    '<li class="list-group-item">No Imaging Reports</li>'
                );
            }
        });
    }
    function labReports(patient_id) {
        $("#loadLabReports").html("");
        $.ajax({
            type: "POST",
            url: "{{url('/load_lab_report_video_page')}}",
            data: {
                id: patient_id,
            },
            success: function (data) {
                if(data == 'ok'){
                    $("#loadLabReports").append(
                        '<li class="list-group-item">No Lab Reports</li>'
                    );
                }
            },
            // error: function(jqXHR, textStatus, errorThrown){
            //     $("#loadLabReports").append(
            //        '<li class="list-group-item">No Lab Reports</li>'
            //     );
            // }
        });
    }



    var client = AgoraRTC.createClient({
        mode: "rtc",
        codec: "vp8",
    });
    var localTracks = {
        videoTrack: null,
        audioTrack: null,
    };
    var remoteUsers = {};
    const joincall = async function join() {

        // Add an event listener to play remote tracks when remote user publishes.
        client.on("user-published", handleUserPublished);
        client.on("user-unpublished", handleUserUnpublished);
        // Join a channel and create local tracks. Best practice is to use Promise.all and run them concurrently.
        [uid, localTracks.audioTrack, localTracks.videoTrack] =
            await Promise.all([
                // Join the channel.
                client.join(appid, channel, token || null, uid || null),
                // Create tracks to the local microphone and camera.
                AgoraRTC.createMicrophoneAudioTrack(),
                AgoraRTC.createCameraVideoTrack(),
            ]);
        // Play the local video track to the local browser and update the UI with the user ID.
        localTracks.videoTrack.play("local-player");
        // Publish the local video and audio tracks to the channel.
        await client.publish(Object.values(localTracks));
        //    console.log("publish success");
    };

    var appid = "{{ env('AGORA_APP_ID') }}";
    var channel = "{{ $session->channel }}";
    var uid = "{{ $session->patient_id }}";
    var token = "";
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
        if (mediaType === "video") {
            const player = $(`
              <div id="player-wrapper-${uid}" style="height:100%;">
              <div id="player-${uid}" class="player" style="height:100%;"></div>
              </div>
           `);
            $("#remote-playerlist").append(player);
            user.videoTrack.play(`player-${uid}`);
        }
        if (mediaType === "audio") {
            user.audioTrack.play();
        }

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

    function handleUserPublished(user, mediaType) {
        const id = user.uid;
        remoteUsers[id] = user;
        subscribe(user, mediaType);
        $('#video_doc').hide();
        $('#video_pat').hide();

    }

    function handleUserUnpublished(user, mediaType) {

        if (mediaType === 'video') {
            const id = user.uid;
            delete remoteUsers[id];
            $(`#player-wrapper-${id}`).remove();
            $('#video_doc').show();
            $('#video_pat').show();
        }
    }
</script>
@endsection @section('content')

<section>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="call-screen-header">
                    <div class="d-flex align-items-center">
                        <i class="fa-regular fa-user"></i>
                        <h6>
                            {{ Auth::user()->name.' '.Auth::user()->last_name }}
                        </h6>
                    </div>
                    <div class="call-time">
                        <h5 id="time">15 Minutes : 00 Seconds</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-7">
                <div class="video-call-screen">
                    <div class="big-video-screen" id="remote-playerlist">
                        <div class="spannerx" id="video_doc">
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
                            <!-- <button class="end-call">
                                <i class="fa-solid fa-phone-slash"></i> END
                            </button> -->
                            <button class="mic-call" id="mic_mute">
                                <i class="fa-solid fa-microphone-lines" id="mic_icon"></i>MIC
                            </button>
                        </div>
                        <div class="patient-name">
                            <div>
                                <h6>Doctor Name:</h6>
                                <p>
                                    Dr.{{ $doctor->name.' '.$doctor->last_name }}
                                </p>
                            </div>
                            <div>
                                <h6>Specialization:</h6>
                                <p>{{ $doctor->sp_name }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="call-disclaimer">
                    <h6>DISCLAIMER</h6>
                    <p class="fs-6">
                        Never disregard professional medical advice or delay
                        seeking medical treatment because of something you have
                        read on or accessed through this web site. umbrella
                        health care systems not responsible nor liable for any
                        advice, course of treatment, diagnosis or any other
                        information, services or products that you obtain
                        through this web site.
                    </p>
                </div>
            </div>

            <div class="col-md-5">
                <div class="right-detail-call-screen">
                    <div>
                        <div class="accordion accordion-flush" id="accordionFlushExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapseOne" aria-expanded="false"
                                        aria-controls="flush-collapseOne">
                                        Medical History
                                    </button>
                                </h2>
                                <div id="flush-collapseOne" class="accordion-collapse collapse"
                                    aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body accordion-first">
                                        {{-- <h6>Symptoms</h6>
                                        <div class="screen-symtoms">
                                            <ul id="loadSymtems"></ul>
                                        </div> --}}
                                        <div class="">
                                            <h6>Medical History</h6>
                                            <div class="medical-history-btn" id="loadMedicalRecord"></div>
                                        </div>
                                        <div class="mt-2">
                                            <h6>Family History</h6>
                                            <table class="table table-family">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">
                                                            Disease
                                                        </th>
                                                        <th scope="col">
                                                            Family Member
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
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapseTwo" aria-expanded="false"
                                        aria-controls="flush-collapseTwo">
                                        Medication
                                    </button>
                                </h2>
                                <div id="flush-collapseTwo" class="accordion-collapse collapse visit-history-data"
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
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapseThree" aria-expanded="false"
                                        aria-controls="flush-collapseThree">
                                        Visit History
                                    </button>
                                </h2>
                                <div id="flush-collapseThree" class="accordion-collapse collapse visit-history-data"
                                    aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body" id="loadSessionRecords"></div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingFour">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapseFour" aria-expanded="false"
                                        aria-controls="flush-collapseFour">
                                        Latest Lab Reports
                                    </button>
                                </h2>
                                <div id="flush-collapseFour" class="accordion-collapse collapse"
                                    aria-labelledby="flush-headingFour" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        <div class="card" style="width: 100%">
                                            <ul class="list-group list-group-flush" id="loadLabReports">

                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingFive">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapseFive" aria-expanded="false"
                                        aria-controls="flush-collapseFive">
                                        Latest Imaging Reports
                                    </button>
                                </h2>
                                <div id="flush-collapseFive" class="accordion-collapse collapse"
                                    aria-labelledby="flush-headingFive" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body table-family">
                                        <div class="card" style="width: 100%">
                                            <ul class="list-group list-group-flush" id="loadImagingReports">

                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
