@extends('layouts.dashboard_admin')
@section('meta_tags')
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
@endsection

@section('page_title')
    <title>UHCS - Physical Locations</title>
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
                            <h3>All Physical Locations</h3>
                            <div class="p-0">
                                <div class="input-group">
                                    <a href="{{ route('admin_add_physical_location') }}" class="btn process-pay">Add new</a>
                                </div>
                            </div>
                        </div>
                      <div class="wallet-table">
                        <table class="table">
                          <thead>
                            <tr>
                              <th scope="col">#</th>
                              <th scope="col">Name</th>
                              <th scope="col">Phone Number</th>
                              <th scope="col">Zip Code</th>
                              <th scope="col">State</th>
                              <th scope="col">City</th>
                              <th scope="col">Latitude</th>
                              <th scope="col">Longitude</th>
                              <th scope="col">Status</th>
                              <th scope="col">Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            @forelse($data as $key => $dt)
                              <tr>
                                <td data-label="#">{{ ++$key }}</td>
                                <td data-label="Name">{{$dt->name}}</td>
                                <td data-label="Phone Number">{{$dt->phone_number}}</td>
                                <td data-label="Zip Code">{{$dt->zipcode}}</td>
                                <td data-label="State">{{$dt->states->name}}</td>
                                <td data-label="City">{{$dt->cities->name}}</td>
                                <td data-label="Latitude">{{$dt->latitude}}</td>
                                <td data-label="Longitude">{{$dt->longitude}}</td>
                                <td data-label="Status">{{$dt->status=='1'?"Active":"Unactive"}}</td>
                                <td data-label="Action">
                                    <div class="dropdown">
                                        <button class="btn option-view-btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                          OPTIONS
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">

                                            <li><a class="dropdown-item" href="/admin/edit/physical/location/{{ $dt->id }}">Edit</a></li>
                                            <li><a class="dropdown-item" href="/admin/delete/physical/location/{{ $dt->id }}">Delete</a></li>
                                        </ul>
                                      </div>
                                </td>
                              </tr>
                              @empty
                              <tr>
                                <td colspan="4">
                                    <div class="m-auto text-center for-empty-div">
                                        <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                        <h6>No Errors To Show</h6>
                                    </div>
                                </td>
                            </tr>
                              @endforelse
                          </tbody>
                        </table>
                        <div class="row d-flex justify-content-center">
                            <div class="paginateCounter">
                                {{ $data->links('pagination::bootstrap-4') }}
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
@endsection
