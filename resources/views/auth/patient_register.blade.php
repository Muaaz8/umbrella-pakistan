@extends('layouts.frontend')
@section('css')
    <link rel="stylesheet" href="{{ asset('asset_frontend/css/register.css') }}">
@endsection
@section('content')
    <div class="form_holder">
        <h2 class="fs-title" style="color:#fff;">steps jquery form with Icons</h2>
        <form id="msform" method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
            @csrf
            <ul id="progressbar">
                <li class="active">1. Account Details</li>
                <li>2. Personal Information</li>
                <li>3. Medical Information</li>
                <li>4. Terms And Conditions</li>
            </ul>
            <input type="hidden" name="user_type" value="patient" id="role">
            <fieldset>
                <h2 class="fs-title">Register as a Patient</h2>
                <h3 class="fs-subtitle">Fill in the following information</h3>
                <div class="form-group row">
                    <input type="hidden" name="url_type" value="{{ isset($_GET['url_type']) ? $_GET['url_type'] : '' }}" />
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
                            <span id="username_err" role="alert" class="invalid-feedback"><strong>
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
                            <label for="check-pass" toggle="#password" class="show-psd" style="font-weight:500">Show
                                Password</label>
                        </div>
                    </div>
                </div>
                <div class="mb-2">
                    <div class="form-group row error col-md-12 text-align-center font-weight-bold">
                        <span id="password_err" role="alert" class="invalid-feedback"><strong>
                                Password Mismatch</strong></span>
                    </div>
                    <div class="form-group row error text-align-center font-weight-bold">
                        <span id="password_validate_err" role="alert" class="invalid-feedback"><strong>
                                Password must contain minimum eight characters, at least one letter, one number and
                                one special character</strong>
                        </span>
                    </div>
                </div>
                @error('password')
                    <span class="invalid-feedback font-weight-bold" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror

                <div class="form-group row font-weight-bold">
                    <span id="step_1_err" role="alert" class="invalid-feedback"><strong>Incomplete
                            Information</strong></span>
                </div>
                <input type="button" id="step_1_next" name="next" class="next action-button button_ui"
                    value="Next" />
            </fieldset>
            <fieldset>
                <h2 class="fs-title">Personal Details</h2>
                <div class="form-group row">
                    <div class="col-md-12 row my-2 mx-5">
                        <div class="form-check form-check-inline col-6" id="pat_radio">
                            <input class="form-check-input mt-1" checked type="radio" name="rep_radio" id="radio1" value="patient">
                            <label class="form-check-label ml-2" for="radio1">I am the Patient</label>
                        </div>
                        <div class="form-check form-check-inline col-5">
                            <input class="form-check-input mt-1" type="radio" name="rep_radio" id="radio2" value="representative">
                            <label class="form-check-label ml-2" for="radio2">I am Patient's Representative</label>
                        </div>
                    </div>
                </div>
                <div class="alert alert-info alert-dismissible fade show" id="pat-rep" role="alert">
                    <strong>To register for under 18 patient, you must add patient's representative!</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="rep-div" class="">
                    <div class="hr-with-heading">
                        <span class="hr-heading">
                            Patient's Representative's Details
                            <!--Padding is optional-->
                        </span>
                    </div>
                    <h3 class="fs-subtitle">Fill in the following details</h3>
                    <div class="form-group row">
                        <div class="col-md-12 row">
                            <label class="col-md-3 p-2">Full Name <span style="color:red">*</span></label>
                            <input id="full_name" type="text"
                                class="form-control col-md-9 @error('full_name') is-invalid @enderror" name="full_name"
                                maxlength="20" value="{{ old('full_name') }}" autocomplete="full_name">
                            <div class="form-group row error offset-md-2 col-md-10 text-center font-weight-bold">
                                <span id="full_name_err" role="alert" class="invalid-feedback">
                                    <strong>
                                        First name should not contain special characters or number</strong></span>
                            </div>
                            @error('full_name')
                                <span class="invalid-feedback font-weight-bold" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12 row">
                            <label class="col-md-3 p-2">Relation to Patient <span style="color:red">*</span></label>
                            <select id="relation" class="form-control col-md-9" name="relation"
                                value="{{ old('relation') }}" autocomplete="relation">
                                <option value="">Choose Relation</option>
                                <option value="sibling">Sibling</option>
                                <option value="parent">Parent</option>
                                <option value="grandParent">GrandParent</option>
                                <option value="other">Other</option>
                            </select>
                            <!-- <div class="form-group row"> -->

                            @error('relation')
                                <span class="invalid-feedback font-weight-bold" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>


                </div>
                <div class="hr-with-heading">
                    <span class="hr-heading">
                        Patient&sbquo;s Details
                    </span>
                </div>
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
                            <span class="invalid-feedback font-weight-bold" role="alert">
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
                            autocomplete="gender">{{ old('gender') }}
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
                        <input id="datetimepicker" type="text" onchange="get_age()"
                            class="form-control col-md-9 @error('date_of_birth') datepicker is-invalid @enderror"
                            name="date_of_birth" value="{{ old('date_of_birth') }}" required
                            autocomplete="date_of_birth" autofocus>
                        <div class="form-group row error col-md-12 text-align-center mb-2 font-weight-bold">
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
                        <div class="form-group row error col-md-12 text-align-center mb-2 font-weight-bold">
                            <span id="phone_err" role="alert" class="invalid-feedback"><strong>
                                    Phone number cannot be greater than 15 digits</strong></span>
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
                                    Zip code cannot be greater than 10 digits</strong></span>
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
                            class="form-control @error('city') is-invalid @enderror col-md-9"
                            value="{{ old('city') }}" autocomplete="city" required>
                            <option value="" selected disabled>Select City</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12 row">
                        <label class="col-md-3 p-2">Street <span style="color:red">*</span></label>
                        <input type="text" id="street" name="street"
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
                        <label class="col-md-3 p-2">Apartment # </label>
                        <input id="appartment" type="number" name="appartment"
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
                    <span id="step_2_err" role="alert" class="invalid-feedback"><strong>Incomplete
                            Information</strong></span>
                </div>

                <input id="step_2_prev" type="button" name="previous" class="previous action-button button_ui"
                    value="Previous" />
                <input id="step_2_next" onClick="scrollSmoothToTop()" type="button" name="next"
                    class="next action-button button_ui" value="Next" />
            </fieldset>
            <fieldset>
                <h2 class="fs-title">Medical Record</h2>
                <h3 class="fs-subtitle">If you have any document of your medical record, then you can upload here.</h3>
                <input style="padding: 2px;" id="record" type="file" class="form-control-file m-1" name="record"
                    value="{{ old('record') }}" accept=".pdf">
                <!-- <div class="drop-zone">
                                <div class="timer-bar">
                                    <div class="wrapper">
                                        <i class='success-icon ion-ios-checkmark-empty'></i>
                                        <p class='success-text'></p>
                                    </div>
                                </div>
                                <p class="timer-info">Drop your file here</p>
                            </div> -->
                <input type="button" name="previous" class="previous action-button button_ui" value="Previous" />
                <input id="step_4_next" type="button" onClick="scrollSmoothToTop()" name="next"
                    class="next action-button button_ui" value="Next" />
            </fieldset>
            <fieldset>
                <div class="terms">
                    <div class="term_of_use">
                        {!! $term->content !!}
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12 row">
                        <label class="col-md-3 p-2"> </label>
                        <div class="col-md-12 p-4 row text-left">
                            <input type="checkbox" id="terms" class="m-1 " name="terms_and_cond" value="0"
                                required>
                            <span class="col-md-9">
                                I agree with all terms and conditions
                            </span>
                        </div>
                    </div>
                </div>

                <input type="button" name="previous" class="previous action-button button_ui" value="Previous" />
                <input id="submit" type="submit" class="submit action-button register_btn" value="Register" />

            </fieldset>
        </form>
    </div>
@endsection
@section('script')
    <script>
        const scrollingElement = (document.scrollingElement || document.body);

        const scrollSmoothToTop = () => {
            scrollingElement.scrollTop = 0;
        }
    </script>
    <script src="{{ asset('asset_frontend/js/register.js') }}"></script>
@endsection
