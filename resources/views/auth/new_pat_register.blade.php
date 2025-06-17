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
    box-shadow: rgba(0,0,0,.24) 0 3px 8px!important;
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
  } .form_check_size{
      font-size: 19px;
    }
    .eye__pass_{
        position: absolute;
    top: 11px;
    right: 10px;
    cursor: pointer;
    }
    </style>
@endsection


@section('page_title')
    <title>Patient As Register |  </title>
@endsection

@section('top_import_file')
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>

@endsection


@section('bottom_import_file')
    <script type="text/javascript">
        <?php header('Access-Control-Allow-Origin: *'); ?>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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
    <script>
    var input = document.querySelector("#rep_phone_number");
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
    <script src="{{ asset('assets/js/pateint_form.js?n=1') }}"></script>
    <script>
        function refresh_recaptcha() {
            grecaptcha.reset();
        }
        $(document).ready(function() {
            $('#timezone').val(moment.tz.guess())
        });

        $(".toggle-password").click(function() {

var input = $($(this).attr("toggle"));
if (input.attr("type") == "password") {
  input.attr("type", "text");
  $(".eye__pass_").addClass("fa-eye");
    $(".eye__pass_").removeClass("fa-eye-slash");
} else {
  input.attr("type", "password");
    $(".eye__pass_").addClass("fa-eye-slash");
    $(".eye__pass_").removeClass("fa-eye");
}
});
    </script>
@endsection

