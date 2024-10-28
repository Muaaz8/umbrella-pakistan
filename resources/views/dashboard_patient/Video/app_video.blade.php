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
    var channel = "{{$session->channel}}";
    var uid = "{{$session->patient_id}}";
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
        <!-- <div class="row">
            <div class="col-12">
                <div class="call-screen-header">
                    <div class="d-flex align-items-center">
                        <i class="fa-regular fa-user"></i>
                        <h6>
                            
                        </h6>
                    </div>
                    <div class="call-time">
                        <h5 id="time">15 Minutes : 00 Seconds</h5>
                    </div>
                </div>
            </div>
        </div> -->

        <div class="row mt-3">
            <div class="col-md-12">
                    <div class="big-video-screen" id="remote-playerlist" style="height: 69vh;">
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
                        <!-- <div class="patient-name">
                            <div>
                                <h6>Doctor Name:</h6>
                                <p>
                                    Dr.
                                </p>
                            </div>
                            <div>
                                <h6>Specialization:</h6>
                                <p></p>
                            </div>
                        </div> -->
                    </div>
                <!-- <div class="call-disclaimer">
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
                </div> -->
            </div>

            <!-- <div class="col-md-5">
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
                                        <h6>Symptoms</h6>
                                        <div class="screen-symtoms">
                                            <ul id="loadSymtems"></ul>
                                        </div>
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

                            <div class="accordion-item">
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
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
</section>

@endsection
