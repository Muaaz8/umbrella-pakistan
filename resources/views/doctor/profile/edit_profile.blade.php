@extends('layouts.admin')
@section('css')
<link rel="stylesheet" href="{{ asset('asset_admin/css/edit-profile.css')}}">
<style>
.profile_picture {
    border-radius: 100px;
    opacity: 1;
    height: 200px;
    width: 200px;
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
}
.custom-file-label{
    border: white solid 1px;
    padding: 5px;
    border-radius: 5px;
}
</style>
@endsection

@section('content')
<script>
setTimeout(function() {
    $('.messageDiv').fadeOut('fast');
}, 2000);
</script>
<section class="content profile-page">
    <div class="container-fluid">
        <div class="block-header">
            @include('flash::message')
        </div>
        <div class="container">
            <div class="card text-center shadow-lg edit-profile">
                <div class="card-body justify-content-center">
                    <form method="POST" novalidate id="form" action="{{ url('/edit_doctor_profile') }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        @if ($errors->any())
                                <div class="d-flex justify-content-center">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li class="alert alert-danger">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">

                                        <img id="img" class="profile_picture" style='background-image:url("{{$doctor_data->user_image}}");' />

                                    </div>
                                    <div class="upload-btn-wrapper">
                                        <label for="file" class="custom-file-upload">
                                            <i class="fa fa-plus"></i>
                                        </label>
                                        <input id="file" name='image' type="file" style="display:none;"
                                            accept=".jpg,.jpeg,.png" capture onchange="validateFileType();">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group row ">
                                    <label for="fname" class="col-sm-2 col-form-label">First Name</label>
                                    <div class="col-sm-4">
                                        <input id="fname" type="text" name="fname" class="form-control  pl-3 pr-3 stl"
                                            value="{{ $doctor_data->name }}" required>
                                    </div>
                                    <label for="lname" class="col-sm-2 col-form-label">Last Name</label>
                                    <div class="col-sm-4">
                                        <input type="text" id="lname" name="lname" class="form-control  pl-3 pr-3 stl"
                                            value="{{ $doctor_data->last_name }}" required>
                                    </div>
                                </div>
                                <input id="set_date" hidden value="{{ $doctor_data->date_of_birth}}">
                                <div class="form-group row ">
                                    <label class="col-sm-2 col-form-label">Date Of Birth</label>
                                    <div class="col-sm-4 ">
                                        <input type="date" id="dob" name="dob" max="{{ $min_age_date}}"
                                            class="form-control pl-3 pr-3 stl" value="{{ $doctor_data->date_of_birth}}"
                                            required>
                                    </div>
                                    <label class="col-sm-2 col-form-label">Phone Number</label>
                                    <div class="col-sm-4 ">
                                        <input id="number" type="number" name="phoneNumber" maxlength="16" min="1"
                                            max="" class="form-control pl-3 pr-3 stl"
                                            value="{{ $doctor_data->phone_number }}" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">ZipCode</label>
                                    <div class="col-sm-4 ">
                                        <input type="number" id="zipcode" name="zip_code"
                                            class="form-control pl-3 pr-3 stl" placeholder="Enter Zipcode"
                                            value="{{ $doctor_data->zip_code }}" disabled>
                                        <p id="zipcode" style="color:red;"></p>
                                    </div>
                                    <label for="zipcode" class="col-sm-2 col-12 form-label">State</label>
                                    <div class="col-sm-4">
                                        <select name="state" id="state" class="form-control pl-3 pr-3 stl" disabled>
                                            @foreach ($doctor_data->states as $st)
                                            @if ($st->id == $doctor_data->state->id)
                                            <option value="{{ $st->id }}" selected="selected">
                                                {{ $st->name }}</option>
                                            @else
                                            <option value="{{ $st->id }}">{{ $st->name }}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <input name="country" value="233" type="hidden">
                                </div>


                                <div class="form-group row ">
                                    <label class="col-sm-2 col-form-label">City</label>
                                    <div class="col-sm-4 ">
                                        <select name="city" id="city" class="form-control pl-3 pr-3 stl" disabled>
                                            @foreach ($doctor_data->cities as $cit)
                                            @if ($cit->id == $doctor_data->city->id)
                                            <option value="{{ $cit->id }}" selected="selected">
                                                {{ $cit->name }}</option>
                                            @else
                                            <option value="{{ $cit->id }}">{{ $cit->name }}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <label class="col-sm-2 col-form-label"></label>
                                    <div class="col-sm-4 ">
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Address</label>
                                    <div class="col-sm-10 ">
                                        <input id="address" type="text" name="address"
                                            class="form-control pl-3 pr-3 stl"
                                            value="{{ $doctor_data->office_address }}" required>
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    <label class="col-sm-2 col-form-label">Specialization</label>
                                    <div class="col-sm-10 ">
                                        <select name="specialization" id="specialization"
                                            class="form-control pl-3 pr-3 stl" required>
                                            @foreach ($specs as $sp)
                                            @if ($sp->id == $doctor_data->spec->id)
                                            <option value="{{ $sp->id }}" selected="selected">
                                                {{ $sp->name }}</option>
                                            @else
                                            <option value="{{ $sp->id }}">{{ $sp->name }}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Bio</label>
                                    <div class="col-sm-10 ">
                                        <textarea name="bio" id="bio" class="form-control pl-3 pr-3 pt-3 pb-3 stl"
                                            style="height:200px;  border:1px solid #cccccc; ; " rows="5" cols="5"
                                            required maxlength="200">{{ $doctor_data->bio }}</textarea>
                                    </div>
                                </div>
                                <p id="error_msg" style="color:red;text-align:center;font-size:20px"></p>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Add Certificates</label>
                                    <div class="col-sm-10" style="text-align:left !important">
                                        <div class="custom-file col-sm-12 p-0">
                                            <input type="file" style="width:88%" id="customFile" name="file[]" multiple>
                                            <label class="custom-file-label" for="customFile">Choose file</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-10 offset-sm-2">
                                        <a href="{{ url(auth()->user()->username) }}">
                                            <button type="button" style="background-color:black !important"
                                                class="btn callbtn">Back</button>
                                        </a>
                                        <button type="submit" class="btn callbtn">Update</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
            </form>
        </div>
    </div>
    </div>
    </div>
