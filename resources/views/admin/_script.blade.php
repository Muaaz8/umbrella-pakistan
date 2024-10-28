

<style>

    .modalCalling {
        background: #FFF;
        width: 350px;
        height: 220px;
        text-align: center;
        box-shadow: 5px 5px 5px rgba(0,0,0,0.5);
            -moz-box-shadow: 5px 5px 5px rgba(0,0,0,0.5);
                -webkit-box-shadow: 5px 5px 5px rgba(0,0,0,0.5);
        position: fixed;
        top: 40%;
        left: 50%;
        line-height: 25px;
        z-index: 99;
    }
    .closeCall{
        color: white !important;
    }
    .modalCalling a {
      line-height: 1em;
      color:white;
    }

    .overlayModal {
  position: fixed; /* Positioning and size */
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(128,128,128,0.5); /* color */
  display: none; /* making it hidden by default */
}
.modalS {
  position: fixed; /* positioning in center of page */
  top: 50vh;
  left: 50vw;
  transform: translate(-50%,-50%);
  height: 250px; /* size */
  width: 450px;
  background-color: white; /* background color */
}
    </style>
    {{-- <a href="#" class="modal-link">click here</a> --}}
    <div class="modalCalling overlayModal" style="display: none;">
        <div class="col-12 text-center modalS">
            <div class="col-12 text-center">
                <div class=" m-0">
                    <img src="{{ asset('asset_admin/images/telephone-pulse.gif') }}" hheight="100" width="100" alt="">
                    <p>Session Has Been Started</p>
                </div>
            </div>
            <div class="col-12 text-center">
                <div class=" m-0">
                    <a href="javascript:void(0)" class="btn btn-danger closeCall">Close</a>
                    <a class="btn btn-info text-white videoCallingJoinButton"><span id="callCounter"></span>Join Call</a>
                </div>
            </div>
        </div>
    </div>

<script src="{{ asset('https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js')}}"></script>
<script src="{{ asset('asset_admin/plugins/jquery/jquery-v3.2.1.min.js')}}"></script>
<script src="{{ asset('js/app.js') }}"></script>
<script>


$('.closeCall').click(function(){
    $('.modalCalling').hide();
    var sess_id=$('#user_session_id').val();
    window.location.href='/patient/not/call/join/'+sess_id;
});
Echo.channel('session-channel')
    .listen('DoctorJoinedVideoSession', (e) => {
    var sess_id=$('#user_session_id').val();
    var pat_id="{{ Auth::user()->id ?? '0'}}";
    if(e.message=="doctor joined session"  && e.patient_id==pat_id)
    {
        var callCount=30;
        var storeTimeInterval=setInterval(() => {
          callCount--;
          if(callCount<1)
          {
            clearInterval(storeTimeInterval);
            window.location.href='/patient/not/call/join/'+e.session_id;
          }
          $('#callCounter').html('('+callCount+")");
        }, 1000);
        $('.modalCalling').show();
        sessionStorage.setItem("time1", 'null');
        $('.videoCallingJoinButton').attr('href','/patient/video/'+e.session_id);

    }
});

Echo.channel('check-online-doctor')
.listen('CheckDoctorStatus', (e) => {
   $.ajax({
        type: 'GET',
        url: "{{ URL('/change_doc_online_status') }}",
        data: {'user':e.doctor_id},
        success: function(status) {
            if (status == 'yes')
            {
                $('#status').text('Offline');
                $('#status').css('color', 'grey');
                $('#status_check').prop('checked', false);
                alert('Your status changed to offline due to inactivity.');
            }
            else{
                return false;
            }
        }
    });
  });


