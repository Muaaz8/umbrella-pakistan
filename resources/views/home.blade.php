@extends('layouts.admin')
@section('css')
    <style>
        .switch input:checked+span {
            background-color: #364d81;
        }

        .switch input:not(:checked)+span {
            background-color: grey;
        }

        .alertbox {
            margin: 10px !important;
            padding-left: 20px !important;
            padding-right: 20px !important;
            width: 80%;
        }

    </style>

@endsection
@section('content')
    <section class="content home">
        <div class="container-fluid">
            <div class="block-header row clearfix col-md-12">
                <div class="col-md-12">
                    <h2>Umbrella Health Care Systems</h2>

                    <small class="text-muted">Welcome to Umbrella Health Care Systems</small>
                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger">
                                <strong>Danger!</strong> {{ $error }}
                            </div>
                        @endforeach
                    @endif
                    @if (\Session::has('message'))
                        <div class="alert alert-success">
                            <ul>
                                <li>{!! \Session::get('message') !!}</li>
                            </ul>
                        </div>
                    @endif

                </div>


                <input hidden value="{{ $currentRole }}" id="user_type">
                @if ($currentRole == 'patient')
                    @if ($pending_feedback['status'] == '0')
                        <input hidden value="{{ $pending_feedback['status'] }}" id="feedback">
                    @elseif($pending_feedback['status']=='1')
                        <input hidden value="{{ $pending_feedback['status'] }}" id="feedback">
                        <input hidden value="{{ $pending_feedback['session']['id'] }}" id="session_id">
                        <input hidden value="{{ $pending_feedback['session']['doc_name'] }}" id="session_doc_nam">

                    @endif
                @endif

                @if ($currentRole == 'doctor')
                    <div class="col-md-12 pt-2 d-flex justify-content-end">
                        <!-- Rounded switch -->
                        <h5 id="status" class="mr-2 mt-1" style="color:#13376C;"></h5>
                        <label class="switch mb-0">
                            <input id="status_check" type="checkbox" stlye="color:#13376C;" name="status">
                            <span class="slider round"></span>
                        </label>
                    </div>
                @endif
            </div>
            @if (!empty($msg))
                <span id="msg_text">{{ $msg }}</span>
            @endif
            <div id="msg_alert" class="row clearfix jsdemo-notification-button">
                <div class="col-sm-12 col-md-6 col-lg-3">
                    <button id="msg_btn" type="button" hidden="" class="btn btn-raised bg-red btn-block waves-effect"
                        data-placement-from="bottom" data-placement-align="left" data-animate-enter="" data-animate-exit=""
                        data-color-name="bg-red"> RED </button>
                </div>
            </div>


            <div class="row clearfix">
                <div class="col-lg-3 col-md-3 col-sm-6">
                    @if ($currentRole == 'doctor')
                        <a href="/patients">
                            <div class="info-box-4 hover-zoom-effect">
                                <div class="icon"> <i class="zmdi zmdi-account col-blue"></i> </div>
                                <div class="content">
                                    <div class="text">All Patient</div>
                                    <div class="number">{{ $totalPatient }}</div>

                                </div>
                            </div>
                        </a>
                    @elseif($currentRole=='admin')
                        <div class="info-box-4 hover-zoom-effect">
                            <div class="icon"> <i class="zmdi zmdi-account col-blue"></i> </div>
                            <div class="content">
                                <div class="text">Total Doctors</div>
                                <div class="number">{{ $totalDoc }}</div>
                            </div>
                        </div>
                    @elseif($currentRole=='patient')

                        <!-- <div class="info-box-4 hover-zoom-effect">
                            <div class="icon"> <i class="zmdi zmdi-account col-blue"></i> </div>
                            <div class="content">
                                <div class="text">Pending Appointments</div>
                                <div class="number">{{ $app_count }}</div>
                            </div>
                        </div> -->
                    @endif

                </div>

                @if ($message = Session::get('success'))
                    <div class="alert alertbox alert-success alert-block" id="popup">
                        <button type="button" class="close" data-dismiss="alert">x</button>
                        <strong>{{ $message }}</strong>
                    </div>
                @endif
                <div class="col-lg-3 col-md-3 col-sm-6">
                    @if ($currentRole == 'doctor')
                        <a href="/all_appointments">
                            <div class="info-box-4 hover-zoom-effect">
                                <div class="icon"> <i class="zmdi zmdi-account col-blue"></i> </div>
                                <div class="content">
                                    <div class="text">Pending Appointments</div>
                                    <div class="number">{{ $totalPendingAppoint }}</div>
                                </div>
                            </div>
                        </a>
                    @elseif($currentRole=='admin')
                        <div class="info-box-4 hover-zoom-effect">
                            <div class="icon"> <i class="zmdi zmdi-account col-blue"></i> </div>
                            <div class="content">
                                <div class="text">Total Patients</div>
                                <div class="number">{{ $totalPatient }}</div>
                            </div>
                        </div>
                    @elseif($currentRole=='patient')
                        <!-- <div class="info-box-4 hover-zoom-effect">
                            <div class="icon"> <i class="zmdi zmdi-account col-blue"></i> </div>
                            <div class="content">
                                <div class="text">Total Appointments(this month)</div>
                                <div class="number">{{ $monthTotalAppoint }}</div>
                            </div>
                        </div> -->
                    @endif
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6">
                    @if ($currentRole == 'doctor')
                        <a href="/sessions_record">
                            <div class="info-box-4 hover-zoom-effect">
                                <div class="icon"> <i class="zmdi zmdi-bug col-blue"></i> </div>
                                <div class="content">
                                    <div class="text">Total Sessions</div>
                                    <div class="number">{{ $totalSessions }}</div>
                                </div>
                            </div>
                        </a>
                    @elseif($currentRole=='admin')
                        <div class="info-box-4 hover-zoom-effect">
                            <div class="icon"> <i class="zmdi zmdi-bug col-blue"></i> </div>
                            <div class="content">
                                <div class="text">Pending Doctor Request</div>
                                <div class="number">{{ $totalpendingdoc }}</div>
                            </div>
                        </div>

                    @elseif($currentRole=='patient')
                        <!-- <div class="info-box-4 hover-zoom-effect">
                            <div class="icon"> <i class="zmdi zmdi-bug col-blue"></i> </div>
                            <div class="content">
                                <div class="text">Pending Purchases</div>
                                <div class="number">{{ $pendingPerchases }}</div>
                            </div>
                        </div> -->

                    @endif
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6">
                    @if ($currentRole == 'doctor')
                        <a href="/wallet_page">
                            <div class="info-box-4 hover-zoom-effect">
                                <div class="icon"> <i class="zmdi zmdi-balance col-blue"></i> </div>
                                <div class="content">
                                    <div class="text">Total Earning(this month)</div>
                                    <div class="number">$ @convert($totalEarning)</div>

                                </div>
                            </div>
                        </a>
                    @elseif($currentRole=='admin')
                        <div class="info-box-4 hover-zoom-effect">
                            <div class="icon"> <i class="zmdi zmdi-balance col-blue"></i> </div>
                            <div class="content">
                                <div class="text">Total Earnings (this month)</div>
                                <div class="number">$ @convert($totalBalance)</div>
                            </div>
                        </div>
                    @elseif($currentRole=='patient')
                        <!-- <div class="info-box-4 hover-zoom-effect">
                            <div class="icon"> <i class="zmdi zmdi-balance col-blue"></i> </div>
                            <div class="content">
                                <div class="text">Total Purchases(this month)</div>
                                <div class="number">$ @convert($grandTotal)</div>
                            </div>
                        </div> -->
                    @endif
                </div>
            </div>
            <div class="row clearfix">
                @if ($currentRole == 'doctor')
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="card">
                            <div class="header">
                                <h2>Coming Appointments <small>All recent patients</small> </h2>
                            </div>
                            <div class="body table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Reason of Appointment</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($appoints as $app)
                                            <tr>
                                                <td>{{ $app->patient_name }}</td>
                                                <td>{{ $app->problem }}</td>
                                                <td>{{ $app->time }}</td>
                                                {{-- @php
                                                    $time = date('h:i:a', ($app->time));
                                                @endphp --}}
                                                <td>{{ $app->time }}</td>
                                                <td class="text-capitalized">{{ ucwords($app->status) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5">
                                                    <center>No Coming Appointments</center>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
            </div>
        @elseif($currentRole=='admin')
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="header">
                        <h2>Pending Doctor Requests <small>All recent requests</small> </h2>
                    </div>
                    <div class="body table-responsive">
                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>State</th>
                                    <th>Registered On</th>
                                    <th>
                                        <center>Action</center>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($doctors as $doctor)
                                    <tr>
                                        <td>{{ \App\User::getName($doctor->id) }}</td>
                                        <td>{{ $doctor->state }}</td>
                                        {{-- @php
                                            $time = date('h:i A', strtotime($doctor->created_at));
                                        @endphp --}}
                                        {{-- <td>{{date('m/d/Y', strtotime ($doctor->created_at)) . " " .$time}}</td> --}}
                                        <td>{{date('m/d/Y h:i A', strtotime ($doctor->time))}}</td>

                                        <td>
                                            <center>
                                                <a href="{{ route('pending_doctor_detail', $doctor->id) }}">
                                                    <button class="btn callbtn">Details</button>
                                                </a>
                                                <!-- <a href="{{ route('approve_doctor', $doctor->id) }}">
                                                    <button class="btn btn-raised g-bg-blue">Approve</button>
                                                </a>
                                                <a href="{{ route('decline_doctor', $doctor->id) }}">
                                                    <button class="btn btn-raised g-bg-grey">Decline</button>
                                                </a> -->
                                            </center>

                                        </td>

                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4"><center>No Pending Requests</center></td>
                                    </tr>
                                @endforelse
                                <!-- <tr>
                                                        <td>Tiger Nixon</td>
                                                        <td>Maryland</td>
                                                        <td>
                                                            <center>
                                                                <a href="#">
                                                                    <button class="btn btn-raised g-bg-cyan">Details</button></a>
                                                                <button class="btn btn-raised g-bg-blue">Approve</button>
                                                                <button class="btn btn-raised g-bg-grey">Decline</button>
                                                            </center>

                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td>Garrett Winters</td>
                                                        <td>Minnesota</td>
                                                        <td>
                                                            <center>
                                                                <a href="#">
                                                                    <button class="btn btn-raised g-bg-cyan">Details</button></a>
                                                                <button class="btn btn-raised g-bg-blue">Approve</button>
                                                                <button class="btn btn-raised g-bg-grey">Decline</button>
                                                            </center>

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Ashton Cox</td>
                                                        <td>Montana</td>
                                                        <td>
                                                            <center>
                                                                <a href="#">
                                                                    <button class="btn btn-raised g-bg-cyan">Details</button></a>
                                                                <button class="btn btn-raised g-bg-blue">Approve</button>
                                                                <button class="btn btn-raised g-bg-grey">Decline</button>
                                                            </center>

                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td>Jennifer Acosta</td>
                                                        <td>Ohio</td>
                                                        <td>
                                                            <center>
                                                                <a href="#">
                                                                    <button class="btn btn-raised g-bg-cyan">Details</button></a>
                                                                <button class="btn btn-raised g-bg-blue">Approve</button>
                                                                <button class="btn btn-raised g-bg-grey">Decline</button>
                                                            </center>

                                                        </td>

                                                    </tr> -->

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @elseif($currentRole=='patient')
            <div class="col-lg-12 col-md-12 col-sm-12" id="loadPatientAppointmentHomePage">
                @if ($reschedule_appointment != null)
                    @foreach ($reschedule_appointment as $r_a)
                        <div class="col-12 p-3" style="background-color:white;">
                            <h5>Please Reschedule Appointment</h5>
                            <p> On {{ date('F dS Y', strtotime($r_a->date)) }} Due To Dr.{{ $r_a->doctor_name }} Unavailability Your Appointment Cancelled. Do You Want To Reschedule Your Appointment
                                <a href="{{ route('reschedule.appointment',['app_id'=> $r_a->id,'spec_id'=>$r_a->spec_id]) }}"> Click Here</a>
                            </p>
                        </div>
                        <br>
                    @endforeach
                @endif
                <div class="card">

                    <div class="header">
                        <h2>Upcoming Appointment
                            <!-- <small>All recent appointment</small>  -->
                        </h2>
                    </div>
                    <div class="body table-responsive">
                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                            <thead>
                                <tr>
                                    <th>Doctor Name</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($appoint as $app)
                                    <tr>
                                        <td>{{ ucwords($app->doctor_name) }}</td>

                                        <td>{{ date('m-d-Y', strtotime($app->date)) }}</td>
                                        @php
                                            $time = date('h:i:a', strtotime($app->time));
                                        @endphp
                                        <td>{{ $time }}</td>
                                        <td>{{ ucwords($app->status) }}</td>
                                        <td>
                                        @if($app->status!='cancelled')
                                        <button onclick="window.location.href='/cancel_appointment/{{$app->id}}'" class="btn btn-raised btn-danger btn-sm waves-effect">Cancel</button>
                                        @endif
                                        @if($app->join_enable=="1")
                                        <button onclick="window.location.href='/waiting_room/{{$app->sesssion_id}}'" class="btn btn-raised btn-info btn-sm waves-effect">Join</button>
                                        @else
                                        <input type="hidden" value="{{ Auth::user()->id }}" id="user_id">
                                        <button onclick="window.location.href='/waiting_room/{{$app->sesssion_id}}'" class="btn btn-raised btn-info btn-sm waves-effect" id="session{{ $app->sesssion_id }}" style="display:none">Join</button>

                                        @endif
                                    </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">
                                            <center>No Upcoming Appointments</center>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
        @if ($currentRole == 'doctor')
            <!-- <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="card">
                                        <div class="header">
                                            <h2>Appointments <small>Only from last 30 days</small> </h2>
                                        </div>
                                        <div class="body table-responsive">
                                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                                <thead>
                                                    <tr>
                                                        <th>Patient Name</th>
                                                        <th>Earning</th>
                                                        <th>Patient Remarks</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <tr>
                                                        <td>Sara Khan</td>
                                                        <td>$35.4</td>
                                                        <td>Feeling better already.</td>

                                                    </tr>
                                                    <tr>
                                                        <td>Tiger Nixon</td>
                                                        <td>$40.5</td>
                                                        <td>Detailed checkup for my sore throat</td>

                                                    </tr>
                                                    <tr>
                                                        <td>Garrett Winters</td>
                                                        <td>$40.5</td>
                                                        <td>Covered maximum stuff in precise session</td>

                                                    </tr>
                                                    <tr>
                                                        <td>Ashton Cox</td>
                                                        <td>$50.5</td>
                                                        <td>Feeling better already.</td>

                                                    </tr>
                                                    <tr>
                                                        <td>Jennifer Acosta</td>
                                                        <td>$25.5</td>
                                                        <td>Feeling better already.</td>

                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="#"><button type="submit" class="btn btn-raised g-bg-cyan">View
                                                    All</button></a>

                                        </div>
                                    </div>
                                </div> -->
        @elseif($currentRole=='admin')
            <!-- <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="card">
                                        <div class="header">
                                            <h2>Pending Pharmacy Requests <small>All recent requests</small> </h2>
                                        </div>
                                        <div class="body table-responsive">
                                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>State</th>
                                                        <th>
                                                            <center>Action</center>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Sara Khan</td>
                                                        <td>New York</td>
                                                        <td>
                                                            <center>
                                                                <a href="#">
                                                                    <button class="btn btn-raised g-bg-cyan">Details</button></a>
                                                                <button class="btn btn-raised g-bg-blue">Approve</button>
                                                                <button class="btn btn-raised g-bg-grey">Decline</button>
                                                            </center>

                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td>Tiger Nixon</td>
                                                        <td>Maryland</td>
                                                        <td>
                                                            <center>
                                                                <a href="#">
                                                                    <button class="btn btn-raised g-bg-cyan">Details</button></a>
                                                                <button class="btn btn-raised g-bg-blue">Approve</button>
                                                                <button class="btn btn-raised g-bg-grey">Decline</button>
                                                            </center>

                                                        </td>

                                                    </tr>


                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div> -->
            </div>
        @elseif($currentRole=='patient')
            <!-- <div class="col-lg-12 col-md-12 col-sm-12">
                                            <div class="card">
                                                <div class="header">
                                                    <h2>Previous Appointments <small>Only from last 30 days</small> </h2>
                                                </div>
                                                <div class="body table-responsive">
                                                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                                        <thead>
                                                            <tr>
                                                                <th>Appoint. No</th>
                                                                <th>Doctor Name</th>
                                                                <th>Payment</th>
                                                                <th><center>Action</center></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                             <tr>
                                                                <td>MD12</td>
                                                                <td>Sara Khan</td>
                                                                <td>$35.4</td>
                                                                <td>

                                                                    <center>
                                                            <a href="#">
                                                                        <button class="btn btn-raised g-bg-cyan">Recommendations</button></a>
                                                                    <button class="btn btn-raised g-bg-blue">Message Doctor (3)</button>
                                                                    </center>

                                                                </td>

                                                            </tr>
                                                            <tr>
                                                                <td>MD32</td>
                                                                <td>Tiger Nixon</td>
                                                                <td>$40.5</td>
                                                                <td>

                                                                    <center>
                                                            <a href="#">
                                                                        <button class="btn btn-raised g-bg-cyan">Recommendations</button></a>
                                                                    <button class="btn btn-raised g-bg-blue">Message Doctor (3)</button>
                                                                    </center>

                                                                </td>

                                                            </tr>
                                                            <tr>
                                                                <td>MD58</td>
                                                                <td>Garrett Winters</td>
                                                                <td>$40.5</td>
                                                                <td>

                                                                    <center>
                                                            <a href="#">
                                                                        <button class="btn btn-raised g-bg-cyan">Recommendations</button></a>
                                                                    <button class="btn btn-raised g-bg-blue">Message Doctor (3)</button>
                                                                    </center>

                                                                </td>

                                                            </tr>
                                                            <tr>
                                                                <td>MD53</td>
                                                                <td>Ashton Cox</td>
                                                                <td>$50.5</td>
                                                                <td>

                                                                    <center>
                                                            <a href="#">
                                                                        <button class="btn btn-raised g-bg-cyan">Recommendations</button></a>
                                                                    <button class="btn btn-raised g-bg-blue">Message Doctor (3)</button>
                                                                    </center>

                                                                </td>

                                                            </tr>
                                                            <tr>
                                                                <td>MD32</td>
                                                                <td>Jennifer Acosta</td>
                                                                <td>$25.5</td>
                                                                <td>

                                                                    <center>
                                                            <a href="#">
                                                                        <button class="btn btn-raised g-bg-cyan">Recommendations</button></a>
                                                                    <button class="btn btn-raised g-bg-blue">Message Doctor (3)</button>
                                                                    </center>

                                                                </td>

                                                            </tr>

                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="col-md-3">
                                                    <a href="#"><button type="submit" class="btn btn-raised g-bg-cyan">View All</button></a>

                                                </div>
                                                </div>
                                            </div>
                                        </div> -->
        @endif
        </div>
    </section>
    <!-- Add Pharmacy Modal -->
    <div class="modal fade m-5 p-5" id="feedback_modal" style="font-weight: normal; " tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg mt-0" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Last Session Feedback</h4>
                    <button id="close_add_phar"
                        class="btn p-1 btn-circle waves-effect waves-circle waves-float"
                        style="color:black;font-size:22px" data-dismiss="modal">&times;</button>

                </div>
                <div class="modal-body row">
                    <div class="container ">


                        <form method="post" action="{{ route('feedback_submit') }}">
                            @csrf
                            <center>
                                <p class="offset-3"><b>How satisfied are you with the quality of service provided</b>
                                </p>
                                <div class="col-md-12">
                                    <i style="font-size:45px" id="fb_1" class="col-md-1 smiley far fa-angry"></i>
                                    <i style="font-size:45px" id="fb_2" class="col-md-1 smiley far fa-frown"></i>
                                    <i style="font-size:45px" id="fb_3" class="col-md-1 smiley far fa-meh"></i>
                                    <i style="font-size:45px" id="fb_4" class="col-md-1 smiley far fa-smile"></i>
                                    <i style="font-size:45px" id="fb_5" class="col-md-1 smiley far fa-laugh-beam"></i>
                                </div>
                                <input id="feedback" hidden="" name="feedback">
                                @if ($currentRole == 'patient')
                                    @if ($pending_feedback['status'] == '1')
                                        <input id="hid_session_id" hidden=""
                                            value="{{ $pending_feedback['session']['id'] }}" name="session_id">
                                    @endif
                                @endif
                                <div class="form-group col-md-6">
                                    <div class="form-line">
                                        <input class="form-control" type="text" name="suggestions" required
                                            placeholder="Write any suggestions or feedback">
                                    </div>
                                </div>
                                <!-- doctor rating -->
                                <p class="offset-3"><b>Rate your Doctor</b></p>
                                <div class="col-md-12 mb-2">
                                    <i style="font-size:40px" id="rt_1" class="far fa-star"></i>
                                    <i style="font-size:40px" id="rt_2" class="far fa-star"></i>
                                    <i style="font-size:40px" id="rt_3" class="far fa-star"></i>
                                    <i style="font-size:40px" id="rt_4" class="far fa-star"></i>
                                    <i style="font-size:40px" id="rt_5" class="far fa-star"></i>
                                </div>
                                <input id="rating" hidden="" name="rating">
                            </center>

                            <div class="col-md-6 offset-3">
                                <input id="check_1" type="checkbox" class="m-1 form-check-input" checked=""><label
                                    class="form-check-label" for="check_1">Doctor listened to my issue</label>
                            </div>
                            <div class="col-md-6 offset-3">
                                <input id="check_2" type="checkbox" class="m-1 form-check-input" checked=""><label
                                    class="form-check-label" for="check_2">Doctor guided me about treatment and
                                    alternatives</label>
                            </div>
                            <div class="col-md-6 offset-3">
                                <input id="check_3" type="checkbox" class="m-1 form-check-input" checked=""><label
                                    class="form-check-label" for="check_3">Quality of call was good</label>
                            </div>
                            <center><button class="btn btn-primary offset-3 btn-raised col-md-6">Submit</button></cdnter>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="asset_admin/js/pages/index.js"></script>
    <script src="asset_admin/js/pages/charts/sparkline.min.js"></script>
    <script type="text/javascript">
        $("#popup").show();
        setTimeout(function() {
            $("#popup").hide();
        }, 5000);
        <?php header('Access-Control-Allow-Origin: *'); ?>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#feedback_modal').on('hidden.bs.modal', function() {
            id = $('#hid_session_id').val();
            $.ajax({
                type: 'POST',
                url: "{{ URL('update_feedback_status') }}",
                data: {
                    id: id,
                },
                success: function(response) {
                    // console.log(response);
                }
            })
        })
        $(document).ready(function() {
            $.ajax({
                type: 'GET',
                url: "{{ URL('/check_status') }}",
                data: {},
                success: function(status) {
                    if (status == 'online') {
                        $('#status').text('Online');
                        $('#status').css('color', '#364d81');
                        $('#status_check').prop('checked', true);
                    } else if (status == 'offline') {
                        $('#status').text('Offline');
                        $('#status').css('color', 'grey');
                        $('#status_check').prop('checked', false);
                    }
                }
            });
            if ($('#msg_text').text() != '') {
                $('#msg_btn').trigger("click");
            }
        });
        //doctor status change
        $('input[type="checkbox"]').click(function() {
            $.ajax({
                type: 'POST',
                url: "{{ URL('/change_status') }}",
                success: function(response) {
                    if(response=='online'){
                        $(this).checked=true;
                        $('#status_check').prop('checked', true);
                        $('#status').text('Online');
                        $('#status').css('color', '#364d81');
                    }else{
                        $(this).checked=false;
                        $('#status_check').prop('checked', false);
                        $('#status').text('Offline');
                        $('#status').css('color', 'grey');
                    }
                }
            });
            if ($(this).is(":checked")) {
                $('#status').text('Online');
                $('#status').css('color', '#364d81');
            } else if ($(this).is(":not(:checked)")) {
                $('#status').text('Offline');
                $('#status').css('color', 'grey');
            }
        });

        $(document).ready(function() {
            $.ajax({
                url: "/get_cart_counter",
                type: "GET",
                processData: false,
                contentType: false,
                success: function(response) {
                    var result = JSON.parse(response);
                    $(".cart_counter").html(result);
                },
            });
        });
    </script>
    <script>
        var note_id = $('#not_id').attr('class');

        function change_doctor(app_id) {
            console.log(app_id);
            $.ajax({
                type: 'POST',
                url: '/another_doctor',
                data: {
                    app_id: app_id,
                },
                success: function(new_appointment) {
                    console.log(new_appointment);

                    return new_appointment;
                    // location.reload();
                }
            });
        }
        $("#notee" + note_id).click(function() {
            app_id = $('#not_app_id').attr('class');
            if ($('#not_type').attr('class') == 'moved_appointment') { //for type moved appointment
                Swal.fire({
                    title: 'Appointment Update',
                    text: 'Due to an emergency, doctor will be unable to attend sessions with patients',
                    showCancelButton: true,
                    confirmButtonColor: '#BD122E',
                    cancelButtonColor: '#24C613',
                    confirmButtonText: 'Reschedule Appointment with same doctor',
                    cancelButtonText: 'Visit another doctor on same time'


                }).then((result) => {
                    if (result.value) { //if move appointment
                        //book appointment page
                        window.location.href = '/book/appointment';

                    } else { //if visit another doc
                        //system
                        // console.log(app_id);

                        var doc = change_doctor(app_id);
                        Swal.fire({
                            title: 'Appointment Moved',
                            icon: 'success',
                            text: 'Your appointment is moved to 2020-09-28 at 09:10am',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK',
                        });
                    }
                });
            } else if ($('#not_type').attr('class') == 'session_created') {
                session_id = $(this).attr('class');
                window.location.href = '/video?session=' + session_id;

            } else {
                var data = note_id;
                $.ajax({
                    url: '/noteUpdate',
                    type: "get",
                    data: {
                        id: data
                    },
                    success: function(response) {

                    }
                });
            }
        });
    </script>
    <script>
        // let stateCheck = setInterval(() => {
        //   if (document.readyState === 'complete') {
        //     clearInterval(stateCheck);

        //   }
        // }, 100);


        // if (document.readyState === 'complete') {
        //   console.log('1');
        // }
        // $(document).load(function(){
        //   console.log('2');

        // })
        // $(window).onload(function(){
        //     console.log('1234');
        // })
    </script>
    <script>
        $(document).ready(function() {
            type = $('#user_type').val();
            if (type == 'patient') {
                feedback = $('#feedback').val();

                if (feedback == '1') {
                    $('#feedback_modal').modal('show');
                }
            }
        })
        $('.smiley').hover(function() {
            classes = $(this).attr('class');
            sp_class = classes.split(' ');
            $(this).attr('class', sp_class[0] + ' ' + sp_class[1] + ' fas ' + sp_class[3]);
        });
        $('.smiley').mouseout(function() {
            classes = $(this).attr('class');
            sp_class = classes.split(' ');
            $(this).attr('class', sp_class[0] + ' ' + sp_class[1] + ' far ' + sp_class[3]);
        });
        $('.smiley').click(function() {
            // classes=$(this).attr('class');
            // sp_class=classes.split(' ');
            // // foreach($('.smiley').attr('class') as aclass){
            // //     sp_aclass=aclass.split(' ');
            // //     $(this).attr('class',sp_aclass[0]+' '+sp_aclass[1]+' far '+sp_aclass[3])

            // // }
            // // $('.smiley').attr('class',sp_class[0]+' '+sp_class[1]+' far ')
            $(this).prevUntil().css('color', 'black');
            id = $(this).attr('id');
            id_sp = id.split('_');
            $('#feedback').val(id_sp[1]);
            // $(this).attr('class',sp_class[0]+' '+sp_class[1]+' fas '+sp_class[3]);
            $(this).css('color', 'orange');
            $(this).nextAll().css('color', 'black');

        });
        $('.fa-star').click(function() {
            $(this).prevUntil().css('color', 'orange');
            $(this).prevUntil().removeClass('far');
            $(this).prevUntil().addClass('fa');
            $(this).css('color', 'orange');
            $(this).removeClass('far');
            $(this).addClass('fa');
            $(this).nextAll().css('color', 'black');
            $(this).nextAll().removeClass('fa');
            $(this).nextAll().addClass('far');
            id = $(this).attr('id');
            id_sp = id.split('_');
            $('#rating').val(id_sp[1]);

        });
        Echo.channel('load_appointment_patient_in_queue')
            .listen('LoadAppointmentPatientInQueue', (e) => {
                $('#loadPatientAppointmentHomePage').load("{{ url('/load_all_appointments_home_page') }}");
        });
    </script>
@endsection
