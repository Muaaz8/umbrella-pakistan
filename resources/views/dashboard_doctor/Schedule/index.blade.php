@extends('layouts.dashboard_doctor')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title> Doctor Schedule</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
    <script>
        <?php header('Access-Control-Allow-Origin: *'); ?>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var myModalEl = document.getElementById('editscheduleModal');
        myModalEl.addEventListener('hidden.bs.modal', function(event) {
            $('#edit_error').html('');
        });
    </script>
    <script src="{{ asset('assets\js\doctor_dashboard_script\schedule_new.js?n=1') }}"></script>
@endsection

@section('content')
    <div class="dashboard-content">
        <div class="container-fluid">
            <div class="row m-auto">
                <div class="col-md-12">
                    <div class="row m-auto">
                        <div class="d-flex align-items-baseline justify-content-between flex-wrap p-0">
                            <h3>My Schedule</h3>
                            <div class="p-0">
                                <button class="add-schedule" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    <i class="fa-solid fa-plus"></i> Add Schedule
                                </button>
                            </div>
                        </div>
                        <div class="wallet-table table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Week Days</th>
                                        <th scope="col">Start Time</th>
                                        <th scope="col">End Time</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($schedule as $sch)
                                        <tr>
                                            <td data-label="Week Days">
                                                <ul class="d-inline-block">
                                                    {!! $sch->mon==1?"<li>Monday</li>":"" !!}
                                                    {!! $sch->tues==1?"<li>Tuesday</li>":"" !!}
                                                    {!! $sch->weds==1?"<li>Wednesday</li>":"" !!}
                                                    {!! $sch->thurs==1?"<li>Thursday</li>":"" !!}
                                                    {!! $sch->fri==1?"<li>Friday</li>":"" !!}
                                                    {!! $sch->sat==1?"<li>Saturday</li>":"" !!}
                                                    {!! $sch->sun==1?"<li>Sunday</li>":"" !!}
                                                </ul>
                                            </td>
                                            <td data-label="Start Time">{{ $sch->from_time }}</td>
                                            <td data-label="End Time">{{ $sch->to_time }}</td>
                                            <td data-label="Action" class="lab-app-td-icon">
                                                <i class="fa-solid fa-pen-to-square"
                                                    onclick="edit_schedule({{ $sch }},[])"></i>
                                                {{-- <i class="fa-solid fa-circle-xmark"
                                                    onclick="delete_schedule({{ $sch->id }},{{ $sch->appointments->count() }})"></i> --}}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4">
                                                <div class="m-auto text-center for-empty-div">
                                                    <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                                    <h6> No Schedule added</h6>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="row d-flex justify-content-center">
                                <div class="paginateCounter">
                                    {{ $schedule->links('pagination::bootstrap-4') }}
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
                                <div class="input-group mb-3 d-flex justify-content-around">
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
                                    <div class="col-md-6">
                                        <div class="input-group mb-3">
                                            <input type="hidden" name="AvailabilityTitle" value="Availability"
                                                id="title" placeholder="Title">
                                            <input type="hidden" value="#008000" name="AvailabilityColor" id="color" />
                                            <input type='time' class="form-control" name="from_time" placeholder="12:00 CST" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group mb-3">
                                            <input type='time' class="form-control" name="to_time" placeholder="09:00 CST" />
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
        <!-- ================= Add schedule Modal Ends ================ -->

        <!-- Edit Schedule Modal -->
        <div class="modal fade" id="editscheduleModal" tabindex="-1" aria-labelledby="editscheduleModalLabel"
            aria-hidden="true">
            <form id="edit_form" method="POST" action="{{ route('edit_doc_schedule') }}">
                @csrf
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editscheduleModalLabel">
                                Edit Schedule
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="p-3">
                                <!-- For the schedule Id -->
                                <div class="d-none scid"></div>
                                <label class="form-text"> Working Days</label>
                                <div class="input-group mb-3 d-flex justify-content-around">
                                    <input type="checkbox" id="edit-week1" name="week[]" value="Mon">
                                    <label for="week1"> Mon</label><br>
                                    <input type="checkbox" id="edit-week2" name="week[]" value="Tues">
                                    <label for="week2"> Tues</label><br>
                                    <input type="checkbox" id="edit-week3" name="week[]" value="Wed">
                                    <label for="week3"> Wed</label><br>
                                    <input type="checkbox" id="edit-week4" name="week[]" value="Thurs">
                                    <label for="week4"> Thurs</label><br>
                                    <input type="checkbox" id="edit-week5" name="week[]" value="Fri">
                                    <label for="week5"> Fri</label><br>
                                    <input type="checkbox" id="edit-week6" name="week[]" value="Sat">
                                    <label for="week6"> Sat</label><br>
                                    <input type="checkbox" id="edit-week7" name="week[]" value="Sun">
                                    <label for="week7"> Sun</label><br>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group mb-3">
                                            <input type="hidden" name="AvailabilityTitle" value="Availability"
                                                id="title" placeholder="Title">
                                            <input type="hidden" value="#008000" name="AvailabilityColor" id="color" />
                                            <input type='time' class="form-control" name="from_time" id="from_time" placeholder="12:00 CST" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group mb-3">
                                            <input type='time' class="form-control" name="to_time" id="to_time" placeholder="09:00 CST" />
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center" id="error">
                                </div>
                                <div class="text-center">
                                    <button id="addTiming" class="w-100 add-schedule">Edit Schedule</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- ================= Edit schedule Modal Ends ================ -->

        <!-- ================= Delete Modal Starts ================ -->
        <div class="modal fade" id="deleteeModal" tabindex="-1" aria-labelledby="deleteeModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteeModalLabel">
                            You want delete this schedule?
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="del_schedule">
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
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="load_appointments">



                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- ================= View Appointment Modal Ends ================ -->
        <!-- ================= Delete Modal Starts ================ -->
        <div class="modal fade" id="copylink" tabindex="-1" aria-labelledby="copylinkModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="copylinkModalLabel">
                            Copy Invitation Link
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="del_schedule">
                        <div class="p-5 text-center">
                            <input id="link_address" class="form-control" readonly />
                            <br>
                            <button id="copy_btn" type="button" class="btn btn-danger delete-m-btn me-2"
                                data-clipboard-action="copy" data-clipboard-target="#link_address">Copy Link</button>
                            <!-- <button type="button" class="btn btn-primary delete-m-btn" data-bs-dismiss="modal">No</button> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
