@extends('layouts.admin')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Umbrella Health Care Systems</h2>
            <small class="text-muted">Schedule an appointment with real doctor</small>
            @if($errors->any())
            @foreach ($errors->all() as $error)
            <div class="alert alert-danger">
                <strong>Danger!</strong> {{ $error }}
            </div>

            @endforeach
            @endif
            @if (\Session::has('message'))
            <div class="alert alert-success">
                <ul>
                    <li>{!! \Session::get('message') !!}</li>
                </ul>
            </div>
            @endif



        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>Appointment Form<small>Fill the form below</small> </h2>
                    </div>
                    <div class="body">
                        <form method="POST" action="{{ route('appointment.store') }}">
                            @csrf
                            <input type="hidden" value="{{ $spec }}" name="spec_id">
                            <h5><b>Patient Information</b></h5>
                            <hr style="background-color: #ccc;">
                            <div class="row clearfix">


                                <div class="col-sm-6 col-xs-12 ">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="floatingInputGrid" style="font-weight:600 !important;">Patient First Name</label>
                                            <input type="text" name="fname" class="form-control"
                                                value="{{$user->name}}" placeholder="First Name" readonly="readonly">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="floatingInputGrid" style="font-weight:600 !important;">Patient Last Name</label>
                                            <input type="text" name="lname" class="form-control"
                                                value="{{$user->last_name}}" placeholder="Last Name"
                                                readonly="readonly">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="floatingInputGrid " style="font-weight:600 !important;">Patient Email</label>
                                            <input type="email" name="email" class="form-control"
                                                value="{{$user->email}}" placeholder="Enter Your Email"
                                                readonly="readonly">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="floatingInputGrid" style="font-weight:600 !important;">Patient Phone</label>
                                            <input type="text" value="{{$user->phone_number}}" name="phone"
                                                class="form-control" placeholder="Phone" readonly="readonly">
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <br>
                    <div class="col-12">
                        <h5><b>Appointment Information<b></h5>

                        <hr style="background-color: #ccc;">
                        <div class="row clearfix">

                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group drop-custum" style="border: none">
                                    @if(isset($_GET['doc']))
                                    @php
                                    $doc=\App\User::find($_GET['doc']);
                                    $doc_name='Dr. '.$doc->name.' '.$doc->last_name;
                                    //echo "Provider: ".$doc_name;
                                    @endphp
                                    <input type="text" name="provider_name" class="form-control" readonly="readonly"
                                                        value="Provider: {{$doc_name}}">
                                    <input hidden name="provider" id="docId" value="{{$_GET['doc']}}">
                                    @else
                                    <label for="floatingInputGrid" style="font-weight:600 !important;">Service Provider</label>
                                    <select class="form-control" id="docId" name="provider" required="" >
                                        <option value="">Select Provider </option>
                                        @foreach($doctors as $doctor)
                                            <option value="{{$doctor->id}}" selected>{{$doctor->name." ".$doctor->last_name}}<small>({{$doctor->spec}})</small>
                                        </option>
                                        @endforeach
                                    </select>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label for="floatingInputGrid" style="font-weight:600 !important;">Symptoms</label>
                                        <textarea required="" rows="4" id="symp_text" name="problem"
                                            class="form-control no-resize" placeholder="Symptoms"></textarea>
                                    </div>
                                </div>
                            </div>

                            <br>
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label for="floatingInputGrid" style="font-weight:600 !important;">Choose Appointment Date </label>
                                        <select class="form-control" id="d2" name="date" required="" >
                                            @if(!$Availabale_dates->isEmpty())
                                            <option value="">Available Dates</option>
                                            @foreach($Availabale_dates as $AD)
                                                <option value="{{$AD->date}}">{{date("m-d-Y", strtotime($AD->date))}}</option>
                                            @endforeach
                                            @else
                                            <option value="">No Date Available</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">

                                    <div class="form-line">
                                        <div class="md-form">
                                            <label for="floatingInputGrid" style="font-weight:600 !important;">Choose Appointment Time </label>
                                            <select class="form-control" id="timeSolt" name="time" required>
                                                <option value="">Select Time</option>
                                            </select>
                                            <div id="result"></div>
                                        </div>
                                    </div>

                                </div>
                                <p id="messageLoad" style="color:red;"></p>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <button type="submit" id="appoint_submit" class="btn btn-raised g-bg-cyan">Submit</button>
                                    </form>
                                    <button onclick="window.location='/home'" class="btn btn-raised">Cancel</button>
                                </div>
                            </div>

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
                <h4 class="modal-title" id="defaultModalLabel"><span id="date">24 August 2020</span>
                    <small>
                        <br>Available Timimgs:</small><span id="start"></span>-<span id="end"></span>
                    <br>
                    <small>Please select time slot for appointment</small>
                </h4>


            </div>
            <div class="modal-body" style="height: 320px;overflow-y: auto">
                <div class="list-group timing-slots">

                </div>
            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="emptyModal" style="font-weight: normal; " tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel"></h4>
            </div>
            <div class="modal-body">
                Doctor<b> not </b>available on <span id="date1" class="font-bold"></span>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
