@extends('layouts.admin')

@section('css')
<link href="asset_admin/plugins/fullcalendar/fullcalendar.min.css" rel="stylesheet">
@endsection

@section('content')
<section class="content page-calendar">
    <div class="container-fluid">
        <div class="row">
            <!-- <div class="col-md-12 col-lg-4 col-xl-3">
                <div class="card m-t-20">
                    <div class="body">
                        <button type="button" class="btn btn-raised btn-primary btn-sm m-t-0" data-toggle="modal" href="#cal-new-event"> <i class="zmdi zmdi-plus"></i> Events</button>
                        <button class="btn btn-sm btn-default hidden-lg-up m-t-0" data-toggle="collapse" data-target="#open-chats" aria-expanded="false" aria-controls="collapseExample"><i class="zmdi zmdi-chevron-down"></i></button>
                        <div class="collapse-xs collapse-sm collapse" id="open-chats">
                            <div class="event-name col-lg-12 clearfix row b-greensea">
                            <div class="col-lg-10"> Appointment #1
                            </div> 
                            <div class="col-lg-2">
                                <a class=" text-muted event-remove"><i class="zmdi zmdi-delete"></i></a> </div>
                            </div>
                            <div class="event-name col-lg-12 clearfix row b-lightred">
                            <div class="col-lg-10"> Appointment #2 
                            </div>
                            <div class="col-lg-2">
                                <a class=" text-muted event-remove"><i class="zmdi zmdi-delete"></i></a>
                            </div>
                            </div>
                            <div class="event-name col-lg-12 clearfix row b-amethyst">
                            <div class="col-lg-10"> Appointment #3
                            </div>
                            <div class="col-lg-2">
                                <a class=" text-muted event-remove"><i class="zmdi zmdi-delete"></i></a>
                            </div></div>
                            <div class="event-name col-lg-12 clearfix row b-amethyst">
                            <div class="col-lg-10"> Appointment #4
                            </div>
                            <div class="col-lg-2">
                                <a class=" text-muted event-remove"><i class="zmdi zmdi-delete"></i></a>
                            </div></div>
                            <div class="event-name col-lg-12 clearfix row b-success">
                            <div class="col-lg-10"> Appointment #5
                            </div>
                            <div class="col-lg-2">
                                <a class=" text-muted event-remove"><i class="zmdi zmdi-delete"></i></a>
                            </div></div>
                            <div class="event-name col-lg-12 clearfix row b-lightred">
                            <div class="col-lg-10"> Appointment #6
                            </div>
                            <div class="col-lg-2">
                                <a class=" text-muted event-remove"><i class="zmdi zmdi-delete"></i></a>
                            </div></div>
                            <div class="event-name col-lg-12 clearfix row b-greensea">
                            <div class="col-lg-10"> Appointment #7
                            </div>
                            <div class="col-lg-2">
                                <a class=" text-muted event-remove"><i class="zmdi zmdi-delete"></i></a>
                            </div></div>
                            <div class="event-name col-lg-12 clearfix row b-success">
                            <div class="col-lg-10"> Appointment #8
                            </div>
                            <div class="col-lg-2">
                                <a class=" text-muted event-remove"><i class="zmdi zmdi-delete"></i></a>
                            </div></div>
                            <div class="event-name col-lg-12 clearfix row b-success">
                            <div class="col-lg-10"> Appointment #9
                            </div>
                            <div class="col-lg-2">
                                <a class=" text-muted event-remove"><i class="zmdi zmdi-delete"></i></a>
                            </div></div>
                            <div class="event-name col-lg-12 clearfix row b-primary">
                            <div class="col-lg-10"> Appointment #10
                            </div>
                            <div class="col-lg-2">
                                <a class=" text-muted event-remove"><i class="zmdi zmdi-delete"></i></a>
                            </div></div>                
                        </div>
                    </div>
                </div>
            </div> -->
            <div class="col-md-12 col-lg-12 col-xl-12">
                <div class="card m-t-20">
                    <div class="body">
                        <button style="background-color: #2193b0;color:white" class="btn btn-raised btn-sm m-r-0 m-t-0" id="change-view-today">today</button>
                        <button class="btn btn-raised btn-default btn-sm m-r-0 m-t-0" id="change-view-day" >Day</button>
                        <button class="btn btn-raised btn-default btn-sm m-r-0 m-t-0" id="change-view-week">Week</button>
                        <button class="btn btn-raised btn-default btn-sm m-r-0 m-t-0" id="change-view-month">Month</button>                        
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- For Material Design Colors -->
<div class="modal fade" id="mdModal" style="font-weight: normal; " tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel"><span id="date" class="date_title" ></span><button id="not_empty" style="color:white;margin-left:170px" class="btn btn-danger holiday_btn">Holiday</button>
                    <small >
                    <br>Booked Appointments</small>
                <br>
                
            </h4>
                
                
            </div>
            <div class="modal-body" style="height: 320px;overflow-y: auto"> 
                <div class="list-group booked-appoint" > 
                   
                </div>
            </div>
            
        </div>
    </div>
