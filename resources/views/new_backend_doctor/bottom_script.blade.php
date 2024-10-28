
	</div>
</div>
<!-- ------------------Delete-Button-Modal-start------------------ -->

            <!-- Modal -->
            <div class="modal fade" id="ask_change_status" tabindex="-1" aria-labelledby="ask_change_statusLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="ask_change_statusLabel">Status Changed</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="ask_change_status-modal-body text-dark p-5">
                            Because you were not active for last 15 minutes that's why we have changed your status to offline
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-bs-dismiss="modal">Ok</button>
                    </div>
                </div>
                </div>
            </div>


    <!-- ------------------Delete-Button-Modal-start------------------ -->
<script
  src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
  crossorigin="anonymous"
></script>
<script src="{{ asset('assets/js/dashboard_custom.js') }}"></script>
<script src="{{ asset('/js/app.js') }}"></script>
<script type="text/javascript">
    <?php header("Access-Control-Allow-Origin: *"); ?>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });




    // $('.decline').click(function(){
    //     $('#callingNewModal').hide();
    //     var session_id=$('#session_user_id').val();
    //     window.location.href='/patient/not/call/join/'+session_id;
    // });
    Echo.channel('session-channel')
        .listen('DoctorJoinedVideoSession', (e) => {

        var pat_id="{{ Auth::user()->id ?? '0' }}";
        if(e.message=="doctor joined session"  && e.patient_id==pat_id)
        {
            var callCount=30;
            var storeTimeInterval=setInterval(() => {
              callCount--;
              if(callCount<1)
              {
                clearInterval(storeTimeInterval);
                window.location.href='/new/patient/not/join/call/'+e.session_id;
              }
              $('#callCounter').html('('+callCount+")");
            }, 1000);
            $('#callingNewModal').modal('show');
            $('#session_user_id').val(e.session_id);
            sessionStorage.setItem("time1", 'null');
            $('.videoCallingJoinButton').attr('href','/pat/video/page/'+e.session_id);
        }
    });

    Echo.channel('count_user_cart_item')
        .listen('CountCartItem', (e) => {
        var user_id = "{{ Auth::user()->id ?? '0' }}";
        if(e.user_id==user_id)
        {
            $('#afterLogin').modal('show');
            $('#cart_counter').text(e.cart_conunt);
        }
    });
    Echo.channel('events')
    .listen('RealTimeMessage',(e)=> {
        var user_id="{{ Auth::user()->id ?? '0'}}";
        if(e.user_id==user_id)
        {
            if(e.getNote!='' || e.getNote!=null)
            {
                $('#notif').html('');

                $.each (e.getNote, function (key, note) {

                    var today = new Date();

                    var Christmas = new Date(note.created_at);

                    var diffMs = (today-Christmas); // milliseconds between now & Christmas
                    var diffDays = Math.floor(diffMs / 86400000); // days
                    var diffHrs = Math.floor((diffMs % 86400000) / 3600000); // hours
                    var diffMins = Math.round(((diffMs % 86400000) % 3600000) / 60000); // minutes
                    var noteTime='';

                    if(diffDays<=0)
                    {

                        if(diffHrs<=0)
                        {
                            if(diffMins<=0)
                            {
                                noteTime='0 mint ago';
                            }
                            else
                            {
                                noteTime=diffMins+' mints ago';
                            }
                        }
                        else
                        {
                            noteTime=diffHrs +' hours ago';
                        }
                    }
                    else{

                        if(diffDays==1)
                        {
                            noteTime=diffDays+' day ago';
                        }else{
                            noteTime=diffDays+' day ago';
                        }
                    }
                    if(note.status=='new')
                    {
                        $('#notif').append(
                          '<div class = "sec new">'+
                            '<a href="/ReadNotification/'+note.id+'" >'+
                                '<div class = "profCont">'+
                                    '<img class = "profile" src = "{{asset("assets/images/notifyuser.png")}}">'+
                                  '</div>'+
                                  '<div class="txt">'+note.text+'</div>'+
                                  '<div class = "txt sub">'+noteTime+'</div>'+
                            '</a>'+
                          '</div>'
                        );
                    }
                    else
                    {
                        $('#notif').append(
                          '<div class = "sec">'+
                            '<a href="/ReadNotification/'+note.id+'" >'+
                                '<div class = "profCont">'+
                                    '<img class = "profile" src = "{{asset("assets/images/notifyuser.png")}}">'+
                                  '</div>'+
                                  '<div class="txt">'+note.text+'</div>'+
                                  '<div class = "txt sub">'+noteTime+'</div>'+
                            '</a>'+
                          '</div>'
                        );
                    }

                });
            }
            if(e.countNote!='' || e.countNote!=null)
            {
                $('#countNote').text(e.countNote);
            }
            if(e.toastShow!='' || e.toastShow!=null)
            {
                $.each (e.toastShow, function (key, toast) {
                    $.notify(
                        {
                            title: "<strong>1 New Notification</strong>",
                            message: "<br>"+toast.text,
                            icon: 'fas fa-bell',
                        },
                        {
                            type: "info",
                            allow_dismiss: true,
                            delay: 3000,
                            placement: {
                            from: "bottom",
                            align: "right"
                            },
                        }
                    );
                });

            }
        }
});



    $(document).ready(function(){
        var user_id="{{ Auth::user()->id ?? '0' }}";
        if(user_id!=0)
        {
            $.ajax({
                url: "{{url('/Toaster')}}",
                type: "get",
                data: {
                        user_id:"{{ Auth::user()->id ?? '0' }}"
                        },
                success: function(data)
                {
                    if(data!='' || data!=null)
                    {
                        $.each (data, function (key, toast) {
                            $.notify(
                                {
                                    title: "<strong>1 New Notification</strong>",
                                    message: "<br>"+toast.text,
                                    icon: 'fas fa-bell',
                                },
                                {
                                    type: "info",
                                    allow_dismiss: true,
                                    delay: 3000,
                                    placement: {
                                    from: "bottom",
                                    align: "right"
                                    },
                                }
                            );
                        });
                    }
                }
            });
        }
    });
    function myFunction() {
    $.ajax({
                type: "POST",
                url: "/ReadAllNotifications",
                data: {
                    check: "",
                },
                success: function(data) {

                },
            });
}



