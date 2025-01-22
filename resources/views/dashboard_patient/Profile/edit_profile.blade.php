@extends('layouts.dashboard_patient')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>CHCC - Edit Profile</title>
@endsection

@section('top_import_file')
@endsection



@section('bottom_import_file')
<script type="text/javascript">
    <?php header("Access-Control-Allow-Origin: *"); ?>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
<script src="{{ asset('assets/js/chatbot.js')}}"></script>
<script src="{{ asset('assets/js/pateint_form.js')}}"></script>
@endsection

@section('content')
        <div class="dashboard-content">
            <div class="container-fluid">
                <div class="row m-auto">
                <div class="nav-borders">
                    <h4>Edit Profile</h4>
                </div>
            </div>
                <hr class="mt-0 mb-4">
                <div class="row m-auto">
                    <div class="col-xl-4">
                        <div class="card mb-4 mb-xl-0">
                            <div class="card-header d-flex justify-content-between">Profile Picture
                                 <i style="cursor:pointer;" class="fa-solid fa-camera fs-4 cursor-pointer" data-bs-toggle="modal" data-bs-target="#editPicModal"></i>
                                </div>
                            <div class="card-body text-center d-flex flex-column m-auto">
                                       <img id="img" class="img-account-profile rounded-circle mb-2 m-auto center" src="{{ url($patient_data->user_image); }}" alt="" style="width:130px; height:130px;object-fit: cover; " />
                                        <!-- <input id="file" name='image' type="file"  class="btn btn-primary"
                                            accept=".jpg,.jpeg,.png" capture onchange="validateFileType();"> -->

                                            <!-- <div class="upload-btn-wrapper">

                                                <input id="file" name='image' type="file"  class="btn btn-primary"
                                            accept=".jpg,.jpeg,.png" capture onchange="validateFileType();" />
                                            </div> -->
                                        </div>
                        </div>
                        <div class="card mt-2 mb-4 mb-xl-0">
                            <div class="card-header d-flex justify-content-between">Phone Number
                                 <i class="fa-solid fa-pen-to-square fs-4" data-bs-toggle="modal" data-bs-target="#editNumberleModal"></i>
                                </div>
                            <div class="card-body">
                                <div class="">
                                    <h5>{{ $patient_data->phone_number }}</h5>
                                    <!-- <input class="form-control" id="inputLocation" name="phoneNumber" type="phoneNumber" placeholder="Enter your Number" value="{{ $patient_data->phone_number }}"> -->
                                </div>
                             </div>
                        </div>
                    </div>
                    <div class="col-xl-8">
                        <div class="card mb-4">
                            <div class="card-header d-flex"><i class="fa-solid fa-circle-left fs-4 me-2" onclick="history.back()"></i>Account Details</div>
                            <div class="card-body">
                            <form method="post" enctype="multipart/form-data" action="{{ route('updatePatient') }}">
                                @csrf
                                @method('POST')
                                <input type='hidden' class="form-control" id="user_id" type="text" name="user_id" value="{{ $patient_data->id }}">

                                    <div class="row gx-3 mb-3">
                                        <div class="col-md-6">
                                            <label class="small mb-1" for="inputFirstName">First Name</label>
                                            <input class="form-control" id="inputFirstName" type="text" name="fname" placeholder="Enter your first name"  value="{{ $patient_data->name }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="small mb-1" for="inputLastName">Last Name</label>
                                            <input class="form-control" id="inputLastName" type="text" name="lname" placeholder="Enter your last name"  value="{{ $patient_data->last_name }}">
                                        </div>
                                    </div>
                                    <div class="row gx-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputEmailAddress">Email</label>
                                        <input class="form-control" id="inputEmailAddress"  name="email" type="email" placeholder="Enter your Emial Address" value="{{ $patient_data->email }}" readonly>
                                      </div>
                                        <div class="col-md-6">
                                            <label class="small mb-1" for="inputOrgName">Date Of Birth</label>
                                            <input class="form-control" id="inputOrgName" type="date" name="dob" placeholder="Enter your organization name"  value="{{ $patient_data->date_of_birth }}" readonly>
                                        </div>
                                        <!-- <div class="col-md-6">
                                            <label class="small mb-1" for="inputLocation">Phone Number</label>
                                            <input class="form-control" id="inputLocation" name="phoneNumber" type="phoneNumber" placeholder="Enter your Number" value="{{ $patient_data->phone_number }}">
                                        </div> -->
                                    </div>

                                    <div class="row gx-3 mb-3">
                                        <div class="col-md-6">
                                            <label class="small mb-1" for="inputEmailAddress">Address</label>
                                            <input class="form-control" id="inputEmailAddress"  type="text" name="address" placeholder="Enter your Address" value="{{ $patient_data->office_address }}">
                                        </div>
                                        <div class="col-md-6">
                                                <label class="small mb-1" for="inputPhone">Zip Code*</label>
                                                <small class="text-danger zipcode_error"></small>
                                                <input class="form-control zip_code" id="inputPhone" type="tel" name="zip_code" placeholder="Enter your Zip Code" value="{{ $patient_data->zip_code }}">
                                            </div>
                                    </div>

                                    {{--<div class="row gx-3 mb-3">
                                        <div class="col-md-6">
                                            <label class="small mb-1" for="inputBirthday">State</label>
                                            <small class="text-danger state_error"></small>
                                            <select class="form-select state" name="state" readonly aria-label="Default select example">
                                                <option selected value="{{ $patient_data->state['id'] }}">{{ $patient_data->state['name'] }}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="small mb-1" for="inputBirthday">City</label>
                                            <small class="text-danger city_error"></small>
                                            <select class="form-select city" name="city" aria-label="Default select example">
                                                <option selected value="{{ $patient_data->city['id'] }}">{{ $patient_data->city['name'] }}</option>                                            </select>
                                        </div>
                                    </div>--}}
                                    <div class="row gx-3 mb-3">
                                        <div class="col-md-12">
                                            <label class="small mb-1" for="inputEmailAddress">Reason for changing</label>
                                            <input class="form-control" id="inputEmailAddress" type="text" name="reason" placeholder="Enter your Reason" value="" required>
                                        </div>
                                    </div>

                                    <!-- <div class="row gx-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputEmailAddress">Email</label>
                                        <input class="form-control" id="inputEmailAddress"  name="bio" type="text" placeholder="Enter your Bio" value="{{ $patient_data->bio }}">
                                      </div>
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputEmailAddress">Bio</label>
                                        <input class="form-control" id="inputEmailAddress"  name="bio" type="text" placeholder="Enter your Bio" value="{{ $patient_data->bio }}">
                                      </div>
                                    </div> -->
                                    <!-- <div class="mb-3">
                                        <div class="col-md-4">
                                            <label for="certificatation">Add Certificate</label>
                                            <input id="certificate" name='certificate' type="file" />
                                        </div>
                                    </div> -->

                                    <!-- Save changes button-->
                                    @if ($update == 0)
                                        <div class="text-center mb-2">
                                            <button class="btn btn-primary" type="submit">Save changes</button>
                                        </div>
                                    @else
                                        <div class="text-center mb-2">
                                            <button class="btn btn-primary" type="submit" disabled>You have already requested for profile updation</button>
                                        </div>
                                    @endif
                            </form>
                            @if ($errors->any())
                            <div class="alert alert-danger my-3">
                                <ul class="list-unstyled">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            @if (Session::has('success'))
                                <div class="alert alert-success">
                                    <ul>
                                        <li>{!! \Session::get('success') !!}</li>
                                    </ul>
                                </div>
                            @elseif (Session::has('error'))
                            <div class="alert alert-danger">
                                <ul>
                                    <li>{!! \Session::get('error') !!}</li>
                                </ul>
                            </div>
                            @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
     <!-- ========Edit Modal For Profile Pic Starts========= -->

