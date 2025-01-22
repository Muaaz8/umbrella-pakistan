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
{{-- {{ dd($patients) }} --}}
    <div class="dashboard-content">
        <div class="container-fluid">
            <div class="row m-auto">
                <div class="col-md-12">
                    <div class="row m-auto">
                        <div class="d-flex justify-content-between flex-wrap align-items-baseline p-0">
                            <h3>All Patients</h3>
                            <div class="col-md-4 col-12 col-sm-6 p-0">

                                <div class="input-group">
                                    <form action="{{ url('admin/all/patient') }}" method="POST" style="width: 100%;">
                                        @csrf
                                        <input type="text"
                                        id="search"
                                        name="name"
                                        class="form-control"
                                        placeholder="Search"
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
                                        <th scope="col">User Image</th>
                                        <th scope="col">First Name</th>
                                        <th scope="col">Last Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Last Visit</th>
                                        <th scope="col">Doctors</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($patients as $pat)
                                    <tr>
                                            <td data-label="User Image" class="p-1" scope="row"><img class="patient_img"
                                                    src="{{ \App\Helper::check_bucket_files_url($pat->user_image) }}" alt=""></td>
                                            <td data-label="First Name">{{ ucfirst($pat->name) }}</td>
                                            <td data-label="Last Name">{{ucfirst($pat->last_name) }}</td>
                                            <td data-label="Email">{{ $pat->email }}</td>
                                            <td data-label="Last Visit">{{$pat->last_visit}}</td>
                                            <td data-label="Doctors">@if($pat->doctors!=null)
                                                {{$pat->doctors}}
                                                @else
                                                No doctors
                                                @endif</td>
                                            <td data-label="Action">
                                                <div class="dropdown">
                                                    <button class="btn option-view-btn dropdown-toggle" type="button"
                                                        id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        OPTIONS
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                        <li><a class="dropdown-item" href="{{ route('patient_detailed',['id'=>$pat->id ]) }}">View Details</a></li>
                                                        {{-- <li><a class="dropdown-item" href="#">Payment Info</a></li> --}}
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6">No Patients to Show</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            {{ $patients->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
