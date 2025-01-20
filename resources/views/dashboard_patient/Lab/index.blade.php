@extends('layouts.dashboard_patient')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>CHCC - Lab Results</title>
@endsection

@section('top_import_file')
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
            <div class="d-flex align-items-baseline flex-wrap p-0">
              <h3>Lab Results</h3>
              <div class="col-md-4 col-12 ms-auto p-0">
                <div class="input-group">
                  <input
                    type="text"
                    id="search"
                    class="form-control"
                    placeholder="Search what you are looking for"
                    aria-label="Username"
                    aria-describedby="basic-addon1"
                  />
                </div>
              </div>
            </div>
            <div class="wallet-table table-responsive">
              <table class="table" id="table">
                <thead>
                    <th scope="col">Test Names</th>
                    @if(auth()->user()->user_type=='patient')
                        <th scope="col">Provider Name</th>
                    @else
                        <th scope="col">Patient Name</th>
                    @endif
                    <th scope="col">Result Status</th>
                    <th scope="col">Specimen Collection Date</th>
                    <th scope="col">Result Date</th>
                    <th scope="col">Action</th>
                </thead>
                <tbody>
                @forelse($reports as $report)
                  <tr>
                    <td data-label="Test Names" scope="row">{{$report->test_names}}</td>
                    @if(auth()->user()->user_type=='patient')
                        <td data-label="Provider Name">{{$report->doctor}}</td>
                    @else
                        <td data-label="Patient Name">{{$report->patient}}</td>
                    @endif
                    <td data-label="Result Status">{{$report->type}}</td>
                    <td data-label="Specimen Collection Date">{{$report->specimen_date}}</td>
                    <td data-label="Result Date">{{$report->result_date}}</td>
                    <td data-label="Action"><a href="/patient/view/lab/result/{{$report->id}}" target="_blank" class="btn btn-primary">
                        <button class="orders-view-btn">View</button>
                    </a></td>
                  </tr>
                  @empty
                  <tr>
                  <td colspan="6">
                          <div class="m-auto text-center for-empty-div">
                            <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                            <h6>No Reports To Show</h6>
                          </div>
                        </td>
                </tr>
                  @endforelse
                </tbody>
              </table>
              {{$reports->links('pagination::bootstrap-4')}}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection
