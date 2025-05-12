@extends('layouts.dashboard_admin')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
    <style>
        .payment-method {
            cursor: pointer;
            border: 2px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            transition: 0.3s;
        }

        .payment-method:hover,
        .payment-method.active {
            border-color: #007bff;
            background-color: #f8f9fa;
        }

        .custom-form-control {
            width: 75%;
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
    </style>
@endsection

@section('page_title')
    <title>CHCC - Admin Dashboard</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
    <script>
        $(".payment-method").on("click", function() {
            $(".payment-method").removeClass("active");
            $(this).addClass("active");

            let selectedMethod = $(this).data("method");

            $("#submit_btn").attr("disabled", false);
            $("#payment_method").val(selectedMethod);
        });

        // Show/Hide other_condition input based on radio button selection
        $("input[name='med_condition[]']").on("change", function() {
            if ($(this).val() === "Other") {
                if ($("#other_condition").hasClass("d-none")) {
                    $("#other_condition").removeClass("d-none");
                } else {
                    $("#other_condition").addClass("d-none");
                }
            }
        });

        // Show/Hide medication_allergies input based on radio button selection
        $("input[name='allergies']").on("change", function() {
            if ($(this).val() === "yes") {
                $("#medication_allergies").removeClass("d-none");
            } else {
                $("#medication_allergies").addClass("d-none");
            }
        });

        // Show/Hide list_food_allergies input based on radio button selection
        $("input[name='food_allergies']").on("change", function() {
            if ($(this).val() === "yes") {
                $("#list_food_allergies").removeClass("d-none");
            } else {
                $("#list_food_allergies").addClass("d-none");
            }
        });


        $("input[name='weight'], input[name='height']").on("keyup", function() {
            calculateBMI();
        });

        function calculateBMI() {
            var weight = parseFloat(document.getElementById('weight').value);
            var height = parseFloat(document.getElementById('height').value) / 100; // Convert cm to m

            if (!isNaN(weight) && !isNaN(height) && height > 0) {
                var bmi = (weight / (height * height)).toFixed(2);
                document.getElementById('bmi').value = bmi;
            } else {
                document.getElementById('bmi').value = '';
            }
        }

    </script>
@endsection

@section('content')
    <div class="dashboard-content">
        <div class="container-fluid">
            <div class="row m-auto">
                <div class="col-md-12">
                    <div class="row m-auto">
                        <div class="d-flex align-items-baseline justify-content-between flex-wrap p-0">
                            <div>
                                <h3>Add In-clinics Patient</h3>
                            </div>
                        </div>
                        <div class="wallet-table " style="border-radius: 18px;">
                            <form action="{{ route('in_clinics_store') }}" method="POST">
                                @csrf
                                <div class="p-3">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="fw-bolder mb-2" for="selectmedicine">Patient First Name</label>
                                            <input type="text" name="first_name" class="form-control" required
                                                placeholder="Enter First Name...">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="fw-bolder mb-2" for="selectmedicine">Patient Last Name</label>
                                            <input type="text" name="last_name" class="form-control" required
                                                placeholder="Enter Last Name...">
                                        </div>
                                        <div class="col-md-6 sub_cat">
                                            <label class="fw-bolder mb-2" for="selectmedicine">Patient Phone Number</label>
                                            <input type="text" id="phone_number" name="phone" class="form-control"
                                                required placeholder="Enter Phone Number..." pattern="\d{11}"
                                                title="Phone number must be 11 digits">
                                        </div>
                                        <div class="col-md-6 sub_cat">
                                            <label class="fw-bolder mb-2" for="selectmedicine">Patient Email</label>
                                            <input type="email" name="email" class="form-control"
                                                placeholder="Enter Email...">
                                        </div>
                                        <div class="col-md-6 sub_cat">
                                            <label class="fw-bolder mb-2" for="selectmedicine">Patient Date of Birth</label>
                                            <input type="date" name="dob" class="form-control" required
                                                placeholder="Enter DOB..." value="1990-01-01">
                                        </div>
                                        <div class="col-md-6 sub_cat">
                                            <label class="fw-bolder mb-2" for="selectmedicine">Highest level of education complete</label>
                                            <select name="education" id="education" class="form-control">
                                                <option value="" disabled selected>Select Education Level</option>
                                                <option value="Less than High School">Less than High School</option>
                                                <option value="High School or equivalent">High School or equivalent</option>
                                                <option value="Attended College/University">Attended College/University</option>
                                                <option value="Bachelor’s degree">Bachelor’s degree</option>
                                                <option value="Master’s degree">Master’s degree</option>
                                                <option value="I prefer not to answer">I prefer not to answer</option>
                                            </select>
                                        </div>

                                        <hr class="mt-3">

                                        <h4>Medical History</h4>
                                        <div class="col-md-6">
                                            <label class="fw-bolder mb-2" for="selectmedicine">Existing Medical Conditions:</label>
                                            <div>
                                                <div>
                                                    <input type="checkbox" name="med_condition[]" id="HTN" value="HTN">
                                                    <label for="HTN">HTN</label>
                                                </div>
                                                <div>
                                                    <input type="checkbox" name="med_condition[]" id="DM" value="DM">
                                                    <label for="DM">DM</label>
                                                </div>
                                                <div>
                                                    <input type="checkbox" name="med_condition[]" id="TSH" value="TSH">
                                                    <label for="TSH">TSH</label>
                                                </div>
                                                <div>
                                                    <input type="checkbox" name="med_condition[]" id="Asthma" value="Asthma">
                                                    <label for="Asthma">Asthma</label>
                                                </div>

                                                <div>
                                                    <input type="checkbox" name="med_condition[]" id="Other" value="Other">
                                                    <label for="Other">Other</label>
                                                    <input type="text" name="other_condition" id="other_condition" class="custom-form-control d-none"
                                                        placeholder="Please specify...">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="fw-bolder mb-2" for="selectmedicine">Tobacco Use:</label>
                                            <div>
                                                <div>
                                                    <input type="radio" name="tobacco_use" id="Never smoked" value="Never smoked">
                                                    <label for="Never smoked">Never smoked</label>
                                                </div>
                                                <div>
                                                    <input type="radio" name="tobacco_use" id="former smoker, no current use" value="former smoker, no current use">
                                                    <label for="former smoker, no current use">former smoker, no current use</label>
                                                </div>
                                                <div>
                                                    <input type="radio" name="tobacco_use" id="current smoker, some days only" value="current smoker, some days only">
                                                    <label for="current smoker, some days only">current smoker, some days only</label>
                                                </div>
                                                <div>
                                                    <input type="radio" name="tobacco_use" id="current smoker, everyday use" value="current smoker, everyday use">
                                                    <label for="current smoker, everyday use">current smoker, everyday use</label>
                                                </div>

                                                <div>
                                                    <input type="radio" name="tobacco_use" id="unknown smoking history" value="unknown smoking history">
                                                    <label for="unknown smoking history">unknown smoking history</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="fw-bolder mb-2" for="selectmedicine">Allergies to Medication:</label>
                                            <div class="d-flex justify-content-center gap-3">
                                                <div>
                                                    <input type="radio" name="allergies" id="allergies_yes" value="yes">
                                                    <label for="allergies_yes">Yes</label>
                                                </div>
                                                <div>
                                                    <input type="radio" name="allergies" id="allergies_no" value="no">
                                                    <label for="allergies_no">No</label>
                                                </div>
                                            </div>
                                            <input type="text" name="medication_allergies" id="medication_allergies" class="form-control d-none"
                                                placeholder="Please specify...">
                                        </div>


                                        <div class="col-md-6">
                                            <label class="fw-bolder mb-2" for="selectmedicine">Allergies to Food and/or other substances</label>
                                            <div class="d-flex justify-content-center gap-3">
                                                <div>
                                                    <input type="radio" name="food_allergies" id="food_allergies_yes" value="yes">
                                                    <label for="food_allergies_yes">Yes</label>
                                                </div>
                                                <div>
                                                    <input type="radio" name="food_allergies" id="food_allergies_no" value="no">
                                                    <label for="food_allergies_no">No</label>
                                                </div>
                                            </div>
                                            <input type="text" name="list_food_allergies" id="list_food_allergies" class="form-control d-none"
                                                placeholder="Please specify...">
                                        </div>

                                        <hr class="mt-3">
                                        <h4>Vital Records</h4>
                                        <div class="col-md-6">
                                            <label class="fw-bolder mb-2" for="selectmedicine">Blood Pressure</label>
                                            <input type="text" name="blood_pressure" class="form-control"
                                                placeholder="Enter Blood Pressure...">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="fw-bolder mb-2" for="selectmedicine">Pulse</label>
                                            <input type="text" name="pulse" class="form-control"
                                                placeholder="Enter Pulse...">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="fw-bolder mb-2" for="selectmedicine">Temperature</label>
                                            <input type="text" name="temperature" class="form-control"
                                                placeholder="Enter Temperature...">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="fw-bolder mb-2" for="selectmedicine">SpO2</label>
                                            <input type="text" name="spo2" class="form-control"
                                                placeholder="Enter SpO2...">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="fw-bolder mb-2" for="selectmedicine">Random Blood Glucose</label>
                                            <input type="text" name="blood_glucose" class="form-control"
                                                placeholder="Enter Random Blood Glucose...">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="fw-bolder mb-2" for="selectmedicine">Weight</label>
                                            <input type="text" name="weight" id="weight" class="form-control"
                                                placeholder="Enter Weight in Kg...">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="fw-bolder mb-2" for="selectmedicine">Height</label>
                                            <input type="text" name="height" id="height" class="form-control"
                                                placeholder="Enter Height in cm...">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="fw-bolder mb-2" for="selectmedicine">BMI</label>
                                            <input type="text" name="bmi" id="bmi" class="form-control"
                                                placeholder="Enter BMI...">
                                        </div>

                                        <hr class="mt-3">


                                        <div class="col-md-12 sub_cat">
                                            <label class="fw-bolder mb-2" for="selectmedicine">Reason</label>
                                            <br>
                                            <textarea name="reason" id="reason" cols="135" rows="5"></textarea>
                                        </div>
                                    </div>

                                    <hr>
                                    {{-- <div class="row mb-3">
                                        <label class="fw-bolder mb-2" for="payment">Payment</label>
                                        <div class="d-flex justify-content-around">
                                            <div>
                                                <input type="radio" name="payment" id="card" value="card" disabled>
                                                <label for="card">Card</label>
                                            </div>
                                            <div>
                                                <input type="radio" name="payment" id="easypaisa" value="easypaisa">
                                                <label for="easypaisa">Easy Paisa</label>
                                            </div>
                                            <div>
                                                <input type="radio" name="payment" id="cash" value="cash">
                                                <label for="cash">Cash</label>
                                            </div>
                                        </div>
                                    </div> --}}

                                    <div class="row">
                                        <input type="hidden" name="payment_method" id="payment_method">
                                        <div class="col-md-4">
                                            <div class="payment-method w-100 p-2 d-flex align-items-center justify-content-between flex-column h-100"
                                                data-method="credit-card">
                                                <img class="icon"
                                                    src="http://127.0.0.1:8000/assets/new_frontend/cards.png" alt=""
                                                    width="250px">
                                                <h5>Credit Card</h5>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="payment-method w-100 p-2 d-flex align-items-center justify-content-between flex-column"
                                                data-method="easy-paisa">
                                                <img class="icon"
                                                    src="http://127.0.0.1:8000/assets/new_frontend/easypaisa-logo.png"
                                                    alt="" width="100px">
                                                <h5>EasyPaisa</h5>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="payment-method w-100 p-2 d-flex align-items-center justify-content-between flex-column"
                                                data-method="online-cash">
                                                <img class="icon"
                                                    src="http://127.0.0.1:8000/assets/new_frontend/online-money.png"
                                                    alt="" width="100px">
                                                <h5>Online/Cash</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="text-end">
                                            <button type="submit" id="submit_btn" class="btn process-pay"
                                                disabled>Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('phone_number').addEventListener('input', function(e) {
            if (this.value.length > 11) {
                this.value = this.value.slice(0, 11);
            }
        });
    </script>
@endsection