@section('content')
    <!-- ******* SIGNUP-PATIENT-FORM STATRS ******** -->

    <section class="login-patient-sec">
        <div class="container-fluid py-4">
        <main class="">
        <div class="col-md-6 m-auto">
            <div class="main_reg_box">
                <h3 class="regis_pat_heading">REGISTER AS A PATIENT</h3>
                <form id="pat_reg_form" method="POST" enctype="multipart/form-data" action="javascript:void(0)">
                    {{--                    <div id="rep_div">
                        <div class="d-flex justify-content-evenly my-2">
                            </div>
                            <h5 class="patient_det_head mb-2">I am Patient‘s Representative:</h5>
                            <div class="mb-2">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-1">
                                        <label for="fullname" class="form-label">Full Name: *</label>
                                        <input type="text" class="form-control"  name="rep_fullname" placeholder="Full Name" maxlength="30">
                                        <small id="rep_fullname_error" class="text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-1">
                                        <label for="firstname" class="form-label">Relation to Patient: *</label>
                                        <select class="form-select" aria-label="Default select example" name="rep_relation" id="rep_relation">
                                            <option selected value="">Choose Relation</option>
                                            <option value="Sibling">Sibling</option>
                                            <option value="Parent">Parent</option>
                                            <option value="GrandParent">GrandParent</option>
                                            <option value="Other">Other</option>
                                        </select>
                                        <small id="rep_relation_error" class="text-danger"></small>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>--}}
                    <input type="hidden" name="url_type" value="{{ isset($_GET['url_type']) ? $_GET['url_type'] : '' }}" />
                    <input type="hidden" name="user_type" value="patient">
                    <input type="hidden" id="timezone" name="timezone" value="">
                    <div id="pat_div">
                        <h5 class="patient_det_head mb-2">Patient Details:</h5>
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
                                        <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Last Name" maxlength="30">
                                        <small id="lname_error" class="text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-1">
                                        <label for="firstname" class="form-label">Gender: *</label>
                                        <select class="form-select" aria-label="Default select example" id="gender" name="gender">
                                            <option selected value="">Select Gender</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                            <option value="Other">Other</option>
                                        </select>
                                        <small id="gender_error" class="text-danger"></small>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-1">
                                        <label for="firstname" class="form-label">Date Of Birth: *</label>
                                        <input type="text" max="<?php echo date('Y-m-d'); ?>" maxlength='10' class="form-control"
                                        onkeyup="addHyphen(this)" name="date_of_birth" id="date_of_birth" placeholder="MM-DD-YYYY">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-1">
                                        <label for="firstname" class="form-label">Phone Number: *</label>
                                        <input type="tel" class="form-control" name="phone_number" id="phone_number"
                                            maxlength="10" />
                                        <small class="text-danger" id="phone_error"></small>

                                    </div>
                                </div>
                                <input type="hidden" name="country" id="country" value="233" />
                                {{--<div class="col-md-6">
                                    <div class="mb-1">
                                        <label for="firstname" class="form-label">Zip Code: *</label>
                                        <input type="text" class="form-control zip_code" name="zip_code" placeholder="Zip Code" id="zip_code"
                                        onkeydown="javascript: return event.keyCode === 8 || event.keyCode === 46 ? true : !isNaN(Number(event.key))">
                                        <small class="text-danger" id="zipcode_error"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-1">
                                        <label for="firstname" class="form-label">State: *</label>
                                        <select class="form-select state" aria-label="Default select example" name="state" id="state">
                                            <option selected value="">Select State</option>
                                        </select>
                                        <small class="text-danger state_error"></small>

                                    </div>
                                </div>--}}
                                <div class="col-md-6">
                                    <div class="mb-1">
                                        <label for="firstname" class="form-label">City: *</label>
                                        <input type="text" class="form-control" name="city" id="city" placeholder="City" />
                                        <small class="text-danger city_error"></small>

                                    </div>
                                </div>
                                {{--<div class="col-md-12">
                                    <div class="mb-1">
                                        <label for="Address" class="form-label">Address: *</label>
                                        <input type="text" class="form-control" id="address" name="address" placeholder="Address">
                                        <small class="text-danger" id="address_error"></small>
                                    </div>
                                </div>--}}
                            </div>
                        </div>
                        <h5 class="patient_det_head mb-2 mt-2">Account Information:</h5>
                        <div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-1">
                                        <label for="Email" class="form-label">Email: (Optional)</label>
                                        <input type="text" class="form-control" name="email" id="email" placeholder="email@example.com">
                                        <small id="email_error" class="text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-1">
                                        <label for="Password" class="form-label">Password: *</label>
                                        <input type="password" class="form-control abcd" name="password" placeholder="Password" id="password" autocomplete="off">
                                        <small id="password_error" class="text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-1">
                                        <label for="confirmPassword" class="form-label">Confirm Password: *</label>
                                        <div class="position-relative">
                                        <input type="password" class="form-control abcd" name="password_confirmation"
                                            id="password_confirmation" placeholder="Confirm Password">
                                            <i class="fa-solid fa-eye-slash eye__pass_ toggle-password" toggle=".abcd" onclick="showPassword()"></i>
                                        </div>
                                        <small id="confirm_password_error" class="text-danger"></small>

                                    </div>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <div class="form-check mb-1">
                                        <input class="form-check-input" name="terms_and_cond" type="checkbox" value="" id="terms_and_cond">
                                        <label class="form-check-label" for="flexCheckDefault">
                                            I agree with all <a target="_blank" href="#" data-bs-toggle="modal"
                                                data-bs-target="#staticBackdrop">terms and conditions</a>
                                        </label>
                                        <small id="terms_and_cond_error" class="text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <div class="d-flex align-items-center">
                                        <div id="google_recaptcha" class="g-recaptcha"></div>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-1 mt-md-4">
                                    <div class="d-flex align-items-center">

                                        <button type="button" class="btn"><i onclick="refresh_recaptcha()" class="ms-2 fa fa-rotate-right"
                                            aria-hidden="true"
                                            style="background-color: #08295a; color: #fff; padding: 10px; border-radius: 100%; cursor: pointer;"></i>										Recaptcha</button>
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
    </main>
        </div>
    </section>


    <!-- ============= Term Policy Modal starts ==================== -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
                        <div id="loader" style="height: auto;" class="d-flex align-items-center justify-content-center">
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
                                <img src="{{ asset('assets/images/GwStPmg.png') }}" width="100%">
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

    <div id="myModal" class="modal fade">
        <div class="modal-dialog modal-confirm">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h4>Ooops!</h4>
                    <p>Under 18 years old patient have to register as Patient's Representative</p>
                </div>
            </div>
        </div>
    </div>
    <!-- ******* SIGNUP-PATIENT-FORM ENDS ******** -->
    <script>
        function addHyphen(element) {
            let ele = document.getElementById(element.id);
            ele = ele.value.split('-').join(''); // Remove dash (-) if mistakenly entered.
            if (ele.length <= 5) {
                let finalVal = ele.match(/.{1,2}/g).join('-');
                document.getElementById(element.id).value = finalVal;
            }
        }
    </script>
@endsection
