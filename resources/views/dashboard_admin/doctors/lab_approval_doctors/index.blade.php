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
                        <div class="d-flex align-items-end p-0">
                            <div class="row">
                                <div>
                                    <h3>Lab Approval Doctors</h3>
                                </div>
                            </div>
                        </div>
                        <div class="wallet-table table-responsive">
                            <table class="table dataTable">
                                <thead>
                                    <tr>
                                        <th scope="col">First Name</th>
                                        <th scope="col">Last Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">State</th>
                                        <th scope="col">UPIN</th>
                                        <th scope="col">NPI</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($doctors as $doctor)
                                        <tr>
                                            <td data-label="Name">{{ $doctor->name }} </td>
                                            <td data-label="Name">{{ $doctor->last_name }} </td>
                                            <td data-label="Email">{{ $doctor->email }}</td>
                                            <td data-label="State">{{ $doctor->state }}</td>
                                            <td data-label="UPIN">{{ $doctor->upin }}</td>
                                            <td data-label="NPI">{{ $doctor->nip_number }}</td>
                                            <td data-label="Status">{{ ucwords($doctor->lab_status) }}</td>
                                            <td data-label="Action">
                                                <a class="btn process-pay" href="{{ route('deactive_doctor_for_lab',['id' => $doctor->id]) }}" id="{{ $doctor->id }}" >Deactivate</a>
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
                                        {{-- {{$doctors->links('pagination::bootstrap-4') }} --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>


    </div>
    {{-- <!-- ------------------Block-Doctor-Modal-start------------------ -->

            <!-- Modal -->
            <div class="modal fade" id="deactivate_doctor" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Deactivate Doctor</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form  method="GET" id="deactive_form">
                    @csrf
                  <div class="modal-body">
                      <div class="delete-modal-body">
                      Are you sure you want to Deactivate this Doctor?
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="submit" class="btn btn-danger">Deactivate</button>
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  </div>
                </form>
              </div>
              </div>
          </div>
  <!-- ------------------Block-Doctor-Modal-end------------------ --> --}}
@endsection
