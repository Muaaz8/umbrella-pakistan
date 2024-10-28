@extends('layouts.frontend')
@section('css')
    <link rel="stylesheet" href="{{ asset('asset_frontend/css/register.css') }}">
    <link rel="stylesheet" href="{{ asset('asset_frontend/css/doc_register.css') }}">
@endsection
@section('content')
    <div class="form_holder">
        <h2 class="fs-title" style="color:#fff;">steps jquery form with Icons</h2>
        <form id="msform" name="msform" method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
            @csrf
            <ul id="progressbar">
                <li class="active">1. Account Details</li>
                <li>2. Personal Information</li>
                <li>3. Professional Information</li>
                <li>4. Terms And Conditions</li>
            </ul>
            <input type="hidden" name="user_type" value="doctor" id="role">
            <input type="hidden" name="url_type" value="{{ isset($_GET['url_type']) ? $_GET['url_type'] : '' }}" />
            <fieldset>
                <h2 class="fs-title">Register as a Doctor</h2>
                <h3 class="fs-subtitle">Fill in the following information</h3>
                <div class="form-group row">
                    <div class="col-md-12 row">
                        <label class="col-md-3 p-2">Email <span style="color:red">*</span></label>
                        <input id="email" type="email"
                            class="form-control @error('email') is-invalid @enderror col-md-9" name="email"
                            value="{{ old('email') }}" required autocomplete="email" placeholder="Enter Email Address">
                        <div class="form-group row error offset-md-3 col-md-9 text-center font-weight-bold">
                            <span id="email_err" role="alert" class="invalid-feedback mb-2"><strong>Invalid
                                    Email Address</strong></span>
                            <span id="email_exist_err" role="alert" class="invalid-feedback mb-2"><strong>
                                    Email Address Already Registered</strong></span>
                        </div>

                        @error('email')
                            <span class="invalid-feedback font-weight-bold" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12 row">
                        <label class="col-md-3 p-2">Username <span style="color:red">*</span></label>
                        <input id="username" type="text"
                            class="form-control col-md-9 @error('username') is-invalid @enderror" name="username"
                            value="{{ old('username') }}" required autocomplete="username" placeholder="Enter Username">

                        <div class="form-group row error offset-md-3 col-md-9 text-center font-weight-bold">
                            <span id="username_err" role="alert" class="invalid-feedback mb-2"><strong>
                                    Username must contain minimum eight characters, at least one letter and one
                                    number</strong></span>
                            <span id="username_exist_err" role="alert" class="invalid-feedback mb-2"><strong>
                                    Username Already Registered</strong></span>
                        </div>
                        @error('username')
                            <span class="invalid-feedback font-weight-bold" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <span class="mb-2 offset-md-3" style="color: #aaabac;">
                            (e.g. johnathan78)</span>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12 row">
                        <label class="col-md-3 p-2">Password <span style="color:red">*</span></label>
                        <input type="password" id="password"
                            class="form-control show-password col-md-9 @error('password') is-invalid @enderror"
                            name="password" required autocomplete="new-password" placeholder="Enter Password">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12 row">

                        <label class="col-md-3 pl-2">Re-Enter Password <span style="color:red">*</span></label>
                        <input id="password-confirm" type="password" class="form-control col-md-9 show-password"
                            name="password_confirmation" required autocomplete="new-password"
                            placeholder="Re-enter Password">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12 row">
                        <label class="col-md-3 p-2"></label>
                        <div class="col-md-9 row text-left">
                            <input type="checkbox" id="check-pass" class="toggle-password" />
                            <label for="check-pass" toggle="#password" class="show-psd " style="font-weight:500">Show
                                Password</label>
                        </div>
                    </div>
                </div>
                <div class="mb-1">
                    <div class="form-group row error offset-md-2 col-md-9 text-center font-weight-bold">
                        <span id="password_err" role="alert" class="invalid-feedback"><strong>
                                Password Mismatch</strong></span>
                    </div>
                    <div class="form-group row error offset-md-2 col-md-9 text-center font-weight-bold">
                        <span id="password_validate_err" role="alert" class="invalid-feedback"><strong>
                                Password must contain minimum eight characters, at least one letter, one number and
                                one special character</strong></span>
                    </div>
                </div>
                @error('password')
                    <span class="invalid-feedback font-weight-bold" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror

                <div class="form-group row font-weight-bold">
                    <span id="step_1_err" role="alert" class="invalid-feedback"><strong>Please fill the required fields
                            to
                            proceed</strong></span>
                </div>
                <input type="button" id="step_1_next" name="next" class="next action-button button_ui"
                    value="Next" />
            </fieldset>
            <fieldset>
                <h2 class="fs-title">Personal Details</h2>
                <h3 class="fs-subtitle">Fill in the following details</h3>
                <div class="form-group row">
                    <div class="col-md-12 row">
                        <label class="col-md-3 p-2">First Name <span style="color:red">*</span></label>
                        <input id="name" type="text"
                            class="form-control col-md-9 @error('name') is-invalid @enderror" name="name"
                            maxlength="20" value="{{ old('name') }}" required autocomplete="name" autofocus>
                        <div class="form-group row error offset-md-2 col-md-10 text-center font-weight-bold">
                            <span id="name_err" role="alert" class="invalid-feedback"><strong>
                                    First name should not contain special characters or number</strong></span>
                        </div>
                        @error('name')
                            <span class="invalid-feedback font-weight-bold" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12 row">
                        <label class="col-md-3 p-2">Last Name <span style="color:red">*</span></label>
                        <input id="last_name" maxlength="20" type="text"
                            class="form-control col-md-9 @error('last_name') is-invalid @enderror" name="last_name"
                            value="{{ old('last_name') }}" required autocomplete="last_name" autofocus>
                        <div class="form-group row error offset-md-2 col-md-10 text-center font-weight-bold">
                            <span id="last_name_err" role="alert" class="invalid-feedback"><strong>
                                    Last name should not contain special characters or number</strong></span>
                        </div>
                        @error('last_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-12 row">
                        <label class="col-md-3 p-2">Gender <span style="color:red">*</span></label>
                        <select id="gender" rows="2" name="gender" required
                            class="form-control no-resize col-md-9 @error('office_address') is-invalid @enderror"
                            autocomplete="gender" value="{{ old('gender') }}">
                            <option value="" selected disabled>Select Gender</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12 row">
                        <label class="col-md-3 p-2">Date of Birth <span style="color:red">*</span></label>
                        <input id="datetimepicker" type="text"
                            class="form-control age col-md-9 @error('date_of_birth') datepicker is-invalid @enderror"
                            name="date_of_birth" value="{{ old('date_of_birth') }}" required
                            autocomplete="date_of_birth" autofocus readonly>
                        <div class="form-group row error offset-md-2 col-md-9 text-center font-weight-bold">
                            <span id="under_age" role="alert" class="invalid-feedback"><strong>
                                    Age should be greater than 18</strong></span>
                            <span id="dob_err" role="alert" class="invalid-feedback"><strong>
                                    Date of birth cannot be a future date</strong></span>
                        </div>
                        @error('date_of_birth')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12 row">
                        <label class="col-md-3 p-2">Phone Number <span style="color:red">*</span></label>
                        <input id="phone_number" pattern="\d*" maxlength="11"
                            class="form-control col-md-9 @error('phone_number') is-invalid @enderror" name="phone_number"
                            type="number" value="{{ old('phone_number') }}" required autocomplete="phone_number">
                        <div class="form-group row error offset-md-2 col-md-10 text-center font-weight-bold">
                            <span id="phone_err" role="alert" class="invalid-feedback"><strong>
                                    Phone number cannot be greater than 15 digits or negative number</strong></span>
                        </div>
                        @error('phone_number')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <input name="country" id="country_id" hidden value="233">

                <div class="form-group row">
                    <div class="col-md-12 row">
                        <label class="col-md-3 p-2">Zip Code <span style="color:red">*</span></label>
                        <input id="zip_code" name="zip_code" type="number" maxlength="10"
                            oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                            class="form-control no-resize col-md-9 @error('zip_code')
is-invalid
@enderror"
                            autocomplete="zip_code" value="{{ old('zip_code') }}">
                        <div class="form-group row error offset-md-2 col-md-10 text-center font-weight-bold">
                            <span id="zip_err" role="alert" class="invalid-feedback"><strong>
                                    Zip code cannot be greater than 10 digits or negative number</strong></span>
                            <span id="valid_zip" role="alert" class="invalid-feedback"><strong>
                                    Please Enter A valid ZipCode</strong></span>
                        </div>
                        @error('zip_code')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12 row">
                        <label class="col-md-3 p-2">State <span style="color:red">*</span></label>
                        <select name="state" id="state"
                            class="form-control @error('state') is-invalid @enderror col-md-9"
                            value="{{ old('state') }}" autocomplete="state" required>
                            <option value="" selected disabled>Select State</option>
                            @foreach ($states as $state)
                                <option value="{{ $state->id }}" {{ old('state') == $state->id ? 'selected' : '' }}>
                                    {{ $state->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12 row">
                        <label class="col-md-3 p-2">City <span style="color:red">*</span></label>
                        <select name="city" id="city"
                            class="form-control @error('city') is-invalid @enderror col-md-9" value="{{ old('city') }}"
                            autocomplete="city" required>
                            <option value="" selected disabled>Select City</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12 row">
                        <label class="col-md-3 p-2">Street <span style="color:red">*</span></label>
                        <input id="street" type="text" name="street"
                            class="form-control no-resize col-md-9 @error('street') is-invalid @enderror"
                            autocomplete="street" value="{{ old('street') }}">
                        <div class="form-group row error offset-md-2 col-md-10 text-center font-weight-bold">
                            <span id="address_err" role="alert" class="invalid-feedback"><strong>
                                    Street address cannot be greater than 50 letters</strong></span>
                        </div>
                        @error('street')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12 row">
                        <label class="col-md-3 p-2">Appartment #</label>
                        <input type="number" id="appartment" name="appartment"
                            class="form-control no-resize col-md-9 @error('appartment') is-invalid @enderror"
                            autocomplete="appartment" value="{{ old('appartment') }}">
                        @error('appartment')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row font-weight-bold">
                    <span id="step_2_err" role="alert" class="invalid-feedback"><strong>Please fill the required fields
                            to
                            proceed</strong></span>
                </div>

                <input id="step_2_prev" type="button" name="previous" class="previous action-button button_ui"
                    value="Previous" />
                <input id="step_2_next" type="button" class="next action-button button_ui" value="Next" />
            </fieldset>
            <fieldset>
                <div class="form-group row">
                    <div class="col-md-12 row">
                        <label class="col-md-3 p-2">NPI <span style="color:red">*</span></label>
                        <input id="nip_number" required maxlength="10" type="number"
                            oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                            class="form-control col-md-9 @error('nip_number')
is-invalid
@enderror" name="nip_number"
                            value="{{ old('nip_number') }}" autocomplete="nip_number">
                        <div class="form-group row error offset-md-2 col-md-10 text-center font-weight-bold">
                            <span id="npi_err" role="alert" class="invalid-feedback"><strong class="npi_strong">
                                    Invalid National Provider Identification Number (It must be 10 digits)</strong></span>
                        </div>
                        @error('nip_number')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12 row">
                        <label class="col-md-3 p-2">UPIN <span style="color:red">*</span></label>
                        <input id="upin" type="number" required maxlength="6"
                            oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                            class="form-control col-md-9 @error('upin')
is-invalid
@enderror" name="upin"
                            value="{{ old('upin') }}" autocomplete="upin">
                        <div class="form-group row error offset-md-2 col-md-10 text-center font-weight-bold">
                            <span id="upin_err" role="alert" class="invalid-feedback"><strong>
                                    Invalid UPIN (It must be 6 Digits)</strong></span>
                        </div>
                        @error('upin')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12 row">
                        <label class="col-md-3 p-2">Specialization <span style="color:red">*</span></label>
                        <select name="specialization" required id="specialization" class="form-control col-md-9  "
                            value="{{ old('specialization') }}">
                            <option value="" hidden selected disabled>Choose Specialization</option>
                            @foreach ($specs as $spec)
                                <option value="{{ $spec->id }}"
                                    {{ old('specialization') == $spec->id ? 'selected' : '' }}>
                                    {{ $spec->name }}</option>
                            @endforeach
                        </select>
                        @error('specialization')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12 row">
                        <label class="col-md-3 p-2">Licensed States <span style="color:red">*</span></label>
                        <select name="licensed_states[]" required id="licensed_states"
                            class="statesSelect form-control col-md-9 doc-register border customStateSelect w-100"
                            multiple="multiple">
                        </select>
                        @error('licensed_states')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12 row mt-3">
                        <label class="col-md-3 p-2">Driver License/ID Card <span style="color:red">*</span>
                            <br><span class="document">(Front Side)</span>
                        </label>
                        <input id="id_card_front" type="file" class="form-control col-md-9 " name="id_card_front"
                            accept="image/*">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12 row ">
                        <label class="col-md-3 p-2">Driver License/ID Card <span style="color:red">*</span>
                            <br><span class="document">(Back Side)</span>
                        </label>
                        <input id="id_card_back" type="file" class="form-control col-md-9 " name="id_card_back"
                            accept="image/*">
                    </div>
                </div>

                <div class="form-group row font-weight-bold">
                    <span id="step_3_err" role="alert" class="invalid-feedback"><strong>Please fill the required fields
                            to
                            proceed</strong></span>
                </div>
                <input type="button" name="previous" class="previous action-button button_ui" value="Previous" />
                <input id="step_3_next" type="button" name="next" class="next action-button button_ui"
                    value="Next" />
            </fieldset>
            <fieldset>
                <div class="terms">
                    <div class="term_of_use">
                        {!! $term->content !!}
                    </div>
                </div>
                <div class="form-group row mt-5">
                    <div class="col-md-12 row">
                        <label class="col-md-3 p-2">Add E-signature <span style="color:red">*</span></label>
                        <div class="col-md-9 canvasborder">
                            <canvas id="sig-canvas" class="canvas" width="550" height="160" name="signature"
                                required>
                                Incompatible browser.
                            </canvas>
                        </div>

                        <a class="btn col-md-1 redo-icon" id="sig-clearBtn">
                            <i class="fa fa-undo arrow"></i></a>
                        <input type="hidden" id="sig-input" name="signature" />
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12 row">
                        <label class="col-md-3 p-2"> </label>
                        <div class="col-md-12 p-4 row text-left">
                            <input type="checkbox" id="terms" class="m-1 " name="terms_and_cond" value="0"
                                required>
                            <label for="terms">
                                I agree with all terms and conditions
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group row font-weight-bold">
                    <span id="step_4_err" role="alert" class="invalid-feedback"><strong>Please fill the required fields
                            to
                            proceed</strong></span>
                </div>
                <input type="button" name="previous" class="previous action-button button_ui" value="Previous" />
                <!-- <button style=" background: #27ae606e; border: 1px solid #27ae606e; " type="button"
                            class="submit action-button noSubmitBtn button_ui">Register</button> -->
                <button id="submit" type="submit"
                    class="submit action-button submitBtn register_btn">Register</button>

            </fieldset>

        </form>
    </div>
@endsection
@section('script')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script>
        <?php header('Access-Control-Allow-Origin: *'); ?>

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        const scrollingElement = (document.scrollingElement || document.body);

        const scrollSmoothToTop = () => {
            scrollingElement.scrollTop = 0;
        }
        $('#datetimepicker').on('change', function() {
            $('#under_age').hide();

            datetimepicker = $("#datetimepicker").val();
            birthyear = datetimepicker.split("/");
            splityear = birthyear[2]
            current_date = new Date();
            year = current_date.getFullYear();
            age = year - splityear;
            if (age < 18) {
                console.log(" under 18")
                $("#step_2_next").prop("disabled", true);
                $('#under_age').show();
                $(".age").css("border", "2px red solid");
                // $("#under_age").css("font-weight", "500");

                // $('#under_age').css({
                //     "font-weight": "bold"
                // });

            } else {
                console.log("not under 18")
                $('#under_age').hide();
                $("#step_2_next").prop("disabled", false);
                $(".age").css("border", 'none');
                $(".age").css("border", '1px solid #ced4da');
            }
        });

        $("#card-name").hide();
        $("#lisence-name").hide();

        $('input:radio[name="documents"]').on('change', function() {

            console.log('in');
            if ($(this).is(":checked") && $(this).val() == "lisence") {
                $("#card-name").hide();
                $("#lisence-name").show();
            } else if ($(this).is(":checked") && $(this).val() == "card") {
                console.log('rep');
                $("#card-name").show();
                $("#lisence-name").hide();
            }
        }).change();
    </script>
    <!-- <script src="https://code.jquery.com/jquery-migrate-3.0.0.min.js"></script> -->
    <script src="{{ asset('asset_frontend/js/register.js') }}"></script>
@endsection
