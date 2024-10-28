@extends('layouts.dashboard_admin')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>UHCS - Admin Dashboard</title>
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
              <div class="d-flex flex-wrap justify-content-between align-items-baseline p-0">
                <h3>All Coupons</h3>
                <div class="col-md-4 col-sm-6 col-12 p-0">
                  <div class="input-group">
                    {{-- <form action="{{ url('/admin/all/cpn') }}" method="POST" style="width: 100%;">
                        @csrf
                        <input
                        type="text"
                        id="search"
                        name="name"
                        class="form-control"
                        placeholder="Search By Name or cpn Code"
                        aria-label="Username"
                        aria-describedby="basic-addon1"/>
                    </form> --}}
                  </div>
                </div>
              </div>
              <div class="wallet-table">
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">Code</th>
                      <th scope="col">Expiry Date</th>
                      <th scope="col">Status</th>
                      <th scope="col">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($coupons as $cpn)
                        <tr>
                            <td data-label="Name">{{ $cpn->coupon_code }}</td>
                            <td data-label="Coupon Code">{{ $cpn->expiry_date }}</td>
                            <td data-label="Status">{{ $cpn->status }}</td>
                            <td data-label="Action">
                                <a id="{{ $cpn->id }}" href="/admin/coupon/delete/{{ $cpn->id }}" class="delete" id="deleteBtn">
                                    <i class="fa-solid fa-trash-can fs-3 text-danger"></i></a></span>
                             </td>
                        </tr>
                    @endforeach
                  </tbody>
                </table>
                {{-- {{ $coupons->links('pagination::bootstrap-4') }} --}}
              </div>
            </div>
          </div>
        </div>
      </div>
</div>

@endsection
