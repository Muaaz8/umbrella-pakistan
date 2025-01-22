@extends('layouts.dashboard_admin')
@section('meta_tags')
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
@endsection

@section('page_title')
    <title>CHCC - Imaging File</title>
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
              <div class="d-flex justify-content-between flex-wrap align-items-baseline p-0">
                <h3>Imaging File</h3>
              </div>
              <div class="wallet-table">
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">Order ID</th>
                      <th scope="col">Date</th>
                      <th scope="col">Physician</th>
                      <th scope="col">Test Names</th>
                      <th scope="col">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($med as $medicine)
                    @php $index = 1; @endphp
                    <tr>
                        <td data-label="Order ID">{{ $medicine->order_id }}</td>
                        <td data-label="Date">{{ $medicine->created_at }}</td>
                        <td data-label="Date">Dr.{{ $medicine->doc }}</td>
                        <td data-label="Date">
                          @foreach($medicine->names as $names)
                          <strong>{{$index}}) </strong>{{$names->name }}</br>
                          @php $index++; @endphp
                          @endforeach
                        </td>
                        <td data-label="Action"><a target="_blank"
                                href="{{\App\Helper::get_files_url($medicine->filename) }}">
                                <button class="orders-view-btn">Imaging File</button>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3">
                            <div class="m-auto text-center for-empty-div">
                                <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                <h6>No Prescription To Show</h6>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                  </tbody>
                </table>
                {{ $med->links('pagination::bootstrap-4') }}
              </div>
            </div>
          </div>
        </div>
      </div>
</div>

@endsection
