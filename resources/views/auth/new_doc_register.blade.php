@extends('layouts.new_pakistan_layout')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css">
    <style>
        .iti{
            display: block;
            margin-bottom: 13px;
        }
    </style>
@endsection


@section('page_title')
    <title>Doctor As Register | Umbrella Health Care Systems</title>
@endsection

@section('top_import_file')
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
<style>
    {{--  .form-control {
        border-radius: 0;
        box-shadow: none;
        border-color: #d2d6de
    }  --}}
.select2-selection__choice{
    margin-left:8px !important;
}
    .select2-hidden-accessible {
        border: 0 !important;
        clip: rect(0 0 0 0) !important;
        height: 1px !important;
        margin: -1px !important;
        overflow: hidden !important;
        padding: 0 !important;
        position: absolute !important;
        width: 1px !important
    }

    {{--  .form-control {
        display: block;
        width: 100%;
        height: 34px;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 4px;
        -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
        -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
        -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
        transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s
    }  --}}

    .select2-container--default .select2-selection--single,
    .select2-selection .select2-selection--single {
        border: 1px solid #d2d6de;
        border-radius: 0;
        padding: 6px 12px;
        height: 34px
    }

    .select2-container--default .select2-selection--single {
        background-color: #fff;
        border: 1px solid #aaa;
        border-radius: 4px
    }

    .select2-container .select2-selection--single {
        box-sizing: border-box;
        cursor: pointer;
        display: block;
        height: 28px;
        user-select: none;
        -webkit-user-select: none
    }

    .select2-container .select2-selection--single .select2-selection__rendered {
        padding-right: 10px
    }

    .select2-container .select2-selection--single .select2-selection__rendered {
        padding-left: 0;
        padding-right: 0;
        height: auto;
        margin-top: -3px
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #444;
        line-height: 28px
    }

    .select2-container--default .select2-selection--single,
    .select2-selection .select2-selection--single {
        border: 1px solid #d2d6de;
        border-radius: 0 !important;
        padding: 6px 12px;
        height: 40px !important
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 26px;
        position: absolute;
        top: 6px !important;
        right: 1px;
        width: 20px
    }
    .main_reg_box .form-label{
    margin-bottom: 0.2rem;
    color: #656363;
  }
  .regis_pat_heading{
    color: #08295a;
    text-align: center;
    font-weight: 600;
  }
  .patient_det_head{
    color: #08295a;
    font-weight: 600;
  }
  .main_reg_box{
    box-shadow: rgba(17, 17, 26, 0.05) 0px 1px 0px, rgba(17, 17, 26, 0.1) 0px 0px 8px;
    border-radius: 8px;
    padding: 10px 25px;
  }
  .reg__submit_btn{
    background-color: #08295a;
    color: #fff;
    font-weight: 600;
    border: none;
    border-radius: 3px;
    padding: 5px 25px;
    font-size: 18px;
    text-transform: uppercase;
    letter-spacing: 2px;
  }
  .main_reg_box .form-control, .main_reg_box .form-select{
    border-radius: 30px;
    background-color: #eceff1;
  }
  .form_check_size{
      font-size: 19px;
    }
    /* --doctor-side-register-- */
    .canvas_main_div{
      height: 120px;
    background-color: #e0dede;
    border-radius: 8px;
    width: 100%;
    }
    .signature_btns{
      display: flex;
      flex-direction: column;
      height: 100%;
      justify-content: center;
      gap: 10px;
    }
    .signature_btns_styles{
        background-color: #08295a;
    color: #fff;
    font-weight: 600;
    border: none;
    border-radius: 3px;
    padding: 6px 0px;
    font-size: 15px;
    }
    .eye__pass_{
        position: absolute;
    top: 11px;
    right: 0;
    cursor: pointer;
    }
    #sig-canvas {
    border: 2px dotted #ccc;
    border-radius: 15px;
    cursor: crosshair;
    width: 100%;
    margin: 30px 0 0;
}
</style>

@endsection


