<!-- Option 1: Bootstrap Bundle with Popper -->
<script
  src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
  crossorigin="anonymous"
></script>
<!-- <script src="{{ asset('assets/js/custom.js ')}}"></script>
<script src="{{ asset('assets/js/chatbot.js ')}}"></script> -->
<script src="{{ asset('assets/js/minifyCustom.js ')}}"></script>
<script src="{{ asset('assets/js/minifyChatbot.js ')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="{{ asset('/js/app.js') }}"></script>
<script type="text/javascript">
    <?php header("Access-Control-Allow-Origin: *"); ?>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    Echo.channel('get-msg')
        .listen('SendMessage', (e) => {
            if(e.user_id == ip && e.text == "user")
            {
                $('#pop').addClass('fa fa-circle');
                $('.Messages_list').append('<div class="msg"><span class="avtr"><figure style="background-image: url(https://demo.umbrellamd-video.com/assets/images/logo.png)"></figure></span><span class="responsText">'+e.msg+'</span></div>');
                var lastMsg = $('.Messages_list').find('.msg').last().offset().top;
	            $('.Messages').animate({scrollTop: lastMsg}, 'slow');
            }
        });

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
            $('#img').attr("src",e.doc_image);
            $('.name').text('Dr. '+e.doc_name);
            $('#callingNewModal').modal('show');
            $('#session_user_id').val(e.session_id);
            sessionStorage.setItem("time1", 'null');
            $('#patientVideoCallingAcceptBtn').attr('href','/pat/video/page/'+e.session_id);
        }
    });

    Echo.channel('load_appointment_patient_in_queue')
        .listen('LoadAppointmentPatientInQueue', (e) => {
            var pat_id="{{ Auth::user()->id ?? '0' }}";
            if(e.patient_id==pat_id)
            {
                window.location.href='/waiting/room/'+e.session_id;
            }
        });

    Echo.channel('count_user_cart_item')
        .listen('CountCartItem', (e) => {
        var user_id = "{{ Auth::user()->id ?? '0' }}";
        if(e.user_id==user_id)
        {
            $('#afterLogin').modal('show');
            $('#cart_counter').text(e.cart_conunt);
            $('#cart_counter_res').text(e.cart_conunt);
        }
    });
    Echo.channel('events')
    .listen('RealTimeMessage',(e)=> {
        var user_id="{{ Auth::user()->id ?? '0'}}";
        if(e.user_id==user_id)
        {
            if(e.getNote!='' || e.getNote!=null)
            {
                $('#noteData').html('');

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
                        $('#noteData').append(
                            '<a href="/ReadNotification/'+note.id+'" >'+
                                '<div class="sec_ newNotifications" style="background-color: #fcfafa !important;">'+
                                    '<span class="nav_notification float-right">New</span>'+
                                    '<div class="txt_ not_text">'+note.text+'</div>'+
                                    '<div class="txt_ not_subtext"><i class="zmdi zmdi-time"></i> '+noteTime+'</div>'+
                                '</div>'+
                            '</a>'
                        );
                    }
                    else
                    {
                        $('#noteData').append(
                            '<a href="/ReadNotification/'+note.id+'">'+
                                '<div class="sec_  oldNotifications" style=" background-color: #ffffff !important;">'+
                                    '<span class="nav_notification_seen float-right">Seen</span>'+
                                    '<div class="txt_ not_text">'+note.text+'</div>'+
                                    '<div class="txt_ not_subtext"><i class="zmdi zmdi-time"></i> '+noteTime+'</div>'+
                                '</div>'+
                            '</a>'
                        );
                    }

                });
            }
            if(e.countNote!='' || e.countNote!=null)
            {
                if(e.countNote>10)
                {
                    $('#countNote').text('10+');
                }else{
                    $('#countNote').text(e.countNote);
                }
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
    if(user_id!=0){
        make_offline();
    }
});

function make_offline(){
    var type="{{ Auth::user()->user_type ?? 'patient' }}";
    var status="{{ Auth::user()->status ?? 'offline' }}";
    if(type=="doctor" && status=="online")
    {
        var Count=900;
        var storeTimeInterval=setInterval(() => {
            Count--;
            if(Count<1)
            {
            clearInterval(storeTimeInterval);
            $.ajax({
                type: "POST",
                url: "/modal_change_status",
                data: {
                    check: "",
                },
                success: function(data) {
                    if(data=='offline'){
                        $('#ask_change_status').modal('show');
                    }
                },
            });
            }
        }, 1000);
    }
}


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
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mouse0270-bootstrap-notify/3.1.5/bootstrap-notify.min.js"></script>