<div class="modal fade" id="editPicModal" tabindex="-1" aria-labelledby="editPicModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editPicModalLabel">Edit Picture</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="post" enctype="multipart/form-data" action="{{ route('updatePicture') }}">
        @csrf
        @method('POST')
        <div class="modal-body ">
            <div class="text-center p-5">
            <img id="img" class="img-account-profile rounded-circle mb-2 m-auto center" src="{{ url($patient_data->user_image); }}" alt="" style="width:130px; height:130px;object-fit: cover; " />
            <div class="input-group mb-3 d-flex">
            <input type="file" id="file" type="file" accept=".jpg,.jpeg,.png" capture onchange="validateFileType();" name="filename" class="form-control">
            <button type="submit" class="btn btn-primary ms-2">Upload</button>
            </div>
            </div>
        </div>
      </form>
    </div>
  </div>
</div>
      <!-- ========Edit Modal For Profile Pic Ends========= -->

      <!-- ========Edit Modal For Phone Number starts========= -->

<div class="modal fade" id="editNumberleModal" tabindex="-1" aria-labelledby="editNumberleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editNumberleModalLabel">Edit Number</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="post" enctype="multipart/form-data" action="{{ route('updateNumber') }}">
        @csrf
        @method('POST')
        <div class="modal-body">
            <div class="d-flex row p-3">
                <label>Phone Number e.g:((407) 693-8484): *</label>
                <input class="form-control" id="inputLocation" maxlength="10" name="phoneNumber" type="phoneNumber" placeholder="Enter your Number" value="{{ $patient_data->phone_number }}">
                <button type="submit" class="btn process-pay m-2 col-md-3 ms-auto">Update</button>

            </div>
        </div>
      </form>
    </div>
  </div>
</div>
      <!-- ========Edit Modal For Phone Number Ends========= -->




        </div>
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
