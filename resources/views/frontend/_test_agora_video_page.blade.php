<link href="{{ asset('asset_frontend/css/bootstrap.min.css')}}" rel="stylesheet">
<style type="text/css">
   #local-player{
      height: 100%;
   }
   #remote-playerlist{
      height: 100%;
   }
  .doctorDiv1{
	  height: 100%;
	  background-color: royalblue;
  }
  .doctorDiv2{
	  bottom: 0px;
	  position: absolute;
	  height: 35%;
	  background-color:pink;
  }
  .patientDiv1{
	  height: 100%;
	  background-color:royalblue;
  }
  .patientDiv2{
	  bottom: 0px;
	  position: absolute;
	  height: 35%;
	  background-color:pink;
  }
  .mainClass{
	  height: 400px;
	  background-color: salmon;
  }
  .andcallBTN{
	  position: absolute;
	  left:0;
	  right:0;
	  bottom: 5%;
  }
</style>
<input id="appid" type="hidden" value="13c2b19a4baa44afa12f7aa3f5529bea">
<input id="channel" type="hidden"value="baqir">
<input id="token" type="hidden"value="">
<input id="uid" type="hidden" value="<?php echo rand(1,100);?>">
<input id="resID" type="hidden">
<input id="sID" type="hidden">
@if($user_type=='patient')
<div class="col-12">
  <div class="row">
      <div class="mainClass col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 pl-0 pr-0">
         <div class="doctorDiv1 col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 pl-0 pr-0">
            <div id="local-player">

            </div>
         </div>
         <div class="doctorDiv2 col-4 col-sm-3 col-md-4 col-lg-3 col-xl-3 pl-0 pr-0">
            <div id="remote-playerlist">

            </div>
         </div>
         <div class="button-group text-center andcallBTN">
            <button id="leave" type="button" class="btn btn-danger btn-sm">End Call</button>
         </div>
      </div>
  </div>
</div>
@else

<div class="col-12">
  <div class="row">
      <div class="mainClass col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 pl-0 pr-0">
         <div class="patientDiv1 col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 pl-0 pr-0">
            <div id="remote-playerlist">
            </div>
         </div>
         <div class="patientDiv2 col-4 col-sm-3 col-md-4 col-lg-3 col-xl-3 pl-0 pr-0">
            <div id="local-player">
            </div>
         </div>
      </div>
  </div>
</div>
@endif



<script src="{{ asset('asset_frontend/js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('asset_frontend/js/bootstrap.min.js') }}"></script>

<script src="https://download.agora.io/sdk/release/AgoraRTC_N-4.12.1.js"></script>
<script>
   $(document).ready(function()
   {
       $("#loading").show();
      $("#end_meeting").hide();
      $("#exit_meeting_div").hide();
   });
   $("#leave").click(function (e) {
   endCall();
   leave();
   updatede();
   });

function updatede()
{
      var doctor_notes = CKEDITOR.instances['patient_detail'].getData();
      var app_id = $("#aid").val();
      var _token = $('meta[name="csrf-token"]').attr('content');
      $.ajax({
         headers: {  'Access-Control-Allow-Origin': 'http://The web site allowed to access' },
         url: "/upDateAppointmentStatus",
         type: "POST",
         data: {
            app_id: app_id,
            _token: _token,
            doctor_notes: doctor_notes
         },
         success: function(response) {

            window.location.href='/appointment';
         },
      });
}
function aquire_start()
{
   var resID = getCookie("resID");
   var sidVal = getCookie("sidVal");
   var channelVal = getCookie("channelVal");
   if (resID != "" && sidVal != "" && channelVal != "" && resID != null && sidVal != null && channelVal != null )
   {
      $("#resID").val(resID);
      $("#sID").val(sidVal);
      //alert(resID+"="+sidVal+"="+channelVal);
   }
   else
   {
      var channelName=$("#channel").val();
      var _token = $('meta[name="csrf-token"]').attr('content');
      $.ajax({
         type: 'GET',
         dataType:"text",
         data:{
               _token: _token
            },
         url: 'https://www.umbrellamd.org/agora_api/only_aquire.php?channel='+$("#channel").val(),
         success: function (data) {
            if(data!='')
            {
               // console.log('run');
               var _token = $('meta[name="csrf-token"]').attr('content');
               $.ajax({
                  type: 'GET',
                  dataType:"json",
                  data:{
                     _token: _token
                  },
                  url: 'https://www.umbrellamd.org/agora_api/only_start.php?channel='+$("#channel").val()+'&resID='+data,
                  success: function (success) {
                     // console.log(success);
                     var string1 = JSON.stringify(success);
                     var parsed = JSON.parse(string1);
                     $("#resID").val(parsed.resourceId);
                     $("#sID").val(parsed.sid);
                     setCookie(parsed.resourceId,parsed.sid,channelName);
                  }
               });
            }
         }
      });
   }
}

