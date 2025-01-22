@extends('layouts.dashboard_doctor')

@section('meta_tags')
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">

@endsection
@section('top_import_file')
@endsection
@section('page_title')
    <title>CHCC - Waiting Room</title>
    <style>
        .waiting-check .form-check-input:checked {
            background-color: #4CBB17;
            border-color: #4CBB17;
        }
    </style>
@endsection
@section('bottom_import_file')
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
        $('#loadPatients').load("{{ url('/waiting_room_my_doc') }}");
    }
});
Echo.channel('load_appointment_patient_in_queue')
    .listen('LoadAppointmentPatientInQueue', (e) => {

    if(e.doctor_id=="{{ auth()->user()->id }}")
    {
        $('#loadPatients').load("{{url('waiting_room_load_doc/')}}"+"/"+e.doctor_id);

        tiemCheck(e.appointment_id);
    }
});
Echo.channel('patient_join_call')
    .listen('patientJoinCall', (e) => {
    var doctor_id="{{ auth()->user()->id }}";
    if(e.message=="patient joined session" && doctor_id==e.doctor_id)
    {
        window.location.href='/doc/video/page/'+e.session_id;
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
            console.log(status);
            if (status == 'online')
            {
                // alert('ok');
                $('#status').text('Online');
                $('#status').css('color', '#364d81');
                $('#flexSwitchCheckChecked1').prop('checked', true);

            }
            else if (status == 'offline')
            {
                $('#status').text('Offline');
                $('#status').css('color', 'grey');
                $('#flexSwitchCheckChecked1').prop('checked', false);
            }
        }
    });
    if ($('#msg_text').text() != '')
    {
        $('#msg_btn').trigger("click");
    }

});
$('#symptom_detail').click(function(e){
            e.preventDefault();
            var patient_id = $(this).data('id');
            $.ajax({
                type: "post",
                url: "/get_symptom_data",
                data: {
                    patient_id: patient_id
                },
                success: function (response) {
                    var clinical_evaluation = response.clinical_evaluation;
                    var hypothesis_report= response.hypothesis_report;
                    var intake_notes =response.intake_notes;
                    var referrals_and_tests =response.referrals_and_tests;
                    var html =  '<h2 style="text-align:left;">Clinical Evaluation</h2><p style="text-align:left;">' +clinical_evaluation+'</p>'+
                    '<h2 style="text-align:left;">Hypothesis Report</h2><p style="text-align:left;">' +hypothesis_report+'</p>'+
                    '<h2 style="text-align:left;">Intake Notes</h2><p style="text-align:left;">' +intake_notes+'</p>'+
                    '<h2 style="text-align:left;">Referrals And Tests</h2><p style="text-align:left;">' +referrals_and_tests+'</p>';
                    $('.model_body').html(html);
                    $('#check_symptoms').modal('show');
                }
            });
        })
$('#flexSwitchCheckChecked1').click(function()
{
    $.ajax({
        type: 'POST',
        url: "{{ URL('/change_status') }}",
        success: function(response) {
            // alert(response);
            if(response=='online'){
                $(this).checked=true;
                $('#flexSwitchCheckChecked1').prop('checked', true);
                $('#status').text('Online');
                $('#status').css('color', '#364d81');
                $('#status_color').removeClass('profile_offline');
                $('#status_color').addClass('profile_online');
                make_offline(response);
            }else{
                $(this).checked=false;
                $('#flexSwitchCheckChecked1').prop('checked', false);
                $('#status').text('Offline');
                $('#status').css('color', 'grey');
                $('#status_color').removeClass('profile_online');
                $('#status_color').addClass('profile_offline');
            }
        }
    });
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
                    if (data.timeNow[1]=="seconds") {
                        var timer2 = "00:"+data.timeNow[0];
                    }else{
                        var timer2 = data.timeNow[0]+":01";
                    }
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
            patient_absent(message.message);
        }
    });
}

