@extends('layouts.dashboard_admin')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>UHCS - Admin Dashboard</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
@endsection

@section('content')
    <div class="dashboard-content">
        <div class="container-fluid">
            <div class="row m-auto">
                <div class="col-md-12">
                    <div class="row m-auto">
                        <div class="d-flex align-items-baseline justify-content-between flex-wrap p-0">
                            <div>
                                <h3>Add In Clinics Patient</h3>
                            </div>
                        </div>
                        <div class="wallet-table " style="border-radius: 18px;">
                            <form action="{{ route('in_clinics_store') }}" method="POST">
                                @csrf
                                <div class="p-3">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="fw-bolder mb-2" for="selectmedicine">Patient First Name</label>
                                            <input type="text" name="first_name" class="form-control" required placeholder="Enter First Name...">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="fw-bolder mb-2" for="selectmedicine">Patient Last Name</label>
                                            <input type="text" name="last_name" class="form-control" required placeholder="Enter Last Name...">
                                        </div>
                                        <div class="col-md-6 sub_cat">
                                            <label class="fw-bolder mb-2" for="selectmedicine">Patient Phone Number</label>
                                            <input type="text" name="phone" class="form-control" required placeholder="Enter Phone Number...">
                                        </div>
                                        <div class="col-md-6 sub_cat">
                                            <label class="fw-bolder mb-2" for="selectmedicine">Patient Email</label>
                                            <input type="email" name="email" class="form-control" required placeholder="Enter Email...">
                                        </div>
                                        <div class="col-md-6 sub_cat">
                                            <label class="fw-bolder mb-2" for="selectmedicine">Patient Date of Birth</label>
                                            <input type="date" name="dob" class="form-control" required placeholder="Enter DOB...">
                                        </div>
                                        <div class="col-md-12 sub_cat">
                                            <label class="fw-bolder mb-2" for="selectmedicine">Reason</label>
                                            <br>
                                            <textarea name="reason" id="reason" cols="135" rows="5"></textarea>
                                        </div>
                                    </div>

                                    <hr>
                                    <div class="row mb-3">
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
                                    </div>

                                    <div class="row mt-3">
                                        <div class="text-end">
                                            <button type="submit" class="btn process-pay">Submit</button>
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
@endsection