</div>
<div class="modal fade" id="emptyModal" style="font-weight: normal; " tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel"><span id="empty_date" class="date_title"></span><button id="emptyy" style="color:white;margin-left:170px" class="btn btn-danger holiday_btn">Holiday</button>
                    </h4>
                
            </div>
            <div class="modal-body"> 
                   No Appointment Booked
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
<div class="modal fade" id="holidayModal" style="font-weight: normal; " tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel"><span id="empty_date" class="date_title"></span><button style="color:white;margin-left:170px" class="btn btn-danger rem_holiday_btn">Remove Holiday</button>
                    </h4>
                
            </div>
            <div class="modal-body"> 
                   You marked this day as holiday
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="asset_admin/bundles/fullcalendarscripts.bundle.js"></script><!--/ calender javascripts --> 
<?php header("Access-Control-Allow-Origin: *"); ?>
<script src="asset_admin/js/pages/calendar/calendar.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script type="text/javascript">
        var result=false;
        var empty=true;

    $('.holiday_btn').click(function(){
        var date=$('.date_title').text();
        var empty;
        if($(this).attr('id')=='emptyy')
            empty=true;
        else if($(this).attr('id')=='not_empty')
            empty=false;
        console.log(empty)
        Swal.fire({
          title: 'Are you sure?',
          text: "If you click on \'Yes\', patients would not be able to book appointments with you",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          cancelButtonText:'No',
          confirmButtonText: 'Yes'
        }).then((result) => {
          if (result.value) {
            result=true;
            console.log(result);
            postholiday(result,empty);
             // window.location.href = "calendar_store_holiday/"+date;

        }
        
    })
            // console.log(result);

     //    if(result){
     //        console.log('asdasf');

     //        $.ajax({
     //       type:'POST',
     //       url: 'calendar_store_holiday/' ,
     //       data:{
     //            date:date,
     //       },
     //       success:function(sucess_msg){
     //        // location.reload(true);

     //        // console.log(sucess_msg);
     //        // alert(sucess_msg);
     //       // window.location.href = "calendar_store_holiday/"+date;
     // }
     //    });
     //    }
});
       
</script>
<script type="text/javascript">
function postholiday(result,empty){//with appointments
             console.log(result);
if(empty)
    var date=$('#empty_date').text();
else
var date=$('#date').text();
             console.log(date);

        if(result){
            console.log('gfhgc');

        $.ajax({
           type:'POST',
           url: '/calendar_holiday' ,
           data:{
                date:date,
           },
           success:function(sucess_msg)
           {
            location.reload();
            }
        });
        }

// var date=$('#empty_date').text();
//         $.ajax({
//            type:'POST',
//            url: 'calendar_store_holiday/' ,
//            data:{
//                 date:date,
//            },
//            success:function(sucess_msg){
//             console.log(sucess_msg);
//            // window.location.href = "calendar_store_holiday/"+date;
//      }
//  });
        }
</script>
@endsection
