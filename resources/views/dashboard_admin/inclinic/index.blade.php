@extends('layouts.dashboard_admin')

@section('meta_tags')
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
<style>
    .searchable-list {
        max-height: 200px;
        overflow-y: auto;
    }

    .table-height {
        max-height: 300px;
        overflow-y: auto;
    }

    .table-height::-webkit-scrollbar {
        width: 5px;
    }

    .table-height::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 5px;
    }

    .table-height::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    .table-height::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
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
                    <div class="d-flex flex-wrap justify-content-between align-items-baseline p-0">
                        <h3>All In Clinics Patients</h3>
                        <div class="col-md-4 col-sm-6 col-12 p-0">
                            <div class="input-group">
                                <a href="{{ route('in_clinics_create') }}" class="btn process-pay">Add new</a>
                                <div class="btn process-pay mx-2" data-bs-toggle="modal"
                                    data-bs-target="#find-user-in-table">Existing Patients</div>
                            </div>
                        </div>
                    </div>
                    <div class="wallet-table">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Id</th>
                                    <th scope="col">Patient Name</th>
                                    <th scope="col">Patient Number</th>
                                    <th scope="col">Patient Email</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Follow Up</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $item)
                                <tr>
                                    <td>{{$item->id}}</td>
                                    <td>{{$item->user->name}}</td>
                                    <td>{{$item->user->phone_number}}</td>
                                    <td>{{$item->user->email}}</td>
                                    <td>{{$item->created_at}}</td>
                                    <td>{{$item->follow_up==1?"Needed":"Not Needed"}}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="m-auto text-center for-empty-div">
                                            <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                            <h6>No Related Products To Show</h6>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $data->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="find-user-in-table" tabindex="-1" aria-labelledby="find-user-in-tableLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Patients List</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="search-view">
                    <div class="container mt-2">
                        <form id="search-form" action="#">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <input type="text" id="search-input" class="form-control" placeholder="Search User" oninput="searchUsers()">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="container mt-2 table-height">
                        <table class="table table-hover mb-2" id="user-table">
                            <thead>
                                <tr scope="row">
                                    <th class="p-2">Id</th>
                                    <th class="p-2">Patient Name</th>
                                    <th class="p-2">Patient Number</th>
                                    <th class="p-2">Patient Email</th>
                                    <th class="p-2">Action</th>
                                </tr>
                            </thead>
                            <tbody id="user-table-body">
                                @foreach ($patients as $patient)
                                <tr>
                                    <td class="p-2">{{$patient->id}}</td>
                                    <td class="p-2">{{$patient->name}}</td>
                                    <td class="p-2">{{$patient->phone_number}}</td>
                                    <td class="p-2">{{$patient->email}}</td>
                                    <td class="p-2">
                                        <button class="btn process-pay" onclick="showUserForm('{{ $patient->id }}', '{{ $patient->name }}', '{{ $patient->phone_number }}', '{{ $patient->email }}')">Select</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="user-detail-view" style="display: none;">
                    <button class="btn btn-secondary mx-3 my-2" onclick="showSearchView()"><i class="fas fa-arrow-left"></i> Back</button>
                    <form id="user-detail-form" action="{{ route('in_clinics_store') }}" method="post">
                        @csrf
                        <div class="row container mb-3">
                            <input type="hidden" name="user_id" id="user-id" value="">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="user-name" class="form-label">Name</label>
                                    <input type="text" id="user-name" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="user-email" class="form-label">Email</label>
                                    <input type="email" id="user-email" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="user-phone" class="form-label">Phone</label>
                                    <input type="text" id="user-phone" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
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

                            <div class="col-md-12">
                                <div class="mb-2">
                                    <label for="reason" class="form-label">Reason</label>
                                    <textarea id="reason" name="reason" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                            {{-- <div class="col-md-12">
                                <div class="mb-2">
                                    <label for="payment" class="form-label">Payment</label>
                                    <div class="d-flex justify-content-between border border-1">
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
                                </div>
                            </div> --}}
                            <div class="row mb-3">
                                <input type="hidden" name="payment_method" id="payment_method">
                                <div class="col-md-4">
                                    <div class="payment-method w-100 p-2 d-flex align-items-center justify-content-between flex-column h-100"
                                        data-method="credit-card">
                                        <img class="icon"
                                            src="http://127.0.0.1:8000/assets/new_frontend/cards.png" alt=""
                                            width="100%">
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
                            <div class="col-md-12">
                                <button type="submit" class="btn process-pay w-100">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function searchUsers() {
        const searchValue = document.getElementById('search-input').value.toLowerCase();
        const rows = document.querySelectorAll('#user-table tbody tr');
        rows.forEach(row => {
            const cells = row.getElementsByTagName('td');
            const name = cells[1].textContent.toLowerCase();
            const email = cells[3].textContent.toLowerCase();
            row.style.display = (name.includes(searchValue) || email.includes(searchValue)) ? '' : 'none';
        });
    }

    function showUserForm(id, name, phone, email) {
        document.getElementById('search-view').style.display = 'none';
        document.getElementById('user-detail-view').style.display = 'block';

        document.getElementById('user-id').value = id;
        document.getElementById('user-name').value = name;
        document.getElementById('user-email').value = email;
        document.getElementById('user-phone').value = phone;
    }

    function showSearchView() {
        document.getElementById('user-detail-view').style.display = 'none';
        document.getElementById('search-view').style.display = 'block';
    }
</script>


@endsection
