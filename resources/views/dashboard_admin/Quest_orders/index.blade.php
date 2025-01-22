@extends('layouts.dashboard_admin')
@section('meta_tags')
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
@endsection

@section('page_title')
    <title>CHCC - Quest Orders</title>
@endsection

@section('top_import_file')
@endsection

@section('bottom_import_file')
@endsection

@section('content')
{{-- {{ dd($requisitions) }} --}}
<div class="dashboard-content">
    <div class="container-fluid">
        <div class="row m-auto">
          <div class="col-md-12">
            <div class="row m-auto">
              <div class="d-flex justify-content-between flex-wrap align-items-baseline  p-0">
                <h3>Quest Orders</h3>
                <div class="col-md-4 p-0">
                  <div class="input-group">
                    <form action="{{ url('admin/all/quest/orders') }}" method="POST" style="width: 100%;">
                        @csrf
                        <input type="text"
                        id="search"
                        name="name"
                        class="form-control"
                        placeholder="Search what are you looking for.."
                        aria-label="Username"
                        aria-describedby="basic-addon1"/>
                    </form>
                  </div>
                </div>
              </div>
              <div class="wallet-table table-responsive">
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">Order ID</th>
                      <th scope="col">Quest Patient ID</th>
                      <th scope="col">Referred Physician ID</th>
                      <th scope="col">UPIN</th>
                      <th scope="col">PMDC</th>
                      <th scope="col">Test Names</th>
                      <th scope="col">Date</th>
                      <th scope="col">Time</th>
                      <th scope="col">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($requisitions as $requisition)
                    <tr>
                        <td data-label="Order ID">{{ $requisition->order_id }}</td>
                        <td data-label="Quest Patient ID">{{ $requisition->quest_patient_id }}</td>
                        <td data-label="Referred Physician ID">{{ $requisition->ref_physician_id }}</td>
                        <td data-label="UPIN">{{ $requisition->upin }}</td>
                        <td data-label="PMDC">{{ $requisition->npi }}</td>
                        <td data-label="Test Names">@foreach($requisition->names as $names)
                            {{$names->testName }}</br>
                            @endforeach</td>
                        <td data-label="Date">{{ explode(" ",$requisition->created_at)[0]." ".explode(" ",$requisition->created_at)[1] }}</td>
                        <td data-label="Time">{{ explode(" ",$requisition->created_at)[2]." ".explode(" ",$requisition->created_at)[3] }}</td>
                        <td data-label="Action"><a target="_blank"
                            href="{{\App\Helper::get_files_url($requisition->requisition_file) }}">
                            <button class="orders-view-btn">Requisition</button></a>
                        </td>
                      </tr>
                      @empty
                    <tr>
                        <td colspan="7">
                            <div class="m-auto text-center for-empty-div">
                                <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                <h6>No Failed Quest Lab To Show</h6>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                  </tbody>
                </table>
                {{ $requisitions->links('pagination::bootstrap-4') }}
              </div>
            </div>
          </div>
        </div>
      </div>
</div>

@endsection
