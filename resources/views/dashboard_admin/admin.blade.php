@extends('layouts.dashboard_admin')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>CHCC - Admin Dashboard</title>
@endsection

@section('top_import_file')
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" crossorigin="anonymous"></script>
@endsection


@section('bottom_import_file')
    <script>
        $(document).ready(function() {
            var authorize_api_status = "{{ $user->authorize_api_status }}"
            var maintain_status = "{{ $user->maintain_status }}"
            var ticker_status = "{{ $user->ticker_status }}"
            if (authorize_api_status == "liveMode") {
                $("#checkbox").prop("checked", true);
                $('#mode_text').text('LIVE');
            }
            if (maintain_status == "on") {
                $("#checkbox2").prop("checked", true);
                $('#maintainance_status').text('ON');
            }
            if (ticker_status == "on") {
                $("#checkbox3").prop("checked", true);
                $('#ticker_status').text('ON');
            }
        });
        $('#checkbox').click(function() {
            $('#pay_mode').modal('show');
        });
        $('#checkbox2').click(function() {
            $('#maintain_confirm').modal('show');
        });
        var ticker_value = "{{ $user->ticker_value }}"
        $('#checkbox3').click(function() {
            if(ticker_value != "")
            {
                $("#ticker_text").val(ticker_value);
            }
            $('#ticker').modal('show');
        });

        var myModalEl = document.getElementById('pay_mode');
        myModalEl.addEventListener('hidden.bs.modal', function(event) {
            if ($('#checkbox').prop('checked')) {
                $("#checkbox").prop("checked", false);
            } else {
                $("#checkbox").prop("checked", true);
            }
        });
        var myModalEl = document.getElementById('maintain_confirm');
        myModalEl.addEventListener('hidden.bs.modal', function(event) {
            if ($('#checkbox2').prop('checked')) {
                $("#checkbox2").prop("checked", false);
            } else {
                $("#checkbox2").prop("checked", true);
            }
        });
        var myModalEl = document.getElementById('ticker');
        myModalEl.addEventListener('hidden.bs.modal', function(event) {
            if ($('#checkbox3').prop('checked')) {
                $("#checkbox3").prop("checked", false);
            } else {
                $("#checkbox3").prop("checked", true);
            }
        });
    </script>
@endsection