<div class="modal fade" id="symptomsModal" style="font-weight: normal; " tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="symp">Symptoms</h4>

            </div>
            <div class="modal-body" style="height: 200px;">
                <div class="form-check ">
                    <input type="checkbox" class="form-check-input" id="s1" name="Headache" value="1">
                    <label class="form-check-label" for="s1">
                        Headache</label>
                </div>
                <div class="form-check ">
                    <input type="checkbox" class="form-check-input" id="s2" name="Flu" value="1">
                    <label class="form-check-label" for="s2">Flu</label>
                </div>
                <div class="form-check ">
                    <input type="checkbox" class="form-check-input" id="s3" name="Fever" value="1">
                    <label class="form-check-label" for="s3">Fever</label>
                </div>
                <div class="form-check ">
                    <input type="checkbox" class="form-check-input" id="s4" name="Nausea" value="1">
                    <label class="form-check-label" for="s4">Nausea</label>
                </div>
                <div class="form-check ">
                    <input type="checkbox" class="form-check-input" id="s5" name="Others" value="1">
                    <label class="form-check-label" for="s5">Others</label>
                </div>
            </div>
            <div class="modal-footer">
                <!-- <a class="col-md-12" href="#"> -->
                <button type="button" id="symp_btn" class="btn btn-link waves-effect">ADD SYMPTOMS</button>
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                <!-- </a> -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')

<script src="asset_admin/js/pages/forms/basic-form-elements.js"></script>
<script src="asset_admin/plugins/momentjs/moment.js"></script> <!-- Moment Plugin Js -->

<script src="asset_admin/plugins/autosize/autosize.js"></script> <!-- Autosize Plugin Js -->
<script src="asset_admin/js/pages/ui/modals.js"></script>

<!-- <script src="asset_admin/js/calendarfile/mdtimepicker.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script> -->
<!--Script for timeslots  -->
<script type="text/javascript">
<?php header("Access-Control-Allow-Origin: *"); ?>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$('#d1').bootstrapMaterialDatePicker({
    format: 'dddd DD MMMM YYYY',
    time: false,
    minDate: new Date()
});

$('#symp_text').click(function() {
    $('#symptomsModal').modal('show');
});
$("#docId").on("change", function() {
    var tr_str = "<option value=''>Select Time</option>";
    $("#timeSolt").html(tr_str);
    $('#d1').val('');
});
$("#d2").on("change", function() {
    var sdate = $(this).val();
    var id = $("#docId").val();

    if (id == '' && sdate == '')
    {
        var tr_str = "<option value=''>Select Time</option>";
        $("#timeSolt").html(tr_str);
    }
    else
    {
        var tr_str = "<option value=''>Select Time</option>";
        $("#timeSolt").html(tr_str);
        $.ajax({
            type: 'POST',
            url: "{{route('timing')}}",
            data: {
                id: id,
                sdate: sdate,
            },
            dataType: 'json',
            success: function(response) {
                var vv=JSON.parse(JSON.stringify(response['data']));
                if (response['data'] != null) {
                    len = response['data'].length;
                }
                if (len > 0) {
                        $.each(vv, function(key, value) {
                            var tr_str = "<option value='" + value.start + "'>" + value.t_start + " to "+ value.t_end +"</option>";
                            $("#timeSolt").append(tr_str);
                        });
                } else {
                    alert('That Doctor have no schedule for '+'"'+sdate+'"');
                    var tr_str = "<option value=''>Select Time</option>";
                    $("#timeSolt").html(tr_str);
                }
            }
        });
    }
});

$('#symp_btn').click(function()
{
    var temp = "";
    if ($('#s1').is(":checked"))
    {
        temp += $('#s1').attr('name') + " ";
    }
    if ($('#s2').is(":checked"))
    {
        temp += $('#s2').attr('name') + " ";
    }
    if ($('#s3').is(":checked"))
    {
        temp += $('#s3').attr('name') + " ";
    }
    if ($('#s4').is(":checked"))
    {
        temp += $('#s4').attr('name') + " ";
    }
    if ($('#s5').is(":checked"))
    {
        temp += $('#s5').attr('name') + " ";
    }
    $('#symp_text').val(temp);
    $('#symptomsModal').modal('hide');
});

