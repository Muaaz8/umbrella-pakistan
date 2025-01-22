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
                    <div class="d-flex align-items-end p-0">
                        <div class="row">
                            <div>
                                <h3>Doctors pending contracts</h3>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between flex-wrap p-0">
                        <div class="col-12 col-sm-6">
                          <form action="{{ url('/doctors/all/doctors') }}" method="POST">
                              @csrf
                              <input type="hidden" name="id" id="search_spec">
                              <input
                              type="text"
                              id="search"
                              name="name"
                              class="form-control mb-1"
                              placeholder="Search By Name, Email, PMDC number or State"
                              aria-label="Username"
                              aria-describedby="basic-addon1"/>
                          </form>
                      </div>
                    <div class="d-flex justify-content-between flex-wrap p-0">
                        <div class="col-12 col-sm-6">
                        </div>
                        <div class="">
                            <div class="input-group justify-content-end">
                                <!--button---->
                                {{-- <a href="#" class="btn process-pay">PENDING REQUEST</a> --}}
                            </div>
                        </div>
                    </div>
                    <div class="wallet-table table-responsive">
                        <table class="table dataTable">
                            <thead>
                            <tr style="font-size: 14px">
                                <th scope="col">First Name</th>
                                <th scope="col">Last Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">State</th>
                                <th scope="col">UPIN</th>
                                <th scope="col">PMDC</th>
                                <th scope="col">Percentage</th>
                                <th scope="col">Specialization</th>
                                <th scope="col">Registration Date</th>
                                <th scope="col">Contract Status</th>
                            </tr>
                            </thead>
                            <tbody>
                                {{-- {{ dd($doctors) }} --}}
                                @forelse($doctors as $doctor)
                                <tr >
                                    <td data-label="First Name">{{ $doctor->name }} </td>
                                    <td data-label="Last Name">{{ $doctor->last_name }} </td>
                                    <td data-label="Email">{{ $doctor->email }}</td>
                                    <td data-label="State">{{ $doctor->state }}</td>
                                    <td data-label="UPIN">{{ $doctor->upin }}</td>
                                    <td data-label="PMDC">{{ $doctor->nip_number }}</td>
                                    <td data-label="Percentage">{{ $doctor->percentage_doctor  }}</td>
                                    <td data-label="Specialization">{{ $doctor->sp_name }}</td>
                                    <td data-label="Join Date">{{ date('m-d-Y',strtotime($doctor->created_at)) }}</td>

                                    <td data-label="Action">
                                        {{ $doctor->contract_status }}
                                    </td>
                                  </tr>
                                @empty
                                <tr>
                                    <td colspan="8">
                                        <div class="m-auto text-center for-empty-div">
                                            <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                            <h6>No Doctors To Show</h6>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="paginateCounter link-paginate">

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