</section>

@endsection
@section('script')
<script src="asset_admin/plugins/autosize/autosize.js"></script>
<!-- Autosize Plugin Js -->
<script src="asset_admin/js/pages/forms/basic-form-elements.js"></script>

<script src="asset_admin/plugins/momentjs/moment.js"></script>
<!-- Moment Plugin Js -->
<script src="asset_admin/plugins/dropzone/dropzone.js"></script>
<!-- Dropzone Plugin Js -->

<!-- Bootstrap Material Datetime Picker Plugin Js -->
<script src="asset_admin/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    dt = $("#dob").val();
    date = new Date(dt.replace(" ", "T"));
    $("#dob").datepicker("setDate", date);
    $('#dob').val(date);
})

function getFile() {
    document.getElementById("upfile").click();
}

function sub(obj) {
    var file = obj.value;
    var fileName = file.split("\\");
    document.getElementById("yourBtn").innerHTML = fileName[fileName.length - 1];
    document.myForm.submit();
    event.preventDefault();
}

function file_changed() {
    var selectedFile = document.getElementById('file').files[0];
    var img = document.getElementById('img')

    var reader = new FileReader();
    reader.onload = function() {
        img.src = this.result
    }
    reader.readAsDataURL(selectedFile);
}

function getFile() {
    document.getElementById("upfile").click();
}

function validateFileType() {
    var fileName = document.getElementById("file").value;
    var idxDot = fileName.lastIndexOf(".") + 1;
    var extFile = fileName.substr(idxDot, file.length).toLowerCase();
    if (extFile == "jpg" || extFile == "jpeg" || extFile == "png") {
        file_changed();
    } else {
        alert("Only image are allowed!");
        document.getElementById("file").value = "";
        // document.getElementById("img").value = "";

        $('#img').attr('background-image', "");


    }
}


<?php header('Access-Control-Allow-Origin: *'); ?>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('#country').change(function() {
    id = $(this).val();
    console.log(id);
    $.ajax({
        type: 'POST',
        url: '/get_states',
        data: {
            id: id,
        },
        success: function(data) {
            console.log(data)
            $('#state').text('');
            $('#state').append('<option value="">Choose State</option>');
            $('#city').text('');
            $('#city').append('<option value="">Choose City</option>');
            $.each(data, function(key, value) {
                $('#state').append('<option value="' + value.id + '">' + value.name +
                    '</option>')
            })
        }
    });
})
$('#state').change(function() {
    id = $(this).val();
    console.log(id);
    $.ajax({
        type: 'POST',
        url: '/get_cities',
        data: {
            id: id,
        },
        success: function(data) {
            console.log(data)
            $('#city').text('');
            $('#city').append('<option value="">Choose City</option>');
            $.each(data, function(key, value) {
                $('#city').append('<option value="' + value.id + '">' + value.name +
                    '</option>')
            })
        }
    });
})
function myJsFunc() {
    window.location.href = 'javascript:void(0)';
}
</script>

@endsection
