@extends('layouts.dashboard_admin')
@section('meta_tags')
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
@endsection

@section('page_title')
    <title>CHCC - Patient Records</title>
@endsection

@section('top_import_file')
@endsection

@section('bottom_import_file')
@endsection

@section('content')
{{-- {{ dd($fetch) }} --}}
    <div class="dashboard-content">
        <div class="container-fluid">
            <div class="row m-auto">
                <div class="col-md-12">
                    <div class="row m-auto">
                        <div class="d-flex align-items-baseline p-0">
                            <h3>Patient Edit Requests</h3>
                            {{-- <div class="col-md-4 ms-auto p-0">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search what you are looking for"
                                        aria-label="Username" aria-describedby="basic-addon1" />
                                </div>
                            </div> --}}
                        </div>
                        <div class="p-0">
                            <span class="highligted-text">Highlighted areas are requested for update</span>
                        </div>
                        <div class="wallet-table doctor-request-style table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">First Name</th>
                                        <th scope="col">Last Name</th>
                                        <th scope="col">Date Of Birth</th>
                                        <th scope="col">State</th>
                                        <th scope="col">Zip Code</th>
                                        <th scope="col">City</th>
                                        <th scope="col">Address</th>
                                        <th scope="col">Reason</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($fetch as $data)
                                    <tr>
                                        @if ($data->actual->name != $data->name )
                                            <td data-label="First Name" class="position-relative" scope="row"> {{ $data->actual->name }} <br><span
                                                    class="update_doctor_data">{{ $data->name }}</span></td>
                                        @else
                                            <td data-label="First Name"  class="position-relative" scope="row"> {{ $data->actual->name }} </td>
                                        @endif

                                        @if ($data->actual->last_name != $data->last_name )
                                        <td data-label="Last Name" class="position-relative">{{ $data->actual->last_name }}<br><span
                                            class="update_doctor_data">{{ $data->last_name }}</span></td>
                                        @else
                                            <td data-label="Last Name" class="position-relative" scope="row"> {{ $data->actual->last_name }} </td>
                                        @endif

                                        @if ($data->actual->date_of_birth != $data->date_of_birth )
                                        <td data-label="Date Of Birth" class="position-relative">{{ $data->actual->date_of_birth }}<br><span
                                            class="update_doctor_data">{{ $data->date_of_birth }}</span></td>
                                        @else
                                            <td data-label="Date Of Birth" class="position-relative" scope="row"> {{ $data->actual->date_of_birth }} </td>
                                        @endif

                                        @if ($data->actual->state_id != $data->state_id )
                                        <td data-label="State" class="position-relative">{{ $data->actual->state_id }}<br><span
                                        class="update_doctor_data">{{ $data->state_id }}</span></td>
                                        @else
                                            <td data-label="State" class="position-relative" scope="row"> {{ $data->actual->state_id }} </td>
                                        @endif

                                        @if ($data->actual->zip_code != $data->zip_code )
                                        <td data-label="Zip Code" class="position-relative">{{ $data->actual->zip_code }}<br><span
                                        class="update_doctor_data">{{ $data->zip_code }}</span></td>
                                        @else
                                            <td data-label="Zip Code" class="position-relative" scope="row"> {{ $data->actual->zip_code }} </td>
                                        @endif

                                        @if ($data->actual->city_id != $data->city_id )
                                        <td data-label="City" class="position-relative">{{ $data->actual->city_id }}<br><span
                                        class="update_doctor_data">{{ $data->city_id }}</span></td>
                                        @else
                                            <td data-label="City" class="position-relative" scope="row"> {{ $data->actual->city_id }} </td>
                                        @endif

                                        @if ($data->actual->office_address != $data->office_address )
                                        <td data-label="Address" class="position-relative">{{ $data->actual->office_address }}<br><span
                                            class="update_doctor_data">{{ $data->office_address }}</span></td>
                                        @else
                                            <td data-label="Address" class="position-relative" scope="row"> {{ $data->actual->office_address }} </td>
                                        @endif

                                        <td data-label="Reason" class="position-relative" scope="row"> {{ $data->reason }} </td>

                                        <td data-label="Actions" class="align-middle">
                                            <div class="dropdown">
                                                <button class="btn doctor_request_option dropdown-toggle" type="button"
                                                    id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    OPTIONS
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                    <li><a class="dropdown-item" href="{{route('approvePatientRecord',$data->id)}}"
                                                            >Approve</a></li>
                                                    <li><a class="dropdown-item" href="{{ route('adminCancelPatUpdateProfile',$data->id) }}"
                                                            >Reject</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9">
                                            <div class="m-auto text-center for-empty-div">
                                                <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                                <h6>No Data To Show</h6>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
