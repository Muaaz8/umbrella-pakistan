@extends('layouts.admin')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Edit Schedule</h2>
            <!-- <small class="text-muted">Schedule an appointment with real doctor</small> -->
        </div>
        <div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card">
					<div class="header">
						<h2>Schedule Form<small>Edit the form below to update schedule</small> </h2>
					</div>
					<div class="body" >
                        <form method="POST" action="{{ route('update_schedule',$schedule->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <hr style="background-color: #ccc;">
                        <input id='monHidden' type='hidden' value="0" name='mon'>
                        <input id='tuesHidden' type='hidden' value="0" name='tues'>
                        <input id='wedsHidden' type='hidden' value="0" name='weds'>
                        <input id='thursHidden' type='hidden' value="0" name='thurs'>
                        <input id='friHidden' type='hidden' value="0" name='fri'>
                        <input id='satHidden' type='hidden' value="0" name='sat'>
                        <input id='sunHidden' type='hidden' value="0" name='sun'>
                        <div class="row clearfix">
                            <div class="col-sm-12 col-xs-12">
                                <h5>Week Days<br><small>Mark the available days</small></h5>
                               
                                <div class="form-check form-check-inline">
                                @if($schedule->mon=='1')
                                    <input type="checkbox" class="form-check-input" id="d1" name="mon" value="1" checked="" >
                                @else
                                 <input type="checkbox" class="form-check-input" id="d1" name="mon" value="1">
                                 @endif
                                    <label class="form-check-label" for="d1" >
                                    Mon</label>

                                </div>
                                <div class="form-check form-check-inline">
                                @if($schedule->tues=='1')

                                    <input type="checkbox" class="form-check-input" id="d2" name="tues" value="1" checked="">
                                @else
                                 <input type="checkbox" class="form-check-input" id="d2" name="tues" value="1">
 								@endif
                                    <label class="form-check-label" for="d2">Tues</label>
                                </div>
                                <div class="form-check form-check-inline">
                                @if($schedule->weds=='1')

                                    <input type="checkbox" class="form-check-input" id="d3" name="weds" value="1" checked="">
                                @else
                                <input type="checkbox" class="form-check-input" id="d3" name="weds" value="1" >
                                @endif

                                    <label class="form-check-label" for="d3">Weds</label>
                                </div>
                                <div class="form-check form-check-inline">
                                @if($schedule->thurs=='1')

                                    <input type="checkbox" class="form-check-input" id="d4" name="thurs" value="1" checked="">
                                    @else
                                <input type="checkbox" class="form-check-input" id="d4" name="thurs" value="1" >
                                @endif

                                    <label class="form-check-label" for="d4">Thurs</label>
                                </div>
                                <div class="form-check form-check-inline">
                                @if($schedule->fri=='1')

                                    <input type="checkbox" class="form-check-input" id="d5" name="fri" value="1" checked="">
                                    @else
                                <input type="checkbox" class="form-check-input" id="d5" name="fri" value="1">
                                @endif

                                    <label class="form-check-label" for="d5">Fri</label>
                                </div>
                                <div class="form-check form-check-inline">
                                @if($schedule->sat=='1')

                                    <input type="checkbox" class="form-check-input" id="d6" name="sat" value="1" checked="">
                                    @else
                                    <input type="checkbox" class="form-check-input" id="d6" name="sat" value="1" >
                                @endif

                                    <label class="form-check-label" for="d6">Sat</label>
                                </div>
                                <div class="form-check form-check-inline">
                                @if($schedule->sun=='1')

                                    <input type="checkbox" class="form-check-input" id="d7" name="sun" value="1" checked="">
                                    @else
                                
                                    <input type="checkbox" class="form-check-input" id="d7" name="sun" value="1" >
                                    @endif
                                    <label class="form-check-label" for="d7">Sun</label>

                                </div>
                            </div>
                        </div>
   
                        <hr style="background-color: #ccc;">

                        <div class="row clearfix">
                            <div class=" col-sm-12 col-xs-12">
                                <h5>Timings<br><small>Move pointers to fill time period in which appointments will be booked</small></h5>
                                <div id="time-range" style="margin-right: 0px">
                                    <div class="sliders_step1 m-4">
                                        <div id="slider-range"></div>
                                    </div>
                                    <p>Availability Time Range: 
                                        <input type="text" name="from_time" class="slider-time m-2 col-md-4 " style="margin-left: 2%" value="{{$schedule->from_time}}" readonly=""> - 
                                        <input type="text" name="to_time" class="slider-time2 m-2 col-md-4" value="{{$schedule->to_time}}" readonly="">
                                    </p>                                    
                                </div>
                            </div>
                        </div>

                            <div class="col-sm-12">
                                <a href="{{url('/')}}"  id="btn" ><button type="submit" class="btn btn-raised g-bg-cyan">Submit</button></a>
                                <button type="submit" class="btn btn-raised">Cancel</button>
                            </div>
                        </form>
                    </div>
				</div>
			</div>
		</div>
    </div>
