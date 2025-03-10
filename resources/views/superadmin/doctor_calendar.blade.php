@extends('layouts.admin')
@section('css')
<script src="{{ asset('https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js')}}"></script>
<script src="{{ asset('asset_admin/plugins/jquery/jquery-v3.2.1.min.js')}}"></script>
<link href="asset_admin/css/calendarfile/mdtimepicker.css" rel="stylesheet">
<link href='asset_admin/css/calendarfile/fullcalendar.css' rel='stylesheet'>
<link href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css' rel='stylesheet' />

<style>
    pre{display:none;}
    #thider{display:none;}
    .fc-title{color:white;}
    .form-control{color:black;}
    .nav-tabs>li>a:before {width:0px;}
    .nav-tabs{padding: 10px 0px 10px 0px;}
    .col-centered{float: none;margin: 0 auto;}
    .nav-tabs>li.active>a{color: white !important;}
    .nav-tabs>li>a{color: white !important;text-decoration: none;}
    .nav-tabs>li:hover{background-color: #4ca6bc;color: rgb(255, 255, 255) !important;}
    .form-group .form-control{border: 1px solid black;padding: 0px 0px 0px 55px;border-radius: 5px;width: 45%;}
    .nav-tabs>li{margin: 0px 0px 0px 5px;background:linear-gradient(45deg, #5e94e4 , #08295a) !important;padding: 10px;color: white !important;text-decoration: none;}
    .form-group label.control-label{font-size: 20px;line-height: 1.0714285718;color: #000;font-weight: 400;width:10%; margin: 0px 20px 0 55px;}
    .nav-tabs>li>#atag:hover, .nav-tabs>li>#atag:active, .nav-tabs>li>#atag:focus{
      background-color: transparent !important;
    color: #fff !important;
    }
    label{
      font-size: 18px !important;
      margin-left: 24px !important;
      margin-top:24px !important;
    }
    select{
      margin-left: 24px !important;
      font-size: 18px !important;
    }
</style>

@endsection

@section('content')

<!-- Button trigger modal -->

<section class="content page-calendar">
  <div class="container-fluid">
    <div class="col-lg-4 col-md-6 col-12">
      <label>Select Doctor:</label>
      <select class="form-control col-12" id="doc_id" >
        @foreach ($doctors as $user)
        @if(isset($_GET['id']))
          @if($_GET['id']==$user->id)
          <option selected value="{{$user->id}}">{{$user->name.' '.$user->last_name}}</option>
          @else
          <option value="{{$user->id}}">{{$user->name.' '.$user->last_name}}</option>
          @endif
        @else
        <option value="{{$user->id}}">{{$user->name.' '.$user->last_name}}</option>
        @endif
         @endforeach
      </select>
    </div>
    <div id="calendar" class="col-centered p-4"></div>
  </div>
  <?php
    $date=date("Y-m-d");
  ?>
</section>


<!-- Modal -->

    <div class="modal fade" id="ModalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">

          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="font-size:25px;"><span aria-hidden="true">&times;</span></button>
          </div>
          <div class="modal-body">
            <div role="tabpanel">

            </div>
            <div class="tab-content">
              <div role="tabpanel" class="tab-pane active" id="uploadTab1">
                <div class="div" style=" width:100%; margin:10px 0px; text-align: justify; " >
                  <input type="hidden" id="ttype" value="">
                  <h4 class="modal-title" id="myModalLabel" style="color:black;"><span id="stime"></span> To <span id="etime"></span> Slot Appointments</h4>
                  <hr>
                  <h5 id="loaded"></h5>

                </div>
              </div>
              <div role="tabpanel" class="tab-pane" id="browseTab1">

                <h3 class="modal-title" id="myModalLabel" style="margin:10px 0px 0px 70px; font-size:25px;"><span id="type"></span> Edit Schedule</h3>
                <form method="POST" class="text-center" action="{{route('updateEvent')}}">
                  @csrf
                  @method('PUT')
                  <div class="form-group row" id="titleinput">
                    <label for="start" class="control-label">Title</label>
                    <input type="text" name="title" class="form-control" id="title" placeholder="Title">
                  </div>
                  <div class="form-group ">
                    <label for="start" class="">Start</label>
                    <input type='hidden' class="form-control startUpdate" name="start"  class="form-control" id="start"/>
                    {{-- <input type='text' class="form-control"  name="EditStartTimePicker" id="timepicker4"/> --}}

                    <select  class="form-control"  name="EditStartTimePicker" style="margin:auto;" id="timepicker4">
                      <option value="12:00 AM">12:00 AM</option>
                      <option value="12:30 AM">12:30 AM</option>
                      <option value="1:00 AM">1:00 AM</option>
                      <option value="1:30 AM">1:30 AM</option>
                      <option value="2:00 AM">2:00 AM</option>
                      <option value="2:30 AM">2:30 AM</option>
                      <option value="3:00 AM">3:00 AM</option>
                      <option value="3:30 AM">3:30 AM</option>
                      <option value="4:00 AM">4:00 AM</option>
                      <option value="4:30 AM">4:30 AM</option>
                      <option value="5:00 AM">5:00 AM</option>
                      <option value="5:30 AM">5:30 AM</option>
                      <option value="6:00 AM">6:00 AM</option>
                      <option value="6:30 AM">6:30 AM</option>
                      <option value="7:00 AM">7:00 AM</option>
                      <option value="7:30 AM">7:30 AM</option>
                      <option value="8:00 AM">8:00 AM</option>
                      <option value="8:30 AM">8:30 AM</option>
                      <option value="9:00 AM">9:00 AM</option>
                      <option value="9:30 AM">9:30 AM</option>
                      <option value="10:00 AM">10:00 AM</option>
                      <option value="10:30 AM">10:30 AM</option>
                      <option value="11:00 AM">11:00 AM</option>
                      <option value="11:30 AM">11:30 AM</option>
                      <option value="12:00 PM">12:00 PM</option>
                      <option value="12:30 PM">12:30 PM</option>
                      <option value="1:00 PM">1:00 PM</option>
                      <option value="1:30 PM">1:30 PM</option>
                      <option value="2:00 PM">2:00 PM</option>
                      <option value="2:30 PM">2:30 PM</option>
                      <option value="3:00 PM">3:00 PM</option>
                      <option value="3:30 PM">3:30 PM</option>
                      <option value="4:00 PM">4:00 PM</option>
                      <option value="4:30 PM">4:30 PM</option>
                      <option value="5:00 PM">5:00 PM</option>
                      <option value="5:30 PM">5:30 PM</option>
                      <option value="6:00 PM">6:00 PM</option>
                      <option value="6:30 PM">6:30 PM</option>
                      <option value="7:00 PM">7:00 PM</option>
                      <option value="7:30 PM">7:30 PM</option>
                      <option value="8:00 PM">8:00 PM</option>
                      <option value="8:30 PM">8:30 PM</option>
                      <option value="9:00 PM">9:00 PM</option>
                      <option value="9:30 PM">9:30 PM</option>
                      <option value="10:00 PM">10:00 PM</option>
                      <option value="10:30 PM">10:30 PM</option>
                      <option value="11:00 PM">11:00 PM</option>
                      <option value="11:30 PM">11:30 PM</option>
                      <option value="12:00 PM">12:00 PM</option>
                      <option value="12:30 PM">12:30 PM</option>

                    </select>

                  </div>
                  <div class="form-group ">
                    <label for="end" class="">End</label>
                    <input type='hidden' class="form-control endUpdate" name="end"  class="form-control" id="end"/>
                    {{-- <input type='text' class="form-control"  name="EditEndTimePicker" id="timepicker5" /> --}}
                    <select  class="form-control"  name="EditEndTimePicker" id="timepicker5" style="margin:auto;">
                      <option value="12:00 AM">12:00 AM</option>
                      <option value="12:30 AM">12:30 AM</option>
                      <option value="1:00 AM">1:00 AM</option>
                      <option value="1:30 AM">1:30 AM</option>
                      <option value="2:00 AM">2:00 AM</option>
                      <option value="2:30 AM">2:30 AM</option>
                      <option value="3:00 AM">3:00 AM</option>
                      <option value="3:30 AM">3:30 AM</option>
                      <option value="4:00 AM">4:00 AM</option>
                      <option value="4:30 AM">4:30 AM</option>
                      <option value="5:00 AM">5:00 AM</option>
                      <option value="5:30 AM">5:30 AM</option>
                      <option value="6:00 AM">6:00 AM</option>
                      <option value="6:30 AM">6:30 AM</option>
                      <option value="7:00 AM">7:00 AM</option>
                      <option value="7:30 AM">7:30 AM</option>
                      <option value="8:00 AM">8:00 AM</option>
                      <option value="8:30 AM">8:30 AM</option>
                      <option value="9:00 AM">9:00 AM</option>
                      <option value="9:30 AM">9:30 AM</option>
                      <option value="10:00 AM">10:00 AM</option>
                      <option value="10:30 AM">10:30 AM</option>
                      <option value="11:00 AM">11:00 AM</option>
                      <option value="11:30 AM">11:30 AM</option>
                      <option value="12:00 PM">12:00 PM</option>
                      <option value="12:30 PM">12:30 PM</option>
                      <option value="1:00 PM">1:00 PM</option>
                      <option value="1:30 PM">1:30 PM</option>
                      <option value="2:00 PM">2:00 PM</option>
                      <option value="2:30 PM">2:30 PM</option>
                      <option value="3:00 PM">3:00 PM</option>
                      <option value="3:30 PM">3:30 PM</option>
                      <option value="4:00 PM">4:00 PM</option>
                      <option value="4:30 PM">4:30 PM</option>
                      <option value="5:00 PM">5:00 PM</option>
                      <option value="5:30 PM">5:30 PM</option>
                      <option value="6:00 PM">6:00 PM</option>
                      <option value="6:30 PM">6:30 PM</option>
                      <option value="7:00 PM">7:00 PM</option>
                      <option value="7:30 PM">7:30 PM</option>
                      <option value="8:00 PM">8:00 PM</option>
                      <option value="8:30 PM">8:30 PM</option>
                      <option value="9:00 PM">9:00 PM</option>
                      <option value="9:30 PM">9:30 PM</option>
                      <option value="10:00 PM">10:00 PM</option>
                      <option value="10:30 PM">10:30 PM</option>
                      <option value="11:00 PM">11:00 PM</option>
                      <option value="11:30 PM">11:30 PM</option>
                      <option value="12:00 PM">12:00 PM</option>
                      <option value="12:30 PM">12:30 PM</option>

                    </select>

                    <input type="hidden" name="id" class="form-control" id="id">
                  </div>
                  <div class="form-group" id="reason">
                    <label for="end" class="">Define Reason Why You Disable Your Avalibility.</label>
                      <input type="text" class="form-control"  name="reason" style="position: relative; margin:auto; opacity: 14; padding:0px 7px;">
                  </div>
                  <div class="form-group">
                    <label for="end" class="" style="width:25%;">Disable Avalibility</label>
                    <input type="checkbox" id="delete" class="ml-1" name="delete" style="position: relative; left: 5px !important; opacity: 14;width: 20px; height: 20px;">
                   </div>
                  <div class="form-group ">
                      <center><button type="submit" id="updateEv" class="btn btn-success" style="padding:10px 50px; 10px 50px; color:white !important;">Save changes</button>	</center>
                  </div>
                </form>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
<!-- Modal -->
@endsection
@section('script')
<script src="asset_admin/js/calendarfile/mdtimepicker.js"></script>
<script src='asset_admin/js/calendarfile/previous-moment.min.js'></script>
<script src='asset_admin/js/calendarfile/previous-fullcalendar.min.js'></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>

<script>
<?php header("Access-Control-Allow-Origin: *"); ?>
    $.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});
$(document).ready(function(){
  $('#timepicker').mdtimepicker();
  $('#timepicker1').mdtimepicker();
  $('#timepicker2').mdtimepicker();
  $('#timepicker3').mdtimepicker();
  // $('#timepicker4').mdtimepicker();
  // $('#timepicker5').mdtimepicker();
	$('#start').datetimepicker({
	  format: 'YYYY-MM-DD h:mm a',
	});
	$('#end').datetimepicker({
	  format: 'YYYY-MM-DD h:mm a',
	});
	$('.start').datetimepicker({
	  format: 'YYYY-MM-DD h:mm a',
	});
	$('.end').datetimepicker({
	  format: 'YYYY-MM-DD h:mm a',
	});
	$('.startUpdate').datetimepicker({
		format:'YYYY-MM-DD h:mm a',
	});
	$('.endUpdate').datetimepicker({
		format:'YYYY-MM-DD h:mm a',
	});
});

$(document).ready(function(){
  $("#reason").hide();
  $("input[name='reason']").prop('required',false);
$("#delete").click(function(){
  if($("#delete").prop("checked")){
    $("#reason").show();
    $("input[name='reason']").prop('required',true);
  }
  else{
    $("#reason").hide();
    $("input[name='reason']").prop('required',false);
  }

});






$("#updateEv").click(function(){

  var str_timing=$("select[name=EditStartTimePicker]").val();
  var end_timing=$("select[name=EditEndTimePicker]").val();

  var str_timing_con = moment(str_timing, 'hh:mm A').format('HH:mm:ss')
  var end_timing_con = moment(end_timing, 'hh:mm A').format('HH:mm:ss')


str_timing_con =  str_timing_con.split(':');
end_timing_con =  end_timing_con.split(':');

totalSeconds1 = parseInt(str_timing_con[0] * 3600 + str_timing_con[1] * 60 + str_timing_con[0]);
totalSeconds2 = parseInt(end_timing_con[0] * 3600 + end_timing_con[1] * 60 + end_timing_con[0]);



if (totalSeconds1 >= totalSeconds2 ) {
  alert('Please Choose Valid End Time');
  return false;
}



});
$("#addTiming").click(function(){
  var str_timing=$("select[name=startTimePicker]").val();
  var end_timing=$("select[name=endTimePicker]").val();

  var str_timing_con = moment(str_timing, 'hh:mm A').format('HH:mm:ss')
  var end_timing_con = moment(end_timing, 'hh:mm A').format('HH:mm:ss')


str_timing_con =  str_timing_con.split(':');
end_timing_con =  end_timing_con.split(':');

totalSeconds1 = parseInt(str_timing_con[0] * 3600 + str_timing_con[1] * 60 + str_timing_con[0]);
totalSeconds2 = parseInt(end_timing_con[0] * 3600 + end_timing_con[1] * 60 + end_timing_con[0]);



if (totalSeconds1 >= totalSeconds2 ) {

  alert('Please Choose Valid End Time');
  return false;
}



});


var time_zone="<?php echo env('TIME_ZONE'); ?>";

  $('#calendar').fullCalendar({
      header:
      {
			  left: 'prev,next,today',
			  center: 'title',
			  right: 'month,agendaWeek,agendaDay'
			},
      timeZone: time_zone,
			defaultDate: '<?php echo $date;?>',
			editable: true,
			eventLimit: true,
			selectable: true,
			selectHelper: true,

			select: function(start, end)
      {

				$('#ModalAdd #type').html(moment(start).format('MM-DD-YYYY'));
				$('#ModalAdd #start').val(moment(start).format('YYYY-MM-DD h:mm a'));
				$('#ModalAdd #end').val(moment(start).format('YYYY-MM-DD h:mm a'));
				$('#ModalAdd').modal('show');
			},
			eventRender: function(event, element)
      {

				element.bind('click', function(){
          $("#loaded").html("");
           $.ajax({
           type:'POST',
           url:"{{route('fetchDoctorAppointmentOnCalendar')}}",
           data:{
             selectData:event.editDate,
             doctorSlotStartTime:event.onlyStartTime,
             doctorSlotEndTime:event.onlyEndTime
             },
             dataType:'json',
           success:function(response){
            console.log(response);
             var len=0;
             if(response['data']!=null)
             {
               len=response['data'].length;

             }

             if(len>0)
             {
               for(var i=0;i<len;i++)
               {
                var id=response['data'][i].patient_id;
                var patient_name=response['data'][i].patient_name;
                var time24=response['data'][i].time;
                function tConv24(time24) {
                  var ts = time24;
                  var H = +ts.substr(0, 2);
                  var h = (H % 12) || 12;
                  h = (h < 10)?("0"+h):h;  // leading 0 at the left for 1 digit hours
                  var ampm = H < 12 ? " AM" : " PM";
                  ts = h + ts.substr(2, 3) + ampm;
                  return ts;
                };
                var tr_str=
                "<tr>"+
                "<td><a href='patient_record/"+id+"'>"+(i+1)+" -: "+patient_name+" at "+tConv24(time24)+"</a></td>"+
                "</tr>";
                $("#loaded").append(tr_str);
               }
             }
             else{
              var tr_str="<tr>"+
                "<td>No Appointment</td>"+
                "</tr>";
                $("#loaded").append(tr_str);
             }
           }
          });




					$('#ModalEdit #id').val(event.id);
					if(event.onlyTitle!="Availability")
					{
						$('#ModalEdit #titleinput').show();
						$('#ModalEdit #title').val(event.onlyTitle);
					}
					else{
						$('#ModalEdit #titleinput').hide();
					}
          function formatDate(date) {
          var d = new Date(date),
              month = '' + (d.getMonth() + 1),
              day = '' + d.getDate(),
              year = d.getFullYear();

          if (month.length < 2) month = '0' + month;
          if (day.length < 2) day = '0' + day;

          return [month, day, year].join('-');
      }
					$('#ModalEdit #ttype').html(event.editDate);
					$('#ModalEdit #type').html(formatDate(event.editDate));
					$('#ModalEdit #start').val(event.editstartDate);
					$('#ModalEdit #end').val(event.editendDate);
          $("#timepicker4").append("<option selected>"+event.onlyStartTime+"</option>");
          $("#timepicker5").append("<option selected>"+event.onlyEndTime+"</option>");
				//	$('#ModalEdit #timepicker4').val(event.onlyStartTime);
					// $('#ModalEdit #timepicker5').val(event.onlyEndTime);
					$('#ModalEdit #stime').html(event.onlyStartTime);
					$('#ModalEdit #etime').html(event.onlyEndTime);

					$('#ModalEdit').modal('show');



				});
			},
			eventDrop: function(event, delta, revertFunc)
      {
				edit(event);
			},
			eventResize: function(event,dayDelta,minuteDelta,revertFunc)
      {
				edit(event);
			},
			events: [
			@php
			foreach($events ?? '' as $event){
        $docid=Auth::user()->id;
        $dcStartTime=$event['slotStartTime'];
        $dcEndTime=$event['slotEndTime'];
        $doctorDate=$event['date'];
        $doc= DB::table('appointments')
            ->select('appointments.*')
            ->whereRaw('(DATE(appointments.date)=?)', [$doctorDate])
            ->whereRaw('(TIME(appointments.time)>=?)', [$dcStartTime])
            ->whereRaw('(TIME(appointments.time)<=?)', [$dcEndTime])
            ->where('appointments.doctor_id',$docid)
            ->get();


        // $doc= DB::table('doctor_schedules')
        //     ->join('appointments', 'appointments.doctor_id', '=', 'doctor_schedules.doctorID')
        //     ->select('doctor_schedules.*', 'appointments.*')
        //     ->whereRaw('(DATE(appointments.date)=?)', [$doctorDate])
        //     ->whereRaw('(TIME(appointments.time)>=?)', [$dcStartTime])
        //     ->whereRaw('(TIME(appointments.time)<=?)', [$dcEndTime])
        //     ->where('appointments.doctor_id',$docid)

           // ->whereRaw('(TIME(appointments.time)>=?)', [$dcStartTime])
          //  ->whereRaw('(TIME(appointments.time)<=?)', [$dcEndTime])
          //  ->whereRaw('(DATE(appointments.date)=?)', [$doctorDate])
           // ->whereRaw('TIME(appointments.time)>=?)',[$dcStartTime])
           // ->whereRaw('TIME(appointments.time))','<=',$dcEndTime)
          //  ->where([['doctor_schedules.doctorID',$docid]])
         //   ->get();
            $c=count($doc);




				$editstartDate=date("Y-m-d g:i a", strtotime($event['start']));
				$editendDate=date("Y-m-d g:i a", strtotime($event['end']));
				$start = explode(" ", $event['start']);
				$end = explode(" ", $event['end']);
				$stime = date("g:i a", strtotime($start[1]));
				$etime = date("g:i a", strtotime($end[1]));
				$editDate=$start[0];
				if($start[1] == '00:00:00'){
					$start = $start[0];
				}else{
					$start = $event['start'];
				}
				if($end[1] == '00:00:00'){
					$end = $end[0];
				}else{
					$end = $event['end'];
				}
				$titleholiday=$event['title'];
				if($titleholiday=="Availability")
				{
          $csv_var='you have '.$c.' appointment';
					$titleholiday="";
				}else{
					$titleholiday=$event['title'];
          $csv_var='Off';
          }

        @endphp
				{
					id: '<?php echo $event['id']; ?>',
					title: '<?php echo $stime.' To '.$etime.' '.$titleholiday.' ('.$csv_var.')';?>',
					start: '<?php echo $start; ?>',
					end: '<?php echo $end; ?>',
					color: '<?php echo $event['color']; ?>',
					StartDate: '<?php echo $event['start']; ?>',
					EndDate: '<?php echo $event['end']; ?>',
					editDate: '<?php echo $editDate; ?>',
					editstartDate: '<?php echo $editstartDate; ?>',
					editendDate: '<?php echo $editendDate; ?>',
					onlyTitle: '<?php echo $event['title']; ?>',
					onlyStartTime:'<?php echo $stime; ?>',
					onlyEndTime:'<?php echo $etime; ?>',
				},
        @php } @endphp
			]
		});
  });
  function edit(event){
			start = event.start.format('YYYY-MM-DD h:mm a');
			if(event.end){
				end = event.end.format('YYYY-MM-DD h:mm a');
			}else{
				end = start;
			}
			id =  event.id;
			Event = [];
			Event[0] = id;
			Event[1] = start;
			Event[2] = end;
			Event[3] = title;
			Event[4] = StartDate;
			Event[5] = EndDate;
			$.ajax({
			 url: 'editEventDate.php',
			 type: "POST",
			 data: {Event:Event},
			 success: function(rep) {
					if(rep == 'OK'){
						alert('Saved');
					}else{
						alert('Could not be saved try again');
					}
				}
			});
  }
    $('#doc_id').on("change",function(event) {
      id=$('#doc_id').val();
      console.log(id)
      window.location.href="/admin_doctor_calendar?id="+id;
    });

</script>
@endsection
