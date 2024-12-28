@extends('layouts.dashboard_doctor')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon" >
@endsection

@section('page_title')
    <title>UHCS - Doctor Dashboard</title>
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
      <div class="card first-card-wrap">
        <div class="card-body">
          <div class="first-card-content">
            <p>Welcome to</p>
          <h1>Community Health Care Clinics</h1>
      </div>
          <div class="first-card-img-div">
              {{-- <img src="{{ asset('assets/images/logo.png') }}" alt="" height="auto" width="200"> --}}
            </div>
        </div>


      </div>
  </div>
</div>
<div class="row m-auto">
  <div class="col-md-12">
      <div class="row my-4">
          <div class="col-sm-6 col-lg-3 mb-2">
              <div class="dashboard-small-card-wrap">
                <a href="/all/patients">
                  <div class="d-flex dashboard-small-card-inner">
                  <i class="fa-solid fa-hospital-user"></i>
                      <div>
                          <h6>All patients</h6>
                          <p>{{ $totalPatient }}</p>
                      </div>

                  </div>
                </a>
              </div>
          </div>
          <div class="col-sm-6 col-lg-3 mb-2">
              <div class="dashboard-small-card-wrap">
                <a href="/doctor/appointments">
                  <div class="d-flex dashboard-small-card-inner">
                  <i class="fa-regular fa-calendar-check"></i>
                      <div>
                          <h6>Upcoming Appointments</h6>
                          <p>{{ $totalPendingAppoint }}</p>
                      </div>

                  </div>
                </a>
              </div>
          </div>
          <div class="col-sm-6 col-lg-3 mb-2">
              <div class="dashboard-small-card-wrap">
                <a href="/all/sessions">
                  <div class="d-flex dashboard-small-card-inner">
                  <i class="fa-solid fa-laptop-medical"></i>
                      <div>
                          <h6>Total Sessions</h6>
                          <p>{{ $totalSessions }}</p>
                      </div>

                  </div>
                </a>
              </div>
          </div>
          <div class="col-sm-6 col-lg-3 mb-2">
              <div class="dashboard-small-card-wrap">
                <a href="/doctor/wallet">
                  <div class="d-flex dashboard-small-card-inner">
                  <i class="fa-solid fa-sack-dollar"></i>
                      <div>
                          <h6>Total Earnings</h6>
                          <p>Rs. {{ $totalEarning }}</p>
                      </div>

                  </div>
                </a>
              </div>
          </div>
      </div>
  </div>
</div>

<div class="row m-auto">
  <div class="col-md-12">
    <h5>Upcoming Appointments</h5>
      <div class="dashboard-table table-responsive">
          <table class="table">
              <thead>
                <tr>
                  <th scope="col">Name</th>
                  <th scope="col">Date</th>
                  <!-- <th scope="col">Reason of Appointment</th> -->
                  <th scope="col">Time</th>
                  <!-- <th scope="col">Status</th> -->
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                @forelse($appoints as $app)
                <tr>
                  <td  data-label="Name" scope="row">{{ $app->patient_name }}</td>
                  <td data-label="Date">{{ $app->date }}</td>
                  <!-- <td data-label="Reason of Appointment">{{ $app->problem }}</td> -->
                  <td data-label="Time">{{ $app->time }}</td>
                  <!-- <td data-label="Status">{{ $app->status }}</td> -->
                  <td data-label="Action">
                    @if($app->status=='complete')
                    <button onclick="location.href='{{route('appointment.view',['id'=>$app->id])}}'" id="view_{{$app->id}}"
                        class="view_btn btn btn-raised btn-primary btn-sm waves-effect">View</button>
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
                  <td colspan="4">
                          <div class="m-auto text-center for-empty-div">
                            <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                            <h6> No Appointments Pending</h6>
                          </div>
                        </td>
                </tr>
                @endforelse
              </tbody>
            </table>
            <div class="row d-flex justify-content-center">
                <div class="paginateCounter">
                    {{ $appoints->links('pagination::bootstrap-4') }}
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
