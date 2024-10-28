@extends('layouts.admin')
@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2></h2>
            <div class="mt-5 card col-md-8 offset-md-2">
                <span id="doc_id" class="{{$doctor->id}}"></span>
                <div id="doc_profile" class="pt-5">
                    <center>
                        <img src="{{$doctor->user_image}}" class="img-thumbnail rounded-circle" style="height:150px; width:150px;" alt="profile-image">
                        <h3><b>Dr. {{ucwords($doctor->name." ".$doctor->last_name)}}</b></h3>
                    </center>

                    <p style="font-size:20px;text-align:center;" id="text" class="pb-3 mb-0">Click on the "Send Invite" button to send request for session  </p>
                    <p class="font-22" style="text-align:center" id="timer">
                        <span id="display" class="font-weight-bold" style="color:green;font-size:26px"></span>
                    </p>

                    <input hidden id="session_id" value="{{$session_id}}">
                    <input hidden id="user_session_id" value="{{$session_id}}">

                    <center id="loadingButton"> <img src="{{ asset('images/loading-file.gif') }}" alt="Loading Please Wait..." style="height:100px ;width:100px;"></center>
                    <button type="button" id="invite" style="display:none;" onclick="sendInviteClick()" class="btn btn-raised btn-success waves-effect col-12">Send Invite</button>
                    <button type="button" id="farworded" style="display:none;" onclick="javscript.void(0);" class="btn btn-raised btn-info waves-effect col-12">Invitation Already Forwarded</button>

                </div>

                <input id="total_sec" type="hidden">
                <div id="wait"></div>

            </div>

            <div></div>
        </div>

    </div>
    </div>
</section>
@endsection
@section('script')

<script src="{{ asset('/js/app.js') }}"></script>
<script>
<?php header("Access-Control-Allow-Origin: *"); ?>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

Echo.channel('updateQueEvent')
    .listen('updateQuePatient', (e) => {
    if(e.message=="update patient que")
    {
        checkSessionStatus();
    }
});

var requested = false;
var docid = $('#doc_id').attr('class');
var session_id = $('#session_id').val();

$(document).ready(function(){
    checkSessionStatus();
});

function checkSessionStatus()
{
    $.ajax({
        type:"POST",
        url:"{{ route('session.status.check') }}",
        data:{
            session_id:session_id
        },
        dataType:'json',
        success:function(data){
            var status=data.data.status;
            var appointment_id=data.data.appointment_id;
            var que_message=data.data.que_message;
            if(appointment_id=="" || appointment_id==null)
            {
                if(status=="paid")
                {
                    $("#loadingButton").css('display','none');
                    $("#invite").css('display','block');
                    $("#farworded").css('display','none');
                }
                else{
                    $("#loadingButton").css('display','none');
                    $("#invite").css('display','none');
                    $("#timer").html(que_message);
                    $("#farworded").css('display','block');
                }
            }else{

                    $("#loadingButton").css('display','none');
                    $("#invite").css('display','none');
                    $("#timer").html(que_message);
                    $("#farworded").css('display','block');

            }

        }
    });
}

function sendInviteClick()
{
    $("#loadingButton").css('display','block');
    $("#invite").css('display','none');
    $.ajax({
        type:"POST",
        url:"{{ route('invite.session') }}",
        data:{
            session_id:session_id
        },
        dataType:'json',
        success:function(data){
            var status=data.data.status;
            var que_message=data.data.que_message;
            if(status=="invitation sent")
            {
                $("#loadingButton").css('display','none');
                $("#farworded").css('display','block');
                $("#timer").html(que_message);
            }
        }
    });
}
</script>
@endsection
