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
        var channel = "{{$session->channel}}";

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
        var channel = "{{$session->channel}}";
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
                    var userid = "{{$session->doctor_id}}";
                    var channel = "{{$session->channel}}";
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
    var channel = "{{$session->channel}}";
    var uid = "{{$session->doctor_id}}";
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

</script>
@endsection @section('content')
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
<section>
    <div class="container-fluid">
        <!-- <div class="row">
            <div class="col-12">
                <div class="call-screen-header">
                    <div class="d-flex align-items-center">
                        <i class="fa-solid fa-user-doctor"></i>
                        <h6>
                            Dr.
                        </h6>
                    </div>
                    <div class="call-time">
                        <h5 id="time">15 Minutes : 00 Seconds</h5>
                    </div>
                </div>
            </div>
        </div> -->

        <div class="row mt-3">
            <div class="col-lg-12">
                    <div class="big-video-screen" id="remote-playerlist" style="height: 69vh;">
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
                            <!-- <button class="end-call" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                <i class="fa-solid fa-phone-slash"></i> END
                            </button> -->

                            <button class="mic-call" id="mic_mute">
                                <i class="fa-solid fa-microphone-lines" id="mic_icon"></i>MIC
                            </button>
                        </div>
                        <!-- <div class="patient-name">
                            <div>
                                <h6>Patient Name:</h6>
                                <p>

                                </p>
                            </div>
                            <div>
                                <h6>Patient Age:</h6>
                                <p>

                                </p>
                            </div>
                        </div> -->
                    </div>
                <!-- <div class="call-disclaimer mb-3">
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
