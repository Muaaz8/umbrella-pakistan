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
    <script>
        $(document).ready(function() {
            $('#deactive').click(function() {
                var allClasses = $(this).attr('class');
                var breakClasses = allClasses.split(' ');

                $('#deactive_form').attr('action', '/doctors/deactivate/' + breakClasses[4]);
            });
        });
    </script>
    <script>
        $('#cancel').click(function() {
            $('#send_email_modal').modal('hide');

        });

        function email_modal_function(a) {
            var email = $(a).attr('id');
            $('#email_to').val(email);
            $('#send_email_modal').modal('show');
        }
    </script>
@endsection


@section('bottom_import_file')
<script>
    $(document).ready(function(){
        $(document).on('click', '.pagination a', function(event){
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            adminfetch_data(page);
        });

        function adminfetch_data(page){
            var _id = '{{$doctor->id}}';
            $.ajax({
            url:"/admin/pagination/fetch_data?page="+page,
            data: {
                user_id: _id,
            },
            success:function(data)
            {
                $('#table_data').html(data);
            }
            });
        }
    });
</script>
@endsection
@section('content')
        <div class="dashboard-content">
            <div class="container-fluid">
                <div class="row m-auto">
                  <div class="col-md-12">
                    <div class="row m-auto">
                        <div class="d-flex flex-wrap justify-content-between p-0">
                            <div >
                              <h3>View Doctor Details</h3>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button class="btn btn-danger  me-2 {{$doctor->id}}" id="deactive" data-bs-toggle="modal" data-bs-target="#deactivate_doctor">Deactivate</button>
                                <button class=" btn btn-danger  me-2 send_email_btn" id="{{ $doctor->email }}" onclick="email_modal_function(this)">Send Email</button>
                              </div>

                        </div>
                    <div class="wallet-table">
                        <div class="p-3">
                            <nav>
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    <button class="nav-link {{ request('tab') == null ? 'active' : '' }}" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="{{ request('tab') == null ? 'true' : 'false' }}">Personal Information</button>
                                    <button class="nav-link {{ request('tab') == 'certificate' ? 'active' : '' }}" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="{{ request('tab') == 'certificate' ? 'true' : 'false' }}">Doctor Certificate</button>
                                    <button class="nav-link {{ request('tab') == 'activity' ? 'active' : '' }}" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="{{ request('tab') == 'activity' ? 'true' : 'false' }}">Activity Log</button>
                                    <button class="nav-link {{ request('tab') == 'sessions' ? 'active' : '' }}" id="nav-sessions-tab" data-bs-toggle="tab" data-bs-target="#nav-sessions" type="button" role="tab" aria-controls="nav-sessions" aria-selected="{{ request('tab') == 'sessions' ? 'true' : 'false' }}">Sessions History</button>
                                    <button class="nav-link {{ request('tab') == 'payment' ? 'active' : '' }}" id="nav-payment-tab" data-bs-toggle="tab" data-bs-target="#nav-payment" type="button" role="tab" aria-controls="nav-payment" aria-selected="{{ request('tab') == 'payment' ? 'true' : 'false' }}">Payment History</button>
                                </div>
                            </nav>
                            <div class="tab-content tab-style px-3 py-2" id="nav-tabContent">
                                <div  id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab"
                                class="tab-pane fade {{ request('tab') == 'activity' ? '' : (request('tab') ? '' : 'show active') }}"
                                >
                                    <div class="view-personal-info-tab">
                                        <div class="row mt-3">
                                            <div class="col-md-4 doctor_info"><p><span>Name:</span>&nbsp; <span>{{ucwords($doctor->name." ".$doctor->last_name)}}</span></p></div>
                                            <div class="col-md-4 doctor_info"><p><span>State:</span>&nbsp; <span>{{ucwords($doctor->state)}}</span></p></div>
                                            <div class="col-md-4 doctor_info"><p><span>Specialization:</span>&nbsp; <span>{{ucwords($doctor->spec)}}</span></p></div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-4 doctor_info"><p><span>Phone:</span>&nbsp; <span>{{ucwords($doctor->phone_number)}}</span></p></div>
                                            <div class="col-md-4 doctor_info"><p><span>PMDC:</span>&nbsp; <span>{{ucwords($doctor->nip_number)}}</span></p></div>
                                            <div class="col-md-4 doctor_info"><p><span>UPIN:</span>&nbsp; <span>{{ucwords($doctor->upin)}}</span></p></div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-4 doctor_info"><p><span>Address:</span>&nbsp; <span>{{ucwords($doctor->office_address)}}</span></p></div>
                                            <div class="col-md-4 doctor_info"><p><span>Join Date:</span>&nbsp; <span>{{date('m-d-Y',strtotime($doctor->created_at))}}</span></p></div>
                                            <div class="col-md-4 doctor_info"><p><span>Email: </span>&nbsp; <span>{{ucwords($doctor->email)}}</span></p></div>

                                        </div>
                                        <div class="col-md-4 doctor_info mt-3"><p><span>Average Response Time:</span>&nbsp; <span>
                                        @if ($averageResponseTime !== null)
                                        {{ number_format($averageResponseTime, 1) }} Min.
                                        @else
                                            No data found
                                        @endif
                                        </span></p>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-md-12 doctor_info">
                                            @if ($doctor->id_card_front != "")
                                            <p><span>Driving License/ID Card:</span>&nbsp; <span>
                                                <a href="{{ \App\Helper::get_files_url($doctor->id_card_front) }}" target="_blank">
                                                    <button type="button" class="btn license-btn">Front</button>
                                                </a>
                                                <a href="{{ \App\Helper::get_files_url($doctor->id_card_back) }}" target="_blank">
                                                    <button type="button" class="btn license-btn">Back</button>
                                                </a>
                                                </span></p>
                                            @else
                                                <p><span>Driving License/ID Card:</span>&nbsp;<span>No Driving License Uploaded</span></p>
                                            @endif

                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                                <div>
                                    <div class="row mt-4">
                                        @forelse($certificate as $cert)
                                    <div class="col-md-3 col-sm-6 mb-3">
                                        <div class="file-view-div">
                                        <img src="{{ asset('assets/images/umbrella-file-view-icon.png') }}" alt="">
                                        <a href="{{$cert->certificate_file}}" target="blank"
                                            class="ml-3">View File</a>
                                        </div>
                                    </div>
                                    @empty
                                    <!-- <li class="list-group-item mt-5">
                                        No documents added
                                    </li> -->
                                    <div class=" mb-3">
                                        <div style="text-transform:uppercase" class="text-center fw-bold">
                                        No documents added
                                        </div>
                                    </div>
                                    @endforelse

                                    </div>
                                </div>
                                </div>
                                <div class="tab-pane fade {{ request('tab') == 'activity' ? 'show active' : '' }}" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                                    <div class="widget">
                                        <div class="widget-content">
                                            <div class="column-wrap">
                                                <div class="coloumn">
                                                    <div class="activity">
                                                        <h3>Last Activities</h3>
                                                        <div id="table_data">
                                                            @include('dashboard_admin.doctors.all_doctors.pagination_data')
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- /////////////////////////// --}}
                                <div class="tab-pane fade" id="nav-sessions" role="tabpanel" aria-labelledby="nav-sessions-tab">
                                    <div class="row m-auto mt-3">
                                        <div class="wallet-table">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                    <th scope="col">Patient Name</th>
                                                    <th scope="col">Date</th>
                                                    <th scope="col">Duration</th>
                                                    <th scope="col">Invite Time</th>
                                                    <th scope="col">Join Invite Time</th>
                                                    <th scope="col">No. Queue Patients</th>
                                                    <th scope="col">Response time</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($sessions as $session)
                                                    <tr>
                                                        <td data-label="Patient Name">{{ ucwords($session->patient_name) }} {{ ucwords($session->patient_last_name) }}</td>
                                                        <td data-label="Date">{{ date('m-d-Y', strtotime($session->date)) }}</td>
                                                        <td data-label="Duration">
                                                            @php
                                                                $duration = strtotime($session->end_time) - strtotime($session->start_time);
                                                                echo $duration > 0 ? sprintf('%02d:%02d', intdiv($duration, 60), $duration % 60) : '-';
                                                            @endphp
                                                        </td>
                                                        <td data-label="Time">{{ date('h:i A', strtotime($session->invite_time)) }}</td>
                                                        <td data-label="Confirm Time">{{ date('h:i A', strtotime($session->response_time)) }}</td>
                                                        <td data-label="Queue Patients">{{ $session->queue }}</td>
                                                        <td data-label="Response time">
                                                            @php
                                                                $response_time = strtotime($session->response_time) - strtotime($session->invite_time);
                                                                echo $response_time > 0 ? sprintf('%02d:%02d', intdiv($response_time, 60), $response_time % 60) : '-';
                                                            @endphp
                                                        </td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center bg-grey">No Session History</td>
                                                    </tr>
                                                    @endforelse


                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                {{-- /////////////// --}}
                                <div class="tab-pane fade" id="nav-payment" role="tabpanel" aria-labelledby="nav-payment-tab">
                                    <div class="row m-auto">
                                        @if($doctor->id=='6')
                                            <div class="wallet-table">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                        <th scope="col">Type</th>
                                                        <th scope="col">Date</th>
                                                        <th scope="col">Earning</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td data-label="Type">Ahmer</td>
                                                            <td data-label="Date">02-01-2022</td>
                                                            <td data-label="Earning">$20.00</td>
                                                        </tr>
                                                        <!-- <tr>
                                                            <td colspan="3" class="text-center bg-grey">No Payment
                                                                History</td>
                                                        </tr> -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <div class=" mb-3 mt-3">
                                                <div style="text-transform:uppercase" class="text-center fw-bold">
                                                    No payment History
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>


    </div>
    <!-- ------------------Block-Doctor-Modal-start------------------ -->

    <!-- Modal -->
    <div class="modal fade" id="deactivate_doctor" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Deactivate Doctor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="GET" id="deactive_form">
                    @csrf
                    <div class="modal-body">
                        <div class="delete-modal-body">
                            Are you sure you want to Deactivate this Doctor?
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" data-id="" class="btn btn-danger">Deactivate</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- ------------------Block-Doctor-Modal-end------------------ -->
    <!-- ------------------Send-Email-Modal-start------------------ -->

    <!-- Modal -->
    <div class="modal fade" id="send_email_modal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Send Email</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form name="send_email" id="send_email" action="/doctors/send_mail" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="email" value={{ $doctor->id }}>
                    <div class="modal-body">


                        <div class="p-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="specialInstructions">To</label>
                                    <input type="text" class="form-control" id="email_to" name="email"
                                        placeholder="xyx@gmail.com" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="specialInstructions">Subject</label>
                                    <input type="text" class="form-control" name="subject"
                                        placeholder="Enter Subject" required>
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-md-12">
                                    <label for="email_body">Email Body</label>
                                    <textarea class="form-control" id="email_body" required name="ebody" rows="3" placeholder="Type your email message"></textarea>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" id="cancel" class="btn btn-secondary"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn process-pay">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- ------------------Send-Email-Modal-end------------------ -->

    <!-- ------------------Change-Percentage-Modal-start------------------ -->

    <!-- Modal -->
    <div class="modal fade" id="change_percentage" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Change Percentage</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form action="">
                        <div class="p-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="add_percentage">Add Percentage</label>
                                    <input type="text" class="form-control" placeholder="10.00%">
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn process-pay">Submit</button>
                </div>
            </div>
        </div>
    </div>


    <!-- ------------------Block-Doctor-Modal-start------------------ -->

    <!-- Modal -->
    <div class="modal fade" id="deactivate_doctor" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Deactivate Doctor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="delete-modal-body">
                        Are you sure you want to Deactivate this Doctor?
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger">Deactivate</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>


    <!-- ------------------Block-Doctor-Modal-end------------------ -->
    <!-- ------------------Send-Email-Modal-start------------------ -->

    <!-- Modal -->
    <div class="modal fade" id="send_email" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Send Email</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form action="">
                        <div class="p-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="specialInstructions">To</label>
                                    <input type="text" class="form-control" placeholder="xyx@gmail.com">
                                </div>
                                <div class="col-md-6">
                                    <label for="specialInstructions">Subject</label>
                                    <input type="text" class="form-control" placeholder="Enter Subject">
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-md-12">
                                    <label for="email_body">Email Body</label>
                                    <textarea class="form-control" id="email_body" rows="3" placeholder="Type your email message"></textarea>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn process-pay">Send</button>
                </div>
            </div>
        </div>
    </div>


    <!-- ------------------Send-Email-Modal-end------------------ -->
@endsection
