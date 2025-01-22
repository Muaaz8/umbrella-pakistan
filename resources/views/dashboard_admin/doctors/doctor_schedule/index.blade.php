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
<script src="{{ asset('asset_admin/plugins/jquery/jquery-v3.2.1.min.js') }}"></script>
@endsection


@section('bottom_import_file')
<script>
    $(document).ready(function () {
        $('#doc_id').on("change", function (e) {
            var id = $('#doc_id').val();
            window.location.href = "/doctors/doctor/schedule/" + id;


        });
    });

    function view_app(app)
    {
        $('#app_modal_bodies').empty();
        var i = 0;
        $.each (app, function (key, ap) {
            i = i + 1;
            $('#app_modal_bodies').append('<tr id="app_body_'+ap.id+'">'
            );
            $('#app_body_'+ap.id).append('<td data-label="S.No" scope="row">'+i+'</td>'
            +'<td data-label="Patient Name">'+ap.patient_name+'</td>'
            +'<td data-label="Status">'+ap.status+'</td>'
            );
        });
        $('#viewappointmentModal').modal('show');
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
                        <h3>Doctor Schedule</h3>
                    </div>
                        <div class="col-md-4  p-0">


                            <select class="form-select" aria-label="Default select example" id="doc_id">
                                <option selected>Select Doctor</option>
                                @foreach ($doctors as $user)
                                @if ($user->id == $id)
                                <option selected value="{{ $user->id }}">
                                    {{ $user->name . ' ' . $user->last_name }}</option>
                                @else
                                <option value="{{ $user->id }}">{{ $user->name . ' ' . $user->last_name }}
                                </option>
                                @endif
                                @endforeach

                            </select>
                        </div>
                    </div>
                    <div class="wallet-table">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Date</th>
                                    <th scope="col">Start Time</th>
                                    <th scope="col">End Time</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($events as $event)
                                <tr>
                                    <td data-label="Date">{{ explode(" ",$event->start)[0] }}</td>
                                    <td data-label="Start Time">{{ explode(" ",$event->start)[1]." ".explode(" ",$event->start)[2] }}</td>
                                    <td data-label="End Time">{{ explode(" ",$event->end)[1]." ".explode(" ",$event->end)[2] }}</td>

                                    <td data-label="Action" class="lab-app-td-icon">

                                        <button
                                            class="orders-view-btn" style="position: relative" data-bs-toggle="modal"
                                            onclick="view_app({{$event->appointments}})"
                                            data-bs-target="#viewappointmentModal">
                                            <h6 class="app-num">{{count($event->appointments)}}</h6>
                                            View Appointments
                                        </button>
                                        @empty
                                <tr>
                                    <td colspan="4">Select a Doctor to view his Schedules</td>
                                </tr>
                                @endforelse
                                </td>
                                </tr>
                            </tbody>
                        </table>

                        {{ $events->links('pagination::bootstrap-4') }}

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>


</div>

<!-- ================= Add schedule Modal start ================ -->
<!-- Button trigger modal -->

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    Add Schedule
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="p-3">
                    <div class="row">
                        <div class="col-md-6">
                            <select class="form-select" aria-label="Default select example">
                                <option selected>Select Doctor</option>
                                <option value="1">Ahmer</option>
                                <option value="2">AHmer</option>
                                <option value="3">Muaaz</option>
                            </select>
                        </div>
                        <div class=" mb-3 col-md-6">
                            <input type="date" class="form-control" placeholder="Username" aria-label="Username"
                                aria-describedby="basic-addon1" />
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <input type="time" class="form-control" placeholder="Username" aria-label="Username"
                                    aria-describedby="basic-addon1" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <input type="time" class="form-control" placeholder="Username" aria-label="Username"
                                    aria-describedby="basic-addon1" />
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button class="w-100 add-schedule">Add Schedule</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ================= Add schedule Modal Ends ================ -->

<!-- ================= Delete Modal Starts ================ -->

<div class="modal fade" id="deleteeModal" tabindex="-1" aria-labelledby="deleteeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteeModalLabel">
                    You want delete this schedule?
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="p-5 text-center">
                    <button type="button" class="btn btn-danger delete-m-btn me-2">
                        Delete
                    </button>
                    <button type="button" class="btn btn-primary delete-m-btn">
                        No
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ================= Delete Modal Ends ================ -->

<!-- ================= View Appointment Modal starts ================ -->
<!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="viewappointmentModal" tabindex="-1" aria-labelledby="viewappointmentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewappointmentModalLabel">Appointments</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">S.No</th>
                                <th scope="col">Patient Name</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody id="app_modal_bodies">
                            <tr>
                                <td data-label="S.No" scope="row"></td>
                                <td data-label="Patient Name"></td>
                                <td data-label="Status"> <button class="orders-view-btn"></button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- ================= View Appointment Modal Ends ================ -->
@endsection
