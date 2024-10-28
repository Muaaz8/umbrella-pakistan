@extends('layouts.dashboard_admin')
@section('meta_tags')
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
@endsection

@section('page_title')
    <title>UHCS - E-Prescription</title>
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
                <h3>E-Prescription</h3>
                <div class="col-md-4 p-0">
                  <div class="input-group">
                    <form action="{{ url('admin/all/prescription') }}" method="POST" style="width: 100%;">
                        @csrf
                        <input type="text"
                        id="search"
                        name="name"
                        class="form-control"
                        placeholder="Search By Order Id"
                        aria-label="Username"
                        aria-describedby="basic-addon1"/>
                    </form>
                  </div>
                </div>
              </div>
              <div class="wallet-table">
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">Order ID</th>
                      <th scope="col">Date</th>
                      <th scope="col">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($med as $medicine)
                    <tr>
                        <td data-label="Order ID">{{ $medicine->order_main_id }}</td>
                        <td data-label="Date">{{ $medicine->created_at }}</td>
                        <td data-label="Action"><a target="_blank"
                                href="{{\App\Helper::get_files_url($medicine->filename) }}">
                                <button class="orders-view-btn">E-Prescription</button>
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