@section('bottom_import_file')
<script type="text/javascript">
    <?php header("Access-Control-Allow-Origin: *"); ?>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(".toggle-password").click(function() {

$(this).toggleClass("fa-eye fa-eye-slash");
var input = $($(this).attr("toggle"));
if (input.attr("type") == "password") {
  input.attr("type", "text");
} else {
  input.attr("type", "password");
}
});
</script>
<script>
var input = document.querySelector("#phone_number");
window.intlTelInput(input, {
    utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.3/build/js/utils.js",
    formatOnDisplay: true,
    separateDialCode: true,
    autoPlaceholder:"aggressive",
    onlyCountries: ["pk"],
    customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
        return ('316 800 1234');
    },
});
</script>
<!-- Option 1: Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.14/moment-timezone-with-data-2012-2022.min.js"></script>
<script src="{{ asset('assets/js/doctors_form.js?n=1')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/mobomo/sketch.js@master/lib/sketch.min.js" type="text/javascript"></script>
<script>
    function refresh_recaptcha()
    {
        grecaptcha.reset();
    }

    $(document).ready(function() {
        $('#doc_timezone').val(moment.tz.guess())
    });
</script>
@endsection

@section('content')


<!-- ******* DOCTOR-FORM STATRS ******** -->
<section class="login-patient-sec">
  <div class="container-fluid my-5">
  <div class="col-md-6 m-auto ">
            <div class="main_reg_box">
                <h3 class="regis_pat_heading">REGISTER AS A DOCTOR</h3>
                <form id="doc_reg_form" method="POST" enctype="multipart/form-data" action="javascript:void(0)">
                    <input type="hidden" name="url_type" value="{{ isset($_GET['url_type']) ? $_GET['url_type'] : '' }}" />
                    <input type="hidden" name="user_type" value="doctor">
                    <input type="hidden" id="doc_timezone" name="timezone" value="">
                    <div>
                        <h5 class="patient_det_head mb-2">Personal Information:</h5>
                        <div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-1">
                                        <label for="firstname" class="form-label">First Name: *</label>
                                        <input type="text" class="form-control" name="name" id="name" placeholder="First Name" maxlength="30">
                                        <small id="fname_error" class="text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-1">
                                        <label for="firstname" class="form-label">Last Name: *</label>
                                        <input type="text" class="form-control" name="last_name"  id="last_name" placeholder="Last Name" maxlength="30">
                                        <small id="lname_error" class="text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-1">
                                        <label for="firstname" class="form-label">Gender: *</label>
                                        <select class="form-select" id="gender" name="gender" aria-label="Default select example">
                                            <option  selected value="">Select Gender</option>
                                            <option  value="male">Male</option>
                                            <option value="female">Female</option>
                                            <option value="Other">Other</option>
                                        </select>
                                        <small id="gender_error" class="text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-1">
                                        <label for="firstname" class="form-label">Date Of Birth: *</label>
                                        <input type="text" class="form-control" max="<?php echo date("Y-m-d"); ?>"  maxlength='10' onkeyup="addHyphen(this)" name="date_of_birth" id="date_of_birth" placeholder="MM-DD-YYYY"/>
                                        <small class="text-danger" id="bod_error"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-1">
                                        <label for="firstname" class="form-label">Phone Number: *</label>
                                        <input type="text" class="form-control" name="phone_number" id="phone_number" maxlength="10" placeholder="(407) 693-8484">
                                        <small class="text-danger" id="phone_error"></small>
                                    </div>
                                </div>
                                <input type="hidden" name="country" id="country" value="233" />
                                {{--
                                    <div class="col-md-6">
                                        <div class="mb-1">
                                            <label for="firstname" class="form-label">Zip Code: *</label>
                                            <input type="text" class="form-control" name="zip_code" id="zip_code" placeholder="Zip Code">
                                            <small class="text-danger" id="zipcode_error"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-1">
                                            <label for="firstname" class="form-label">State: *</label>
                                            <select class="form-select" aria-label="Default select example" readonly name="state" id="state">
                                                <option selected value="">Select State</option>
                                            </select>
                                            <small class="text-danger" id="state_error"></small>
                                        </div>
                                    </div>
                                --}}
                                <div class="col-md-6">
                                    <div class="mb-1">
                                        <label for="firstname" class="form-label">City: *</label>
                                        <input type="text" class="form-control" id="city" name="city" placeholder="City">
                                        <small class="text-danger" id="city_error"></small>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-1">
                                        <label for="Address" class="form-label">Address: *</label>
                                        <input type="text" class="form-control" id="address" name="address" placeholder="Address">
                                        <small class="text-danger" id="address_error"></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h5 class="patient_det_head mb-2 mt-2">Professional Information:</h5>
                        <div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-1">
                                        <label for="npi" class="form-label">PMDC Registration Number: *</label>
                                        <input type="text" class="form-control"  name="npi" id="npi" placeholder="PMDC Registration Number" maxlength="10">
                                        <small class="text-danger" id="npi_error"></small>
                                    </div>
                                </div>
                                {{--<div class="col-md-6">
                                    <div class="mb-1">
                                        <label for="upin" class="form-label">UPIN: </label>
                                        <input type="text" class="form-control" name="upin" id="upin" placeholder="UPIN(Optional).">
                                        <small class="text-danger" id="upin_error"></small>
                                    </div>
                                </div>--}}
                                <div class="col-md-6">
                                    <div class="mb-1">
                                        <label for="firstname" class="form-label">Specialization: *</label>
                                        <select class="form-select" aria-label="Default select example" name="specializations" id="specializations">
                                            <option selected value="">Choose Specialization</option>
                                            @foreach ($specs as $spec)
                                                <option value="{{ $spec->id }}"{{ old('specialization') == $spec->id ? 'selected' : '' }}>{{ $spec->name }}</option>
                                            @endforeach
                                        </select>
                                        <small class="text-danger" id="specializations_error"></small>
                                    </div>
                                </div>
                                {{--<div class="col-md-6">
                                    <div class="mb-1">
                                        <label for="Licensed_States" class="form-label">Licensed States: *</label>
                                        <select class="form-select mb-3 form-control select2 select2-hidden-accessible" multiple="" id="licensed_states" name="licensed_states[]" data-placeholder="Select a State" style="width: 100%; margin:0; " tabindex="-1" aria-hidden="true">
                                            <!-- <option selected value="">Select Licenced State</option> -->
                                            @foreach ($states as $state)
                                                <option value="{{ $state->id }}" {{ old('state') == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                                            @endforeach
                                        </select>
                                        <small class="text-danger" id="licensed_state_error"></small>

                                    </div>
                                </div>--}}
                            </div>
                        </div>
                        <h5 class="patient_det_head mb-2 mt-2">Account Information:</h5>
                        <div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-1">
                                        <label for="Email" class="form-label">Email: *</label>
                                        <input type="text" class="form-control" name="email" id="email" placeholder="email@example.com">
                                        <small id="email_error" class="text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-1">
                                        <label for="Username" class="form-label">Username: *</label>
                                        <input type="text" class="form-control" name="username"  id="username" placeholder="UserName" maxlength="20" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');"/>
                                        <small id="username_error" class="text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-1">
                                        <label for="Password" class="form-label">Password: *</label>
                                        <input type="password" class="form-control abcd" name="password" placeholder="Password" id="password" maxlength="16"/>
                                        <small id="password_error" class="text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-1">
                                        <label for="confirmPassword" class="form-label">Confirm Password: *</label>
                                        <div class="position-relative">
                                        <input type="password" class="form-control abcd" name="password_confirmation" placeholder="Confirm Password" id="password_confirmation" maxlength="16"/>
                                        <i class="fa-solid fa-eye-slash eye__pass_ toggle-password" toggle=".abcd"></i>
                                    </div>
                                    <small id="confirm_password_error" class="text-danger"></small>

                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-1">
                                        <h5 class="patient_det_head mt-2">Add E-Signature:</h5>
                                        <div>
                                            <div class="row">
                                                <div class="col-md-9">
                                                    <!-- <div class="canvas_main_div">
                                                    </div> -->
                                                    <input type="hidden" name="signature" id="signature" value="">
                                                    <div id="reload">
                                                        <canvas id="sig-canvas" width="620" height="160"></canvas>
                                                    </div>
                                                    <small id="sign_error" class="text-danger"></small>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="signature_btns">
                                                        <button class="signature_btns_styles" onclick="javascript:void(0)" id="clearBtn">Retake Signature</button>
                                                        <button class="signature_btns_styles" onclick="javascript:void(0)" id="btnSave">Save Signature</button>
                                                    </div>
                                                </div>

                                            </div>


                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-check mb-1">
                                        <input class="form-check-input" type="checkbox" id="term" name="terms_and_cond" value="0">
                                        <label class="form-check-label" for="flexCheckDefault">
                                            I agree with all <a target="_blank"
                                                href="#" data-bs-toggle="modal" data-bs-target="#staticBackdrop">terms and
                                                conditions</a>
                                        </label>
                                        <small id="term_error" class="text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <div class="d-flex align-items-center">
                                        <div id="google_recaptcha" class="g-recaptcha"></div>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-1 mt-md-4">
                                    <div class="d-flex align-items-center">

                                        <button type="button" class="btn"><i onclick="refresh_recaptcha()" class="ms-2 fa fa-rotate-right"
                                            aria-hidden="true"
                                            style="background-color: #08295a; color: #fff; padding: 10px; border-radius: 100%; cursor: pointer;"></i>Recaptcha</button>
                                    </div>
                                </div>

                                    <div class="col-md-12 mt-3">
                                    <div class="text-center">
                                        <button type="submit" class="reg__submit_btn">Submit</button>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <div class="text-center">
                                        <p>Are you already register? <a href="{{ route('login') }}"><span class="login__Span_re">Login</span></a></p>
                                    </div>
                                </div>


                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
  </div>
</section>
<!-- ******* DOCTOR-FORM ENDS ******** -->
<!-- ============= Term Policy Modal starts ==================== -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"  aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">TERMS OF USE</h5>
        <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
      </div>
      <div class="modal-body text-start">
        <div>
        <p>Last Revised: {{ $date }},</p>
        {!! $term->content !!}
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- ============= Term Policy Modal Ends ==================== -->
<!-- ============= Success Modal starts ==================== -->
<div class="modal fade" id="success_modal" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="success_modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="success_modalLabel">Registration Successfull</h5>
                    <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                </div>
                <div class="modal-body text-start">
                    <div class="form-card" id="success_load">
                        <h2 class="purple-text text-center"><strong>Please wait...!!!</strong></h2>
                        <div id="loader" style="height: auto;">
                        <lottie-player src="https://assets8.lottiefiles.com/packages/lf20_zwauaf7y.json"
                            background="transparent" speed="1" style="width: 300px; height: 200px"
                            loop autoplay>
                        </lottie-player>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-7 text-center">
                                <h5 class="purple-text text-center">You will be redirected soon</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ============= Success Policy Modal Ends ==================== -->
    <!-- ============= Success Modal starts ==================== -->
    <div class="modal fade" id="registered_modal" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="registered_modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registered_modalLabel">Registration Successfull</h5>
                    <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                </div>
                <div class="modal-body text-start">
                    <div class="form-card" id="success_load">
                        <h2 class="purple-text text-center"><strong>SUCCESS</strong></h2>
                        <div class="row justify-content-center">
                            <div class="col-3">
                                <img src="{{ asset('assets/images/GwStPmg.png') }}" class="fit-image">
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-7 text-center">
                                <h5 class="purple-text text-center">You will be redirected soon</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ============= Success Policy Modal Ends ==================== -->

<script>
	function addHyphen (element) {
    	let ele = document.getElementById(element.id);
        ele = ele.value.split('-').join('');    // Remove dash (-) if mistakenly entered.
        if(ele.length <= 5)
        {
            let finalVal = ele.match(/.{1,2}/g).join('-');
            document.getElementById(element.id).value = finalVal;
        }
    }
</script>

@endsection
