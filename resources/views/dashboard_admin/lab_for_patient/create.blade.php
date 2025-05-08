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

        .bank_select_divv {
            box-shadow: rgba(17, 17, 26, 0.05) 0px 1px 0px, rgba(17, 17, 26, 0.1) 0px 0px 8px;
            background-color: #ffffffcf;
            padding: 10px;
            border-radius: 5px;
            height: 330px;
            overflow-y: auto;
        }

        #selectedLabsList{
            height: 330px;
            overflow-y: auto;
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

        $("#save_labs").on("click", function() {
            let selectedTests = [];
            $("input[name='test[]']:checked").each(function() {
                selectedTests.push($(this).val());
            });

            if(selectedTests.length < 1){
                alert('Please select atleast 1 lab test.');
            }

            $("#selectedLabs").html("");
            let totalPrice = 0;
            $.each(selectedTests, function (indexInArray, valueOfElement) {
                let label = $("label[for='defaultCheck" + valueOfElement + "']").html();
                $("#selectedLabs").append("<li class='list-group-item'>" + label + "</li>");

                let price = $("label[for='defaultCheck" + valueOfElement + "']").text().split("Rs. ")[1];
                totalPrice += parseFloat(price.replace(/[^0-9.-]+/g, ""));
            });
            $("#pricetag").html(totalPrice);
            $("#price").val(totalPrice);

        });

        $(document).ready(function () {
            $('input[name="patient_type"]').change(function () {
                if ($(this).val() === 'new') {
                    $('.new_pat').show().find('input').prop('disabled', false);
                    $('.exist_pat').hide().find('input').prop('disabled', true);
                } else {
                    $('.new_pat').hide().find('input').prop('disabled', true);
                    $('.exist_pat').show().find('input').prop('disabled', false);
                }
            });
            $('input[name="patient_type"]:checked').trigger('change');
        });

        function select_exist_pat(id){
            $("#user_id").val(id);
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
                                <h3>Add Lab for Patient</h3>
                            </div>
                        </div>
                        <div class="wallet-table " style="border-radius: 18px;">
                            <form action="{{ route('lab_for_patient_store') }}" method="POST">
                                @csrf
                                <div class="p-3">
                                    <div class="d-flex justify-content-around">
                                        <div>
                                            <input type="radio" name="patient_type" id="patient_type1" value="new" checked>
                                            <label for="patient_type1">New Patient</label>
                                        </div>
                                        <div>
                                            <input type="radio" name="patient_type" id="patient_type2" value="exist">
                                            <label for="patient_type2">Existing Patient</label>
                                        </div>
                                    </div>
                                    <div class="row mb-3 new_pat">
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
                                    </div>
                                    <div class="row mb-3 exist_pat">
                                        <input type="hidden" name="user_id" id="user_id">
                                        <div id="search-view">
                                            <div class="container mt-2">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="input-group">
                                                            <input type="text" id="search-input" class="form-control" placeholder="Search User" oninput="searchUsers()">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="container mt-2 table-height" style="max-height: 300px; overflow-y: auto;">
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
                                                                <button type="button" class="btn process-pay" onclick="select_exist_pat({{$patient->id}})">Select</button>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>
                                    <div class="col-md-12 sub_cat">
                                        <label class="fw-bolder mb-2" for="selectmedicine">Lab Test</label>
                                        <br>
                                        <div class="row">
                                            <div class="col-md-6 bank_select_divv p-2">
                                                @foreach ($products as $item)
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="{{ $item->TEST_CD }}"
                                                            id="defaultCheck{{ $item->TEST_CD }}" name="test[]">
                                                        <label class="form-check-label"
                                                            for="defaultCheck{{ $item->TEST_CD }}" id="defaultCheck{{ $item->TEST_CD }}">
                                                            {{ $item->TEST_NAME }} <strong>(Rs. {{$item->SALE_PRICE}})</strong>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="col-md-6" id="selectedLabsList">
                                                <div class="d-flex justify-content-between">
                                                    <h6>Selected Labs: </h6>
                                                    <h6>Price: Rs. <span id="pricetag"></span> </h6>
                                                    <input type="hidden" name="price" id="price">
                                                </div>
                                                <ul id="selectedLabs" class="list-group">
                                                    {{-- Selected labs will be displayed here --}}
                                                </ul>
                                            </div>
                                            <div class="col-md-12 my-2 d-flex justify-content-center">
                                                <button type="button" id="save_labs" class="btn process-pay ">Save</button>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row">
                                        <input type="hidden" name="payment_method" id="payment_method">
                                        {{-- <div class="col-md-4">
                                            <div class="payment-method w-100 p-2 d-flex align-items-center justify-content-between flex-column h-100"
                                                data-method="credit-card">
                                                <img class="icon"
                                                    src="http://127.0.0.1:8000/assets/new_frontend/cards.png" alt=""
                                                    width="250px">
                                                <h5>Credit Card</h5>
                                            </div>
                                        </div> --}}
                                        <div class="col-md-6">
                                            <div class="payment-method w-100 p-2 d-flex align-items-center justify-content-between flex-column"
                                                data-method="easy-paisa">
                                                <img class="icon"
                                                    src="http://127.0.0.1:8000/assets/new_frontend/easypaisa-logo.png"
                                                    alt="" width="100px">
                                                <h5>EasyPaisa</h5>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
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

        function searchUsers() {
            const searchValue = document.getElementById('search-input').value.toLowerCase();
            const rows = document.querySelectorAll('#user-table tbody tr');
            rows.forEach(row => {
                const cells = row.getElementsByTagName('td');
                const name = cells[1].textContent.toLowerCase();
                const phone = cells[2].textContent.toLowerCase();
                const email = cells[3].textContent.toLowerCase();
                row.style.display = (name.includes(searchValue) || email.includes(searchValue) || phone.includes(searchValue)) ? '' : 'none';
            });
        }
    </script>
@endsection