function updateStatus(videoNmae)
{
   var _token = $('meta[name="csrf-token"]').attr('content');
   var hash = 342432;
   $.ajax({
      type:'GET',
      dataType:'json',
      data:{
         videoName:videoNmae,
         hash:hash,
         _token:_token
      },
      url:'/saveVideoAndUpdateAppointment',
      success:function(responses){
         if(responses!=null)
         {
           console.log('ok');
         }
      }
   });
}
function endCall()
{
   var hash =12313;
   var channel=$('#channel').val();
   var resID=$('#resID').val();
   var sID=$('#sID').val();
   var _token = $('meta[name="csrf-token"]').attr('content');
   $.ajax({
      type:'GET',
      dataType:'json',
      data:{
         _token: _token
      },
      url:'https://www.umbrellamd.org/agora_api/only_stop.php?channel='+channel+'&resID='+resID+'&sid='+sID+'&hash='+hash,
      success:function(responses){}
   });
}

var client = AgoraRTC.createClient({ mode: "rtc", codec: "vp8" });
var localTracks = {
videoTrack: null,
audioTrack: null
};
var remoteUsers = {};
const joincall=async function join()
{

  // aquire_start();
   // Add an event listener to play remote tracks when remote user publishes.
   client.on("user-published", handleUserPublished);
   client.on("user-unpublished", handleUserUnpublished);
   // Join a channel and create local tracks. Best practice is to use Promise.all and run them concurrently.
   [ uid, localTracks.audioTrack, localTracks.videoTrack ] =await Promise.all([
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
   console.log("publish success");
}

var appid = $("#appid").val();
var channel =$("#channel").val();
var uid = $("#uid").val();
var token = $("#token").val();
if(appid!=null && channel!=null)
{
joincall();
}

function leave()
{
   for (trackName in localTracks) {
   var track = localTracks[trackName];
   if(track) {
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
   console.log("client leaves channel success");
}

async function subscribe(user, mediaType)
{
   const uid = user.uid;
   // subscribe to a remote user
   await client.subscribe(user, mediaType);
   console.log("subscribe success");
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

function handleUserPublished(user, mediaType)
{
   const id = user.uid;
   remoteUsers[id] = user;
   subscribe(user, mediaType);
}

function handleUserUnpublished(user, mediaType)
{
   if (mediaType === 'video') {
   const id = user.uid;
   delete remoteUsers[id];
   $(`#player-wrapper-${id}`).remove();
   }
}

function setCookie(resVal,sidVal,channelVal)
{
   const d = new Date();
   d.setTime(d.getTime() + (1*60*1000));
   var expires = "expires=" + d.toGMTString();
   document.cookie = "resID=" + resVal + ";" + expires + ";path=/";
   document.cookie = "sidVal=" + sidVal + ";" + expires + ";path=/";
   document.cookie = "channelVal=" + channelVal + ";" + expires + ";path=/";
}

function getCookie(cname)
{
   var name = cname + "=";
   var decodedCookie = decodeURIComponent(document.cookie);
   var ca = decodedCookie.split(';');
   for(var i = 0; i < ca.length; i++) {
   let c = ca[i];
   while (c.charAt(0) == ' ') {
      c = c.substring(1);
   }
   if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
   }
   }
   return "";
}
</script>
