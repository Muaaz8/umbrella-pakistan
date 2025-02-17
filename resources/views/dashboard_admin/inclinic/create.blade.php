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