function filltime(a)
{
    var time = $(a).text();
    $('#time').val(time);
    $('#mdModal').modal('hide');
}
// function addMinutes(time, minutes)
// {
//     var date = new Date(new Date('01/01/2015 ' + time).getTime() + minutes * 60000);
//     var tempTime = ((date.getHours().toString().length == 1) ? '0' + date.getHours() : date.getHours()) + ':' +
//         ((date.getMinutes().toString().length == 1) ? '0' + date.getMinutes() : date.getMinutes()) + ':' +
//         ((date.getSeconds().toString().length == 1) ? '0' + date.getSeconds() : date.getSeconds());
//     return tempTime;
// }
// function tConv24(time24)
// {
//     var ts = time24;
//     var H = +ts.substr(0, 2);
//     var h = (H % 12) || 12;
//     h = (h < 10) ? ("0" + h) : h; // leading 0 at the left for 1 digit hours
//     var ampm = H < 12 ? " AM" : " PM";
//     ts = h + ts.substr(2, 3) + ampm;
//     return ts;
// }
// $("#timeSolt").on("change", function() {
//     var time = $("#timeSolt").val();
//     var sdate = $("#d1").val();
//     var id = $("#docId").val();
//     $.ajax({
//         type: 'POST',
//         url: "{{route('checkAlredyBookTiming')}}",
//         data: {
//             id: id,
//             sdate: sdate,
//             time: time,
//         },
//         success: function(response) {
//             if(response!='')
//             {
//                 $("#appoint_submit").prop('disabled', true);
//                 $("#messageLoad").html(response);
//             }
//             else{
//                 $("#appoint_submit").prop('disabled', false);
//                 $("#messageLoad").html(response);
//             }
//         }
//     });
// });
// function timeslots() {
//     var id = $("#docId").val();
//     var sdate = $('#d1').val();
//     var split_date = sdate.split(' ');
//     var day = split_date[0];
//     $('#date').html(split_date[1] + " " + split_date[2] + " " + split_date[3]);
//     $('#date1').html(sdate);
//     var urltiming = '/timing';
//     var urlappointment = '/booked';
//     $.ajax({
//         type: 'POST',
//         url: urlappointment,
//         data: {
//             id: id,
//             full_date: sdate,
//         },
//         success: function(booked) {
//             // console.log(data, sdate);
//             $.ajax({
//                 type: 'POST',
//                 url: urltiming,
//                 data: {
//                     id: id,
//                     day: day,
//                 },
//                 success: function(data) {
//                     if (data != "0") {
//                         split_2 = data.split(',');
//                         from = split_2[0];
//                         to = split_2[1];
//                         $('#start').text(from);
//                         $('#end').text(to);
//                         farray = from.split(':');
//                         fhour = farray[0];
//                         fmarr = farray[1].split(' ');
//                         fm = fmarr[1];
//                         tarray = to.split(':');
//                         thour = tarray[0];
//                         tmarr = tarray[1].split(' ');
//                         tm = tmarr[1];
//                         //console.log(fmarr[1]);
//                         console.log(data);
//                         x = 10; //minutes interval
//                         times = []; // time array
//                         ap = ['AM', 'PM']; // AM-PM

//                         if (fm == 'AM' && tm == 'AM') {
//                             st = fhour * 60; // start time
//                             et = thour * 60; //end time
//                         } else if (fm == 'AM' && tm == 'PM') {
//                             st = fhour * 60; // start time
//                             et = thour * 60 + 12 * 60; //end time
//                         } else if (fm == 'PM' && tm == 'PM') {
//                             st = fhour * 60 + 12 * 60; // start time
//                             et = thour * 60 + 12 * 60; //end time
//                         }

//                         //loop to increment the time and push results in array
//                         for (var i = 0; st < et; i++) {
//                             var hh = Math.floor(st / 60); // getting hours of day in 0-24 format
//                             var mm = (st % 60); // getting minutes of the hour in 0-55 format
//                             times[i] = ("0" + (hh % 12)).slice(-2) + ':' + ("0" + mm).slice(-
//                                 2) + " " + ap[Math.floor(hh /
//                                 12)]; // pushing data in array in [00:00 - 12:00 AM/PM format]
//                             st = st + x;
//                         }

//                         if (booked.length != 0) {
//                             var btime = [];
//                             $.each(booked, function(key1, value1) {
//                                 btime.push(value1.time);
//                             });
//                             // console.log(btime);
//                             var filtered = times.filter(function(e) {
//                                 return this.indexOf(e) < 0;

//                             }, btime)

//                             // console.log(filtered);
//                             $.each(filtered, function(key, value) {
//                                 $('.timing-slots').append(
//                                     '<a href="javascript:void(0);" onclick="javascript:filltime(this)" class="list-group-item  list1">' +
//                                     value + '</a>');
//                             });


//                         } else {
//                             $.each(times, function(key, value) {
//                                 $('.timing-slots').append(
//                                     '<a href="javascript:void(0);" onclick="javascript:filltime(this)" class="list-group-item  list1">' +
//                                     value + '</a>');
//                             });
//                         }
//                         $('#mdModal').modal('show');

//                     } else {
//                         $('#emptyModal').modal('show');
//                         $('#time').val("");


//                     }
//                 }
//             });
//         }
//     });
// }
</script>



@endsection
