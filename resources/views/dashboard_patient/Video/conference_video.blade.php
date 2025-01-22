@extends('layouts.new_video_calling') @section('meta_tags')
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon" />
@endsection @section('page_title')
<title>CHCC - Doctor Video Calling</title>
@endsection @section('top_import_file')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<link rel="stylesheet" href="{{asset('css/style.css')}}">
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
        var channel = "{{$therapy_session->channel}}";
        var appID = "{{ env('AGORA_APP_ID') }}";
        var start_time = "{{$time}}";
        CountDown(start_time);
    });
    function CountDown(duration) {
        if (!isNaN(duration)) {
            var timer = duration,
                hours,
                minutes,
                seconds;
            var interVal = setInterval(function () {
                hours = parseInt(timer / 60 / 60 % 60, 10);
                minutes = parseInt(timer / 60 % 60, 10);
                seconds = parseInt(timer % 60, 10);
                hours = hours < 10 ? "0" + hours : hours;
                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;
                $(".timer__").text(hours + " : " + minutes + " : " + seconds + " ");
                ++timer;
            }, 1000);
        }
    }

    Echo.channel('end_conference_call')
        .listen('EndConferenceCall', (e) => {
            if (e.session_id == "{{ $therapy_session->id }}") {
                leave();
                window.location.href = '/patient/end/conference/call/0';
            }
        });

    Echo.channel('hand_raise')
        .listen('HandRaise', (e) => {
            if (e.session_id == "{{ $therapy_session->id }}" && e.pat_id!= "{{ auth()->user()->id }}") {
                $('#hand_'+e.pat_id).toggleClass('fa-hand');
                $('#hand_'+e.pat_id).toggleClass('wave');
            }
        });
    Echo.channel('end_patient_call')
        .listen('PatientCallEnd', (e) => {
            if (e.session_id == "{{ $therapy_session->id }}" && e.pat_id!= "{{ auth()->user()->id }}") {
                delete remoteUsers[e.pat_id];
                $(`#player-wrapper-${e.pat_id}`).remove();
                $(`#pat-head-${e.pat_id}`).remove();
            }
        });

    $("#mic_icon").click(function () {
        if ($("#mic_icon").hasClass('fa-microphone-slash')) {
            localTracks.audioTrack.setEnabled(true);
            $('#mic_icon').prop('title', 'mute');
            $("#mic_icon").toggleClass('fa-microphone-slash');
            $("#local_mic").toggleClass('fa-microphone-slash');
        }
        else {
            localTracks.audioTrack.setEnabled(false);
            $('#mic_icon').prop('title', 'unmute');
            $("#mic_icon").toggleClass('fa-microphone-slash');
            $("#local_mic").toggleClass('fa-microphone-slash');
        }
    });
    $("#vid_icon").click(function () {
        if ($("#vid_icon").hasClass('fa-video-slash')) {
            localTracks.videoTrack.play("local-player");
            localTracks.videoTrack.setEnabled(true);
            $('#vid_icon').prop('title', 'Off');
            $("#vid_icon").toggleClass('fa-video-slash');
        }
        else {
            $('#local-player').html('<div class="user_Name d-flex align-items-center">'
            +'<div>'+my_name+'</div><i id="local_hand" class="fa-solid"></i></div>');
            localTracks.videoTrack.setEnabled(false);
            $('#vid_icon').prop('title', 'On');
            $("#vid_icon").toggleClass('fa-video-slash');
        }
    });
    // ================= For Visit History Details Ends================

    // ================= For AOEs Div Starts================

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
        var channel = "{{$therapy_session->channel}}";

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
        var channel = "{{$therapy_session->channel}}";
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
                    var userid = "{{ auth()->user()->id }}";
                    var channel = "{{$therapy_session->channel}}";
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
        //aquire_start();
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
    var channel = "{{$therapy_session->channel}}";
    var uid = "{{ auth()->user()->id }}_{{$therapy_session->pat_name}}";
    var token = '';
    var my_name = "{{$therapy_session->pat_name}}";

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
        const idname = user.uid.split('_');
        const uid = idname[0];
        const username = idname[1];
        // subscribe to a remote user
        await client.subscribe(user, mediaType);

        if(document.getElementById("player-wrapper-"+uid) === null)
        {
            const player = $(`
            <div id="player-wrapper-${uid}" class="doc_user position-relative">
                <div id="pat-name-${uid}" class="user_Name">${username}
                    <i id="hand_${uid}" class="fa-solid"></i><i id="mic_${uid}" class="fa-solid"></i></div>
            </div>
       `    );
            $('#mems').append('<li id="pat-head-'+uid+'"><i class="fa-regular fa-user px-1"></i>'+username+'</li>');
            $("#remote-playerlist").append(player);
        }
        if (mediaType === 'video') {
            user.videoTrack.play(`player-wrapper-${uid}`);
        }
        if (mediaType === 'audio') {
            user.audioTrack.play();
        }

    }

    function handleUserPublished(user, mediaType) {
        const idname = user.uid.split('_');
        const id = idname[0];
        if(mediaType === 'audio')
        {
            $('#mic_'+id).toggleClass('fa-microphone-slash');
        }
        remoteUsers[id] = user;
        subscribe(user, mediaType);
        // const idname = user.uid.split('_');
        // const id = idname[0];
        // remoteUsers[id] = user;
        // if(mediaType === 'video')
        // {
        //     if(document.getElementById("player-wrapper-"+id) === null)
        //     {
        //         subscribe(user, mediaType);
        //     }
        //     else
        //     {
        //         user.videoTrack.play(`player-wrapper-${id}`);

        //     }
        // }
        // else
        // {
        //     subscribe(user, mediaType);
        //     $('#mic_'+id).toggleClass('fa-microphone-slash');
        // }
        // $('#video_pat').hide();
        // $('#video_doc').hide();
    }

    function handleUserUnpublished(user, mediaType) {
        if (mediaType === 'audio') {
            const idname = user.uid.split('_');
            const id = idname[0];
            $('#mic_'+id).toggleClass('fa-microphone-slash');
            // delete remoteUsers[id];
            // $(`#player-wrapper-${id}`).remove();
            // $(`#pat-head-${id}`).remove();
            // $('#video_pat').show();
            // $('#video_doc').show();
        }
    }

    // ================= video code end================



    $('#end_session').click(function () {
        //endCall();
        leave();
        window.location.href="/patient/end/conference/call/{{$therapy_session->id}}";
    });

    /* Set the width of the side navigation to 250px */
    function openNav() {
        document.getElementById("mySidenav").style.width = "250px";
    }

    /* Set the width of the side navigation to 0 */
    function closeNav() {
        document.getElementById("mySidenav").style.width = "0";
    }

    function raise_hand()
    {
        $.ajax({
            type: "POST",
            url: "/raise/hand",
            data: {
                session_id: "{{ $therapy_session->id }}",
                pat_id: "{{ auth()->user()->id }}",
            },
            success: function(resp) {
                if(resp=='ok')
                {
                    $('#local_hand').toggleClass('fa-hand');
                    $('#local_hand').toggleClass('wave');
                    $('#raise').toggleClass('hand_color');
                }
            },
        });
    }