</section>

@endsection
@section('script')
<script src="{{ asset('asset_admin/plugins/jquery-ui/jquery-ui.js')}}"></script>
<script type="text/javascript">
    $('#btn').click(function(){
        if($('#monHidden').checked) {
            $(this).disabled = true;
        }
        if($('#tuesHidden').checked) {
            $(this).disabled = true;
        }
        if($('#wedsHidden').checked) {
            $(this).disabled = true;
        }
        if($('#thursHidden').checked) {
            $(this).disabled = true;
        }
        if($('#friHidden').checked) {
            $(this).disabled = true;
        }
        if($('#satHidden').checked) {
            $(this).disabled = true;
        }
        if($('#sunHidden').checked) {
            $(this).disabled = true;
        }
        
    });
    
</script>
<script type="text/javascript">
	// $(document).ready(function(){
 //    	var from_time=$('.slider-time').val();
 //    	var ft=from_time.split(':')
 //    	var to_time=$('.slider-time2').val();
 //    	var tt=to_time.split(':')

 //    	$('#span').val(ft[0]);
 //    	$('#span1').val(tt[0]);
	// });
</script>
<script type="text/javascript">
	
    $("#slider-range").slider({
    range: true,
    min: 0,
    max: 1440,
    step: 15,
    values: [0,0],
    slide: function (e, ui) {
        var hours1 = Math.floor(ui.values[0] / 60);
        var minutes1 = ui.values[0] - (hours1 * 60);

        if (hours1.length == 1) hours1 = '0' + hours1;
        if (minutes1.length == 1) minutes1 = '0' + minutes1;
        if (minutes1 == 0) minutes1 = '00';
        if (hours1 >= 12) {
            if (hours1 == 12) {
                hours1 = hours1;
                minutes1 = minutes1 + " PM";
            } else {
                hours1 = hours1 - 12;
                minutes1 = minutes1 + " PM";
            }
        } else {
            hours1 = hours1;
            minutes1 = minutes1 + " AM";
        }
        if (hours1 == 0) {
            hours1 = 12;
            minutes1 = minutes1;
        }

        $('.slider-time').val(hours1 + ':' + minutes1);

        var hours2 = Math.floor(ui.values[1] / 60);
        var minutes2 = ui.values[1] - (hours2 * 60);

        if (hours2.length == 1) hours2 = '0' + hours2;
        if (minutes2.length == 1) minutes2 = '0' + minutes2;
        if (minutes2 == 0) minutes2 = '00';
        if (hours2 >= 12) {
            if (hours2 == 12) {
                hours2 = hours2;
                minutes2 = minutes2 + " PM";
            } else if (hours2 == 24) {
                hours2 = 11;
                minutes2 = "59 PM";
            } else {
                hours2 = hours2 - 12;
                minutes2 = minutes2 + " PM";
            }
        } else {
            hours2 = hours2;
            minutes2 = minutes2 + " AM";
        }

        $('.slider-time2').val(hours2 + ':' + minutes2);
    }
});
 </script>

@endsection