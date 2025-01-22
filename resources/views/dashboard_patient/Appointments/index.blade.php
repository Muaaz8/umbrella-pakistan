@extends('layouts.dashboard_patient')
@section('meta_tags')
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">

@endsection
@section('top_import_file')
@endsection
@section('page_title')
    <title>CHCC - All Appointments</title>
@endsection
@section('bottom_import_file')
<script src="{{asset('assets\js\searching.js')}}"></script>
@endsection
@section('content')
        <div class="dashboard-content">
          <div class="container-fluid">
            <div class="row m-auto">
              <div class="col-md-12">
                <div class="row m-auto">
                  <div class="row m-auto p-0">
                    <div class="col-md-8">
                      <h3>All Appointments</h3>
                      <p>All The Appointments Schedules Are Listed Here</p>
                    </div>
                    <div class="col-md-4 mt-md-auto mt-2 p-0">
                      <div class="input-group">
                        <input
                          type="text"
                          class="form-control"
                          id="search"
                          placeholder="Search any doctor name"
                          aria-label="Username"
                          aria-describedby="basic-addon1"
                        />
                      </div>
                    </div>
                  </div>
                  <div class="wallet-table table-responsive">
                    <table class="table" id="table">
                      <thead>
                          <th scope="col">Doctor Name</th>
                          <th scope="col">Symptoms</th>
                          <th scope="col">Appointment Id</th>
                          <th scope="col">Date</th>
                          <th scope="col">Time</th>
                          <th scope="col">Status</th>
                          <th scope="col">Action</th>
                      </thead>
                      <tbody>
                        @forelse ($appointments as $app)
                            <tr>
                            <td data-label="Doctor Name">{{$app->doctor_name}}</td>
                            <td data-label="Symptoms">{{$app->problem}}</td>
                            <td data-label="appointment_id">UAP-{{$app->appointment_id}}</td>
                            <td data-label="Date">{{$app->date}}</td>
                            <td data-label="Time">{{$app->time}}</td>
                            <td data-label="Status"><label class="badge bg-danger text-wrap">{{$app->status}}</label></td>
                            <td data-label="Action">
                                @if($app->status=='complete')
                                <button onclick="location.href='{{ route('sessionDetail', $app->sesssion_id) }}'" id="view_{{$app->id}}"
                                  class="view_btn btn btn-raised process-pay btn-sm waves-effect">View</button>
                                @endif
                                @if($app->status=='pending')
                                    @if($app->join_enable=='1')

                                    <a href="/waiting/room/{{\Crypt::encrypt($app->sesssion_id)}}">
                                    <button class="btn btn-raised btn-info btn-sm waves-effect mb-1">Join</button>
                                    </a>
                                    <button onclick="window.location.href='/patient/appointment_cancel/{{$app->id}}'" id="{{$app->id}}" class="btn btn-raised btn-danger btn-sm waves-effect mb-1">Cancel</button>
                                    @else
                                    <button onclick="window.location.href='/patient/appointment_cancel/{{$app->id}}'" id="{{$app->id}}" class="btn btn-raised btn-danger btn-sm waves-effect mb-1">Cancel</button>
                                    @endif
                                @endif
                                @if($app->status=='make-reschedule')
                                {{-- doctor_id --}}
                                    <button onclick="window.location.href='{{ url('view/doctor',['id'=>\Crypt::encrypt($app->doctor_id)]) }}'" class="btn btn-raised btn-info btn-sm waves-effect mb-1">Reschedule</button>
                                    <button onclick="window.location.href='/patient/appointment_cancel/{{$app->id}}'" id="{{$app->id}}" class="btn btn-raised btn-danger btn-sm waves-effect mb-1">Cancel</button>
                                @endif
                            </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan='7'>
                                <div class="m-auto text-center for-empty-div">
                                    <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                    <h6> No Appointments</h6>
                                </div>
                                </td>
                            </tr>
                            @endforelse
                      </tbody>
                    </table>
                    <div class="row d-flex justify-content-center">
                        <div class="paginateCounter">
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
    </div>
@endsection
