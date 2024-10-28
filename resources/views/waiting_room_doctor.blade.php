@extends('layouts.admin')
@section('css')
<style>
.switch input:checked+span {
    background-color: #364d81;
}

.switch input:not(:checked)+span {
    background-color: grey;
}
</style>
@endsection
@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="block-header mb-0 clearfix row">
            <div class="col-md-10">
                <h2>Waiting Room</h2>
            </div>
            <div class="col-md-2 pt-2">
                <h5 id="status" class="mr-2 mt-1 float-left" style="color:#13376C;"></h5>
                <label class="switch mb-0 float-left">
                    <input id="status_check" type="checkbox" stlye="color:#13376C;" name="status">
                    <span class="slider round"></span>
                </label>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card mb-5 pb-5">
                    <div class="header">
                        <h2>Awaiting Patients<small class="col-md text-white">All awaiting patients are listed here</small></h2>
                    </div>
                    <div class="col-md-12" id="loadPatient">

                        @if(isset($sessions[0]))
                            <div class="offset-3 col-5 border" style="background:linear-gradient(45deg, #5e94e4 , #08295a) !important;">
                                <h3 class="pt-2 text-white">Next Patient</h3>

                                <img src="{{$sessions[0]['user_image']}}" class="img-thumbnail rounded-circle boeder-0 p-0 " style="height:60px; width:60px;" alt="profile-image">

                                <span class="m-3 fontt-weight-bold" style="font-size:22px; color:white;">{{ucwords($sessions[0]['patient_full_name'])}}</span>

                                @if (isset($sessions[0]['appointment_id']))
                                    <button id="waiting_button" style="font-size:16px" onclick="javascript.void(0)" class="btn btn-primary col-12 btn-raised"></button>
                                    <button id="join_btn1" style="font-size:22px" class="{{$sessions[0]['session_id']}} btn btn-primary col-12 btn-raised" onclick="joinBtnClick()">Join</button>
                                    <input type="hidden" value="{{$sessions[0]['session_id']}}" id="now_session_id">
                                    <input type="hidden" value="{{$sessions[0]['appointment_id']}}" id="appointment_id">
                                @else
                                    <input type="hidden" value="{{$sessions[0]['session_id']}}" id="now_session_id">
                                    <button id="join_btn1" style="font-size:22px"  class="{{$sessions[0]['session_id']}} btn btn-primary col-12 btn-raised" onclick="joinBtnClick()">Join</button>
                                @endif
                            </div>
                        <div class="offset-3 col-5 p-0">
                            <h5 class="p-2 mb-0 mt-1"
                                style="background:linear-gradient(45deg, #5e94e4 , #08295a) !important;color:white;">
                                Patients In Queue
                                <span class="float-right"
                                    style="border-radius:50px;background-color:white;color:black;padding:0px 6px">
                                    {{count($sessions)-1}}
                                </span>
                            </h5>
                            @if((count($sessions)-1)!=0)
                            @foreach($sessions as $key => $session)
                            @if($key>=1)
                            <div class="list-group-item">
                                <img src="{{$session['user_image']}}" class="img-thumbnail rounded-circle boeder-0 p-0 " style="height:60px; width:60px;" alt="profile-image">
                                <span class="m-3" style="font-size:18px">
                                    {{ucwords($session['patient_full_name'])}}
                                </span>
                            </div>
                            @endif
                            @endforeach
                            @else
                            <div class="list-group-item">
                                <span>No awaiting patient</span>
                            </div>
                            @endif
                        </div>
                        @else
                        <div class="list-group-item">
                            <span>No awaiting patient</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
