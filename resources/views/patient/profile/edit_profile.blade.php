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

</style>
@endsection
@section('content')
<script>
setTimeout(function() {
    $('.messageDiv').fadeOut('fast');
}, 2000);
</script>
<section class="content profile-page">
    <div class="container-fluid form-container">
        <div class="block-header">
            @include('flash::message')
            <div class="container">
                <div class="card text-center shadow-lg edit-profile" style="border-radius:30px;">
                    <div class="card-body justify-content-center">
                        <form method="POST"  action="{{ route('edit_patient_profile') }}"
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
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                       <img id="img" class="profile_picture" style='background-image:url("{{$patient_data->user_image}}");' />
                                    </div>

                                    <div class="upload-btn-wrapper">
                                        <label for="file" class="custom-file-upload">
                                            <i class="fa fa-plus"></i>
                                        </label>
                                        <input id="file" name='image' type="file"  style="display:none;"
                                            accept=".jpg,.jpeg,.png" capture onchange="validateFileType();">
                                    </div>


                                </div>
                            </div>

                            <div class="col-sm-12">
                                @if ($patient_data->provider != '')
                                <div class="form-group row">
                                    <label for="name" class="col-sm-2 col-12 form-label"> First Name</label>
                                    <div class="col-sm-4 ">
                                        <input type="text" id="name" name="fname" maxlength="10" class="form-control pl-3 pr-3 stl"
                                            value="{{ $patient_data->name }}" required>
                                        <p id="fnamemess" style="color:red;">bb</p>
                                    </div>
                                    <label for="lname" class="col-sm-2 col-12 form-label">Last Name</label>
                                    <div class="col-sm-4">
                                        <input type="text" id="lname" name="lname" maxlength="10" class="form-control pl-3 pr-3 stl"
                                            value="{{ $patient_data->last_name }}" required>
                                        <p id="lnamemess" style="color:red;">bb</p>
                                    </div>
                                </div>

                                @else
                                <div class="form-group row ">
                                    <label for="fname" class="col-sm-2 col-form-label">First Name</label>
                                    <div class="col-sm-4">
                                        <input type="text" id="fname" name="fname" maxlength="10" class="form-control pl-3 pr-3 stl"
                                            style=" border:1px solid #cccccc; " value="{{ $patient_data->name }}"
                                            required>
                                        <p id="fnamemess" style="color:red;"></p>
                                    </div>
                                    <label for="lname" class="col-sm-2 col-form-label">Last Name</label>
                                    <div class="col-sm-4">
                                        <input type="text" id="lname" name="lname" maxlength="10" class="form-control pl-3 pr-3 stl"
                                            style="border:1px solid #cccccc;" value="{{ $patient_data->last_name }}"
                                            required>
                                        <p id="lnamemess" style="color:red;"></p>
                                    </div>
                                </div>


                                @endif


                                <div class="form-group row ">
                                    <label for="d1" class="col-sm-2 col-form-label">Date Of Birth</label>
                                    <div class="col-sm-4">
                                        <input type="date" id="d1" name="dob" max="{{ $min_age_date}}"
                                            class="form-control pl-3 pr-3 stl" style=" border:1px solid #cccccc; "
                                            value="{{ $patient_data->date_of_birth }}" required>


                                    </div>
                                    <label for="number" class="col-sm-2 col-form-label">Phone Number</label>
                                    <div class="col-sm-4">
                                        <input type="number" id="number" name="phoneNumber"
                                            class="form-control pl-3 pr-3 stl" style=" border:1px solid #cccccc; "
                                            placeholder="Enter Phone Number" value="{{ $patient_data->phone_number }}"
                                            required>
                                        <p id="numbermess" style="color:red;"></p>
                                    </div>
                                </div>



                                <div class="form-group row">
                                    <label for="address" class="col-sm-2 col-form-label">Address</label>
                                    <div class="col-sm-4">
                                        <input type="text" id="address" name="address"
                                            class="form-control pl-3 pr-3 stl" style=" border:1px solid #cccccc; "
                                            placeholder="Enter Address" value="{{ $patient_data->office_address }}"
                                            maxlength="60" required>
                                    </div>
                                    <label for="zipcode" class="col-sm-2 col-form-label">ZipCode</label>
                                    <div class="col-sm-4">
                                        <input type="number" id="zipcode" name="zip_code"
                                            class="form-control pl-3 pr-3 stl" style=" border:1px solid #cccccc; "
                                            placeholder="Enter Zipcode" value="{{ $patient_data->zip_code }}" disabled>
                                        <p id="zipcode" style="color:red;"></p>
                                    </div>
                                    <input name="country" value="233" type="hidden">
                                </div>


                                <div class="form-group row ">
                                    <label for="state" class=" col-sm-2 col-form-label">State</label>
                                    <div class="col-sm-4">
                                        <select name="state" id="state" class="form-control pl-3 pr-3 stl"
                                            style=" border:1px solid #cccccc; " disabled>

                                            @foreach ($patient_data->states as $st)
                                            @if ($st->id == $patient_data->state_id)
                                            <option value="{{ $st->id }}" selected="selected">
                                                {{ $st->name }}</option>
                                            @else
                                            <option value="{{ $st->id }}">{{ $st->name }}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <label for="city" class="col-sm-2 col-form-label">City</label>
                                    <div class="col-sm-4">
                                        <select name="city" id="city" class="form-control pl-3 pr-3 stl"
                                            style=" border:1px solid #cccccc;" disabled>

                                            @foreach ($patient_data->cities as $cit)
                                            @if ($cit->id == $patient_data->city_id)
                                            <option value="{{ $cit->id }}" selected="selected">
                                                {{ $cit->name }}</option>
                                            @else
                                            <option value="{{ $cit->id }}">{{ $cit->name }}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                                <div class="form-group row">
                                    <label for="bio" class="col-sm-2 col-form-label">Bio</label>
                                    <div class="col-sm-10">
                                        <textarea name="bio" id="bio" class="form-control pl-3 pr-3 pt-3 pb-3 stl"
                                            style=" height:200px; border:1px solid #cccccc; " rows="5" cols="5"
                                            placeholder="Enter Your Bio" maxlength="200">{{ $patient_data->bio }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-10 offset-sm-2">
                                        <a href="{{ url(auth()->user()->username) }}">
                                            <button type="button" class="btn callbtn" style="background-color:black !important">Back</button>
                                        </a>
                                        <button type="submit" name="btnSubmit" id="btnSubmit" class="btn callbtn" >Update</button>

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

function sub(obj) {
    var file = obj.value;
    var fileName = file.split("\\");
    document.getElementById("yourBtn").innerHTML = fileName[fileName.length - 1];
    document.myForm.submit();
    event.preventDefault();
}

<?php header('Access-Control-Allow-Origin: *'); ?>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function file_changed() {
    var selectedFile = document.getElementById('file').files[0];
    var img = document.getElementById('img')
    var reader = new FileReader();
    reader.onload = function() {
        img.src = this.result
    }
    reader.readAsDataURL(selectedFile);
}

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
