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

    $('input[name="to_time"]').on('change', function () {
    var from_time = $('input[name="from_time"]').val();
    var to_time = $('input[name="to_time"]').val();

    if (!to_time) return;

    var maxTime = "23:59";

    var fromTimeObj = from_time ? new Date(`1970-01-01T${from_time}`) : null;
    var toTimeObj = new Date(`1970-01-01T${to_time}`);
    var maxTimeObj = new Date(`1970-01-01T${maxTime}`);

    if (toTimeObj > maxTimeObj) {
        $('#error').html('<p class="text-danger">To Time cannot be later than 11:59 PM</p>');
        $('#addTiming').attr('disabled', true);
    } else if (fromTimeObj && fromTimeObj >= toTimeObj) {
        $('#error').html('<p class="text-danger">To Time cannot be later than 11:59 PM</p>');
        $('#addTiming').attr('disabled', true);
    } else {
        $('#error').html('');
        $('#addTiming').attr('disabled', false);
    }
    });

</script>
@endsection

@section('content')

{{-- {{ dd($events , $doctors , $appointments) }} --}}

<div class="dashboard-content">
    <div class="container-fluid">
        <div class="row m-auto">
            <div class="col-md-12">
                <div class="row m-auto">
                    <div class="d-flex align-items-baseline justify-content-between flex-wrap p-0">
                        <div>
                        <h3>Doctor Schedule</h3>
                    </div>
                        <div class="col-md-4 p-0 d-flex align-items-center justify-content-around gap-2">
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
                            <button class="add-schedule w-100" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                <i class="fa-solid fa-plus"></i> Add Schedule
                            </button>
                        </div>
                    </div>

                    {{-- {{ dd($events) }} --}}
                    <div class="wallet-table">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Week Days</th>
                                    <th scope="col">Start Time</th>
                                    <th scope="col">End Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($events as $event)
                                <tr>
                                    <td data-label="Date">
                                        <ul class="list-unstyled">
                                            {!! $event->mon==1?"<li>Monday</li>":"" !!}
                                            {!! $event->tues==1?"<li>Tuesday</li>":"" !!}
                                            {!! $event->weds==1?"<li>Wednesday</li>":"" !!}
                                            {!! $event->thurs==1?"<li>Thursday</li>":"" !!}
                                            {!! $event->fri==1?"<li>Friday</li>":"" !!}
                                            {!! $event->sat==1?"<li>Saturday</li>":"" !!}
                                            {!! $event->sun==1?"<li>Sunday</li>":"" !!}
                                        </ul>
                                    </td>
                                    <td data-label="Start Time">{{ $event->from_time }}</td>
                                    <td data-label="End Time">{{ $event->to_time }}</td>
                                        @empty
                                    <tr>
                                    <td colspan="3">Select a Doctor to view his Schedules</td>
                                    </tr>
                                @endforelse
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

<!-- ================= Add Schedule Modal Starts ================ -->

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form method="POST" action="{{ route('add_doc_schedule') }}">
        @csrf
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
                        <label class="form-text"> Working Days</label>
                        <div class="input-group mb-2 d-flex justify-content-around">
                            <input type="checkbox" id="week1" name="week[]" value="Mon">
                            <label for="week1"> Mon</label><br>
                            <input type="checkbox" id="week2" name="week[]" value="Tues">
                            <label for="week2"> Tues</label><br>
                            <input type="checkbox" id="week3" name="week[]" value="Wed">
                            <label for="week3"> Wed</label><br>
                            <input type="checkbox" id="week4" name="week[]" value="Thurs">
                            <label for="week4"> Thurs</label><br>
                            <input type="checkbox" id="week5" name="week[]" value="Fri">
                            <label for="week5"> Fri</label><br>
                            <input type="checkbox" id="week6" name="week[]" value="Sat">
                            <label for="week6"> Sat</label><br>
                            <input type="checkbox" id="week7" name="week[]" value="Sun">
                            <label for="week7"> Sun</label><br>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <select required name="doc_id" class="form-select" aria-label="Default select example">
                                    <option disabled>Select Doctor</option>
                                    @foreach ($doctors as $user)
                                    <option value="{{ $user->id }}">{{ $user->name . ' ' . $user->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-text">From Time</label>
                                <div class="input-group mb-3">
                                    <input type="hidden" name="AvailabilityTitle" value="Availability"
                                        id="title" placeholder="Title">
                                    <input type="hidden" value="#008000" name="AvailabilityColor" id="color" />
                                    <input required type='time' class="form-control" name="from_time" placeholder="12:00 CST" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-text">To Time</label>
                                <div class="input-group mb-3">
                                    <input required max="23:59" type='time' class="form-control" name="to_time" placeholder="09:00 CST" />
                                </div>
                            </div>
                        </div>
                        <div class="text-center" id="error">
                        </div>
                        <div class="text-center">
                            <button id="addTiming" class="w-100 add-schedule">Add Schedule</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>


<!-- ================= Add Schedule Modal End =================== -->

@endsection
