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
                        <h2>Reschedule Appointment Form<small>Fill the form below</small> </h2>
                    </div>
                    <div class="body">
                        <form method="POST" action="{{ route('appointment.update',['id'=>$appointment->id]) }}">
                            @csrf
                            @method('put')
                            <input type="hidden" value="{{ $specialization->id }}" name="spec_id">
                            <input type="hidden" value="{{ $appointment->id }}" name="app_id">
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

                                    <label for="floatingInputGrid" style="font-weight:600 !important;">Service Provider</label>
                                    <select class="form-control" id="docId" name="provider" required="" >
                                        <option value="">Select Provider </option>
                                        @foreach($doctors as $doctor)
                                            <option value="{{$doctor->id}}" selected>{{$doctor->name." ".$doctor->last_name}}<small>({{$specialization->name}})</small>
                                        </option>
                                        @endforeach
                                    </select>

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
                                        <input required="" id="d1" type="text" name="date" class="datepicker form-control" placeholder="Please choose date">
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
$("#d1").on("change", function() {
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
                            var tr_str = "<option value='" + value.start + "'>" + value.t_start + " to "+value.t_end+"</option>";
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

</script>



@endsection
