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
                <h3>All States</h3>
                <div class="col-md-4 col-sm-6 col-12 p-0">
                  <div class="input-group">
                    <form action="{{ url('/admin/all/state') }}" method="POST" style="width: 100%;">
                        @csrf
                        <input
                        type="text"
                        id="search"
                        name="name"
                        class="form-control"
                        placeholder="Search By Name or State Code"
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
                      <th scope="col">Name</th>
                      <th scope="col">State Code</th>
                      <th scope="col">Status</th>
                      <th scope="col">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($states as $state)
                        <tr>
                            <td data-label="Name">{{ $state->name }}</td>
                            <td data-label="State Code">{{ $state->state_code }}</td>
                            <td data-label="Status">{{ $state->active == 0 ? " Deactive " : "Active" }}</td>
                            <td data-label="Action">
                                @if ($state->active == 0)
                                <a href="{{ route('activate_state', $state->id) }}" title="Activate">
                                    <button class="btn state_act_btn">Activate</button>
                                </a>
                                @else
                                <a href="{{ route('deactivate_state', $state->id) }}" title="Deactivate">
                                    <button class="btn state_deac_btn">Deactive</button>
                                </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                  </tbody>
                </table>
                {{ $states->links('pagination::bootstrap-4') }}
              </div>
            </div>
          </div>
        </div>
      </div>
</div>

@endsection