<script type="text/javascript">
<?php header("Access-Control-Allow-Origin: *"); ?>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
Echo.channel('doc_wating_event')
    .listen('updateDoctorWaitingRoom', (e) => {
    if(e.message=="new_patient_listed")
    {
        $('#loadPatient').load("{{ url('/waiting_room_my') }}");
    }
});
Echo.channel('load_appointment_patient_in_queue')
    .listen('LoadAppointmentPatientInQueue', (e) => {

    if(e.doctor_id=="{{ auth()->user()->id }}")
    {
        $('#loadPatient').load("{{url('waiting_room_load/')}}"+"/"+e.doctor_id);

        tiemCheck(e.appointment_id);
    }
});
Echo.channel('patient_join_call')
    .listen('patientJoinCall', (e) => {
    var doctor_id="{{ auth()->user()->id }}";
    if(e.message=="patient joined session" && doctor_id==e.doctor_id)
    {
        window.location.href='/doctor/video/'+e.session_id;
    }
});


$(document).ready(function() {
    // check appointment time
    var appointment_id=$("#appointment_id").val();

    if(appointment_id!=null || appointment_id=='')
    {
        $('#waiting_button').hide();
        $('#join_btn1').hide();
        tiemCheck(appointment_id);
    }
    $.ajax({
        type: 'GET',
        url: "{{ URL('/check_status') }}",
        data: {},
        success: function(status)
        {
            // console.log(status);
            if (status == 'online')
            {
                $('#status').text('Online');
                $('#status').css('color', '#364d81');
                $('#status_check').prop('checked', true);
            }
            else if (status == 'offline')
            {
                $('#status').text('Offline');
                $('#status').css('color', 'grey');
                $('#status_check').prop('checked', false);
            }
        }
    });
    if ($('#msg_text').text() != '')
    {
        $('#msg_btn').trigger("click");
    }

});

function tiemCheck(appoint_id){
    $.ajax({
            type: 'GET',
            url: "{{ URL('/appointment_time_check') }}",
            data: {
                appointment_id:appoint_id
            },
            success: function(data)
            {
                if(data.timeNow[2]=="after")
                {
                    var timer2 = data.timeNow[0]+":01";
                    var interval = setInterval(function(){
                        var timer = timer2.split(':');
                        var minutes = parseInt(timer[0], 10);
                        var seconds = parseInt(timer[1], 10);
                        --seconds;
                        minutes = (seconds < 0) ? --minutes : minutes;
                        if (minutes < 0) {
                            clearInterval(interval);
                            $('#waiting_button').hide();
                            $('#join_btn1').show();
                        }
                        else{
                            seconds = (seconds < 0) ? 59 : seconds;
                            seconds = (seconds < 10) ? '0' + seconds : seconds;
                            document.getElementById("waiting_button").innerHTML = minutes +":"+seconds+" Time Remain For Next Appointment";
                            $('#waiting_button').show();
                            $('#join_btn1').hide();
                            timer2 = minutes + ':' + seconds;
                        }
                    }, 1000);
                }else{
                    $('#waiting_button').hide();
                    $('#join_btn1').show();
                }
            }
    });
}
//doctor status change
$('input[type="checkbox"]').click(function()
{
    $.ajax({
        type: 'POST',
        url: "{{ URL('/change_status') }}",
        success: function(response) {
            if(response=='online'){
                $(this).checked=true;
                $('#status_check').prop('checked', true);
                $('#status').text('Online');
                $('#status').css('color', '#364d81');
            }else{
                $(this).checked=false;
                $('#status_check').prop('checked', false);
                $('#status').text('Offline');
                $('#status').css('color', 'grey');
            }
        }
    });
    if ($(this).is(":checked")) {
        $('#status').text('Online');
        $('#status').css('color', '#364d81');
    } else if ($(this).is(":not(:checked)")) {
        $('#status').text('Offline');
        $('#status').css('color', 'grey');
    }
});

function joinBtnClick()
{
    var session_id=$("#now_session_id").val();

    $.ajax({
        type:"POST",
        url:"{{route('waitingPatientJoinCall')}}",
        data: {
            session_id:session_id,
        },
        success:function(message)
        {
            $('#join_btn1').html(message.message);
        }
    });
}


</script>
@endsection