</script>
@endsection @section('content')
<section class="container-fluid">
<div class="row">
        <div class="col-12">
          <div class="call-screen-header">
            <div class="d-flex align-items-center">
              <i class="fa-solid fa-user-doctor"></i>
              <h6>{{$therapy_session->doc_name}}</h6>
            </div>
            <div class="call-time">
              <!-- <h5>14 Minutes : 16 Seconds</h5> -->
              <h5 class="timer__">00:00:00</h5>
            </div>
          </div>
        </div>
      </div>
      <div>
      <div style="height: 545px;width: 100%">
          <div id="remote-playerlist" class="ahmer">
            <div id="local-player" class="doc_user position-relative">
                <div class="user_Name d-flex align-items-center">
                    <div>{{$therapy_session->pat_name}}</div>
                    <i id="local_hand" class="fa-solid"></i>
                    <i id="local_mic" class="fa-solid"></i>
                </div>
            </div>
          </div>
        </div>
      </div>
      <div class="py-2">
        <div class="row m-0 align-items-center">
          <div class="col-lg-3">
            <!-- <div class="d-flex align-items-center">
                <p class="timer__">00:00:00</p>
            </div> -->
          </div>
          <div class="col-lg-6">
            <div class="video-iconss">
              <i id="mic_icon" class="fa fa-microphone "></i>
              <i id="end_session" class="fa-solid fa-phone "></i>
              <i id="vid_icon" class="fa-solid fa-video "></i>
              <i id="raise" onclick="raise_hand()" class="fa-regular fa-hand "></i>
            </div>
          </div>
          <div class="col-lg-3">
            <div class="multiple__options d-flex justify-content-end">
                {{-- <i class="fa fa-circle" style="font-size:36px;"></i> --}}
                {{-- <i class="fa-solid fa-users"></i> --}}
            </div>
          </div>
        </div>
      </div>
    </section>
@endsection
