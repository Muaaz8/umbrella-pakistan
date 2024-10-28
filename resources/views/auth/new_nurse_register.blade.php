@extends('layouts.new_web_register_layout')

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
    <title>Nurse As Register | Umbrella Health Care Systems</title>
@endsection

@section('top_import_file')
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
<style>
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
</script>
<script>
var input = document.querySelector("#phone_number");
window.intlTelInput(input, {
    utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.3/build/js/utils.js",
    formatOnDisplay: true,
    separateDialCode: true,
    autoPlaceholder:"aggressive",
    onlyCountries: ["us"],
    customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
        return ('(407) 693-8484');
    },
});
</script>
<!-- Option 1: Bootstrap Bundle with Popper -->
<script
  src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
  crossorigin="anonymous"
></script>
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
      <div class="row justify-content-center">
          <div class="col-11 col-sm-9 col-md-6 col-lg-6 col-xl-5 text-center p-0 mt-3 mb-2 patient-form-wrapper">
              <div class="card mt-3 mb-3">
                  <h2 id="heading">REGISTER AS A ASSISTANT DOCTOR</h2>
                  <p>Fill all form field to go to next step</p>

                  <form id="msform" method="POST" enctype="multipart/form-data" action="javascript:void(0)">
                    <input type="hidden" name="url_type" value="{{ isset($_GET['url_type']) ? $_GET['url_type'] : '' }}" />
                    <input type="hidden" name="user_type" value="doctor">
                    <input type="hidden" id="doc_timezone" name="timezone" value="">
                      <!-- progressbar -->
                      <ul id="progressbar">
                          <li class="active" id="account"><strong>Account</strong></li>
                          <li id="personal"><strong>Personal</strong></li>
                          <li id="profession"><strong>Professional</strong></li>
                          <li id="terms"><strong>Terms & Conditions</strong></li>
                          <li id="confirm"><strong>Finish</strong></li>
                      </ul>
                      <div class="progress">
                          <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                      <br>
                      <!-- fieldsets -->
                      <fieldset>
                        <div class="form-card">
                            <div class="row">
                                <div class="col-7">
                                    <h2 class="fs-title">Account Information:</h2>
                                </div>
                                <div class="col-5">
                                    <h2 class="steps">Step 1 - 5</h2>
                                </div>
                            </div>
                            <label class="fieldlabels">Email: *</label>
                            <small id="email_error" class="text-danger"></small>
                            <input type="email" name="email" id="email" placeholder="Email Id" />

                            <label class="fieldlabels">Username: *</label>
                            <small id="username_error" class="text-danger"></small>
                            <input type="text" name="username"  id="username" placeholder="UserName" maxlength="20" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');"/>

                            <label class="fieldlabels">Password: *</label>
                            <small id="password_error" class="text-danger"></small>
                            <input type="password" name="password" placeholder="Password" id="password" maxlength="16"/>

                            <label class="fieldlabels">Confirm Password: *</label>
                            <small id="confirm_password_error" class="text-danger"></small>
                            <input type="password" name="password_confirmation" placeholder="Confirm Password" id="password_confirmation" maxlength="16"/>

                            <div class="showpass-patient">
                            <input type="checkbox" onclick="showPassword()">Show Password
                            </div>
                        </div>
                        <input type="button" name="next" class="next action-button firstNext" value="Next"/>
                    </fieldset>
                    <fieldset>
                        <div class="form-card">
                            <div class="row">
                                  <div class="col-7">
                                      <h2 class="fs-title">Personal Information:</h2>
                                  </div>
                                  <div class="col-5">
                                      <h2 class="steps">Step 2 - 5</h2>
                                  </div>
                            </div>
                            <label class="fieldlabels">First Name: *</label>
                            <small id="fname_error" class="text-danger"></small>
                            <input type="text" name="name" id="name" placeholder="First Name" maxlength="30"/>

                            <label class="fieldlabels">Last Name: *</label>
                            <small id="lname_error" class="text-danger"></small>
                            <input type="text" name="last_name"  id="last_name" placeholder="Last Name" maxlength="30"/>

                            <label class="fieldlabels">Gender: *</label>
                            <small id="gender_error" class="text-danger"></small>
                            <select class="form-select mb-3" id="gender" name="gender" aria-label="Default select example">
                                <option  selected value="">Select Gender</option>
                                <option  value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="Other">Other</option>
                            </select>

                            <label class="fieldlabels">Date Of Birth.: *</label>
                            <small class="text-danger" id="bod_error"></small>
                            <input type="text" max="<?php echo date("Y-m-d"); ?>"  maxlength='10' onkeyup="addHyphen(this)" name="date_of_birth" id="date_of_birth" placeholder="MM-DD-YYYY"/>

                            <label class="fieldlabels">Phone Number e.g:((407) 693-8484): *</label>
                            <small class="text-danger" id="phone_error"></small>
                            <input type="text" name="phone_number" id="phone_number" maxlength="10"/>

                            <label class="fieldlabels">Zip Code.: *</label>
                            <small class="text-danger" id="zipcode_error"></small>
                            <input type="text" name="zip_code" id="zip_code" placeholder="Zip Code"/>
                            <input type="hidden" name="country" id="country" value="233" />

                            <label class="fieldlabels">State: *</label>
                            <small class="text-danger" id="state_error"></small>
                            <select class="form-select mb-3" aria-label="Default select example" readonly name="state" id="state">
                                <option selected value="">Select State</option>
                            </select>

                            <label class="fieldlabels">City: *</label>
                            <small class="text-danger" id="city_error"></small>
                            <select class="form-select mb-3" aria-label="Default select example" name="city" id="city">
                                <option selected value="">Select City</option>
                            </select>
                            <label class="fieldlabels">House/Apartment No: *</label>
                            <small class="text-danger" id="appartment_error"></small>
                            <input type="text" id="appartment" name="appartment" placeholder="Apartment." />

                            <label class="fieldlabels">Address: *</label>
                            <small class="text-danger" id="address_error"></small>
                            <input type="text" id="address" name="address" placeholder="Address." />
                        </div>
                        <input type="button" name="next" class="next action-button secondNext" value="Next"/>
                        <input type="button" name="previous" class="previous action-button-previous" value="Previous"/>
                    </fieldset>

                    <fieldset>
                        <div class="form-card">
                            <div class="row">
                                <div class="col-7">
                                    <h2 class="fs-title">Professional Information</h2>
                                </div>
                                <div class="col-5">
                                    <h2 class="steps">Step 3 - 5</h2>
                                </div>
                            </div>
                            <label class="fieldlabels">Reference Email: *</label>
                            <small class="text-danger" id="ref_email_error"></small>
                            <input type="email" name="ref_email" id="ref_email" placeholder="Reference Email"/>

                            <label class="fieldlabels">Reference NPI: *</label>
                            <small class="text-danger" id="npi_error"></small>
                            <input type="text" name="npi" id="npi" placeholder="NPI" maxlength="10"/>

                            <label class="fieldlabels">Reference UPIN: (Optional)</label>
                            <small class="text-danger" id="upin_error"></small>
                            <input type="text" name="upin" id="upin" placeholder="UPIN(Optional)." />

                            <label class="fieldlabels">Specialization: *</label>
                            <small class="text-danger" id="specializations_error"></small>
                            <select class="form-select mb-3" aria-label="Default select example" name="specializations" id="specializations">
                                <option selected value="">Choose Specialization</option>
                                @foreach ($specs as $spec)
                                    <option value="{{ $spec->id }}"{{ old('specialization') == $spec->id ? 'selected' : '' }}>{{ $spec->name }}</option>
                                @endforeach
                            </select>

                            <label class="fieldlabels">Licensed States: * (Search Like "New York")</label>
                            <small class="text-danger" id="licensed_state_error"></small>
                            {{--  <select class="form-select mb-3" aria-label="Default select example" name="licensed_state">
                                <option selected>Select States</option>
                                <option value="1">one</option>
                            </select>  --}}


                            <select class="form-select mb-3 form-control select2 select2-hidden-accessible" multiple="" id="licensed_states" name="licensed_states[]" data-placeholder="Select a State" style="width: 100%; margin:0; " tabindex="-1" aria-hidden="true">
                                @foreach ($states as $state)
                                    <option value="{{ $state->id }}" {{ old('state') == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                                @endforeach
                            </select>

                            {{-- <label class="fieldlabels">Driver License/ID Card <span>(Front Side) (Optional)</span></label>
                            <small class="text-danger" id="id_front_side_error"></small>
                            <input type="file" name="id_front_side" id="id_front_side" accept="image/*">

                            <label class="fieldlabels">Driver License/ID Cards <span>(Back Side) (Optional)</span></label>
                            <small class="text-danger" id="id_back_side_error"></small>
                            <input type="file" name="id_back_side"  id="id_back_side" accept="image/*">

                            <label class="fieldlabels">Profile Picture <span>(Optional)</span></label>
                            <small class="text-danger" id="profile_pic_error"></small>
                            <input type="file" name="profile_pic"  id="profile_pic" accept="image/*"> --}}
                        </div>
                        <input type="button" name="next" class="next action-button thirdNext" value="Next"/>
                        <input type="button" name="previous" class="previous action-button-previous" value="Previous"/>
                    </fieldset>

                    <fieldset>
                        <div class="form-card">
                            <div class="row">
                                <div class="col-7">
                                    <h2 class="fs-title">TERMS OF USE</h2>
                                </div>
                                <div class="col-5">
                                    <h2 class="steps">Step 4 - 5</h2>
                                </div>
                            </div>
                            <div class="terms-condition-wrapper">
                            <div class="terms-condition-div">
                                <h6>Last Revised: {{ $date }},</h6>
                                {!! $term->content !!}
                            </div>
                            <a class="text-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Read More</a>
                            </div>
                            <!-- <div class="terms-condition-div">
                                <p>Last Revised: {{ $date }},</p>
                                {!! $term->content !!}
                            </div> -->
                            <div class="patient-agreement-checkbox">
                                <input type="checkbox" id="term" name="terms_and_cond" value="0"> I agree with all terms and conditions
                            </div>
                            <div class="row mt-4">
                              <div class="col-md-12">
                                <h6>Add E-Signature</h6>
                                <input type="hidden" name="signature" id="signature">
                                    <div id="reload">
                                        <canvas id="sig-canvas" width="620" height="160"></canvas>
                                    </div>
                               </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12" id="error">
                                </div>
                            </div>
                            <div class="row">
                              <div class="col-md-12">
                                <button class="btn btn-default" type="button" onclick="javascript:void(0)" id="clearBtn">Retake Signature</button>
                                <button class="btn btn-default" type="button" onclick="javascript:void(0)" id="btnSave">Save Signature</button>
                              </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="d-flex align-items-center">
                                <div id="google_recaptcha" class="g-recaptcha"></div>
                                <button type="button" class="btn"><i onclick="refresh_recaptcha()" class="ms-2 fa fa-rotate-right"
                                    aria-hidden="true"
                                    style="background-color: #08295a; color: #fff; padding: 10px; border-radius: 100%; cursor: pointer;"></i>Recaptcha</button>
                            </div>
                        </div>
                        {{-- <input type="submit" name="next" class="next action-button forthNext" value="submit" disabled style="background:#a9b9d0;"/> --}}
                        <input type="button" name="previous" class="previous action-button-previous" value="Previous"/>
                    </fieldset>

                    <fieldset>
                        <div class="form-card" id="success_load">
                            <div class="row">
                                <div class="col-7">
                                    <h2 class="fs-title">Finish:</h2>
                                </div>
                                <div class="col-5">
                                    <h2 class="steps">Step 5 - 5</h2>
                                </div>
                            </div>
                            <br><br>
                            <h2 class="purple-text text-center"><strong>SUCCESS !</strong></h2>
                            <br>
                            <div class="row justify-content-center">
                                <div class="col-3">
                                    <img src="{{ asset('assets/images/GwStPmg.png') }}" class="fit-image">
                                </div>
                            </div>
                            <br><br>
                            <div class="row justify-content-center">
                                <div class="col-7 text-center">
                                    <h5 class="purple-text text-center">You Have Successfully Signed Up</h5>
                                </div>
                            </div>
                        </div>
                        <center>
                            <div id="loader" style="height: auto;">
                                <lottie-player
                                src="https://assets8.lottiefiles.com/packages/lf20_zwauaf7y.json"
                                background="transparent"
                                speed="1"
                                style="width: 500px; height: 300px"
                                loop
                                autoplay>
                                </lottie-player>
                            </div>
                        </center>

                    </fieldset>
                  </form>
              </div>
          </div>
          <!-- <div class="col-md-6">
            <div>
              <img src="{{ asset('assets/images/logo.png') }}" alt="" width="100%">
            </div>
          </div> -->
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
