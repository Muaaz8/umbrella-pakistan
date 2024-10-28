@extends('layouts.dashboard_doctor')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>UHCS - Doctor Appointment</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
    <script>
        const mobileScreen = window.matchMedia("(max-width: 990px )");
        $(document).ready(function () {
        $(".dashboard-nav-dropdown-toggle").click(function () {
            $(this)
            .closest(".dashboard-nav-dropdown")
            .toggleClass("show")
            .find(".dashboard-nav-dropdown")
            .removeClass("show");
            $(this).parent().siblings().removeClass("show");
        });
        $(".menu-toggle").click(function () {
            if (mobileScreen.matches) {
            $(".dashboard-nav").toggleClass("mobile-show");
            } else {
            $(".dashboard").toggleClass("dashboard-compact");
            }
        });
        });
        $(document).ready(function () {
            $('#example').DataTable();
        });
    </script>
@endsection

@section('content')

        <div class="dashboard-content">
          <div class="container-fluid">
            <div class="row m-auto">
              <div class="col-md-12">



<div class="row m-auto">
    <div>
    <h3>All Appointments</h3>
    <p>All The Appointments Schedules Are Listed Here</p>
    <hr>
    </div>
  <div class="wallet-table table-responsive">
  <table id="example" class="table table-bordered ">
    <thead>
        <tr>
            <th>Patient Name</th>
            <th>Symptoms</th>
            <th>Appointment Id</th>
            <th>Date</th>
            <th>Time</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($appointments as $app)
        <tr>
          <td data-label="Patient Name">{{$app->patient_name}}</td>
          <td data-label="Symptoms">{{$app->problem}}</td>
          <td data-label="appointment_id">UAP-{{$app->appointment_id}}</td>
          <td data-label="Date">{{$app->date}}</td>
          <td data-label="Time">{{$app->time}}</td>
          <td data-label="Status" class="px-md-2"><label class="badge bg-danger text-wrap">{{ucwords($app->status)}}</label></td>
          <td data-label="Action">
            @if($app->status=='complete')
             {{--  <button onclick="location.href='{{route('appointment.view',['id'=>$app->id])}}'" id="view_{{$app->id}}"
                class="view_btn btn btn-raised btn-primary btn-sm waves-effect">View</button>  --}}
                <button onclick="location.href='{{route('sessionDetail',['id'=>$app->session_id])}}'" id="view_{{$app->id}}"
                    class="view_btn btn btn-raised process-pay btn-sm waves-effect">View</button>
            @endif
            @if($app->status=='pending')
                @if($app->join_enable=='1')
                <a href="{{ route('doctor_queue') }}">
                <button class="btn btn-raised btn-info btn-sm waves-effect">Join</button>
                </a>
                <button onclick="window.location.href='/doctor/appointment_cancel/{{$app->id}}'" id="{{$app->id}}" class="btn btn-raised btn-danger btn-sm waves-effect">Cancel</button>
                @else
                <button onclick="window.location.href='/doctor/appointment_cancel/{{$app->id}}'" id="{{$app->id}}" class="btn btn-raised btn-danger btn-sm waves-effect">Cancel</button>
                @endif
            @endif
            @if($app->status=='make-reschedule')
                <button onclick="window.location.href='/doctor/appointment_cancel/{{$app->id}}'" class="btn btn-raised btn-danger btn-sm waves-effect">Cancel</button>
            @endif
          </td>
      </tr>
      @empty
      <tr>
        <td colspan='7'>
        <div class="m-auto text-center for-empty-div">
            <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
            <h6>No Appointments</h6>
        </div>
        </td>
      </tr>
      @endforelse
    </tbody>

</table>
</div>
<div class="p-0">
    <div class="paginateCounter d-flex justify-content-end">
        {{ $appointments->links('pagination::bootstrap-4') }}
    </div>
</div>
</div>



              </div>
          </div>
          </div>
        </div>
      </div>
    </div>


@endsection
