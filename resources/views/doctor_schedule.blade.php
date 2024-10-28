@extends('layouts.admin2')

@section('content')
<style>
    table{
        border:none !important
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Schedule</h2>
            <small class="text-muted ">Schedule is listed here</small>
            
        </div>
        @if($schedule==null)
        <a href="{{route('add_schedule')}}"><button class="btn btn-raised g-bg-cyan">Set Up Schedule</button></a>
        @else
        <!-- <a href="{{route('edit_schedule', $schedule->id)}}"><button class="btn btn-raised g-bg-cyan">Edit Schedule</button></a> -->
        
        </div>
        @if(isset($success))
        <div class="alert-success">
            {{$success}}
        </div>
        @endif
@if(!empty($holidays[0]))
        <div class="row col-md-12">
        <div class="col-md-6 d-flex" >
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
@else
        <div class="col-md-12">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
@endif
				<div class="card">
                    <div class="row col-lg-12 col-md-12 col-sm-12 col-xs-12">
@if(!empty($holidays[0]))   
					<div class="header col-md-7">
@else
                    <div class="header col-md-9">
@endif
                        <h2>Schedule<small>Edit the form below to update schedule</small> </h2>
                    </div>
@if(empty($holidays[0]))   
        <a href="{{route('holiday', $schedule->id)}}"><button class="btn btn-raised g-bg-cyan float-left">Add Holiday</button></a>
        @endif

                    </div>
                    <div class="body" >
                        
                        <hr style="background-color: #ccc;">
                        <div class="row clearfix">
                            <div class="col-sm-12 col-xs-12">
                                <h5>Week Days<br><small>Mark the available days</small></h5>
                               
                                <div class="form-check form-check-inline">
                                @if($schedule->mon=='1')
                                    <input type="checkbox" class="form-check-input" id="d1" name="mon" value="1" checked="" disabled="" >
                                @endif
                                    <label class="form-check-label" for="d1" >
                                    Mon</label>

                                </div>
                                <div class="form-check form-check-inline">
                                @if($schedule->tues=='1')

                                    <input type="checkbox" class="form-check-input" id="d2" name="tues" value="1" checked="" disabled="">
                                @endif

                                    <label class="form-check-label" for="d2">Tues</label>
                                </div>
                                <div class="form-check form-check-inline">
                                @if($schedule->weds=='1')

                                    <input type="checkbox" class="form-check-input" id="d3" name="weds" value="1" checked="" disabled="">
                                @endif

                                    <label class="form-check-label" for="d3">Weds</label>
                                </div>
                                <div class="form-check form-check-inline">
                                @if($schedule->thurs=='1')

                                    <input type="checkbox" class="form-check-input" id="d4" name="thurs" value="1" checked="" disabled="">
                                @endif

                                    <label class="form-check-label" for="d4">Thurs</label>
                                </div>
                                <div class="form-check form-check-inline">
                                @if($schedule->fri=='1')

                                    <input type="checkbox" class="form-check-input" id="d5" name="fri" value="1" checked="" disabled="">
                                @endif

                                    <label class="form-check-label" for="d5">Fri</label>
                                </div>
                                <div class="form-check form-check-inline">
                                @if($schedule->sat=='1')

                                    <input type="checkbox" class="form-check-input" id="d6" name="sat" value="1" checked="" disabled="">
                                @endif
                                    
                                    <label class="form-check-label" for="d6">Sat</label>
                                </div>
                                <div class="form-check form-check-inline">
                                @if($schedule->sun=='1')

                                    <input type="checkbox" class="form-check-input" id="d7" name="sun" value="1" checked="" disabled="">
                                @endif
                                    
                                    <label class="form-check-label" for="d7">Sun</label>
                                </div>
                            </div>
                        </div>
   
                        <hr style="background-color: #ccc;">

                        <div class="row clearfix">
                            <div class=" col-sm-12 col-xs-12">
                                <h5>Timings<br><small>Time period in which appointments will be booked</small></h5>
                                <div id="time-range" style="margin-right: 0px">
                                    <p>Available Timings:
@if(!empty($holidays[0]))    
                                        <input type="text" name="from_time" class="slider-time m-2 col-md-3 " style="margin-left: 2%" value="{{$schedule->from_time}}" readonly=""> - 
                                        <input type="text" name="to_time" class="slider-time2 m-2 col-md-3" value="{{$schedule->to_time}}" readonly="">
@else
                                        <input type="text" name="from_time" class="slider-time m-2 col-md-4 " style="margin-left: 2%" value="{{$schedule->from_time}}" readonly=""> - 
                                        <input type="text" name="to_time" class="slider-time2 m-2 col-md-4" value="{{$schedule->to_time}}" readonly="">
@endif
                                    </p>                                    
                                </div>
                            </div>
                        </div>
@if(!empty($holidays[0]))    
                        <div class="col-md-12">
                            <a href="{{route('edit_schedule', $schedule->id)}}"><button class="btn btn-raised g-bg-cyan col-md-5">Edit Schedule</button></a>
                            <a href="{{route('del_schedule',$schedule->id)}}"><button class="btn btn-raised g-bg-cyan col-md-5">Delete Schedule</button></a>
                        </div>
@else
                        <div class="col-md-12">
                            <a href="{{route('edit_schedule', $schedule->id)}}"><button class="btn btn-raised g-bg-cyan col-md-2">Edit Schedule</button></a>
                            <a href="{{route('del_schedule',$schedule->id)}}"><button class="btn btn-raised g-bg-cyan col-md-2">Delete Schedule</button></a>
                        </div>
@endif
                    </div>
				</div>
                </div>
			</div>
		</div>
        @endif
@if(!empty($holidays[0]))    
<div class="col-md-6 d-flex">
    <div class="row col-12">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="row col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="header col-md-9">
                        <h2>Holidays<small>Edit the form below to update schedule</small> </h2>
                    </div>
                    
        <a href="{{route('holiday', $schedule->id)}}"><button class="btn btn-raised btn-default offset-2 btn-circle float-left"><div class="demo-google-material-icon"> <i class="material-icons">add_circle</i>  </div></button></a>

                    </div>
                    <div class="body" >
                <div class="list-group " > 

                        @foreach($holidays as $holiday)
                            <a href="javascript:void(0);" class="list-group-item ">{{$holiday->date}} <button class="offset-8 btn-default btn-circle">X</button></a>
                        @endforeach
                    </div>
                    </div>
                </div>       
        </div>
    </div>
</div>
</div>
@endif
</div>

</section>
@endsection
@section('script')
@endsection



