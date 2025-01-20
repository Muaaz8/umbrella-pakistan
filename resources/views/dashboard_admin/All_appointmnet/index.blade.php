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
@endsection


@section('bottom_import_file')
@endsection

@section('content')
<div class="dashboard-content">
    <div class="container-fluid">
        <div class="row m-auto">
          <div class="col-md-12">
            <div class="row m-auto">
              <div class="d-flex justify-content-between flex-wrap align-items-end p-0">
                <div>
                  <h3>ALL APPOINTMENTS</h3>
                  <p>All The Appointments Scheduled Are Listed Here</p>
                </div>
                <div class="col-md-4 col-12 col-sm-6 p-0">
                  <div class="input-group">
                    <form ction="{{ url('/admin/all/sessions/record') }}" method="POST" style="width: 100%;">
                        @csrf
                    <input
                    type="text"
                    id="search"
                    name="name"
                    class="form-control"
                    placeholder="Search By Appointment Id"
                    aria-label="Username"
                    aria-describedby="basic-addon1"
                    />
                </form>
                </div>
                </div>
              </div>
              <div class="wallet-table table-responsive">
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">Id</th>
                      <th scope="col">Patient Name</th>
                      <th scope="col">Doctor Name</th>
                      <th scope="col">Title/Problem</th>
                      <th scope="col">Patient Email</th>
                      <th scope="col">Date</th>
                      <th scope="col">Time</th>
                      <th scope="col">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($appointments as $appoint)
                    <tr>

                      <td data-label="S.NO">UAP-{{ $appoint->appointment_id }}</td>
                      <td data-label="Patient Name">{{ $appoint->patient_name }}</td>
                      <td data-label="Doctor Name">{{ $appoint->doctor_name }}</td>
                      <td data-label="Title/Problem">{{ $appoint->problem }}</td>
                      <td data-label="Patient Email">{{ $appoint->email }}</td>
                      <td data-label="Date">{{ $appoint->date }}</td>
                      <td data-label="Time">{{ $appoint->time }}</td>
                      <td data-label="Status">{{ $appoint->status }}</td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
                {{ $appointments->links('pagination::bootstrap-4') }}
              </div>
            </div>
          </div>
        </div>
      </div>
</div>

@endsection
