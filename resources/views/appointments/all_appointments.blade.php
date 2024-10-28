{{-- {{dd($appointments)}} --}}
@extends('layouts.admin')
@section('css')
<link href="asset_admin/plugins/sweetalert/sweetalert.css" rel="stylesheet" />
@endsection
@section('content')
<style>
    .page-item {
        margin: 5px !important;
        border-radius: 5px !important
    }
    .page-link {
        color: #111 !important;
    }
    .paginateCounter .page-item.active .page-link,
    .paginateCounter .page-item .page-link:hover {
        background: #0c3b81 !important;
        color: #fff !important;
    }
</style>
<section class="content" id="allAppointmentLoad">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Umbrella Health Care Systems</h2>
            <!-- <small class="text-muted">All the appointments schedule to you are listed here</small> -->
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                @if(Auth::user()->user_type=='patient')
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
                @endif
                <div class="card">
                    <div class="header">
                        <h2>All Appointments<small>All the appointments scheduled are listed here</small> </h2>
                    </div>
                    <div class="body table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover js-basic-example dataTable js-sweetalert">
                            @if(Auth::user()->user_type=='admin')
                            <thead>
                                <tr>
                                    <th>S.NO</th>
                                    <th>Patient Name</th>
                                    <th>Doctor Name</th>
                                    <th>Title/Problem</th>
                                    <th>Patient Email</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($appointments as $app)
                                <tr>
                                    <td>UAP-{{$app->appointment_id}}</td>
                                    <td>{{$app->patient_name}}</td>
                                    <td>{{$app->doctor_name}}</td>
                                    <td>{{$app->problem}}</td>
                                    <td>{{$app->email}}</td>
                                    <td>{{date('m-d-Y', strtotime($app->date))}}</td>
                                    @php
                                    $time = date("h:i:a", strtotime($app->time));
                                    @endphp
                                    <td>{{$time}}</td>
                                    <td>{{ucwords($app->status)}}</td>
                                </tr>
                                @endforeach

                            </tbody>
                            @elseif(Auth::user()->user_type=='doctor')
                            <thead>
                                <tr>
                                    <th>Patient Name</th>
                                    <th>Title/Problem</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                    <th class="align-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($appointments as $app)
                                <tr>
                                    <td>{{$app->patient_name}}</td>
                                    <td>{{$app->problem}}</td>
                                    <td>{{$app->date}}</td>
                                    @php
                                    $time = date("h:i:a", strtotime($app->time));
                                    @endphp
                                    <td>{{$time}}</td>
                                    <td>{{ucwords($app->status)}}</td>
                                    <td>
                                    @if($app->status=='complete')
                                    <button onclick="location.href='{{route('appointment.view',['id'=>$app->id])}}'" id="view_{{$app->id}}"
                                            class="view_btn btn btn-raised btn-primary btn-sm waves-effect">View</button>
                                    @endif
                                    @if($app->status=='pending')
                                        @if($app->join_enable=='1')
                                        <a href="/waiting_room">
                                        <button class="btn btn-raised btn-info btn-sm waves-effect">Join</button>
                                        </a>
                                        <button onclick="window.location.href='/cancel_appointment/{{$app->id}}'" id="{{$app->id}}" class="btn btn-raised btn-danger btn-sm waves-effect">Cancel</button>
                                        @else
                                        <button onclick="window.location.href='/cancel_appointment/{{$app->id}}'" id="{{$app->id}}" class="btn btn-raised btn-danger btn-sm waves-effect">Cancel</button>
                                        @endif
                                    @endif
                                    @if($app->status=='make-reschedule')
                                        <button onclick="window.location.href='/cancel_appointment/{{$app->id}}'" class="btn btn-raised btn-danger btn-sm waves-effect">Cancel</button>
                                    @endif
                                    </td>

                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5">
                                        <center>No upcoming appointments</center>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                            @elseif(Auth::user()->user_type=='patient')
                            <input type="hidden" value="{{ Auth::user()->id }}" id="user_id">
                            <thead>
                                <tr>
                                    <th class="align-center">Doctor Name</th>
                                    <th class="align-center">Title/Problem</th>
                                    <th class="align-center">Date</th>
                                    <th class="align-center">Time</th>
                                    <th class="align-center">Status</th>
                                    <th class="align-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="loadAppointment">
                                @forelse($appointments as $app)
                                <tr >
                                    <td class="align-center p-3">{{$app->doctor_name}}</td>
                                    <td class="align-center  p-3">{{$app->problem}}</td>
                                    <td class="align-center  p-3">{{$app->date}}</td>
                                    @php
                                    $time = date("h:i:a", strtotime($app->time));
                                    @endphp

                                    <td class="align-center  p-3">{{$time}}</td>
                                    <td class="align-center p-3">
                                        {{ucwords($app->status)}}
                                    </td>
                                    <td>

                                    @if($app->status=='complete')
                                        <button onclick="window.location.href='{{route('appointment.view',['id'=>$app->id])}}'" id="view_{{$app->id}}" class="view_btn btn btn-raised btn-primary btn-sm waves-effect">View</button>
                                    @endif
                                    @if($app->status=='make-reschedule')
                                        <button onclick="window.location.href='/cancel_appointment/{{$app->id}}'" class="btn btn-raised btn-danger btn-sm waves-effect">Cancel</button>
                                    @endif
                                    @if($app->status=='pending')
                                        @if($app->join_enable=='1')
                                        <button onclick="window.location.href='/waiting_room/{{$app->sesssion_id}}'" class="btn btn-raised btn-info btn-sm waves-effect">Join</button>
                                        <button onclick="window.location.href='/cancel_appointment/{{$app->id}}'" class="btn btn-raised btn-danger btn-sm waves-effect">Cancel</button>
                                        @else
                                        <button onclick="window.location.href='/cancel_appointment/{{$app->id}}'" class="btn btn-raised btn-danger btn-sm waves-effect">Cancel</button>
                                        @endif
                                    @endif

                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6">
                                        <center>No upcoming appointments</center>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                            @endif
                        </table>
                    </div>


                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
<script src="asset_admin/js/pages/tables/jquery-datatable.js"></script>
<script src="asset_admin/plugins/bootstrap-notify/bootstrap-notify.js"></script> <!-- Bootstrap Notify Plugin Js -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="asset_admin/js/pages/ui/dialogs.js"></script>
<script src="{{ asset('js/app.js') }}"></script>
<script type="text/javascript">
$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

$('.cancel_btn').click(function() {
    var id = $(this).attr('id');
    Swal.fire({
        title: 'Are you sure?',
        text: "You want to cancel this appointment",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'No',
        confirmButtonText: 'Yes'
    }).then((result) => {
        if (result.value) {
            window.location.href = "cancel_appointment/" + id;
        }
    });

});


Echo.channel('load_appointment_patient_in_queue')
    .listen('LoadAppointmentPatientInQueue', (e) => {
      $('#allAppointmentLoad').load('<?php  echo url('/load_all_appointments');?>');
});

</script>
@endsection
