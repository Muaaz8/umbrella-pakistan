@extends('layouts.dashboard_doctor')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>UHCS - Doctor Lab Reports</title>
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
            <div class="d-flex align-items-baseline justify-content-between flex-wrap p-0">
              <h3>Patients Lab Reports</h3>
              <div class="p-0">
                <div class="input-group">
                  <input
                    type="text"
                    id="search"
                    class="form-control"
                    placeholder="Search"
                    aria-label="Username"
                    aria-describedby="basic-addon1"
                  />
                </div>
              </div>
            </div>
            <div class="wallet-table table-responsive">
              <table class="table" id="table">
                <thead>
                        <!-- <th width="50">Sr. #</th> -->
                        <th scope="col">Test Name/(s)</th>
                        <th scope="col">Patient Name</th>
                        <th scope="col">Result Status</th>
                        <th scope="col">Specimen Collection Date</th>
                        <th scope="col">Result Date</th>
                        <!-- <th width="550" scope="col">Out of Range Values</th> -->
                        <th  scope="col">Action</th>
                </thead>
                <tbody>
                @forelse($reports as $report)
                    {{-- <!-- <td width="20%">{{$report->id}}</td> --> --}}
                    <td data-label="Test Name/(s)">{{$report->test_names}}</td>
                    <td data-label="Patient Name">{{$report->patient}}</td>
                    <td data-label="Result Status">{{$report->type}}</td>
                    <td data-label="Specimen Collection Date">{{$report->specimen_date}}</td>
                    <td data-label="Result Date">{{$report->result_date}}</td>
                    {{--@if($report->out_of_range!='No out of range value')
                    <td  width="20%" style="color:red;font-weight:bold">{{$report->out_of_range}}</td>
                    @else
                    <td width="20%">{{$report->out_of_range}}</td>
                    @endif
                    --}}<td data-label="Action">
                        <a href="{{url(\App\Helper::get_files_url($report->file))}}" target="_blank" class="orders-view-btn">
                            View Full Report
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                  <td colspan="6">
                          <div class="m-auto text-center for-empty-div">
                            <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                            <h6>No Patients Lab Reports</h6>
                          </div>
                        </td>
                </tr>
                @endforelse
                  {{-- <tr>
                    <th scope="row">UMB3841</th>
                    <td>2022-08-25 06:04 PM</td>
                    <td>ACACIA (T19) IGE</td>
                    <td class="lab-app-td-icon">
                      <button class="orders-view-btn">View Patient</button
                      ><i class="fa-solid fa-circle-xmark"></i>
                      <i class="fa-solid fa-circle-check"></i>
                    </td>
                  </tr> --}}
                </tbody>
              </table>
              <div class="row d-flex justify-content-center">
                <div class="paginateCounter">
                    {{ $reports->links('pagination::bootstrap-4') }}
                </div>
            </div>
              {{-- <nav aria-label="..." class="float-end pe-3">
                <ul class="pagination">
                  <li class="page-item disabled">
                    <span class="page-link">Previous</span>
                  </li>
                  <li class="page-item">
                    <a class="page-link" href="#">1</a>
                  </li>
                  <li class="page-item active" aria-current="page">
                    <span class="page-link">2</span>
                  </li>
                  <li class="page-item">
                    <a class="page-link" href="#">3</a>
                  </li>
                  <li class="page-item">
                    <a class="page-link" href="#">Next</a>
                  </li>
                </ul>
              </nav> --}}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
@endsection
