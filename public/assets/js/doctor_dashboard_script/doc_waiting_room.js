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