function patient_absent(msg)
{
    var session_id=$("#now_session_id").val();
    var check_counter=40;
    var storeTimeInterval=setInterval(() => {
        check_counter--;
        if(check_counter<1)
        {
            clearInterval(storeTimeInterval);
            window.location.href='/patient_absent/'+session_id;
        }
        $('#join_btn1').html(msg+' ('+check_counter+')');
    }, 1000);
}
</script>
@endsection
@section('content')
        <div class="dashboard-content">
          <div class="container-fluid">
            <div class="col-11 m-auto">
            <div class="row">
                <div class="d-flex justify-content-between align-items-center p-0">
                <div>
                <h4>Waiting Room</h4>
            </div>
            {{-- <div class="d-flex waiting-check">
                <span class="me-2"><b> Current Status</b> </span>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked1">
                </div>
               </div>
               <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked">
                </div> --}}
            </div>
            </div>

            <div class="row my-4" id="loadPatients">
                @if(isset($sessions[0]) && (count($sessions)-1)!=0)
                <div class="col-md-5 mb-3">
                        <div class="Waiting-next-patient d-flex justify-content-center align-items-center">

                            <div class="card py-4">
                              <div class="logo is-animetion">
                                <span>N</span>
                                <span>E</span>
                                <span>X</span>
                                <span>T</span>
                              </div>
                              <div class="logo is-animetion">
                                <span>P</span>
                                <span>A</span>
                                <span>T</span>
                                <span>I</span>
                                <span>E</span>
                                <span>N</span>
                                <span>T</span>
                              </div>
                               <div class="d-flex justify-content-center align-items-center">
                                 <div class="round-image">
                                    <img src="{{$sessions[0]['user_image']}}" class="rounded-circle" width="97">
                                 </div>
                               </div>

                               <div class="text-center">

                                 <h4 class="mt-3">{{ucwords($sessions[0]['patient_full_name'])}}</h4>
                                @if(isset($sessions[0]) && $sessions[0]['status'] == 'doctor joined')
                                <input type="hidden" value="{{$sessions[0]['session_id']}}" id="now_session_id">
                                <button id="join_btn1" style="font-size:22px"  class="{{$sessions[0]['session_id']}} join-btn" onclick="joinBtnClick()">Join</button> <br>
                                {{--<button id="symptom_detail" style="font-size:22px; background: #08295a;"  class="join-btn" data-id="{{ $sessions[0]['patient_id'] }}" style="margin-top:10px !important;" >Read symptoms</button>--}}
                                @elseif (isset($sessions[0]['appointment_id']))
                                    <button id="waiting_button" style="font-size:16px" onclick="javascript.void(0)" class="btn btn-primary col-12 btn-raised"></button>
                                    <button id="join_btn1" style="font-size:22px" class="{{$sessions[0]['session_id']}} join-btn" onclick="joinBtnClick()">Join</button> <br>
                                {{--<button id="symptom_detail" style="font-size:22px; background: #08295a;"  class="join-btn" data-id="{{ $sessions[0]['patient_id'] }}" style="margin-top:10px !important;" >Read symptoms</button>--}}
                                    <input type="hidden" value="{{$sessions[0]['session_id']}}" id="now_session_id">
                                    <input type="hidden" value="{{$sessions[0]['appointment_id']}}" id="appointment_id">
                                @else
                                    <input type="hidden" value="{{$sessions[0]['session_id']}}" id="now_session_id">
                                    <button id="join_btn1" style="font-size:22px"  class="{{$sessions[0]['session_id']}} join-btn" onclick="joinBtnClick()">Join</button> <br>
                                {{--<button id="symptom_detail" style="font-size:22px; background: #08295a;"  class="join-btn" data-id="{{ $sessions[0]['patient_id'] }}" style="margin-top:10px !important;" >Read symptoms</button>--}}
                                @endif
                               </div>

                            </div>
                             </div>
                </div>
                <div class="col-md-7">
                    <div class="Waiting-patient-queue-div">

                        <div class="content d-flex align-items-start flex-wrap">
                            <div class="list bg-white py-3">
                                <div class="row m-0 px-2 pb-4 border-bottom">
                                    <div class="d-flex align-items-center flex-wrap justify-content-between">
                                        <div class="title mx-lg-2 mx-1">Patients in Queue</div>
                                        <div class="pink-label mx-1">{{count($sessions)-1}}</div>
                                    </div>
                                </div>
                                <div class="table-responsive-lg">
                                    <table class="table">
                                        <tbody>

                                            {{-- <tr class="active">
                                                @foreach($sessions as $key => $session)
                                                @if($key>=1)
                                                <th scope="row"><img src="{{$session['user_image']}}" alt=""></th>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <div class="blue-label">{{ucwords($session['patient_full_name'])}}</div>
                                                    </div>
                                                </td>
                                            </tr> --}}
                                            @foreach($sessions as $key => $session)
                                            @if($key>=1)
                                            <tr>
                                                <td scope="row"><img src="{{$session['user_image']}}" alt="" style="width:80px;border-radius:100%;height:80px;object-fit: cover;"></td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <div class="blue-label">{{ucwords($session['patient_full_name'])}}</div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @elseif(isset($sessions[0]) && (count($sessions)-1)==0)
                <div class="col-md-5 m-auto">
                    <div class="Waiting-next-patient d-flex justify-content-center align-items-center">

                        <div class="card py-4">
                          <div class="logo is-animetion">
                            <span>N</span>
                            <span>E</span>
                            <span>X</span>
                            <span>T</span>
                          </div>
                          <div class="logo is-animetion">
                            <span>P</span>
                            <span>A</span>
                            <span>T</span>
                            <span>I</span>
                            <span>E</span>
                            <span>N</span>
                            <span>T</span>
                          </div>
                           <div class="d-flex justify-content-center align-items-center">
                             <div class="round-image">
                                <img src="{{$sessions[0]['user_image']}}" class="rounded-circle" width="97">
                             </div>
                           </div>

                           <div class="text-center">

                             <h4 class="mt-3">{{ucwords($sessions[0]['patient_full_name'])}}</h4>
                             @if(isset($sessions[0]) && $sessions[0]['status'] == 'doctor joined')
                                <input type="hidden" value="{{$sessions[0]['session_id']}}" id="now_session_id">
                                <button id="join_btn1" style="font-size:22px"  class="{{$sessions[0]['session_id']}} join-btn" onclick="joinBtnClick()">Join</button><br>
                                {{--<button id="symptom_detail" style="font-size:22px; background: #08295a;"  class="join-btn" data-id="{{ $sessions[0]['patient_id'] }}" style="margin-top:10px !important;" >Read symptoms</button>--}}
                            @elseif (isset($sessions[0]['appointment_id']))
                                <button id="waiting_button" style="font-size:16px" onclick="javascript.void(0)" class="btn btn-primary col-12 btn-raised"></button> <br>
                                <button id="join_btn1" style="font-size:22px" class="{{$sessions[0]['session_id']}} join-btn" onclick="joinBtnClick()">Join</button> <br>
                                {{--<button id="symptom_detail" style="font-size:22px; background: #08295a;"  class="join-btn" data-id="{{ $sessions[0]['patient_id'] }}" style="margin-top:10px !important;" >Read symptoms</button>--}}
                                <input type="hidden" value="{{$sessions[0]['session_id']}}" id="now_session_id">
                                <input type="hidden" value="{{$sessions[0]['appointment_id']}}" id="appointment_id">
                            @else
                                <input type="hidden" value="{{$sessions[0]['session_id']}}" id="now_session_id">
                                <button id="join_btn1" style="font-size:22px"  class="{{$sessions[0]['session_id']}} join-btn" onclick="joinBtnClick()">Join</button> <br>
                                {{--<button id="symptom_detail" style="font-size:22px; background: #08295a;"  class="join-btn" data-id="{{ $sessions[0]['patient_id'] }}" style="margin-top:10px !important;" >Read symptoms</button>--}}

                            @endif
                           </div>

                        </div>
                         </div>
                </div>
                @else
                <div class="row m-auto text-center">
                    <div class="text-center for-empty-div">
                        <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                        <h6> No Patients in Queue</h6>
                    </div>
                </div>
                @endif
            </div>


          </div>
        </div>
        </div>
      </div>
    </div>
<div class="modal fade" id="check_symptoms" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
  aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Automated Symptoms Checker</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div>
          <div class="">
            <div class="row justify-content-center p-0 m-0">
              <div class=" text-center p-0">
                <div class="card px-0 ">
                    <div class="model_body" style="padding: 20px;">

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
@endsection

@section('script')
<script>

</script>
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
      crossorigin="anonymous"
    ></script>

@endsection
