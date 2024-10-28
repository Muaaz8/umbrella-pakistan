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
                      <div class="d-flex align-items-baseline justify-content-between flex-wrap p-0">
                        <h3>Pending Doctor Requests</h3>
                        <div class="col-md-3 p-0">
                          <div class="input-group">
                            <form action="{{ url('/doctors/pending/doctor/requests') }}" method="POST" style="width: 100%;">
                                @csrf
                                <input
                                type="text"
                                id="search"
                                name="name"
                                class="form-control"
                                placeholder="Search By name or NPI number"
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
                              <th scope="col">State</th>
                              <th scope="col">Registered On</th>
                              <th scope="col">UPIN</th>
                              <th scope="col">NPI</th>
                              <th scope="col">Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            @forelse($doctors as $doc)
                            <tr>
                                <td data-label="Name">{{ "Dr. ".ucfirst($doc->name)." ".ucfirst($doc->last_name) }}</td>
                                <td data-label="State">{{$doc->state_name}}</td>
                                <td data-label="Registered On">{{$doc->created_at}}</td>
                                <td data-label="UPIN">{{$doc->upin}}</td>
                                <td data-label="NPI">{{$doc->nip_number}}</td>
                                <td data-label="Action">
                                    <center>
                                        <a href="{{ route('doctor_pending_request_view',$doc->id) }}">
                                             <button class="btn btn-raised process-pay">View</button>
                                        </a>
                                    </center>

                                </td>
                              </tr>
                              @empty
                              <tr>
                                <td colspan="6">
                                    <div class="m-auto text-center for-empty-div">
                                        <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                        <h6>No Doctors To Show</h6>
                                    </div>
                                </td>
                            </tr>
                              @endforelse
                          </tbody>
                        </table>
                        {{ $doctors->links('pagination::bootstrap-4') }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
        </div>
      </div>


    </div>

@endsection