$( "#unreadmsgs" ).click(function() {
    $.ajax({
            type: "POST",
            url: "/GetUnreadNotifications",
            data: {
                check: "",
            },
            success: function(data) {
                $('#notif').html('');

                $.each (data, function (key, note) {
                    var today = new Date();

                    var Christmas = new Date(note.created_at);

                    var diffMs = (today-Christmas); // milliseconds between now & Christmas
                    var diffDays = Math.floor(diffMs / 86400000); // days
                    var diffHrs = Math.floor((diffMs % 86400000) / 3600000); // hours
                    var diffMins = Math.round(((diffMs % 86400000) % 3600000) / 60000); // minutes
                    var noteTime='';

                    if(diffDays<=0)
                    {

                        if(diffHrs<=0)
                        {
                            if(diffMins<=0)
                            {
                                noteTime='0 mint ago';
                            }
                            else
                            {
                                noteTime=diffMins+' mints ago';
                            }
                        }
                        else
                        {
                            noteTime=diffHrs +' hours ago';
                        }
                    }
                    else{

                        if(diffDays==1)
                        {
                            noteTime=diffDays+' day ago';
                        }else{
                            noteTime=diffDays+' day ago';
                        }
                    }

                $('#notif').append(
                  '<div class = "sec new">'+
                    '<a href="/ReadNotification/'+note.id+'" >'+
                        '<div class = "profCont">'+
                            '<img class = "profile" src = "{{asset("assets/images/notifyuser.png")}}">'+
                          '</div>'+
                          '<div class="txt">'+note.text+'</div>'+
                          '<div class = "txt sub">'+noteTime+'</div>'+
                    '</a>'+
                  '</div>'
                );
            });
        },
    });
});

function make_offline(s){
    var type="{{ auth()->user()->user_type }}";
    var status=s;
    if(type=="doctor" && status=="online")
    {
        var Count=900;
        var storeTimeInterval=setInterval(() => {
            Count--;
            if(Count<1)
            {
                clearInterval(storeTimeInterval);
                $.ajax({
                    type: "GET",
                    url: "/modal_change_status",
                    data: {
                        check: "",
                    },
                    success: function(data) {
                        if(data=='offline'){
                            $('#status').text('Offline');
                            $('#status').css('color', 'grey');
                            $('#flexSwitchCheckChecked').prop('checked', false);
                            $('#status_color').removeClass('profile_online');
                            $('#status_color').addClass('profile_offline');
                            $('#ask_change_status').modal('show');
                        }
                    },
                });
            }
        }, 1000);
    }
}

$(document).ready(function() {
    // check appointment time
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
                $('#flexSwitchCheckChecked').prop('checked', true);
                $('#status_color').removeClass('profile_offline');
                $('#status_color').addClass('profile_online');
                make_offline(status);
            }
            else if (status == 'offline')
            {
                $('#status').text('Offline');
                $('#status').css('color', 'grey');
                $('#flexSwitchCheckChecked').prop('checked', false);
                $('#status_color').removeClass('profile_online');
                $('#status_color').addClass('profile_offline');
            }
        }
    });
    if ($('#msg_text').text() != '')
    {
        $('#msg_btn').trigger("click");
    }

});



$('#flexSwitchCheckChecked').click(function()
{
    $.ajax({
        type: 'GET',
        url: "{{ URL('/change_status') }}",
        success: function(response) {
            if(response=='online'){
                $(this).checked=true;
                $('#flexSwitchCheckChecked').prop('checked', true);
                $('#status').text('Online');
                $('#status').css('color', '#364d81');
                $('#status_color').removeClass('profile_offline');
                $('#status_color').addClass('profile_online');
                make_offline(response);
            }else{
                $(this).checked=false;
                $('#flexSwitchCheckChecked').prop('checked', false);
                $('#status').text('Offline');
                $('#status').css('color', 'grey');
                $('#status_color').removeClass('profile_online');
                $('#status_color').addClass('profile_offline');
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

</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mouse0270-bootstrap-notify/3.1.5/bootstrap-notify.min.js"></script>