$("#search").keyup(function(){
      var search=$("#search").val();
      if(search != ""){
                $("#searchMedison").html("");
                $("#searchImaging").html("");
                $("#searchIab").html("");
                $.ajax({
                  url: "{{route('searchProductMedicine')}}",
                  type: "POST",
                  data: {
                    search:search,
                    "_token": "{{ csrf_token() }}"
                  },
                  dataType:'json',
                  success: function(medison)
                  {
                    var len=0;
                    if(medison['medison']!=null)
                    {
                      len=medison['medison'].length;
                    }
                    if(len>0)
                    {
                        $("#searchMedison").html("");
                        for(var i=0;i<len;i++)
                        {
                          var id=medison['medison'][i].id;
                          var name=medison['medison'][i].name;
                          var slug=medison['medison'][i].slug;
                          var mode=medison['medison'][i].mode;
                          var featured_image=medison['medison'][i].featured_image;
                          var tr_str="<a class='dummy-media-object' href='/product/"+mode+"/"+slug+"/'> <img class='round' src='/uploads/"+featured_image+"' alt=''/><h3>"+name+"</h3></a>";
                          $("#searchMedison").append(tr_str);
                        }
                    } else{
                      $("#searchMedison").html("");
                      var tr_str="<h5 class='text-center'>Record Not Found</h5>";
                      $("#searchMedison").append(tr_str);

                    }
                  }
                });
                $.ajax({
                  url: "{{route('searchProductImaging')}}",
                  type: "POST",
                  data: {
                    search:search,
                    "_token": "{{ csrf_token() }}"
                  },
                  dataType:'json',
                  success: function(imaging)
                  {
                    var len=0;
                    if(imaging['imaging']!=null)
                    {
                      len=imaging['imaging'].length;
                    }
                    if(len>0)
                    {
                        $("#searchImaging").html("");
                        for(var i=0;i<len;i++)
                        {
                          var id=imaging['imaging'][i].id;
                          var name=imaging['imaging'][i].name;
                          var slug=imaging['imaging'][i].slug;
                          var mode=imaging['imaging'][i].mode;
                          var featured_image=imaging['imaging'][i].featured_image;
                          var tr_str="<a class='dummy-media-object' href='/product/"+mode+"/"+slug+"/'> <img class='round' src='/uploads/"+featured_image+"' alt=''/><h3>"+name+"</h3></a>";
                          $("#searchImaging").append(tr_str);
                        }
                    }
                    else{
                      $("#searchImaging").html("");
                      var tr_str="<h5 class='text-center'>Record Not Found</h5>";
                      $("#searchImaging").append(tr_str);

                    }
                  }
                });
                $.ajax({
                  url: "{{route('searchProductLab')}}",
                  type: "POST",
                  data: {
                    search:search,
                    "_token": "{{ csrf_token() }}"
                  },
                  dataType:'json',
                  success: function(lab)
                  {
                    var len=0;
                    if(lab['lab']!=null)
                    {
                      len=lab['lab'].length;
                    }
                    if(len>0)
                    {
                        $("#searchIab").html("");
                        for(var i=0;i<len;i++)
                        {
                          var id=lab['lab'][i].id;
                          var name=lab['lab'][i].name;
                          var slug=lab['lab'][i].slug;
                          var mode=lab['lab'][i].mode;
                          var featured_image=lab['lab'][i].featured_image;
                          var tr_str="<a class='dummy-media-object' href='/product/"+mode+"/"+slug+"/'> <img class='round' src='/uploads/"+featured_image+"' alt=''/><h3>"+name+"</h3></a>";
                          $("#searchIab").append(tr_str);
                        }
                    } else{
                      $("#searchIab").html("");
                      var tr_str="<h5 class='text-center'>Record Not Found</h5>";
                      $("#searchIab").append(tr_str);

                    }
                  }
                });
                $.ajax({
                  url: "{{route('searchPatient')}}",
                  type: "POST",
                  data: {
                    search:search,
                    "_token": "{{ csrf_token() }}"
                  },
                  dataType:'json',
                  success: function(patient)
                  {
                    var len=0;
                    if(patient['patient']!=null)
                    {
                      len=patient['patient'].length;
                    // console.log(len);
                    }
                    if(len>0)
                    {
                        $("#searchPatient").html("");
                        for(var i=0;i<len;i++)
                        {
                          var id=patient['patient'][i].id;
                          var name=patient['patient'][i].name;
                          var last_name=patient['patient'][i].last_name;
                          var user_image=patient['patient'][i].user_image;
                          var user_type=patient['patient'][i].user_type;
                          if(user_type=='doctor')
                          {
                          var tr_str="<a class='dummy-media-object' href='/online_doctors'> <img class='round' src='"+user_image+"' alt=''/><h3>"+name+" "+last_name+"</h3></a>";
                          }
                          else{
                          var tr_str="<a class='dummy-media-object' href='/patient_record/"+id+"'> <img class='round' src='"+user_image+"' alt=''/><h3>"+name+"</h3></a>";
                          }
                          $("#searchPatient").append(tr_str);
                        }
                    } else{
                      $("#searchPatient").html("");

                      var tr_str="<h5 class='text-center'>Record Not Found</h5>";
                      $("#searchPatient").append(tr_str);

                    }
                  }
                });






    }else{




                var search=$("#search").val();
        $.ajax({
                  url: "{{route('searchProductMedicine')}}",
                  type: "POST",
                  data: {
                    search:search,
                    "_token": "{{ csrf_token() }}"
                  },
                  dataType:'json',
                  success: function(medison)
                  {
                    var len=0;
                    if(medison['medison']!=null)
                    {
                      len=medison['medison'].length;
                    }
                    if(len>0)
                    {
                        $("#searchMedison").html("");
                        for(var i=0;i<len;i++)
                        {
                          var id=medison['medison'][i].id;
                          var name=medison['medison'][i].name;
                          var slug=medison['medison'][i].slug;
                          var mode=medison['medison'][i].mode;
                          var featured_image=medison['medison'][i].featured_image;
                          var tr_str="<a class='dummy-media-object' href='/product/"+mode+"/"+slug+"/'> <img class='round' src='/uploads/"+featured_image+"' alt=''/><h3>"+name+"</h3></a>";
                          $("#searchMedison").append(tr_str);
                        }
                    }else{
                      $("#searchMedison").html("");
                      var tr_str="<h5 class='text-center'>Record Not Found</h5>";
                      $("#searchMedison").append(tr_str);

                    }
                  }
                });
                $.ajax({
                  url: "{{route('searchProductImaging')}}",
                  type: "POST",
                  data: {
                    search:search,
                    "_token": "{{ csrf_token() }}"
                  },
                  dataType:'json',
                  success: function(imaging)
                  {
                    var len=0;
                    if(imaging['imaging']!=null)
                    {
                      len=imaging['imaging'].length;
                    }
                    if(len>0)
                    {
                        $("#searchImaging").html("");
                        for(var i=0;i<len;i++)
                        {
                          var id=imaging['imaging'][i].id;
                          var name=imaging['imaging'][i].name;
                          var slug=imaging['imaging'][i].slug;
                          var mode=imaging['imaging'][i].mode;
                          var featured_image=imaging['imaging'][i].featured_image;
                          var tr_str="<a class='dummy-media-object' href='/product/"+mode+"/"+slug+"/'> <img class='round' src='/uploads/"+featured_image+"' alt=''/><h3>"+name+"</h3></a>";
                          $("#searchImaging").append(tr_str);
                        }
                    } else{
                      $("#searchImaging").html("");
                      var tr_str="<h5 class='text-center'>Record Not Found</h5>";
                      $("#searchImaging").append(tr_str);

                    }
                  }
                });
                $.ajax({
                  url: "{{route('searchProductLab')}}",
                  type: "POST",
                  data: {
                    search:search,
                    "_token": "{{ csrf_token() }}"
                  },
                  dataType:'json',

                  success: function(lab)
                  {
                    var len=0;
                    if(lab['lab']!=null)
                    {
                      len=lab['lab'].length;
                    }
                    if(len>0)
                    {
                        $("#searchIab").html("");
                        for(var i=0;i<len;i++)
                        {
                          var id=lab['lab'][i].id;
                          var name=lab['lab'][i].name;
                          var slug=lab['lab'][i].slug;
                          var mode=lab['lab'][i].mode;
                          var featured_image=lab['lab'][i].featured_image;
                          var tr_str="<a class='dummy-media-object' href='/product/"+mode+"/"+slug+"/'> <img class='round' src='/uploads/"+featured_image+"' alt=''/><h3>"+name+"</h3></a>";
                          $("#searchIab").append(tr_str);
                        }
                    } else{
                      $("#searchIab").html("");
                      var tr_str="<h5 class='text-center'>Record Not Found</h5>";
                      $("#searchIab").append(tr_str);

                    }
                  }
                });
                $.ajax({
                  url: "{{route('searchPatient')}}",
                  type: "POST",
                  data: {
                    search:search,
                    "_token": "{{ csrf_token() }}"
                  },
                  dataType:'json',
                  success: function(patient)
                  {
                    var len=0;
                    if(patient['patient']!=null)
                    {
                      len=patient['patient'].length;
                    }
                    if(len>0)
                    {
                        $("#searchPatient").html("");
                        for(var i=0;i<len;i++)
                        {
                          var id=patient['patient'][i].id;
                          var name=patient['patient'][i].name;
                          var last_name=patient['patient'][i].last_name;
                          var user_image=patient['patient'][i].user_image;
                          var user_type=patient['patient'][i].user_type;
                        //  alert(user_type);
                          if(user_type=='doctor')
                          {
                          var tr_str="<a class='dummy-media-object' href='/online_doctors'> <img class='round' src='"+user_image+"' alt=''/><h3>"+name+" "+last_name+"</h3></a>";
                          }
                          else{
                          var tr_str="<a class='dummy-media-object' href='/patient_record/"+id+"'> <img class='round' src="+user_image+"' alt=''/><h3>"+name+"</h3></a>";
                          }
                          $("#searchPatient").append(tr_str);
                        }
                    }else{
                      $("#searchPatient").html("");
                      var tr_str="<h5 class='text-center'>Record Not Found</h5>";
                      $("#searchPatient").append(tr_str);
                    }
                  }
                });
    }
    });









