"use strict";
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});
function getmonth(m){
	var month="";
	if(m=='Jan'||m=='January')
		month='01';
	else if(m=='Feb'||m=='February')
		month='02';
	else if(m=='Mar'||m=='March')
		month='03';
	else if(m=='Apr'||m=='April')
		month='04';
	else if(m=='May'||m=='May')
		month='05';
	else if(m=='Jun'||m=='June')
		month='06';
	else if(m=='Jul'||m=='July')
		month='07';
	else if(m=='Aug'||m=='August')
		month='08';
	else if(m=='Sep'||m=='September')
		month='09';
	else if(m=='Oct'||m=='October')
		month='10';
	else if(m=='Nov'||m=='November')
		month='11';
	else if(m=='Dec'||m=='December')
		month='12';

	return month;
}
function getappointments(date){
	// var appoints;
	$.ajax({
           type:'POST',
           url: '/getappointments' ,
           data:{
                date:date,
           },
           success:function(appointments){
           	$('.booked-appoint').text("");
           	console.log(appointments);
           	$.each( appointments, function( key, value ) {
                     $('.booked-appoint').append('<a href="javascript:void(0);" onclick="javascript:filltime(this)" class="list-group-item  list1">Appointment with '+ value.patient_name +'<span style="margin-left:20%">'+value.time+'</span></a>');
                 });
           	// appoints=appointments;
           }
       });
	// return appoints;
}
$(document).ready(function(){
var d = new Date();
var dstr=d+"";
var d_split=dstr.split(' ');
var month1=d_split[1];
var date=d_split[2];
var year=d_split[3];
var month= getmonth(month1);
var fulldate=year+'-'+month+'-'+date;
console.log(month+" "+month1);
var id = $("#docId").val();
var holidays_arr=[];
var booked_app=[];
var urlappointment = '/bookedappoint';
var urlholidays = '/all_holidays';
 $.ajax({
           type:'POST',
           url: urlappointment ,
           data:{
                id:id,
           },
           success:function(booked){

           	$.each(booked,function(key, value){
           		var time_sp=value.time.split(" ");
           		var time=time_sp[0];
           		var am_pm=time_sp[1];
           		booked_app.push({
           			id: value.id,
				    title : 'Appointment', 
				    start : value.date+"T"+time
				});
           	});
           	console.log(booked);
           	console.log(booked_app);
           $.ajax({
		           type:'POST',
		           url: urlholidays ,
		           data:{
		                // id:id,
		           },
		           success:function(holidays){
           	$.each(holidays,function(key, value){
           		holidays_arr.push(value.date);
           	});
           	function onlyUnique(value, index, self) { 
    return self.indexOf(value) === index;
}

var unique_holiday = holidays_arr.filter( onlyUnique );

$('#calendar').fullCalendar({
	header: {
		left: 'prev',
		center: 'title',
		right: 'next'
	},
	defaultDate: fulldate,
	editable: true,
	eventLimit: true, // allow "more" link when too many events
	// events: booked,
	eventSources: [
    {
        events:booked_app,
        color: 'yellow',
        textColor: 'black'
    }
    ],
	dayClick: function(date, allDay, jsEvent, view) {
		var today = new Date();
	var holiday=false;

    if ($.inArray(date.format("YYYY-MM-DD"), unique_holiday) > -1) {
    	holiday=true;
    }

   //How many days to add from today?
   // var daysToAdd = 15;

   // myDate.setDate(myDate.getDate() + daysToAdd);

   if (date < today) {//modal before today with no holiday
   	var selected_date=date.format();
	var appointments=false;

		$.each(booked,function(key, value){
				var booked_date=value.date+"";
				// var bdate_split=bdate.split(' ');
				// var date=bdate_split[1];
				// var month1=bdate_split[2];
				// var year=bdate_split[3];
				// var month=getmonth(month1);
				// var booked_date=year+'-'+month+'-'+date;
				// console.log(selected_date);
				// console.log(bdate);

			if(booked_date==selected_date){
				// console.log(booked_date);

				appointments=true;
				var formatted_date=value.date;
				// var date_split=formatted_date.split(' ');
				// var date1=date_split[1];
				// var month2=date_split[2];
				// var year1=date_split[3];
				// var month3=getmonth(month2);
				// var format_date=year1+'-'+month3+'-'+date1;
				$('#date').text(formatted_date);
				getappointments(formatted_date);
				$('.holiday_btn').hide();
				$('#mdModal').modal('show');
			}

			});
		if(appointments==false){
				$('#empty_date').text(selected_date);
				$('#btn').attr('id',selected_date);
				$('.holiday_btn').hide();

				$('#emptyModal').modal('show');
			}
			// if(holidays==true){
			// 	$('#empty_date').text(selected_date);
			// 	$('#btn').attr('id',selected_date);
			// 	// $('.holiday_btn').hide();

			// 	$('#holidayModal').modal('show');
			// }

       //TRUE Clicked date smaller than today + daysToadd
       // alert("You cannot book on this day!");
   } else {//modals after today with holiday
		var selected_date=date.format();
		var appointments=false;

		$.each(booked,function(key, value){

				var booked_date=value.date+"";
				
			if(booked_date==selected_date){
				appointments=true;
				var formatted_date=value.date;
				
				$('#date').text(formatted_date);
				getappointments(formatted_date);
				$('.holiday_btn').show();

				$('#mdModal').modal('show');
				// console.log();
			}

			
		});
		if(appointments==false){
				$('#empty_date').text(selected_date);
				$('#btn').attr('id',selected_date);
				$('.holiday_btn').show();

				$('#emptyModal').modal('show');
			}

   // var today = new Date();

   // console.log(booked[1].date);

   //How many days to add from today?
   // var daysToAdd = 15;

   // myDate.setDate(myDate.getDate() + daysToAdd);

   // if (date < myDate) {
   //     //TRUE Clicked date smaller than today + daysToadd
   //     alert("You cannot book on this day!");
   // } else {
   //     //FLASE Clicked date larger than today + daysToadd
   //     alert("Excellent choice! We can book today..");
   }

},
 dayRender: function (date, cell) {
   // console.log(unique_holiday);
    if ($.inArray(date.format("YYYY-MM-DD"), unique_holiday) > -1) {
    // console.log(date.format("YYYY-MM-DD"));
    cell.css("background-color", "lightblue");
  }
//  $.each(unique_holiday,function(key,value){
//    console.log(value);
// var today = new Date();

// var hdate = moment(holidays_arr);
//     if (date.isSame(hdate, "day")) {
//       cell.css("background-color", "red");
//     }
}
        // if (date.getDate() === today.getDate()) {
        //     cell.css("background-color", "red");
        // }
         // // cell.css("background-color", "red");
         // if(date.getDate()==value.date){
         //  cell.css("background-color", "red");

         // }
     // }
    // },
//    eventRender: function (event, element, monthView) {
//  if (event.title == "HOLIDAY") {
//  var one_day = 1000 * 60 * 60 * 24;
//  var _Diff = Math.ceil((event.start.getTime() - monthView.visStart.getTime()/ 
//     (one_day));
//  var dayClass = ".fc-day" + _Diff;
// $(dayClass).addClass('holiday-color');
// }


// 	dayClick: function(date, jsEvent, view) {working

//     alert('Clicked on: ' + date.format());

//     // alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);

//     // alert('Current view: ' + view.name);

//     // change the day's background color just for fun
//     // $(this).css('background-color', 'red');
// }
});
$('.fc-today').css('background-color',' #2193b0');

}});



// Previous month action
$('#cal-prev').click(function(){
	$('#calendar').fullCalendar( 'prev' );
$('.fc-today').css('background-color',' #2193b0');

});

// Next month action
$('#cal-next').click(function(){
	$('#calendar').fullCalendar( 'next' );
$('.fc-today').css('background-color',' #2193b0');

});

// Change to month view
$('#change-view-month').click(function(){
	$('#calendar').fullCalendar('changeView', 'month');
$('.fc-today').css('background-color',' #2193b0');

	// $('#change-view-month').css('background-color',' #2193b0');

	// safari fix
	$('#content .main').fadeOut(0, function() {
		setTimeout( function() {
			$('#content .main').css({'display':'table'});
		}, 0);
	});

});

// Change to week view
$('#change-view-week').click(function(){
	$('#calendar').fullCalendar( 'changeView', 'agendaWeek');
// $('#change-view-week').css('background-color',' #2193b0')
	// safari fix
	$('#content .main').fadeOut(0, function() {
		setTimeout( function() {
			$('#content .main').css({'display':'table'});
		}, 0);
	});

});

// Change to day view
$('#change-view-day').click(function(){
	$('#calendar').fullCalendar( 'changeView','agendaDay');
// $('#change-view-day').css('background-color',' #2193b0')
	// safari fix
	$('#content .main').fadeOut(0, function() {
		setTimeout( function() {
			$('#content .main').css({'display':'table'});
		}, 0);
	});

});

// Change to today view
$('#change-view-today').click(function(){
	$('#calendar').fullCalendar('today');
});

/* initialize the external events
 -----------------------------------------------------------------*/
$('#external-events .event-control').each(function() {

	// store data so the calendar knows to render an event upon drop
	$(this).data('event', {
		title: $.trim($(this).text()), // use the element's text as the event title
		stick: true // maintain when user navigates (see docs on the renderEvent method)
	});

	// make the event draggable using jQuery UI
	$(this).draggable({
		zIndex: 999,
		revert: true,      // will cause the event to go back to its
		revertDuration: 0  //  original position after the drag
	});

});

$('#external-events .event-control .event-remove').on('click', function(){
	$(this).parent().remove();
});

// Submitting new event form
$('#add-event').submit(function(e){
	e.preventDefault();
	var form = $(this);

	var newEvent = $('<div class="event-control p-10 mb-10">'+$('#event-title').val() +'<a class="pull-right text-muted event-remove"><i class="fa fa-trash-o"></i></a></div>');

	$('#external-events .event-control:last').after(newEvent);

	$('#external-events .event-control').each(function() {

		// store data so the calendar knows to render an event upon drop
		$(this).data('event', {
			title: $.trim($(this).text()), // use the element's text as the event title
			stick: true // maintain when user navigates (see docs on the renderEvent method)
		});

		// make the event draggable using jQuery UI
		$(this).draggable({
			zIndex: 999,
			revert: true,      // will cause the event to go back to its
			revertDuration: 0  //  original position after the drag
		});

	});

	$('#external-events .event-control .event-remove').on('click', function(){
		$(this).parent().remove();
	});

	form[0].reset();

	$('#cal-new-event').modal('hide');

});

}
});


});
