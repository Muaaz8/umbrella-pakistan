@extends('layouts.admin2')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>My Schedule</h2>
            <small class="text-muted ">Schedule is listed here</small>
            
        </div>
        <div class="col-md-12">
        <div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card">
					<div class="header col-md-9">
                        <h2>Add A Holiday<small>Fill the form for holiday</small> </h2>

                    </div>

                    <div class="body" >
                        <form method="POST" action="{{ route('holiday_store') }}">
                            @csrf
                            <h4>Select Day</h4>
                            <div class="demo-button-toolbar clearfix">
                            <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                                <div class="btn-group" role="group" aria-label="First group">
                                    @if($schedule->mon==1)
                                    <button type="button" id="d1" onclick="javascript:showdates(this)" class="btn btn-default waves-effect">Monday</button>
                                    @endif
                                    @if($schedule->tues==1)
                                    <button type="button" id="d2" onclick="javascript:showdates(this)" class="btn btn-default waves-effect">Tuesday</button>
                                    @endif
                                    @if($schedule->weds==1)
                                    <button type="button" id="d3" onclick="javascript:showdates(this)" class="btn btn-default waves-effect">Wednesday</button>
                                    @endif
                                    @if($schedule->thurs==1)
                                    <button type="button" id="d4" onclick="javascript:showdates(this)" class="btn btn-default waves-effect">Thursday</button>
                                    @endif
                                    @if($schedule->fri==1)
                                    <button type="button" id="d5" onclick="javascript:showdates(this)" class="btn btn-default waves-effect">Friday</button>
                                    @endif
                                    @if($schedule->sat==1)
                                    <button type="button" id="d6" onclick="javascript:showdates(this)" class="btn btn-default waves-effect">Saturday</button>
                                    @endif
                                    @if($schedule->sun==1)
                                    <button type="button" id="d7" onclick="javascript:showdates(this)" class="btn btn-default waves-effect">Sunday</button>
                                    @endif
                                </div>
                                </div>
                            </div>


                        
                        <div class="col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <div class="md-form">
                                          <input placeholder="Selected Date" name="date" type="text"  id="date" class="form-control"  required=""  >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <a href="{{route('appointment.index')}}"><button type="submit" class="btn btn-raised g-bg-cyan">Submit</button></a>
                                <button type="submit" class="btn btn-raised">Cancel</button>
                            </div>
                    </form>
                    </div>
				</div>
                </div>
			</div>
		</div>
    </div>
</section>
<div class="modal fade" id="datesModal" style="font-weight: normal;  " tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel"><span id="day" ></span>
                    
                <br>
                <small>Please select date</small>
            </h4>
                
                
            </div>
            <div class="modal-body" style="height: 240px;overflow-y: auto"> 
                <div class="list-group days" > 
                   
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection
@section('script')
<!-- Bootstrap Material Datetime Picker Plugin Js --> 

<script type="text/javascript">
function showdates(a) {
    // $(document).ready(function(){
        // $('.datepicker').datetimepicker({
        //     format:'d/m/Y',
        //     maxDate:'+0'

        // });
        $('.days').text("");
    var d = new Date(),
        month = d.getMonth(),
    dates = [];
    id=$(a).attr('id');
// console.log(id)
    // d.setDate(1);

    // Get the first date in the month
    if(id=='d1'){
    while (d.getDay() !== 1) {
        d.setDate(d.getDate() + 1);
    }
    $('#day').text('Monday');
    }else if(id=='d2'){
    while (d.getDay() !== 2) {
            d.setDate(d.getDate() + 1);
        }    
    $('#day').text('Tuesday');

    }else if(id=='d3'){
    while (d.getDay() !== 3) {
            d.setDate(d.getDate() + 1);
        }  

    $('#day').text('Wednesday');

    }else if(id=='d4'){
    while (d.getDay() !== 4) {
            d.setDate(d.getDate() + 1);
        }   
    $('#day').text('Thursday');

    }else if(id=='d5'){
    while (d.getDay() !== 5) {
            d.setDate(d.getDate() + 1);
        }    
    $('#day').text('Friday');

    }else if(id=='d6'){
    while (d.getDay() !== 6) {
            d.setDate(d.getDate() + 1);
        }
    $('#day').text('Saturday');

    }else if(id=='d7'){
    while (d.getDay() !== 0) {
            d.setDate(d.getDate() + 1);
        }    
    $('#day').text('Sunday');

    }  
    // Get all the other dates of that day in the month
    while (d.getMonth() === month) {
        dates.push(new Date(d.getTime()));
        d.setDate(d.getDate() + 7);
    }
console.log(dates);
    $.each( dates, function( key, value ) {
    // var split_date=sdate.split(' ');
    value+=value+"";
        frag=value.split(' ');
                     $('.days').append('<a href="javascript:void(0);" onclick="javascript:filldate(this)" class="list-group-item  list1">'+ frag[1]+" "+frag[2]+" "+frag[3] +'</a>');
                 });
    $('#datesModal').modal('show');
}
function filldate(a){
    $('#datesModal').modal('hide');
    var date=$(a).text();
    // cons
    $('#date').val(date);

}
// });
</script>
@endsection