$(document).ready(function(){
    var search=$("#search").val();
    $.ajax({
        url: "{{route('searchProductMedicine')}}",
        type: "POST",
        data: {
                    search:search,
                    "_token": "{{ csrf_token() }}"
                  },
        dataType:'json',

        success: function(medison)
        {
          var len=0;
          if(medison['medison']!=null)
          {
            len=medison['medison'].length;
          }
          if(len>0)
          {
              $("#searchMedison").html("");
              for(var i=0;i<len;i++)
              {
                var id=medison['medison'][i].id;
                var name=medison['medison'][i].name;
                var slug=medison['medison'][i].slug;
                var mode=medison['medison'][i].mode;
                var featured_image=medison['medison'][i].featured_image;
                var tr_str="<a class='dummy-media-object' href='/product/"+mode+"/"+slug+"/'> <img class='round' src='/uploads/"+featured_image+"' alt=''/><h3>"+name+"</h3></a>";
                $("#searchMedison").append(tr_str);
              }
          }else{
            $("#searchMedison").html("");
            var tr_str="<h5 class='text-center'>Record Not Found</h5>";
            $("#searchMedison").append(tr_str);

          }
        }
    });

    $.ajax({
        url: "{{route('searchProductImaging')}}",
        type: "POST",
        data: {
                    search:search,
                    "_token": "{{ csrf_token() }}"
                  },
        dataType:'json',
        success: function(imaging)
        {
          var len=0;
          if(imaging['imaging']!=null)
          {
            len=imaging['imaging'].length;
          }
          if(len>0)
          {
              $("#searchImaging").html("");
              for(var i=0;i<len;i++)
              {
                var id=imaging['imaging'][i].id;
                var name=imaging['imaging'][i].name;
                var slug=imaging['imaging'][i].slug;
                var mode=imaging['imaging'][i].mode;
                var featured_image=imaging['imaging'][i].featured_image;
                var tr_str="<a class='dummy-media-object' href='/product/"+mode+"/"+slug+"/'> <img class='round' src='/uploads/"+featured_image+"' alt=''/><h3>"+name+"</h3></a>";
                $("#searchImaging").append(tr_str);
              }
          } else{
            $("#searchImaging").html("");
            var tr_str="<h5 class='text-center'>Record Not Found</h5>";
            $("#searchImaging").append(tr_str);

          }
        }
    });

    $.ajax({
        url: "{{route('searchProductLab')}}",
        type: "POST",
        data: {
                    search:search,
                    "_token": "{{ csrf_token() }}"
                  },
        dataType:'json',
        success: function(lab)
        {
          var len=0;
          if(lab['lab']!=null)
          {
            len=lab['lab'].length;
          }
          if(len>0)
          {
              $("#searchIab").html("");
              for(var i=0;i<len;i++)
              {
                var id=lab['lab'][i].id;
                var name=lab['lab'][i].name;
                var slug=lab['lab'][i].slug;
                var mode=lab['lab'][i].mode;
                var featured_image=lab['lab'][i].featured_image;
                var tr_str="<a class='dummy-media-object' href='/product/"+mode+"/"+slug+"/'> <img class='round' src='/uploads/"+featured_image+"' alt=''/><h3>"+name+"</h3></a>";
                $("#searchIab").append(tr_str);
              }
          } else{
            $("#searchIab").html("");
            var tr_str="<h5 class='text-center'>Record Not Found</h5>";
            $("#searchIab").append(tr_str);

          }
        }
    });

    $.ajax({
        url: "{{route('searchPatient')}}",
        type: "POST",
        data: {
                    search:search,
                    "_token": "{{ csrf_token() }}"
                  },
        dataType:'json',
        success: function(patient)
        {
          var len=0;
          if(patient['patient']!=null)
          {
            len=patient['patient'].length;
          }
          if(len>0)
          {
              $("#searchPatient").html("");
              for(var i=0;i<len;i++)
              {
                var id=patient['patient'][i].id;
                var name=patient['patient'][i].name;
                var last_name=patient['patient'][i].last_name;
                var user_image=patient['patient'][i].user_image;
                var user_type=patient['patient'][i].user_type;
              //  alert(user_type);
                if(user_type=='doctor')
                {
                var tr_str="<a class='dummy-media-object' href='/online_doctors'> <img class='round' src='"+user_image+"' alt=''/><h3>"+name+" "+last_name+"</h3></a>";
                }
                else{
                var tr_str="<a class='dummy-media-object' href='/patient_record/"+id+"'> <img class='round' src='"+user_image+"' alt=''/><h3>"+name+"</h3></a>";
                }
                $("#searchPatient").append(tr_str);
              }
          }else{
            $("#searchPatient").html("");
            var tr_str="<h5 class='text-center'>Record Not Found</h5>";
            $("#searchPatient").append(tr_str);

          }
        }
    });

    $.ajax({
        url: "{{url('/Toaster')}}",
        type: "get",
        data: {
                user_id:"{{ Auth::user()->id ?? '0'}}",
                "_token": "{{ csrf_token() }}"
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
});





Echo.channel('events')
    .listen('RealTimeMessage',(e)=> {
        var user_id="{{ Auth::user()->id ?? '0'}}";
        if(e.user_id==user_id)
        {
            if(e.getNote!='' || e.getNote!=null)
            {
                $('#noteData').html('');
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
                        $('#noteData').append(
                            '<a href="/ReadNotification/'+note.id+'" >'+
                                '<div class="sec_ newNotifications" style="background-color: #fcfafa !important;">'+
                                    '<span class="nav_notification float-right">New</span>'+
                                    '<div class="txt_ not_text">'+note.text+'</div>'+
                                    '<div class="txt_ not_subtext"><i class="zmdi zmdi-time"></i> '+noteTime+'</div>'+
                                '</div>'+
                            '</a>'
                        );

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
                        $('#noteData').append(
                            '<a href="/ReadNotification/'+note.id+'">'+
                                '<div class="sec_  oldNotifications" style=" background-color: #ffffff !important;">'+
                                    '<span class="nav_notification_seen float-right">Seen</span>'+
                                    '<div class="txt_ not_text">'+note.text+'</div>'+
                                    '<div class="txt_ not_subtext"><i class="zmdi zmdi-time"></i> '+noteTime+'</div>'+
                                '</div>'+
                            '</a>'
                        );

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





// change Password script start here

$(".toggle-password").click(function(){
    $(this).toggleClass("fa-eye fa-eye-slash");
    var input = $($(this).attr("toggle"));
    if (input.attr("type") == "password") {
        input.attr("type", "text");
    } else {
        input.attr("type", "password");
    }
});



</script>

<!-- Moment Plugin Js -->
<script src="{{ asset('asset_admin/plugins/momentjs/moment.js')}}"></script>
<script type="text/javascript">
  $(document).ready(function() {
      $.ajax({
          url: "/get_cart_counter",
          type: "GET",
          processData: false,
          contentType: false,
          success: function(response) {
              var result = JSON.parse(response);
              $(".cart_counter").html(result);
          },
      });
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

<!-- Moment Plugin Js -->
<script src="{{ asset('asset_admin/plugins/momentjs/moment.js')}}"></script>

<!-- Jquery Core Js -->
<script src="{{ asset('asset_admin/bundles/libscripts.bundle.js')}}"></script>
 <!-- Lib Scripts Plugin Js -->
<script src="{{ asset('asset_admin/bundles/morphingsearchscripts.bundle.js')}}"></script>
 <!-- morphing search Js -->
<script src="{{ asset('asset_admin/bundles/vendorscripts.bundle.js')}}"></script>
<!-- Lib Scripts Plugin Js -->

<script src="{{ asset('asset_admin/plugins/jquery-sparkline/jquery.sparkline.min.js')}}"></script>
 <!-- Sparkline Plugin Js -->
<script src="{{ asset('asset_admin/plugins/chartjs/Chart.bundle.min.js')}}"></script>
<!-- Chart Plugins Js -->

<script src="{{ asset('asset_admin/bundles/mainscripts.bundle.js')}}"></script>
<!-- Custom Js -->
<script src="{{ asset('asset_admin/bundles/morphingscripts.bundle.js')}}"></script>
<!-- morphing search page js -->
<script src="{{ asset('asset_admin/js/pages/index.js')}}"></script>
<!-- Notification -->
<script src="{{ asset('asset_admin/plugins/bootstrap-notify/bootstrap-notify.js')}}"></script>
<!-- Bootstrap Notify Plugin Js -->
<script src="{{ asset('asset_admin/js/pages/ui/notifications.js')}}"></script> <!-- Custom Js -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.js" integrity="sha512-dqw6X88iGgZlTsONxZK9ePmJEFrmHwpuMrsUChjAw1mRUhUITE5QU9pkcSox+ynfLhL15Sv2al5A0LVyDCmtUw==" crossorigin="anonymous"></script> -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="{{ asset('asset_admin/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js')}}"></script>
<script src="{{ asset('asset_admin/jquery_datatable/jquery-datatable.js') }}"></script>
<script src="{{ asset('asset_admin/jquery_datatable/datatablescripts.bundle.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/mouse0270-bootstrap-notify/3.1.5/bootstrap-notify.min.js"></script>
