@extends('layouts.dashboard_doctor')



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
                  <h5>Edit Profile</h5>
              </div>
          </div>
              <hr class="mt-0 mb-4">
              <div class="row m-auto">
                  <div class="col-xl-4">
                    <form action="{{route('updateDocProfile')}}" enctype="multipart/form-data" method="post" >
                    @csrf
                      <!-- Profile picture card-->
                      <div class="card mb-4 mb-xl-0">
                          <div class="card-header d-flex justify-content-between">Profile Picture
                          <i class="fa-solid fa-pen-to-square fs-4 cursor-pointer" data-bs-toggle="modal" data-bs-target="#editPicModal"></i>
                          </div>
                          <div class="card-body text-center d-flex flex-column m-auto">
                              <!-- Profile picture image-->
                              <img type='image' name='image' class="img-account-profile rounded-circle mb-2 m-auto" src="{{ url($doctor_data->user_image); }}" alt="" style="width:130px; height:130px; ">
                              <!-- Profile picture help block-->
                              <!-- <div class="small font-italic text-muted mb-4">JPG or PNG no larger than 5 MB</div> -->
                              <!-- Profile picture upload button-->
                              <!-- <input  class="btn btn-primary" name='image' type="file"></input> -->
                              <!-- <div class="upload-btn-wrapper">
                                <input id="file" name='image' type="file"  class="btn btn-primary"
                                accept=".jpg,.jpeg,.png" capture onchange="validateFileType();" />
                              </div> -->
                          </div>

                      </div>
                  </div>
                  <div class="col-xl-8">
                      <!-- Account details card-->
                      <div class="card mb-4">
                          <div class="card-header">Account Details</div>
                          <div class="card-body">

                                  <input type='hidden' class="form-control" id="user_id" type="text" name="user_id" value="{{  $doctor_data->id }}">
                                  <!-- Form Row-->
                                  <div class="row gx-3 mb-3">
                                      <!-- Form Group (first name)-->
                                      <div class="col-md-6">
                                          <label class="small mb-1" for="inputFirstName">First name</label>
                                          <input class="form-control" id="inputFirstName" type="text" name="fname" placeholder="Enter your first name" value="{{  $doctor_data->name }}">
                                      </div>
                                      <!-- Form Group (last name)-->
                                      <div class="col-md-6">
                                          <label class="small mb-1" for="inputLastName">Last name</label>
                                          <input class="form-control" id="inputLastName" type="text" name="lname"  placeholder="Enter your last name" value="{{  $doctor_data->last_name }}">
                                      </div>
                                  </div>
                                  <!-- Form Row        -->
                                  <div class="row gx-3 mb-3">
                                      <!-- Form Group (Date Of Birth)-->
                                      <div class="col-md-6">
                                          <label class="small mb-1" for="inputOrgName">Date Of Birth</label>
                                          <input class="form-control" name="dob" id="inputOrgName" type="date" placeholder="Enter your Date Of Birth" value="{{  $doctor_data->date_of_birth }}" readonly>
                                      </div>
                                      <!-- Form Group (Phone Number)-->
                                      <div class="col-md-6">
                                          <label class="small mb-1" for="inputLocation">Phone Number e.g:((407) 693-8484):</label>
                                          <input class="form-control" id="phoneNumber" name="phoneNumber" maxlength="10" type="text" placeholder="Enter your Number" value="{{  $doctor_data->phone_number }}">
                                      </div>
                                  </div>

                                  <div class="row gx-3 mb-3">
                                      <!-- Form Group (Email)-->
                                      <div class="col-md-6">
                                          <label class="small mb-1" for="inputOrgName">Email</label>
                                          <input class="form-control" name="email" id="email" type="text" placeholder="Enter your Email" value="{{  $doctor_data->email }}" readonly>
                                      </div>
                                      <!-- Form Group (Address)-->
                                      <div class="col-md-6">
                                          <label class="small mb-1" for="inputLocation">Address</label>
                                          <input class="form-control" id="address" name="address" type="text" placeholder="Enter your Address" value="{{  $doctor_data->office_address }}">
                                      </div>
                                  </div>

                                  <div class="row gx-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputPhone">Zip Code*</label>
                                        <small class="text-danger zipcode_error"></small>
                                        <input class="form-control zip_code" id="inputPhone" type="tel" name="zip_code" placeholder="Enter your Zip Code" value="{{  $doctor_data->zip_code }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputPhone">BIO</label>
                                        <input class="form-control" id="bio" type="text"  name="bio"  placeholder="Enter your Bio" value="{{ $doctor_data->bio }}">

                                        <!-- <textarea  class="form-control" id="bio"  name="bio" >
                                          {{ $doctor_data->bio }}
                                        </textarea> -->
                                    </div>
                                    </div>

                                <div class="row gx-3 mb-3">
                                    <div class="col-md-12">
                                        <label class="small mb-1" for="inputEmailAddress">Reason for changing</label>
                                        <input class="form-control" id="inputEmailAddress" type="text" name="reason" placeholder="Enter your Reason" value="" required>
                                    </div>
                                </div>

                               <div class=" mb-3">
                                 <div class="">
                                   <label for="certificatation">Add Certificate</label>
                                   <input type="file" name="certificate" class="form-control" id="certificate">
                                 </div>
                               </div>

                                      <!-- Form Group (birthday)-->
                                      {{--  <div class="col-md-6">

                                          <label class="small mb-1" name="specialization" for="inputBirthday">Specialization</label>
                                          <select class="form-select" aria-label="Default select example">
                                            @foreach($specs as $docspec )
                                              <option selected> Select Specialization </option>
                                              <option value="{{ $docspec->id }}" >{{ $docspec->name }}</option>
                                              @endforeach
                                            </select>
                                      </div>  --}}
                                  </div>



                                  {{--  <div class="mb-3">
                                      <label class="small mb-1" for="inputUsername">Choose Profile</label>
                                      <div class="file-input">
                                          <input
                                            type="file"
                                            name="doctor_certificates"
                                            id="doctor_certificates"
                                            class="file-input__input"
                                          />
                                          <label class="file-input__label" for="file-input">
                                            <svg
                                              aria-hidden="true"
                                              focusable="false"
                                              data-prefix="fas"
                                              data-icon="upload"
                                              class="svg-inline--fa fa-upload fa-w-16"
                                              role="img"
                                              xmlns="http://www.w3.org/2000/svg"
                                              viewBox="0 0 512 512"
                                            >
                                              <path
                                                fill="currentColor"
                                                d="M296 384h-80c-13.3 0-24-10.7-24-24V192h-87.7c-17.8 0-26.7-21.5-14.1-34.1L242.3 5.7c7.5-7.5 19.8-7.5 27.3 0l152.2 152.2c12.6 12.6 3.7 34.1-14.1 34.1H320v168c0 13.3-10.7 24-24 24zm216-8v112c0 13.3-10.7 24-24 24H24c-13.3 0-24-10.7-24-24V376c0-13.3 10.7-24 24-24h136v8c0 30.9 25.1 56 56 56h80c30.9 0 56-25.1 56-56v-8h136c13.3 0 24 10.7 24 24zm-124 88c0-11-9-20-20-20s-20 9-20 20 9 20 20 20 20-9 20-20zm64 0c0-11-9-20-20-20s-20 9-20 20 9 20 20 20 20-9 20-20z"
                                              ></path>
                                            </svg>
                                            <span>Upload file</span></label
                                          >
                                        </div>
                                  </div>  --}}

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
                                    <div class="alert alert-danger">
                                        <ul>
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
            <img id="img" class="img-account-profile rounded-circle mb-2 m-auto center" src="{{ url($doctor_data->user_image); }}" alt="" style="width:130px; height:130px;object-fit: cover; " />
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