@section('content')
    <div class="dashboard-content">
        <div class="container-fluid">
            <div class="row m-auto">
                <div class="col-md-12">
                    <div class="card first-card-wrap">
                        <div class="card-body">
                            <div class="first-card-content">
                                <p>Welcome to</p>
                                <h1>Community Healthcare Clinics</h1>
                                <div class="d-flex flex-wrap justify-content-between">
                                </div>
                            </div>
                            <div class="first-card-img-div">
                                {{-- <img src="{{ asset('assets/images/logo.png') }}" alt=""  height="auto" width="200"> --}}
                            </div>
                        </div>


                    </div>

                </div>
            </div>
            <div class="row m-auto">
                <div class="col-md-12">
                    <div class="row my-4">
                        <div class="col-md-3 mb-3">
                            <div class="dashboard-small-card-wrap">
                                <a href="{{ route('all_doctors') }}" class="text-dark">
                                <div class="d-flex dashboard-small-card-inner">
                                    <i class="fa-solid fa-user-doctor"></i>
                                    <div>
                                        <h6>Active Doctors</h6>
                                        <p>{{ $doctor_count }}</p>
                                    </div>

                                </div>
                                </a>
                                </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="dashboard-small-card-wrap">
                                <a href="{{ route('admin_all_patients') }}" class="text-dark">
                                    <div class="d-flex dashboard-small-card-inner">
                                    <i class="fa-solid fa-cart-shopping"></i>
                                    <div>
                                        <h6>Total Patients</h6>
                                        <p>{{ $patients_count }}</p>
                                    </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="dashboard-small-card-wrap">
                                <a href="{{ route('pending_doctor_requests') }}" class="text-dark">
                                    <div class="d-flex dashboard-small-card-inner">
                                    <i class="fa-solid fa-vials"></i>
                                    <div>
                                        <h6>Pending/Blocked Doctor</h6>
                                        <p>{{ $pending_doctors_count }}</p>
                                    </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="dashboard-small-card-wrap">
                                <a href="{{ route('admin_wallet_pay') }}" class="text-dark">
                                <div class="d-flex dashboard-small-card-inner">
                                    <i class="fa-solid fa-prescription-bottle-medical"></i>
                                    <div>
                                        <h6>Total Earnings</h6>
                                        <p>0</p>
                                    </div>
                                </div>
                                </a>
                            </div>
                        </div>

                    </div>
                    <div class="first-card-wrap card py-3 px-2 mb-2">
                        <div class="row">
                            <div class="d-flex col-md-4 align-items-center">
                                <div>
                                    <h6>Authorize API Mode:</h6>
                                </div>
                                <div>
                                    <label class="switch ms-3 me-2" for="checkbox">
                                        <input type="checkbox" id="checkbox" />
                                        <div class="slider round"></div>
                                    </label>
                                </div>
                                <div>
                                    <h6 id="mode_text" class="test_mode">TEST</h6>
                                </div>
                            </div>
                            <div class="d-flex col-md-4 align-items-center">
                                <div>
                                    <h6>Maintainance:</h6>
                                </div>
                                <div>
                                    <label class="switch ms-3 me-2" for="checkbox2">
                                        <input type="checkbox" id="checkbox2" />
                                        <div class="slider round"></div>
                                    </label>
                                </div>
                                <div>
                                    <h6 id="maintainance_status" class="test_mode">Maintainance</h6>
                                </div>
                            </div>
                            <div class="d-flex col-md-4 align-items-center">
                                <div>
                                    <h6>Home Page Ticker:</h6>
                                </div>
                                <div>
                                    <label class="switch ms-3 me-2" for="checkbox3">
                                        <input type="checkbox" id="checkbox3" />
                                        <div class="slider round"></div>
                                    </label>
                                </div>
                                <div>
                                    <h6 id="ticker_status" class="test_mode">OFF</h6>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row m-auto">
                <div class="col-md-12">
                    <div class="row m-auto">
                        <div class="d-flex align-items-end p-0">
                            <div>
                                <h3>PENDING DOCTOR REQUESTS</h3>
                                <p>All Recent Requests</p>
                            </div>
                        </div>
                        <div class="wallet-table">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col">State</th>
                                        <th scope="col">Registered On</th>
                                        <th scope="col">UPIN</th>
                                        <th scope="col">PMDC</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($doctors as $doc)
                                        <tr>
                                            <td data-label="Name">
                                                {{ 'Dr. ' . ucfirst($doc->name) . ' ' . ucfirst($doc->last_name) }}
                                            </td>
                                            <td data-label="State">{{ $doc->state_name }}</td>
                                            <td data-label="Registered On">{{ $doc->created_at }}</td>
                                            <td data-label="UPIN">{{ $doc->upin }}</td>
                                            <td data-label="PMDC">{{ $doc->nip_number }}</td>
                                            <td data-label="Action">
                                                <center>
                                                    <a href="{{ route('doctor_pending_request_view', $doc->id) }}">
                                                        <button class="btn btn-raised process-pay">View</button>
                                                    </a>
                                                </center>

                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6">
                                                <center>No Pending Requests</center>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="paginateCounter link-paginate">
                                        {{ $doctors->links('pagination::bootstrap-4') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="pay_mode" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Change Payment Mode</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="delete-modal-body">
                        Are you sure you want to change Payment Mode?
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="window.location.href='/change/authorize_api/mode'"
                        class="btn btn-danger">Change</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        aria-label="Close">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="maintain_confirm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Change Maintanance Mode</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="delete-modal-body">
                        Are you sure you want to change Maintainance Mode?
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="window.location.href='/change/maintainance/mode'"
                        class="btn btn-danger">Change</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        aria-label="Close">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="ticker" tabindex="-1" aria-labelledby="tickerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="/change/ticker" method="POST">
                @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tickerModalLabel">Website Home Page Ticker</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="delete-modal-body">
                        <div class="row">
                            <div class="col-md-2">
                                Text:
                            </div>
                            <div class="col-md-10">
                                <input type="text" name="ticker_text" id="ticker_text" class="form-control" value="" required/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        aria-label="Close">Cancel</button>
                </div>
            </div>
            </form>
        </div>
    </div>
@endsection
