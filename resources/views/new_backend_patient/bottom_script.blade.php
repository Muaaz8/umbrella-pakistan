
	</div>
</div>
<div class="modal fade" id="callingNewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <input type="hidden" id="session_user_id">
        <div class="modal-content calling-modal">
            <div class="">
                <div id="phone">
                    <div class="main">
                        <div class="header-background"></div>
                        <div class="avatar-wrapper">
                            <img id="img" src='https://avatars3.githubusercontent.com/u/34525547' alt="">
                        </div>
                        <div class="snippet" data-title=".dot-pulse">
                            <div class="stage">
                                <div class="dot-pulse"></div>
                            </div>
                        </div>
                        <h2 class="incoming">Join Session</h2>
                        <h6 class="with--">With Doctor</h6>
                        <h1 class="name">Anas Murtaza</h1>
                    </div>
                    <div class="footer d-flex justify-content-evenly flex-row-reverse">
                        <div class="decline">
                        <span id="callCounter" class="fs-5"></span>
                        </div>
                        <div class="accept">
                            <a id="patientVideoCallingAcceptBtn" class="videoCallingJoinButton text-white" href=""><i class="fas fa-phone "></i></a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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

    Echo.channel('get-msg')
        .listen('SendMessage', (e) => {
            var my_id = "{{ Auth::user()->id ?? '0' }}";
            if(e.user_id == my_id && e.text == "user")
            {
                $('#pop').addClass('fa fa-circle');
                $(".Messages_list").append('<div class="msg"><span class="avtr"><figure style="background-image: url(https://demo.umbrellamd-video.com/assets/images/logo.png)"></figure></span><span class="responsText">'+e.msg+"</span></div>");
                var lastMsg = $('.Messages_list').find('.msg').last().offset().top;
	            $('.Messages').animate({scrollTop: lastMsg}, 'slow');
            }
        });

    Echo.channel('load_appointment_patient_in_queue')
        .listen('LoadAppointmentPatientInQueue', (e) => {
            if(e.patient_id=="{{ auth()->user()->id }}")
            {
                window.location.href='/waiting/room/'+e.session_id;
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
                                    '<img class = "profile" src = "{{asset("assets/images/logo.png")}}">'+
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
                                    '<img class = "profile" src = "{{asset("assets/images/umbrella_white.png")}}">'+
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
                                    '<img class = "profile" src = "{{asset("assets/images/logo.png")}}">'+
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

</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/mouse0270-bootstrap-notify/3.1.5/bootstrap-notify.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>
